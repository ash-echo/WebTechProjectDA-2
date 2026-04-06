<?php
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$showId = isset($data['show_id']) ? (int)$data['show_id'] : 0;
// Note: seat_ids can be empty if the user is polling for status, we handle locking separately from polling.
$action = isset($data['action']) ? $data['action'] : 'lock';

if (!$showId) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

if ($action === 'poll') {
    // Return current seat states
    $seats = getSeatStatusForShow($showId);
    echo json_encode(['success' => true, 'seats' => $seats]);
    exit();
}

// Below logic is for LOCKING seats
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must log in first', 'redirect' => 'login.php']);
    exit();
}

$seatIds = isset($data['seat_ids']) ? $data['seat_ids'] : [];
if (empty($seatIds)) {
    echo json_encode(['success' => false, 'message' => 'No seats selected']);
    exit();
}

try {
    $conn = getDBConnection();
    cleanExpiredLocks(); // Delete old locks

    $userId = $_SESSION['user_id'];

    // Start transaction
    $conn->beginTransaction();

    // 1b. Check if ANY of the requested seats are SIMULATED booked
    $simulatedSeats = getSeatStatusForShow($showId);
    foreach ($seatIds as $sId) {
        foreach ($simulatedSeats as $s) {
            if ($s['id'] == $sId && $s['status'] === 'booked') {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'One or more seats have just been booked (simulated).']);
                exit();
            }
        }
    }

    // 1. Check if ANY of the requested seats are REALLY booked
    $placeholders = implode(',', array_fill(0, count($seatIds), '?'));
    $params = array_merge([$showId], $seatIds);


    // 2. Check if ANY of the requested seats are locked by OTHERS
    $stmt = $conn->prepare("
        SELECT seat_id 
        FROM seat_locks 
        WHERE show_id = ? AND seat_id IN ($placeholders) AND expires_at > NOW() AND locked_by != ?
    ");
    $lockParams = array_merge([$showId], $seatIds, [$userId]);
    $stmt->execute($lockParams);
    if ($stmt->fetch()) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'One or more seats are currently locked by someone else.']);
        exit();
    }

    // 3. Clear existing locks for this user for this show
    $stmt = $conn->prepare("DELETE FROM seat_locks WHERE locked_by = ? AND show_id = ?");
    $stmt->execute([$userId, $showId]);

    // 4. Insert new locks
    $insertStmt = $conn->prepare("INSERT INTO seat_locks (seat_id, show_id, locked_by, expires_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))");
    
    foreach ($seatIds as $sId) {
        $insertStmt->execute([$sId, $showId, $userId]);
    }

    // Store selected seats in session
    $_SESSION['selected_seats'] = $seatIds;
    $_SESSION['locked_show_id'] = $showId;

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log('Seat locking error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while locking seats.']);
}
?>
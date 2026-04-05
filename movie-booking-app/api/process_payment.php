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

requireLogin();

$showId = isset($_POST['show_id']) ? (int)$_POST['show_id'] : 0;
$selectedSeatsJson = isset($_POST['selected_seats']) ? $_POST['selected_seats'] : '';
$totalAmount = isset($_POST['total_amount']) ? (float)$_POST['total_amount'] : 0;

if (!$showId || !$selectedSeatsJson || $totalAmount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

$selectedSeatObjs = json_decode($selectedSeatsJson, true);
if (!is_array($selectedSeatObjs) || empty($selectedSeatObjs)) {
    echo json_encode(['success' => false, 'message' => 'Invalid seat selection']);
    exit();
}

// Map if we received array of objects with 'id' or just array of ids
$selectedSeatIds = [];
foreach ($selectedSeatObjs as $s) {
    if (is_array($s) && isset($s['id'])) {
        $selectedSeatIds[] = $s['id'];
    } elseif (is_numeric($s)) {
        $selectedSeatIds[] = $s;
    }
}

try {
    $conn = getDBConnection();
    cleanExpiredLocks();
    
    // Start transaction
    $conn->beginTransaction();
    
    // Verify show exists
    $show = getShowById($showId);
    if (!$show) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Show not found']);
        exit();
    }
    
    // Check if seats are currently locked by the user
    $placeholders = implode(',', array_fill(0, count($selectedSeatIds), '?'));
    $stmt = $conn->prepare("
        SELECT seat_id FROM seat_locks 
        WHERE show_id = ? AND locked_by = ? AND seat_id IN ($placeholders)
    ");
    $params = array_merge([$showId, $_SESSION['user_id']], $selectedSeatIds);
    $stmt->execute($params);
    $lockedSeatIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($lockedSeatIds) !== count($selectedSeatIds)) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Seat lock expired. Please select seats again.']);
        exit();
    }
    
    // Create booking
    $userId = $_SESSION['user_id'];
    $bookingDate = date('Y-m-d H:i:s');
    $bookingId = generateBookingId();
    
    $stmt = $conn->prepare("
        INSERT INTO bookings (id, user_id, show_id, booking_date, total_amount, status) 
        VALUES (?, ?, ?, ?, ?, 'confirmed')
    ");
    $stmt->execute([$bookingId, $userId, $showId, $bookingDate, $totalAmount]);
    
    // Create booking details
    $stmtDetail = $conn->prepare("
        INSERT INTO booking_details (booking_id, seat_id, price) 
        VALUES (?, ?, ?)
    ");
    
    $seatPrice = $show['price'];
    foreach ($selectedSeatIds as $seatId) {
        // Find specific seat price if it was VIP
        $stmtS = $conn->prepare("SELECT seat_type FROM seats WHERE id = ?");
        $stmtS->execute([$seatId]);
        $type = $stmtS->fetchColumn();
        $finalPrice = ($type == 'VIP') ? $seatPrice + 5 : $seatPrice;
        
        $stmtDetail->execute([$bookingId, $seatId, $finalPrice]);
    }
    
    // Delete locks now that they are booked
    $stmt = $conn->prepare("
        DELETE FROM seat_locks
        WHERE show_id = ? AND locked_by = ? AND seat_id IN ($placeholders)
    ");
    $stmt->execute(array_merge([$showId, $_SESSION['user_id']], $selectedSeatIds));
    
    // Clear session data
    unset($_SESSION['selected_seats']);
    unset($_SESSION['locked_show_id']);
    
    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'booking_id' => $bookingId,
        'redirect' => 'booking_confirmation.php?booking_id=' . $bookingId
    ]);
    
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log('Payment processing error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred during payment processing. Details: ' . $e->getMessage()]);
}
?>
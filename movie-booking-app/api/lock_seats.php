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

$data = json_decode(file_get_contents('php://input'), true);
$showId = isset($data['show_id']) ? (int)$data['show_id'] : 0;
$seatIds = isset($data['seat_ids']) ? $data['seat_ids'] : [];

if (!$showId || empty($seatIds)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

try {
    $conn = getDBConnection();
    
    // Start transaction
    $conn->beginTransaction();
    
    // Check if seats are still available
    $placeholders = str_repeat('?,', count($seatIds) - 1) . '?';
    $stmt = $conn->prepare("
        SELECT id, status FROM seats 
        WHERE id IN ($placeholders) AND show_id = ?
    ");
    $params = array_merge($seatIds, [$showId]);
    $stmt->execute($params);
    $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($seats) !== count($seatIds)) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Some seats not found']);
        exit();
    }
    
    foreach ($seats as $seat) {
        if ($seat['status'] !== 'available') {
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Some seats are no longer available']);
            exit();
        }
    }
    
    // Lock the seats
    $userId = $_SESSION['user_id'];
    $lockExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
    $stmt = $conn->prepare("
        UPDATE seats SET status = 'locked', locked_by = ?, locked_until = ? 
        WHERE id IN ($placeholders) AND show_id = ? AND status = 'available'
    ");
    $params = array_merge([$userId, $lockExpiry], $seatIds, [$showId]);
    $stmt->execute($params);
    
    if ($stmt->rowCount() !== count($seatIds)) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to lock some seats']);
        exit();
    }
    
    // Store selected seats in session
    $_SESSION['selected_seats'] = $seatIds;
    $_SESSION['locked_show_id'] = $showId;
    
    $conn->commit();
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollBack();
    }
    error_log('Seat locking error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
?>
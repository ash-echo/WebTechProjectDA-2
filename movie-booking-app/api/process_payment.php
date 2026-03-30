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

$selectedSeats = json_decode($selectedSeatsJson, true);
if (!is_array($selectedSeats) || empty($selectedSeats)) {
    echo json_encode(['success' => false, 'message' => 'Invalid seat selection']);
    exit();
}

try {
    $conn = getDBConnection();
    
    // Start transaction
    $conn->beginTransaction();
    
    // Verify show exists
    $show = getShowById($showId);
    if (!$show) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Show not found']);
        exit();
    }
    
    // Verify seats are still locked by this user
    $placeholders = str_repeat('?,', count($selectedSeats) - 1) . '?';
    $stmt = $conn->prepare("
        SELECT id FROM seats 
        WHERE id IN ($placeholders) AND show_id = ? AND status = 'locked' AND locked_by = ?
    ");
    $params = array_merge($selectedSeats, [$showId, $_SESSION['user_id']]);
    $stmt->execute($params);
    $lockedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($lockedSeats) !== count($selectedSeats)) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Some seats are no longer available']);
        exit();
    }
    
    // Create booking
    $bookingId = generateBookingId();
    $userId = $_SESSION['user_id'];
    $bookingDate = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("
        INSERT INTO bookings (id, user_id, show_id, booking_date, total_amount, status) 
        VALUES (?, ?, ?, ?, ?, 'confirmed')
    ");
    $stmt->execute([$bookingId, $userId, $showId, $bookingDate, $totalAmount]);
    
    // Create booking details
    $stmt = $conn->prepare("
        INSERT INTO booking_details (booking_id, seat_id, price) 
        VALUES (?, ?, ?)
    ");
    
    $seatPrice = $show['price'];
    foreach ($selectedSeats as $seatId) {
        $stmt->execute([$bookingId, $seatId, $seatPrice]);
    }
    
    // Update seats to booked
    $stmt = $conn->prepare("
        UPDATE seats SET status = 'booked', locked_by = NULL, locked_until = NULL 
        WHERE id IN ($placeholders) AND show_id = ? AND status = 'locked' AND locked_by = ?
    ");
    $params = array_merge($selectedSeats, [$showId, $_SESSION['user_id']]);
    $stmt->execute($params);
    
    if ($stmt->rowCount() !== count($selectedSeats)) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to confirm some seats']);
        exit();
    }
    
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
    if (isset($conn)) {
        $conn->rollBack();
    }
    error_log('Payment processing error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred during payment processing']);
}
?>
<?php
header('Content-Type: application/json');
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit();
}

$movieId = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : 0;
$movie = getMovieById($movieId);

if (!$movie || !$movie['is_rentable']) {
    echo json_encode(['success' => false, 'message' => 'Movie not available for rent']);
    exit();
}

$userId = $_SESSION['user_id'];

// Check if already rented
if (isMovieRented($movieId, $userId)) {
    echo json_encode(['success' => true, 'message' => 'You already own this rental!']);
    exit();
}

// Process rental
if (rentMovie($movieId, $userId)) {
    echo json_encode(['success' => true, 'message' => 'Rental successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Transaction failed. Please try again.']);
}
?>

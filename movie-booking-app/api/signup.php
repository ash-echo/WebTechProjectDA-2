<?php
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$terms = isset($_POST['terms']) ? true : false;

if (!$name || !$email || !$password || !$confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit();
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit();
}

if (!$terms) {
    echo json_encode(['success' => false, 'message' => 'You must agree to the terms and conditions']);
    exit();
}

try {
    $conn = getDBConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit();
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phone, password_hash, status, created_at) 
        VALUES (?, ?, ?, ?, 'active', NOW())
    ");
    $stmt->execute([$name, $email, $phone, $passwordHash]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully',
        'redirect' => 'login.php?success=1'
    ]);
    
} catch (Exception $e) {
    error_log('Signup error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred during registration']);
}
?>
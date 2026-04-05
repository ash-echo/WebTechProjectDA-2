<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
        exit();
    }

    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND reset_otp = ? AND otp_expiry > NOW()");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch();

        if ($user) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password_hash = ?, reset_otp = NULL, otp_expiry = NULL WHERE id = ?");
            $update->execute([$hashedPassword, $user['id']]);
            echo json_encode(['success' => true, 'message' => 'Password reset successfully! Redirecting to login.']);
        } else {
            error_log("Password Reset Failed for $email. OTP: $otp (Expired or Invalid)");
            echo json_encode(['success' => false, 'message' => 'Invalid or expired OTP. Session timed out.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
}

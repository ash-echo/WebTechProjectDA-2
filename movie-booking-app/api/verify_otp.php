<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND reset_otp = ? AND otp_expiry > NOW()");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(['success' => true, 'message' => 'OTP verified successfully!']);
        } else {
            // Debug: Log the attempt if it fails (can be seen in PHP error log)
            error_log("OTP Verification Failed for $email. OTP: $otp");
            echo json_encode(['success' => false, 'message' => 'Invalid or expired OTP.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
}

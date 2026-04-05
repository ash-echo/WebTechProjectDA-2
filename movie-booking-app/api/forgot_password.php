<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit();
    }

    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Email not found.']);
            exit();
        }

        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));
        
        // Store OTP with 10-minute expiry
        $stmt = $conn->prepare("UPDATE users SET reset_otp = ?, otp_expiry = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = ?");
        $stmt->execute([$otp, $email]);

        // Send Email
        $to = $email;
        $subject = "Your CINEFLOW Password Reset OTP";
        $message = "
        <html>
        <body style='font-family: sans-serif; background-color: #0f0f0f; color: #eee; padding: 40px;'>
            <h1 style='color: #ff4444;'>Reset Your Password</h1>
            <p>Your one-time password (OTP) for resetting your CINEFLOW account is:</p>
            <div style='background-color: #181818; padding: 20px; font-size: 32px; font-weight: 900; letter-spacing: 10px; color: #fff; text-align: center; border-radius: 12px; margin: 20px 0;'>$otp</div>
            <p>This code will expire in 10 minutes.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        require_once __DIR__ . '/../includes/mail_helper.php';

        $mailResult = sendCineflowEmail($to, $subject, $message);

        if ($mailResult) {
            echo json_encode(['success' => true, 'message' => 'OTP sent successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP email. Check server logs.']);
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

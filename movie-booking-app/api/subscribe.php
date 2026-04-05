<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit();
    }

    // Send email (Simulation/Actual depending on environment)
    $to = $email;
    $subject = "Welcome to the CINEFLOW Director's Cut!";
    $message = "
    <html>
    <head>
        <title>Welcome to CINEFLOW</title>
    </head>
    <body style='font-family: sans-serif; background-color: #0f0f0f; color: #eee; padding: 40px;'>
        <h1 style='color: #ff4444;'>Welcome to the Inner Circle!</h1>
        <p>Thank you for subscribing to <b>The Director's Cut</b>.</p>
        <p>You'll now receive weekly editorial insights, early access to premieres, and member-only screenings in Chennai.</p>
        <br>
        <p>Stay Cinematic,<br>The CINEFLOW Team</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    require_once __DIR__ . '/../includes/mail_helper.php';

    $mailResult = sendCineflowEmail($to, $subject, $message);

    if ($mailResult) {
        echo json_encode(['success' => true, 'message' => 'Thank you for subscribing! Check your inbox.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send confirmation email. Check server logs.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

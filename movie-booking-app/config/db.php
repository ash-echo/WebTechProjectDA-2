<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Global Configuration
if (!defined('BASE_URL')) {
    define('BASE_URL', '/WebTechProject/movie-booking-app/');
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'movie_booking');

// Create PDO connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Close connection (PDO connections close automatically when out of scope)
function closeDBConnection($pdo) {
    // PDO connections are closed automatically when the object goes out of scope
    // This function is kept for compatibility but doesn't need to do anything
}
?>
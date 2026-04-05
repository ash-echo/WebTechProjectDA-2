<?php
require_once __DIR__ . '/config/db.php';

try {
    $conn = getDBConnection();
    
    // Add OTP columns to users table
    $sql = "ALTER TABLE users 
            ADD COLUMN reset_otp VARCHAR(6) NULL,
            ADD COLUMN otp_expiry TIMESTAMP NULL";
    
    $conn->exec($sql);
    echo "Database migrated successfully: Added OTP columns to users table.\n";

} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist. Migration skipped.\n";
    } else {
        echo "Error: " . $e->getMessage();
    }
}

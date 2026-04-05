<?php
// Simple script to import the database schema to make setup easy
$host = 'localhost';
$user = 'root'; // default XAMPP user
$pass = '';     // default XAMPP password

try {
    // Connect to MySQL server first (without database)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Database Setup Tool</h1>";
    echo "Connected successfully to MySQL server.<br>";
    
    // Read the SQL file
    $sqlFile = __DIR__ . '/autodeploy_schema.sql';
    
    // Fallback if not found
    if (!file_exists($sqlFile)) {
        $sqlFile = __DIR__ . '/database_schema_enhanced.sql';
    }

    if (!file_exists($sqlFile)) {
        die("Error: SQL file not found!");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Execute all queries
    $pdo->exec($sql);
    
    echo "<h3>Database `movie_booking` successfully created and mock data imported!</h3>";
    echo "<p><a href='index.php'>Go to Homepage</a></p>";
    
} catch (PDOException $e) {
    die("Setup Error: " . $e->getMessage() . "<br><p>Please make sure MySQL is running in XAMPP control panel.</p>");
}
?>

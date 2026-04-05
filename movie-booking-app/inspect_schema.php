<?php
require_once 'config/db.php';
$conn = getDBConnection();
echo "MOVIES TABLE:\n";
$s = $conn->query("DESCRIBE movies")->fetchAll(PDO::FETCH_ASSOC);
print_r($s);
echo "\nSHOWS TABLE:\n";
$s = $conn->query("DESCRIBE shows")->fetchAll(PDO::FETCH_ASSOC);
print_r($s);

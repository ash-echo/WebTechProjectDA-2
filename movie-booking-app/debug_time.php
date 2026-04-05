<?php
require_once __DIR__ . '/config/db.php';
$conn = getDBConnection();
echo "PHP Time: " . date('Y-m-d H:i:s') . "<br>";
echo "DB Time: " . $conn->query("SELECT NOW()")->fetchColumn() . "<br>";
echo "DB Timezone: " . $conn->query("SELECT @@session.time_zone")->fetchColumn() . "<br>";
echo "Global Timezone: " . $conn->query("SELECT @@global.time_zone")->fetchColumn() . "<br>";

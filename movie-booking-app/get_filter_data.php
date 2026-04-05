<?php
require_once 'config/db.php';
$conn = getDBConnection();
echo "GENRES:\n";
$genres = $conn->query("SELECT DISTINCT genre FROM movies")->fetchAll(PDO::FETCH_COLUMN);
print_r($genres);
echo "\nLANGUAGES:\n";
$langs = $conn->query("SELECT DISTINCT language FROM movies")->fetchAll(PDO::FETCH_COLUMN);
print_r($langs);
echo "\nFORMATS:\n";
$formats = $conn->query("SELECT DISTINCT format FROM movies")->fetchAll(PDO::FETCH_COLUMN);
print_r($formats);

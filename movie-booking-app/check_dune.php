<?php
require_once 'config/db.php';
$conn = getDBConnection();
$stmt = $conn->query("SELECT id, title, backdrop_url, poster_url FROM movies WHERE title LIKE '%Dune%'");
$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($r);

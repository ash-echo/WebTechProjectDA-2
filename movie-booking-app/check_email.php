<?php
require_once 'config/db.php';
$c = getDBConnection();
$r = $c->query('SELECT email FROM users LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
print_r($r);

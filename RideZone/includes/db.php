<?php
// includes/db.php
require_once __DIR__ . '/../config.php';

$host = '127.0.0.1';
$db   = 'ridezone_db';
$user = 'root';
$pass = ''; // set your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo "DB Connection failed: " . $e->getMessage();
    exit;
}

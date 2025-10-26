<?php
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
$cars = $stmt->fetchAll();

echo json_encode($cars);
?>

<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_POST['image'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO cars (name, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $image]);

    echo json_encode(['message' => 'Car added successfully']);
}
?>

<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("UPDATE cars SET name=?, description=?, image=? WHERE id=?");
    $stmt->execute([$name, $description, $image, $id]);

    echo json_encode(['message' => 'Car updated successfully']);
}
?>

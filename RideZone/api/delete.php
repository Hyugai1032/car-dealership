<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM cars WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(['message' => 'Car deleted successfully']);
}
?>

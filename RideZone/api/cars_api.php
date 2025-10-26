<?php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;

  case 'POST':
    $data = $_POST;
    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
      $targetDir = "../assets/uploads/";
      if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
      $imagePath = $targetDir . basename($_FILES['image']['name']);
      move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $stmt = $pdo->prepare("INSERT INTO cars (make, model, type, year, price, dealer_id, description, warranty_info, image)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
      $data['make'], $data['model'], $data['type'], $data['year'], $data['price'],
      $data['dealer_id'], $data['description'], $data['warranty_info'], $imagePath
    ]);

    echo json_encode(['status' => 'success']);
    break;

  case 'PUT':
    parse_str(file_get_contents("php://input"), $data);
    $stmt = $pdo->prepare("UPDATE cars SET make=?, model=?, type=?, year=?, price=?, dealer_id=?, description=?, warranty_info=? WHERE id=?");
    $stmt->execute([
      $data['make'], $data['model'], $data['type'], $data['year'], $data['price'],
      $data['dealer_id'], $data['description'], $data['warranty_info'], $data['id']
    ]);
    echo json_encode(['status' => 'updated']);
    break;

  case 'DELETE':
    parse_str(file_get_contents("php://input"), $data);
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id=?");
    $stmt->execute([$data['id']]);
    echo json_encode(['status' => 'deleted']);
    break;
}
?>

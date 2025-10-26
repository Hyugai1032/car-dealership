<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/sidebar.php';

// ✅ ADD NEW CAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    $image = $_FILES['image']['name'];
    $targetDir = "../assets/cars/";
    $target = $targetDir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $pdo->prepare("INSERT INTO cars (make, model, year, price, type, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$make, $model, $year, $price, $type, $image]);
    echo json_encode(["success" => true, "message" => "Car added successfully!"]);
    exit;
}

// ✅ DELETE CAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);
    exit;
}

// ✅ EDIT CAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $targetDir = "../assets/cars/";
        $target = $targetDir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $stmt = $pdo->prepare("UPDATE cars SET make=?, model=?, year=?, price=?, type=?, image=? WHERE id=?");
        $stmt->execute([$make, $model, $year, $price, $type, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE cars SET make=?, model=?, year=?, price=?, type=? WHERE id=?");
        $stmt->execute([$make, $model, $year, $price, $type, $id]);
    }
    echo json_encode(["success" => true, "message" => "Car updated successfully!"]);
    exit;
}

// 🧾 FETCH CARS
$cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RideZone | Manage Cars</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    background: radial-gradient(circle at top, #0f172a 0%, #1e293b 100%);
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
}
.glass {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
img.car-img {
    width: 100px;
    height: 70px;
    object-fit: cover;
    border-radius: 10px;
}
</style>
</head>

<body class="text-gray-100 flex">

<!-- Sidebar is included automatically -->

<main class="flex-1 p-6 lg:ml-64">
    <h1 class="text-4xl font-bold mb-8 text-indigo-400 drop-shadow-lg text-center lg:text-left">
        🚘 Manage Cars
    </h1>

    <!-- 🔍 Search + Add -->
    <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
        <input type="text" id="search" placeholder="Search cars..."
            class="px-4 py-2 rounded-lg text-gray-800 w-full sm:w-1/3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <button id="addBtn" class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-lg font-semibold">+ Add New Car</button>
    </div>

    <!-- 🚗 Table -->
    <div class="overflow-x-auto glass rounded-lg shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-indigo-600/70 text-white">
                <tr>
                    <th class="p-4">#</th>
                    <th class="p-4">Image</th>
                    <th class="p-4">Make</th>
                    <th class="p-4">Model</th>
                    <th class="p-4">Year</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Type</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="carTableBody">
                <?php foreach ($cars as $i => $car): ?>
                <tr class="border-b border-gray-700 hover:bg-gray-700/40 transition">
                    <td class="p-4"><?= $i + 1 ?></td>
                    <td class="p-4"><img src="../assets/cars/<?= htmlspecialchars($car['image']) ?>" class="car-img"></td>
                    <td class="p-4"><?= htmlspecialchars($car['make']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($car['model']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($car['year']) ?></td>
                    <td class="p-4 text-green-400">$<?= number_format($car['price']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($car['type']) ?></td>
                    <td class="p-4 text-center flex justify-center gap-3">
                        <button class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-3 py-1 rounded-md editBtn"
                                data-id="<?= $car['id'] ?>">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded-md deleteBtn"
                                data-id="<?= $car['id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- 📸 Add/Edit Modal -->
<div id="modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center">
    <div class="glass rounded-lg shadow-2xl p-6 w-full max-w-lg relative">
        <h2 id="modalTitle" class="text-2xl font-bold text-indigo-400 mb-4">Add New Car</h2>
        <form id="carForm" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id" id="carId">
            <input type="hidden" name="action" id="formAction" value="add">

            <input type="text" name="make" id="make" placeholder="Car Make" class="w-full px-4 py-2 rounded text-gray-800" required>
            <input type="text" name="model" id="model" placeholder="Car Model" class="w-full px-4 py-2 rounded text-gray-800" required>
            <input type="number" name="year" id="year" placeholder="Year" class="w-full px-4 py-2 rounded text-gray-800" required>
            <input type="number" name="price" id="price" placeholder="Price" class="w-full px-4 py-2 rounded text-gray-800" required>
            <input type="text" name="type" id="type" placeholder="Type (SUV, Sedan, etc.)" class="w-full px-4 py-2 rounded text-gray-800" required>

            <div>
                <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 bg-gray-700 rounded">
                <img id="preview" class="mt-3 w-24 h-20 object-cover rounded hidden">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="closeModal" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded font-semibold">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
$('#search').on('keyup', function() {
  const val = $(this).val().toLowerCase();
  $('#carTableBody tr').filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
  });
});

$('#addBtn').click(() => {
  $('#modalTitle').text('Add New Car');
  $('#formAction').val('add');
  $('#carForm')[0].reset();
  $('#preview').hide();
  $('#modal').fadeIn();
});
$('#closeModal').click(() => $('#modal').fadeOut());

$('#image').change(function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => $('#preview').attr('src', e.target.result).show();
    reader.readAsDataURL(file);
  }
});

$('#carForm').submit(function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  $.ajax({
    url: 'cars.php',
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: res => {
      const data = JSON.parse(res);
      Swal.fire({ icon: 'success', text: data.message || 'Success!', timer: 1500, showConfirmButton: false });
      setTimeout(() => location.reload(), 1000);
    }
  });
});

$('.deleteBtn').click(function() {
  const id = $(this).data('id');
  Swal.fire({
    title: 'Are you sure?',
    text: 'This will permanently delete the car.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e11d48',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!'
  }).then(result => {
    if (result.isConfirmed) {
      $.post('cars.php', { action: 'delete', id }, () => location.reload());
    }
  });
});

$('.editBtn').click(function() {
  const row = $(this).closest('tr');
  $('#modalTitle').text('Edit Car');
  $('#formAction').val('edit');
  $('#carId').val($(this).data('id'));
  $('#make').val(row.find('td:eq(2)').text());
  $('#model').val(row.find('td:eq(3)').text());
  $('#year').val(row.find('td:eq(4)').text());
  $('#price').val(row.find('td:eq(5)').text().replace('$', '').replace(',', ''));
  $('#type').val(row.find('td:eq(6)').text());
  $('#preview').attr('src', row.find('img').attr('src')).show();
  $('#modal').fadeIn();
});
</script>

</body>
</html>

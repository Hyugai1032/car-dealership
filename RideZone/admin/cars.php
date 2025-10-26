<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/sidebar.php';

// ✅ HANDLE AJAX REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // ADD CAR
    if ($_POST['action'] === 'add') {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $price = $_POST['price'];
        $type = $_POST['type'];

        $image = $_FILES['image']['name'] ?? '';
        if ($image) {
            $targetDir = "../assets/cars/";
            $target = $targetDir . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }

        $stmt = $pdo->prepare("INSERT INTO cars (make, model, year, price, type, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$make, $model, $year, $price, $type, $image]);
        echo json_encode(["success" => true, "message" => "✅ Car added successfully!"]);
        exit;
    }

    // DELETE CAR
    if ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        echo json_encode(["success" => true, "message" => "🗑️ Car deleted successfully!"]);
        exit;
    }

    // EDIT CAR
    if ($_POST['action'] === 'edit') {
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
        echo json_encode(["success" => true, "message" => "✏️ Car updated successfully!"]);
        exit;
    }
}

// FETCH DATA
$cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
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
  width: 100px; height: 70px;
  object-fit: cover; border-radius: 10px;
}
#modal { transition: all 0.3s ease-in-out; }
.swal2-popup {
  border-radius: 1rem !important;
  background: #1e293b !important;
  color: #fff !important;
}
</style>
</head>

<body class="text-gray-100 flex">
<main class="flex-1 p-6 lg:ml-64 transition-all duration-300">
  <h1 class="text-4xl font-bold mb-8 text-indigo-400 text-center lg:text-left">🚘 Manage Cars</h1>

  <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
    <input type="text" id="search" placeholder="Search cars..." class="px-4 py-2 rounded-lg text-gray-800 w-full sm:w-1/3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    <button id="addBtn" class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-lg font-semibold">+ Add New Car</button>
  </div>

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
          <td class="p-4"><?= $i+1 ?></td>
          <td class="p-4"><img src="../assets/cars/<?= htmlspecialchars($car['image']) ?>" class="car-img"></td>
          <td class="p-4"><?= htmlspecialchars($car['make']) ?></td>
          <td class="p-4"><?= htmlspecialchars($car['model']) ?></td>
          <td class="p-4"><?= htmlspecialchars($car['year']) ?></td>
          <td class="p-4 text-green-400">$<?= number_format($car['price']) ?></td>
          <td class="p-4"><?= htmlspecialchars($car['type']) ?></td>
          <td class="p-4 text-center flex justify-center gap-3">
            <button class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-3 py-1 rounded-md editBtn" data-id="<?= $car['id'] ?>">Edit</button>
            <button class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded-md deleteBtn" data-id="<?= $car['id'] ?>">Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black/70 hidden z-50 flex items-center justify-center">
  <div class="glass rounded-2xl shadow-2xl p-8 w-full max-w-lg transform scale-95 opacity-0 transition-all duration-300" id="modalBox">
    <h2 id="modalTitle" class="text-2xl font-bold text-indigo-400 mb-4 text-center">Add New Car</h2>
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
        <img id="preview" class="mt-3 w-24 h-20 object-cover rounded hidden mx-auto">
      </div>
      <div class="flex justify-center gap-3 mt-4">
        <button type="button" id="closeModal" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded">Cancel</button>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded font-semibold">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
/* --------- helper to show error toast --------- */
function showError(title, text) {
  Swal.fire({
    icon: 'error',
    title: title || 'Error',
    text: text || 'Something went wrong.',
    confirmButtonColor: '#ef4444',
    background: '#1e293b',
    color: '#fff'
  });
}

/* --------- SEARCH (unchanged) --------- */
$('#search').on('keyup', function() {
  const val = $(this).val().toLowerCase();
  $('#carTableBody tr').filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
  });
});

/* --------- MODAL OPEN/CLOSE (unchanged but safe) --------- */
function openModal(mode) {
  $('#modalTitle').text(mode === 'add' ? 'Add New Car' : 'Edit Car');
  $('#formAction').val(mode);
  if (mode === 'add') { $('#carForm')[0].reset(); $('#preview').hide(); }
  $('#modal').fadeIn(200).css('display','flex');
  setTimeout(() => $('#modalBox').removeClass('opacity-0 scale-95').addClass('opacity-100 scale-100'), 10);
}
function closeModal() {
  $('#modalBox').removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95');
  setTimeout(() => $('#modal').fadeOut(200), 180);
}
$('#addBtn').click(()=>openModal('add'));
$('#closeModal').click(closeModal);

/* --------- IMAGE PREVIEW (unchanged) --------- */
$('#image').change(function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => $('#preview').attr('src', e.target.result).show();
    reader.readAsDataURL(file);
  }
});

/* --------- LOAD TABLE (must return HTML with #carTableBody for GET) --------- */
function loadCars() {
  $.get('cars.php')
    .done(function(data){
      // data is full HTML when GET; extract rows
      try {
        const newRows = $(data).find('#carTableBody').html();
        if (newRows) {
          $('#carTableBody').html(newRows);
        } else {
          // server returned something unexpected
          showError('Load failed', 'Could not find table rows in response.');
          console.warn('loadCars response:', data);
        }
      } catch (err) {
        showError('Load failed', 'Error parsing server response.');
        console.error(err, data);
      }
    })
    .fail(function(xhr, status, err){
      showError('Network error', 'Failed to load cars. Check your connection or server.');
      console.error('loadCars fail:', status, err, xhr.responseText);
    });
}

/* --------- ADD/EDIT SUBMIT (POST) with JSON response expected --------- */
$('#carForm').submit(function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  $.ajax({
    url: 'cars.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json', // expect JSON
    timeout: 10000,
    success: function(res) {
      // res should be a JSON object from server
      if (!res || typeof res !== 'object') {
        showError('Server error', 'Invalid server response (not JSON). Open console for details.');
        console.log('Non-JSON response:', res);
        return;
      }

      if (res.success) {
        closeModal();
        Swal.fire({ icon: 'success', title: 'Success', text: res.message || 'Saved.', timer: 1400, showConfirmButton: false }).then(()=> {
          // refresh only the table rows (no full reload)
          loadCars();
        });
      } else {
        showError('Failed', res.message || 'Operation failed.');
        console.warn('Server returned failure:', res);
      }
    },
    error: function(xhr, status, err) {
      // helpful error reporting
      let msg = 'Request failed. See console for details.';
      if (xhr && xhr.responseText) {
        // try to show server-side error text (useful during development)
        msg = (xhr.responseText.length < 400 ? xhr.responseText : 'Server error (check logs).');
      }
      showError('Request error', msg);
      console.error('carForm AJAX error:', status, err, xhr.responseText);
    }
  });
});

/* --------- DELETE (with JSON response expected) --------- */
$(document).on('click', '.deleteBtn', function() {
  const id = $(this).data('id');
  Swal.fire({
    title: 'Delete this car?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    confirmButtonColor: '#e11d48'
  }).then(result => {
    if (!result.isConfirmed) return;
    $.ajax({
      url: 'cars.php',
      method: 'POST',
      data: { action: 'delete', id },
      dataType: 'json',
      success: function(res) {
        if (res && res.success) {
          Swal.fire({ icon: 'success', title: 'Deleted', text: res.message || 'Car removed.', timer: 1100, showConfirmButton: false });
          setTimeout(loadCars, 700);
        } else {
          showError('Delete failed', res && res.message ? res.message : 'Unknown server response.');
          console.warn('Delete response:', res);
        }
      },
      error: function(xhr, status, err) {
        showError('Delete error', 'Unable to delete. Check connection or server.');
        console.error('Delete error:', status, err, xhr.responseText);
      }
    });
  });
});

/* --------- EDIT button handler (open modal + prefill) --------- */
$(document).on('click', '.editBtn', function() {
  const row = $(this).closest('tr');
  $('#modalTitle').text('Edit Car');
  $('#formAction').val('edit');
  $('#carId').val($(this).data('id'));
  $('#make').val(row.find('td:eq(2)').text().trim());
  $('#model').val(row.find('td:eq(3)').text().trim());
  $('#year').val(row.find('td:eq(4)').text().trim());
  $('#price').val(row.find('td:eq(5)').text().replace('$','').replace(',','').trim());
  $('#type').val(row.find('td:eq(6)').text().trim());
  $('#preview').attr('src', row.find('img').attr('src')).show();

  $('#modal').fadeIn(200).css('display','flex');
  setTimeout(() => $('#modalBox').removeClass('opacity-0 scale-95').addClass('opacity-100 scale-100'), 10);
});

/* --------- INITIAL LOAD (in case you want to refresh after page load) --------- */
$(function(){
  // if you want to fetch fresh rows on load, uncomment:
  // loadCars();

  // quick check to see if required libs loaded
  if (typeof $ === 'undefined') {
    alert('jQuery failed to load. Check your internet/CDN or include a local copy of jQuery.');
  }
  if (typeof Swal === 'undefined') {
    alert('SweetAlert2 failed to load. Check your internet/CDN or include a local copy of SweetAlert2.');
  }
});
</script>

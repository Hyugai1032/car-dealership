<?php
// includes/header.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>RideZone Admin</title>
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
</head>
<body class="bg-slate-900 text-slate-100">
<aside class="fixed top-0 left-0 w-64 h-screen bg-gradient-to-b from-blue-600 to-blue-700 text-white p-6">
  <div class="flex items-center mb-8">
    <img src="../assets/image/car_icon.png" alt="RideZone" class="w-8 h-8 mr-2">
    <h1 class="text-2xl font-bold">RideZone</h1>
  </div>

  <nav class="flex flex-col space-y-4">
    <a href="dashboard.php" class="hover:bg-blue-500 px-3 py-2 rounded transition">🏠 Dashboard</a>
    <a href="dealers.php" class="hover:bg-blue-500 px-3 py-2 rounded transition">🚗 Dealers</a>
    <a href="cars.php" class="hover:bg-blue-500 px-3 py-2 rounded transition">🚘 Cars</a>
    <a href="appointments.php" class="hover:bg-blue-500 px-3 py-2 rounded transition">📅 Appointments</a>
  </nav>
</aside>

  <main class="max-w-7xl mx-auto p-6">

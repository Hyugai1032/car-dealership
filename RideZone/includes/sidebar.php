<!-- includes/sidebar.php -->
<aside id="sidebar" class="fixed lg:static left-0 top-0 h-full w-64 bg-slate-800 text-white shadow-lg z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
  <div class="p-6 border-b border-slate-700 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-indigo-400">🚗 RideZone</h2>
    <button id="closeSidebar" class="lg:hidden text-slate-400 hover:text-white text-xl">&times;</button>
  </div>
  <nav class="p-6 space-y-3 text-slate-300">
    <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">📊 Dashboard</a>
    <a href="cars.php" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">🚘 Cars</a>
    <a href="#" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">🤝 Dealers</a>
    <a href="#" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">📅 Appointments</a>
    <a href="../logout.php" class="block py-2 px-4 rounded bg-red-600 text-white hover:bg-red-700 mt-4">🚪 Logout</a>
  </nav>
</aside>

<!-- Sidebar Toggle Button (for mobile) -->
<button id="openSidebar" class="fixed lg:hidden top-4 left-4 bg-indigo-600 text-white px-3 py-2 rounded shadow-md z-50">
  ☰
</button>

<script>
const sidebar = document.getElementById("sidebar");
const openBtn = document.getElementById("openSidebar");
const closeBtn = document.getElementById("closeSidebar");

openBtn.addEventListener("click", () => {
  gsap.to(sidebar, { x: 0, duration: 0.5, ease: "power2.out" });
});
closeBtn.addEventListener("click", () => {
  gsap.to(sidebar, { x: "-100%", duration: 0.5, ease: "power2.in" });
});
</script>

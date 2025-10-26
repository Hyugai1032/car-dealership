<!-- includes/sidebar.php -->
<aside id="sidebar" 
    class="fixed left-0 top-0 h-screen w-64 bg-slate-800 text-white shadow-lg z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col justify-between">
    
    <!-- Top Section -->
    <div>
        <div class="p-6 border-b border-slate-700 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-indigo-400">🚗 RideZone</h2>
            <button id="closeSidebar" class="lg:hidden text-slate-400 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        
        <nav class="p-6 space-y-3 text-slate-300">
            <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">📊 Dashboard</a>
            <a href="cars.php" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">🚘 Cars</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">🤝 Dealers</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">📅 Appointments</a>
        </nav>
    </div>
    
    <!-- Logout (Fixed Bottom) -->
    <div class="p-6 border-t border-slate-700">
        <a href="../logout.php" class="block w-full py-2 px-4 rounded bg-red-600 text-center hover:bg-red-700">
            🚪 Logout
        </a>
    </div>
</aside>

<!-- Sidebar Toggle Button (for mobile) -->
<button id="openSidebar" 
    class="block lg:hidden fixed top-4 left-4 bg-indigo-600 text-white px-3 py-2 rounded shadow-md z-[60] focus:outline-none">
    ☰
</button>


<script>
const sidebar = document.getElementById("sidebar");
const openBtn = document.getElementById("openSidebar");
const closeBtn = document.getElementById("closeSidebar");

// Open sidebar
openBtn.addEventListener("click", () => {
    sidebar.classList.remove("-translate-x-full");
});

// Close sidebar
closeBtn.addEventListener("click", () => {
    sidebar.classList.add("-translate-x-full");
});

// Close sidebar when clicking outside (mobile only)
document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !openBtn.contains(e.target) && window.innerWidth < 1024) {
        sidebar.classList.add("-translate-x-full");
    }
});
</script>

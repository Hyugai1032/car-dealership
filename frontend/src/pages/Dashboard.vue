

<template>
  <main class="flex-1 p-8 lg:ml-64 space-y-10">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-extrabold">Admin Dashboard</h1>
        <p class="text-slate-400 text-lg">Welcome back — here's a quick overview.</p>
      </div>
      <button id="toggleTheme" class="px-5 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600">
        Toggle Theme
      </button>
    </div>

    <!-- HERO SHOWCASE (larger car slideshow) -->
    <section id="car-showcase" class="relative rounded-3xl overflow-hidden shadow-2xl h-[400px] lg:h-[480px] flex items-center justify-between px-10">
      <div id="showcase-bg" class="absolute inset-0 transition-all duration-700 z-0"></div>
      <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between w-full gap-10">
        <div class="flex-1 text-center lg:text-left">
          <h2 id="car-name" class="text-5xl font-bold text-white mb-4 drop-shadow-md"></h2>
          <p id="car-desc" class="text-lg text-slate-200 max-w-md mx-auto lg:mx-0"></p>
          <div class="mt-8 flex justify-center lg:justify-start gap-4">
            <button id="prevBtn" class="px-5 py-2 rounded bg-white/20 hover:bg-white/30">◀ Prev</button>
            <button id="nextBtn" class="px-5 py-2 rounded bg-white/20 hover:bg-white/30">Next ▶</button>
            <button id="pauseBtn" class="px-5 py-2 rounded bg-white/10 hover:bg-white/20">Pause</button>
          </div>
        </div>
        <div class="flex-1 flex justify-center">
          <img id="car-image" src="" alt="Car" class="w-full max-w-md object-contain drop-shadow-2xl transition-opacity duration-700 rounded-2xl" />
        </div>
      </div>
    </section>

    <!-- DASHBOARD STATS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="card p-6 rounded-xl shadow text-center">
        <div class="text-slate-400 text-sm">Total Cars</div>
        <div id="totalCars" class="text-4xl font-bold text-blue-500 mt-2">--</div>
      </div>
      <div class="card p-6 rounded-xl shadow text-center">
        <div class="text-slate-400 text-sm">Dealers</div>
        <div id="totalDealers" class="text-4xl font-bold text-emerald-500 mt-2">--</div>
      </div>
      <div class="card p-6 rounded-xl shadow text-center">
        <div class="text-slate-400 text-sm">Pending Appointments</div>
        <div id="pendingAppointments" class="text-4xl font-bold text-yellow-500 mt-2">--</div>
      </div>
      <div class="card p-6 rounded-xl shadow text-center">
        <div class="text-slate-400 text-sm">Users</div>
        <div id="totalUsers" class="text-4xl font-bold text-pink-500 mt-2">--</div>
      </div>
    </div>

    <!-- ACTIVITY + CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="card p-6 rounded-xl shadow lg:col-span-1">
        <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
        <ul id="recentActivities" class="text-slate-400 text-sm space-y-2">
          <li>Loading...</li>
        </ul>
      </div>

      <div class="card p-6 rounded-xl shadow lg:col-span-2 grid grid-cols-2 gap-6">
        <div>
          <h3 class="font-semibold mb-3">Car Types Distribution</h3>
          <canvas id="typesChart" height="200"></canvas>
        </div>
        <div>
          <h3 class="font-semibold mb-3">Monthly Sales</h3>
          <canvas id="salesChart" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="card p-5 rounded-xl shadow mt-8 text-center">
      <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
      <div class="flex justify-center gap-4 flex-wrap">
        <a href="cars.php" class="px-4 py-2 bg-blue-600 rounded-lg text-white hover:bg-blue-700">Cars</a>
        <a href="#" onclick="alert('Dealers page coming soon!')" class="px-4 py-2 bg-emerald-600 rounded-lg text-white hover:bg-emerald-700">Dealers</a>
        <a href="#" onclick="alert('Appointments page coming soon!')" class="px-4 py-2 bg-yellow-500 rounded-lg text-white hover:bg-yellow-600">Appointments</a>
      </div>
    </div>
  </main>
</template>

<script setup>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
import { ref } from "vue";

const cars = [
  {
    name: "Ferrari 488 Spider",
    desc: "A high-performance convertible blending luxury and power.",
    img: "https://i.imgur.com/H3RkxM4.png",
    bg: "linear-gradient(to right, #a83232, #f24d4d)",
  },
  {
    name: "Lamborghini Huracán",
    desc: "Pure adrenaline on wheels with an unmistakable design.",
    img: "https://i.imgur.com/oX2sJx7.png",
    bg: "linear-gradient(to right, #f5a623, #f76b1c)",
  },
  {
    name: "Porsche 911 Turbo S",
    desc: "Iconic sports car engineered for dynamic precision.",
    img: "https://i.imgur.com/z3a2uYV.png",
    bg: "linear-gradient(to right, #1e3c72, #2a5298)",
  },
];

const currentIndex = ref(0);
const currentCar = ref(cars[currentIndex.value]);

function nextCar() {
  currentIndex.value = (currentIndex.value + 1) % cars.length;
  currentCar.value = cars[currentIndex.value];
}

function prevCar() {
  currentIndex.value =
    (currentIndex.value - 1 + cars.length) % cars.length;
  currentCar.value = cars[currentIndex.value];
}

const stats = [
  { label: "Total Cars", value: "128", color: "#2563eb" },
  { label: "Dealers", value: "32", color: "#16a34a" },
  { label: "Appointments", value: "75", color: "#f59e0b" },
  { label: "Sales", value: "58", color: "#dc2626" },
];
</script>

<style>
body.dark {
      background-color: #0f172a;
      color: #e2e8f0;
    }
    body.dark .card {
      background-color: #1e293b;
    }
    body.light {
      background-color: #f8fafc;
      color: #0f172a;
    }
    body.light .card {
      background-color: #e2e8f0;
    }
    .card {
      transition: all 0.3s ease-in-out;
    }
</style>



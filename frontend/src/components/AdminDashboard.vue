<template>
  <div :class="['transition-colors duration-500 flex', theme]">
    <!-- Header -->
    <main class="flex-1 p-8 lg:ml-64 space-y-10">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-extrabold">Admin Dashboard</h1>
          <p class="text-slate-400 text-lg">Welcome back — here's a quick overview.</p>
        </div>
        <button
          @click="toggleTheme"
          class="px-5 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600"
        >
          Toggle Theme
        </button>
      </div>

      <!-- HERO SHOWCASE -->
      <section
        id="car-showcase"
        class="relative rounded-3xl overflow-hidden shadow-2xl h-[400px] lg:h-[480px] flex items-center justify-between px-10"
      >
        <div
          :style="{ background: cars[currentCar].bg }"
          class="absolute inset-0 transition-all duration-700 z-0"
        ></div>

        <div
          class="relative z-10 flex flex-col lg:flex-row items-center justify-between w-full gap-10"
        >
          <div class="flex-1 text-center lg:text-left">
            <h2 class="text-5xl font-bold text-white mb-4 drop-shadow-md">
              {{ cars[currentCar].name }}
            </h2>
            <p class="text-lg text-slate-200 max-w-md mx-auto lg:mx-0">
              {{ cars[currentCar].desc }}
            </p>
            <div class="mt-8 flex justify-center lg:justify-start gap-4">
              <button
                @click="prevCar"
                class="px-5 py-2 rounded bg-white/20 hover:bg-white/30"
              >
                ◀ Prev
              </button>
              <button
                @click="nextCar"
                class="px-5 py-2 rounded bg-white/20 hover:bg-white/30"
              >
                Next ▶
              </button>
              <button
                @click="pauseSlide"
                class="px-5 py-2 rounded bg-white/10 hover:bg-white/20"
              >
                Pause
              </button>
            </div>
          </div>
          <div class="flex-1 flex justify-center">
            <img
              :src="cars[currentCar].img"
              :alt="cars[currentCar].name"
              class="w-full max-w-md object-contain drop-shadow-2xl transition-opacity duration-700 rounded-2xl"
            />
          </div>
        </div>
      </section>

      <!-- STATS -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="stat in stats" :key="stat.label" class="card p-6 rounded-xl shadow text-center">
          <div class="text-slate-400 text-sm">{{ stat.label }}</div>
          <div :class="['text-4xl font-bold mt-2', stat.color]">{{ stat.value }}</div>
        </div>
      </div>

      <!-- ACTIVITIES & CHARTS -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="card p-6 rounded-xl shadow lg:col-span-1">
          <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
          <ul class="text-slate-400 text-sm space-y-2">
            <li v-for="(activity, i) in recentActivities" :key="i">{{ activity }}</li>
          </ul>
        </div>

        <div class="card p-6 rounded-xl shadow lg:col-span-2 grid grid-cols-2 gap-6">
          <div>
            <h3 class="font-semibold mb-3">Car Types Distribution</h3>
            <canvas ref="typesChart"></canvas>
          </div>
          <div>
            <h3 class="font-semibold mb-3">Monthly Sales</h3>
            <canvas ref="salesChart"></canvas>
          </div>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <div class="card p-5 rounded-xl shadow mt-8 text-center">
        <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
        <div class="flex justify-center gap-4 flex-wrap">
          <button class="px-4 py-2 bg-blue-600 rounded-lg text-white hover:bg-blue-700">
            Cars
          </button>
          <button
            @click="comingSoon('Dealers page coming soon!')"
            class="px-4 py-2 bg-emerald-600 rounded-lg text-white hover:bg-emerald-700"
          >
            Dealers
          </button>
          <button
            @click="comingSoon('Appointments page coming soon!')"
            class="px-4 py-2 bg-yellow-500 rounded-lg text-white hover:bg-yellow-600"
          >
            Appointments
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { Chart } from "chart.js/auto";
import gsap from "gsap";

// THEME
const theme = ref(localStorage.getItem("theme") || "dark");
const toggleTheme = () => {
  theme.value = theme.value === "dark" ? "light" : "dark";
  localStorage.setItem("theme", theme.value);
};

// CAR SLIDESHOW
const cars = reactive([
  {
    name: "Tesla Model S",
    desc: "Electric performance with luxury.",
    img: "/image/tesla.png",
    bg: "linear-gradient(90deg,#6366f1,#a855f7)",
  },
  {
    name: "Lamborghini Huracán",
    desc: "Supercar speed and Italian design.",
    img: "/image/lamborghini_huracan.png",
    bg: "linear-gradient(90deg,#f59e0b,#ef4444)",
  },
  {
    name: "BMW M4",
    desc: "Precision engineering and raw power.",
    img: "/image/bmw_m4.png",
    bg: "linear-gradient(90deg,#3b82f6,#06b6d4)",
  },
]);

let currentCar = ref(0);
let autoSlide;

const nextCar = () => {
  currentCar.value = (currentCar.value + 1) % cars.length;
  gsap.to(".car-image", { opacity: 0, duration: 0.5 });
};
const prevCar = () => {
  currentCar.value = (currentCar.value - 1 + cars.length) % cars.length;
};
const pauseSlide = () => clearInterval(autoSlide);

// STATS
const stats = reactive([
  { label: "Total Cars", value: 72, color: "text-blue-500" },
  { label: "Dealers", value: 11, color: "text-emerald-500" },
  { label: "Pending Appointments", value: 8, color: "text-yellow-500" },
  { label: "Users", value: 342, color: "text-pink-500" },
]);

// ACTIVITIES
const recentActivities = ref([
  "User John purchased a Tesla.",
  "New dealer 'MotorMax' registered.",
  "Appointment scheduled for 3PM.",
  "BMW M4 restocked.",
  "Lamborghini Huracán reserved.",
]);

// CHARTS
const typesChart = ref(null);
const salesChart = ref(null);

onMounted(() => {
  autoSlide = setInterval(() => nextCar(), 4000);

  new Chart(typesChart.value, {
    type: "doughnut",
    data: {
      labels: ["SUV", "Sedan", "Truck", "Sports"],
      datasets: [
        {
          data: [35, 45, 10, 25],
          backgroundColor: ["#3b82f6", "#10b981", "#f59e0b", "#ef4444"],
        },
      ],
    },
    options: { plugins: { legend: { position: "bottom" } } },
  });

  new Chart(salesChart.value, {
    type: "bar",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
      datasets: [
        {
          label: "Sales",
          data: Array.from({ length: 8 }, () => Math.floor(Math.random() * 100)),
          backgroundColor: "#6366f1",
        },
      ],
    },
    options: { scales: { y: { beginAtZero: true } } },
  });
});

const comingSoon = (msg) => alert(msg);
</script>

<style scoped>
body.dark {
  background-color: #0f172a;
  color: #e2e8f0;
}
body.light {
  background-color: #f8fafc;
  color: #0f172a;
}
.card {
  background-color: var(--card-bg);
  transition: all 0.3s ease-in-out;
}
</style>



<template>
  <div class="dashboard">
    <Header />

    <!-- Hero Section -->
    <section id="car-showcase" class="hero-section">
      <div :style="{ background: currentCar.bg }" class="hero-bg"></div>

      <div class="hero-content">
        <div class="hero-text">
          <h2>{{ currentCar.name }}</h2>
          <p>{{ currentCar.desc }}</p>
          <div class="hero-buttons">
            <button @click="prevCar">◀ Prev</button>
            <button @click="nextCar">Next ▶</button>
          </div>
        </div>

        <div class="hero-image">
          <img :src="currentCar.img" alt="Car" />
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <div class="stats-grid">
      <div v-for="stat in stats" :key="stat.label" class="stat-card">
        <div class="stat-label">{{ stat.label }}</div>
        <div class="stat-value" :style="{ color: stat.color }">{{ stat.value }}</div>
      </div>
    </div>

    <Footer />
  </div>
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

<style scoped>
.dashboard {
  padding: 2rem;
  margin-left: 16rem;
}

.hero-section {
  position: relative;
  border-radius: 24px;
  overflow: hidden;
  height: 400px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
  margin-bottom: 2.5rem;
}

.hero-bg {
  position: absolute;
  inset: 0;
  transition: all 0.7s ease;
  z-index: 0;
}

.hero-content {
  position: relative;
  z-index: 10;
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
  width: 100%;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
}

@media (min-width: 1024px) {
  .hero-content {
    flex-direction: row;
  }
}

.hero-text {
  flex: 1;
  text-align: center;
}

@media (min-width: 1024px) {
  .hero-text {
    text-align: left;
  }
}

.hero-text h2 {
  font-size: 3rem;
  font-weight: bold;
  color: white;
  margin-bottom: 1rem;
}

.hero-text p {
  font-size: 1.125rem;
  color: #e2e8f0;
  max-width: 28rem;
  margin: 0 auto;
}

.hero-buttons {
  margin-top: 2rem;
  display: flex;
  gap: 1rem;
  justify-content: center;
}

@media (min-width: 1024px) {
  .hero-buttons {
    justify-content: flex-start;
  }
}

.hero-buttons button {
  padding: 0.5rem 1.25rem;
  border: none;
  border-radius: 8px;
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
  cursor: pointer;
  transition: background 0.3s;
}

.hero-buttons button:hover {
  background-color: rgba(255, 255, 255, 0.3);
}

.hero-image {
  flex: 1;
  display: flex;
  justify-content: center;
}

.hero-image img {
  width: 100%;
  max-width: 400px;
  object-fit: contain;
  border-radius: 16px;
  filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
}

.stats-grid {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.stat-label {
  color: #94a3b8;
  font-size: 0.875rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: bold;
  margin-top: 0.5rem;
}
</style>



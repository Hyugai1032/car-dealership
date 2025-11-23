<template>
  <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
    <h1 class="text-2xl font-semibold text-red-600">RideZone</h1>
    <nav class="space-x-6 text-gray-700 font-medium flex items-center">
      <router-link to="/home" class="text-red-600 border-b-2 border-red-600">Home</router-link>
      <router-link to="/cars-page" class="hover:text-red-600 ">Cars</router-link>

      <router-link
        to="/car-comparison"
        class="relative hover:text-red-600 flex items-center gap-1 font-bold"
      >
        Compare
        <span
          v-if="compareIds?.length"
          class="absolute -top-3 -right-4 bg-red-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center animate-pulse"
        >
          {{ compareIds.length }}
        </span>
      </router-link>

      <router-link to="/about" class="hover:text-red-600">About</router-link>
      <router-link to="/contact" class="hover:text-red-600">Contact</router-link>

      <button
        @click="$router.push({ name: 'login' })"
        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
      >
        Login
      </button>
    </nav>
  </header>

  <!-- Add padding top so hero is not hidden -->
  <section class="hero pt-24">
    <div class="overlay">
      <div class="search-card">
        <h1>Find Your Next Car</h1>
        <p>Search and compare new or used vehicles from trusted dealers.</p>

        <div class="tabs">
          <button
            class="tab"
            :class="{ active: selectedTab === 'new' }"
            @click="selectedTab = 'new'"
          >
            New Cars
          </button>

          <button
            class="tab"
            :class="{ active: selectedTab === 'used' }"
            @click="selectedTab = 'used'"
          >
            Used Cars
          </button>
        </div>

        <div class="search-row">
          <input
            type="text"
            placeholder="Search make, model, or variant..."
            v-model="searchQuery"
          />
          <button class="search-btn">Search</button>
        </div>
      </div>
    </div>
  </section>
</template>


<script setup>
import { ref } from 'vue'

const selectedTab = ref("new")
const searchQuery = ref("")
</script>

<style scoped>
.hero {
  background-image: url("https://images.unsplash.com/photo-1503376780353-7e6692767b70");
  background-size: cover;
  background-position: center;
  height: 85vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.overlay {
  width: 100%;
  height: 100%;
  background: rgba(185, 183, 183, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

/* TRANSPARENT SEARCH CARD */
.search-card {
  background: rgba(255, 255, 255, 0.376);
  backdrop-filter: blur(12px);
  padding: 2.5rem 3rem;
  border-radius: 18px;
  max-width: 700px;
  width: 100%;
  text-align: center;
  box-shadow: 0px 12px 35px rgba(0,0,0,0.25);
}

h1 {
  font-size: 2.4rem;
  font-weight: 700;
  color: #111;
  margin-bottom: 0.4rem;
}

p {
  font-size: 1.05rem;
  color: #444;
  margin-bottom: 1.5rem;
}

/* Tabs */
.tabs {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 1.2rem;
}

/* GRAY & RED BUTTON STYLES */
.tab {
  padding: 0.6rem 1.4rem;
  border: none;
  background: #f2f2f2;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  color: #333;
  transition: 0.3s;
}

.tab.active {
  background: #c1121f;    /* RED */
  color: white;
}

/* Search row */
.search-row {
  display: flex;
  gap: 0.8rem;
}

.search-row input {
  flex: 1;
  padding: 0.75rem 1rem;
  border: 1px solid #ccc;
  font-size: 1rem;
  border-radius: 8px;
}

/* Search button - red */
.search-btn {
  background: #c1121f;
  color: white;
  font-weight: 600;
  padding: 0 1.6rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.25s;
}

.search-btn:hover {
  background: #a10f19;
}
</style>
  
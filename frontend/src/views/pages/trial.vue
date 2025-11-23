<template>
  <div class="cars-page bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
      <h1 class="text-2xl font-semibold text-red-600">RideZone</h1>
      <nav class="space-x-6 text-gray-700 font-medium flex items-center">
        <a href="#" class="hover:text-red-600">Home</a>
        <a href="#" class="text-red-600 border-b-2 border-red-600">Cars</a>
        <router-link to="/car-comparison" class="relative hover:text-red-600 flex items-center gap-1 font-bold">
          Compare
          <span v-if="compareIds.length" class="absolute -top-3 -right-4 bg-red-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center animate-pulse">
            {{ compareIds.length }}
          </span>
        </router-link>
        <a href="#" class="hover:text-red-600">About</a>
        <a href="#" class="hover:text-red-600">Contact</a>
        <button @click="$router.push({ name: 'login' })" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
          Login
        </button>
      </nav>
    </header>

    <!-- Main Content -->
    <div class="flex-1 p-6">
      <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <aside class="md:col-span-1 bg-white p-6 rounded-2xl shadow-lg space-y-5">
          <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Filter Cars</h2>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
            <select v-model="filters.make" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500">
              <option value="">All Makes</option>
              <option v-for="make in carMakes" :key="make" :value="make">{{ make }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
            <select v-model="filters.year" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500">
              <option value="">All Years</option>
              <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
            <input v-model.number="filters.maxPrice" type="range" min="300000" max="5000000" step="100000" class="w-full">
            <div class="flex justify-between text-xs text-gray-600">
              <span>₱300K</span>
              <span>₱{{ (filters.maxPrice || 5000000)/1000000 }}M+</span>
            </div>
          </div>

          <button @click="resetFilters" class="w-full bg-gray-200 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
            Reset Filters
          </button>
        </aside>

        <!-- Car Listings -->
        <main class="md:col-span-3">
          <h1 class="text-4xl font-bold text-gray-800 mb-8">Available Cars</h1>

          <div v-if="cars.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="car in cars" :key="car.id" class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition relative group">
              
              <!-- COMPARE CHECKBOX (Floating Top Right) -->
              <label class="absolute top-4 right-4 z-10 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg flex items-center gap-2 cursor-pointer border-2 border-gray-200">
                <input 
                  type="checkbox" 
                  :value="car.id" 
                  v-model="compareIds"
                  class="w-5 h-5 text-red-600 rounded focus:ring-red-500"
                >
                <span class="font-bold text-sm" :class="compareIds.includes(car.id) ? 'text-red-600' : 'text-gray-600'">
                  {{ compareIds.includes(car.id) ? 'Added' : 'Compare' }}
                </span>
              </label>

              <!-- Car Image -->
              <img 
                :src="getCarImage(car.main_image)" 
                alt="Car" 
                class="w-full h-56 object-cover group-hover:scale-105 transition"
                @error="e => e.target.src = '/default-car.jpg'"
              >

              <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-800">{{ car.make }} {{ car.model }}</h3>
                <p class="text-gray-600">{{ car.variant }} • {{ car.year }}</p>
                <p class="mt-3 text-3xl font-bold text-red-600">₱{{ Number(car.price).toLocaleString() }}</p>
                
                <div class="mt-4 flex flex-wrap gap-2 text-sm">
                  <span class="px-3 py-1 bg-gray-100 rounded-full">{{ car.transmission }}</span>
                  <span class="px-3 py-1 bg-gray-100 rounded-full">{{ car.fuel_type }}</span>
                  <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full">{{ car.status }}</span>
                </div>

                <!-- Book Button -->
                <button @click="openModal(car.id)" class="w-full mt-6 bg-red-600 text-white py-4 rounded-xl hover:bg-red-700 transition font-bold text-lg">
                  Book Test Drive
                </button>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-20 text-gray-500 text-xl">
            No cars found. Try adjusting your filters.
          </div>
        </main>
      </div>
    </div>

    <!-- Appointment Modal -->
    <AppointmentModal 
      :show="showModal" 
      :carId="selectedCar" 
      @close="showModal = false"
    />

    <!-- Footer -->
    <footer class="bg-white border-t p-6 text-center text-gray-600 mt-auto">
      © 2025 RideZone. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import AppointmentModal from '../../components/Appointments/AppointmentModal.vue'

// === COMPARE SYSTEM (2 CARS ONLY) ===
const compareIds = ref([])

// Load from localStorage
onMounted(() => {
  const saved = localStorage.getItem('ridezone_compare')
  if (saved) {
    compareIds.value = JSON.parse(saved).slice(0, 2)
  }
})

// Auto-save to localStorage
watch(compareIds, (newVal) => {
  localStorage.setItem('ridezone_compare', JSON.stringify(newVal.slice(0, 2)))
}, { deep: true })

// === CARS & FILTERS ===
const cars = ref([])
const filters = ref({
  make: '',
  year: '',
  maxPrice: 5000000
})

const carMakes = ['Toyota', 'Honda', 'Mitsubishi', 'Ford', 'Nissan', 'Hyundai', 'Suzuki']
const years = Array.from({ length: 15 }, (_, i) => 2025 - i)

// Fetch cars
async function fetchCars() {
  try {
    const url = new URL('/api/searchcars', window.location.origin)
    url.searchParams.append('limit', 20)
    if (filters.value.make) url.searchParams.append('make', filters.value.make)
    if (filters.value.year) url.searchParams.append('year', filters.value.year)
    if (filters.value.maxPrice < 5000000) url.searchParams.append('maxPrice', filters.value.maxPrice)

    const res = await fetch(url)
    const data = await res.json()
    cars.value = data.cars || []
  } catch (err) {
    console.error('Failed to load cars')
  }
}

// Watch filters
watch(filters, fetchCars, { deep: true })

// Reset filters
function resetFilters() {
  filters.value = { make: '', year: '', maxPrice: 5000000 }
}

// Load on mount
onMounted(() => {
  fetchCars()
})

// Image helper
const getCarImage = (path) => {
  if (!path) return '/default-car.jpg'
  return path.startsWith('http') ? path : `http://localhost:8000${path.startsWith('/') ? '' : '/'}${path}`
}

// Appointment Modal
const showModal = ref(false)
const selectedCar = ref(null)
function openModal(carId) {
  selectedCar.value = carId
  showModal.value = true
}
</script>

<style scoped>
.animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
</style>
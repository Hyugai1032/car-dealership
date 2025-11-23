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

    <!-- Main content -->
    <div class="flex flex-col md:flex-row flex-1 p-6 gap-6">
      <!-- Filters Sidebar -->
      <aside class="md:w-1/3 lg:w-1/4 bg-white p-5 rounded-xl shadow-md space-y-6">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Filter Cars</h2>

        <!-- Make -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
          <select v-model="filters.make" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-red-200">
            <option value="">All Makes</option>
            <option v-for="make in carMakes" :key="make" :value="make">{{ make }}</option>
          </select>
        </div>

        <!-- Year -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
          <select v-model="filters.year" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-red-200">
            <option value="">All Years</option>
            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>

        <!-- Price Range -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Price Range (₱)</label>
          <div class="flex items-center gap-2 mb-2">
            <input v-model.number="filters.minPrice" type="number" placeholder="Min" class="w-full border rounded-lg px-3 py-2 text-sm">
            <span class="text-gray-400">—</span>
            <input v-model.number="filters.maxPrice" type="number" placeholder="Max" class="w-full border rounded-lg px-3 py-2 text-sm">
          </div>
          <input type="range" v-model.number="filters.maxPrice" min="0" :max="maxPossiblePrice" step="100000" class="w-full">
          <div class="text-xs text-gray-500 mt-1 text-right">Up to ₱{{ filters.maxPrice?.toLocaleString() || '5M' }}</div>
        </div>

        <!-- Transmission -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Transmission</label>
          <div class="flex gap-3">
            <button @click="filters.transmission = 'automatic'"
              :class="filters.transmission === 'automatic' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700'"
              class="flex-1 py-2 rounded-lg border hover:bg-red-600 hover:text-white transition">
              Automatic
            </button>
            <button @click="filters.transmission = 'manual'"
              :class="filters.transmission === 'manual' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700'"
              class="flex-1 py-2 rounded-lg border hover:bg-red-600 hover:text-white transition">
              Manual
            </button>
          </div>
        </div>

        <!-- Fuel Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Type</label>
          <div class="grid grid-cols-2 gap-3">
            <label v-for="fuel in fuelTypes" :key="fuel" class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="filters.fuelTypes" :value="fuel" class="w-4 h-4 text-red-600 rounded focus:ring-red-500">
              <span class="text-sm">{{ fuel }}</span>
            </label>
          </div>
        </div>

        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <input v-model="search" type="text" placeholder="Make, model, year..." class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-red-200 outline-none">
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
          <button @click="resetFilters" class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition">
            Reset
          </button>
          <button @click="fetchCars" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
            Apply Filters
          </button>
        </div>
      </aside>

      <!-- Car Listings -->
      <main class="flex-1">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Available Cars ({{ totalCars }})</h1>

        <div v-if="cars.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="car in cars" :key="car.id" class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition relative group">

            <!-- Compare Checkbox -->
            <label class="absolute top-4 right-4 z-10 bg-white/95 backdrop-blur px-4 py-2 rounded-full shadow-lg flex items-center gap-2 cursor-pointer border-2 border-gray-200">
              <input type="checkbox" :value="car.id" v-model="compareIds"
                class="w-5 h-5 text-red-600 rounded focus:ring-red-500"
                :disabled="!compareIds.includes(car.id) && compareIds.length >= 2">
              <span class="font-bold text-sm" :class="compareIds.includes(car.id) ? 'text-red-600' : 'text-gray-600'">
                {{ compareIds.includes(car.id) ? 'Added' : 'Compare' }}
              </span>
            </label>

            <!-- Image with Lightbox -->
            <img :src="getCarImage(car.main_image)"
                 alt="Car"
                 class="w-full h-52 object-cover cursor-zoom-in transition-transform group-hover:scale-105"
                 @click="openLightbox(car.main_image)"
                 @error="onImageError">

            <div class="p-5">
              <h2 class="text-xl font-bold text-gray-800">{{ car.make }} {{ car.model }}</h2>
              <p class="text-sm text-gray-500">{{ car.variant }} • {{ car.year }}</p>
              <p class="mt-2 text-gray-600 text-sm line-clamp-2">{{ car.description }}</p>

              <div class="mt-4 flex items-center justify-between">
                <p class="text-2xl font-bold text-red-600">₱{{ Number(car.price).toLocaleString() }}</p>
                <span class="px-3 py-1 text-xs rounded-full"
                      :class="car.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                  {{ car.status }}
                </span>
              </div>
            </div>

            <button @click="openModal(car.id)"
                    class="w-full bg-red-600 text-white py-3 font-medium hover:bg-red-700 transition">
              Book Appointment
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20 text-gray-500">
          <p class="text-xl">No cars found matching your criteria.</p>
          <p class="mt-2">Try adjusting the filters or search term.</p>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.total_pages > 1" class="flex justify-center items-center mt-12 gap-3">
          <button @click="changePage(pagination.page - 1)" :disabled="pagination.page === 1"
                  class="px-5 py-2 rounded-lg border disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
            Previous
          </button>
          <span class="text-gray-600">Page {{ pagination.page }} of {{ pagination.total_pages }}</span>
          <button @click="changePage(pagination.page + 1)" :disabled="pagination.page === pagination.total_pages"
                  class="px-5 py-2 rounded-lg border disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
            Next
          </button>
        </div>
      </main>
    </div>

    <!-- Modals -->
    <AppointmentModal :show="showModal" :carId="selectedCar" :userId="userId"
                      @close="showModal = false" @saved="onAppointmentSaved" />

    <VueEasyLightbox :visible="lightboxVisible" :imgs="lightboxImages" :index="lightboxIndex"
                     @hide="lightboxVisible = false" scrollDisabled moveDisabled />

    <!-- Footer -->
    <footer class="bg-white border-t p-6 text-center text-sm text-gray-600 mt-auto">
      © 2025 RideZone. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { debounce } from 'lodash-es' // optional: npm i lodash-es
import VueEasyLightbox from 'vue-easy-lightbox'
import AppointmentModal from '../../components/Appointments/AppointmentModal.vue'

// === Reactive State ===
const cars = ref([])
const totalCars = ref(0)
const search = ref('')
const filters = ref({
  make: '',
  year: '',
  minPrice: null,
  maxPrice: null,
  transmission: '',
  fuelTypes: []
})

const pagination = ref({
  page: 1,
  limit: 9, // changed to 9 for nicer 3×3 grid
  total_pages: 1
})

// Static lists
const carMakes = ['Toyota', 'Honda', 'Ford', 'BMW', 'Nissan', 'Mitsubishi', 'Hyundai', 'Suzuki']
const years = Array.from({ length: 20 }, (_, i) => new Date().getFullYear() - i)
const fuelTypes = ['Gasoline', 'Diesel', 'Electric', 'Hybrid']
const maxPossiblePrice = 10000000

// Appointment
const showModal = ref(false)
const selectedCar = ref(null)
const userId = 1 // TODO: replace with real auth

// === Compare (max 2 cars) ===
const compareIds = ref([])

onMounted(() => {
  const saved = localStorage.getItem('ridezone_compare')
  if (saved) compareIds.value = JSON.parse(saved).slice(0, 2)
})

watch(compareIds, (newVal) => {
  const limited = newVal.slice(0, 2)
  if (limited.length !== newVal.length) {
    compareIds.value = limited
    alert('You can compare up to 2 cars only.')
  }
  localStorage.setItem('ridezone_compare', JSON.stringify(limited))
}, { deep: true })

// === Lightbox ===
const lightboxVisible = ref(false)
const lightboxImages = ref([])
const lightboxIndex = ref(0)

const openLightbox = (imagePath) => {
  const url = getCarImage(imagePath)
  lightboxImages.value = [url]
  lightboxIndex.value = 0
  lightboxVisible.value = true
}

// === Image Helpers ===
const getCarImage = (path) => {
  if (!path) return '/default-car.jpg'
  if (path.startsWith('http') || path.startsWith('data:')) return path
  return path.startsWith('/') ? path : `/${path}`
}

const onImageError = (e) => {
  e.target.src = '/default-car.jpg'
}

// === API ===
const fetchCars = async () => {
  try {
    const url = new URL('/api/searchcars', window.location.origin)

    url.searchParams.append('page', pagination.value.page)
    url.searchParams.append('limit', pagination.value.limit)

    if (search.value.trim()) url.searchParams.append('search', search.value.trim())

    Object.entries(filters.value).forEach(([key, val]) => {
      if (val === null || val === '') return
      if (Array.isArray(val) && val.length === 0) return
      url.searchParams.append(key, Array.isArray(val) ? val.join(',') : val)
    })

    const res = await fetch(url)
    const data = await res.json()

    if (data.status === 'success') {
      cars.value = data.cars || []
      pagination.value.total_pages = data.pagination?.total_pages || 1
      totalCars.value = data.pagination?.total || 0
    } else {
      cars.value = []
      totalCars.value = 0
    }
  } catch (err) {
    console.error('Fetch cars error:', err)
    cars.value = []
  }
}

// Debounced search (optional but recommended)
const debouncedFetch = debounce(fetchCars, 400)

watch(search, () => {
  pagination.value.page = 1
  debouncedFetch()
})

// Deep watch filters
watch(filters, () => {
  pagination.value.page = 1
  fetchCars()
}, { deep: true })

const changePage = (page) => {
  if (page < 1 || page > pagination.value.total_pages) return
  pagination.value.page = page
  fetchCars()
  window.scrollTo(0, 0)
}

const resetFilters = () => {
  filters.value = {
    make: '', year: '', minPrice: null, maxPrice: null,
    transmission: '', fuelTypes: []
  }
  search.value = ''
  pagination.value.page = 1
  fetchCars()
}

// Modal handlers
const openModal = (carId) => {
  selectedCar.value = carId
  showModal.value = true
}

const onAppointmentSaved = () => {
  // refresh list if needed
}

// Initial load
onMounted(fetchCars)
</script>

<style scoped>
.cars-page { font-family: 'Poppins', sans-serif; }
.line-clamp-2 { 
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
:deep(.vel-img) { max-height: 90vh !important; object-fit: contain; }
</style>
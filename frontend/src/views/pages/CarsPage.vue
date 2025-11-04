<template> 
  <div class="cars-page bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
      <h1 class="text-2xl font-semibold text-red-600">LuxuryCars</h1>
      <nav class="space-x-6 text-gray-700 font-medium">
        <a href="#" class="hover:text-red-600">Home</a>
        <a href="#" class="text-red-600 border-b-2 border-red-600">Cars</a>
        <a href="#" class="hover:text-red-600">About</a>
        <a href="#" class="hover:text-red-600">Contact</a>
        <button class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700 transition">Logout</button>
      </nav>
    </header>

    <!-- Main content -->
    <div class="flex flex-col md:flex-row flex-1 p-6 gap-6">
      <!-- Filters Sidebar -->
      <aside class="md:w-1/3 lg:w-1/4 bg-white p-5 rounded-xl shadow-md space-y-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Filter Cars</h2>

        <!-- Make -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
          <select v-model="filters.make" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-red-200">
            <option value="">All</option>
            <option v-for="make in carMakes" :key="make" :value="make">{{ make }}</option>
          </select>
        </div>

        <!-- Year -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
          <select v-model="filters.year" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-red-200">
            <option value="">All</option>
            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>

        <!-- Price -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Price Range (₱)</label>
          <div class="flex items-center gap-2">
            <input v-model.number="filters.minPrice" type="number" placeholder="Min" class="w-1/2 border rounded-lg px-2 py-1 focus:ring-2 focus:ring-red-200">
            <input v-model.number="filters.maxPrice" type="number" placeholder="Max" class="w-1/2 border rounded-lg px-2 py-1 focus:ring-2 focus:ring-red-200">
          </div>
          <input v-model.number="filters.maxPrice" type="range" min="0" max="5000000" step="50000" class="w-full mt-2">
        </div>

        <!-- Transmission -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Transmission</label>
          <div class="flex gap-2">
            <button
              @click="filters.transmission = 'automatic'"
              :class="filters.transmission === 'automatic' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700'"
              class="px-3 py-1 rounded-lg border hover:bg-red-600 hover:text-white transition"
            >
              Automatic
            </button>
            <button
              @click="filters.transmission = 'manual'"
              :class="filters.transmission === 'manual' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700'"
              class="px-3 py-1 rounded-lg border hover:bg-red-600 hover:text-white transition"
            >
              Manual
            </button>
          </div>
        </div>

        <!-- Fuel Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Type</label>
          <div class="flex flex-wrap gap-3">
            <label v-for="fuel in fuelTypes" :key="fuel" class="flex items-center gap-1">
              <input type="checkbox" v-model="filters.fuelTypes" :value="fuel">
              <span class="text-gray-700 text-sm">{{ fuel }}</span>
            </label>
          </div>
        </div>

        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <input v-model="search" type="text" placeholder="Search make, model, or year..." class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-red-200 outline-none">
        </div>

        <!-- Reset Filters -->
        <button @click="resetFilters" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
          Reset Filters
        </button>
      </aside>

      <!-- Car Listings -->
      <main class="flex-1">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Available Cars</h1>
        <div v-if="cars.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="car in cars" :key="car.id" class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition relative">
            <img :src="car.main_image || '/default-car.jpg'" alt="Car image" class="w-full h-48 object-cover">
            <div class="p-4">
              <h2 class="text-lg font-semibold text-gray-800">{{ car.make }} {{ car.model }}</h2>
              <p class="text-sm text-gray-500">{{ car.variant }} • {{ car.year }}</p>
              <p class="mt-2 text-gray-700 text-sm">{{ car.description }}</p>
              <p class="mt-3 font-semibold text-red-600">₱{{ Number(car.price).toLocaleString() }}</p>
              <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full"
                :class="car.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                {{ car.status }}
              </span>
            </div>

            <!-- ✅ Appointment Button -->
                <button
                @click="openModal(car.id)"
                class="mt-3 w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition"
                >
                Book Appointment
                </button>
          </div>
        </div>

        <div v-else class="text-center text-gray-500 mt-10">No cars found. Try adjusting your filters.</div>

        <!-- Pagination -->
        <div v-if="pagination.total_pages > 1" class="flex justify-center items-center mt-8 space-x-2">
          <button @click="changePage(pagination.page - 1)" :disabled="pagination.page === 1" class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-200 disabled:opacity-40">Prev</button>
          <span class="text-gray-600 font-medium">Page {{ pagination.page }} of {{ pagination.total_pages }}</span>
          <button @click="changePage(pagination.page + 1)" :disabled="pagination.page === pagination.total_pages" class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-200 disabled:opacity-40">Next</button>
        </div>
      </main>
    </div>

    <!-- ✅ Appointment Modal -->
        <AppointmentModal
        :show="showModal"
        :carId="selectedCar"
        :userId="userId"
        @close="showModal = false"
        @saved="onAppointmentSaved"
        />

    <!-- ✅ Appointment List -->
    <AppointmentList :user-id="userId" ref="appointmentList" class="p-6" />

    <!-- Footer -->
    <footer class="bg-white border-t p-4 text-center text-sm text-gray-600">
      © 2025 LuxuryCars. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import AppointmentModal from '../../components/Appointments/AppointmentModal.vue'
import AppointmentList from '../../components/Appointments/AppointmentList.vue'

const cars = ref([])
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
  limit: 6,
  total_pages: 1,
})

const carMakes = ['Toyota', 'Honda', 'Ford', 'BMW', 'Nissan']
const years = Array.from({ length: 15 }, (_, i) => 2025 - i)
const fuelTypes = ['Gasoline', 'Diesel', 'Electric', 'Hybrid']

// Appointment Modal Controls
const showModal = ref(false)
const selectedCar = ref(null)
const appointmentList = ref(null)
const userId = 1 // Replace with logged-in user ID

function openModal(carId) {
  selectedCar.value = carId
  showModal.value = true
}

function onAppointmentSaved() {
  appointmentList.value?.fetchAppointments()
}

// Fetch Cars
async function fetchCars() {
  try {
    const url = new URL('http://localhost:8000/searchcars')
    url.searchParams.append('page', pagination.value.page)
    url.searchParams.append('limit', pagination.value.limit)
    if (search.value) url.searchParams.append('search', search.value)

    Object.entries(filters.value).forEach(([key, val]) => {
      if (val && (typeof val !== 'object' || val.length)) {
        url.searchParams.append(key, Array.isArray(val) ? val.join(',') : val)
      }
    })

    const response = await fetch(url)
    const data = await response.json()

    if (data.status === 'success') {
      cars.value = data.cars || []
      pagination.value.total_pages = data.pagination?.total_pages || 1
    } else {
      cars.value = []
    }
  } catch (err) {
    console.error('Failed to load cars:', err)
  }
}

function resetFilters() {
  filters.value = { make: '', year: '', minPrice: null, maxPrice: null, transmission: '', fuelTypes: [] }
  search.value = ''
  fetchCars()
}

function changePage(newPage) {
  if (newPage < 1 || newPage > pagination.value.total_pages) return
  pagination.value.page = newPage
  fetchCars()
}

watch([filters, search], fetchCars, { deep: true })
onMounted(fetchCars)
</script>

<style scoped>
.cars-page {
  font-family: 'Poppins', sans-serif;
}
</style>

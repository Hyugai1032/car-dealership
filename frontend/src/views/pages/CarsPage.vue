<template>
  <div class="cars-page bg-gray-50 min-h-screen p-6">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Available Cars</h1>

    <!-- Search Bar -->
    <div class="flex flex-col sm:flex-row items-center gap-3 mb-6">
      <input
        v-model="search"
        type="text"
        placeholder="Search by make, model, or year..."
        class="w-full sm:w-96 px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 outline-none"
      />
      <button
        @click="fetchCars"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
      >
        Search
      </button>
    </div>

    <!-- Car List -->
    <div v-if="cars.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="car in cars"
        :key="car.id"
        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition"
      >
        <img
          :src="car.main_image || '/default-car.jpg'"
          alt="Car image"
          class="w-full h-48 object-cover"
        />
        <div class="p-4">
          <h2 class="text-lg font-semibold text-gray-800">
            {{ car.make }} {{ car.model }}
          </h2>
          <p class="text-sm text-gray-500">{{ car.variant }} • {{ car.year }}</p>
          <p class="mt-2 text-gray-700 text-sm">{{ car.description }}</p>
          <p class="mt-3 font-semibold text-blue-700">
            ₱{{ Number(car.price).toLocaleString() }}
          </p>
          <span
            class="inline-block mt-2 text-xs px-3 py-1 rounded-full"
            :class="{
              'bg-green-100 text-green-700': car.status === 'available',
              'bg-red-100 text-red-700': car.status !== 'available'
            }"
          >
            {{ car.status }}
          </span>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center text-gray-500 mt-10">
      No cars found. Try adjusting your search.
    </div>

    <!-- Pagination -->
    <div v-if="pagination.total_pages > 1" class="flex justify-center items-center mt-8 space-x-2">
      <button
        @click="changePage(pagination.page - 1)"
        :disabled="pagination.page === 1"
        class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-200 disabled:opacity-40"
      >
        Prev
      </button>

      <span class="text-gray-600 font-medium">
        Page {{ pagination.page }} of {{ pagination.total_pages }}
      </span>

      <button
        @click="changePage(pagination.page + 1)"
        :disabled="pagination.page === pagination.total_pages"
        class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-200 disabled:opacity-40"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const cars = ref([])
const search = ref('')
const pagination = ref({
  page: 1,
  limit: 6,
  total_pages: 1,
})

async function fetchCars() {
  try {
    const url = new URL('http://localhost:8000/searchcars')
    url.searchParams.append('page', pagination.value.page)
    url.searchParams.append('limit', pagination.value.limit)
    if (search.value) url.searchParams.append('search', search.value)

    const response = await fetch(url)
    const data = await response.json()

    if (data.status === 'success') {
      cars.value = data.cars || []
      pagination.value = {
        ...pagination.value,
        total_pages: data.pagination?.total_pages || 1,
      }
    } else {
      cars.value = []
    }
  } catch (err) {
    console.error('Failed to load cars:', err)
  }
}

function changePage(newPage) {
  if (newPage < 1 || newPage > pagination.value.total_pages) return
  pagination.value.page = newPage
  fetchCars()
}

onMounted(fetchCars)
</script>

<style scoped>
.cars-page {
  font-family: 'Poppins', sans-serif;
}
</style>

<template>
  <div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Compare Cars</h1>

    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12">

      <!-- CAR 1 -->
      <div v-if="car1" class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <img :src="getImage(car1.main_image)" class="w-full h-80 object-cover" alt="Car 1">
        <div class="p-8 text-center">
          <h2 class="text-3xl font-bold text-gray-800">{{ car1.make }} {{ car1.model }} {{ car1.variant }}</h2>
          <p class="text-gray-600 text-lg mt-2">{{ car1.year }} • {{ car1.transmission }} • {{ car1.fuel_type }}</p>
          <p class="text-4xl font-bold text-red-600 mt-4">₱{{ Number(car1.price).toLocaleString() }}</p>

          <!-- Warranty (safe check) -->
          <div v-if="car1.warranty" class="mt-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200">
            <p class="font-bold text-green-700 text-lg">{{ car1.warranty.provider || 'Official Warranty' }}</p>
            <p class="text-sm text-gray-700 mt-1">{{ car1.warranty.coverage || 'Full Coverage' }}</p>
            <p class="text-xs text-gray-500 mt-2">Valid until: {{ formatDate(car1.warranty.expiry_date) }}</p>
          </div>
          <div v-else class="mt-6 p-5 bg-gray-100 rounded-2xl">
            <p class="text-gray-500 italic">No warranty information</p>
          </div>
        </div>
      </div>
      <div v-else class="border-4 border-dashed border-gray-300 rounded-3xl h-96 flex items-center justify-center text-gray-400 text-2xl bg-gray-50">
        <div>
          <p>Select First Car</p>
          <p class="text-sm mt-2">Go to Cars page and check a car</p>
        </div>
      </div>

      <!-- CAR 2 -->
      <div v-if="car2" class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <img :src="getImage(car2.main_image)" class="w-full h-80 object-cover" alt="Car 2">
        <div class="p-8 text-center">
          <h2 class="text-3xl font-bold text-gray-800">{{ car2.make }} {{ car2.model }} {{ car2.variant }}</h2>
          <p class="text-gray-600 text-lg mt-2">{{ car2.year }} • {{ car2.transmission }} • {{ car2.fuel_type }}</p>
          <p class="text-4xl font-bold text-red-600 mt-4">₱{{ Number(car2.price).toLocaleString() }}</p>

          <div v-if="car2.warranty" class="mt-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200">
            <p class="font-bold text-green-700 text-lg">{{ car2.warranty.provider || 'Official Warranty' }}</p>
            <p class="text-sm text-gray-700 mt-1">{{ car2.warranty.coverage || 'Full Coverage' }}</p>
            <p class="text-xs text-gray-500 mt-2">Valid until: {{ formatDate(car2.warranty.expiry_date) }}</p>
          </div>
          <div v-else class="mt-6 p-5 bg-gray-100 rounded-2xl">
            <p class="text-gray-500 italic">No warranty information</p>
          </div>
        </div>
      </div>
      <div v-else class="border-4 border-dashed border-gray-300 rounded-3xl h-96 flex items-center justify-center text-gray-400 text-2xl bg-gray-50">
        <div>
          <p>Select Second Car</p>
          <p class="text-sm mt-2">Maximum 2 cars only</p>
        </div>
      </div>
    </div>

    <!-- Clear Button -->
    <div class="text-center mt-12">
      <button @click="clearCompare" class="px-10 py-4 bg-red-600 text-white text-lg font-bold rounded-xl hover:bg-red-700 transition shadow-lg">
        Clear Comparison
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const car1 = ref(null)
const car2 = ref(null)
const loading = ref(true)

onMounted(async () => {
  const saved = localStorage.getItem('ridezone_compare')
  if (!saved) return

  const ids = JSON.parse(saved)
  if (ids.length === 0) return

  try {
    const res = await fetch('http://localhost:8000/api/compare/cars', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ids })
    })
    const data = await res.json()
    
    if (data.status === 'success') {
      car1.value = data.cars[0] || null
      car2.value = data.cars[1] || null
    } else {
      console.error('API error:', data.message)
    }
  } catch (err) {
    console.error('Fetch error:', err)
  }
})

const clearCompare = () => {
  localStorage.removeItem('ridezone_compare')
  router.push('/cars-page') // o '/cars-page'
}

const getImage = (path) => path?.startsWith('http') ? path : `http://localhost:8000${path}`
</script>

<style scoped>
.bg-gradient-to-r { background: linear-gradient(to right, #f0fdf4, #ecfdf5); }
</style>
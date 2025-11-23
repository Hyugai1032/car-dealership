<template>
  <div v-if="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
      <div class="bg-red-600 text-white p-5 text-center">
        <h2 class="text-2xl font-bold">Book Appointment</h2>
      </div>

      <div class="p-6 space-y-6">

        <!-- Date Picker -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
          <input
            v-model="appointmentDate"
            type="date"
            :min="today"
            @change="onDateChange"
            class="w-full border rounded-lg px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
          >
          <p v-if="dateError" class="text-red-600 text-sm font-medium mt-2">
            {{ dateError }}
          </p>
          <p v-if="isDateFullyBooked && !dateError" class="text-orange-600 text-sm mt-2">
            This date is already taken (approved appointment exists).
          </p>
        </div>

        <!-- Time Picker -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Time</label>
          <input
            v-model="appointmentTime"
            type="time"
            :disabled="!appointmentDate || isDateFullyBooked"
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition"
          >
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
          <textarea
            v-model="notes"
            rows="3"
            placeholder="Any special requests or preferred contact time?"
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 resize-none"
          ></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 pt-4">
          <button
            @click="$emit('close')"
            class="flex-1 py-3 rounded-lg border border-gray-300 hover:bg-gray-50 font-medium transition"
          >
            Cancel
          </button>
          <button
            @click="createAppointment"
            :disabled="isBookButtonDisabled"
            class="flex-1 py-3 rounded-lg bg-red-600 text-white font-bold hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition shadow-lg"
          >
            {{ loading ? 'Booking...' : 'Book Appointment' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  show: Boolean,
  carId: Number,
  userId: Number,
})

const emit = defineEmits(['close', 'saved'])

const appointmentDate = ref('')
const appointmentTime = ref('')
const notes = ref('')
const loading = ref(false)
const bookedDates = ref([])
const dateError = ref('')

// Today in YYYY-MM-DD format
const today = new Date().toISOString().split('T')[0]

// REACTIVE: Check if selected date has an approved appointment
const isDateFullyBooked = computed(() => {
  if (!appointmentDate.value) return false
  const selected = appointmentDate.value.trim()
  return bookedDates.value.some(date => date.trim() === selected)
})

// REACTIVE: Book button state
const isBookButtonDisabled = computed(() => {
  return (
    loading.value ||
    !appointmentDate.value ||
    !appointmentTime.value ||
    isDateFullyBooked.value ||
    !!dateError.value
  )
})

// FETCH BOOKED DATES — with cache busting!
async function fetchBookedDates() {
  try {
    console.log('%cFetching booked dates for car_id:', 'color: cyan; font-weight: bold;', props.carId)

    // TAMA NA 'TO NGAYON — gamit ang {car_id} sa URL
    const url = `http://localhost:8000/get-booked-dates/${props.carId}?_=${Date.now()}`
    const res = await fetch(url)
    const data = await res.json()

    console.log('%cBackend Response:', 'color: lime; font-weight: bold;', data)

    if (data.status === 'success') {
      bookedDates.value = data.booked_dates || []
      console.log('%cUpdated bookedDates:', 'color: gold; font-weight: bold;', bookedDates.value)
    } else {
      console.warn('API returned error:', data)
      bookedDates.value = []
    }
  } catch (err) {
    console.error('Failed to fetch booked dates:', err)
    bookedDates.value = []
  }
}

// Reset & refresh when modal opens
watch(
  () => props.show,
  async (newVal) => {
    if (newVal) {
      await fetchBookedDates()  // Always fresh data!
      appointmentDate.value = ''
      appointmentTime.value = ''
      notes.value = ''
      dateError.value = ''
    }
  }
)

// Validate date selection
function onDateChange() {
  dateError.value = ''

  if (!appointmentDate.value) return

  const selected = new Date(appointmentDate.value)
  const now = new Date()
  now.setHours(0, 0, 0, 0)

  // Block past dates
  if (selected < now) {
    dateError.value = 'Cannot select past dates.'
    appointmentDate.value = ''
    return
  }

  // Show warning if already taken
  if (isDateFullyBooked.value) {
    dateError.value = 'This date is no longer available.'
    appointmentTime.value = ''
  }
}

// CREATE APPOINTMENT
async function createAppointment() {
  if (!appointmentDate.value || !appointmentTime.value) {
    alert('Please select date and time.')
    return
  }

  if (isDateFullyBooked.value) {
    alert('This date is already taken.')
    return
  }

  const appointmentAt = `${appointmentDate.value}T${appointmentTime.value}:00`

  try {
    loading.value = true

    const response = await fetch('http://localhost:8000/createappointment', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        car_id: props.carId,
        user_id: props.userId,
        dealer_id: 1,
        appointment_at: appointmentAt,
        notes: notes.value || null,
      }),
    })

    const data = await response.json()

    if (data.message) {
      alert('Appointment booked successfully!')
      emit('saved')
      emit('close')
      
      // CRITICAL: Refresh booked dates even after closing
      await fetchBookedDates()
    } else {
      alert('Failed: ' + (data.error || 'Please try again.'))
    }
  } catch (err) {
    console.error('Booking failed:', err)
    alert('Network error. Please try again.')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
input[type="date"]::-webkit-calendar-picker-indicator {
  cursor: pointer;
  opacity: 0.7;
}
</style>
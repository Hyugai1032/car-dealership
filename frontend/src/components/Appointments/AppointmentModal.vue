<template>
  <div v-if="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-96">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Book Appointment</h2>

      <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
      <input v-model="appointmentDate" type="date" class="w-full border rounded-lg px-3 py-2 mb-3">

      <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
      <input v-model="appointmentTime" type="time" class="w-full border rounded-lg px-3 py-2 mb-3">

      <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
      <textarea v-model="notes" rows="3" class="w-full border rounded-lg px-3 py-2 mb-4"></textarea>

      <div class="flex justify-end gap-2">
        <button @click="$emit('close')" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Cancel</button>
        <button @click="createAppointment" :disabled="loading" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 disabled:opacity-50">
          {{ loading ? 'Booking...' : 'Book' }}
        </button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, watch } from 'vue'
//import { emit } from 'vue'

const props = defineProps({
  show: Boolean,
  carId: Number,
  userId: Number,
})
const emitEvent = defineEmits(['close', 'saved'])

const appointmentDate = ref('')
const appointmentTime = ref('')
const notes = ref('')
const loading = ref(false)

async function createAppointment() {
  if (!appointmentDate.value || !appointmentTime.value) {
    alert('Please select a date and time.')
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
        notes: notes.value,
      }),
    })

    const data = await response.json()

    if (data.message) {
      alert('✅ Appointment booked successfully!')
      emitEvent('saved')
      emitEvent('close')
    } else {
      alert('⚠️ Failed to book appointment: ' + (data.error || 'Unknown error'))
    }
  } catch (err) {
    console.error('Error:', err)
    alert('⚠️ Network or server error.')
  } finally {
    loading.value = false
  }
}
</script>

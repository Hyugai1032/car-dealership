<template>
  <div class="appointments mt-6 p-4 bg-white rounded-xl shadow-md max-w-lg mx-auto w-full">
    <h3 class="text-lg font-semibold mb-4">Your Appointments</h3>

    <div v-if="appointments.length">
      <div
        v-for="a in appointments"
        :key="a.id"
        class="border-b last:border-none py-3"
      >
        <p class="font-medium text-gray-800">
          {{ a.car.make }} {{ a.car.model }}
        </p>
        <p class="text-sm text-gray-500">
          {{ new Date(a.appointment_at).toLocaleString() }}
        </p>
        <p v-if="a.notes" class="text-sm text-gray-700 italic">
          "{{ a.notes }}"
        </p>
      </div>
    </div>

    <p v-else class="text-gray-500">No appointments yet.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  userId: Number
})

const appointments = ref([])

async function fetchAppointments() {
  try {
    const res = await fetch(`http://localhost:8000/listappointment?user_id=${props.userId}`)
    const data = await res.json()
    if (data.status === 'success') {
      appointments.value = data.appointments
    } else {
      appointments.value = []
    }
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchAppointments)

// expose to parent
defineExpose({ fetchAppointments })
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4 text-gray-800">Admin Appointment Management</h1>

    <!-- ✅ Edit Area (Always Visible) -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <h2 class="text-lg font-semibold mb-3">Edit Appointment</h2>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Client Name</label>
          <input
            v-model="selectedAppointment.user_name"
            :disabled="!selectedAppointment.id"
            class="w-full border rounded p-2 bg-gray-100"
            placeholder="Select an appointment"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Car</label>
          <input
            v-model="carDisplay"
            :disabled="!selectedAppointment.id"
            class="w-full border rounded p-2 bg-gray-100"
            placeholder="Car details"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Appointment Date</label>
          <input
            v-model="selectedAppointment.appointment_at"
            type="datetime-local"
            :disabled="!selectedAppointment.id"
            class="w-full border rounded p-2"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Status</label>
          <select
            v-model="selectedAppointment.status"
            :disabled="!selectedAppointment.id"
            class="w-full border rounded p-2"
          >
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="col-span-2">
          <label class="block text-sm font-medium mb-1">Notes</label>
          <textarea
            v-model="selectedAppointment.notes"
            :disabled="!selectedAppointment.id"
            class="w-full border rounded p-2"
            placeholder="Enter notes here"
          ></textarea>
        </div>
      </div>

      <div class="mt-4 flex gap-3">
        <button
          @click="updateAppointment"
          :disabled="!selectedAppointment.id"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
        >
          Save
        </button>
        <button
          @click="cancelEdit"
          class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500"
        >
          Clear
        </button>
      </div>
    </div>

    <!-- ✅ Appointment Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full border-collapse text-sm">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="p-3 text-left">Client</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Phone</th>
            <th class="p-3 text-left">Car</th>
            <th class="p-3 text-left">Date & Time</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Notes</th>
            <th class="p-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="appointment in appointments"
            :key="appointment.id"
            class="border-b hover:bg-gray-50"
          >
            <td class="p-3">{{ appointment.user_name }}</td>
            <td class="p-3">{{ appointment.email }}</td>
            <td class="p-3">{{ appointment.phone }}</td>
            <td class="p-3">{{ appointment.make }} {{ appointment.model }}</td>
            <td class="p-3">{{ formatDate(appointment.appointment_at) }}</td>
            <td class="p-3 capitalize">
              <span
                :class="{
                  'text-yellow-600 font-semibold': appointment.status === 'pending',
                  'text-green-600 font-semibold': appointment.status === 'approved' || appointment.status === 'completed',
                  'text-red-600 font-semibold': appointment.status === 'cancelled'
                }"
              >
                {{ appointment.status }}
              </span>
            </td>
            <td class="p-3">{{ appointment.notes || '—' }}</td>
            <td class="p-3 text-center">
              <button
                @click="editAppointment(appointment)"
                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
              >
                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";

const appointments = ref([]);
const selectedAppointment = ref({
  id: null,
  user_name: "",
  make: "",
  model: "",
  appointment_at: "",
  status: "",
  notes: "",
});

// ✅ Fetch Appointments
const fetchAppointments = async () => {
  try {
    const res = await fetch("http://localhost:8000/listappointment");
    const data = await res.json();
    appointments.value = data.appointments || [];

    // Update sidebar badge for pending appointments
    const pendingCount = appointments.value.filter(a => a.status === "pending").length;
    localStorage.setItem("pendingAppointments", pendingCount);
    window.dispatchEvent(new CustomEvent("updateSidebarBadge", { detail: { pendingCount } }));
  } catch (error) {
    console.error("Failed to fetch appointments:", error);
  }
};

// ✅ Edit Appointment
const editAppointment = (appointment) => {
  selectedAppointment.value = { ...appointment };
};

// ✅ Clear Selection
const cancelEdit = () => {
  selectedAppointment.value = {
    id: null,
    user_name: "",
    make: "",
    model: "",
    appointment_at: "",
    status: "",
    notes: "",
  };
};

// ✅ Update Appointment (via PUT)
const updateAppointment = async () => {
  if (!selectedAppointment.value.id) {
    alert("Please select an appointment to update.");
    return;
  }

  try {
    const payload = {
      status: selectedAppointment.value.status,
      notes: selectedAppointment.value.notes,
      appointment_at: selectedAppointment.value.appointment_at,
    };

    const response = await fetch(
      `http://localhost:8000/updateappointment/${selectedAppointment.value.id}`,
      {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      }
    );

    const result = await response.json();

    if (result.message?.includes("updated") || result.status === "success") {
      alert("✅ Appointment updated successfully!");
      fetchAppointments();
    } else {
      alert("❌ Failed to update appointment.");
    }
  } catch (error) {
    console.error("Update failed:", error);
    alert("⚠️ Error updating appointment.");
  }
};

// ✅ Format datetime nicely
const formatDate = (datetime) => {
  const date = new Date(datetime);
  return date.toLocaleString();
};

// ✅ Display car make/model in edit section
const carDisplay = computed(() => {
  if (!selectedAppointment.value.make && !selectedAppointment.value.model) return "";
  return `${selectedAppointment.value.make} ${selectedAppointment.value.model}`;
});

onMounted(fetchAppointments);
</script>

<template>
  <div class="dashboard" :class="theme">
    <!-- Background Glow Effect -->
    <div class="background-glow"></div>

    <!-- Main Dashboard Content -->
    <div class="dashboard-content">
      <!-- Header Section -->
      <div class="panel-header">
        <div class="header-content">
          <div class="title-section">
            <div class="title-icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <div class="title-text">
              <h1 class="main-title">Appointment Management</h1>
              <p class="subtitle">Manage client test drives & bookings</p>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="header-stats">
            <div class="stat-card">
              <div class="stat-icon"><i class="fas fa-clock"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ appointments.length }}</div>
                <div class="stat-label">Total</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon warning"><i class="fas fa-hourglass-half"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ pendingCount }}</div>
                <div class="stat-label">Pending</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ approvedCount }}</div>
                <div class="stat-label">Approved</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon error"><i class="fas fa-times-circle"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ cancelledCount }}</div>
                <div class="stat-label">Cancelled</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Panel: Form on Top + Scrollable Table Below -->
      <div class="dashboard-grid">
        <div class="management-panel">

          <!-- EDIT FORM (Always Visible on Top) -->
          <div class="form-section">
            <div class="form-container">
              <div class="form-header">
                <h3 class="form-title">
                  <i class="fas fa-edit"></i> Edit Appointment
                </h3>
                <div class="form-badge" :class="selectedAppointment.id ? 'editing' : 'idle'">
                  {{ selectedAppointment.id ? 'EDITING' : 'SELECT A ROW TO EDIT' }}
                </div>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label"><i class="fas fa-user"></i> Client Name</label>
                  <input v-model="selectedAppointment.user_name" :disabled="!selectedAppointment.id" class="form-input" placeholder="Enter name" />
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                  <input v-model="selectedAppointment.email" :disabled="!selectedAppointment.id" class="form-input" placeholder="client@example.com" />
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-phone"></i> Phone</label>
                  <input v-model="selectedAppointment.phone" :disabled="!selectedAppointment.id" class="form-input" placeholder="+63 9xx xxx xxxx" />
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-car"></i> Vehicle</label>
                  <input :value="carDisplay" disabled class="form-input" placeholder="Make & Model" />
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-calendar-alt"></i> Date & Time</label>
                  <input v-model="selectedAppointment.appointment_at" type="datetime-local" :disabled="!selectedAppointment.id" class="form-input" />
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-tags"></i> Status</label>
                  <select v-model="selectedAppointment.status" :disabled="!selectedAppointment.id" class="form-input">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group full-width">
                  <label class="form-label"><i class="fas fa-sticky-note"></i> Notes</label>
                  <textarea v-model="selectedAppointment.notes" :disabled="!selectedAppointment.id" class="form-textarea" rows="3" placeholder="Additional notes..."></textarea>
                  <div class="form-focus-line"></div>
                </div>
              </div>

              <div class="form-actions">
                <button @click="updateAppointment" :disabled="!selectedAppointment.id" class="submit-btn">
                  <i class="fas fa-save"></i> Save Changes
                  <div class="btn-sparkle"><i class="fas fa-bolt"></i></div>
                </button>
                <button @click="cancelEdit" class="cancel-btn">
                  <i class="fas fa-times"></i> Clear
                </button>
              </div>
            </div>
          </div>

          <!-- TABLE SECTION (Scrollable Horizontally) -->
          <div class="table-section">
            <div class="table-container">
              <div class="table-header">
                <h3 class="table-title"><i class="fas fa-list-alt"></i> All Appointments</h3>
                <button @click="fetchAppointments" class="refresh-btn">
                  <i class="fas fa-sync-alt"></i> Refresh
                </button>
              </div>

              <div class="table-content">
                <!-- Loading State -->
                <div v-if="loading" class="empty-state">
                  <div class="loading-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-car"><i class="fas fa-car"></i></div>
                  </div>
                  <p>Loading appointments...</p>
                </div>

                <!-- Empty State -->
                <div v-else-if="appointments.length === 0" class="empty-state">
                  <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                  <h3>No Appointments Found</h3>
                  <p>All test drive bookings will appear here</p>
                </div>

                <!-- Table with Horizontal Scroll -->
                <div v-else class="table-responsive">
                  <table class="appointments-table">
                    <thead>
                      <tr>
                        <th><i class="fas fa-user"></i> Client</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-phone"></i> Phone</th>
                        <th><i class="fas fa-car"></i> Vehicle</th>
                        <th><i class="fas fa-calendar"></i> Date & Time</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-sticky-note"></i> Notes</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="apt in appointments" :key="apt.id" 
                          :class="{ 'editing-row': selectedAppointment.id === apt.id }"
                          class="table-row">
                        <td><strong>{{ apt.user_name }}</strong></td>
                        <td><a :href="`mailto:${apt.email}`" class="email-link">{{ apt.email }}</a></td>
                        <td>{{ apt.phone || '—' }}</td>
                        <td><strong>{{ apt.make }} {{ apt.model }}</strong></td>
                        <td>{{ formatDate(apt.appointment_at) }}</td>
                        <td>
                          <span class="status-badge" :class="apt.status">
                            {{ apt.status.charAt(0).toUpperCase() + apt.status.slice(1) }}
                          </span>
                        </td>
                        <td class="notes-cell">{{ apt.notes || '—' }}</td>
                        <td>
                          <button @click="editAppointment(apt)" class="action-btn edit-btn">
                            <i class="fas fa-edit"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Fullscreen Loading -->
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner">
        <div class="spinner-ring"></div>
        <div class="spinner-car"><i class="fas fa-car"></i></div>
      </div>
      <p>Processing...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const theme = ref(localStorage.getItem('dashboard-theme') || 'dark')
const loading = ref(false)
const appointments = ref([])

const selectedAppointment = ref({
  id: null, user_name: "", email: "", phone: "", make: "", model: "",
  appointment_at: "", status: "pending", notes: ""
})

const pendingCount = computed(() => appointments.value.filter(a => a.status === 'pending').length)
const approvedCount = computed(() => appointments.value.filter(a => ['approved', 'completed'].includes(a.status)).length)
const cancelledCount = computed(() => appointments.value.filter(a => a.status === 'cancelled').length)

const carDisplay = computed(() => selectedAppointment.value.make && selectedAppointment.value.model 
  ? `${selectedAppointment.value.make} ${selectedAppointment.value.model}` : '—')

// Fetch Appointments
const fetchAppointments = async () => {
  loading.value = true
  try {
    const res = await fetch('http://localhost:8000/listappointment')
    const data = await res.json()
    appointments.value = data.appointments || []
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Edit Row
const editAppointment = (apt) => {
  selectedAppointment.value = { ...apt }
  document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' })
}

const cancelEdit = () => {
  selectedAppointment.value = { id: null, user_name: "", email: "", phone: "", make: "", model: "", appointment_at: "", status: "pending", notes: "" }
}

// Update Appointment
const updateAppointment = async () => {
  if (!selectedAppointment.value.id) return

  loading.value = true
  try {
    const payload = {
      status: selectedAppointment.value.status,
      notes: selectedAppointment.value.notes,
      appointment_at: selectedAppointment.value.appointment_at
    }

    const res = await fetch(`http://localhost:8000/updateappointment/${selectedAppointment.value.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })

    const result = await res.json()
    if (result.message?.includes('updated') || result.status === 'success') {
      alert('Appointment updated successfully!')
      await fetchAppointments()
      cancelEdit()
    } else {
      alert('Update failed.')
    }
  } catch (err) {
    alert('Network error.')
  } finally {
    loading.value = false
  }
}

const formatDate = (date) => new Date(date).toLocaleString('en-US', {
  month: 'short', day: 'numeric', year: 'numeric',
  hour: 'numeric', minute: '2-digit'
})

onMounted(fetchAppointments)
</script>

<style scoped>
/* === PERFECT FIT FOR SIDEBAR LAYOUT === */
.dashboard {
  min-height: 100vh;
  background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #0a0a0a 100%);
  color: #fff;
  position: relative;
  overflow-x: hidden;
}

.background-glow {
  position: fixed;
  inset: 0;
  background: radial-gradient(circle at 15% 85%, rgba(212,0,0,0.12), transparent 50%),
              radial-gradient(circle at 85% 15%, rgba(30,30,30,0.1), transparent 50%);
  pointer-events: none;
  z-index: -1;
}

.dashboard-content {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
  box-sizing: border-box;
}

/* Header */
.panel-header { padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 20px; }
.header-content { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; }
.title-section { display: flex; align-items: center; gap: 15px; }
.title-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #d40000, #a80000); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 6px 20px rgba(212,0,0,0.3); }
.main-title { font-size: 2rem; font-weight: 800; background: linear-gradient(90deg, #fff, #d40000); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.subtitle { font-size: 1rem; color: rgba(255,255,255,0.7); margin-top: 4px; }

.header-stats { display: flex; gap: 12px; flex-wrap: wrap; }
.stat-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; min-width: 120px; backdrop-filter: blur(8px); }
.stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; background: linear-gradient(135deg, #2d2d2d, #1a1a1a); color: #d40000; }
.warning { background: linear-gradient(135deg, #92400e, #6b3000) !important; color: #f59e0b !important; }
.success { background: linear-gradient(135deg, #166534, #0d4f2c) !important; color: #10b981 !important; }
.error { background: linear-gradient(135deg, #7f1d1d, #5f1515) !important; color: #ef4444 !important; }
.stat-value { font-size: 1.5rem; font-weight: 700; }
.stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7); }

/* Main Panel */
.management-panel {
  background: rgba(255,255,255,0.025);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 15px 40px rgba(0,0,0,0.4);
  backdrop-filter: blur(15px);
}

.form-section, .table-section { padding: 20px; }
.form-section { border-bottom: 1px solid rgba(255,255,255,0.08); }

.form-container, .table-container {
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 16px;
  padding: 20px;
  backdrop-filter: blur(10px);
}

.form-header, .table-header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;
}
.form-title, .table-title { font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.form-title i, .table-title i { color: #d40000; }

.form-badge {
  padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
}
.form-badge.editing { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
.form-badge.idle { background: rgba(100,100,100,0.1); color: #aaa; }

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 14px;
  margin-bottom: 16px;
}
.full-width { grid-column: 1 / -1; }

.form-label {
  display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: #fff;
}
.form-label i { color: #d40000; width: 14px; }

.form-input, .form-textarea, select.form-input {
  width: 100%;
  background: rgba(255,255,255,0.06);
  border: 2px solid rgba(255,255,255,0.12);
  border-radius: 10px;
  padding: 11px 14px;
  color: #fff;
  font-size: 0.9rem;
  transition: all 0.3s;
}
.form-input:focus, .form-textarea:focus, select:focus {
  outline: none;
  border-color: #d40000;
  background: rgba(255,255,255,0.09);
}
.form-input:disabled, .form-textarea:disabled {
  opacity: 0.6; cursor: not-allowed; background: rgba(255,255,255,0.04);
}
.form-focus-line {
  position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: #d40000; transition: 0.3s; transform: translateX(-50%);
}
.form-input:focus ~ .form-focus-line, .form-textarea:focus ~ .form-focus-line { width: 100%; }

.form-actions {
  display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px;
}
.submit-btn, .cancel-btn {
  padding: 11px 20px; border-radius: 10px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.3s; cursor: pointer;
}
.submit-btn {
  background: linear-gradient(135deg, #d40000, #a80000);
  color: white; border: none; box-shadow: 0 6px 20px rgba(212,0,0,0.3);
}
.submit-btn:hover { transform: translateY(-2px); }
.cancel-btn {
  background: rgba(255,255,255,0.06); color: #fff; border: 1px solid rgba(255,255,255,0.15);
}
.btn-sparkle { animation: sparkle 2s infinite; }
@keyframes sparkle { 0%,100% { transform: scale(1); } 50% { transform: scale(1.3) rotate(180deg); } }

/* Table */
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,0.1);
  max-width: 100%;
}
.appointments-table {
  width: 100%;
  /* allow table to shrink within container; overflow handled by wrapper */
  min-width: 0;
  border-collapse: collapse;
  background: rgba(255,255,255,0.02);
  table-layout: auto;
}
.appointments-table th {
  background: rgba(255,255,255,0.06);
  padding: 16px 14px;
  text-align: left;
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #fff;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
.appointments-table th i { margin-right: 6px; color: #d40000; font-size: 0.8rem; }
.table-row:hover { background: rgba(255,255,255,0.04); }
.table-row.editing-row { background: rgba(245,158,11,0.06); border-left: 4px solid #f59e0b; }
.appointments-table td {
  padding: 16px 14px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  font-size: 0.95rem;
  vertical-align: middle;
  /* allow long values (email, names) to wrap instead of forcing tiny columns */
  word-break: break-word;
}
.email-link { color: #60a5fa; text-decoration: underline; }
.status-badge {
  padding: 3px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 600;
  display: inline-block;
  line-height: 1;
  min-width: 56px;
  text-align: center;
}
.status-badge.pending { background: rgba(245,158,11,0.15); color: #f59e0b; }
.status-badge.approved, .status-badge.completed { background: rgba(16,185,129,0.15); color: #10b981; }
.status-badge.cancelled { background: rgba(239,68,68,0.15); color: #ef4444; }
.notes-cell {
  max-width: 260px;
  /* allow notes to wrap and show multi-line content */
  white-space: normal;
  overflow-wrap: anywhere;
  word-break: break-word;
}
.action-btn {
  padding: 8px 12px; background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);
  border-radius: 8px; cursor: pointer; transition: 0.2s;
}
.action-btn:hover { background: rgba(59,130,246,0.25); transform: translateY(-1px); }

/* Narrow and center the Status column to reduce crowding */
.appointments-table th:nth-child(6), .appointments-table td:nth-child(6) {
  width: 120px;
  max-width: 140px;
  text-align: center;
  white-space: nowrap;
}

@media (max-width: 1024px) {
  .appointments-table th:nth-child(6), .appointments-table td:nth-child(6) {
    width: 110px;
  }
}

@media (max-width: 768px) {
  .appointments-table th:nth-child(6), .appointments-table td:nth-child(6) {
    width: auto;
    white-space: normal;
  }
  .status-badge { font-size: 0.75rem; padding: 4px 8px; }
}

.refresh-btn {
  padding: 10px 16px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15);
  border-radius: 10px; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 0.9rem;
}
.refresh-btn:hover { background: rgba(255,255,255,0.1); }

.empty-state {
  text-align: center; padding: 60px 20px; color: rgba(255,255,255,0.6);
}
.empty-icon { font-size: 3rem; margin-bottom: 16px; opacity: 0.4; }

/* Loading Overlay */
.loading-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.92); backdrop-filter: blur(10px);
  display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; color: #fff;
}
.loading-spinner {
  width: 70px; height: 70px; position: relative; margin-bottom: 20px;
}
.spinner-ring {
  width: 100%; height: 100%; border: 4px solid rgba(212,0,0,0.3); border-top-color: #d40000;
  border-radius: 50%; animation: spin 1s linear infinite;
}
.spinner-car { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); font-size: 1.8rem; color: #d40000; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Mobile */
@media (max-width: 768px) {
  .dashboard-content { padding: 15px; }
  .header-content { flex-direction: column; align-items: flex-start; }
  .header-stats { justify-content: center; width: 100%; }
  .form-grid { grid-template-columns: 1fr; }
  .form-actions { flex-direction: column; }
}
</style>
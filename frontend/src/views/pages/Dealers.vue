<template>
  <div class="dashboard" :class="theme">
    <!-- Background Effects -->
    <div class="background-glow"></div>
    
    <!-- Dashboard Content -->
    <div class="dashboard-content">
      <!-- Panel Header -->
      <div class="panel-header">
        <div class="header-content">
          <div class="title-section">
            <div class="title-icon">
              <i class="fas fa-store"></i>
            </div>
            <div class="title-text">
              <h1 class="main-title">Dealers Management</h1>
              <p class="subtitle">Manage dealership partners and their details</p>
            </div>
          </div>
          <div class="header-stats">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-store-alt"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ dealers.length }}</div>
                <div class="stat-label">Total Dealers</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="dashboard-grid">
        <!-- Dealers Management Panel -->
        <div class="cars-management-panel">
          <!-- Form Section -->
          <div class="form-section">
            <div class="form-container">
              <div class="form-header">
                <h3 class="form-title">
                  <i :class="isEditing ? 'fas fa-edit' : 'fas fa-plus'"></i>
                  {{ isEditing ? 'Update Dealer' : 'Add New Dealer' }}
                </h3>
                <div class="form-badge" :class="isEditing ? 'editing' : 'creating'">
                  {{ isEditing ? 'EDITING' : 'CREATING' }}
                </div>
              </div>

              <form @submit.prevent="isEditing ? updateDealer() : createDealer()" class="car-form">
                <div class="form-grid">
                  <!-- Dealer Name -->
                  <div class="form-group">
                    <label class="form-label"><i class="fas fa-store"></i> Dealer Name</label>
                    <input v-model="form.name" required class="form-input" placeholder="e.g., AutoLux Manila" />
                    <div class="form-focus-line"></div>
                  </div>

                  <!-- Email -->
                  <div class="form-group">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input v-model="form.email" type="email" class="form-input" placeholder="dealer@example.com" />
                    <div class="form-focus-line"></div>
                  </div>

                  <!-- Phone -->
                  <div class="form-group">
                    <label class="form-label"><i class="fas fa-phone"></i> Phone</label>
                    <input v-model="form.phone" class="form-input" placeholder="+63 9XX XXX XXXX" />
                    <div class="form-focus-line"></div>
                  </div>

                  <!-- Address -->
                  <div class="form-group full-width">
                    <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                    <input v-model="form.address" class="form-input" placeholder="123 Main St, Quezon City" />
                    <div class="form-focus-line"></div>
                  </div>

                  <!-- Description -->
                  <div class="form-group full-width">
                    <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                    <textarea v-model="form.description" class="form-textarea" placeholder="About this dealership..."></textarea>
                    <div class="form-focus-line"></div>
                  </div>

                  <!-- Logo Upload -->
                  <div class="form-group full-width">
                    <label class="form-label"><i class="fas fa-image"></i> Dealer Logo</label>
                    <input
                      ref="logoInput"
                      type="file"
                      accept="image/*"
                      @change="handleLogoUpload"
                      style="display: none"
                    />
                    <div
                      class="image-upload-box"
                      @click="$refs.logoInput.click()"
                      :class="{ 'has-image': previewLogo }"
                    >
                      <div v-if="!previewLogo" class="upload-placeholder">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload logo (Max 3MB)</p>
                      </div>
                      <img v-else :src="previewLogo" alt="Logo preview" class="preview-img" />
                    </div>
                    <div class="form-hint">
                      {{ form.logo ? 'Logo ready (click to change)' : 'No logo selected' }}
                    </div>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                  <button v-if="isEditing" type="button" @click="cancelEdit" class="cancel-btn">
                    <i class="fas fa-times"></i> Cancel
                  </button>
                  <button type="submit" class="submit-btn" :class="{ editing: isEditing }">
                    <i :class="isEditing ? 'fas fa-save' : 'fas fa-plus'"></i>
                    {{ isEditing ? 'Update Dealer' : 'Add Dealer' }}
                    <div class="btn-sparkle"><i class="fas fa-bolt"></i></div>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Dealers Table -->
          <div class="table-section">
            <div class="table-container">
              <div class="table-header">
                <h3 class="table-title"><i class="fas fa-list"></i> Dealer Directory</h3>
                <div class="table-actions">
                  <button @click="fetchDealers" class="refresh-btn">
                    <i class="fas fa-sync-alt"></i> Refresh
                  </button>
                </div>
              </div>

              <div class="table-content">
                <div v-if="dealers.length === 0" class="empty-state">
                  <div class="empty-icon"><i class="fas fa-store-slash"></i></div>
                  <h3>No Dealers Found</h3>
                  <p>Add your first dealership partner to get started</p>
                </div>

                <div v-else class="enhanced-table">
                  <div class="table-responsive">
                    <table class="vehicles-table">
                      <thead>
                        <tr>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-hashtag"></i> ID</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-image"></i> Logo</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-store"></i> Name</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-envelope"></i> Email</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-phone"></i> Phone</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-map-marker-alt"></i> Address</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-actions"></i> Actions</div></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="dealer in dealers" :key="dealer.id" class="table-row" :class="{ 'editing-row': isEditing && editId === dealer.id }">
                          <td class="table-cell id-cell">#{{ dealer.id }}</td>
                          <td class="table-cell">
                            <img
                              v-if="dealer.logo"
                              :src="dealer.logo.startsWith('data:') ? dealer.logo : 'http://localhost:8000' + dealer.logo"
                              alt="Logo"
                              width="60"
                              height="60"
                              style="border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.1);"
                            />
                            <span v-else class="variant-empty">—</span>
                          </td>
                          <td class="table-cell"><span class="make-text">{{ dealer.name }}</span></td>
                          <td class="table-cell"><span class="model-text">{{ dealer.email || '—' }}</span></td>
                          <td class="table-cell">{{ dealer.phone || '—' }}</td>
                          <td class="table-cell" style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ dealer.address || '—' }}
                          </td>
                          <td class="table-cell actions-cell">
                            <div class="action-buttons">
                              <button @click="editDealer(dealer)" class="action-btn edit-btn" :disabled="isEditing && editId !== dealer.id">
                                <i class="fas fa-edit"></i> Edit
                                <div class="btn-glow"></div>
                              </button>
                              <button @click="deleteDealer(dealer.id)" class="action-btn delete-btn" :disabled="isEditing">
                                <i class="fas fa-trash"></i> Delete
                                <div class="btn-glow"></div>
                              </button>
                            </div>
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
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner">
        <div class="spinner-ring"></div>
        <div class="spinner-car"><i class="fas fa-store"></i></div>
      </div>
      <p>Processing dealer data...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// State
const theme = ref(localStorage.getItem('dashboard-theme') || 'dark')
const loading = ref(false)
const dealers = ref([])
const isEditing = ref(false)
const editId = ref(null)
const previewLogo = ref('')

// Form
const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  description: '',
  logo: ''
})

// API Base
const API_BASE = 'http://localhost:8000'

// Notification
const showNotification = (message, type = 'info') => {
  alert(`[${type.toUpperCase()}] ${message}`)
}

// Logo Upload
const handleLogoUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']
  if (!allowedTypes.includes(file.type)) {
    showNotification('Only JPG, PNG, WebP allowed', 'error')
    return
  }
  if (file.size > 3 * 1024 * 1024) {
    showNotification('Logo must be under 3MB', 'error')
    return
  }

  loading.value = true
  const formData = new FormData()
  formData.append('logo_file', file)

  try {
    const res = await fetch(`${API_BASE}/dealers/upload-logo`, {
      method: 'POST',
      body: formData
    })
    const data = await res.json()
    if (data.status === 'success') {
      form.value.logo = data.url
      previewLogo.value = `${API_BASE}${data.url}`
      showNotification('Logo uploaded successfully!', 'success')
    } else {
      showNotification(data.message || 'Upload failed', 'error')
    }
  } catch (err) {
    showNotification('Network error during upload', 'error')
  } finally {
    loading.value = false
  }
}

// CRUD Operations
const fetchDealers = async () => {
  loading.value = true
  try {
    const res = await fetch(`${API_BASE}/dealers`)
    const data = await res.json()
    if (data.status === 'success') {
      dealers.value = data.dealers
    }
  } catch (err) {
    console.error('Fetch dealers error:', err)
  } finally {
    loading.value = false
  }
}

const createDealer = async () => {
  if (!form.value.name.trim()) return showNotification('Dealer name is required!', 'error')
  loading.value = true
  try {
    const res = await fetch(`${API_BASE}/dealers`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
        name: form.value.name,
        description: form.value.description,
        address: form.value.address,
        phone: form.value.phone,
        email: form.value.email,
        logo: form.value.logo
      })
    })
    const data = await res.json()
    if (data.status === 'success') {
      await fetchDealers()
      resetForm()
      showNotification('Dealer created successfully!', 'success')
    } else {
      showNotification(data.message || 'Failed to create dealer', 'error')
    }
  } catch (err) {
    showNotification('Network error', 'error')
  } finally {
    loading.value = false
  }
}

const updateDealer = async () => {
  loading.value = true
  try {
    const res = await fetch(`${API_BASE}/dealers/${editId.value}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    const data = await res.json()
    if (data.status === 'success') {
      await fetchDealers()
      cancelEdit()
      showNotification('Dealer updated successfully!', 'success')
    } else {
      showNotification(data.message || 'Update failed', 'error')
    }
  } catch (err) {
    showNotification('Network error', 'error')
  } finally {
    loading.value = false
  }
}

const deleteDealer = async (id) => {
  if (!confirm('Permanently delete this dealer? This cannot be undone.')) return

  loading.value = true
  try {
    const res = await fetch(`${API_BASE}/dealers/${id}`, { method: 'DELETE' })
    const data = await res.json()
    if (data.status === 'success') {
      await fetchDealers()
      showNotification('Dealer deleted', 'success')
    } else {
      showNotification(data.message || 'Delete failed', 'error')
    }
  } catch (err) {
    showNotification('Network error', 'error')
  } finally {
    loading.value = false
  }
}

const editDealer = (dealer) => {
  isEditing.value = true
  editId.value = dealer.id
  form.value = { ...dealer }
  previewLogo.value = dealer.logo ? `${API_BASE}${dealer.logo}` : ''
  document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' })
}

const cancelEdit = () => {
  isEditing.value = false
  editId.value = null
  resetForm()
}

const resetForm = () => {
  form.value = { name: '', email: '', phone: '', address: '', description: '', logo: '' }
  previewLogo.value = ''
  if (document.querySelector('input[type="file"]')) {
    document.querySelector('input[type="file"]').value = ''
  }
}

onMounted(() => {
  fetchDealers()
})
</script>

<style scoped>
/* === EXACT SAME STUNNING STYLE FROM YOUR CARS PAGE === */
/* You already know this is pure art — every line copied perfectly */

.dashboard {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
  color: #ffffff;
  position: relative;
  overflow-x: hidden;
}

.background-glow {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(212, 0, 0, 0.15) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(26, 26, 26, 0.1) 0%, transparent 50%);
  pointer-events: none;
  z-index: -1;
}

.dashboard-content {
  flex: 1;
  padding: 30px;
  display: flex;
  flex-direction: column;
  gap: 30px;
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
}

.dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 30px; }

.panel-header { padding: 40px 40px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
.header-content { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 30px; }
.title-section { display: flex; align-items: center; gaps: 20px; }
.title-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #d40000, #a80000); border-radius: 18px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem; box-shadow: 0 10px 30px rgba(212,0,0,0.4); }
.main-title { font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #ffffff, #f0f0f0, #d40000); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; }
.subtitle { font-size: 1.1rem; color: rgba(255,255,255,0.7); font-weight: 500; }

.header-stats { display: flex; gap: 20px; }
.stat-card { display: flex; align-items: center; gap: 15px; background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); min-width: 180px; }
.stat-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #2d2d2d, #1a1a1a); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #d40000; font-size: 1.3rem; }
.stat-value { font-size: 2rem; font-weight: 800; color: white; }
.stat-label { font-size: 0.9rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; }

.cars-management-panel { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); border-radius: 25px; backdrop-filter: blur(20px); overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); }

.form-section { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.08); }
.form-container { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 16px; backdrop-filter: blur(8px); }
.form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.form-title { font-size: 1.5rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 12px; }
.form-title i { color: #d40000; }
.form-badge { padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.form-badge.creating { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.form-badge.editing { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }

.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 18px; }
.form-group { position: relative; }
.form-group.full-width { grid-column: 1 / -1; }
.form-label { display: flex; align-items: center; gap: 8px; font-weight: 600; color: white; margin-bottom: 8px; font-size: 0.9rem; }
.form-label i { color: #d40000; width: 16px; }
.form-input, .form-textarea { width: 100%; background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px; color: white; font-size: 0.95rem; transition: all 0.2s ease; }
.form-textarea { resize: vertical; min-height: 64px; }
.form-input:focus, .form-textarea:focus { outline: none; border-color: #d40000; background: rgba(255,255,255,0.08); }
.form-input::placeholder, .form-textarea::placeholder { color: rgba(255,255,255,0.4); }
.form-focus-line { position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: #d40000; transition: all 0.3s ease; transform: translateX(-50%); }
.form-input:focus ~ .form-focus-line, .form-textarea:focus ~ .form-focus-line { width: 100%; }
.form-hint { font-size: 0.8rem; color: rgba(255,255,255,0.5); margin-top: 5px; }

.form-actions { display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap; }
.cancel-btn { padding: 10px 18px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; color: white; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; }
.cancel-btn:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.15); transform: translateY(-1px); }
.submit-btn { position: relative; padding: 10px 20px; background: linear-gradient(135deg, #d40000, #a80000); border: none; border-radius: 10px; color: white; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; overflow: hidden; box-shadow: 0 6px 18px rgba(212,0,0,0.25); }
.submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(212,0,0,0.4); }
.submit-btn.editing { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 8px 25px rgba(245,158,11,0.3); }
.submit-btn.editing:hover { box-shadow: 0 12px 35px rgba(245,158,11,0.5); }
.btn-sparkle { animation: sparkle 2s ease-in-out infinite; }
@keyframes sparkle { 0%,100% { transform: scale(1) rotate(0deg); } 50% { transform: scale(1.2) rotate(180deg); } }

.table-section { padding: 20px; }
.table-container { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 16px; backdrop-filter: blur(8px); }
.table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
.table-title { font-size: 1.3rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 10px; }
.table-title i { color: #d40000; }
.refresh-btn { padding: 12px 20px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; font-weight: 600; }
.refresh-btn:hover { background: rgba(255,255,255,0.1); border-color: rgba(59,130,246,0.3); transform: translateY(-2px); }

.empty-state { text-align: center; padding: 60px 40px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1); }
.empty-icon { font-size: 3rem; color: rgba(255,255,255,0.3); margin-bottom: 20px; }

.enhanced-table .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); max-width: 100%; }
.vehicles-table { width: 100%; min-width: 0; border-collapse: collapse; background: rgba(255,255,255,0.02); }
.table-header-cell { background: rgba(255,255,255,0.05); padding: 12px; text-align: left; font-weight: 700; color: white; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.86rem; text-transform: uppercase; letter-spacing: 0.4px; }
.header-content { display: flex; align-items: center; gap: 8px; }
.header-content i { color: #d40000; font-size: 0.78rem; }
.table-row { transition: all 0.2s ease; border-bottom: 1px solid rgba(255,255,255,0.05); }
.table-row:hover { background: rgba(255,255,255,0.03); }
.table-row.editing-row { background: rgba(245,158,11,0.05); border-left: 3px solid #f59e0b; }
.table-cell { padding: 12px; color: rgba(255,255,255,0.9); border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }

/* Column sizing for Dealers table: keep name/email/phone fitting the container */
.vehicles-table th:nth-child(3), .vehicles-table td:nth-child(3) { /* Name */
  width: 30%;
  max-width: 36ch;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.vehicles-table th:nth-child(4), .vehicles-table td:nth-child(4) { /* Email */
  width: 28%;
  max-width: 40ch;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.vehicles-table th:nth-child(5), .vehicles-table td:nth-child(5) { /* Phone */
  width: 16%;
  max-width: 18ch;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.vehicles-table th:nth-child(6), .vehicles-table td:nth-child(6) { /* Address */
  width: auto;
}

@media (max-width: 900px) {
  .vehicles-table th:nth-child(3), .vehicles-table td:nth-child(3),
  .vehicles-table th:nth-child(4), .vehicles-table td:nth-child(4),
  .vehicles-table th:nth-child(5), .vehicles-table td:nth-child(5) {
    width: auto;
    white-space: normal;
    max-width: none;
  }
}
.id-cell { font-weight: 600; }
.make-text { font-weight: 600; color: white; }
.model-text { color: white; font-size: 1rem; }
.variant-empty { color: rgba(255,255,255,0.4); font-style: italic; }

.action-buttons { display: flex; gap: 8px; }
.action-btn { position: relative; padding: 10px 16px; border: none; border-radius: 8px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; overflow: hidden; }
.action-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; }
.edit-btn { background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
.edit-btn:hover:not(:disabled) { background: rgba(59,130,246,0.2); transform: translateY(-2px); }
.delete-btn { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
.delete-btn:hover:not(:disabled) { background: rgba(239,68,68,0.2); transform: translateY(-2px); }
.btn-glow { position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: left 0.5s ease; }
.action-btn:hover:not(:disabled) .btn-glow { left: 100%; }

.loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10,10,10,0.9); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 2000; backdrop-filter: blur(10px); }
.loading-spinner { position: relative; width: 80px; height: 80px; margin-bottom: 20px; }
.spinner-ring { width: 100%; height: 100%; border: 3px solid rgba(212,0,0,0.3); border-top: 3px solid #d40000; border-radius: 50%; animation: spin 1s linear infinite; }
.spinner-car { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #d40000; font-size: 1.5rem; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

.image-upload-box { cursor: pointer; border: 2px dashed rgba(255,255,255,.18); border-radius: 10px; height: 120px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.03); transition: all .2s ease; position: relative; overflow: hidden; }
.image-upload-box:hover { border-color: #d40000; background: rgba(255,255,255,.05); }
.image-upload-box.has-image:hover { border-color: #d40000; }
.upload-placeholder { text-align: center; color: rgba(255,255,255,.5); }
.upload-placeholder i { font-size: 1.6rem; margin-bottom: .4rem; }
.preview-img { width: 100%; height: 100%; object-fit: contain; background: #111; border-radius: 8px; }

@media (max-width: 1200px) { .header-content { flex-direction: column; align-items: flex-start; } .form-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) { .dashboard-content { padding: 20px; } .panel-header, .form-section, .table-section { padding: 30px 20px; } .main-title { font-size: 2rem; } .title-icon { width: 60px; height: 60px; font-size: 1.5rem; } .form-actions { flex-direction: column; align-items: stretch; } .action-buttons { flex-direction: column; } }
@media (max-width: 480px) { .dashboard-content { padding: 15px; } .panel-header, .form-section, .table-section { padding: 20px 15px; } .main-title { font-size: 1.8rem; } }
</style>
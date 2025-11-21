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
              <p class="subtitle">Manage authorized dealership partners</p>
            </div>
          </div>
          <div class="header-stats">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-building"></i>
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
        <div class="dealers-management-panel">
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

              <form @submit.prevent="isEditing ? updateDealer() : createDealer()" class="dealer-form">
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label"><i class="fas fa-store"></i> Dealer Name <span class="req">*</span></label>
                    <input v-model="form.name" required class="form-input" placeholder="e.g. Luxury Motors Manila" />
                  </div>

                  <div class="form-group">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input v-model="form.email" type="email" class="form-input" placeholder="dealer@example.com" />
                  </div>

                  <div class="form-group">
                    <label class="form-label"><i class="fas fa-phone"></i> Phone</label>
                    <input v-model="form.phone" class="form-input" placeholder="+63 912 345 6789" />
                  </div>

                  <div class="form-group full-width">
                    <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                    <input v-model="form.address" class="form-input" placeholder="123 Main Street, Makati City" />
                  </div>

                  <div class="form-group full-width">
                    <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                    <textarea v-model="form.description" class="form-textarea" rows="3" placeholder="Premium dealership specializing in luxury vehicles..."></textarea>
                  </div>

                  <!-- Logo Upload -->
                  <div class="form-group full-width">
                    <label class="form-label"><i class="fas fa-image"></i> Dealer Logo</label>
                    <input ref="logoInput" type="file" accept="image/*" @change="handleLogoUpload" hidden />
                    <div class="image-upload-box" @click="$refs.logoInput.click()" :class="{ 'has-image': previewLogo }">
                      <div v-if="!previewLogo" class="upload-placeholder">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload logo (Max 2MB)</p>
                      </div>
                      <img v-else :src="previewLogo" class="preview-img" />
                      <button v-if="previewLogo" @click.stop="removeLogo" class="remove-btn">×</button>
                    </div>
                    <div class="form-hint">
                      {{ form.logo ? 'Logo ready (click to change)' : 'No logo selected' }}
                    </div>
                  </div>
                </div>

                <div class="form-actions">
                  <button v-if="isEditing" type="button" @click="cancelEdit" class="cancel-btn">
                    Cancel
                  </button>
                  <button type="submit" class="submit-btn" :class="{ editing: isEditing }" :disabled="loading">
                    <i :class="isEditing ? 'fas fa-save' : 'fas fa-plus'"></i>
                    {{ isEditing ? 'Update Dealer' : 'Add Dealer' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Table Section -->
          <div class="table-section">
            <div class="table-container">
              <div class="table-header">
                <h3 class="table-title">All Dealers</h3>
                <button @click="fetchDealers" class="refresh-btn">
                  Refresh
                </button>
              </div>

              <div v-if="dealers.length === 0" class="empty-state">
                <div class="empty-icon"><i class="fas fa-store-slash"></i></div>
                <h3>No Dealers Found</h3>
                <p>Add your first dealership partner</p>
              </div>

              <div v-else class="enhanced-table">
                <div class="table-responsive">
                  <table class="dealers-table">
                    <thead>
                      <tr>
                        <th><div class="header-content">Logo</div></th>
                        <th><div class="header-content">Name</div></th>
                        <th><div class="header-content">Contact</div></th>
                        <th><div class="header-content">Address</div></th>
                        <th><div class="header-content">Created</div></th>
                        <th><div class="header-content">Actions</div></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="dealer in dealers" :key="dealer.id" class="table-row" :class="{ 'editing-row': isEditing && editId === dealer.id }">
                        <td class="logo-cell">
                          <img v-if="dealer.logo" :src="API_BASE + dealer.logo" alt="Logo" class="dealer-logo" />
                          <div v-else class="no-logo"><i class="fas fa-image"></i></div>
                        </td>
                        <td class="name-cell"><strong>{{ dealer.name }}</strong></td>
                        <td>
                          <div>{{ dealer.email || '—' }}</div>
                          <small>{{ dealer.phone || '—' }}</small>
                        </td>
                        <td class="address-cell">{{ dealer.address || '—' }}</td>
                        <td>{{ formatDate(dealer.created_at) }}</td>
                        <td class="actions-cell">
                          <div class="action-buttons">
                            <button @click="editDealer(dealer)" class="action-btn edit-btn" :disabled="isEditing && editId !== dealer.id">
                              Edit
                            </button>
                            <button @click="deleteDealer(dealer.id)" class="action-btn delete-btn" :disabled="isEditing">
                              Delete
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

const API_BASE = 'http://localhost:8000'
const theme = ref('dark')
const loading = ref(false)
const dealers = ref([])
const isEditing = ref(false)
const editId = ref(null)
const previewLogo = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  description: '',
  logo: ''
})

const toast = (msg, type = 'success') => {
  const t = document.createElement('div')
  t.textContent = msg
  t.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;padding:16px 28px;border-radius:12px;color:#fff;background:${type==='success'?'#10b981':type==='error'?'#ef4444':'#f59e0b'};box-shadow:0 10px 30px rgba(0,0,0,0.5);transform:translateX(120%);opacity:0;transition:all 0.4s;`
  document.body.appendChild(t)
  requestAnimationFrame(() => { t.style.transform = 'translateX(0)'; t.style.opacity = '1' })
  setTimeout(() => { t.style.transform = 'translateX(120%)'; t.style.opacity = '0'; t.addEventListener('transitionend', () => t.remove()) }, 3000)
}

const api = async (url, options = {}) => {
  const res = await fetch(`${API_BASE}${url}`, {
    ...options,
    credentials: 'include',
    headers: { 'Accept': 'application/json', ...(options.headers || {}) }
  })
  if (!res.ok) throw new Error(await res.text() || res.statusText)
  return res.json()
}

const fetchDealers = async () => {
  loading.value = true
  try {
    const data = await api('/dealers')
    dealers.value = data.dealers || []
  } catch (err) {
    toast('Failed to load dealers: ' + err.message, 'error')
  } finally {
    loading.value = false
  }
}

const createDealer = async () => {
  if (!form.value.name.trim()) return toast('Dealer name is required!', 'error')
  loading.value = true
  try {
    await api('/dealers', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(form.value) })
    toast('Dealer created successfully!', 'success')
    resetForm()
    fetchDealers()
  } catch (err) {
    toast('Create failed: ' + err.message, 'error')
  } finally {
    loading.value = false
  }
}

const updateDealer = async () => {
  loading.value = true
  try {
    await api(`/dealers/${editId.value}`, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(form.value) })
    toast('Dealer updated!', 'success')
    cancelEdit()
    fetchDealers()
  } catch (err) {
    toast('Update failed: ' + err.message, 'error')
  } finally {
    loading.value = false
  }
}

const deleteDealer = async (id) => {
  if (!confirm('Delete this dealer permanently?')) return
  loading.value = true
  try {
    await api(`/dealers/${id}`, { method: 'DELETE' })
    toast('Dealer deleted', 'success')
    fetchDealers()
  } catch (err) {
    toast('Delete failed', 'error')
  } finally {
    loading.value = false
  }
}

const editDealer = (dealer) => {
  isEditing.value = true
  editId.value = dealer.id
  form.value = { ...dealer }
  previewLogo.value = dealer.logo ? API_BASE + dealer.logo : ''
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
}

const handleLogoUpload = async (e) => {
  const file = e.target.files[0]
  if (!file) return
  if (!['image/jpeg', 'image/png'].includes(file.type)) return toast('Only JPG/PNG allowed', 'error')
  if (file.size > 2 * 1024 * 1024) return toast('Max 2MB', 'error')

  const fd = new FormData()
  fd.append('logo_file', file)

  try {
    loading.value = true
    const data = await api('/dealers/upload-logo', { method: 'POST', body: fd })
    form.value.logo = data.url
    previewLogo.value = API_BASE + data.url
    toast('Logo uploaded!', 'success')
  } catch (err) {
    toast('Upload failed', 'error')
  } finally {
    loading.value = false
  }
}

const removeLogo = () => {
  form.value.logo = ''
  previewLogo.value = ''
  if ($refs.logoInput) $refs.logoInput.value = ''
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

onMounted(fetchDealers)
</script>

<style scoped>
/* SAME EXACT STUNNING STYLE AS CARS — RED BLACK THEME */
.dashboard { min-height: 100vh; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%); color: #fff; position: relative; overflow-x: hidden; }
.background-glow { position: fixed; inset: 0; background: radial-gradient(circle at 20% 80%, rgba(212,0,0,0.15), transparent 50%); pointer-events: none; z-index: -1; }
.dashboard-content { padding: 30px; max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 30px; }

/* Reusing your GOD-TIER styles from Cars — only minor tweaks */
.panel-header { padding: 40px 40px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
.header-content { display: flex; justify-content: space-between; align-items: flex-end; gap: 30px; flex-wrap: wrap; }
.title-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #d40000, #a80000); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; box-shadow: 0 10px 30px rgba(212,0,0,0.4); }
.main-title { font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #fff, #d40000); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.subtitle { color: rgba(255,255,255,0.7); }
.stat-card { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px; min-width: 180px; }
.stat-value { font-size: 2rem; font-weight: 800; }
.dealers-management-panel { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); border-radius: 25px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.5); backdrop-filter: blur(20px); }
.form-section, .table-section { padding: 40px; }
.form-section { border-bottom: 1px solid rgba(255,255,255,0.1); }
.form-container, .table-container { background: rgba(255,255,255,0.03); border-radius: 20px; padding: 30px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
.full-width { grid-column: 1/-1; }
.form-label { display: flex; align-items: center; gap: 8px; font-weight: 600; margin-bottom: 8px; }
.req { color: #d40000; }
.form-input, .form-textarea { width: 100%; background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 15px; color: white; transition: all 0.3s; }
.form-input:focus, .form-textarea:focus { outline: none; border-color: #d40000; background: rgba(255,255,255,0.08); }
.image-upload-box { cursor: pointer; border: 2px dashed rgba(255,255,255,0.2); border-radius: 12px; height: 180px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.03); position: relative; overflow: hidden; }
.image-upload-box:hover { border-color: #d40000; }
.preview-img { width: 100%; height: 100%; object-fit: contain; }
.remove-btn { position: absolute; top: 10px; right: 10px; background: #ef4444; color: white; width: 36px; height: 36px; border-radius: 50%; border: none; font-size: 1.4rem; cursor: pointer; }
.form-actions { display: flex; gap: 15px; justify-content: flex-end; flex-wrap: wrap; }
.submit-btn { position: relative; padding: 15px 30px; background: linear-gradient(135deg, #d40000, #a80000); border: none; border-radius: 12px; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 25px rgba(212,0,0,0.3); overflow: hidden; }
.submit-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(212,0,0,0.5); }
.submit-btn.editing { background: linear-gradient(135deg, #f59e0b, #d97706); }
.cancel-btn { padding: 15px 25px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: white; }
.dealer-logo { width: 60px; height: 60px; object-fit: contain; border-radius: 12px; border: 2px solid rgba(255,255,255,0.1); }
.no-logo { width: 60px; height: 60px; background: rgba(255,255,255,0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.3); font-size: 1.8rem; }
.loading-overlay { position: fixed; inset: 0; background: rgba(10,10,10,0.9); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 2000; backdrop-filter: blur(10px); }
.spinner-ring { width: 80px; height: 80px; border: 4px solid rgba(212,0,0,0.3); border-top-color: #d40000; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
<template>
  <div class="dashboard dark">
    <div class="background-glow"></div>

    <div class="dashboard-content">
      <!-- Header -->
      <div class="panel-header">
        <div class="header-content">
          <div class="title-section">
            <div class="title-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="title-text">
              <h1 class="main-title">User Management</h1>
              <p class="subtitle">Create, edit, and manage all system users</p>
            </div>
          </div>

          <!-- Only Total Users Stat -->
          <div class="header-stats">
            <div class="stat-card big">
              <div class="stat-icon"><i class="fas fa-users"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ users.length }}</div>
                <div class="stat-label">Total Users</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Layout: Form (Left) + Users Table (Right) -->
      <div class="main-grid">
        <!-- LEFT: Create/Edit Form -->
        <div class="form-panel">
          <div class="form-container">
            <div class="form-header">
              <h3 class="form-title">
                <i :class="isEditing ? 'fas fa-user-edit' : 'fas fa-user-plus'"></i>
                {{ isEditing ? 'Update User' : 'Create New User' }}
              </h3>
              <div class="form-badge" :class="isEditing ? 'editing' : 'creating'">
                {{ isEditing ? 'EDITING' : 'CREATING' }}
              </div>
            </div>

            <div v-if="errorMessage" class="error-alert">
              <i class="fas fa-exclamation-triangle"></i> {{ errorMessage }}
            </div>

            <form @submit.prevent="submitForm" class="compact-form">
              <div class="form-grid">
                <div class="form-group">
                  <label>Full Name</label>
                  <input v-model="form.name" required placeholder="John Doe" />
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input v-model="form.email" type="email" required :disabled="isEditing" placeholder="user@example.com" />
                </div>
                <div class="form-group">
                  <label>Password {{ isEditing ? '(leave blank to keep)' : '' }}</label>
                  <input v-model="form.password" type="password" :required="!isEditing" />
                </div>
                <div class="form-group">
                  <label>Phone (Optional)</label>
                  <input v-model="form.phone" placeholder="+639171234567" />
                </div>
                <div class="form-group">
                  <label>Dealer</label>
                  <select v-model="form.dealer_id">
                    <option :value="null">None (Customer)</option>
                    <option v-for="d in dealers" :key="d.id" :value="d.id">{{ d.name }}</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Role</label>
                  <select v-model="form.role" required>
                    <option value="buyer">Customer</option>
                    <option value="dealer">Dealer</option>
                    <option value="admin">Administrator</option>
                  </select>
                </div>
              </div>

              <div class="form-actions">
                <button v-if="isEditing" type="button" @click="cancelEdit" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn" :disabled="loading">
                  {{ isEditing ? 'Update' : 'Create' }} User
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- RIGHT: Users Table with Search & Filter -->
        <div class="table-panel">
          <div class="table-header">
            <h3><i class="fas fa-users"></i> Users List</h3>
            <div class="controls">
              <div class="search-box">
                <i class="fas fa-search"></i>
                <input v-model="searchQuery" placeholder="Search name or email..." @input="debouncedSearch" />
              </div>
              <select v-model="filterRole" @change="applyFilters">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="dealer">Dealer</option>
                <option value="buyer">Customer</option>
              </select>
              <button @click="refreshAll" class="refresh-btn">
                <i class="fas fa-sync-alt"></i>
              </button>
            </div>
          </div>

          <div class="table-body">
            <div v-if="filteredUsers.length === 0" class="empty">
              <p>No users found</p>
            </div>
            <div v-else class="users-table-wrap">
              <table class="users-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Dealer</th>
                  <th>Last Login</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="u in pagedUsers" :key="u.id">
                  <td>#{{ u.id }}</td>
                  <td><strong>{{ u.name }}</strong></td>
                  <td>{{ u.email }}</td>
                  <td>
                    <span class="role-badge" :class="u.role">
                      {{ u.role === 'buyer' ? 'Customer' : u.role.toUpperCase() }}
                    </span>
                  </td>
                  <td>{{ u.dealer_name || '—' }}</td>
                  <td>{{ u.last_login ? formatDate(u.last_login) : 'Never' }}</td>
                  <td class="actions">
                    <button @click="editUser(u)" class="mini-btn edit"><i class="fas fa-edit"></i></button>
                    <button 
                      @click="deleteUser(u.id)" 
                      class="mini-btn delete"
                      :disabled="u.id === currentUser.id || (u.role === 'admin' && adminCount <= 1)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="pagination" v-if="totalPages > 1">
          <div class="pagination-info">Showing {{ startIndex }} - {{ endIndex }} of {{ filteredUsers.length }}</div>
          <div class="pagination-controls">
            <button @click="changePage(currentPage-1)" :disabled="currentPage <= 1">Prev</button>
            <button v-for="p in pageList" :key="p" @click="changePage(p)" :class="{ active: p === currentPage }">{{ p }}</button>
            <button @click="changePage(currentPage+1)" :disabled="currentPage >= totalPages">Next</button>
          </div>
        </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div v-if="toastMessage" class="toast" :class="toastType">
      {{ toastMessage }}
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner">
        <div class="spinner-ring"></div>
      </div>
      <p>{{ loadingMessage }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const API_BASE = 'http://localhost:8000/api/user'

const loading = ref(false)
const loadingMessage = ref('Loading...')
const users = ref([])
const dealers = ref([])
const isEditing = ref(false)
const editId = ref(null)
const errorMessage = ref('')
const toastMessage = ref('')
const toastType = ref('success')
const currentUser = ref({ id: null })

const form = ref({
  name: '', email: '', password: '', phone: '', dealer_id: null, role: 'buyer'
})

const searchQuery = ref('')
const filterRole = ref('')
const filteredUsers = ref([])

// Pagination state (declare early so functions can reference it)
const currentPage = ref(1)
const perPage = ref(7)

const totalPages = computed(() => Math.max(1, Math.ceil(filteredUsers.value.length / perPage.value)))

const pagedUsers = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredUsers.value.slice(start, start + perPage.value)
})

const startIndex = computed(() => filteredUsers.value.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1)
const endIndex = computed(() => Math.min(filteredUsers.value.length, currentPage.value * perPage.value))

const pageList = computed(() => {
  const pages = []
  for (let i = 1; i <= totalPages.value; i++) pages.push(i)
  return pages
})

const changePage = (p) => {
  if (p < 1) p = 1
  if (p > totalPages.value) p = totalPages.value
  currentPage.value = p
}

// Ensure current page remains valid if filteredUsers length changes
watch(filteredUsers, () => {
  if (currentPage.value > totalPages.value) currentPage.value = totalPages.value
})


let searchTimeout = null
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(applyFilters, 300)
}

const adminCount = computed(() => users.value.filter(u => u.role === 'admin').length)

const getToken = () => localStorage.getItem('access_token')
const getHeaders = () => ({
  'Content-Type': 'application/json',
  Authorization: `Bearer ${getToken()}`
})

const submitForm = async () => {
  errorMessage.value = ''
  loading.value = true
  loadingMessage.value = isEditing.value ? 'Updating user...' : 'Creating user...'

  const url = isEditing.value ? `${API_BASE}/update/${editId.value}` : `${API_BASE}/create`
  const method = isEditing.value ? 'PUT' : 'POST'
  const body = { ...form.value }
  if (isEditing.value && !body.password) delete body.password

  try {
    const res = await fetch(url, { method, headers: getHeaders(), body: JSON.stringify(body) })
    if (res.ok) {
      toast(isEditing.value ? 'User updated!' : 'User created!')
      cancelEdit()
      fetchUsers()
    } else {
      const data = await res.json()
      errorMessage.value = data.message || 'Failed'
    }
  } catch {
    errorMessage.value = 'Network error'
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id) => {
  if (!confirm('Delete user permanently?')) return
  loading.value = true
  try {
    await fetch(`${API_BASE}/delete/${id}`, { method: 'DELETE', headers: getHeaders() })
    toast('User deleted')
    fetchUsers()
  } catch { toast('Delete failed', 'error') }
  finally { loading.value = false }
}

const editUser = (user) => {
  isEditing.value = true
  editId.value = user.id
  form.value = { ...user, password: '' }
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const cancelEdit = () => {
  isEditing.value = false
  editId.value = null
  form.value = { name: '', email: '', password: '', phone: '', dealer_id: null, role: 'buyer' }
  errorMessage.value = ''
}

const fetchUsers = async () => {
  loading.value = true
  try {
    const res = await fetch(`${API_BASE}/list`, { headers: getHeaders() })
    users.value = await res.json()
    applyFilters()
  } catch (e) { console.error(e) }
  finally { loading.value = false }
}

const fetchDealers = async () => {
  try {
    const res = await fetch('http://localhost:8000/api/dealers')
    const data = await res.json()
    dealers.value = data.dealers || data || []
  } catch (e) { console.error(e) }
}

const applyFilters = () => {
  let result = users.value

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(u =>
      u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
    )
  }
  if (filterRole.value) {
    result = result.filter(u => u.role === filterRole.value)
  }

  filteredUsers.value = result
  // reset to first page when filters change
  currentPage.value = 1
}

const refreshAll = () => fetchUsers()

const toast = (msg, type = 'success') => {
  toastMessage.value = msg
  toastType.value = type
  setTimeout(() => toastMessage.value = '', 4000)
}

const formatDate = (d) => d ? new Date(d).toLocaleString('en-US', {
  month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit'
}) : '—'

onMounted(() => {
  const token = getToken()
  if (token) {
    try {
      const payload = JSON.parse(atob(token.split('.')[1]))
      currentUser.value.id = payload.sub
    } catch {}
  }
  fetchUsers()
  fetchDealers()
})

watch(users, applyFilters, { deep: true })
</script>

<style scoped>
/* LAVA RED CYBERPUNK — #d40000 */
.dashboard { min-height: 100vh; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #fff; position: relative; overflow-x: hidden; }
.background-glow { position: fixed; inset: 0; background: radial-gradient(circle at 20% 80%, rgba(212,0,0,0.15), transparent 50%); pointer-events: none; z-index: -1; }
.dashboard-content { padding: 30px; max-width: 1600px; margin: 0 auto; }

.panel-header { margin-bottom: 40px; }
.header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 30px; }
.title-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #d40000, #a80000); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 10px 30px rgba(212,0,0,0.5); }
.main-title { font-size: 2.8rem; font-weight: 900; background: linear-gradient(135deg, #fff, #d40000); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.subtitle { font-size: 1.1rem; color: rgba(255,255,255,0.7); margin-top: 8px; }

.stat-card.big { background: rgba(255,255,255,0.08); padding: 24px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; gap: 20px; min-width: 280px; }
.stat-icon { width: 60px; height: 60px; background: #2d2d2d; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #d40000; font-size: 1.8rem; }
.stat-value { font-size: 3rem; font-weight: 900; }
.stat-label { font-size: 1rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; }

.main-grid { display: grid; grid-template-columns: 420px 1fr; gap: 30px; }
.form-panel, .table-panel { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.5); backdrop-filter: blur(15px); }

.form-container { padding: 30px; }
.form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.form-title { font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 12px; }
.form-badge { padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; }
.form-badge.creating { background: rgba(16,185,129,0.15); color: #10b981; }
.form-badge.editing { background: rgba(245,158,11,0.15); color: #f59e0b; }

.compact-form .form-grid { display: grid; gap: 18px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.95rem; }
input, select { width: 100%; padding: 14px; background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.15); border-radius: 12px; color: white; font-size: 1rem; }
input:focus, select:focus { outline: none; border-color: #d40000; background: rgba(255,255,255,0.08); }
.form-actions { margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end; }
.cancel-btn, .submit-btn { padding: 14px 28px; border-radius: 12px; font-weight: 600; }
.cancel-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); }
.submit-btn { background: linear-gradient(135deg, #d40000, #a80000); border: none; color: white; }

.table-panel { padding: 30px; }
.table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.table-header h3 { font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 12px; }
.controls { display: flex; align-items: center; gap: 12px; }
.search-box { position: relative; width: 280px; }
.search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #d40000; }
.search-box input { width: 100%; padding: 12px 16px 12px 44px; background: rgba(255,255,255,0.05); border: 1.5px solid rgba(255,255,255,0.15); border-radius: 12px; color: white; }
.search-box input:focus { border-color: #d40000; outline: none; }
select { padding: 12px 16px; background: rgba(255,255,255,0.05); border: 1.5px solid rgba(255,255,255,0.15); border-radius: 12px; color: white; }
.refresh-btn { padding: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; color: white; }

.users-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
.users-table th { text-align: left; padding: 16px 12px; background: rgba(212,0,0,0.2); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
.users-table td { padding: 16px 12px; border-bottom: 1px solid rgba(255,255,255,0.08); }
.role-badge { padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
.role-badge.admin { background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
.role-badge.dealer { background: rgba(245,158,11,0.2); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
.role-badge.buyer { background: rgba(16,185,129,0.2); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
.actions button { padding: 8px 10px; border: none; border-radius: 8px; margin-right: 8px; }
.actions .edit { background: rgba(245,158,11,0.25); color: #f59e0b; }
.actions .delete { background: rgba(239,68,68,0.25); color: #ef4444; }

.empty { text-align: center; padding: 80px 20px; color: rgba(255,255,255,0.5); font-size: 1.1rem; }

/* Scroll wrapper for wide tables */
.users-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; max-width: 100%; }
.users-table { min-width: 900px; }

.pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; gap: 12px; flex-wrap: wrap; }
.pagination-info { color: rgba(255,255,255,0.75); font-size: 0.95rem; }
.pagination-controls { display: flex; gap: 8px; align-items: center; }
.pagination-controls button { padding: 8px 10px; border-radius: 8px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: white; }
.pagination-controls button.active { background: linear-gradient(135deg,#d40000,#a80000); border: none; }
.pagination-controls button:disabled { opacity: 0.45; cursor: not-allowed; }

.toast { position: fixed; bottom: 30px; right: 30px; padding: 16px 24px; border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); color: white; font-weight: 600; box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 9999; animation: slideIn 0.4s; }
.toast.error { background: linear-gradient(135deg, #ef4444, #dc2626); }
@keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }

.loading-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.95); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; color: white; }
.spinner-ring { width: 70px; height: 70px; border: 5px solid rgba(212,0,0,0.3); border-top-color: #d40000; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 1200px) {
  .main-grid { grid-template-columns: 1fr; }
  .form-panel { order: 2; }
  .table-panel { order: 1; }
}

@media (max-width: 768px) {
  .header-content { flex-direction: column; align-items: flex-start; }
  .stat-card.big { width: 100%; justify-content: center; }
  .search-box { width: 100%; }
}
</style>
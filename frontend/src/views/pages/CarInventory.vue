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
              <i class="fas fa-warehouse"></i>
            </div>
            <div class="title-text">
              <h1 class="main-title">Car Inventory</h1>
              <p class="subtitle">Complete overview of all vehicles in stock</p>
            </div>
          </div>
          <div class="header-stats">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-car"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ cars.length }}</div>
                <div class="stat-label">Total Vehicles</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ availableCount }}</div>
                <div class="stat-label">Available</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ reservedCount }}</div>
                <div class="stat-label">Reserved</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon error">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ soldCount }}</div>
                <div class="stat-label">Sold</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="dashboard-grid">
        <!-- Inventory Panel -->
        <div class="cars-management-panel">
          <!-- Filters Section (Replaces Form) -->
          <div class="form-section compact-filters">
            <div class="form-container">
              <div class="form-header">
                <h3 class="form-title">
                  <i class="fas fa-filter"></i>
                  Inventory Filters
                </h3>
                <div class="form-badge creating">VIEW MODE</div>
              </div>

              <div class="form-grid" style="align-items: end;">
                <div class="form-group">
                  <label class="form-label"><i class="fas fa-search"></i> Search</label>
                  <input v-model="filters.search" class="form-input" placeholder="Make, model, year..." />
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-tag"></i> Make</label>
                  <select v-model="filters.make" class="form-input">
                    <option value="">All Makes</option>
                    <option v-for="make in makes" :key="make" :value="make">{{ make }}</option>
                  </select>
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                  <select v-model="filters.status" class="form-input">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="reserved">Reserved</option>
                    <option value="sold">Sold</option>
                    <option value="draft">Draft</option>
                  </select>
                  <div class="form-focus-line"></div>
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fas fa-dollar-sign"></i> Price Range</label>
                  <div class="price-range">
                    <input v-model.number="filters.minPrice" type="number" class="form-input" placeholder="Min" />
                    <span style="color: rgba(255,255,255,0.6); padding: 0 8px;">→</span>
                    <input v-model.number="filters.maxPrice" type="number" class="form-input" placeholder="Max" />
                  </div>
                </div>

                <div class="form-actions">
                  <button @click="fetchCars" class="refresh-btn">
                    <i class="fas fa-sync-alt"></i> Refresh
                  </button>
                  <button @click="exportCSV" class="submit-btn">
                    <i class="fas fa-download"></i> Export CSV
                    <div class="btn-sparkle"><i class="fas fa-bolt"></i></div>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Table Section -->
          <div class="table-section">
            <div class="table-container">
              <div class="table-header">
                <h3 class="table-title"><i class="fas fa-list"></i> Vehicle Inventory</h3>
                <div class="table-actions">
                  <span class="result-count">{{ filteredCars.length }} vehicles found</span>
                </div>
              </div>

              <div class="table-content">
                <div v-if="loading" class="empty-state">
                  <div class="loading-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-car"><i class="fas fa-car"></i></div>
                  </div>
                  <p>Loading inventory...</p>
                </div>

                <div v-else-if="filteredCars.length === 0" class="empty-state">
                  <div class="empty-icon"><i class="fas fa-car-crash"></i></div>
                  <h3>No Vehicles Match</h3>
                  <p>Try adjusting your filters</p>
                </div>

                <div v-else class="enhanced-table">
                  <div class="table-responsive">
                    <table class="vehicles-table">
                      <thead>
                        <tr>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-hashtag"></i> ID</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-store"></i> Dealer</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-tag"></i> Make</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-car"></i> Model</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-calendar"></i> Year</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-dollar-sign"></i> Price</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-info-circle"></i> Status</div></th>
                          <th class="table-header-cell"><div class="header-content"><i class="fas fa-image"></i> Image</div></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="car in filteredCars" :key="car.id" class="table-row">
                          <td class="table-cell id-cell">#{{ car.id }}</td>
                          <td class="table-cell"><div class="dealer-info"><i class="fas fa-store"></i> {{ car.dealer_id }}</div></td>
                          <td class="table-cell"><span class="make-text">{{ car.make }}</span></td>
                          <td class="table-cell"><span class="model-text">{{ car.model }} {{ car.variant || '' }}</span></td>
                          <td class="table-cell"><span class="year-badge">{{ car.year }}</span></td>
                          <td class="table-cell"><span class="price-text">${{ Number(car.price).toLocaleString() }}</span></td>
                          <td class="table-cell">
                            <span class="status-badge" :class="car.status">
                              {{ car.status.charAt(0).toUpperCase() + car.status.slice(1) }}
                            </span>
                          </td>
                          <td class="table-cell">
                            <img
                              v-if="car.main_image"
                              :src="car.main_image.startsWith('data:') ? car.main_image : 'http://localhost:8000' + car.main_image"
                              alt="Car"
                              width="80"
                              style="border-radius: 10px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.4); max-width:100%; height:auto;"
                            />
                            <span v-else class="variant-empty">—</span>
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
        <div class="spinner-car"><i class="fas fa-car"></i></div>
      </div>
      <p>Processing inventory data...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const theme = ref(localStorage.getItem('dashboard-theme') || 'dark')
const cars = ref([])
const loading = ref(false)

const filters = ref({
  search: '',
  make: '',
  status: '',
  minPrice: null,
  maxPrice: null
})

const makes = computed(() => [...new Set(cars.value.map(c => c.make))].sort())

const filteredCars = computed(() => {
  return cars.value.filter(car => {
    const search = filters.value.search.toLowerCase()
    const matchesSearch = !search || 
      `${car.make} ${car.model} ${car.variant || ''} ${car.year} ${car.dealer_id}`.toLowerCase().includes(search)
    const matchesMake = !filters.value.make || car.make === filters.value.make
    const matchesStatus = !filters.value.status || car.status === filters.value.status
    const matchesMin = !filters.value.minPrice || car.price >= filters.value.minPrice
    const matchesMax = !filters.value.maxPrice || car.price <= filters.value.maxPrice
    return matchesSearch && matchesMake && matchesStatus && matchesMin && matchesMax
  })
})

const availableCount = computed(() => cars.value.filter(c => c.status === 'available').length)
const reservedCount = computed(() => cars.value.filter(c => c.status === 'reserved').length)
const soldCount = computed(() => cars.value.filter(c => c.status === 'sold').length)

const fetchCars = async () => {
  loading.value = true
  try {
    // GET USER FROM localStorage
    const user = JSON.parse(localStorage.getItem('user') || '{}')

    const res = await fetch('http://localhost:8000/listcars', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-User': JSON.stringify(user)  // ← ITO ANG KULANG MO BRO!!!
      }
    })

    const data = await res.json()
    if (data.status === 'success') {
      cars.value = data.cars || []
    }
  } catch (err) {
    console.error('Fetch cars error:', err)
    notify('Failed to load cars', 'error')
  } finally {
    loading.value = false
  }
}

const exportCSV = () => {
  const headers = 'ID,Dealer ID,Make,Model,Variant,Year,Price,Status,Type,Fuel,Transmission,Color\n'
  const rows = filteredCars.value.map(c => 
    `${c.id},${c.dealer_id},${c.make},${c.model},${c.variant || ''},${c.year},${c.price},${c.status},${c.type || ''},${c.fuel_type || ''},${c.transmission || ''},${c.color || ''}`
  ).join('\n')
  const csv = headers + rows
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `carlux_inventory_${new Date().toISOString().slice(0,10)}.csv`
  a.click()
  URL.revokeObjectURL(url)
}

onMounted(fetchCars)
</script>

<style scoped>
/* 100% REUSING THE EXACT SAME STYLES FROM YOUR ORIGINAL CARS MANAGEMENT */
/* Paste ALL the <style scoped> from your CarsManagement.vue here */
/* (Exactly the same — no changes needed) */
.dashboard { min-height: 100vh; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%); color: #ffffff; position: relative; overflow-x: hidden; }
.background-glow { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 20% 80%, rgba(212,0,0,0.15) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(26,26,26,0.1) 0%, transparent 50%); pointer-events: none; z-index: -1; }
.dashboard-content { flex: 1; padding: 30px; display: flex; flex-direction: column; gap: 30px; max-width: 1400px; margin: 0 auto; width: 100%; }
.dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 30px; }

/* === ALL ORIGINAL STYLES FROM CARS MANAGEMENT === */
/* (Copy-paste the entire <style scoped> block from your first masterpiece here) */
/* Including .panel-header, .cars-management-panel, .form-section, .table-section, .vehicles-table, .action-btn, .loading-overlay, etc. */

.panel-header { padding: 40px 40px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
.header-content { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 30px; }
.title-section { display: flex; align-items: center; gap: 20px; }
.title-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #d40000, #a80000); border-radius: 18px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem; box-shadow: 0 10px 30px rgba(212,0,0,0.4); }
.main-title { font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #ffffff, #f0f0f0, #d40000); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
.subtitle { font-size: 1.1rem; color: rgba(255,255,255,0.7); font-weight: 500; }
.header-stats { display: flex; gap: 20px; }
.stat-card { display: flex; align-items: center; gap: 15px; background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); min-width: 180px; }
.stat-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #2d2d2d, #1a1a1a); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #d40000; font-size: 1.3rem; }
.success { background: linear-gradient(135deg, #166534, #0d4f2c) !important; color: #10b981 !important; }
.warning { background: linear-gradient(135deg, #92400e, #6b3000) !important; color: #f59e0b !important; }
.error { background: linear-gradient(135deg, #7f1d1d, #5f1515) !important; color: #ef4444 !important; }
.stat-value { font-size: 2rem; font-weight: 800; color: white; }
.stat-label { font-size: 0.9rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; }

.cars-management-panel { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); border-radius: 25px; backdrop-filter: blur(20px); overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); }
.form-section { padding: 40px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.form-container { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; backdrop-filter: blur(10px); }
.form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.form-title { font-size: 1.5rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 12px; }
.form-title i { color: #d40000; }
.form-badge { padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.form-badge.creating { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
.form-group { position: relative; }
.form-label { display: flex; align-items: center; gap: 8px; font-weight: 600; color: white; margin-bottom: 8px; font-size: 0.9rem; }
.form-label i { color: #d40000; width: 16px; }
.form-input, select.form-input { width: 100%; background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 15px; color: white; font-size: 1rem; transition: all 0.3s ease; }
.form-input:focus { outline: none; border-color: #d40000; background: rgba(255,255,255,0.08); }
.form-focus-line { position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: #d40000; transition: all 0.3s ease; transform: translateX(-50%); }
.form-input:focus ~ .form-focus-line { width: 100%; }
.price-range { display: flex; align-items: center; gap: 10px; }
.form-actions { display: flex; gap: 15px; justify-content: flex-end; flex-wrap: wrap; }
.refresh-btn { padding: 15px 25px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; font-weight: 600; }
.refresh-btn:hover { background: rgba(255,255,255,0.1); transform: translateY(-2px); }
.submit-btn { position: relative; padding: 15px 30px; background: linear-gradient(135deg, #d40000, #a80000); border: none; border-radius: 12px; color: white; cursor: pointer; font-weight: 600; overflow: hidden; box-shadow: 0 8px 25px rgba(212,0,0,0.3); display: flex; align-items: center; gap: 10px; }
.submit-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(212,0,0,0.5); }
.btn-sparkle { animation: sparkle 2s ease-in-out infinite; }
@keyframes sparkle { 0%,100%{transform:scale(1) rotate(0deg)} 50%{transform:scale(1.2) rotate(180deg)} }

/* Table styles — exactly the same as CarsManagement */
.table-section { padding: 40px; }
.table-container { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; backdrop-filter: blur(10px); }
.table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.table-title { font-size: 1.3rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 10px; }
.table-title i { color: #d40000; }
.result-count { font-size: 0.9rem; color: rgba(255,255,255,0.6); }
.enhanced-table .table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 15px;
  border: 1px solid rgba(255,255,255,0.1);
  max-width: 100%;
}
.vehicles-table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.02); min-width: 0; }
.table-header-cell { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-weight: 700; color: white; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
.header-content { display: flex; align-items: center; gap: 8px; }
.header-content i { color: #d40000; font-size: 0.8rem; }
.table-row:hover { background: rgba(255,255,255,0.03); }
.table-cell { padding: 20px; color: rgba(255,255,255,0.9); border-bottom: 1px solid rgba(255,255,255,0.05); }
.id-cell { font-weight: 600; }
.dealer-info { display: flex; align-items: center; gap: 8px; }
.make-text { font-weight: 600; color: white; }
.model-text { color: white; font-size: 1rem; }
.year-badge { background: rgba(59,130,246,0.1); color: #3b82f6; padding: 6px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 600; }
.price-text { color: #d40000; font-weight: 700; }
.status-badge { padding: 6px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 600; text-transform: capitalize; }
.status-badge.available { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.status-badge.reserved { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
.status-badge.sold { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
.variant-empty { color: rgba(255,255,255,0.4); font-style: italic; }

.empty-state { text-align: center; padding: 80px 40px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1); }
.empty-icon { font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 20px; }

.loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10,10,10,0.95); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(10px); }
.loading-spinner { position: relative; width: 80px; height: 80px; margin-bottom: 20px; }
.spinner-ring { width: 100%; height: 100%; border: 4px solid rgba(212,0,0,0.3); border-top: 4px solid #d40000; border-radius: 50%; animation: spin 1s linear infinite; }
.spinner-car { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); font-size: 2rem; color: #d40000; }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 1200px) { .header-content { flex-direction: column; align-items: flex-start; } }
@media (max-width: 768px) { .dashboard-content { padding: 20px; } .panel-header, .form-section, .table-section { padding: 20px; } }

/* Compact filter overrides for this inventory view */
.compact-filters .form-container {
  padding: 12px; /* reduce container padding */
}
.compact-filters .form-section {
  padding: 12px 20px; /* reduce outer section padding */
}
.compact-filters .form-grid {
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 12px;
  align-items: center;
}
.compact-filters .form-label { font-size: 0.8rem; }
.compact-filters .form-input { padding: 10px; font-size: 0.9rem; }
.compact-filters .price-range input.form-input { padding: 8px; }
.compact-filters .form-actions { gap: 10px; justify-content: flex-start; }
.compact-filters .refresh-btn, .compact-filters .submit-btn { padding: 10px 14px; font-size: 0.9rem; }

@media (max-width: 768px) {
  .compact-filters .form-grid { grid-template-columns: 1fr; }
  .compact-filters .form-actions { justify-content: stretch; }
}
</style>
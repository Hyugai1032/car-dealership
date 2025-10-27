<template>
  <div class="admin-container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="logo-section">
                <div class="logo">🚗 AUTOELITE</div>
                <div class="admin-badge">Admin Panel</div>
            </div>
      <nav class="nav-menu">
        <div class="nav-section">
          <div class="nav-header">MAIN</div>
          <a href="#" class="nav-item active">📊 Dashboard</a>
          <a href="#" class="nav-item">🚘 Cars Configuration</a>
          <a href="#" class="nav-item">👥 Customer Management</a>
        </div>
        <div class="nav-section">
          <div class="nav-header">OPERATIONS</div>
          <a href="#" class="nav-item">📅 Appointments</a>
          <a href="#" class="nav-item">🏢 Dealers</a>
          <a href="#" class="nav-item">🔧 Services</a>
          <a href="#" class="nav-item">🛡️ Warranties</a>
        </div>
        <div class="nav-section">
          <div class="nav-header">SYSTEM</div>
          <a href="#" class="nav-item">⚙️ Site Configuration</a>
          <a href="#" class="nav-item">📤 Export Documents</a>
          <a href="#" class="nav-item">📈 Analytics</a>
          <a href="#" class="nav-item">👤 User Management</a>
        </div>
        <div class="nav-section">
          <a href="#" class="nav-item logout">🚪 Logout</a>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <header class="admin-header">
        <div class="header-left">
          <h1>Cars Configuration</h1>
          <p>Manage and configure vehicle inventory</p>
        </div>
        <div class="header-actions">
          <button class="btn btn-secondary">📤 Export</button>
          <button class="btn btn-primary" @click="showAddCarModal = true">➕ Add New Car</button>
        </div>
      </header>

      <!-- Search & Filters -->
      <div class="search-section">
        <div class="search-box">
          <input v-model="searchQuery" type="text" placeholder="Search cars by make, model, or variant..." class="search-input"/>
          <span class="search-icon">🔍</span>
        </div>
        <div class="filters">
          <select v-model="statusFilter" class="filter-select">
            <option value="">All Status</option>
            <option value="available">Available</option>
            <option value="reserved">Reserved</option>
            <option value="sold">Sold</option>
          </select>
          <select v-model="typeFilter" class="filter-select">
            <option value="">All Types</option>
            <option value="sedan">Sedan</option>
            <option value="suv">SUV</option>
            <option value="truck">Truck</option>
            <option value="coupe">Coupe</option>
          </select>
        </div>
      </div>

      <!-- Loading & Error -->
      <div v-if="loading" class="loading-section">
        <div class="loading-spinner"></div>
        <p>Loading cars data...</p>
      </div>
      <div v-else-if="error" class="error-section">
        <div class="error-icon">⚠️</div>
        <h3>Failed to load cars</h3>
        <p>{{ error }}</p>
        <button class="btn btn-primary" @click="fetchCars">Retry</button>
      </div>

      <!-- Cars Grid -->
      <div v-else class="cars-grid">
        <div v-for="car in filteredCars" :key="car.id" class="car-card" :class="{selected: selectedCar?.id === car.id}" @click="selectCar(car)">
          <div class="car-image-section">
            <img :src="car.main_image || '/api/placeholder/300/200'" :alt="`${car.make} ${car.model}`" class="car-image"/>
            <div class="car-status" :class="car.status">{{ car.status }}</div>
          </div>
          <div class="car-info">
            <h3 class="car-title">{{ car.make }} {{ car.model }}</h3>
            <p class="car-variant">{{ car.variant }} • {{ car.year }}</p>
            <div class="car-specs">
              <span class="spec-item">🚗 {{ car.type }}</span>
              <span class="spec-item">⛽ {{ car.fuel_type }}</span>
              <span class="spec-item">📏 {{ car.mileage }} km</span>
            </div>
            <div class="car-price-section">
              <span class="car-price">₱{{ formatPrice(car.price) }}</span>
              <div class="car-actions">
                <button class="icon-btn" @click.stop="editCar(car)">✏️</button>
                <button class="icon-btn delete" @click.stop="deleteCar(car.id)">🗑️</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Car Configuration Panel -->
      <div v-if="selectedCar" class="config-panel">
        <div class="panel-header">
          <h2>Configure {{ selectedCar.make }} {{ selectedCar.model }}</h2>
          <button class="close-btn" @click="selectedCar=null">✕</button>
        </div>
        <div class="panel-content">
          <!-- Image Gallery -->
          <div class="config-section">
            <h3 class="section-title">Image Gallery</h3>
            <div class="image-gallery">
              <div class="main-image">
                <img :src="selectedCar.main_image" :alt="`${selectedCar.make} ${selectedCar.model}`">
              </div>
              <div class="gallery-thumbnails">
                <div v-for="(img, i) in selectedCar.gallery || []" :key="i" class="thumbnail">
                  <img :src="img" :alt="`Gallery image ${i+1}`"/>
                </div>
                <button class="add-image-btn" @click="addImageToGallery">+ Add Image</button>
              </div>
            </div>
          </div>

          <!-- Basic Info -->
          <div class="config-section">
            <h3 class="section-title">Basic Information</h3>
            <div class="info-grid">
              <div class="info-field"><label>Make</label><input v-model="selectedCar.make" class="config-input"/></div>
              <div class="info-field"><label>Model</label><input v-model="selectedCar.model" class="config-input"/></div>
              <div class="info-field"><label>Variant</label><input v-model="selectedCar.variant" class="config-input"/></div>
              <div class="info-field"><label>Year</label><input type="number" v-model="selectedCar.year" class="config-input"/></div>
            </div>
          </div>

          <!-- Specs -->
          <div class="config-section">
            <h3 class="section-title">Specifications</h3>
            <div class="specs-grid">
              <div class="spec-field"><label>Type</label><select v-model="selectedCar.type" class="config-select"><option value="sedan">Sedan</option><option value="suv">SUV</option><option value="truck">Truck</option><option value="coupe">Coupe</option></select></div>
              <div class="spec-field"><label>Fuel Type</label><select v-model="selectedCar.fuel_type" class="config-select"><option value="gasoline">Gasoline</option><option value="diesel">Diesel</option><option value="electric">Electric</option><option value="hybrid">Hybrid</option></select></div>
              <div class="spec-field"><label>Transmission</label><select v-model="selectedCar.transmission" class="config-select"><option value="automatic">Automatic</option><option value="manual">Manual</option></select></div>
              <div class="spec-field"><label>Mileage</label><input type="number" v-model="selectedCar.mileage" class="config-input"/></div>
              <div class="spec-field"><label>Color</label><input type="text" v-model="selectedCar.color" class="config-input"/></div>
              <div class="spec-field"><label>Price</label><input type="number" v-model="selectedCar.price" class="config-input"/></div>
            </div>
          </div>

          <!-- Status & Warranty -->
          <div class="config-section">
            <h3 class="section-title">Status & Warranty</h3>
            <div class="status-section">
              <div class="status-field"><label>Status</label><select v-model="selectedCar.status" class="config-select"><option value="available">Available</option><option value="reserved">Reserved</option><option value="sold">Sold</option></select></div>
              <div class="warranty-field"><label>Warranty Info</label><textarea v-model="selectedCar.warranty_info" class="config-textarea" placeholder="Enter warranty details..."></textarea></div>
            </div>
          </div>

          <!-- Description -->
          <div class="config-section">
            <h3 class="section-title">Description</h3>
            <textarea v-model="selectedCar.description" class="config-textarea large" placeholder="Enter detailed car description..."></textarea>
          </div>

          <!-- Actions -->
          <div class="action-buttons">
            <button class="btn btn-secondary" @click="selectedCar=null">Cancel</button>
            <button class="btn btn-primary" @click="saveCar">Save Changes</button>
            <button class="btn btn-danger" @click="deleteCar(selectedCar.id)">Delete Car</button>
          </div>
        </div>
      </div>

      <!-- Add Car Modal -->
      <div v-if="showAddCarModal" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Add New Car</h2>
            <button class="close-btn" @click="showAddCarModal=false">✕</button>
          </div>
          <div class="modal-body">
            <div class="info-grid">
              <input v-model="newCar.make" placeholder="Make" class="config-input"/>
              <input v-model="newCar.model" placeholder="Model" class="config-input"/>
              <input v-model="newCar.variant" placeholder="Variant" class="config-input"/>
              <input type="number" v-model="newCar.year" placeholder="Year" class="config-input"/>
              <select v-model="newCar.type" class="config-select">
                <option value="sedan">Sedan</option><option value="suv">SUV</option><option value="truck">Truck</option><option value="coupe">Coupe</option>
              </select>
              <select v-model="newCar.fuel_type" class="config-select">
                <option value="gasoline">Gasoline</option><option value="diesel">Diesel</option><option value="electric">Electric</option><option value="hybrid">Hybrid</option>
              </select>
              <select v-model="newCar.transmission" class="config-select">
                <option value="automatic">Automatic</option><option value="manual">Manual</option>
              </select>
              <input type="number" v-model="newCar.mileage" placeholder="Mileage" class="config-input"/>
              <input type="text" v-model="newCar.color" placeholder="Color" class="config-input"/>
              <input type="number" v-model="newCar.price" placeholder="Price" class="config-input"/>
              <textarea v-model="newCar.description" placeholder="Description" class="config-textarea"></textarea>
            </div>
            <button class="btn btn-primary" @click="addNewCar">Add Car</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'

export default {
  name: 'AdminCarsConfig',
  setup() {
    const cars = ref([])
    const loading = ref(true)
    const error = ref(null)
    const selectedCar = ref(null)
    const showAddCarModal = ref(false)
    const searchQuery = ref('')
    const statusFilter = ref('')
    const typeFilter = ref('')

    const fetchCars = async () => {
      loading.value = true
      error.value = null
      try {
        const response = await fetch('http://localhost:8000/api/cars')
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data = await response.json()
        cars.value = data
      } catch (err) {
        error.value = err.message
        console.error('Error fetching cars:', err)
      } finally {
        loading.value = false
      }
    }

    const filteredCars = computed(() => {
      return cars.value.filter(car => {
        const matchesSearch = car.make.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            car.model.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            car.variant.toLowerCase().includes(searchQuery.value.toLowerCase())
        
        const matchesStatus = !statusFilter.value || car.status === statusFilter.value
        const matchesType = !typeFilter.value || car.type === typeFilter.value
        
        return matchesSearch && matchesStatus && matchesType
      })
    })

    const formatPrice = (price) => {
      return new Intl.NumberFormat('en-PH').format(price)
    }

    const selectCar = (car) => {
      selectedCar.value = { ...car }
    }

    const editCar = (car) => {
      selectedCar.value = { ...car }
    }

    const saveCar = async () => {
      try {
        // Implementation for saving car changes
        console.log('Saving car:', selectedCar.value)
        // await fetch(`http://localhost:8000/api/cars/${selectedCar.value.id}`, {
        //   method: 'PUT',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify(selectedCar.value)
        // })
        await fetchCars() // Refresh data
        selectedCar.value = null
      } catch (err) {
        console.error('Error saving car:', err)
      }
    }

    const deleteCar = async (carId) => {
      if (!confirm('Are you sure you want to delete this car?')) return
      
      try {
        // await fetch(`http://localhost:8000/api/cars/${carId}`, { method: 'DELETE' })
        await fetchCars() // Refresh data
        if (selectedCar.value?.id === carId) {
          selectedCar.value = null
        }
      } catch (err) {
        console.error('Error deleting car:', err)
      }
    }

    onMounted(() => {
      fetchCars()
    })

    return {
      cars,
      loading,
      error,
      selectedCar,
      showAddCarModal,
      searchQuery,
      statusFilter,
      typeFilter,
      filteredCars,
      fetchCars,
      formatPrice,
      selectCar,
      editCar,
      saveCar,
      deleteCar
    }
  }
}
</script>

<style scoped>
.admin-container {
  display: flex;
  min-height: 100vh;
  background: #1e1e1e;
  color: white;
}

/* Sidebar Styles */
.sidebar {
  width: 280px;
  background: #000;
  padding: 0;
  border-right: 1px solid #333;
}

.logo-section {
  padding: 2rem 1.5rem;
  border-bottom: 1px solid #333;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: #e53935;
  margin-bottom: 0.5rem;
}

.admin-badge {
  background: #e53935;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 4px;
  font-size: 0.75rem;
  display: inline-block;
}

.nav-menu {
  padding: 1rem 0;
}

.nav-section {
  margin-bottom: 2rem;
}

.nav-header {
  padding: 0 1.5rem 0.5rem;
  font-size: 0.75rem;
  color: #b0b0b0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  color: #b0b0b0;
  text-decoration: none;
  transition: all 0.3s ease;
  border-left: 3px solid transparent;
}

.nav-item:hover {
  background: #2b2b2b;
  color: white;
  border-left-color: #e53935;
}

.nav-item.active {
  background: #2b2b2b;
  color: white;
  border-left-color: #e53935;
}

.nav-icon {
  margin-right: 0.75rem;
  font-size: 1.1rem;
}

.nav-item.logout {
  margin-top: 2rem;
  border-top: 1px solid #333;
  padding-top: 1rem;
}

/* Main Content Styles */
.main-content {
  flex: 1;
  padding: 2rem;
  background: #1e1e1e;
}

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
}

.header-left h1 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  color: white;
}

.header-left p {
  color: #b0b0b0;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

/* Button Styles */
.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary {
  background: #e53935;
  color: white;
}

.btn-primary:hover {
  background: #d32f2f;
  transform: translateY(-2px);
}

.btn-secondary {
  background: #2b2b2b;
  color: white;
  border: 1px solid #444;
}

.btn-secondary:hover {
  background: #333;
  border-color: #e53935;
}

.btn-danger {
  background: #dc2626;
  color: white;
}

.btn-danger:hover {
  background: #b91c1c;
}

/* Search Section */
.search-section {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  flex: 1;
  min-width: 300px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  background: #2b2b2b;
  border: 1px solid #444;
  border-radius: 6px;
  color: white;
  font-size: 0.9rem;
}

.search-input:focus {
  outline: none;
  border-color: #e53935;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #b0b0b0;
}

.filter-select {
  padding: 0.75rem 1rem;
  background: #2b2b2b;
  border: 1px solid #444;
  border-radius: 6px;
  color: white;
  min-width: 150px;
}

/* Cars Grid */
.cars-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.car-card {
  background: #2b2b2b;
  border-radius: 8px;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 1px solid #444;
  cursor: pointer;
}

.car-card:hover {
  transform: translateY(-4px);
  border-color: #e53935;
  box-shadow: 0 8px 25px rgba(229, 57, 53, 0.15);
}

.car-card.selected {
  border-color: #e53935;
  box-shadow: 0 4px 20px rgba(229, 57, 53, 0.3);
}

.car-image-section {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.car-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.car-card:hover .car-image {
  transform: scale(1.05);
}

.car-status {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  padding: 0.25rem 0.75rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.car-status.available {
  background: #e53935;
  color: white;
}

.car-status.reserved {
  background: #f59e0b;
  color: black;
}

.car-status.sold {
  background: #6b7280;
  color: white;
}

.car-info {
  padding: 1.25rem;
}

.car-title {
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: white;
}

.car-variant {
  color: #b0b0b0;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.car-specs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.spec-item {
  background: #1e1e1e;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.8rem;
  color: #b0b0b0;
}

.car-price-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.car-price {
  font-size: 1.5rem;
  font-weight: bold;
  color: #e53935;
}

.car-actions {
  display: flex;
  gap: 0.5rem;
}

.icon-btn {
  background: none;
  border: none;
  color: #b0b0b0;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.icon-btn:hover {
  background: #1e1e1e;
  color: white;
}

.icon-btn.delete:hover {
  color: #e53935;
}

/* Configuration Panel */
.config-panel {
  background: #2b2b2b;
  border-radius: 8px;
  border: 1px solid #444;
  margin-top: 2rem;
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #444;
}

.panel-header h2 {
  color: white;
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  color: #b0b0b0;
  cursor: pointer;
  font-size: 1.25rem;
  padding: 0.25rem;
  border-radius: 4px;
}

.close-btn:hover {
  color: white;
  background: #1e1e1e;
}

.panel-content {
  padding: 1.5rem;
}

.config-section {
  margin-bottom: 2rem;
}

.section-title {
  color: #e53935;
  font-size: 1.1rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

/* Form Elements */
.config-input, .config-select, .config-textarea {
  width: 100%;
  padding: 0.75rem;
  background: #1e1e1e;
  border: 1px solid #444;
  border-radius: 4px;
  color: white;
  font-size: 0.9rem;
}

.config-input:focus, .config-select:focus, .config-textarea:focus {
  outline: none;
  border-color: #e53935;
}

.config-textarea.large {
  min-height: 120px;
  resize: vertical;
}

/* Grid Layouts */
.info-grid, .specs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.info-field, .spec-field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.info-field label, .spec-field label {
  color: #b0b0b0;
  font-size: 0.9rem;
  font-weight: 500;
}

.status-section {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 1rem;
}

/* Image Gallery */
.image-gallery {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
}

.main-image {
  background: #1e1e1e;
  border-radius: 4px;
  overflow: hidden;
  height: 300px;
}

.main-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.gallery-thumbnails {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.thumbnail {
  background: #1e1e1e;
  border-radius: 4px;
  overflow: hidden;
  height: 70px;
  cursor: pointer;
}

.thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.add-image-btn {
  background: #1e1e1e;
  border: 2px dashed #444;
  color: #b0b0b0;
  padding: 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.add-image-btn:hover {
  border-color: #e53935;
  color: #e53935;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 2rem;
  border-top: 1px solid #444;
}

/* Loading and Error States */
.loading-section, .error-section {
  text-align: center;
  padding: 3rem;
  background: #2b2b2b;
  border-radius: 8px;
  margin: 2rem 0;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #444;
  border-top: 4px solid #e53935;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: #2b2b2b;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #444;
}

.modal-body {
  padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .admin-container {
    flex-direction: column;
  }
  
  .sidebar {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #333;
  }
  
  .cars-grid {
    grid-template-columns: 1fr;
  }
  
  .search-section {
    flex-direction: column;
  }
  
  .search-box {
    min-width: auto;
  }
  
  .image-gallery {
    grid-template-columns: 1fr;
  }
  
  .status-section {
    grid-template-columns: 1fr;
  }
  
  .action-buttons {
    flex-direction: column;
  }
}
</style>
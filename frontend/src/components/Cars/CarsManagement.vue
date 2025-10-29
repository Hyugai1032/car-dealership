<template>
  <div class="dashboard" :class="theme">
    <!-- Side Navigation -->
    <Sidebar :theme="theme" @toggle-theme="toggleTheme" />

    <!-- Main Content Area -->
    <div class="main-content">
      <!-- Top Navigation -->
      <Header :theme="theme" />

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <!-- Hero / Stats Section -->
        <div class="dashboard-grid">

          <!-- Enhanced Cars Management Panel -->
          <div class="cars-management-panel">
            <!-- Panel Header -->
            <div class="panel-header">
              <div class="header-content">
                <div class="title-section">
                  <div class="title-icon">
                    <i class="fas fa-car"></i>
                  </div>
                  <div class="title-text">
                    <h2 class="main-title">Vehicle Inventory Management</h2>
                    <p class="subtitle">Manage your premium automotive collection with precision</p>
                  </div>
                </div>
                <div class="header-stats">
                  <div class="stat-card">
                    <div class="stat-icon">
                      <i class="fas fa-car-side"></i>
                    </div>
                    <div class="stat-info">
                      <span class="stat-value">{{ cars.length }}</span>
                      <span class="stat-label">Total Vehicles</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Enhanced Form Section -->
            <div class="form-section">
              <div class="form-container">
                <div class="form-header">
                  <h3 class="form-title">
                    <i :class="isEditing ? 'fas fa-edit' : 'fas fa-plus'"></i>
                    {{ isEditing ? 'Update Vehicle Details' : 'Add New Vehicle' }}
                  </h3>
                  <div class="form-badge" :class="isEditing ? 'editing' : 'creating'">
                    {{ isEditing ? 'EDITING' : 'CREATING' }}
                  </div>
                </div>

                <form @submit.prevent="isEditing ? updateCar() : createCar()" class="car-form">
                  <div class="form-grid">
                    <!-- Basic Information -->
                    <div class="form-group">
                      <label class="form-label">
                        <i class="fas fa-store"></i>
                        Dealer ID
                      </label>
                      <input
                        v-model="form.dealer_id"
                        type="number"
                        required
                        class="form-input"
                        placeholder="Enter dealer identification"
                      />
                      <div class="form-focus-line"></div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">
                        <i class="fas fa-tag"></i>
                        Make
                      </label>
                      <input
                        v-model="form.make"
                        required
                        class="form-input"
                        placeholder="Enter vehicle make (e.g., Toyota, BMW)"
                      />
                      <div class="form-focus-line"></div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">
                        <i class="fas fa-car"></i>
                        Model
                      </label>
                      <input
                        v-model="form.model"
                        required
                        class="form-input"
                        placeholder="Enter vehicle model (e.g., Camry, X5)"
                      />
                      <div class="form-focus-line"></div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">
                        <i class="fas fa-cogs"></i>
                        Variant
                      </label>
                      <input
                        v-model="form.variant"
                        class="form-input"
                        placeholder="Enter variant (e.g., Hybrid, Sport)"
                      />
                      <div class="form-focus-line"></div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">
                        <i class="fas fa-calendar"></i>
                        Year
                      </label>
                      <input
                        v-model="form.year"
                        type="number"
                        required
                        class="form-input"
                        placeholder="Enter manufacturing year"
                      />
                      <div class="form-focus-line"></div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">
                        <i class="fas fa-car-side"></i>
                        Type
                      </label>
                      <input
                        v-model="form.type"
                        class="form-input"
                        placeholder="Enter vehicle type (e.g., SUV, Sedan)"
                      />
                      <div class="form-focus-line"></div>
                    </div>

                    <!-- Media Section -->
                    <div class="form-group full-width">
                      <label class="form-label">
                        <i class="fas fa-image"></i>
                        Main Image Path
                      </label>
                      <input
                        v-model="form.main_image"
                        class="form-input"
                        placeholder="e.g., cars/mercedes-s-class-front.jpg"
                      />
                      <div class="form-focus-line"></div>
                      <div class="form-hint">
                        Path to the main vehicle image in storage
                      </div>
                    </div>

                    <div class="form-group full-width">
                      <label class="form-label">
                        <i class="fas fa-images"></i>
                        Gallery Images
                      </label>
                      <textarea
                        v-model="form.gallery"
                        class="form-textarea"
                        placeholder='JSON array format: ["cars/image1.jpg", "cars/image2.jpg"]'
                        rows="3"
                      ></textarea>
                      <div class="form-focus-line"></div>
                      <div class="form-hint">
                        Enter image paths as a JSON array for the gallery
                      </div>
                    </div>
                  </div>

                  <!-- Form Actions -->
                  <div class="form-actions">
                    <button
                      v-if="isEditing"
                      type="button"
                      @click="cancelEdit"
                      class="cancel-btn"
                    >
                      <i class="fas fa-times"></i>
                      Cancel Edit
                    </button>
                    <button
                      type="submit"
                      class="submit-btn"
                      :class="{ editing: isEditing }"
                    >
                      <i :class="isEditing ? 'fas fa-save' : 'fas fa-plus'"></i>
                      {{ isEditing ? 'Update Vehicle' : 'Add Vehicle' }}
                      <div class="btn-sparkle">
                        <i class="fas fa-bolt"></i>
                      </div>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Enhanced Cars Table -->
            <div class="table-section">
              <div class="table-container">
                <div class="table-header">
                  <h3 class="table-title">
                    <i class="fas fa-list"></i>
                    Vehicle Inventory
                  </h3>
                  <div class="table-actions">
                    <button @click="fetchCars" class="refresh-btn">
                      <i class="fas fa-sync-alt"></i>
                      Refresh
                    </button>
                  </div>
                </div>

                <div class="table-content">
                  <div v-if="cars.length === 0" class="empty-state">
                    <div class="empty-icon">
                      <i class="fas fa-car-crash"></i>
                    </div>
                    <h3>No Vehicles Found</h3>
                    <p>Start by adding your first vehicle to the inventory</p>
                  </div>

                  <div v-else class="enhanced-table">
                    <div class="table-responsive">
                      <table class="vehicles-table">
                        <thead>
                          <tr>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-hashtag"></i>
                                ID
                              </div>
                            </th>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-store"></i>
                                Dealer
                              </div>
                            </th>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-tag"></i>
                                Make
                              </div>
                            </th>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-car"></i>
                                Model
                              </div>
                            </th>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-calendar"></i>
                                Year
                              </div>
                            </th>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-cogs"></i>
                                Variant
                              </div>
                            </th>
                            <th class="table-header-cell">
                              <div class="header-content">
                                <i class="fas fa-wrench"></i>
                                Actions
                              </div>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="car in cars"
                            :key="car.id"
                            class="table-row"
                            :class="{ 'editing-row': isEditing && editId === car.id }"
                          >
                            <td class="table-cell id-cell">
                              <span class="cell-badge">#{{ car.id }}</span>
                            </td>
                            <td class="table-cell">
                              <div class="dealer-info">
                                <i class="fas fa-store"></i>
                                <span>{{ car.dealer_id }}</span>
                              </div>
                            </td>
                            <td class="table-cell">
                              <span class="make-text">{{ car.make }}</span>
                            </td>
                            <td class="table-cell">
                              <strong class="model-text">{{ car.model }}</strong>
                            </td>
                            <td class="table-cell">
                              <span class="year-badge">{{ car.year }}</span>
                            </td>
                            <td class="table-cell">
                              <span class="variant-tag" v-if="car.variant">
                                {{ car.variant }}
                              </span>
                              <span class="variant-empty" v-else>
                                -
                              </span>
                            </td>
                            <td class="table-cell actions-cell">
                              <div class="action-buttons">
                                <button
                                  @click="editCar(car)"
                                  class="action-btn edit-btn"
                                  :disabled="isEditing && editId !== car.id"
                                >
                                  <i class="fas fa-edit"></i>
                                  <span>Edit</span>
                                  <div class="btn-glow"></div>
                                </button>
                                <button
                                  @click="deleteCar(car.id)"
                                  class="action-btn delete-btn"
                                  :disabled="isEditing"
                                >
                                  <i class="fas fa-trash"></i>
                                  <span>Delete</span>
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
    </div>

    <!-- Background Glow Effects -->
    <div class="background-glow"></div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner">
        <div class="spinner-ring"></div>
        <div class="spinner-car">
          <i class="fas fa-car"></i>
        </div>
      </div>
      <p>Processing vehicle data...</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import Sidebar from '../layout/Sidebars.vue'
import Header from '../layout/Header.vue'
import StatsGrid from '../dashboard/StatsGrid.vue'

export default {
  name: 'CarsManagement',
  components: { Sidebar, Header, StatsGrid },
  setup() {
    const theme = ref(localStorage.getItem('dashboard-theme') || 'dark')
    const loading = ref(false)

    const toggleTheme = () => {
      theme.value = theme.value === 'dark' ? 'light' : 'dark'
      localStorage.setItem('dashboard-theme', theme.value)
    }

    const stats = ref({
      totalCars: '--',
      totalDealers: '--',
      pendingAppointments: '--',
      totalUsers: '--',
      revenue: '--'
    })

    const cars = ref([])
    const form = ref({
      dealer_id: '',
      make: '',
      model: '',
      variant: '',
      year: '',
      type: '',
      main_image: '',
      gallery: ''
    })
    const isEditing = ref(false)
    const editId = ref(null)

    const fetchCars = async () => {
      loading.value = true
      try {
        const res = await fetch('http://localhost:8000/api/cars')
        const data = await res.json()
        if (data.status === 'success') cars.value = data.cars
        // Update stats
        stats.value.totalCars = cars.value.length
      } catch (err) { 
        console.error(err) 
      } finally {
        loading.value = false
      }
    }

    const createCar = async () => {
      loading.value = true
      try {
        const payload = { ...form.value }
        payload.gallery = payload.gallery ? JSON.parse(payload.gallery) : []
        const res = await fetch('http://localhost:8000/api/cars', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
        const data = await res.json()
        if (data.status === 'success') {
          fetchCars()
          resetForm()
          showNotification('Vehicle added successfully!', 'success')
        } else {
          showNotification(data.message || 'Error adding vehicle', 'error')
        }
      } catch (err) { 
        console.error(err)
        showNotification('Network error occurred', 'error')
      } finally {
        loading.value = false
      }
    }

    const editCar = (car) => {
      isEditing.value = true
      editId.value = car.id
      form.value = { 
        ...car, 
        gallery: car.gallery ? JSON.stringify(car.gallery) : '[]'
      }
      // Scroll to form
      document.querySelector('.form-section').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
      })
    }

    const cancelEdit = () => {
      isEditing.value = false
      editId.value = null
      resetForm()
    }

    const updateCar = async () => {
      loading.value = true
      try {
        const payload = { ...form.value }
        payload.gallery = payload.gallery ? JSON.parse(payload.gallery) : []
        const res = await fetch(`http://localhost:8000/cars/${editId.value}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
        const data = await res.json()
        if (data.status === 'success') {
          fetchCars()
          cancelEdit()
          showNotification('Vehicle updated successfully!', 'success')
        } else {
          showNotification(data.message || 'Error updating vehicle', 'error')
        }
      } catch (err) { 
        console.error(err)
        showNotification('Network error occurred', 'error')
      } finally {
        loading.value = false
      }
    }

    const deleteCar = async (id) => {
      if (!confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) return
      
      loading.value = true
      try {
        const res = await fetch(`http://localhost:8000/cars/${id}`, { method: 'DELETE' })
        const data = await res.json()
        if (data.status === 'success') {
          fetchCars()
          showNotification('Vehicle deleted successfully!', 'success')
        } else {
          showNotification(data.message || 'Error deleting vehicle', 'error')
        }
      } catch (err) { 
        console.error(err)
        showNotification('Network error occurred', 'error')
      } finally {
        loading.value = false
      }
    }

    const resetForm = () => {
      form.value = {
        dealer_id: '',
        make: '',
        model: '',
        variant: '',
        year: '',
        type: '',
        main_image: '',
        gallery: ''
      }
    }

    const showNotification = (message, type) => {
      // You can integrate with your notification system here
      console.log(`${type.toUpperCase()}: ${message}`)
    }

    onMounted(() => {
      fetchCars()
    })

    return {
      theme,
      loading,
      toggleTheme,
      stats,
      cars,
      form,
      isEditing,
      editId,
      createCar,
      editCar,
      cancelEdit,
      updateCar,
      deleteCar
    }
  }
}
</script>

<style scoped>
.dashboard {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
  color: #ffffff;
  position: relative;
  overflow-x: hidden;
}

/* Background Effects */
.background-glow {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(212, 0, 0, 0.15) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(26, 26, 26, 0.1) 0%, transparent 50%);
  pointer-events: none;
  z-index: -1;
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-left: 280px;
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

.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 30px;
}

/* Cars Management Panel */
.cars-management-panel {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 25px;
  backdrop-filter: blur(20px);
  overflow: hidden;
  box-shadow: 
    0 20px 60px rgba(0, 0, 0, 0.5),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Panel Header */
.panel-header {
  padding: 40px 40px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  flex-wrap: wrap;
  gap: 30px;
}

.title-section {
  display: flex;
  align-items: center;
  gap: 20px;
}

.title-icon {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, #d40000, #a80000);
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.8rem;
  box-shadow: 0 10px 30px rgba(212, 0, 0, 0.4);
}

.title-text {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.main-title {
  font-size: 2.5rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ffffff, #f0f0f0, #d40000);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1;
}

.subtitle {
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.7);
  font-weight: 500;
}

.header-stats {
  display: flex;
  gap: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 15px;
  background: rgba(255, 255, 255, 0.05);
  padding: 20px;
  border-radius: 15px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  min-width: 180px;
}

.stat-icon {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #2d2d2d, #1a1a1a);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #d40000;
  font-size: 1.3rem;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 2rem;
  font-weight: 800;
  color: white;
  line-height: 1;
}

.stat-label {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Form Section */
.form-section {
  padding: 40px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.form-container {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  padding: 30px;
  backdrop-filter: blur(10px);
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.form-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  display: flex;
  align-items: center;
  gap: 12px;
}

.form-title i {
  color: #d40000;
}

.form-badge {
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.form-badge.creating {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.form-badge.editing {
  background: rgba(245, 158, 11, 0.1);
  color: #f59e0b;
  border: 1px solid rgba(245, 158, 11, 0.2);
}

.car-form {
  space-y: 0;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.form-group {
  position: relative;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  color: white;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.form-label i {
  color: #d40000;
  width: 16px;
}

.form-input, .form-textarea {
  width: 100%;
  background: rgba(255, 255, 255, 0.05);
  border: 2px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  padding: 15px;
  color: white;
  font-size: 1rem;
  transition: all 0.3s ease;
  font-family: inherit;
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #d40000;
  background: rgba(255, 255, 255, 0.08);
}

.form-input::placeholder, .form-textarea::placeholder {
  color: rgba(255, 255, 255, 0.4);
}

.form-focus-line {
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background: #d40000;
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.form-input:focus ~ .form-focus-line,
.form-textarea:focus ~ .form-focus-line {
  width: 100%;
}

.form-hint {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.5);
  margin-top: 5px;
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  align-items: center;
  flex-wrap: wrap;
}

.cancel-btn {
  padding: 15px 25px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
}

.cancel-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
}

.submit-btn {
  position: relative;
  padding: 15px 30px;
  background: linear-gradient(135deg, #d40000, #a80000);
  border: none;
  border-radius: 12px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(212, 0, 0, 0.3);
}

.submit-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(212, 0, 0, 0.5);
}

.submit-btn.editing {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
}

.submit-btn.editing:hover {
  box-shadow: 0 12px 35px rgba(245, 158, 11, 0.5);
}

.btn-sparkle {
  animation: sparkle 2s ease-in-out infinite;
}

@keyframes sparkle {
  0%, 100% { transform: scale(1) rotate(0deg); }
  50% { transform: scale(1.2) rotate(180deg); }
}

/* Table Section */
.table-section {
  padding: 40px;
}

.table-container {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  padding: 30px;
  backdrop-filter: blur(10px);
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.table-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: white;
  display: flex;
  align-items: center;
  gap: 10px;
}

.table-title i {
  color: #d40000;
}

.table-actions {
  display: flex;
  gap: 10px;
}

.refresh-btn {
  padding: 12px 20px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
}

.refresh-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(59, 130, 246, 0.3);
  transform: translateY(-2px);
}

.table-content {
  space-y: 0;
}

.empty-state {
  text-align: center;
  padding: 60px 40px;
  background: rgba(255, 255, 255, 0.02);
  border-radius: 15px;
  border: 2px dashed rgba(255, 255, 255, 0.1);
}

.empty-icon {
  font-size: 3rem;
  color: rgba(255, 255, 255, 0.3);
  margin-bottom: 20px;
}

.empty-state h3 {
  font-size: 1.3rem;
  color: white;
  margin-bottom: 10px;
}

.empty-state p {
  color: rgba(255, 255, 255, 0.6);
}

.enhanced-table {
  space-y: 0;
}

.table-responsive {
  overflow-x: auto;
  border-radius: 15px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.vehicles-table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255, 255, 255, 0.02);
}

.table-header-cell {
  background: rgba(255, 255, 255, 0.05);
  padding: 20px;
  text-align: left;
  font-weight: 700;
  color: white;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

.header-content i {
  color: #d40000;
  font-size: 0.8rem;
}

.table-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.table-row:hover {
  background: rgba(255, 255, 255, 0.03);
}

.table-row.editing-row {
  background: rgba(245, 158, 11, 0.05);
  border-left: 3px solid #f59e0b;
}

.table-cell {
  padding: 20px;
  color: rgba(255, 255, 255, 0.9);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.id-cell {
  font-weight: 600;
}

.cell-badge {
  background: rgba(212, 0, 0, 0.1);
  color: #d40000;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 700;
}

.dealer-info {
  display: flex;
  align-items: center;
  gap: 8px;
}

.dealer-info i {
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.9rem;
}

.make-text {
  font-weight: 600;
  color: white;
}

.model-text {
  color: white;
  font-size: 1rem;
}

.year-badge {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.variant-tag {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.variant-empty {
  color: rgba(255, 255, 255, 0.4);
  font-style: italic;
}

.actions-cell {
  white-space: nowrap;
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.action-btn {
  position: relative;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 6px;
  overflow: hidden;
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

.edit-btn {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
  border: 1px solid rgba(59, 130, 246, 0.2);
}

.edit-btn:hover:not(:disabled) {
  background: rgba(59, 130, 246, 0.2);
  transform: translateY(-2px);
}

.delete-btn {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border: 1px solid rgba(239, 68, 68, 0.2);
}

.delete-btn:hover:not(:disabled) {
  background: rgba(239, 68, 68, 0.2);
  transform: translateY(-2px);
}

.btn-glow {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s ease;
}

.action-btn:hover:not(:disabled) .btn-glow {
  left: 100%;
}

/* Loading Overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(10, 10, 10, 0.9);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  backdrop-filter: blur(10px);
}

.loading-spinner {
  position: relative;
  width: 80px;
  height: 80px;
  margin-bottom: 20px;
}

.spinner-ring {
  width: 100%;
  height: 100%;
  border: 3px solid rgba(212, 0, 0, 0.3);
  border-top: 3px solid #d40000;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.spinner-car {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #d40000;
  font-size: 1.5rem;
}

.loading-overlay p {
  color: white;
  font-size: 1.1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 1200px) {
  .main-content {
    margin-left: 0;
  }
  
  .header-content {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .dashboard-content {
    padding: 20px;
  }
  
  .panel-header,
  .form-section,
  .table-section {
    padding: 30px 20px;
  }
  
  .main-title {
    font-size: 2rem;
  }
  
  .title-icon {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }
  
  .form-actions {
    flex-direction: column;
    align-items: stretch;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .table-responsive {
    font-size: 0.9rem;
  }
  
  .table-cell {
    padding: 15px 10px;
  }
}

@media (max-width: 480px) {
  .dashboard-content {
    padding: 15px;
  }
  
  .panel-header,
  .form-section,
  .table-section {
    padding: 20px 15px;
  }
  
  .main-title {
    font-size: 1.8rem;
  }
  
  .form-container,
  .table-container {
    padding: 20px;
  }
}
</style>
<template>
  <div class="car-list-container">
    <div class="list-header">
      <h1>Car Management</h1>
      <div class="header-actions">
        <button class="btn btn-secondary" @click="$emit('refresh-data')" :disabled="loading">
          <span class="btn-icon">↻</span>
          {{ loading ? 'Loading...' : 'Refresh' }}
        </button>
        <button class="btn btn-primary" @click="$emit('add-car')">
          <span class="btn-icon">+</span>
          Add New Car
        </button>
      </div>
    </div>

    <div class="cars-grid" v-if="!loading && cars.length > 0">
      <div 
        v-for="car in cars" 
        :key="car.id" 
        class="car-card"
        @click="$emit('select-car', car.id)"
      >
        <div class="car-image">
          <img :src="car.main_image || '/api/placeholder/600/400'" :alt="car.make + ' ' + car.model">
          <span class="status-badge" :class="'status-' + car.status.toLowerCase()">
            {{ car.status }}
          </span>
        </div>
        <div class="car-info">
          <h3>{{ car.make }} {{ car.model }}</h3>
          <p class="car-variant">{{ car.variant }} • {{ car.year }}</p>
          <p class="car-specs">{{ car.fuel_type }} • {{ car.transmission }} • {{ car.type }}</p>
          <div class="car-price">₱{{ formatPrice(car.price) }}</div>
        </div>
      </div>
    </div>

    <div v-else-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <p>Loading cars...</p>
    </div>

    <div v-else class="empty-state">
      <p>No cars found.</p>
      <button class="btn btn-primary" @click="$emit('add-car')">
        <span class="btn-icon">+</span>
        Add First Car
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CarList',
  props: {
    cars: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  emits: ['select-car', 'add-car', 'refresh-data'],
  methods: {
    formatPrice(price) {
      return price?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') || '0'
    }
  }
}
</script>

<style scoped>
.car-list-container {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.list-header h1 {
  color: #fff;
  font-size: 2.5rem;
  font-weight: 700;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.cars-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
}

.car-card {
  background: #2b2b2b;
  border: 1px solid #333;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s ease;
}

.car-card:hover {
  transform: translateY(-4px);
  border-color: #e53935;
  box-shadow: 0 8px 25px rgba(229, 57, 53, 0.15);
}

.car-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.car-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.status-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  display: inline-block;
}

.status-available {
  background: rgba(76, 175, 80, 0.2);
  color: #4caf50;
  border: 1px solid #4caf50;
}

.status-reserved {
  background: rgba(255, 152, 0, 0.2);
  color: #ff9800;
  border: 1px solid #ff9800;
}

.status-sold {
  background: rgba(244, 67, 54, 0.2);
  color: #f44336;
  border: 1px solid #f44336;
}

.status-maintenance {
  background: rgba(33, 150, 243, 0.2);
  color: #2196f3;
  border: 1px solid #2196f3;
}

.car-info {
  padding: 1.5rem;
}

.car-info h3 {
  color: #fff;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.car-variant {
  color: #e53935;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.car-specs {
  color: #b0b0b0;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.car-price {
  color: #fff;
  font-size: 1.5rem;
  font-weight: 700;
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 4rem;
  color: #b0b0b0;
}

.empty-state p {
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

@media (max-width: 768px) {
  .car-list-container {
    padding: 1rem;
  }
  
  .list-header {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }
  
  .header-actions {
    justify-content: center;
  }
  
  .cars-grid {
    grid-template-columns: 1fr;
  }
}
</style>
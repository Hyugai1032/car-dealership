<template>
  <nav class="navigation-bar">
    <div class="nav-section">
      <h2 class="section-title">
        Appointments
        <span class="title-underline"></span>
      </h2>
      
      <div class="nav-links">
        <button 
          v-for="link in navLinks" 
          :key="link.id"
          @click="setActiveLink(link.id)"
          :class="{ active: activeLink === link.id }"
          class="nav-link"
        >
          {{ link.label }}
        </button>
      </div>
    </div>

    <div class="nav-controls">
      <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input
          type="text"
          v-model="searchInput"
          @input="handleSearchInput"
          placeholder="Search appointments..."
          class="search-input"
        >
      </div>

      <div class="filter-dropdown">
        <select v-model="selectedFilter" @change="handleFilterChange" class="filter-select">
          <option value="all">All Status</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <i class="fas fa-chevron-down dropdown-icon"></i>
      </div>

      <button @click="$emit('book-appointment')" class="book-btn">
        <i class="fas fa-plus"></i>
        Book Appointment
      </button>
    </div>
  </nav>
</template>

<script>
import { ref, watch } from 'vue'

export default {
  name: 'NavigationBar',
  emits: ['search', 'filter', 'book-appointment'],
  setup(props, { emit }) {
    const activeLink = ref('all')
    const searchInput = ref('')
    const selectedFilter = ref('all')

    const navLinks = [
      { id: 'all', label: 'All Appointments' },
      { id: 'my', label: 'My Appointments' },
      { id: 'book', label: 'Book Appointment' }
    ]

    const setActiveLink = (linkId) => {
      activeLink.value = linkId
      if (linkId === 'book') {
        emit('book-appointment')
      }
    }

    const handleSearchInput = () => {
      emit('search', searchInput.value)
    }

    const handleFilterChange = () => {
      emit('filter', selectedFilter.value)
    }

    return {
      activeLink,
      searchInput,
      selectedFilter,
      navLinks,
      setActiveLink,
      handleSearchInput,
      handleFilterChange
    }
  }
}
</script>

<style scoped>
.navigation-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--luxury-black);
  padding: 20px 30px;
  border-radius: 12px;
  box-shadow: var(--shadow);
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 20px;
}

.nav-section {
  display: flex;
  align-items: center;
  gap: 30px;
}

.section-title {
  color: var(--pure-white);
  font-size: 1.5rem;
  font-weight: 700;
  position: relative;
}

.title-underline {
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 100%;
  height: 2px;
  background: var(--primary-red);
  transform: scaleX(1);
  transition: var(--transition);
}

.nav-links {
  display: flex;
  gap: 5px;
  background: var(--soft-black);
  padding: 4px;
  border-radius: 8px;
}

.nav-link {
  background: transparent;
  border: none;
  color: var(--pure-white);
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: var(--transition);
}

.nav-link:hover {
  color: var(--primary-red);
}

.nav-link.active {
  background: var(--primary-red);
  color: var(--pure-white);
}

.nav-controls {
  display: flex;
  align-items: center;
  gap: 15px;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-input {
  background: var(--pure-white);
  border: 2px solid var(--medium-gray);
  padding: 8px 12px 8px 35px;
  border-radius: 6px;
  width: 200px;
  transition: var(--transition);
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-red);
}

.search-icon {
  position: absolute;
  left: 12px;
  color: var(--dark-gray);
  z-index: 1;
}

.filter-dropdown {
  position: relative;
  display: flex;
  align-items: center;
}

.filter-select {
  background: var(--pure-white);
  border: 2px solid var(--medium-gray);
  padding: 8px 35px 8px 12px;
  border-radius: 6px;
  appearance: none;
  cursor: pointer;
  transition: var(--transition);
  min-width: 150px;
}

.filter-select:focus {
  outline: none;
  border-color: var(--primary-red);
}

.dropdown-icon {
  position: absolute;
  right: 12px;
  color: var(--dark-gray);
  pointer-events: none;
}

.book-btn {
  background: var(--primary-red);
  color: var(--pure-white);
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 8px;
}

.book-btn:hover {
  background: var(--dark-red);
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .navigation-bar {
    flex-direction: column;
    align-items: stretch;
    gap: 15px;
  }

  .nav-section {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }

  .nav-links {
    justify-content: center;
  }

  .nav-controls {
    justify-content: center;
  }

  .search-input {
    width: 100%;
  }
}
</style>
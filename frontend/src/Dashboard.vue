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
        
        <!-- Hero Showcase Section -->
        <HeroShowcase 
          :currentCar="currentCar"
          @prev-car="prevCar"
          @next-car="nextCar"
          @pause-slideshow="pauseSlideshow"
        />
        
        <!-- Stats Overview -->
        <StatsGrid 
          :stats="stats"
          :theme="theme"
        />
        
        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
          <!-- Activity Feed -->
          <ActivityFeed 
            :activities="activities"
            :theme="theme"
          />
          
          <!-- Charts Panel -->
          <ChartsPanel 
            :theme="theme"
          />
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions-section">
          <h3 class="section-title">Quick Actions</h3>
          <div class="actions-grid">
            <button class="action-btn primary">
              <i class="fas fa-car"></i>
              Manage Cars
            </button>
            <button class="action-btn secondary">
              <i class="fas fa-store"></i>
              Dealers
            </button>
            <button class="action-btn accent">
              <i class="fas fa-calendar-check"></i>
              Appointments
            </button>
            <button class="action-btn neutral">
              <i class="fas fa-users"></i>
              Users
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Background Glow Effects -->
    <div class="background-glow"></div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import Sidebar from './components/layout/Sidebars.vue'
import Header from './components/layout/Header.vue'
import HeroShowcase from './components/dashboard/HeroShowcase.vue'
//import StatsGrid from './components/dashboard/StatsGrid.vue'
import ActivityFeed from './components/dashboard/ActivityFeed.vue'
import ChartsPanel from './components/dashboard/ChartsPanel.vue'

export default {
  name: 'Dashboard',
  components: {
    Sidebar,
    Header,
    HeroShowcase,
   // StatsGrid,
    ActivityFeed,
    ChartsPanel
  },
  setup() {
    const theme = ref('dark')
    
    // Dashboard Data
    const stats = ref({
      totalCars: '--',
      totalDealers: '--',
      pendingAppointments: '--',
      totalUsers: '--',
      revenue: '--'
    })
    
    const activities = ref([
      "Loading activities..."
    ])
    
    // Car Showcase Data
    const cars = [
      {
        name: "Ferrari SF90 Stradale",
        desc: "Hybrid hypercar with 986 hp of pure Italian excellence",
        img: "/api/placeholder/800/400",
        bg: "radial-gradient(circle at 30% 20%, #d40000 0%, #8b0000 50%, #1a1a1a 100%)",
        price: "$625,000",
        specs: "0-60: 2.5s • Top Speed: 211 mph"
      },
      {
        name: "Porsche 911 Turbo S",
        desc: "Precision engineering meets breathtaking performance",
        img: "/api/placeholder/800/400",
        bg: "radial-gradient(circle at 70% 20%, #2d2d2d 0%, #1a1a1a 50%, #000000 100%)",
        price: "$215,000",
        specs: "0-60: 2.6s • 640 hp"
      },
      {
        name: "Aston Martin DBS",
        desc: "British luxury with uncompromising power and elegance",
        img: "/api/placeholder/800/400",
        bg: "radial-gradient(circle at 50% 20%, #8b0000 0%, #660000 30%, #1a1a1a 70%)",
        price: "$330,000",
        specs: "V12 • 715 hp • Grand Tourer"
      }
    ]
    
    let currentCarIndex = 0
    const currentCar = ref(cars[0])
    let autoSlideInterval
    
    // Methods
    const toggleTheme = () => {
      theme.value = theme.value === 'dark' ? 'light' : 'dark'
      localStorage.setItem('dashboard-theme', theme.value)
    }
    
    const showCar = (index) => {
      currentCarIndex = (index + cars.length) % cars.length
      currentCar.value = cars[currentCarIndex]
    }
    
    const nextCar = () => {
      showCar(currentCarIndex + 1)
      resetAutoSlide()
    }
    
    const prevCar = () => {
      showCar(currentCarIndex - 1)
      resetAutoSlide()
    }
    
    const pauseSlideshow = () => {
      clearInterval(autoSlideInterval)
    }
    
    const resetAutoSlide = () => {
      clearInterval(autoSlideInterval)
      autoSlideInterval = setInterval(() => {
        showCar(currentCarIndex + 1)
      }, 5000)
    }
    
    const fetchStats = async () => {
      try {
        // Simulate API calls
        setTimeout(() => {
          stats.value = {
            totalCars: "247",
            totalDealers: "18",
            pendingAppointments: "23",
            totalUsers: "1,842",
            revenue: "$4.2M"
          }
          
          activities.value = [
            "New Ferrari SF90 Stradale added to inventory",
            "Appointment confirmed for John Doe - Porsche 911",
            "Monthly revenue target exceeded by 27%",
            "New dealer partnership established in Miami",
            "System maintenance completed successfully"
          ]
        }, 1000)
      } catch (error) {
        console.error('Failed to fetch stats:', error)
      }
    }
    
    // Lifecycle
    onMounted(() => {
      theme.value = localStorage.getItem('dashboard-theme') || 'dark'
      fetchStats()
      resetAutoSlide()
    })
    
    return {
      theme,
      stats,
      activities,
      currentCar,
      toggleTheme,
      nextCar,
      prevCar,
      pauseSlideshow
    }
  }
}
</script>

<style scoped>
.dashboard {
  min-height: 100vh;
  display: flex;
  background: var(--bg-primary);
  color: var(--text-primary);
  position: relative;
  overflow-x: hidden;
}

.dashboard.dark {
  --bg-primary: #0a0a0a;
  --bg-secondary: #1a1a1a;
  --bg-tertiary: #2d2d2d;
  --text-primary: #ffffff;
  --text-secondary: #a0a0a0;
  --text-accent: #d40000;
  --border-primary: #333333;
  --shadow-glow: 0 0 50px rgba(212, 0, 0, 0.3);
}

.dashboard.light {
  --bg-primary: #ffffff;
  --bg-secondary: #f5f5f5;
  --bg-tertiary: #e8e8e8;
  --text-primary: #1a1a1a;
  --text-secondary: #666666;
  --text-accent: #d40000;
  --border-primary: #e0e0e0;
  --shadow-glow: 0 0 30px rgba(212, 0, 0, 0.2);
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-left: 280px;
  transition: margin-left 0.3s ease;
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
  grid-template-columns: 1fr 2fr;
  gap: 30px;
  align-items: start;
}

.quick-actions-section {
  background: var(--bg-secondary);
  border-radius: 20px;
  padding: 30px;
  border: 1px solid var(--border-primary);
  box-shadow: var(--shadow-glow);
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.section-title::before {
  content: '';
  width: 4px;
  height: 24px;
  background: var(--text-accent);
  border-radius: 2px;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px 20px;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.action-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s ease;
}

.action-btn:hover::before {
  left: 100%;
}

.action-btn.primary {
  background: linear-gradient(135deg, #d40000, #a80000);
  color: white;
  box-shadow: 0 8px 25px rgba(212, 0, 0, 0.3);
}

.action-btn.primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(212, 0, 0, 0.4);
}

.action-btn.secondary {
  background: var(--bg-tertiary);
  color: var(--text-primary);
  border: 2px solid var(--border-primary);
}

.action-btn.secondary:hover {
  transform: translateY(-3px);
  border-color: var(--text-accent);
}

.action-btn.accent {
  background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
  color: white;
  border: 2px solid #333;
}

.action-btn.accent:hover {
  transform: translateY(-3px);
  border-color: var(--text-accent);
}

.action-btn.neutral {
  background: var(--bg-secondary);
  color: var(--text-primary);
  border: 2px solid var(--border-primary);
}

.action-btn.neutral:hover {
  transform: translateY(-3px);
  background: var(--bg-tertiary);
}

.background-glow {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: 
    radial-gradient(circle at 20% 80%, rgba(212, 0, 0, 0.15) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(26, 26, 26, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(45, 45, 45, 0.05) 0%, transparent 50%);
  pointer-events: none;
  z-index: -1;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .main-content {
    margin-left: 0;
  }
}

@media (max-width: 768px) {
  .dashboard-content {
    padding: 20px;
    gap: 20px;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
}

/* Custom Scrollbar */
.dashboard::-webkit-scrollbar {
  width: 8px;
}

.dashboard::-webkit-scrollbar-track {
  background: var(--bg-secondary);
}

.dashboard::-webkit-scrollbar-thumb {
  background: var(--text-accent);
  border-radius: 4px;
}

.dashboard::-webkit-scrollbar-thumb:hover {
  background: #a80000;
}
</style>
<template>
  <div class="dashboard" :class="theme">
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
        <!-- <StatsGrid :stats="stats" :theme="theme" /> -->
        
        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
          <!-- Activity Feed -->
          
          <!-- Charts Panel -->
          <ChartsPanel :theme="theme" />
          <ActivityFeed :activities="activities" :theme="theme" />
        </div>
      </div>
    </div>
    
    <!-- Background Glow Effects -->
    <div class="background-glow"></div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import Header from '../components/layout/Header.vue'
import HeroShowcase from '../components/dashboard/HeroShowcase.vue'
// import StatsGrid from './components/dashboard/StatsGrid.vue'
import ActivityFeed from '../components/dashboard/ActivityFeed.vue'
import ChartsPanel from '../components/dashboard/ChartsPanel.vue'

export default {
  name: 'Dashboard',
  components: {
    Header,
    HeroShowcase,
    // StatsGrid,
    ActivityFeed,
    ChartsPanel
  },
  setup() {
    const theme = ref('dark')

    const stats = ref({
      totalCars: '--',
      totalDealers: '--',
      pendingAppointments: '--',
      totalUsers: '--',
      revenue: '--'
    })

    const activities = ref(["Loading activities..."])

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
    }

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
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-left: 0; /* Full width since no sidebar */
  transition: margin-left 0.3s ease;
}
</style>

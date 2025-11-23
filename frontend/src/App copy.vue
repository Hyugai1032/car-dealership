<template>
  <div id="app" :class="theme">
    <!-- Only show Sidebar if not on login or registration -->
      <Sidebar
        v-if="!['login', 'registration', 'cars-page'].includes(currentPageName)"
        :theme="theme"
        :currentPage="currentPageName"
        @toggle-theme="toggleTheme"
        @page-change="changePage"
      />

      <div
        class="main-content"
        :class="{ 'full-width': ['login', 'registration', 'cars-page'].includes(currentPageName) }"
      >
      <component
        :is="currentPage"
        :theme="theme"
        @registered="goToLogin"
        @no-account="goToRegistration"
        @logged-in="goToDashboard"
        @manage-cars="goToCarsManagement"
      />
    </div>
  </div>
</template>

<script>
import { shallowRef, ref, computed } from 'vue'
import Sidebar from './components/layout/Sidebars.vue'
import RegistrationComponent from './components/Auth/Registration.vue'
import LoginComponent from './components/Auth/Login.vue'
import UserDashboard from './components/Appointments/AppointmentCard.vue'
import Dashboard from './views/Dashboard.vue'
import CarsManagement from './components/Cars/CarsManagement.vue'
import AppointmentCard from './components/Appointments/AppointmentCard.vue'
import CarsPage from './views/pages/CarsPage.vue'

export default {
  name: 'App',
  components: {
    Sidebar,
    RegistrationComponent,
    LoginComponent,
    UserDashboard,
    Dashboard,
    CarsManagement,
    CarsPage,
    AppointmentCard
  },
  setup() {
    // Set default page
    const currentPage = shallowRef(CarsManagement)
    const user = shallowRef(null)
    const theme = ref('dark')

    const currentPageName = computed(() => {
      if (currentPage.value === CarsManagement) return 'cars-management'
      if (currentPage.value === Dashboard) return 'dashboard'
      if (currentPage.value === LoginComponent) return 'login'
      if (currentPage.value === RegistrationComponent) return 'registration'
      if (currentPage.value === UserDashboard) return 'appointments'
      if (currentPage.value === CarsPage) return 'cars-page' // ✅ added this
      return 'dashboard'
    })

    const goToLogin = () => (currentPage.value = LoginComponent)
    const goToRegistration = () => (currentPage.value = RegistrationComponent)
    const goToDashboard = () => (currentPage.value = Dashboard)
    const goToCarsManagement = () => (currentPage.value = CarsManagement)
    const goToCarsPage = () => (currentPage.value = CarsPage) // ✅ optional navigation

    const toggleTheme = () => {
      theme.value = theme.value === 'dark' ? 'light' : 'dark'
      document.documentElement.setAttribute('data-theme', theme.value)
    }

    const changePage = (page) => {
      const pageMap = {
        dashboard: Dashboard,
        analytics: Dashboard,
        inventory: CarsManagement,
        dealers: Dashboard,
        appointments: UserDashboard,
        'cars-management': CarsManagement,
        customers: Dashboard,
        financing: Dashboard,
        settings: Dashboard,
        'cars-page': CarsPage // ✅ added here
      }
      currentPage.value = pageMap[page] || Dashboard
    }

    return {
      currentPage,
      currentPageName,
      user,
      theme,
      goToLogin,
      goToRegistration,
      goToDashboard,
      goToCarsManagement,
      goToCarsPage,
      toggleTheme,
      changePage
    }
  }
}
</script>


<style>
:root {
  --bg-primary: #b8afaf;
  --bg-secondary: #1a1a1a;
  --bg-tertiary: #2d2d2d;
  --text-primary: #ffffff;
  --text-secondary: #a0a0a0;
  --text-accent: #d40000;
  --border-primary: rgba(255, 255, 255, 0.1);
}

[data-theme="light"] {
  --bg-primary: #ffffff;
  --bg-secondary: #f8f9fa;
  --bg-tertiary: #e9ecef;
  --text-primary: #2d3748;
  --text-secondary: #718096;
  --text-accent: #d40000;
  --border-primary: rgba(0, 0, 0, 0.1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background-color: var(--bg-primary);
  color: var(--text-primary);
  font-family: 'Poppins', sans-serif;
  line-height: 1.6;
}

#app {
  display: flex;
  min-height: 100vh;
  background: var(--bg-primary);
  transition: all 0.3s ease;
}

html, body, #app {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}


.main-content {
  flex: 1;
  margin-left: 280px;
  padding: 0;
  min-height: 100vh;
  background: var(--bg-primary);
  transition: margin-left 0.3s ease;
}

.main-content.full-width {
  margin-left: 0; /* expands content when sidebar hidden */
  width: 100%;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .main-content {
    margin-left: 0;
    padding-top: 70px;
  }
}

/* Scrollbar Styling */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: var(--bg-secondary);
}

::-webkit-scrollbar-thumb {
  background: var(--text-accent);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a80000;
}
</style>
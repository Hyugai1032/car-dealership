<template> 
  <div id="app">
    <component
      :is="currentPage"
      @registered="goToLogin"
      @Noaccount="goToRegistration"
      @logged-in="goToCarsManagement"
      @manage-cars="goToCarsManagement"
    />
  </div>
</template>

<script>
import RegistrationComponent from './components/Auth/Registration.vue'
import LoginComponent from './components/Auth/Login.vue'
import UserDashboard from './components/Appointments/AppointmentCard.vue'
import Dashboard from './Dashboard.vue'
import CarsManagement from './components/Cars/CarsManagement.vue'

export default {
  name: 'App',
  components: {
    RegistrationComponent,
    LoginComponent,
    UserDashboard,
    Dashboard,
    CarsManagement
  },
  data() {
    return {
      currentPage: LoginComponent,
      user: null
    }
  },
  methods: {
    goToLogin() {
      this.currentPage = RegistrationComponent
    },
    goToRegistration() {
      this.currentPage = LoginComponent
    },
    goToDashboard() {
      // Skip role verification, go straight to dashboard
      this.currentPage = Dashboard
    },
    goToCarsManagement() {
      this.currentPage = CarsManagement
    }

  }
}
</script>

<style>
body {
  margin: 0;
  background-color: #0e0e0e;
  color: white;
  font-family: 'Poppins', sans-serif;
}
</style>

<template>
  <div id="app">
    <component :is="currentPage" 
               @registered="goToLogin" 
               @Noaccount="goToRegistration"
               @logged-in="goToDashboard" />
  </div>
</template>

<script>
import RegistrationComponent from './components/LOGIN/REGISTRATION/Registration.vue';
import LoginComponent from './components/LOGIN/Login.vue';
import UserDashboard from './components/Appointments/AppointmentCard.vue';

export default {
  name: 'App',
  components: {
    RegistrationComponent,
    LoginComponent,
    UserDashboard
  },
  data() {
    return {
      currentPage: LoginComponent // start with login
    };
  },
  methods: {
    goToLogin() {
      this.currentPage = LoginComponent;
    },
    goToRegistration() {
      this.currentPage = RegistrationComponent;
    },
    goToDashboard(user) {
      this.currentPage = UserDashboard;
      // optionally pass user data via props or store
      this.user = user;
    }
  }
}
</script>

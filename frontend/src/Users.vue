<template>
  <div class="users-page">
    <nav>
      <button @click="current = 'login'">Login</button>
      <button @click="current = 'register'">Register</button>
    </nav>

    <component :is="currentComponent"></component>
  </div>
</template>

<script>
import LoginComponent from './components/LOGIN/Login.vue';
import RegistrationComponent from './components/LOGIN/REGISTRATION/Registration.vue';

export default {
  name: 'Users',
  data() {
    return {
      current: 'login' // default view
    };
  },
  computed: {
    currentComponent() {
      return this.current === 'login' ? LoginComponent : RegistrationComponent;
    }
  }
};
</script>

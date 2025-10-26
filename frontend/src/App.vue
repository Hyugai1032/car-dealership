<template>
  <div id="app">
    <!-- Sidebar (stays visible) -->
    <Sidebar />

    <!-- Main content area (where pages show up) -->
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import Sidebar from "./components/Sidebar.vue";
</script>

<style scoped>
#app {
  display: flex;
  min-height: 100vh;
  background-color: #f4f7fb;
  font-family: "Poppins", sans-serif;
  color: #1e293b;
}

/* Main page content */
.main-content {
  flex: 1;
  margin-left: 16rem; /* matches your Sidebar width */
  padding: 2rem;
  transition: margin-left 0.3s ease;
}

/* Make it more responsive */
@media (max-width: 1024px) {
  .main-content {
    margin-left: 0;
  }
}
</style>

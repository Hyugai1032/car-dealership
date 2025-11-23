<template>
  <div id="app" :class="theme">
    <Sidebar
      v-if="!['login', 'registration', 'cars-page', 'appointmentpage', 'car-inventory', 'dealers', 'car-comparison', 'featured-cars', 'cta-section', 'footer-section', 'hello-world', 'hero-section', 'promotion-section', 'services-section', 'testimonials-section', 'the-welcome', 'welcome-item', 'home', 'contact', 'google-form'].includes($route.name)"
      :theme="theme"
      :currentPage="$route.name"
      @toggle-theme="toggleTheme"
    />

    <div
      class="main-content"
      :class="{ 'full-width': ['login', 'registration', 'cars-page', 'car-comparison', 'featured-cars', 'cta-section', 'footer-section', 'hello-world', 'hero-section', 'promotion-section', 'services-section', 'testimonials-section', 'the-welcome', 'welcome-item', 'home', 'contact', 'google-form'].includes($route.name) }"
    >
      <router-view :theme="theme" />
    </div>

  </div>
</template>

<script>

import { ref } from 'vue'
import Sidebar from './components/layout/Sidebars.vue'

export default {
  name: 'App',
  components: { Sidebar },
  setup() {
    const theme = ref('dark')

    const toggleTheme = () => {
      theme.value = theme.value === 'dark' ? 'light' : 'dark'
      document.documentElement.setAttribute('data-theme', theme.value)
    }

    return { theme, toggleTheme }
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
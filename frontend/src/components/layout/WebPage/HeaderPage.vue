<template>
  <header class="header">
    <div class="container">
      <div class="logo">
        <svg viewBox="0 0 100 100" class="logo-icon">
          <path d="M50 15 L70 35 L70 65 L50 85 L30 65 L30 35 Z" fill="url(#grad)" />
          <defs>
            <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#4f46e5" />
              <stop offset="100%" stop-color="#7c3aed" />
            </linearGradient>
          </defs>
        </svg>
        <span class="logo-text">LUXAUTO</span>
      </div>

      <nav class="nav">
        <a href="#" class="nav-link active">Browse</a>
        <a href="#" class="nav-link">Compare</a>
        <a href="#" class="nav-link">Favorites</a>
        <a href="#" class="nav-link">Finance</a>
      </nav>

      <div class="actions">
        <button @click="$emit('toggle-dark')" class="icon-btn">
          <svg v-if="!dark" viewBox="0 0 24 24" class="icon"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <svg v-else viewBox="0 0 24 24" class="icon"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        </button>
        <button class="profile-btn">
          <svg viewBox="0 0 24 24" class="icon"><circle cx="12" cy="7" r="4"/><path d="M4 21v-2a4 4 0 014-4h8a4 4 0 014 4v2"/></svg>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
defineEmits(['toggle-dark'])
const dark = defineModel('dark', { default: false })
</script>

<style scoped>
.header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(0,0,0,0.05);
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 800;
  font-size: 1.5rem;
  color: #1f2937;
}

.logo-icon {
  width: 2.5rem;
  height: 2.5rem;
}

.logo-text {
  background: linear-gradient(to right, #4f46e5, #7c3aed);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.nav {
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: #4b5563;
  font-weight: 500;
  text-decoration: none;
  position: relative;
  transition: color 0.3s ease;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: -0.5rem;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(to right, #4f46e5, #7c3aed);
  transition: width 0.3s ease;
}

.nav-link:hover::after, .nav-link.active::after {
  width: 100%;
}

.nav-link:hover, .nav-link.active {
  color: #4f46e5;
}

.actions {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.icon-btn, .profile-btn {
  padding: 0.5rem;
  background: none;
  border: none;
  border-radius: 9999px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.icon-btn:hover, .profile-btn:hover {
  background: rgba(79, 70, 229, 0.1);
  transform: scale(1.1);
}

.icon {
  width: 1.5rem;
  height: 1.5rem;
  stroke: #6b7280;
  stroke-width: 1.5;
  fill: none;
}
</style>
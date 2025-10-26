<template>
  <header class="bg-slate-800 text-white p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold">RideZone Admin</h1>
    <button @click="toggleTheme" class="bg-slate-700 px-4 py-2 rounded">
      Toggle Theme
    </button>
  </header>
</template>

<script setup>
const toggleTheme = () => {
  const theme = document.body.classList.contains('dark') ? 'light' : 'dark'
  document.body.classList.toggle('dark', theme === 'dark')
  localStorage.setItem('theme', theme)
}
</script>

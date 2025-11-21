<template>
  <div class="stat-card" :class="color">
    <div class="icon">{{ icon }}</div>
    <div class="value">{{ value }}</div>
    <div class="label">{{ title }}</div>
  </div>
</template>
<script setup>
defineProps({ title: String, value: [String, Number], icon: String, color: { type: String, default: '' } })
</script>
<style scoped>
.stat-card { padding: 1rem; background: var(--bg-secondary); border-radius: 0.75rem; text-align: center; }
.value { font-size: 1.5rem; font-weight: bold; }
.label { font-size: 0.875rem; color: var(--text-secondary); }
.success { border-left: 4px solid #10b981; }
.warning { border-left: 4px solid #f59e0b; }
.error { border-left: 4px solid #ef4444; }
</style>
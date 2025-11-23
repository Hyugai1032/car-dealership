<template>
  <span class="badge" :class="status">{{ status }}</span>
</template>
<script setup>
defineProps({ status: String })
</script>
<style scoped>
.badge { padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem; text-transform: capitalize; }
.available { background: #dcfce7; color: #166534; }
.sold { background: #fee2e2; color: #991b1b; }
.reserved { background: #fef3c7; color: #92400e; }
</style>
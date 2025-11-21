<template>
  <div class="pagination">
    <select v-model="perPage" @change="$emit('change-per-page', perPage)">
      <option v-for="n in [10,25,50]" :value="n">{{ n }} / page</option>
    </select>
    <button :disabled="currentPage === 1" @click="$emit('change-page', currentPage - 1)">Prev</button>
    <span>{{ currentPage }} / {{ totalPages }}</span>
    <button :disabled="currentPage === totalPages" @click="$emit('change-page', currentPage + 1)">Next</button>
  </div>
</template>
<script setup>
defineProps(['currentPage', 'totalPages', 'totalItems'])
const perPage = defineModel()
</script>
<style scoped>
.pagination { display: flex; gap: 0.5rem; align-items: center; justify-content: flex-end; margin-top: 1rem; }
</style>
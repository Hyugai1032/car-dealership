<template>
  <span v-if="field === current" class="sort-icon">
    {{ dir === 'asc' ? 'Up Arrow' : 'Down Arrow' }}
  </span>
</template>
<script setup>
defineProps({ field: String, current: String, dir: String })
</script>
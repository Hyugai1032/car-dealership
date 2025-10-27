<template>
  <div>
    <h2 class="text-2xl mb-3">Available Cars</h2>
    <ul>
      <li
        v-for="car in cars"
        :key="car.id"
        class="border p-3 rounded mb-2 flex justify-between items-center"
      >
        <div>
          <p class="font-semibold">{{ car.brand }} - {{ car.model }}</p>
          <p>₱{{ car.price }}</p>
          <p>Year: {{ car.year }}</p>
          <p v-if="car.image">
            <img :src="car.image" alt="Car image" class="w-32 h-20 object-cover rounded mt-2" />
          </p>
          <p class="text-gray-600 text-sm mt-1">{{ car.description }}</p>
        </div>
        <button
          @click="deleteCar(car.id)"
          class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
        >
          Delete
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['cars'],
  methods: {
    async deleteCar(id) {
      await fetch(`http://localhost:8000/api/cars/${id}`, {
        method: 'DELETE',
      });
      this.$emit('refreshCars');
    },
  },
};
</script>

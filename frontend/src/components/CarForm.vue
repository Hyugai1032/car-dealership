<template>
  <form @submit.prevent="addCar" class="mb-6 space-y-3">
    <input v-model="car.brand" placeholder="Brand" class="border p-2 rounded w-full" required />
    <input v-model="car.model" placeholder="Model" class="border p-2 rounded w-full" required />
    <input v-model="car.price" placeholder="Price" type="number" step="0.01" class="border p-2 rounded w-full" required />
    <input v-model="car.year" placeholder="Year" type="date" class="border p-2 rounded w-full" />
    <input v-model="car.image" placeholder="Image URL" class="border p-2 rounded w-full" />
    <textarea v-model="car.description" placeholder="Description" class="border p-2 rounded w-full"></textarea>

    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Car</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      car: {
        brand: '',
        model: '',
        price: '',
        year: '',
        image: '',
        description: '',
      },
    };
  },
  methods: {
    async addCar() {
      await fetch('http://localhost:8000/api/cars', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(this.car),
      });
      this.car = { brand: '', model: '', price: '', year: '', image: '', description: '' };
      this.$emit('refreshCars');
    },
  },
};
</script>

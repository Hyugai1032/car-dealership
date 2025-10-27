<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Car Dealership</h1>

    <!-- Car Form -->
    <CarForm @add-car="addCar" />

    <hr class="my-6">

    <!-- Car List -->
    <CarList 
      :cars="cars" 
      @delete-car="deleteCar"
    />
  </div>
</template>

<script>
import CarList from "../components/CarList.vue";
import CarForm from "../components/CarForm.vue";

export default {
  components: { CarList, CarForm },
  data() {
    return {
      cars: [],
    };
  },
  methods: {
    async fetchCars() {
      try {
        const res = await fetch("http://localhost:8000/api/cars");
        this.cars = await res.json();
      } catch (err) {
        console.error("Error fetching cars:", err);
      }
    },

    async addCar(newCar) {
      try {
        const res = await fetch("http://localhost:8000/api/cars", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(newCar),
        });
        const data = await res.json();
        if (data.status === "success") this.fetchCars();
      } catch (err) {
        console.error("Error adding car:", err);
      }
    },

    async deleteCar(id) {
      try {
        const res = await fetch(`http://localhost:8000/api/cars/${id}`, {
          method: "DELETE",
        });
        const data = await res.json();
        if (data.status === "success") this.fetchCars();
      } catch (err) {
        console.error("Error deleting car:", err);
      }
    },
  },
  mounted() {
    this.fetchCars();
  },
};
</script>

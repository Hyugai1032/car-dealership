<template>
  <div class="cars-container">
    <h1 class="title">🚘 Manage Cars</h1>

    <!-- Search + Add -->
    <div class="search-bar">
      <input v-model="searchQuery" placeholder="Search cars..." class="search-input" />
      <button @click="openAddModal" class="add-btn">+ Add New Car</button>
    </div>

    <!-- Table -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Price</th>
            <th>Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(car, index) in filteredCars" :key="car.id">
            <td>{{ index + 1 }}</td>
            <td><img :src="`/assets/cars/${car.image}`" class="car-img" /></td>
            <td>{{ car.make }}</td>
            <td>{{ car.model }}</td>
            <td>{{ car.year }}</td>
            <td class="price">${{ formatPrice(car.price) }}</td>
            <td>{{ car.type }}</td>
            <td class="actions">
              <button @click="openEditModal(car)" class="edit-btn">Edit</button>
              <button @click="deleteCar(car.id)" class="delete-btn">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal">
      <div class="modal-content">
        <h2 class="modal-title">{{ modalTitle }}</h2>

        <form @submit.prevent="submitForm" enctype="multipart/form-data">
          <input v-model="form.make" placeholder="Car Make" required />
          <input v-model="form.model" placeholder="Car Model" required />
          <input v-model="form.year" type="number" placeholder="Year" required />
          <input v-model="form.price" type="number" placeholder="Price" required />
          <input v-model="form.type" placeholder="Type (SUV, Sedan, etc.)" required />

          <input type="file" @change="handleImageUpload" accept="image/*" />
          <img v-if="preview" :src="preview" class="preview-img" />

          <div class="modal-buttons">
            <button type="button" @click="closeModal" class="cancel-btn">Cancel</button>
            <button type="submit" class="save-btn">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, computed, onMounted } from "vue";

const cars = ref([]);
const searchQuery = ref("");
const showModal = ref(false);
const modalTitle = ref("Add New Car");
const preview = ref(null);
const isEditing = ref(false);

const form = ref({
  id: null,
  make: "",
  model: "",
  year: "",
  price: "",
  type: "",
  image: null,
});

// Load cars from backend
const fetchCars = async () => {
  try {
    const res = await axios.get("http://localhost/backend/api/cars.php");
    cars.value = res.data;
  } catch (err) {
    console.error("Error fetching cars:", err);
  }
};

onMounted(fetchCars);

const filteredCars = computed(() =>
  cars.value.filter((car) =>
    Object.values(car)
      .join(" ")
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase())
  )
);

const openAddModal = () => {
  modalTitle.value = "Add New Car";
  isEditing.value = false;
  Object.assign(form.value, { id: null, make: "", model: "", year: "", price: "", type: "", image: null });
  preview.value = null;
  showModal.value = true;
};

const openEditModal = (car) => {
  modalTitle.value = "Edit Car";
  isEditing.value = true;
  Object.assign(form.value, { ...car });
  preview.value = `/assets/cars/${car.image}`;
  showModal.value = true;
};

const handleImageUpload = (e) => {
  const file = e.target.files[0];
  form.value.image = file;
  if (file) {
    const reader = new FileReader();
    reader.onload = (ev) => (preview.value = ev.target.result);
    reader.readAsDataURL(file);
  }
};

const closeModal = () => (showModal.value = false);

const submitForm = async () => {
  const data = new FormData();
  for (let key in form.value) {
    data.append(key, form.value[key]);
  }
  data.append("action", isEditing.value ? "edit" : "add");

  try {
    await axios.post("http://localhost/backend/api/cars.php", data, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    await fetchCars();
    closeModal();
    alert("Car saved successfully!");
  } catch (err) {
    console.error(err);
  }
};

const deleteCar = async (id) => {
  if (confirm("Are you sure you want to delete this car?")) {
    await axios.post("http://localhost/backend/api/cars.php", { action: "delete", id });
    await fetchCars();
  }
};

const formatPrice = (val) => parseFloat(val).toLocaleString();
</script> 


<style scoped>
.cars-container {
  background: radial-gradient(circle at top, #0f172a 0%, #1e293b 100%);
  color: #f1f5f9;
  font-family: "Poppins", sans-serif;
  padding: 2rem;
  min-height: 100vh;
}
.title {
  font-size: 2.5rem;
  color: #818cf8;
  font-weight: bold;
  margin-bottom: 2rem;
  text-align: center;
}
.search-bar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}
.search-input {
  flex: 1;
  min-width: 250px;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  border: none;
}
.add-btn {
  background: #6366f1;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}
.table-container {
  overflow-x: auto;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 1rem;
  border-bottom: 1px solid #475569;
  text-align: left;
}
tr:hover {
  background: rgba(255, 255, 255, 0.05);
}
.car-img {
  width: 100px;
  height: 70px;
  object-fit: cover;
  border-radius: 10px;
}
.price {
  color: #22c55e;
}
.actions button {
  margin: 0 0.3rem;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  cursor: pointer;
}
.edit-btn {
  background: #facc15;
  color: #1e293b;
}
.delete-btn {
  background: #ef4444;
  color: white;
}
.modal {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: rgba(255, 255, 255, 0.05);
  padding: 1.5rem;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
}
.modal-title {
  font-size: 1.5rem;
  color: #818cf8;
  margin-bottom: 1rem;
}
.modal-content input {
  display: block;
  width: 100%;
  margin-bottom: 0.8rem;
  padding: 0.5rem;
  border-radius: 6px;
  border: none;
}
.preview-img {
  width: 100px;
  height: 80px;
  border-radius: 8px;
  margin-top: 0.5rem;
}
.modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}
.cancel-btn {
  background: #475569;
  color: white;
}
.save-btn {
  background: #6366f1;
  color: white;
}
</style>


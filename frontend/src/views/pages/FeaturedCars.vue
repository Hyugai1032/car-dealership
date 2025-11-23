<template>
  <section class="featured">
    <h2>Featured Cars</h2>

    <div class="car-grid">
      <div class="car-card" v-for="car in cars" :key="car.id">
        <img :src="car.image" :alt="car.model" />

        <div class="info">
          <h3>{{ car.make }} {{ car.model }}</h3>
          <p class="price">${{ car.price.toLocaleString() }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const cars = [
  { id: 1, make: 'Chevrolet', model: 'Camaro', price: 28000, image: 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d' },
  { id: 2, make: 'Porsche', model: 'GT3 RS', price: 26000, image: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70' },
  { id: 3, make: 'BMW', model: '3 Series', price: 42000, image: 'https://i.wpimg.pl/1024x683/filerepo.grupawp.pl/api/v1/display/embed/a672eaf5-ccba-4b97-9efc-f275a92e822b' },
];
</script>

<style scoped>
.featured {
  padding: 4rem 2rem;
  text-align: center;
  background: linear-gradient(135deg, #dad9d9, #d2d1d1, #ebe9e9);
  color: #fff;
}

/* Title */
h2 {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 2.5rem;
}

/* Grid */
.car-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 2.2rem;
}

/* Car Card */
.car-card {
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(8px);
  padding: 1.2rem;
  border-radius: 14px;
  box-shadow: 0px 6px 18px rgba(0,0,0,0.35);
  transition: 0.35s ease;
  cursor: pointer;
}

.car-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0px 12px 30px rgba(0,0,0,0.45);
}

/* Image */
img {
  width: 100%;
  border-radius: 12px;
  height: 180px;
  object-fit: cover;
}

/* Info */
.info h3 {
  margin-top: 0.8rem;
  color: #fff;
  font-size: 1.25rem;
}

.price {
  color: #ff4d4d;
  font-weight: 700;
  font-size: 1.15rem;
  margin-top: 0.4rem;
}
</style>

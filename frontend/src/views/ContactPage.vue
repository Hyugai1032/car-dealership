<template>
  <div class="contact-page-wrapper bg-gray-50 min-h-screen flex flex-col">

    <!-- Header (same as Cars page) -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
      <h1 class="text-2xl font-semibold text-red-600">RideZone</h1>

      <nav class="space-x-6 text-gray-700 font-medium flex items-center">
        <router-link to="/home" class="hover:text-red-600">Home</router-link>
        <router-link to="/cars-page" class="hover:text-red-600">Cars</router-link>
        <router-link to="/car-comparison" class="relative hover:text-red-600 flex items-center gap-1 font-bold">
          Compare
        </router-link>
        <router-link to="/about" class="hover:text-red-600">About</router-link>
        <router-link to="/contact" class="text-red-600 border-b-2 border-red-600">Contact</router-link>
        <button @click="$router.push({ name: 'login' })"
          class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
          Login
        </button>
      </nav>
    </header>

    <!-- Main Contact Content -->
    <section class="contact-page flex-1">
      <div class="container">
        <h1>Contact Us</h1>

        <p class="subtitle">
          We’re here to assist you — reach out for inquiries, test drives, or dealership info.
        </p>

        <div class="contact-box">
          <h2>Get in Touch</h2>

          <p><strong>Phone:</strong> +63 912 345 6789</p>
          <p><strong>Email:</strong> sales@cardeal.ph</p>
          <p><strong>Address:</strong> 123 Dealer St., Manila, PH</p>
        </div>

        <div class="map-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3264.449272164198!2d121.16797922293794!3d13.343663153786602!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bced0059cfbc8f%3A0xfeeddde9d147a69!2sBYD%20Calapan!5e0!3m2!1sen!2sph!4v1763618195824!5m2!1sen!2sph"
            width="100%"
            height="450"
            style="border:0; border-radius: 12px; margin-top: 2rem;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t p-6 text-center text-sm text-gray-600">
      © 2025 RideZone. All rights reserved.
    </footer>

  </div>
</template>

<script setup>
</script>

<style scoped>
.contact-page {
  padding: 4rem 1.5rem;
  background: linear-gradient(135deg, #ffffff, #e7e5e5);
  color: #000;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  text-align: center;
}

h1 {
  font-size: 2.5rem;
  margin-bottom: .5rem;
}

.subtitle {
  margin-bottom: 2rem;
}

.contact-box {
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  backdrop-filter: blur(10px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

.map-container {
  margin-top: 2rem;
}
</style>

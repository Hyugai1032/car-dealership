<template>
  
  <main>
    <HeroSection />
    <FeaturedCars />
    <ServiceSection />
    <PromotionSection />
    <CTASection />
    <TestimonialSection />
    <FooterSection />
  </main>
</template>

<script setup>
import HeroSection from './pages/HeroSection.vue'
import FeaturedCars from './pages/FeaturedCars.vue'
import ServiceSection from './pages/ServiceSection.vue'
import PromotionSection from './pages/PromotionSection.vue'
import CTASection from './pages/CTASection.vue'
import TestimonialSection from './pages/TestimonialSection.vue'
import FooterSection from './pages/FooterSection.vue'
</script>

<style>
main { background: #fff; color: #111; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
</style>

<template>
  <section class="services section" aria-label="Our Services">
    <div class="container">
      <div class="heading">
        <h2>Why Choose Us</h2>
        <p class="sub">We make buying a car simple, safe and transparent.</p>
      </div>

      <div class="cards">
        <div class="card" v-for="(s, i) in services" :key="i">
          <div class="icon" v-html="s.icon"></div>
          <h3>{{ s.title }}</h3>
          <p>{{ s.text }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const services = [
  {
    title: 'Certified Vehicles',
    text: 'Pre-inspected and dealer-certified cars with full history.',
    icon: `<svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15 8L22 9L17 14L18 21L12 18L6 21L7 14L2 9L9 8L12 2Z" stroke="#c1121f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>`
  },
  {
    title: 'Flexible Financing',
    text: 'Multiple loan partners and easy monthly plans.',
    icon: `<svg width="36" height="36" viewBox="0 0 24 24" fill="none"><path d="M12 3V21" stroke="#c1121f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 7H19" stroke="#c1121f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>`
  },
  {
    title: 'Trade-in Assistance',
    text: 'Quick valuations and seamless trade-in process.',
    icon: `<svg width="36" height="36" viewBox="0 0 24 24" fill="none"><path d="M3 12H21" stroke="#c1121f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 7L12 3L16 7" stroke="#c1121f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>`
  }
]
</script>

<style scoped>
.section { padding: 4rem 1rem; background: #ffffff; }
.container { max-width: 1200px; margin: 0 auto; }
.heading { text-align: center; margin-bottom: 2rem; }
.heading h2 { font-size: 2rem; color: #111; margin-bottom: 0.25rem; }
.heading .sub { color: #666; }

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.2rem;
}

.card {
  background: #fafafa;
  border-radius: 12px;
  padding: 1.4rem;
  text-align: left;
  box-shadow: 0 6px 18px rgba(17,17,17,0.04);
  transition: transform .18s ease, box-shadow .18s ease;
  display:flex;
  gap:1rem;
  align-items:flex-start;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(17,17,17,0.08);
}

.icon { flex: 0 0 48px; }
h3 { margin: 0 0 .35rem; font-size: 1.05rem; color: #111; }
p { margin: 0; color: #555; font-size: .95rem; }
</style>
  
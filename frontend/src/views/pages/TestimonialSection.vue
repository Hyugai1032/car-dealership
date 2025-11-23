<template>
  <section class="testimonials section" aria-label="Customer testimonials">
    <div class="container">
      <div class="heading">
        <h2>What Customers Say</h2>
        <p class="sub">Real reviews from people who purchased through us.</p>
      </div>

      <div class="test-grid">
        <div class="test-card" v-for="(t,i) in testimonials" :key="i">
          <div class="top">
            <div class="avatar">{{ initials(t.name) }}</div>
            <div>
              <strong>{{ t.name }}</strong>
              <div class="stars" aria-hidden="true">★ {{ t.rating }}</div>
            </div>
          </div>
          <p class="quote">“{{ t.text }}”</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const testimonials = [
  { name: 'Maria Dela Cruz', rating: 5, text: 'Smooth transaction and excellent after-sales support.' },
  { name: 'Juan Santos', rating: 5, text: 'Got a great trade-in deal and fast approval for financing.' },
  { name: 'Anne Reyes', rating: 4, text: 'Helpful sales team and transparent pricing.' },
]

function initials(name){
  return name.split(' ').map(n=>n[0]).slice(0,2).join('').toUpperCase()
}
</script>

<style scoped>
.section { padding: 3.5rem 1rem; background: #fff; }
.container { max-width:1100px; margin:0 auto; }
.heading { text-align:center; margin-bottom:1.4rem; }
.heading h2 { font-size:1.9rem; color:#111; }
.heading .sub { color:#666; }

.test-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:1rem; }
.test-card {
  background:#fafafa;
  padding:1.1rem;
  border-radius:12px;
  box-shadow:0 6px 18px rgba(17,17,17,0.03);
  display:flex;
  flex-direction:column;
  gap:.8rem;
}
.top { display:flex; gap:.8rem; align-items:center; }
.avatar {
  width:44px; height:44px; border-radius:10px;
  background:linear-gradient(135deg,#fff 0%, #f3f3f3 100%);
  display:flex; align-items:center; justify-content:center;
  color:#c1121f; font-weight:700; border:1px solid #eee;
}
.stars { color:#ffb400; font-size:.95rem; margin-top:.2rem; }
.quote { color:#444; margin:0; font-style:italic; }
</style>

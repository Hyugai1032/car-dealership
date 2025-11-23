<template>
  <section class="cta-section">
    <div class="container cta-wrap">
      <div class="left">
        <h2>Ready to Drive Home Your Dream Car?</h2>
        <p>Book a test drive or request a quote — fast, secure, and hassle-free.</p>
      </div>
      <div class="actions">
        <button class="btn primary" @click="goReserve">Book Test Drive</button>
        <button class="btn ghost" @click="goQuote">Get a Quote</button>
      </div>
    </div>
  </section>
</template>

<script setup>
function goReserve() {
  // navigate or emit event to open booking modal
  alert('Open booking flow (implement router or modal).')
}
function goQuote() {
  alert('Open quote flow (implement router or modal).')
}
</script>

<style scoped>
.cta-section { background: linear-gradient(90deg,#cecdcd 0%, #939393 100%); color:white; padding:2.2rem 1rem; border-radius:12px; margin: 2rem 1rem; }
.container { max-width:1200px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.cta-wrap { display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.left h2 { margin:0; font-size:1.6rem; }
.left p { margin: .4rem 0 0; color: #4c4a4a; }
.actions { display:flex; gap:.75rem; }
.btn { padding:.7rem 1.1rem; border-radius:10px; font-weight:600; cursor:pointer; border: none; }
.btn.primary { background: #c1121f; color: white; }
.btn.ghost { background: transparent; color: white; border: 1px solid rgba(255,255,255,0.12); }
@media (max-width:760px){
  .container { flex-direction: column; text-align:center; }
  .actions { width:100%; justify-content:center; }
}
</style>

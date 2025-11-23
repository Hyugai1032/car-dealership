<template>
  <section class="promotions section" aria-label="Promotions">
    <div class="container">
      <div class="promo-header">
        <h2>Hot Promotions</h2>
        <p class="sub">Grab limited-time offers and special deals.</p>
      </div>

      <div class="carousel" @mouseenter="pause" @mouseleave="play">
        <div class="track" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
          <div class="slide" v-for="(p, i) in promos" :key="i">
            <div class="promo-card">
              <img :src="p.image" :alt="p.title" />
              <div class="promo-body">
                <h3>{{ p.title }}</h3>
                <p class="desc">{{ p.desc }}</p>
                <div class="meta">
                  <span class="price">₱{{ p.price.toLocaleString() }}</span>
                  <button class="btn" @click="openPromo(p)">View Offer</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="dots">
          <button v-for="(p,i) in promos" :key="i" :class="{ active: i === currentIndex }" @click="go(i)" :aria-label="`Go to promo ${i+1}`"></button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const promos = [
  { title: '0% Promo on Selected Models', desc: 'Limited allocation – book while stocks last.', price: 1299000, image: 'https://images.unsplash.com/photo-1501386763375-9b4c9e7c0a8a' },
  { title: 'Trade-in Bonus', desc: 'Extra value for trade-ins this week only.', price: 899000, image: 'https://images.unsplash.com/photo-1493238792000-8113da705763' },
  { title: 'Holiday Cashbacks', desc: 'Cashback when you reserve & complete purchase.', price: 599000, image: 'https://images.unsplash.com/photo-1544103478-0a3a5b7f3f2f' }
]

const currentIndex = ref(0)
let timer = null

function play() {
  stopTimer()
  timer = setInterval(() => {
    currentIndex.value = (currentIndex.value + 1) % promos.length
  }, 4000)
}

function pause() { stopTimer() }
function stopTimer() { if (timer) { clearInterval(timer); timer = null } }

function go(i) { currentIndex.value = i }

function openPromo(p) {
  // hook for real action: navigate to promo page or open modal
  alert(`Open promo: ${p.title}`)
}

onMounted(() => play())
onBeforeUnmount(() => stopTimer())
</script>

<style scoped>
.section { padding: 4rem 1rem; background: #f8f8f9; }
.container { max-width: 1200px; margin: 0 auto; }

.promo-header { text-align:center; margin-bottom:1.6rem; }
.promo-header h2 { font-size:2rem; color:#111; }
.promo-header .sub { color:#666; }

.carousel { position: relative; overflow: hidden; border-radius:12px; background: #fff; padding: 1rem; box-shadow: 0 12px 30px rgba(17,17,17,0.04); }
.track { display: flex; transition: transform .6s cubic-bezier(.22,.9,.35,1); width: 100%; }
.slide { min-width: 100%; box-sizing: border-box; padding: .6rem; display:flex; align-items:center; justify-content:center; }
.promo-card { display:flex; gap:1rem; align-items:center; width:100%; }
.promo-card img { width: 48%; height: 220px; object-fit:cover; border-radius:10px; }
.promo-body { width: 52%; }
.promo-body h3 { margin:0 0 .5rem; font-size:1.2rem; color:#111; }
.desc { color:#555; margin-bottom:1rem; }
.meta { display:flex; align-items:center; gap:1rem; }
.price { color: #c1121f; font-weight:700; font-size:1.05rem; }
.btn { background:#c1121f; color:white; border:none; padding:.6rem 1rem; border-radius:8px; cursor:pointer; }

.dots { position:absolute; left:50%; transform:translateX(-50%); bottom:.8rem; display:flex; gap:.5rem; }
.dots button { width:10px; height:10px; border-radius:50%; border:none; background:#ddd; cursor:pointer; }
.dots button.active { background:#c1121f; transform:scale(1.08); }
@media (max-width:800px){
  .promo-card { flex-direction: column; }
  .promo-card img, .promo-body { width: 100%; }
  .promo-card img { height:180px; }
}
</style>

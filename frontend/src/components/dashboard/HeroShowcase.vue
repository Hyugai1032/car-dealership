<template>
  <section class="hero-showcase" v-if="cars.length">
    <!-- Animated Background Layers -->
    <div class="bg-layers">
      <div class="bg-layer layer-1" :style="{ background: currentCar.bg || defaultBg }"></div>
      <div class="bg-layer layer-2" :style="{ background: currentCar.bg || defaultBg }"></div>
      <div class="bg-layer layer-3" :style="{ background: currentCar.bg || defaultBg }"></div>
    </div>

    <!-- Floating Particles -->
    <div class="particles-container">
      <div class="particle" v-for="n in 15" :key="n" :style="getParticleStyle(n)"></div>
    </div>

    <div class="showcase-content">
      <!-- Left Info Panel -->
      <div class="info-panel">
        <!-- Premium Badge -->
        <div class="premium-badge">
          <div class="badge-glow"></div>
          <span class="badge-text">PREMIUM SELECTION</span>
          <div class="badge-sparkle">
            <i class="fas fa-star"></i>
          </div>
        </div>

        <!-- Car Title with Animation -->
        <div class="title-container">
          <div class="make-model">
            <span class="car-make">{{ currentCar.make }}</span>
            <span class="car-model">{{ currentCar.model }}</span>
          </div>
          <div class="year-trim">
            <span class="car-year">{{ currentCar.year }}</span>
            <span class="car-trim">{{ currentCar.trim_level }}</span>
          </div>
        </div>

        <!-- Description -->
        <p class="car-description">{{ currentCar.description }}</p>

        <!-- Enhanced Specs Grid -->
        <div class="specs-grid">
          <div class="spec-card">
            <div class="spec-icon">
              <i class="fas fa-horse-head"></i>
            </div>
            <div class="spec-info">
              <span class="spec-value">{{ currentCar.horsepower }} HP</span>
              <span class="spec-label">Power</span>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon">
              <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="spec-info">
              <span class="spec-value">{{ currentCar.engine_capacity }}L</span>
              <span class="spec-label">Engine</span>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon">
              <i class="fas fa-gas-pump"></i>
            </div>
            <div class="spec-info">
              <span class="spec-value">{{ currentCar.fuel_type }}</span>
              <span class="spec-label">Fuel</span>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon">
              <i class="fas fa-cog"></i>
            </div>
            <div class="spec-info">
              <span class="spec-value">{{ currentCar.transmission }}</span>
              <span class="spec-label">Transmission</span>
            </div>
          </div>
        </div>

        <!-- Price & Actions -->
        <div class="price-actions">
          <div class="price-tag">
            <div class="price-glow"></div>
            <span class="price-currency">₱</span>
            <span class="price-amount">{{ Number(currentCar.price).toLocaleString() }}</span>
            <div class="price-ribbon">EXCLUSIVE</div>
          </div>

          <div class="action-buttons">
            <button class="btn-primary">
              <div class="btn-glow"></div>
              <i class="fas fa-calendar-check"></i>
              <span>Schedule Test Drive</span>
              <div class="btn-sparkle">
                <i class="fas fa-bolt"></i>
              </div>
            </button>
            <button class="btn-secondary">
              <i class="fas fa-info-circle"></i>
              <span>View Details</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Enhanced Car Image Section -->
      <div class="image-panel">
        <div class="image-container">
          <!-- Car Image with Multiple Effects -->
          <div class="car-image-wrapper">
            <div class="image-reflection"></div>
            <img 
              :src="getImageUrl(currentCar.main_image)" 
              :alt="currentCar.make + ' ' + currentCar.model"
              class="car-image"
            />
            <div class="image-halo"></div>
            <div class="image-shine"></div>
          </div>

          <!-- Floating Spec Bubbles -->
          <div class="spec-bubbles">
            <div class="bubble" v-for="bubble in specBubbles" :key="bubble.id" :style="bubble.style">
              <i :class="bubble.icon"></i>
              <span>{{ bubble.value }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Navigation Controls -->
    <div class="showcase-controls">
      <button @click="prevCar" class="control-btn prev">
        <div class="btn-inner">
          <i class="fas fa-chevron-left"></i>
        </div>
        <div class="btn-glow"></div>
      </button>

      <div class="car-indicators">
        <button 
          v-for="(car, index) in cars" 
          :key="car.id"
          :class="['indicator', { active: currentCarIndex === index }]"
          @click="showCar(index)"
        >
          <div class="indicator-glow"></div>
          <div class="indicator-progress"></div>
        </button>
      </div>

      <button @click="nextCar" class="control-btn next">
        <div class="btn-inner">
          <i class="fas fa-chevron-right"></i>
        </div>
        <div class="btn-glow"></div>
      </button>

      <!-- Auto-play Toggle -->
      <button class="control-btn play-pause" @click="toggleAutoPlay">
        <div class="btn-inner">
          <i :class="autoPlay ? 'fas fa-pause' : 'fas fa-play'"></i>
        </div>
      </button>
    </div>

    <!-- Progress Bar -->
    <div class="progress-bar">
      <div class="progress-fill" :style="{ width: progressWidth }"></div>
    </div>
  </section>
</template>

<script>
import { ref, onMounted, computed, onUnmounted } from 'vue'

export default {
  name: 'HeroShowcase',
  setup() {
    const cars = ref([])
    const currentCarIndex = ref(0)
    const autoPlay = ref(true)
    const defaultBg = 'radial-gradient(circle at 30% 20%, #d40000 0%, #8b0000 50%, #1a1a1a 100%)'
    let autoPlayInterval

    const currentCar = computed(() => cars.value[currentCarIndex.value] || {})

    const progressWidth = computed(() => {
      return `${((currentCarIndex.value + 1) / cars.value.length) * 100}%`
    })

    const specBubbles = computed(() => [
      {
        id: 1,
        icon: 'fas fa-bolt',
        value: `${currentCar.value.horsepower || '--'} HP`,
        style: { top: '20%', left: '10%', animationDelay: '0s' }
      },
      {
        id: 2,
        icon: 'fas fa-tachometer-alt',
        value: `0-100: ${currentCar.value.acceleration || '--'}s`,
        style: { top: '60%', left: '15%', animationDelay: '0.5s' }
      },
      {
        id: 3,
        icon: 'fas fa-gas-pump',
        value: currentCar.value.fuel_type || '--',
        style: { top: '30%', right: '10%', animationDelay: '1s' }
      },
      {
        id: 4,
        icon: 'fas fa-cogs',
        value: currentCar.value.transmission || '--',
        style: { top: '70%', right: '15%', animationDelay: '1.5s' }
      }
    ])
        const getImageUrl = (path) => {
        if (!path) return 'http://localhost:8000/api/placeholder/800/400'
        return `http://localhost:8000${path}`
        }

    const getParticleStyle = (index) => {
      const size = Math.random() * 4 + 2
      return {
        width: `${size}px`,
        height: `${size}px`,
        left: `${Math.random() * 100}%`,
        animationDelay: `${Math.random() * 5}s`,
        animationDuration: `${Math.random() * 3 + 2}s`
      }
    }

    // ✅ Replaced axios with Fetch
    const fetchCars = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/cars')
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`)
        }
        const data = await response.json()
        cars.value = data.map(car => ({
          ...car,
          bg: car.bg || defaultBg
        }))
      } catch (err) {
        console.error('Error fetching cars:', err)
      }
    }

    const nextCar = () => {
      currentCarIndex.value = (currentCarIndex.value + 1) % cars.value.length
      resetAutoPlay()
    }

    const prevCar = () => {
      currentCarIndex.value = (currentCarIndex.value - 1 + cars.value.length) % cars.value.length
      resetAutoPlay()
    }

    const showCar = (index) => {
      currentCarIndex.value = index
      resetAutoPlay()
    }

    const toggleAutoPlay = () => {
      autoPlay.value = !autoPlay.value
      if (autoPlay.value) {
        startAutoPlay()
      } else {
        clearInterval(autoPlayInterval)
      }
    }

    const startAutoPlay = () => {
      autoPlayInterval = setInterval(() => {
        nextCar()
      }, 5000)
    }

    const resetAutoPlay = () => {
      if (autoPlay.value) {
        clearInterval(autoPlayInterval)
        startAutoPlay()
      }
    }

    onMounted(() => {
      fetchCars()
      startAutoPlay()
    })

    onUnmounted(() => {
      clearInterval(autoPlayInterval)
    })

    return {
      cars,
      currentCar,
      currentCarIndex,
      autoPlay,
      progressWidth,
      specBubbles,
      nextCar,
      prevCar,
      showCar,
      toggleAutoPlay,
      getImageUrl,
      getParticleStyle,
      defaultBg
    }
  }
}
</script>


<style scoped>
.hero-showcase {
  height: 700px;
  border-radius: 30px;
  overflow: hidden;
  position: relative;
  margin: 20px 0;
  box-shadow: 
    0 25px 60px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  background: #0a0a0a;
}

/* Background Layers */
.bg-layers {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.bg-layer {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.layer-1 {
  filter: blur(0px);
  opacity: 1;
}

.layer-2 {
  filter: blur(20px);
  opacity: 0.7;
  transform: scale(1.1);
}

.layer-3 {
  filter: blur(40px);
  opacity: 0.4;
  transform: scale(1.2);
}

/* Particles */
.particles-container {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
}

.particle {
  position: absolute;
  background: rgba(212, 0, 0, 0.3);
  border-radius: 50%;
  animation: floatParticle linear infinite;
}

@keyframes floatParticle {
  0% {
    transform: translateY(100vh) rotate(0deg);
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  90% {
    opacity: 1;
  }
  100% {
    transform: translateY(-100px) rotate(360deg);
    opacity: 0;
  }
}

.showcase-content {
  position: relative;
  z-index: 10;
  display: grid;
  grid-template-columns: 1fr 1fr;
  height: 100%;
  padding: 50px;
  align-items: center;
  gap: 40px;
}

/* Info Panel */
.info-panel {
  color: white;
  max-width: 600px;
}

.premium-badge {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: linear-gradient(135deg, rgba(212, 0, 0, 0.9), rgba(139, 0, 0, 0.9));
  color: white;
  padding: 12px 20px;
  border-radius: 25px;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  position: relative;
  overflow: hidden;
  margin-bottom: 30px;
}

.badge-glow {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  animation: shine 3s infinite;
}

.badge-sparkle {
  animation: sparkle 2s ease-in-out infinite;
}

@keyframes shine {
  0% { left: -100%; }
  100% { left: 100%; }
}

@keyframes sparkle {
  0%, 100% { transform: scale(1) rotate(0deg); }
  50% { transform: scale(1.2) rotate(180deg); }
}

.title-container {
  margin-bottom: 25px;
}

.make-model {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-bottom: 10px;
}

.car-make {
  font-size: 1.5rem;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.8);
  text-transform: uppercase;
  letter-spacing: 3px;
}

.car-model {
  font-size: 4rem;
  font-weight: 800;
  line-height: 1;
  background: linear-gradient(135deg, #ffffff, #f0f0f0, #d40000);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 2px 20px rgba(212, 0, 0, 0.3);
}

.year-trim {
  display: flex;
  gap: 15px;
  align-items: center;
}

.car-year, .car-trim {
  background: rgba(255, 255, 255, 0.1);
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.9rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
}

.car-description {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
  margin-bottom: 35px;
  max-width: 500px;
}

/* Specs Grid */
.specs-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
  margin-bottom: 35px;
}

.spec-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: rgba(255, 255, 255, 0.05);
  padding: 15px;
  border-radius: 15px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.spec-card:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: translateY(-3px);
}

.spec-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #d40000, #a80000);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
}

.spec-info {
  display: flex;
  flex-direction: column;
}

.spec-value {
  font-size: 1.1rem;
  font-weight: 700;
  color: white;
}

.spec-label {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.7);
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Price & Actions */
.price-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 30px;
}

.price-tag {
  position: relative;
  background: linear-gradient(135deg, rgba(212, 0, 0, 0.9), rgba(139, 0, 0, 0.9));
  padding: 20px 25px;
  border-radius: 20px;
  color: white;
  font-weight: 800;
  min-width: 200px;
  text-align: center;
  overflow: hidden;
}

.price-glow {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
  animation: priceGlow 2s ease-in-out infinite;
}

@keyframes priceGlow {
  0%, 100% { opacity: 0; }
  50% { opacity: 1; }
}

.price-currency {
  font-size: 1.5rem;
  margin-right: 5px;
}

.price-amount {
  font-size: 2.5rem;
  line-height: 1;
}

.price-ribbon {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #1a1a1a;
  color: #d40000;
  padding: 4px 12px;
  border-radius: 10px;
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  border: 2px solid #d40000;
}

.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex: 1;
}

.btn-primary, .btn-secondary {
  position: relative;
  padding: 18px 25px;
  border: none;
  border-radius: 15px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  backdrop-filter: blur(10px);
  overflow: hidden;
}

.btn-primary {
  background: linear-gradient(135deg, #d40000, #a80000);
  color: white;
  box-shadow: 0 10px 30px rgba(212, 0, 0, 0.4);
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 40px rgba(212, 0, 0, 0.6);
}

.btn-glow {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.5s ease;
}

.btn-primary:hover .btn-glow {
  left: 100%;
}

.btn-sparkle {
  margin-left: auto;
  animation: sparkle 1.5s ease-in-out infinite;
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
}

/* Image Panel */
.image-panel {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.image-container {
  position: relative;
  max-width: 700px;
}

.car-image-wrapper {
  position: relative;
  perspective: 1000px;
}

.car-image {
  width: 100%;
  height: auto;
  filter: drop-shadow(0 30px 60px rgba(0, 0, 0, 0.8));
  animation: floatCar 6s ease-in-out infinite;
  transform-style: preserve-3d;
}

@keyframes floatCar {
  0%, 100% { 
    transform: translateY(0px) rotateX(0deg) rotateY(0deg); 
  }
  25% { 
    transform: translateY(-10px) rotateX(1deg) rotateY(1deg); 
  }
  75% { 
    transform: translateY(-5px) rotateX(-1deg) rotateY(-1deg); 
  }
}

.image-reflection {
  position: absolute;
  bottom: -50px;
  left: 10%;
  right: 10%;
  height: 30px;
  background: linear-gradient(to top, rgba(212, 0, 0, 0.3), transparent);
  filter: blur(10px);
  border-radius: 50%;
}

.image-halo {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 120%;
  height: 120%;
  background: radial-gradient(circle, rgba(212, 0, 0, 0.4) 0%, transparent 70%);
  filter: blur(30px);
  z-index: -1;
  animation: pulseHalo 4s ease-in-out infinite;
}

@keyframes pulseHalo {
  0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
  50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
}

.image-shine {
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
  animation: shineMove 3s ease-in-out infinite;
}

@keyframes shineMove {
  0% { left: -100%; }
  100% { left: 200%; }
}

/* Spec Bubbles */
.spec-bubbles {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
}

.bubble {
  position: absolute;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 10px 15px;
  border-radius: 25px;
  color: white;
  font-size: 0.8rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  animation: floatBubble 3s ease-in-out infinite;
  opacity: 0;
}

@keyframes floatBubble {
  0%, 100% { 
    transform: translateY(20px) scale(0.8);
    opacity: 0;
  }
  50% { 
    transform: translateY(0px) scale(1);
    opacity: 1;
  }
}

/* Enhanced Controls */
.showcase-controls {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  align-items: center;
  gap: 25px;
  z-index: 20;
}

.control-btn {
  position: relative;
  width: 60px;
  height: 60px;
  border: none;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
}

.control-btn:hover {
  background: rgba(212, 0, 0, 0.8);
  transform: scale(1.1);
}

.btn-inner {
  position: relative;
  z-index: 2;
}

.btn-glow {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(212, 0, 0, 0.6) 0%, transparent 70%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.control-btn:hover .btn-glow {
  opacity: 1;
}

.car-indicators {
  display: flex;
  gap: 12px;
}

.indicator {
  position: relative;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: none;
  background: rgba(255, 255, 255, 0.3);
  cursor: pointer;
  transition: all 0.3s ease;
  overflow: hidden;
}

.indicator.active {
  background: #d40000;
  transform: scale(1.2);
}

.indicator-glow {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 50%;
  background: radial-gradient(circle, #d40000 0%, transparent 70%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.indicator.active .indicator-glow {
  opacity: 0.5;
}

.indicator-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #d40000;
  transform: scaleY(0);
  transform-origin: bottom;
  transition: transform 5s linear;
}

.indicator.active .indicator-progress {
  transform: scaleY(1);
}

/* Progress Bar */
.progress-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: rgba(255, 255, 255, 0.1);
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #d40000, #a80000);
  transition: width 0.5s ease;
  position: relative;
  overflow: hidden;
}

.progress-fill::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  animation: progressShine 2s ease-in-out infinite;
}

@keyframes progressShine {
  0% { left: -100%; }
  100% { left: 100%; }
}

/* Responsive Design */
@media (max-width: 1200px) {
  .showcase-content {
    grid-template-columns: 1fr;
    text-align: center;
    padding: 30px;
    gap: 30px;
  }
  
  .car-model {
    font-size: 3rem;
  }
  
  .price-actions {
    flex-direction: column;
    align-items: center;
  }
}

@media (max-width: 768px) {
  .hero-showcase {
    height: 600px;
  }
  
  .car-model {
    font-size: 2.5rem;
  }
  
  .specs-grid {
    grid-template-columns: 1fr;
  }
  
  .showcase-controls {
    bottom: 20px;
    gap: 15px;
  }
  
  .control-btn {
    width: 50px;
    height: 50px;
  }
}
</style>
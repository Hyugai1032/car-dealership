<template>
  <div class="stats-grid">
    <div class="stat-card" v-for="stat in stats" :key="stat.id" :class="stat.theme">
      <div class="stat-icon" :style="{ background: stat.gradient }">
        <i :class="stat.icon"></i>
      </div>
      
      <div class="stat-content">
        <div class="stat-value">{{ stat.value }}</div>
        <div class="stat-label">{{ stat.label }}</div>
        <div class="stat-trend" :class="stat.trend">
          <i :class="stat.trendIcon"></i>
          <span>{{ stat.trendValue }}</span>
        </div>
      </div>
      
      <div class="stat-decoration">
        <div class="decoration-circle"></div>
        <div class="decoration-bar"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'StatsGrid',
  props: {
    stats: {
      type: Object,
      required: true
    },
    theme: {
      type: String,
      default: 'dark'
    }
  },
  setup(props) {
    const statItems = computed(() => [
      {
        id: 1,
        value: props.stats.totalCars,
        label: 'Total Vehicles',
        icon: 'fas fa-car',
        gradient: 'linear-gradient(135deg, #d40000, #a80000)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        trendValue: '+12%',
        theme: 'primary'
      },
      {
        id: 2,
        value: props.stats.totalDealers,
        label: 'Active Dealers',
        icon: 'fas fa-store',
        gradient: 'linear-gradient(135deg, #2d2d2d, #1a1a1a)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        trendValue: '+5%',
        theme: 'secondary'
      },
      {
        id: 3,
        value: props.stats.pendingAppointments,
        label: 'Pending Appointments',
        icon: 'fas fa-calendar-check',
        gradient: 'linear-gradient(135deg, #8b0000, #660000)',
        trend: 'neutral',
        trendIcon: 'fas fa-minus',
        trendValue: '0%',
        theme: 'accent'
      },
      {
        id: 4,
        value: props.stats.totalUsers,
        label: 'Total Customers',
        icon: 'fas fa-users',
        gradient: 'linear-gradient(135deg, #404040, #2d2d2d)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        trendValue: '+8%',
        theme: 'neutral'
      },
      {
        id: 5,
        value: props.stats.revenue,
        label: 'Quarterly Revenue',
        icon: 'fas fa-dollar-sign',
        gradient: 'linear-gradient(135deg, #d40000, #8b0000)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        trendValue: '+27%',
        theme: 'primary'
      }
    ])

    return {
      statItems
    }
  }
}
</script>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.stat-card {
  background: var(--bg-secondary);
  border: 1px solid var(--border-primary);
  border-radius: 20px;
  padding: 30px;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
  transform: translateY(-8px);
  box-shadow: 
    0 15px 40px rgba(0, 0, 0, 0.2),
    0 0 0 1px rgba(212, 0, 0, 0.1);
}

.stat-card.primary:hover {
  box-shadow: 
    0 15px 40px rgba(212, 0, 0, 0.3),
    0 0 0 1px rgba(212, 0, 0, 0.2);
}

.stat-icon {
  width: 70px;
  height: 70px;
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.8rem;
  margin-bottom: 20px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  position: relative;
  z-index: 2;
}

.stat-icon::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
  border-radius: 18px;
}

.stat-content {
  position: relative;
  z-index: 2;
}

.stat-value {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--text-primary);
  line-height: 1;
  margin-bottom: 8px;
  background: linear-gradient(135deg, var(--text-primary), var(--text-secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-label {
  font-size: 1rem;
  color: var(--text-secondary);
  font-weight: 600;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-trend {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.9rem;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 20px;
  width: fit-content;
}

.stat-trend.up {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.stat-trend.down {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border: 1px solid rgba(239, 68, 68, 0.2);
}

.stat-trend.neutral {
  background: rgba(156, 163, 175, 0.1);
  color: #9ca3af;
  border: 1px solid rgba(156, 163, 175, 0.2);
}

.stat-decoration {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  pointer-events: none;
  overflow: hidden;
}

.decoration-circle {
  position: absolute;
  top: -50px;
  right: -50px;
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(212, 0, 0, 0.05) 0%, transparent 70%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.stat-card:hover .decoration-circle {
  opacity: 1;
}

.decoration-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, transparent, var(--text-accent), transparent);
  transform: scaleX(0);
  transition: transform 0.3s ease;
}

.stat-card:hover .decoration-bar {
  transform: scaleX(1);
}

/* Specific card themes */
.stat-card.primary .decoration-bar {
  background: linear-gradient(90deg, transparent, #d40000, transparent);
}

.stat-card.secondary .decoration-bar {
  background: linear-gradient(90deg, transparent, #2d2d2d, transparent);
}

.stat-card.accent .decoration-bar {
  background: linear-gradient(90deg, transparent, #8b0000, transparent);
}

/* Responsive Design */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  
  .stat-card {
    padding: 25px;
  }
  
  .stat-value {
    font-size: 2rem;
  }
  
  .stat-icon {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }
}

/* Animation for stat cards */
@keyframes statAppear {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-card {
  animation: statAppear 0.6s ease forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
.stat-card:nth-child(5) { animation-delay: 0.5s; }
</style>
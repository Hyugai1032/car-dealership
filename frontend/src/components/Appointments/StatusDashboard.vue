<template>
  <div class="status-dashboard">
    <h3 class="dashboard-title">Appointment Overview</h3>
    <div class="stats-grid">
      <div class="stat-card" v-for="stat in stats" :key="stat.status" :class="stat.status">
        <div class="stat-icon">
          <i :class="stat.icon"></i>
        </div>
        <div class="stat-info">
          <div class="stat-count">{{ stat.count }}</div>
          <div class="stat-label">{{ stat.label }}</div>
        </div>
        <div class="stat-trend" :class="stat.trend">
          <i :class="stat.trendIcon"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'StatusDashboard',
  props: {
    appointments: {
      type: Array,
      default: () => []
    }
  },
  setup(props) {
    const stats = computed(() => {
      const statusCounts = props.appointments.reduce((acc, appointment) => {
        acc[appointment.status] = (acc[appointment.status] || 0) + 1
        return acc
      }, {})

      return [
        {
          status: 'pending',
          label: 'Pending',
          count: statusCounts.pending || 0,
          icon: 'fas fa-clock',
          trend: 'up',
          trendIcon: 'fas fa-arrow-up'
        },
        {
          status: 'confirmed',
          label: 'Confirmed',
          count: statusCounts.confirmed || 0,
          icon: 'fas fa-check-circle',
          trend: 'up',
          trendIcon: 'fas fa-arrow-up'
        },
        {
          status: 'completed',
          label: 'Completed',
          count: statusCounts.completed || 0,
          icon: 'fas fa-flag-checkered',
          trend: 'neutral',
          trendIcon: 'fas fa-minus'
        },
        {
          status: 'cancelled',
          label: 'Cancelled',
          count: statusCounts.cancelled || 0,
          icon: 'fas fa-times-circle',
          trend: 'down',
          trendIcon: 'fas fa-arrow-down'
        }
      ]
    })

    return {
      stats
    }
  }
}
</script>

<style scoped>
.status-dashboard {
  background: var(--pure-white);
  border-radius: 12px;
  padding: 25px;
  box-shadow: var(--shadow);
  margin-bottom: 30px;
}

.dashboard-title {
  color: var(--luxury-black);
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.dashboard-title::before {
  content: '';
  width: 4px;
  height: 20px;
  background: var(--primary-red);
  border-radius: 2px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  padding: 20px;
  border-radius: 10px;
  border-left: 4px solid;
  transition: var(--transition);
  background: var(--light-gray);
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.stat-card.pending {
  border-left-color: var(--primary-red);
}

.stat-card.confirmed {
  border-left-color: var(--luxury-black);
}

.stat-card.completed {
  border-left-color: var(--dark-gray);
}

.stat-card.cancelled {
  border-left-color: var(--medium-gray);
}

.stat-icon {
  font-size: 2rem;
  margin-right: 15px;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
}

.stat-card.pending .stat-icon {
  background: rgba(212, 0, 0, 0.1);
  color: var(--primary-red);
}

.stat-card.confirmed .stat-icon {
  background: rgba(26, 26, 26, 0.1);
  color: var(--luxury-black);
}

.stat-card.completed .stat-icon {
  background: rgba(102, 102, 102, 0.1);
  color: var(--dark-gray);
}

.stat-card.cancelled .stat-icon {
  background: rgba(224, 224, 224, 0.1);
  color: var(--dark-gray);
}

.stat-info {
  flex: 1;
}

.stat-count {
  font-size: 2rem;
  font-weight: 700;
  color: var(--luxury-black);
  line-height: 1;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 0.9rem;
  color: var(--dark-gray);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-trend {
  font-size: 1.2rem;
  padding: 8px;
  border-radius: 6px;
}

.stat-trend.up {
  color: #10b981;
  background: rgba(16, 185, 129, 0.1);
}

.stat-trend.down {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
}

.stat-trend.neutral {
  color: var(--dark-gray);
  background: rgba(102, 102, 102, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .stat-card {
    padding: 15px;
  }

  .stat-count {
    font-size: 1.5rem;
  }

  .stat-icon {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    margin-right: 10px;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
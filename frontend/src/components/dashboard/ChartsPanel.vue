<template>
  <div class="charts-panel" :class="theme">
    <div class="panel-header">
      <h3 class="panel-title">
        <i class="fas fa-chart-bar"></i>
        Performance Analytics
      </h3>
      <div class="panel-controls">
        <select v-model="selectedPeriod" class="period-select">
          <option value="7d">Last 7 Days</option>
          <option value="30d">Last 30 Days</option>
          <option value="90d">Last 90 Days</option>
          <option value="1y">Last Year</option>
        </select>
      </div>
    </div>
    
    <div class="charts-grid">
      <div class="chart-container">
        <div class="chart-header">
          <h4 class="chart-title">Sales Performance</h4>
          <div class="chart-stats">
            <span class="stat-value">+27%</span>
            <span class="stat-label">vs last period</span>
          </div>
        </div>
        <canvas ref="salesChart" class="chart-canvas"></canvas>
      </div>
      
      <div class="chart-container">
        <div class="chart-header">
          <h4 class="chart-title">Vehicle Distribution</h4>
          <div class="chart-stats">
            <span class="stat-value">247</span>
            <span class="stat-label">total units</span>
          </div>
        </div>
        <canvas ref="distributionChart" class="chart-canvas"></canvas>
      </div>
    </div>
    
    <div class="metrics-grid">
      <div class="metric-card" v-for="metric in metrics" :key="metric.id">
        <div class="metric-icon" :style="{ background: metric.gradient }">
          <i :class="metric.icon"></i>
        </div>
        <div class="metric-info">
          <div class="metric-value">{{ metric.value }}</div>
          <div class="metric-label">{{ metric.label }}</div>
        </div>
        <div class="metric-trend" :class="metric.trend">
          <i :class="metric.trendIcon"></i>
          <span>{{ metric.change }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'

export default {
  name: 'ChartsPanel',
  props: {
    theme: {
      type: String,
      default: 'dark'
    }
  },
  setup(props) {
    const salesChart = ref(null)
    const distributionChart = ref(null)
    const selectedPeriod = ref('30d')
    
    const metrics = ref([
      {
        id: 1,
        value: '84%',
        label: 'Conversion Rate',
        icon: 'fas fa-percentage',
        gradient: 'linear-gradient(135deg, #d40000, #a80000)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        change: '+12%'
      },
      {
        id: 2,
        value: '18.5K',
        label: 'Website Visits',
        icon: 'fas fa-eye',
        gradient: 'linear-gradient(135deg, #2d2d2d, #1a1a1a)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        change: '+8%'
      },
      {
        id: 3,
        value: '2.4K',
        label: 'Test Drives',
        icon: 'fas fa-car',
        gradient: 'linear-gradient(135deg, #8b0000, #660000)',
        trend: 'up',
        trendIcon: 'fas fa-arrow-up',
        change: '+15%'
      },
      {
        id: 4,
        value: '4.7',
        label: 'Avg. Rating',
        icon: 'fas fa-star',
        gradient: 'linear-gradient(135deg, #404040, #2d2d2d)',
        trend: 'neutral',
        trendIcon: 'fas fa-minus',
        change: '+0.2'
      }
    ])

    const initCharts = () => {
      // Sales Chart
      if (salesChart.value) {
        const ctx = salesChart.value.getContext('2d')
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
              label: 'Vehicle Sales',
              data: [45, 52, 38, 65, 72, 58, 80, 85, 78, 92, 88, 95],
              borderColor: '#d40000',
              backgroundColor: 'rgba(212, 0, 0, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.4,
              pointBackgroundColor: '#d40000',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 2,
              pointRadius: 6,
              pointHoverRadius: 8
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              x: {
                grid: {
                  color: props.theme === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                },
                ticks: {
                  color: props.theme === 'dark' ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.7)'
                }
              },
              y: {
                grid: {
                  color: props.theme === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                },
                ticks: {
                  color: props.theme === 'dark' ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.7)'
                }
              }
            }
          }
        })
      }

      // Distribution Chart
      if (distributionChart.value) {
        const ctx = distributionChart.value.getContext('2d')
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ['Sports Cars', 'SUVs', 'Sedans', 'Luxury', 'Electric'],
            datasets: [{
              data: [25, 30, 20, 15, 10],
              backgroundColor: [
                '#d40000',
                '#2d2d2d',
                '#8b0000',
                '#404040',
                '#660000'
              ],
              borderWidth: 0,
              hoverOffset: 15
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: props.theme === 'dark' ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.7)',
                  padding: 20,
                  usePointStyle: true
                }
              }
            }
          }
        })
      }
    }

    onMounted(() => {
      setTimeout(initCharts, 100)
    })

    watch(() => props.theme, () => {
      setTimeout(initCharts, 100)
    })

    return {
      salesChart,
      distributionChart,
      selectedPeriod,
      metrics
    }
  }
}
</script>

<style scoped>
.charts-panel {
  background: var(--bg-secondary);
  border: 1px solid var(--border-primary);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.panel-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 10px;
}

.panel-title i {
  color: var(--text-accent);
}

.period-select {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-primary);
  color: var(--text-primary);
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.period-select:focus {
  outline: none;
  border-color: var(--text-accent);
}

.charts-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
  margin-bottom: 30px;
}

.chart-container {
  background: var(--bg-tertiary);
  border-radius: 15px;
  padding: 25px;
  border: 1px solid var(--border-primary);
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.chart-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text-primary);
}

.chart-stats {
  text-align: right;
}

.stat-value {
  display: block;
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-accent);
}

.stat-label {
  font-size: 0.8rem;
  color: var(--text-secondary);
}

.chart-canvas {
  width: 100% !important;
  height: 250px !important;
}

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.metric-card {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 20px;
  background: var(--bg-tertiary);
  border-radius: 15px;
  border: 1px solid var(--border-primary);
  transition: all 0.3s ease;
}

.metric-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  border-color: var(--text-accent);
}

.metric-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.metric-info {
  flex: 1;
}

.metric-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-primary);
  line-height: 1;
  margin-bottom: 4px;
}

.metric-label {
  font-size: 0.8rem;
  color: var(--text-secondary);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.metric-trend {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.8rem;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 10px;
}

.metric-trend.up {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.metric-trend.down {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.metric-trend.neutral {
  background: rgba(156, 163, 175, 0.1);
  color: #9ca3af;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .charts-grid {
    grid-template-columns: 1fr;
  }
  
  .metrics-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .charts-panel {
    padding: 20px;
  }
  
  .panel-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .metrics-grid {
    grid-template-columns: 1fr;
  }
  
  .chart-container {
    padding: 20px;
  }
}
</style>
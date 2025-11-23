<template>
  <div class="dashboard">
    <div class="background-glow" aria-hidden="true"></div>

    <div class="dashboard-content">
      <!-- Header -->
      <div class="panel-header">
        <div class="header-content">
          <div class="title-section">
            <div class="title-icon">
              <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="title-text">
              <h1 class="main-title">Performance Analytics</h1>
              <p class="subtitle">Real-time stock & appointment insights</p>
            </div>
          </div>

          <div class="header-stats">
            <div class="stat-card">
              <div class="stat-icon"><i class="fas fa-car"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ totalStock }}</div>
                <div class="stat-label">Total Stock</div>
              </div>
            </div>
            <div class="stat-card success">
              <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
              <div class="stat-info">
                <div class="stat-value">{{ totalAppointments }}</div>
                <div class="stat-label">Appointments</div>
              </div>
            </div>
            <div class="stat-card warning">
              <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
              <div class="stat-info">
                <div class="stat-value">+27%</div>
                <div class="stat-label">Growth</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Grid -->
      <div class="charts-grid">
        <!-- Appointment Frequency (Bar Chart) -->
        <div class="chart-card">
          <div class="card-header">
            <h3>Appointment Frequency by Model</h3>
            <p class="card-subtitle">November 2025</p>
          </div>
          <div class="chart-wrapper">
            <canvas ref="barChartRef"></canvas>
          </div>
        </div>

        <!-- Stock Distribution (Doughnut Chart) -->
        <div class="chart-card">
          <div class="card-header">
            <h3>Stock Distribution</h3>
            <p class="card-subtitle">{{ totalStock }} units in inventory</p>
          </div>
          <div class="chart-wrapper doughnut">
            <canvas ref="doughnutChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-overlay">
      <div class="spinner"></div>
      <p>Loading analytics...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import Chart from 'chart.js/auto'

// Chart.js is imported via 'chart.js/auto' which auto-registers controllers

const loading = ref(true)
const stockData = ref([])
const appointmentData = ref([])

const barChartRef = ref(null)
const doughnutChartRef = ref(null)

let barChartInstance = null
let doughnutChartInstance = null

const totalStock = computed(() => 
  stockData.value.reduce((sum, car) => sum + (car.stock_count || 0), 0)
)
const totalAppointments = computed(() => 
  appointmentData.value.reduce((sum, car) => sum + (car.total_appointments || 0), 0)
)

const fetchData = async () => {
  loading.value = true
  try {
    // GET USER FROM LOCALSTORAGE
    const user = JSON.parse(localStorage.getItem('user') || '{}')

    // ADD X-User HEADER SA LAHAT NG REQUEST
    const headers = {
      'Content-Type': 'application/json',
      'X-User': JSON.stringify(user)  // ← ITO ANG KAILANGAN MO!
    }

    const [stockRes, appRes] = await Promise.all([
      fetch('http://localhost:8000/cardistribution', { headers }),
      fetch('http://localhost:8000/dataappointments', { headers })
    ])

    const stockJson = await stockRes.json()
    const appJson = await appRes.json()

    if (stockJson.status === 'success') {
      stockData.value = stockJson.stocks || []
    }
    if (appJson.status === 'success') {
      appointmentData.value = appJson.data || []
    }
  } catch (err) {
    console.error('Failed to load dashboard data:', err)
    notify('Failed to load charts', 'error')
  } finally {
    loading.value = false
    await nextTick()
    renderCharts()
  }
}

const renderCharts = () => {
  // Destroy previous instances
  if (barChartInstance) barChartInstance.destroy()
  if (doughnutChartInstance) doughnutChartInstance.destroy()

  // BAR CHART
  barChartInstance = new Chart(barChartRef.value, {
    type: 'bar',
    data: {
      labels: appointmentData.value.map(c => 
        `${c.make} ${c.model}${c.variant ? ' ' + c.variant : ''}`.trim()
      ),
      datasets: [{
        label: 'Appointments',
        data: appointmentData.value.map(c => c.total_appointments || 0),
        backgroundColor: '#ef4444',
        borderRadius: 10,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1, color: '#fff' } },
        x: { ticks: { color: '#fff', maxRotation: 45 } }
      }
    }
  })

  // DOUGHNUT CHART
  doughnutChartInstance = new Chart(doughnutChartRef.value, {
    type: 'doughnut',
    data: {
      labels: stockData.value.map(c => 
        `${c.make} ${c.model}${c.variant ? ' ' + c.variant : ''}`.trim()
      ),
      datasets: [{
        data: stockData.value.map(c => c.stock_count || 0),
        backgroundColor: ['#ef4444','#dc2626','#b91c1c','#991b1b','#7f1d1d','#450a0a','#a80000'],
        borderColor: '#1a1a1a',
        borderWidth: 4,
        hoverOffset: 30,
        cutout: '70%'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom', labels: { color: '#fff', padding: 20 } }
      }
    }
  })
}

onMounted(fetchData)
</script>

<style scoped>
/* Same luxury style — copy mo lang lahat */
.dashboard { min-height: 100vh; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #fff; position: relative; overflow-x: hidden; font-family: 'Segoe UI', sans-serif; }
.background-glow { position: absolute; top: -60px; left: 40px; right: 40px; height: 720px; background: radial-gradient(circle at 25% 75%, rgba(239,68,68,0.12), transparent 35%), radial-gradient(circle at 80% 20%, rgba(59,130,246,0.04), transparent 30%); pointer-events: none; z-index: 0; border-radius: 28px; filter: blur(36px); }
.dashboard-content { padding: 30px; max-width: 1600px; margin: 0 auto; }
.panel-header { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 35px; margin-bottom: 30px; backdrop-filter: blur(20px); }
.header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
.title-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444, #b91c1c); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; box-shadow: 0 15px 40px rgba(239,68,68,0.4); }
.main-title { font-size: 1.7rem; font-weight: 900; background: linear-gradient(135deg, #fff, #fca5a5); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0; }
.subtitle { color: rgba(255,255,255,0.7); font-size: 1.2rem; margin-top: 8px; }
.header-stats { display: flex; gap: 20px; flex-wrap: wrap; }
.stat-card { background: rgba(255,255,255,0.05); padding: 22px; border-radius: 18px; border: 1px solid rgba(255,255,255,0.1); min-width: 160px; display: flex; align-items: center; gap: 16px; }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #10b981, #059669); }
.stat-card.warning .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-icon { width: 55px; height: 55px; background: linear-gradient(135deg, #2b2b2b, #141414); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff; }
.stat-value { font-size: 2.4rem; font-weight: 900; }
.stat-label { font-size: 0.9rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; }
.charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
@media (max-width: 1100px) { .charts-grid { grid-template-columns: 1fr; } }
.chart-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 30px; backdrop-filter: blur(20px); }
.card-header h3 { font-size: 1.5rem; font-weight: 700; margin: 0 0 8px 0; }
.card-subtitle { color: rgba(255,255,255,0.6); font-size: 0.95rem; margin: 0; }
.chart-wrapper { height: 380px; margin-top: 20px; position: relative; }
.chart-wrapper.doughnut { height: 420px; }

/* custom dark scrollbar to avoid native bright/red accents */
::-webkit-scrollbar { width: 10px; height: 10px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); border-radius: 999px; border: 2px solid transparent; }
::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.10); }

@media (max-width: 700px) { .main-title { font-size: 1.8rem; } .title-icon { width:56px; height:56px; font-size:1.6rem } .chart-wrapper { height: 300px } }
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; }
.kpi-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 28px; text-align: center; position: relative; overflow: hidden; transition: transform 0.3s ease; }
.kpi-card:hover { transform: translateY(-8px); }
.kpi-card i { font-size: 2.2rem; color: #ef4444; margin-bottom: 16px; }
.kpi-value { font-size: 2.8rem; font-weight: 900; margin: 12px 0; }
.kpi-label { color: rgba(255,255,255,0.7); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1.5px; }
.kpi-change { position: absolute; top: 16px; right: 16px; font-size: 0.9rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; }
.kpi-change.positive { background: rgba(16,185,129,0.2); color: #10b981; }
.kpi-change.neutral { background: rgba(156,163,175,0.2); color: #9ca3af; }
.loading-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.98); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(10px); }
.spinner { width: 80px; height: 80px; border: 6px solid rgba(239,68,68,0.3); border-top-color: #ef4444; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
import { createRouter, createWebHistory } from 'vue-router'

// Import your views
import Dashboard from '../views/Dashboard.vue'
import Login from '../components/Auth/Login.vue'
import Registration from '../components/Auth/Registration.vue'
import CarsManagement from '../components/Cars/CarsManagement.vue'
import CarsPage from '../views/pages/CarsPage.vue'
import AppointmentCard from '../components/Appointments/AppointmentCard.vue'

const routes = [
  { path: '/', name: 'dashboard', component: Dashboard },
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'registration', component: Registration },
  { path: '/cars-management', name: 'cars-management', component: CarsManagement },
  { path: '/cars-page', name: 'cars-page', component: CarsPage },
  { path: '/appointments', name: 'appointments', component: AppointmentCard }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

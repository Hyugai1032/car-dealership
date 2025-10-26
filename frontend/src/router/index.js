import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../pages/Dashboard.vue";
import Cars from "../pages/Cars.vue";
import Dealers from "../pages/Dealers.vue";
import Appointments from "../pages/Appointments.vue";

const routes = [
  { path: "/", component: Dashboard },
  { path: "/cars", component: Cars },
  { path: "/dealers", component: Dealers },
  { path: "/appointments", component: Appointments },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

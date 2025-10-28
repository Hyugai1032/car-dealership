import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Auth/Login.vue";
import Register from "../components/Auth/Registration.vue";
import AdminDashboard from "../components/AdminDashboard.vue";

const routes = [
    { path: "/", component: Login },
    { path: "/Login", component: Login },
    { path: "/Register", component: Register },
    { path: "/admin/dashboard", component: AdminDashboard }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

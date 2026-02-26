import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const HomeView = () => import('../views/HomeView.vue');
const LoginView = () => import('../views/Auth/LoginView.vue');

const routes: RouteRecordRaw[] = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;


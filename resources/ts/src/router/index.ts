import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const HomeView = () => import('../views/HomeView.vue');
const LoginView = () => import('../views/Auth/LoginView.vue');
const ProfileView = () => import('../views/Profile/ProfileView.vue');
const TimeView = () => import('../views/Time/TimeView.vue');

const routes: RouteRecordRaw[] = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/profile', name: 'profile', component: ProfileView },
  { path: '/time', name: 'time', component: TimeView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global auth guard: require auth for all routes except login
router.beforeEach(async (to) => {
  const { state, loadUser } = useAuth();

  if (to.name !== 'login') {
    if (!state.isAuthenticated) await loadUser();
    if (!state.isAuthenticated) return { name: 'login', query: { redirect: to.fullPath } };
  }

  if (to.name === 'login' && state.isAuthenticated) {
    const q = to.query?.redirect;
    const redirect = typeof q === 'string' ? q : undefined;
    if (redirect && redirect.startsWith('/')) {
      return redirect;
    }
    return { name: 'home' };
  }
});

export default router;

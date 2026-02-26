import { reactive } from 'vue';
import { loginApi, logoutApi, getMeApi, type LoginPayload } from '../api/auth.api';

type UserLike = Record<string, unknown> | null;

const state = reactive({
  isAuthenticated: false,
  loading: false,
  error: '' as string | '',
  user: null as UserLike,
});

export function useAuth() {
  async function login(payload: LoginPayload) {
    state.loading = true;
    state.error = '';
    try {
      const data = await loginApi(payload);
      state.isAuthenticated = true;
      state.user = (data as any)?.user ?? null;
      return data;
    } catch (e: any) {
      state.error = e?.response?.data?.message || 'Login failed';
      throw e;
    } finally {
      state.loading = false;
    }
  }

  async function logout() {
    state.loading = true;
    try {
      await logoutApi();
    } finally {
      state.isAuthenticated = false;
      state.user = null;
      state.loading = false;
    }
  }

  async function loadUser() {
    try {
      state.loading = true;
      const data = await getMeApi();
      state.user = (data as any)?.user ?? null;
      state.isAuthenticated = !!state.user;
      return state.user;
    } catch (e) {
      state.isAuthenticated = false;
      state.user = null;
    } finally {
      state.loading = false;
    }
  }

  return { state, login, logout, loadUser };
}

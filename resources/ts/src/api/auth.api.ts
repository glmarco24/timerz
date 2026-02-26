import http from './http';

export interface LoginPayload {
  personal_number: string;
  password: string;
}

export async function loginApi(payload: LoginPayload) {
  const { data } = await http.post('/api/login', payload);
  return data;
}

export async function logoutApi() {
  const { data } = await http.post('/api/logout');
  return data;
}

export async function getMeApi() {
  const { data } = await http.get('/api/me');
  return data;
}

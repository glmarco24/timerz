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

export interface UpdateMePayload {
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  password?: string;
}

export async function updateMeApi(payload: UpdateMePayload) {
  const { data } = await http.put('/api/me', payload);
  return data;
}

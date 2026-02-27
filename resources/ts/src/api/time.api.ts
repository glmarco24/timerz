import http from './http';

export interface WorkPlace { id: number; name: string }
export interface TimeFormData { workplaces: WorkPlace[]; defaults: { date: string | null; benefit: string | null } }

export async function getTimeFormData() {
  const { data } = await http.get('/api/companies');
  return data as TimeFormData;
}

export async function getCompanyStaff(companyId: number) {
  const { data } = await http.get(`/api/companies/${companyId}/staff`);
  return data as { staff: Array<{ id: number; first_name: string; last_name: string }> };
}

export interface CreateTimePayload {
  company_id: number;
  user_id: number;
  date: string; // YYYY-MM-DD
  start_time: string; // HH:mm
  end_time?: string | null; // HH:mm
  benefit?: string | null;
  comment: string;
  latitude?: number | null;
  longitude?: number | null;
}

export async function createTime(payload: CreateTimePayload) {
  const { data } = await http.post('/api/times', payload);
  return data as { message: string; time: any };
}

export type TimeListItem = {
  id: number;
  date: string; // YYYY-MM-DD
  start_time: string | null;
  end_time: string | null;
  status: string;
  benefit: string | null;
  comment: string | null;
  user: { id: number; first_name: string; last_name: string };
  company: { id: number; name: string };
};

export async function getTimes() {
  const { data } = await http.get('/api/times');
  return data as { times: TimeListItem[] };
}

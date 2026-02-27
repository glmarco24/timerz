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

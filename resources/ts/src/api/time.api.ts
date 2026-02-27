import http from './http';

export interface WorkPlace { id: number; name: string }
export interface TimeFormData { workplaces: WorkPlace[]; defaults: { date: string | null; benefit: string | null } }

export async function getTimeFormData(companyId?: number) {
  const { data } = await http.get('/api/companies', { params: companyId ? { company_id: companyId } : {} });
  return data as TimeFormData;
}

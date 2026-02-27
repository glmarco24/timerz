<template>
  <div class="min-h-screen flex">
    <SideMenu />

    <div class="flex-1 min-w-0">
      <TopBar />

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        <!-- Top tabs -->
        <div class="flex items-center justify-between">
          <div class="flex items-center rounded-md overflow-hidden border bg-white">
            <button class="px-4 py-2 text-sm font-medium bg-sky-800 text-white">Times</button>
            <button class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Summation</button>
          </div>
          <div class="flex items-center gap-2">
            <button class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-sky-100 text-sky-700 hover:bg-sky-200" title="Export">
              <span class="material-symbols-outlined">download</span>
            </button>
            <button class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-sky-100 text-sky-700 hover:bg-sky-200" title="Add" @click="openAdd">
              <span class="material-symbols-outlined">add</span>
            </button>
          </div>
        </div>

        <!-- Date range navigation -->
        <div class="mt-4 flex items-center gap-3">
          <div class="flex items-center gap-1">
            <button class="h-8 w-8 inline-flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200" title="Previous">
              <span class="material-symbols-outlined text-gray-700">chevron_left</span>
            </button>
            <div class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 text-sm font-medium">Feb 1 - Feb 28, 2026</div>
            <button class="h-8 w-8 inline-flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200" title="Next">
              <span class="material-symbols-outlined text-gray-700">chevron_right</span>
            </button>
          </div>
        </div>

        <!-- Filters -->
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          <select class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
            <option>All groups</option>
          </select>
          <select class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
            <option>All cost centers</option>
          </select>
          <select class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
            <option>View changes</option>
          </select>
          <select class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
            <option>All statuses</option>
          </select>
        </div>

        <!-- Table -->
        <div class="mt-6 overflow-hidden rounded-lg border bg-white">
          <div class="px-4 py-3 border-b text-sm text-gray-600 font-medium">Staff</div>
          <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-3 text-xs text-gray-500 border-b">
            <div class="col-span-4">Name</div>
            <div class="col-span-1">In</div>
            <div class="col-span-1">Out</div>
            <div class="col-span-1">Break</div>
            <div class="col-span-2">Working hours</div>
            <div class="col-span-1">Info</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-1">Manage</div>
          </div>

          <template v-for="section in sections" :key="section.date">
            <div class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-b">{{ section.label }}</div>
            <div
              v-for="row in section.rows"
              :key="row.id"
              class="grid grid-cols-12 gap-4 px-4 py-3 border-b items-center"
            >
              <div class="col-span-12 md:col-span-4 flex items-center gap-3">
                <div class="h-6 w-6 rounded-full bg-gray-200"></div>
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ row.name }}</div>
                  <div class="text-xs text-gray-500">{{ row.company }}</div>
                </div>
              </div>
              <div class="col-span-4 md:col-span-1 text-sm">
                <template v-if="isEditing(row.id, 'start')">
                  <div class="flex items-center gap-2">
                    <div class="w-40">
                      <Datepicker
                        :ref="(el: any) => setEditRef(row.id, 'start', el)"
                        v-model="editing.value"
                        time-picker
                        :is-24="true"
                        :enable-seconds="false"
                        model-type="format"
                        format="HH:mm"
                        :minutes-increment="1"
                        :teleport="true"
                        input-class="w-full rounded-md border border-gray-300 px-3 py-2"
                      />
                    </div>
                  </div>
                </template>
                <template v-else>
                  <span
                    :class="[row.status === 'Deleted' ? 'text-gray-400 line-through' : 'text-gray-700', row.status !== 'Deleted' ? 'cursor-pointer underline decoration-dotted' : '']"
                    @click="row.status !== 'Deleted' && startEdit(row.id, 'start', row.in)">
                    {{ row.in }}
                  </span>
                </template>
              </div>
              <div class="col-span-4 md:col-span-1 text-sm">
                <template v-if="isEditing(row.id, 'end')">
                  <div class="flex items-center gap-2">
                    <div class="w-40">
                      <Datepicker
                        :ref="(el: any) => setEditRef(row.id, 'end', el)"
                        v-model="editing.value"
                        time-picker
                        :is-24="true"
                        :enable-seconds="false"
                        model-type="format"
                        format="HH:mm"
                        :minutes-increment="1"
                        :teleport="true"
                        input-class="w-full rounded-md border border-gray-300 px-3 py-2"
                      />
                    </div>
                  </div>
                </template>
                <template v-else>
                  <span
                    :class="[row.status === 'Deleted' ? 'text-gray-400 line-through' : 'text-gray-700', row.status !== 'Deleted' ? 'cursor-pointer underline decoration-dotted' : '']"
                    @click="row.status !== 'Deleted' && startEdit(row.id, 'end', row.out)">
                    {{ row.out }}
                  </span>
                </template>
              </div>
              <div class="col-span-4 md:col-span-1 text-sm text-gray-700">{{ row.break }}</div>
              <div :class="['col-span-6 md:col-span-2 text-sm', row.status === 'Deleted' ? 'text-gray-400 line-through' : 'text-gray-700']">{{ row.hours }}</div>
              <div class="col-span-3 md:col-span-1 flex gap-1">
                <span class="material-symbols-outlined text-sky-700 text-base">chat</span>
                <span class="material-symbols-outlined text-sky-700 text-base">note</span>
              </div>
              <div class="col-span-3 md:col-span-1">
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 text-gray-700 text-xs px-2 py-1">
                  <span class="material-symbols-outlined text-sm">info</span> {{ row.status }}
                </span>
              </div>
              <div class="col-span-3 md:col-span-1 flex gap-1 justify-end md:justify-start">
                <button class="h-8 w-8 inline-flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200">
                  <span class="material-symbols-outlined text-gray-700">more_horiz</span>
                </button>
              </div>
            </div>
          </template>

        </div>
      </div>
    </div>
  </div>

  <!-- Add time modal -->
  <div v-if="addOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
      <div class="px-5 py-4 border-b flex items-center justify-between">
        <h3 class="text-lg font-semibold">Add time</h3>
        <button class="h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-gray-100" @click="closeAdd">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-700 mb-1">Date</label>
            <input type="date" v-model="form.date" :class="['w-full rounded-md border px-3 py-2', errors.date ? 'border-red-500' : 'border-gray-300']" />
            <p v-if="errors.date" class="mt-1 text-sm text-red-600">{{ errors.date }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">Workplace</label>
            <select v-model="form.company_id" :class="['w-full rounded-md border px-3 py-2', errors.company_id ? 'border-red-500' : 'border-gray-300']">
              <option v-for="wp in workplaces" :key="wp.id" :value="wp.id">{{ wp.name }}</option>
            </select>
            <p v-if="errors.company_id" class="mt-1 text-sm text-red-600">{{ errors.company_id }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">Staff</label>
            <select v-model="form.user_id" :class="['w-full rounded-md border px-3 py-2', errors.user_id ? 'border-red-500' : 'border-gray-300']">
              <option v-for="u in staff" :key="u.id" :value="u.id">{{ u.first_name }} {{ u.last_name }}</option>
            </select>
            <p v-if="errors.user_id" class="mt-1 text-sm text-red-600">{{ errors.user_id }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">Benefit</label>
            <input type="text" placeholder="e.g. Regular" v-model="form.benefit" class="w-full rounded-md border border-gray-300 px-3 py-2" />
          </div>
          
          <div>
            <label class="block text-sm text-gray-700 mb-1">Start</label>
            <Datepicker
              v-model="form.start_time"
              time-picker
              :is-24="true"
              :enable-seconds="false"
              model-type="format"
              format="HH:mm"
              :minutes-increment="1"
              :input-class="['w-full rounded-md border px-3 py-2', errors.start_time ? 'border-red-500' : 'border-gray-300'].join(' ')"
            />
            <p v-if="errors.start_time" class="mt-1 text-sm text-red-600">{{ errors.start_time }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">End</label>
            <Datepicker
              v-model="form.end_time"
              time-picker
              :is-24="true"
              :enable-seconds="false"
              model-type="format"
              format="HH:mm"
              :minutes-increment="1"
              :input-class="['w-full rounded-md border px-3 py-2', errors.end_time ? 'border-red-500' : 'border-gray-300'].join(' ')"
            />
            <p v-if="errors.end_time" class="mt-1 text-sm text-red-600">{{ errors.end_time }}</p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-1">Comment</label>
            <textarea rows="3" v-model="form.comment" :class="['w-full rounded-md border px-3 py-2', errors.comment ? 'border-red-500' : 'border-gray-300']"></textarea>
            <p v-if="errors.comment" class="mt-1 text-sm text-red-600">{{ errors.comment }}</p>
          </div>
        </div>
      </div>
      <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
        <button class="rounded-md px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200" @click="closeAdd">Cancel</button>
        <button :disabled="saving" class="rounded-md px-3 py-2 text-sm text-white bg-sky-800 hover:bg-sky-900 disabled:opacity-60 disabled:cursor-not-allowed" @click="saveAdd">Save</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import SideMenu from '../../components/layout/SideMenu.vue';
import TopBar from '../../components/layout/TopBar.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref, reactive, watch, nextTick } from 'vue';
import { getTimeFormData, getCompanyStaff, createTime, getTimes, updateTime, type TimeListItem } from '../../api/time.api';

interface Row { id: number; name: string; company: string; in: string; out: string; break: string; hours: string; status: string }
interface Section { date: string; label: string; rows: Row[] }

const sections = ref<Section[]>([]);

const addOpen = ref(false);
const workplaces = ref<Array<{ id: number; name: string }>>([]);
const today = new Date().toISOString().slice(0, 10);
const form = reactive({
  company_id: '' as number | '',
  date: today,
  start_time: '',
  end_time: '',
  benefit: '',
  comment: '',
  user_id: '' as number | '',
});

const errors = reactive<Record<string, string>>({});
const saving = ref(false);
const editing = reactive<{ id: number | null; field: 'start' | 'end' | null; value: string | '' }>({ id: null, field: null, value: '' });
const originalEditValue = ref<string>('');
const editPicker = ref<any>(null);
function setEditRef(rowId: number, field: 'start' | 'end', el: any) {
  if (rowId === editing.id && field === editing.field) {
    editPicker.value = el;
  }
}

function openAdd() {
  addOpen.value = true;
  getTimeFormData()
    .then((data: any) => {
      workplaces.value = data?.workplaces || [];
      if (!form.company_id && workplaces.value.length) form.company_id = workplaces.value[0].id;
      if (data?.defaults?.date) form.date = String(data.defaults.date);
    })
    .catch(() => {});
}

function formatSectionLabel(d: string) {
  const dt = new Date(d + 'T00:00:00');
  return new Intl.DateTimeFormat(undefined, { weekday: 'long', month: 'long', day: 'numeric' }).format(dt);
}

function computeHours(start: string | null, end: string | null) {
  if (!start || !end) return '-';
  const [sh, sm] = start.split(':').map(Number);
  const [eh, em] = end.split(':').map(Number);
  const startMin = sh * 60 + sm;
  const endMin = eh * 60 + em;
  const diff = endMin - startMin;
  if (diff <= 0) return '-';
  const h = Math.floor(diff / 60);
  const m = diff % 60;
  const hp = h === 1 ? 'hour' : 'hours';
  const mp = m === 1 ? 'minute' : 'minutes';
  if (h && m) return `${h} ${hp} ${m} ${mp}`;
  if (h) return `${h} ${hp}`;
  return `${m} ${mp}`;
}

function nameOf(u: { first_name: string; last_name: string }) {
  return `${u.first_name ?? ''} ${u.last_name ?? ''}`.trim();
}

async function loadTimes() {
  try {
    const { times } = await getTimes();
    const byDate = new Map<string, Row[]>();
    for (const t of times as TimeListItem[]) {
      const date = t.date;
      const rows = byDate.get(date) ?? [];
      rows.push({
        id: t.id,
        name: nameOf(t.user),
        company: t.company?.name ?? '',
        in: t.start_time || '-',
        out: t.end_time || '-',
        break: '-',
        hours: computeHours(t.start_time, t.end_time),
        status: t.status,
      });
      byDate.set(date, rows);
    }
    sections.value = Array.from(byDate.entries())
      .sort((a, b) => (a[0] < b[0] ? 1 : -1))
      .map(([date, rows]) => ({ date, label: formatSectionLabel(date), rows }));
  } catch (e) {
    console.error('Failed to load times', e);
  }
}

const staff = ref<Array<{ id: number; first_name: string; last_name: string }>>([]);
watch(
  () => form.company_id,
  async (val) => {
    if (!val) { staff.value = []; form.user_id = '' as any; return; }
    try {
      const data: any = await getCompanyStaff(Number(val));
      staff.value = data?.staff || [];
      if (!form.user_id && staff.value.length) form.user_id = staff.value[0].id;
    } catch {}
  }
);
function closeAdd() {
  addOpen.value = false;
}
async function saveAdd() {
  if (saving.value) return;
  // client-side validation
  Object.keys(errors).forEach(k => delete (errors as any)[k]);
  const req: Array<[keyof typeof form, string]> = [
    ['company_id', 'Workplace is required'],
    ['user_id', 'Staff is required'],
    ['date', 'Date is required'],
    ['start_time', 'Start time is required'],
    ['comment', 'Comment is required'],
  ];
  for (const [key, msg] of req) {
    const val = (form as any)[key];
    if (val === '' || val === undefined || val === null) {
      (errors as any)[key] = msg;
    }
  }
  if (form.start_time && form.end_time && form.end_time <= form.start_time) {
    errors.end_time = 'End time must be later than start time.';
  }
  if (Object.keys(errors).length) return;

  saving.value = true;
  try {
    const coords = await getCoords();
    const payload = {
      company_id: Number(form.company_id),
      user_id: Number(form.user_id),
      date: form.date,
      start_time: form.start_time,
      end_time: form.end_time || undefined,
      benefit: form.benefit || undefined,
      comment: form.comment,
      latitude: coords?.latitude ?? undefined,
      longitude: coords?.longitude ?? undefined,
    };
    await createTime(payload as any);
    await loadTimes();
    resetModalState();
    closeAdd();
  } catch (e: any) {
    const data = e?.response?.data;
    const fieldErrors = data?.errors as Record<string, string[] | string> | undefined;
    if (fieldErrors) {
      for (const [k, v] of Object.entries(fieldErrors)) {
        (errors as any)[k] = Array.isArray(v) ? (v[0] || '') : String(v);
      }
    }
  } finally {
    saving.value = false;
  }
}

// Initial load of times list
loadTimes();

function resetModalState() {
  form.company_id = '' as any;
  form.date = new Date().toISOString().slice(0, 10);
  form.start_time = '';
  form.end_time = '';
  form.benefit = '';
  form.comment = '';
  form.user_id = '' as any;
  staff.value = [];
  Object.keys(errors).forEach(k => delete (errors as any)[k]);
}

function getCoords(): Promise<{ latitude: number; longitude: number } | null> {
  return new Promise((resolve) => {
    if (!('geolocation' in navigator)) return resolve(null);
    navigator.geolocation.getCurrentPosition(
      (pos) => resolve({ latitude: pos.coords.latitude, longitude: pos.coords.longitude }),
      () => resolve(null),
      { enableHighAccuracy: false, timeout: 5000, maximumAge: 60000 }
    );
  });
}

// Using @vuepic/vue-datepicker for 24h time selection

function isEditing(id: number, field: 'start' | 'end') {
  return editing.id === id && editing.field === field;
}
function startEdit(id: number, field: 'start' | 'end', current: string) {
  editing.id = id;
  editing.field = field;
  editing.value = current && current !== '-' ? current : '';
  originalEditValue.value = editing.value;
  nextTick(() => {
    try {
      const inst: any = editPicker.value;
      if (inst && typeof inst.openMenu === 'function') inst.openMenu();
      else if (inst?.$el) {
        const input = inst.$el.querySelector('input');
        if (input) (input as HTMLInputElement).focus();
      }
    } catch {}
  });
}
function cancelEdit() {
  editing.id = null;
  editing.field = null;
  editing.value = '';
}
async function saveEdit(id: number) {
  if (!editing.field) return;
  try {
    const payload: any = {};
    if (editing.field === 'start') payload.start_time = editing.value || null;
    if (editing.field === 'end') payload.end_time = editing.value || null;
    await updateTime(id, payload);
    cancelEdit();
    await loadTimes();
  } catch (e: any) {
    const errs = e?.response?.data?.errors;
    const msg = errs ? (Object.values(errs).flat() as any[]).join('\n') : (e?.response?.data?.message || 'Failed to update');
    alert(String(msg));
  }
}

// Auto-save on apply if value changed
watch(() => editing.value, (val) => {
  if (editing.id && val !== originalEditValue.value) {
    // Fire and forget; saveEdit handles state cleanup
    saveEdit(editing.id);
  }
});
</script>

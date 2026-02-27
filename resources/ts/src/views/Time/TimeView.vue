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
          <div class="flex">
            <select class="w-40 rounded-l-md border border-gray-300 bg-white px-3 py-2 text-sm">
              <option>All statuses</option>
            </select>
            <input class="flex-1 rounded-r-md border border-l-0 border-gray-300 bg-white px-3 py-2 text-sm" placeholder="Search staff..." />
          </div>
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
                  <div class="text-xs text-gray-500">Braingeneers</div>
                </div>
              </div>
              <div class="col-span-4 md:col-span-1 text-sm text-gray-700">{{ row.in }}</div>
              <div class="col-span-4 md:col-span-1 text-sm text-gray-700">{{ row.out }}</div>
              <div class="col-span-4 md:col-span-1 text-sm text-gray-700">{{ row.break }}</div>
              <div class="col-span-6 md:col-span-2 text-sm text-gray-700">{{ row.hours }}</div>
              <div class="col-span-3 md:col-span-1 flex gap-1">
                <span class="material-symbols-outlined text-sky-700 text-base">chat</span>
                <span class="material-symbols-outlined text-sky-700 text-base">note</span>
              </div>
              <div class="col-span-3 md:col-span-1">
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 text-xs px-2 py-1">
                  <span class="material-symbols-outlined text-sm">check_circle</span> Completed
                </span>
              </div>
              <div class="col-span-3 md:col-span-1 flex gap-1 justify-end md:justify-start">
                <button class="h-8 w-8 inline-flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200">
                  <span class="material-symbols-outlined text-gray-700">more_horiz</span>
                </button>
              </div>
            </div>
          </template>

          <div class="px-4 py-3 text-xs text-gray-600">2 hours 29 minutes</div>
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
            <input type="date" v-model="form.date" class="w-full rounded-md border border-gray-300 px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">Workplace</label>
            <select v-model="form.company_id" class="w-full rounded-md border border-gray-300 px-3 py-2">
              <option v-for="wp in workplaces" :key="wp.id" :value="wp.id">{{ wp.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">Staff</label>
            <select v-model="form.user_id" class="w-full rounded-md border border-gray-300 px-3 py-2">
              <option v-for="u in staff" :key="u.id" :value="u.id">{{ u.first_name }} {{ u.last_name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1">Benefit</label>
            <input type="text" placeholder="e.g. Regular" v-model="form.benefit" class="w-full rounded-md border border-gray-300 px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1" @click="openTime('start')">Start</label>
            <div class="w-full" @click="openTime('start')">
              <input ref="startRef" type="time" v-model="form.start_time" class="w-full cursor-pointer rounded-md border border-gray-300 px-3 py-2" />
            </div>
          </div>
          <div>
            <label class="block text-sm text-gray-700 mb-1" @click="openTime('end')">End</label>
            <div class="w-full" @click="openTime('end')">
              <input ref="endRef" type="time" v-model="form.end_time" class="w-full cursor-pointer rounded-md border border-gray-300 px-3 py-2" />
            </div>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-1">Comment</label>
            <textarea rows="3" v-model="form.comment" class="w-full rounded-md border border-gray-300 px-3 py-2"></textarea>
          </div>
        </div>
      </div>
      <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
        <button class="rounded-md px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200" @click="closeAdd">Cancel</button>
        <button class="rounded-md px-3 py-2 text-sm text-white bg-sky-800 hover:bg-sky-900" @click="saveAdd">Save</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import SideMenu from '../../components/layout/SideMenu.vue';
import TopBar from '../../components/layout/TopBar.vue';
import { ref, reactive } from 'vue';
import { getTimeFormData } from '../../api/time.api';
import { watch } from 'vue';

interface Row { id: number; name: string; in: string; out: string; break: string; hours: string }
interface Section { date: string; label: string; rows: Row[] }

const sections = ref<Section[]>([
  {
    date: '2026-02-26',
    label: 'Thursday, February 26',
    rows: [
      { id: 1, name: 'Marko Gligorijevic', in: '16:20', out: '18:24', break: '-', hours: '2 hours 4 minutes' },
      { id: 2, name: 'Marko Gligorijevic', in: '16:25', out: '18:24', break: '-', hours: '1 hour 59 minutes' },
    ],
  },
  {
    date: '2026-02-25',
    label: 'Wednesday, February 25',
    rows: [
      { id: 3, name: 'Marko Gligorijevic', in: '17:58', out: '18:23', break: '-', hours: '0 hours 25 minutes' },
    ],
  },
]);

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

const staff = ref<Array<{ id: number; first_name: string; last_name: string }>>([]);
watch(
  () => form.company_id,
  async (val) => {
    if (!val) { staff.value = []; form.user_id = '' as any; return; }
    try {
      const data: any = await getTimeFormData(Number(val));
      staff.value = data?.staff || [];
      if (!form.user_id && staff.value.length) form.user_id = staff.value[0].id;
    } catch {}
  }
);
function closeAdd() {
  addOpen.value = false;
}
function saveAdd() {
  // Placeholder for API call — just log and close
  console.log('Add time', { ...form });
  closeAdd();
}

const startRef = ref<HTMLInputElement | null>(null);
const endRef = ref<HTMLInputElement | null>(null);
function openTime(which: 'start' | 'end') {
  const el = which === 'start' ? startRef.value : endRef.value;
  if (!el) return;
  try {
    // @ts-ignore - showPicker may not exist in TS lib
    if (typeof (el as any).showPicker === 'function') (el as any).showPicker();
    else el.focus();
  } catch {
    el.focus();
  }
}
</script>

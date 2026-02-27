<template>
  <div class="min-h-screen flex">
    <!-- Left navigation -->
    <SideMenu />

    <!-- Main content -->
    <div class="flex-1 min-w-0">
      <TopBar />

      <!-- Page banner -->
      <div class="w-full bg-sky-900 text-white">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-4 sm:py-8 text-center">
          <h1 class="text-xl sm:text-4xl font-semibold sm:font-bold">
            <span class="hidden sm:inline">Welcome, </span>{{ fullName }}
          </h1>
        </div>
      </div>

      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-4 flex items-center justify-between">
          <h2 class="text-xl font-semibold">Profile</h2>
          <button
            class="inline-flex items-center gap-2 rounded-md bg-sky-800 hover:bg-sky-900 text-white px-4 py-2 text-sm disabled:opacity-60 disabled:cursor-not-allowed"
            @click="handleSave"
            :disabled="saving"
          >
            <span class="material-symbols-outlined text-base">save</span>
            Save
          </button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Form -->
          <div class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-lg shadow border">
              <h3 class="px-6 py-4 font-medium border-b">Account</h3>
              <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">First name</label>
                  <input v-model="form.first_name" type="text" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Last name</label>
                  <input v-model="form.last_name" type="text" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                  <input v-model="form.email" type="email" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                  <input v-model="form.password" type="password" autocomplete="new-password" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mobile number</label>
                  <input v-model="form.phone" type="text" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                </div>
              </div>
            </section>

            <section class="bg-white rounded-lg shadow border">
              <h3 class="px-6 py-4 font-medium border-b">Security settings</h3>
              <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Logout after inactivity</label>
                  <select class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option>8 hours</option>
                    <option>4 hours</option>
                    <option>1 hour</option>
                  </select>
                </div>
                <div class="flex items-center justify-center self-center">
                  <label class="inline-flex items-center gap-2">
                    <input type="checkbox" class="rounded border-gray-300 text-sky-600 focus:ring-sky-600" />
                    <span class="text-sm text-gray-700">Disable password login</span>
                  </label>
                </div>
              </div>
            </section>
          </div>

          <!-- Profile card -->
          <aside class="bg-white rounded-lg shadow border h-fit p-6">
            <div class="flex flex-col items-center text-center">
              <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-semibold text-gray-600">
                {{ initials }}
              </div>
              <div class="mt-3">
                <p class="font-medium">{{ fullName }}</p>
                <p class="text-sm text-gray-500">{{ form.email }}</p>
              </div>
              <div class="mt-6 w-full space-y-2">
                <RouterLink to="/profile" class="block w-full px-4 py-2 rounded-md bg-gray-100 text-gray-700 text-sm">
                  Profile
                </RouterLink>
                <RouterLink to="/time" class="block w-full px-4 py-2 rounded-md hover:bg-gray-100 text-gray-700 text-sm">
                  My companies
                </RouterLink>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import TopBar from '../../components/layout/TopBar.vue';
import SideMenu from '../../components/layout/SideMenu.vue';
import { computed, reactive, ref } from 'vue';
import { useAuth } from '../../composables/useAuth';
import { updateMeApi } from '../../api/auth.api';
import { RouterLink } from 'vue-router';

const { state } = useAuth();

const user = computed(() => (state.user ?? {}) as Record<string, any>);
const fullName = computed(() => `${user.value.first_name ?? ''} ${user.value.last_name ?? ''}`.trim() || 'User');

const form = reactive({
  first_name: (user.value.first_name as string) || '',
  last_name: (user.value.last_name as string) || '',
  email: (user.value.email as string) || '',
  phone: (user.value.phone as string) || '',
  password: '',
});



const initials = computed(() => {
  const a = (form.first_name || '').trim()[0] || '';
  const b = (form.last_name || '').trim()[0] || '';
  return (a + b).toUpperCase() || 'U';
});

const saving = ref(false);
async function handleSave() {
  if (saving.value) return;
  saving.value = true;
  try {
    const payload: any = {
      first_name: form.first_name,
      last_name: form.last_name,
      email: form.email,
      phone: form.phone || undefined,
    };
    if (form.password && form.password.trim().length) {
      payload.password = form.password;
    }
    const data = await updateMeApi(payload);
    state.user = (data as any)?.user ?? state.user;
    form.password = '';
  } catch (e: any) {
    console.error('Failed to save profile', e?.response?.data || e);
  } finally {
    saving.value = false;
  }
}
</script>

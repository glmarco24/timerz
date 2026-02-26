<template>
  <div class="flex min-h-screen flex-col items-center pt-12">
    <div class="w-full max-w-xl px-4">

      <div class="flex justify-center mb-8">
        <h1 class="text-2xl font-semibold text-blue-700">timetjek</h1>
      </div>

      <h2 class="text-2xl font-semibold">Log in</h2>

      <div class="mt-4">
        <LoginForm @submit="handleSubmit" />
        <p v-if="authError" class="mt-3 text-sm text-red-600">{{ authError }}</p>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from '../../composables/useAuth';
import LoginForm from '../../components/auth/LoginForm.vue';
import { useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';

const { login, state } = useAuth();
const route = useRoute();
const router = useRouter();
const authError = computed(() => state.error);

async function handleSubmit(payload: { personal_number: string; password: string }) {
  try {
    await login(payload);
    const q = route.query?.redirect;
    const redirect = typeof q === 'string' ? q : undefined;
    if (redirect && redirect.startsWith('/')) {
      router.replace(redirect);
    } else {
      router.replace({ name: 'home' });
    }
  } catch {
    // TODO: handle wrong credentials
  }
}
</script>

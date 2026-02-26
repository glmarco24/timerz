<template>
  <div class="flex min-h-screen flex-col items-center pt-12">
    <div class="w-full max-w-xl px-4">

      <div class="flex justify-center mb-8">
        <h1 class="text-2xl font-semibold text-blue-700">timetjek</h1>
      </div>

      <h2 class="text-2xl font-semibold">Log in</h2>

      <div class="mt-4">
        <LoginForm @submit="handleSubmit" />
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from '../../composables/useAuth';
import LoginForm from '../../components/auth/LoginForm.vue';

const { login } = useAuth();

async function handleSubmit(payload: { personal_number: string; password: string }) {
  try {
    await login(payload);
    window.location.href = '/';
  } catch {
      //TODO handle this error, wrong credentials
  }
}
</script>

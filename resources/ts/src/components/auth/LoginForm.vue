<template>
  <form @submit.prevent="onSubmit" class="space-y-3">
    <div>
      <label for="personal_number" class="sr-only">Personal number</label>
      <input
        id="personal_number"
        v-model.trim="personal_number"
        type="text"
        inputmode="numeric"
        autocomplete="username"
        placeholder="Personal number"
        class="w-full rounded-md border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>
    <div>
      <label for="password" class="sr-only">Password</label>
      <input
        id="password"
        v-model="password"
        type="password"
        autocomplete="current-password"
        placeholder="Password"
        class="w-full rounded-md border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <button
      type="submit"
      :disabled="submitting"
      class="w-full rounded-full bg-blue-600 px-4 py-3 text-white font-medium hover:bg-blue-700 disabled:opacity-60"
    >
      Log in
    </button>

    <button
      type="button"
      class="w-full rounded-full bg-gray-100 px-4 py-3 text-gray-700 font-medium hover:bg-gray-200"
      @click="$emit('forgot')"
    >
      Forgot password?
    </button>

    <p v-if="error" class="text-sm text-red-600 pt-1">{{ error }}</p>
  </form>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const emit = defineEmits<{ (e: 'submit', payload: { personal_number: string; password: string }): void; (e: 'forgot'): void }>();

const personal_number = ref('');
const password = ref('');
const submitting = ref(false);
const error = ref('');

watch([personal_number, password], () => (error.value = ''));

function onSubmit() {
  if (!personal_number.value || !password.value) {
    error.value = 'Both fields are required';
    return;
  }
  submitting.value = true;
  emit('submit', { personal_number: personal_number.value, password: password.value });
  setTimeout(() => (submitting.value = false), 0);
}
</script>


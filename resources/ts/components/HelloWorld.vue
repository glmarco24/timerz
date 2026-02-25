<template>
  <div class="p-4 rounded border border-gray-200 dark:border-gray-700">
    <h1 class="text-xl font-semibold">Hello {{ message }}</h1>
    <p class="text-gray-600 dark:text-gray-300 mt-2">
      Vue 3 + TypeScript is mounted and working.
    </p>
  </div>

</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const message = ref('...')

onMounted(async () => {
  try {
    const { data } = await axios.get<{ message: string }>('/api/ping')
    message.value = data.message
  } catch {
    message.value = 'error'
  }
})
</script>

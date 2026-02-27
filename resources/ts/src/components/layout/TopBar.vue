<template>
  <header class="w-full bg-white text-sky-800 border-b border-gray-200">
    <div class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 flex items-center justify-between">
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="h-8 w-8 rounded-full bg-sky-100 flex items-center justify-center">
          <span class="text-sky-800 text-lg">✓</span>
        </div>
        <div class="text-2xl sm:text-3xl font-bold leading-none">Timetjek</div>
        <div v-if="title">
          <h1 class="text-lg sm:text-xl font-semibold leading-tight">{{ title }}</h1>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
defineProps<{ title?: string; subtitle?: string }>();
</script>

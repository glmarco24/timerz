<template>
  <header class="w-full bg-sky-800 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex items-center justify-between">
      <div>
        <h1 class="text-lg sm:text-xl font-semibold leading-tight">{{ title }}</h1>
        <p v-if="subtitle" class="hidden md:block text-sky-100 text-xs mt-1">{{ subtitle }}</p>
      </div>
      <div class="hidden sm:flex items-center gap-2">
        <slot name="actions" />
      </div>
    </div>
  </header>
  <div class="hidden md:block h-1 bg-sky-900"></div>
  <div class="hidden md:block h-1 bg-sky-700"></div>
</template>

<script setup lang="ts">
defineProps<{ title: string; subtitle?: string }>();
</script>

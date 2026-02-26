<template>
  <aside class="w-16 md:w-56 bg-sky-900 text-sky-100 min-h-screen py-4 md:py-6">
    <div class="px-3 md:px-6 mb-6 flex items-center gap-2 md:gap-3">
      <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
        <span class="text-white text-lg">✓</span>
      </div>
      <span class="hidden md:inline font-semibold">timetjek</span>
    </div>

    <nav class="px-1 md:px-2">
      <RouterLink
        v-for="item in items"
        :key="item.to"
        :to="item.to"
        class="flex items-center gap-3 md:gap-3 px-3 md:px-4 py-3 rounded-md hover:bg-sky-800 mb-1"
        :class="{ 'bg-sky-800 text-white': isActive(item.to) }"
        :title="item.label"
      >
        <span v-if="item.icon" class="material-symbols-outlined text-xl">{{ item.icon }}</span>
        <span class="hidden md:inline">{{ item.label }}</span>
      </RouterLink>
    </nav>
  </aside>
</template>

<script setup lang="ts">
import { useRoute, RouterLink } from 'vue-router';

interface Item { label: string; to: string; icon?: string }
defineProps<{ items: Item[] }>();

const route = useRoute();
function isActive(path: string) {
  return route.path === path;
}
</script>

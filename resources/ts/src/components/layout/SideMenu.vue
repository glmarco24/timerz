<template>
  <aside class="w-16 bg-sky-900 text-sky-100 min-h-screen pb-4 md:pb-6 flex flex-col">

    <nav class="px-1">
      <RouterLink
        v-for="item in topItems"
        :key="item.to"
        :to="item.to"
        class="flex items-center justify-center px-3 py-3 rounded-md hover:bg-sky-800 mb-1"
        :class="{ 'bg-sky-800 text-white': isActive(item.to) }"
        :title="item.label"
      >
        <span v-if="item.icon" class="material-symbols-outlined text-xl md:text-3xl">{{ item.icon }}</span>
      </RouterLink>
    </nav>
    
    <div class="mt-auto px-1">
      <nav>
        <RouterLink
          v-for="item in bottomItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center justify-center px-3 py-3 rounded-md hover:bg-sky-800 mb-1"
          :class="{ 'bg-sky-800 text-white': isActive(item.to) }"
          :title="item.label"
        >
          <span v-if="item.icon" class="material-symbols-outlined text-xl md:text-3xl">{{ item.icon }}</span>
        </RouterLink>
      </nav>
      <button
        type="button"
        @click="onLogout"
        title="Logout"
        class="w-full flex items-center justify-center px-3 py-3 rounded-md hover:bg-sky-800"
      >
        <span class="material-symbols-outlined text-xl md:text-3xl">logout</span>
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, useRouter, RouterLink } from 'vue-router';
import { useAuth } from '../../composables/useAuth';
import { useMenu } from '../../composables/useMenu';

interface Item { label: string; to: string; icon?: string; bottom?: boolean }
const props = defineProps<{ items?: Item[] }>();

const route = useRoute();
const router = useRouter();
const { logout } = useAuth();
function isActive(path: string) {
  return route.path === path;
}

const defaultMenu = useMenu();
const resolvedItems = computed<Item[]>(() => (props.items && props.items.length ? props.items : defaultMenu));
const topItems = computed(() => resolvedItems.value.filter(i => !i.bottom));
const bottomItems = computed(() => resolvedItems.value.filter(i => i.bottom));

async function onLogout() {
  try {
    await logout();
  } finally {
    router.push({ name: 'login' });
  }
}
</script>

<template>
  <div class="min-h-screen flex">
    <SideMenu />

    <div class="flex-1 min-w-0">
      <TopBar />

      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center py-16">
          <h1 class="text-4xl sm:text-5xl font-bold">Welcome to Timetjek</h1>
          <p class="mt-2 text-sm text-gray-500">(This page does absolutely nothing)</p>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
import SideMenu from '../components/layout/SideMenu.vue';
import TopBar from '../components/layout/TopBar.vue';
</script>

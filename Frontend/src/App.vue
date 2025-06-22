<template>
  <header>
    <Navigation />
  </header>

  <main class="mt-16">
    <router-view />
  </main>
</template>

<script setup>
import Navigation from "@/components/Navigation.vue";
</script>

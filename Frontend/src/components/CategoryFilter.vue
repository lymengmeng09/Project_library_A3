<!-- src/components/CategoryFilter.vue -->
<template>
    <div class="flex flex-wrap justify-center mt-6 gap-2">
      <button
        v-for="cat in categories"
        :key="cat"
        @click="$emit('update:selectedCategory', cat)"
        :class="[
          'px-4 py-2 rounded-full text-sm font-medium',
          selectedCategory === cat ? 'bg-black text-white' : 'bg-gray-100 text-black hover:bg-gray-200'
        ]"
      >
        {{ cat }}
      </button>
    </div>
  </template>
  
  <script setup>
  defineProps({
    categories: Array,
    selectedCategory: String
  })
  
  defineEmits(['update:selectedCategory'])
  </script>
  
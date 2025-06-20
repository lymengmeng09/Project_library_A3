<!-- src/components/Search.vue -->
<template>
      <div class="flex justify-center mt-6 w-2xl">
            <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" type="text"
                  placeholder="Search by title or ID..."
                  class="border border-gray-300 rounded-full px-6 py-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>
</template>

<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
    

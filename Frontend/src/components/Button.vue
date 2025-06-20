<!-- src/components/Button.vue -->
<template>
    <div class="flex justify-end mt-4 mr-12">
        <button @click="$emit('toggle')"
            class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-full transition duration-300">
            {{ showForm ? 'Close Form' : '+ Add Book' }}
        </button>
    </div>
</template>

<script setup>
defineProps({ showForm: Boolean })
defineEmits(['toggle'])
</script>
  


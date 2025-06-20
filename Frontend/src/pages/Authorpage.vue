<template>
  <div class="w-11/12 mx-auto mt-8">
    <h1 class="text-3xl font-bold text-center">Author Page</h1>
    <p class="text-center mt-4 text-gray-600">This is the author page where you can find information about authors.</p>

    <!-- 🔍 Search + Range Filter -->
    <div class="flex flex-wrap gap-4 justify-center items-center mt-8">
      <!-- ✅ Use Search with v-model -->
      <Search v-model="searchName" />

      <input v-model.number="minBooks" type="number" placeholder="Min books"
        class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500" />

      <input v-model.number="maxBooks" type="number" placeholder="Max books"
        class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
    </div>

    <!-- 📋 Author List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-10">
      <div v-for="author in filteredAuthors" :key="author.name"
        class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h2 class="text-xl font-semibold">{{ author.name }}</h2>
        <p class="text-gray-600 text-sm">📅 DOB: {{ author.dob }}</p>
        <p class="text-gray-600 text-sm">📚 Books: {{ author.books }}</p>
        <p class="text-gray-600 text-sm">🌍 Nationality: {{ author.nationality }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Search from '@/components/Search.vue'

const authors = ref([
  { name: 'J.K. Rowling', dob: '1965-07-31', books: 15, nationality: 'British' },
  { name: 'George R.R. Martin', dob: '1948-09-20', books: 10, nationality: 'American' },
  { name: 'Haruki Murakami', dob: '1949-01-12', books: 20, nationality: 'Japanese' },
  { name: 'Agatha Christie', dob: '1890-09-15', books: 85, nationality: 'British' },
  { name: 'Dan Brown', dob: '1964-06-22', books: 7, nationality: 'American' }
])

const searchName = ref('')
const minBooks = ref(0)
const maxBooks = ref(100)

const filteredAuthors = computed(() => {
  return authors.value.filter(author => {
    const matchesName = author.name.toLowerCase().includes(searchName.value.toLowerCase())
    const withinRange = author.books >= minBooks.value && author.books <= maxBooks.value
    return matchesName && withinRange
  })
})
</script>

<template>
  <div class="w-11/12 mx-auto"> <!-- avoid overlap with navbar -->
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Author Page</h1>

      <!-- Search Bar -->
      <Search v-model="searchName" class="flex-shrink-0" />

      <!-- Range Filter -->
      <div class="flex gap-4 justify-center items-center flex-wrap mt-4 md:mt-0">
        <input
          v-model.number="minBooks"
          type="number"
          min="0"
          placeholder="Min books"
          class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        />
        <input
          v-model.number="maxBooks"
          type="number"
          min="0"
          placeholder="Max books"
          class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        />
      </div>
    </div>

    <hr class="border-gray-300 my-6" />

    <!-- Add Author Button -->
    <Button :showForm="showForm" @toggle="showForm = !showForm" />

    <!-- Add Author Form -->
    <div v-if="showForm" class="max-w-md mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">Add New Author</h2>
      <form @submit.prevent="addAuthor">
        <input
          v-model="newAuthor.name"
          type="text"
          placeholder="Name"
          class="w-full px-3 py-2 mb-3 rounded border"
          required
        />
        <input
          v-model="newAuthor.dob"
          type="date"
          placeholder="Date of Birth"
          class="w-full px-3 py-2 mb-3 rounded border"
          required
        />
        <input
          v-model.number="newAuthor.written_book"
          type="number"
          min="0"
          placeholder="Books Written"
          class="w-full px-3 py-2 mb-3 rounded border"
          required
        />
        <input
          v-model="newAuthor.nationality"
          type="text"
          placeholder="Nationality"
          class="w-full px-3 py-2 mb-4 rounded border"
          required
        />
        <button
          type="submit"
          class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition"
        >
          Add Author
        </button>
      </form>
    </div>

    <!-- Loading and Error States -->
    <p v-if="isLoading" class="text-center mt-10">Loading authors...</p>
    <p v-if="error" class="text-center text-red-600 mt-10">{{ error }}</p>

    <!-- Author List -->
    <div
      v-if="filteredAuthors.length && !isLoading && !error"
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-10"
    >
      <div
        v-for="author in filteredAuthors"
        :key="author.id"
        class="bg-white p-4 rounded-lg shadow hover:shadow-md transition"
      >
        <h2 class="text-xl font-semibold">{{ author.name }}</h2>
        <p class="text-gray-600 text-sm">📅 DOB: {{ author.dob || 'N/A' }}</p>
        <p class="text-gray-600 text-sm">📚 Books: {{ author.written_book }}</p>
        <p class="text-gray-600 text-sm">🌍 Nationality: {{ author.nationality }}</p>
      </div>
    </div>

    <p v-else-if="!isLoading && !error" class="text-center text-gray-500 mt-10">
      No authors found matching your criteria.
    </p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const authors = ref([])
const isLoading = ref(false)
const error = ref(null)

const searchName = ref('')
const minBooks = ref(0)
const maxBooks = ref(100)
const showForm = ref(false)

const newAuthor = ref({
  name: '',
  dob: '',
  written_book: 0,
  nationality: '',
})

onMounted(async () => {
  isLoading.value = true
  error.value = null
  try {
    const res = await axios.get('http://192.168.108.11:8000/api/authors')
    console.log('API response:', res.data)

    // Use the "data" array from your API response
    authors.value = Array.isArray(res.data.data) ? res.data.data : []
  } catch (err) {
    console.error('Failed to fetch authors:', err)
    error.value = err.message || 'Failed to load authors'
  } finally {
    isLoading.value = false
  }
})

const filteredAuthors = computed(() => {
  return authors.value.filter((author) => {
    const matchesName = author.name.toLowerCase().includes(searchName.value.toLowerCase())
    const withinRange = author.written_book >= minBooks.value && author.written_book <= maxBooks.value
    return matchesName && withinRange
  })
})
// Change POST URL (remove /create)
const addAuthor = async () => {
  const a = newAuthor.value

  if (
    a.name.trim() &&
    a.dob &&
    a.written_book >= 0 &&
    a.nationality.trim()
  ) {
    try {
      const response = await axios.post('http://192.168.108.11:8000/api/authors/create', a)
      authors.value.push(response.data)
      newAuthor.value = {
        name: '',
        dob: '',
        written_book: 0,
        nationality: '',
      }
      showForm.value = false
    } catch (err) {
      alert('Failed to add author: ' + (err.response?.data?.message || err.message))
    }
  } else {
    alert('Please fill all fields correctly.')
  }
}


</script>

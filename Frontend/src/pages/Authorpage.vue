<template>
  <div class="w-11/12 mx-auto "> <!-- avoid overlap with navbar -->
    <div class="flex justify-between items-center   flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Author Page</h1>

      <!-- Search Bar -->
      <Search v-model="searchName" class="flex-shrink-0" />

      <!-- Range Filter -->
      <div class="flex gap-4 justify-center items-center flex-wrap mt-4 md:mt-0">
        <input v-model.number="minBooks" type="number" min="0" placeholder="Min books"
          class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
        <input v-model.number="maxBooks" type="number" min="0" placeholder="Max books"
          class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>
    </div>

    <hr class="border-gray-300 my-6" />


    <!-- Add Author Button -->
    <Button :showForm="showForm" @toggle="showForm = !showForm" />

    <!-- Add Author Form -->
    <div v-if="showForm" class="max-w-md mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">Add New Author</h2>
      <form @submit.prevent="addAuthor">
        <input v-model="newAuthor.name" type="text" placeholder="Name" class="w-full px-3 py-2 mb-3 rounded border"
          required />
        <input v-model="newAuthor.dob" type="date" placeholder="Date of Birth"
          class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model.number="newAuthor.books" type="number" min="0" placeholder="Books Written"
          class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="newAuthor.nationality" type="text" placeholder="Nationality"
          class="w-full px-3 py-2 mb-4 rounded border" required />
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
          Add Author
        </button>
      </form>
    </div>

    <!-- Author List -->
    <!-- Author List -->
    <div v-if="filteredAuthors.length"
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-10">
      <div v-for="author in filteredAuthors" :key="author.name + author.dob"
        class="bg-white p-4 rounded-lg shadow hover:shadow-md transition flex flex-col justify-between">
        <div>
          <h2 class="text-xl font-semibold">{{ author.name }}</h2>
          <p class="text-gray-600 text-sm">📅 DOB: {{ author.dob }}</p>
          <p class="text-gray-600 text-sm">📚 Books: {{ author.books }}</p>
          <p class="text-gray-600 text-sm">🌍 Nationality: {{ author.nationality }}</p>
        </div>

        <div class="flex gap-2 mt-3 justify-end">
          <button @click="editAuthor(author)"
            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</button>
          <button @click="deleteAuthor(author.id)"
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
          <button @click="showDetails(author)"
            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">Details</button>
        </div>
      </div>
    </div>


    <p v-else class="text-center text-gray-500 mt-10">No authors found matching your criteria.</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const authors = ref([])
const isLoading = ref(true)

const searchName = ref('')
const minBooks = ref(0)
const maxBooks = ref(100)
const showForm = ref(false)

// New author form model
const newAuthor = ref({
  name: '',
  dob: '',
  books: 0,
  nationality: '',
})

// Fetch authors from API on mount
onMounted(() => {
  fetch('http://127.0.0.1:8080/api/authors')
    .then(res => res.json())
    .then(data => {
      authors.value = data // adjust if API returns { data: [...] }
    })
    .catch(err => {
      console.error('Error fetching authors:', err)
    })
    .finally(() => {
      isLoading.value = false
    })
})

// Filter authors by search and book count range
const filteredAuthors = computed(() => {
  return authors.value.filter(author => {
    const matchesName = author.name.toLowerCase().includes(searchName.value.toLowerCase())
    const withinRange = author.books >= minBooks.value && author.books <= maxBooks.value
    return matchesName && withinRange
  })
})

// Add new author locally (can be adapted to POST API call)
const addAuthor = () => {
  const a = newAuthor.value

  if (
    a.name.trim() &&
    a.dob &&
    a.books >= 0 &&
    a.nationality.trim()
  ) {
    // Add new author to the list
    authors.value.push({ ...a })

    // Clear form
    newAuthor.value = {
      name: '',
      dob: '',
      books: 0,
      nationality: '',
    }

    // Close form
    showForm.value = false
  } else {
    alert('Please fill all fields correctly.')
  }
}
</script>

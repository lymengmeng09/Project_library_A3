<template>
  <div class="w-11/12 mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Author Page</h1>
      <Search v-model="searchName" class="flex-shrink-0" />
      <!-- Filter -->
      <div class="flex gap-4 justify-center items-center flex-wrap mt-4 md:mt-0">
        <input v-model.number="minBooks" type="number" min="0" placeholder="Min books"
          class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
        <input v-model.number="maxBooks" type="number" min="0" placeholder="Max books"
          class="border border-gray-300 rounded-lg px-4 py-2 w-28 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
      </div>
    </div>

    <hr class="border-gray-300 my-6" />

    <!-- Add/Edit Toggle Button -->
    <Button :showForm="showForm" @toggle="showForm = !showForm" />

    <!-- Form -->
    <div v-if="showForm" class="max-w-md mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Author' : 'Add New Author' }}</h2>
      <form @submit.prevent="addAuthor">
        <input v-model="newAuthor.name" type="text" placeholder="Name" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="newAuthor.dob" type="date" placeholder="Date of Birth" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model.number="newAuthor.written_book" type="number" min="0" placeholder="Books Written"
          class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="newAuthor.nationality" type="text" placeholder="Nationality"
          class="w-full px-3 py-2 mb-4 rounded border" required />
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
          {{ isEditing ? 'Update Author' : 'Add Author' }}
        </button>
      </form>
    </div>

    <!-- Loading/Error -->
    <div v-if="isLoading" class="text-center text-gray-500 mt-10">Loading authors...</div>
    <div v-if="error" class="text-center text-red-500 mt-4">{{ error }}</div>

    <!-- Author List -->
    <template v-if="filteredAuthors.length">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-10">
        <div v-for="author in filteredAuthors" :key="author.id"
          class="bg-white p-4 rounded-lg shadow hover:shadow-md transition flex flex-col justify-between">
          <div>
            <h2 class="text-xl font-semibold">{{ author.name }}</h2>
            <p class="text-gray-600 text-sm">📅 DOB: {{ author.dob }}</p>
            <p class="text-gray-600 text-sm">📚 Books: {{ author.written_book }}</p>
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
    </template>
    <template v-else>
      <p class="text-center text-gray-500 mt-10">No authors found matching your criteria.</p>
    </template>

    <!-- Detail View -->
    <div v-if="detailAuthor"
      class="max-w-md mx-auto mt-10 bg-white border border-gray-300 p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">Author Details</h2>
      <p><strong>Name:</strong> {{ detailAuthor.name }}</p>
      <p><strong>DOB:</strong> {{ detailAuthor.dob }}</p>
      <p><strong>Books Written:</strong> {{ detailAuthor.written_book }}</p>
      <p><strong>Nationality:</strong> {{ detailAuthor.nationality }}</p>
      <button @click="detailAuthor = null" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Close</button>
    </div>
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
const isEditing = ref(false)
const selectedAuthorId = ref(null)
const detailAuthor = ref(null)

const newAuthor = ref({
  name: '',
  dob: '',
  written_book: 0,
  nationality: '',
})

// Load authors
onMounted(async () => {
  isLoading.value = true
  error.value = null
  try {
    const res = await axios.get('http://192.168.108.11:8000/api/authors')
    authors.value = Array.isArray(res.data.data) ? res.data.data : []
  } catch (err) {
    console.error('Fetch error:', err)
    error.value = err.message || 'Failed to load authors'
  } finally {
    isLoading.value = false
  }
})

// Filtered authors
const filteredAuthors = computed(() => {
  return authors.value.filter((author) => {
    const matchesName = author.name.toLowerCase().includes(searchName.value.toLowerCase())
    const withinRange = author.written_book >= minBooks.value && author.written_book <= maxBooks.value
    return matchesName && withinRange
  })
})

// Add or update
const addAuthor = async () => {
  const a = newAuthor.value
  if (!a.name || !a.dob || a.written_book < 0 || !a.nationality) {
    alert('Please fill all fields correctly.')
    return
  }

  try {
    if (isEditing.value) {
      await axios.put(`http://192.168.108.11:8000/api/authors/edit/${selectedAuthorId.value}`, a)
      const index = authors.value.findIndex(a => a.id === selectedAuthorId.value)
      if (index !== -1) authors.value[index] = { ...a, id: selectedAuthorId.value }
    } else {
      const res = await axios.post('http://192.168.108.11:8000/api/authors/create', a)
      authors.value.push(res.data.data)
    }
    resetForm()
  } catch (err) {
    alert('Error: ' + (err.response?.data?.message || err.message))
  }
}

// Delete
const deleteAuthor = async (id) => {
  if (!confirm('Are you sure you want to delete this author?')) return
  try {
    await axios.delete(`http://192.168.108.11:8000/api/authors/delete/${id}`)
    authors.value = authors.value.filter(a => a.id !== id)
  } catch (err) {
    alert('Failed to delete author: ' + (err.response?.data?.message || err.message))
  }
}

// Show details
const showDetails = (author) => {
  detailAuthor.value = author
}

// Edit
const editAuthor = (author) => {
  newAuthor.value = { ...author }
  showForm.value = true
  isEditing.value = true
  selectedAuthorId.value = author.id
}

// Reset
const resetForm = () => {
  newAuthor.value = { name: '', dob: '', written_book: 0, nationality: '' }
  showForm.value = false
  isEditing.value = false
  selectedAuthorId.value = null
}
</script>

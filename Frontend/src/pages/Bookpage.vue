<template>
  <div class="w-11/12 mx-auto">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Book Page</h1>
      <Search v-model="searchQuery" />
      <Category :categories="categories" v-model:selectedCategory="selectedCategory" />
    </div>

    <hr class="border-gray-300 my-4" />

    <!-- Book Form -->
    <div v-if="showForm" class="w-full md:w-1/2 mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Book' : 'Add New Book' }}</h2>
      <form @submit.prevent="saveBook">
        <input v-model="newBook.title" type="text" placeholder="Title" class="w-full px-3 py-2 mb-2 rounded border" required />
        <input v-model="newBook.author" type="text" placeholder="Author" class="w-full px-3 py-2 mb-2 rounded border" required />
        <input v-model="newBook.published_date" type="date" class="w-full px-3 py-2 mb-2 rounded border" required />
        <input v-model="newBook.ISBN" type="text" placeholder="ISBN" class="w-full px-3 py-2 mb-2 rounded border" required />
        <input v-model="newBook.image" type="text" placeholder="Image URL (optional)" class="w-full px-3 py-2 mb-2 rounded border" />
        <select v-model="newBook.category" class="w-full px-3 py-2 mb-2 rounded border" required>
          <option disabled value="">Select Category</option>
          <option v-for="cat in categories" :key="cat" v-if="cat !== 'All'">{{ cat }}</option>
        </select>
        <input v-model.number="newBook.publication_year" type="number" placeholder="Publication Year" class="w-full px-3 py-2 mb-2 rounded border" required min="0" />
        <input v-model.number="newBook.available_copies" type="number" placeholder="Available Copies" class="w-full px-3 py-2 mb-2 rounded border" required min="1" />
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
          {{ isEditing ? 'Update Book' : 'Add Book' }}
        </button>
      </form>
    </div>

    <!-- Toggle Form Button -->
    <Button :showForm="showForm" @toggle="toggleForm" />

    <!-- Loading -->
    <div v-if="isLoading" class="text-center text-gray-500 mt-10">Loading books...</div>

    <!-- Book List -->
    <div v-else class="flex justify-center mt-8">
      <div v-if="filteredBooks.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
        <div v-for="book in filteredBooks" :key="book.id" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
          <h2 class="text-xl font-semibold mb-1">{{ book.title }}</h2>
          <p class="text-sm text-gray-600">Author: {{ book.author }}</p>
          <p class="text-sm text-gray-600">ISBN: {{ book.ISBN }}</p>
          <p class="text-sm text-gray-600">Published Date: {{ book.published_date }}</p>
          <p class="text-sm text-gray-600">Publication Year: {{ book.publication_year }}</p>
          <p class="text-sm text-gray-600">Available Copies: {{ book.available_copies }}</p>
          <p class="text-sm text-gray-500">Category: {{ book.category }}</p>
          <div class="flex justify-between mt-3">
            <button @click="editBook(book)" class="text-blue-500 hover:underline">Edit</button>
            <button @click="deleteBook(book.id)" class="text-red-500 hover:underline">Delete</button>
          </div>
        </div>
      </div>
      <p v-else class="text-center text-gray-500 mt-10">No books found matching your criteria.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'
import Category from '@/components/CategoryFilter.vue'

// State
const books = ref([])
const searchQuery = ref('')
const selectedCategory = ref('All')
const isLoading = ref(true)
const showForm = ref(false)
const isEditing = ref(false)
const editingBookId = ref(null)

const categories = ['All', 'Fiction', 'Science Fiction', 'Mystery', 'Programming']

const newBook = ref({
  title: '',
  author: '',
  published_date: '',
  ISBN: '',
  image: '',
  category: '',
  publication_year: '',
  available_copies: ''
})

// Fetch books
const fetchBooks = () => {
  isLoading.value = true
  let url = 'http://192.168.108.11:8000/api/books'
  if (searchQuery.value) {
    url = `http://192.168.108.11:8000/api/search?query=${encodeURIComponent(searchQuery.value)}`
  }

  fetch(url)
    .then(res => res.json())
    .then(data => {
      books.value = data
    })
    .catch(err => console.error('Error fetching books:', err))
    .finally(() => {
      isLoading.value = false
    })
}

// Save (create or update)
const saveBook = () => {
  const b = newBook.value
  const method = isEditing.value ? 'PUT' : 'POST'
  const url = isEditing.value
    ? `http://192.168.108.11:8000/api/books/${editingBookId.value}`
    : 'http://192.168.108.11:8000/api/books'

  fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify(b)
  })
    .then(res => {
      if (!res.ok) return res.json().then(err => { throw err })
      return res.json()
    })
    .then(data => {
      if (isEditing.value) {
        const index = books.value.findIndex(book => book.id === editingBookId.value)
        books.value[index] = data
        alert('Book updated!')
      } else {
        books.value.push(data)
        alert('Book added!')
      }
      resetForm()
    })
    .catch(err => {
      console.error('Save failed:', err)
      alert('Failed to save book. See console.')
    })
}

// Edit
const editBook = (book) => {
  newBook.value = { ...book }
  showForm.value = true
  isEditing.value = true
  editingBookId.value = book.id
}

// Delete
const deleteBook = (id) => {
  if (!confirm('Are you sure?')) return
  fetch(`http://192.168.108.11:8000/api/books/${id}`, {
    method: 'DELETE',
    headers: { Accept: 'application/json' }
  })
    .then(res => {
      if (!res.ok) throw new Error('Delete failed')
      books.value = books.value.filter(book => book.id !== id)
      alert('Book deleted!')
    })
    .catch(err => {
      console.error('Delete failed:', err)
      alert('Failed to delete book.')
    })
}

// Toggle form
const toggleForm = () => {
  showForm.value = !showForm.value
  if (!showForm.value) resetForm()
}

// Reset form
const resetForm = () => {
  newBook.value = {
    title: '', author: '', published_date: '', ISBN: '', image: '',
    category: '', publication_year: '', available_copies: ''
  }
  isEditing.value = false
  editingBookId.value = null
  showForm.value = false
}

// Filter books
const filteredBooks = computed(() => {
  return books.value.filter(book => {
    const matchCategory = selectedCategory.value === 'All' || book.category === selectedCategory.value
    return matchCategory
  })
})

// On mounted and watch
onMounted(() => {
  fetchBooks()
})
watch(searchQuery, fetchBooks)
</script>

<template>
    <div class="w-11/12 mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h1 class="text-3xl font-bold text-left mt-5 ml-5">Book Page</h1>
            <Search v-model="searchQuery" />
            <Category :categories="categories" v-model:selectedCategory="selectedCategory" />
        </div>

        <hr class="border-gray-300 my-4" />

        <!-- Add/Edit Book Form -->
        <div v-if="showForm" class="w-full md:w-1/2 mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Book' : 'Add New Book' }}</h2>
            <form @submit.prevent="saveBook">
                <input v-model="newBook.title" type="text" placeholder="Title"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.author" type="text" placeholder="Author"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.published_date" type="date" class="w-full px-3 py-2 mb-2 rounded border"
                    required />
                <input v-model="newBook.ISBN" type="text" placeholder="ISBN"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.image" type="text" placeholder="Image URL (optional)"
                    class="w-full px-3 py-2 mb-2 rounded border" />
                <select v-model="newBook.category" class="w-full px-3 py-2 mb-2 rounded border" required>
                    <option disabled value="">Select Category</option>
                    <option v-for="cat in categories" :key="cat" v-if="cat !== 'All'">{{ cat }}</option>
                </select>
                <input v-model.number="newBook.publication_year" type="number" placeholder="Publication Year"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model.number="newBook.available_copies" type="number" placeholder="Available Copies"
                    class="w-full px-3 py-2 mb-2 rounded border" required min="1" />
                <div class="flex gap-2 mt-4">
                    <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">{{ isEditing ?
                            'Update Book' : 'Add Book' }}</button>
                    <button type="button" @click="cancelEdit"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 transition">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Add Book Button -->
        <Button :showForm="showForm" @toggle="toggleForm" />

        <!-- Loading State -->
        <div v-if="isLoading" class="text-center text-gray-500 mt-10">Loading books...</div>

        <!-- Book List -->
        <div v-else class="flex justify-center mt-8">
            <div v-if="filteredBooks.length"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
                <div v-for="book in filteredBooks" :key="book.id"
                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div
                        class="w-64 bg-white shadow-[0px_0px_15px_rgba(0,0,0,0.09)] p-9 space-y-3 relative overflow-hidden">
                        <div class="w-24 h-24 bg-violet-500 rounded-full absolute -right-5 -top-7">
                            <p class="absolute bottom-6 left-7 text-white text-2xl">02</p>
                        </div>
                        <div class="fill-violet-500 w-12">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m24,6.928v13.072h-11.5v3h5v1H6.5v-1h5v-3H0V4.5c0-1.379,1.122-2.5,2.5-2.5h12.98c-.253.295-.54.631-.856,1H2.5c-.827,0-1.5.673-1.5,1.5v14.5h22v-10.993l1-1.079Zm-12.749,3.094C19.058.891,19.093.855,19.11.838c1.118-1.115,2.936-1.113,4.052.002,1.114,1.117,1.114,2.936,0,4.052l-8.185,8.828c-.116,1.826-1.623,3.281-3.478,3.281h-5.59l.097-.582c.043-.257,1.086-6.16,5.244-6.396Zm2.749,3.478c0-1.379-1.122-2.5-2.5-2.5-2.834,0-4.018,3.569-4.378,5h4.378c1.378,0,2.5-1.121,2.5-2.5Zm.814-1.073l2.066-2.229c-.332-1.186-1.371-2.057-2.606-2.172-.641.749-1.261,1.475-1.817,2.125,1.117.321,1.998,1.176,2.357,2.277Zm.208-5.276c1.162.313,2.125,1.134,2.617,2.229l4.803-5.18c.737-.741.737-1.925.012-2.653-.724-.725-1.908-.727-2.637,0-.069.08-2.435,2.846-4.795,5.606Z" />
                            </svg>
                        </div>
                        <h1 class="font-bold text-xl">{{ book.title || 'Untitled' }}</h1>
                        <p class="text-sm text-zinc-500 leading-6">Author: {{ book.author || 'Unknown' }}</p>
                        <p class="text-sm text-zinc-500 leading-6">ISBN: {{ book.ISBN || 'N/A' }}</p>                                
                        <p class="text-sm text-zinc-500 leading-6">Publication Year: {{ book.publication_year || 'N/A'
                        }}</p>
                        <p class="text-sm text-zinc-500 leading-6">Available Copies: {{ book.available_copies || 0 }}
                        </p>
                        <p class="text-sm text-zinc-500 leading-6">Category: {{ book.category || 'None' }}</p>
                    </div>
                    <div class="flex gap-2 mt-3 justify-end">
                        <button @click="editBook(book)"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</button>
                        <button @click="deleteBook(book.id)"
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                        <button @click="showDetails(book)"
                            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">Details</button>
                    </div>
                </div>
            </div>
            <p v-else class="text-center text-gray-500 mt-10">No books found matching your criteria.</p>
        </div>
    </div>

</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Category from '@/components/CategoryFilter.vue'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

// States
const searchQuery = ref('')
const selectedCategory = ref('All')
const showForm = ref(false)
const isEditing = ref(false)
const editingBookId = ref(null)
const isLoading = ref(true)
const books = ref([])
const categories = ['All', 'Fiction', 'Science Fiction', 'Mystery', 'Programming']

const newBook = ref({
  id: '',
  title: '',
  author: '',
  published_date: '',
  ISBN: '',
  image: '',
  category: '',
  publication_year: '',
  available_copies: ''
})

// Lifecycle
onMounted(() => {
  fetchBooks()
})

// Fetch books
function fetchBooks() {
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
    .catch(err => console.error('Error fetching:', err))
    .finally(() => {
      isLoading.value = false
    })
}

// Save (Add or Edit)
function saveBook() {
  const method = isEditing.value ? 'PUT' : 'POST'
  const url = isEditing.value
    ? `http://192.168.108.11:8000/api/books/${editingBookId.value}`
    : 'http://192.168.108.11:8000/api/books'

  fetch(url, {
    method,
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json'
    },
    body: JSON.stringify(newBook.value)
  })
    .then(res => res.json())
    .then(data => {
      if (isEditing.value) {
        const index = books.value.findIndex(b => b.id === data.id)
        if (index !== -1) books.value[index] = data
      } else {
        books.value.push(data)
      }
      resetForm()
      showForm.value = false
    })
    .catch(err => {
      console.error('Error saving:', err)
      alert('Save failed')
    })
}

// Delete
function deleteBook(id) {
  if (!confirm('Are you sure?')) return
  fetch(`http://192.168.108.11:8000/api/books/${id}`, {
    method: 'DELETE'
  })
    .then(res => {
      if (!res.ok) throw new Error()
      books.value = books.value.filter(b => b.id !== id)
    })
    .catch(err => {
      console.error('Delete failed:', err)
      alert('Could not delete')
    })
}

// Edit
function editBook(book) {
  newBook.value = { ...book }
  editingBookId.value = book.id
  isEditing.value = true
  showForm.value = true
}

// Show details
function showDetails(book) {
  alert(`
    Title: ${book.title}
    Author: ${book.author}
    ISBN: ${book.ISBN}
    Published: ${book.published_date}
    Category: ${book.category}
    Year: ${book.publication_year}
    Available: ${book.available_copies}
  `)
}

// Cancel edit
function cancelEdit() {
  resetForm()
  showForm.value = false
  isEditing.value = false
  editingBookId.value = null
}

// Reset form
function resetForm() {
  newBook.value = {
    id: '',
    title: '',
    author: '',
    published_date: '',
    ISBN: '',
    image: '',
    category: '',
    publication_year: '',
    available_copies: ''
  }
}

// Filtered books
const filteredBooks = computed(() => {
  return books.value.filter(book => {
    const matchesCategory = selectedCategory.value === 'All' || book.category === selectedCategory.value
    return matchesCategory
  })
})
</script>


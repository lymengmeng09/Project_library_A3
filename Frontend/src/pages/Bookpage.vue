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
                <input v-model="newBook.title" type="text" placeholder="Title" class="border p-2 rounded w-full mb-3"
                    required />
                <input v-model="newBook.author" type="text" placeholder="Author" class="border p-2 rounded w-full mb-3"
                    required />
                <input v-model="newBook.published_date" type="date" class="border p-2 rounded w-full mb-3" required />
                <input v-model="newBook.ISBN" type="text" placeholder="ISBN" class="border p-2 rounded w-full mb-3"
                    required />
                <input v-model="newBook.image" type="text" placeholder="Image URL"
                    class="border p-2 rounded w-full mb-3" />
                <select v-model="newBook.category" class="border p-2 rounded w-full mb-3" required>
                    <option disabled value="">Select Category</option>
                    <option v-for="cat in filteredCategories" :key="cat">{{ cat }}</option>
                </select>
                <input v-model.number="newBook.publication_year" type="number" placeholder="Publication Year"
                    class="border p-2 rounded w-full mb-3" required />
                <input v-model.number="newBook.available_copies" type="number" placeholder="Available Copies"
                    class="border p-2 rounded w-full mb-3" required min="1" />

                <div class="flex gap-2 mt-4">
                    <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                        {{ isEditing ? 'Update Book' : 'Add Book' }}
                    </button>
                    <button type="button" @click="cancelEdit"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 transition">
                        Cancel
                    </button>
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
                            <p class="absolute bottom-6 left-7 text-white text-2xl">📚</p>
                        </div>
                        <div v-if="book.image" class="w-full h-32 overflow-hidden rounded">
                            <img :src="book.image" alt="Book cover" class="w-full h-full object-cover" />
                        </div>
                        <h1 class="font-bold text-xl">{{ book.title || 'Untitled' }}</h1>
                        
                        <p class="text-sm text-zinc-500 leading-6">ID: {{ book.id }}</p>
                        <p class="text-sm text-zinc-500 leading-6">Author: {{ book.author || 'Unknown' }}</p>
                        <p class="text-sm text-zinc-500 leading-6">ISBN: {{ book.ISBN || 'N/A' }}</p>
                        <p class="text-sm text-zinc-500 leading-6">
                            Publication Year: {{ book.publication_year || 'N/A' }}
                        </p>
                        <p class="text-sm text-zinc-500 leading-6">Available Copies: {{ book.available_copies || 0 }}
                        </p>
                        <p class="text-sm text-zinc-500 leading-6">Category: {{ book.category || 'None' }}</p>
                    </div>
                    <div class="flex gap-2 mt-3 justify-end">
                        <button @click="editBook(book)"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Edit
                        </button>
                        <button @click="deleteBook(book.id)"
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            Delete
                        </button>
                        <button @click="showDetails(book)"
                            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Details
                        </button>
                    </div>
                </div>
            </div>
            <p v-else class="text-center text-gray-500 mt-10">No books found matching your criteria.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Category from '@/components/CategoryFilter.vue'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const searchQuery = ref('')
const selectedCategory = ref('All')
const showForm = ref(false)
const isEditing = ref(false)
const editingBookId = ref(null)
const isLoading = ref(true)
const books = ref([])
const categories = ['All', 'Fiction', 'Science Fiction', 'Mystery', 'Programming']

const filteredCategories = computed(() => categories.filter(cat => cat !== 'All'))

// Fetch books from backend API
onMounted(async () => {
    try {
        const res = await axios.get('http://127.0.0.1:8000/api/books')
        books.value = res.data.data // assuming your API returns { message, data: [...] }
    } catch (error) {
        console.error('Failed to load books:', error)
        alert('Failed to load books data.')
    } finally {
        isLoading.value = false
    }
})

// Book form data model
const newBook = ref({
    id: '',
    title: '',
    author: '',
    published_date: '',
    ISBN: '',
    image: '',
    category: '',
    publication_year: null,
    available_copies: null,
})

// Show/hide form toggle
function toggleForm() {
    showForm.value = !showForm.value
    if (!showForm.value) {
        resetForm()
        isEditing.value = false
        editingBookId.value = null
    }
}

// Reset form fields
function resetForm() {
    newBook.value = {
        id: '',
        title: '',
        author: '',
        published_date: '',
        ISBN: '',
        image: '',
        category: '',
        publication_year: null,
        available_copies: null,
    }
}

// Save book (create or update)
async function saveBook() {
    try {
        // Normalize image to string to avoid backend validation errors
        if (!newBook.value.image) newBook.value.image = ''

        // Make sure published_date is formatted YYYY-MM-DD for <input type="date">
        if (newBook.value.published_date && newBook.value.published_date.length > 10) {
            newBook.value.published_date = newBook.value.published_date.slice(0, 10)
        }

        if (isEditing.value) {
            const res = await axios.put(`http://127.0.0.1:8000/api/books/update/${editingBookId.value}`, newBook.value)
            const index = books.value.findIndex(b => b.id === editingBookId.value)
            if (index !== -1) {
                books.value[index] = res.data.data
            }
            alert('Book updated successfully.')
        } else {
            const res = await axios.post('http://127.0.0.1:8000/api/books/store', newBook.value)
            books.value.push(res.data.data)
            alert('Book added successfully.')
        }
        resetForm()
        showForm.value = false
        isEditing.value = false
        editingBookId.value = null
    } catch (error) {
        console.error('Failed to save book:', error.response || error)
        alert('Failed to save book.')
    }
}

// Delete a book by ID
async function deleteBook(id) {
    if (!confirm('Are you sure you want to delete this book?')) return
    try {
        await axios.delete(`http://127.0.0.1:8000/api/books/destroy/${id}`)
        books.value = books.value.filter(book => book.id !== id)
        alert('Book deleted successfully.')
    } catch (error) {
        console.error('Failed to delete book:', error)
        alert('Failed to delete book.')
    }
}

// Open edit form and fill with book data
function editBook(book) {
    newBook.value = { ...book }

    // Format date for date input
    if (book.published_date && book.published_date.length > 10) {
        newBook.value.published_date = book.published_date.slice(0, 10)
    }

    editingBookId.value = book.id
    isEditing.value = true
    showForm.value = true
}

// Show book details alert (optional)
function showDetails(book) {
    alert(`
      Title: ${book.title}
      Author: ${book.author}
      ISBN: ${book.ISBN}
      Published: ${book.published_date}
      Category: ${book.category}
      Year: ${book.publication_year}
      Available Copies: ${book.available_copies}
    `)
}

// Cancel edit & reset form
function cancelEdit() {
    resetForm()
    showForm.value = false
    isEditing.value = false
    editingBookId.value = null
}

// Computed filtered list by search and category
const filteredBooks = computed(() => {
    return (books.value || []).filter(book => {
        const matchesCategory = selectedCategory.value === 'All' || book.category === selectedCategory.value
        const searchLower = searchQuery.value.toLowerCase()
        const matchesSearch =
            book.title?.toLowerCase().includes(searchLower) ||
            book.ISBN?.toLowerCase().includes(searchLower) ||
            String(book.id).includes(searchLower)
        return matchesCategory && matchesSearch
    })
})

</script>
  

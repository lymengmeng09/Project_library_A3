<template>
    <div class="w-11/12 mx-auto "> <!-- spacing for sticky navbar -->
        <div class="flex justify-between items-center  flex-wrap gap-4">
            <h1 class="text-3xl font-bold text-left mt-5 ml-5">Book Page</h1>

            <!-- Search Bar -->
            <Search v-model="searchQuery" />

            <!-- Category Filter -->
            <Category :categories="categories" v-model:selectedCategory="selectedCategory" />
        </div>

        <hr class="border-gray-300 my-4" />

        <!-- Add Book Form -->
        <div v-if="showForm" class="w-full md:w-1/2 mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Add New Book</h2>
            <form @submit.prevent="addBook">
                <input v-model="newBook.id" type="text" placeholder="ID" class="w-full px-3 py-2 mb-2 rounded border"
                    required />
                <input v-model="newBook.isbn" type="text" placeholder="ISBN"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.title" type="text" placeholder="Title"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model.number="newBook.year" type="number" placeholder="Year"
                    class="w-full px-3 py-2 mb-2 rounded border" required min="0" />
                <input v-model.number="newBook.number" type="number" placeholder="Number"
                    class="w-full px-3 py-2 mb-2 rounded border" required min="1" />
                <select v-model="newBook.category" class="w-full px-3 py-2 mb-4 rounded border" required>
                    <option disabled value="">Select Category</option>
                    <option v-for="cat in categories" :key="cat" v-if="cat !== 'All'">{{ cat }}</option>
                </select>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    Add Book
                </button>
            </form>
        </div>

        <!-- Add Book Button -->
        <Button :showForm="showForm" @toggle="showForm = !showForm" />

        <!-- Loading State -->
        <div v-if="isLoading" class="text-center text-gray-500 mt-10">Loading books...</div>

        <!-- Book List -->
        <div v-else class="flex justify-center mt-8">
            <div v-if="filteredBooks.length"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
                <div v-for="book in filteredBooks" :key="book.id"
                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-xl font-semibold mb-1">{{ book.title }}</h2>
                    <p class="text-sm text-gray-600">ID: {{ book.id }}</p>
                    <p class="text-sm text-gray-600">ISBN: {{ book.isbn }}</p>
                    <p class="text-sm text-gray-600">Year: {{ book.year }}</p>
                    <p class="text-sm text-gray-600">Number: {{ book.number }}</p>
                    <p class="text-sm text-gray-500">Category: {{ book.category }}</p>
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

const searchQuery = ref('')
const selectedCategory = ref('All')
const showForm = ref(false)
const categories = ['All', 'Fiction', 'Non-Fiction', 'Science Fiction', 'Mystery']
const books = ref([])
const isLoading = ref(true)

onMounted(() => {
    fetch('http://127.0.0.1:8080/api/books')
        .then(res => res.json())
        .then(data => {
            books.value = data // or data.data if API wraps it
        })
        .catch(err => {
            console.error('Error fetching books:', err)
        })
        .finally(() => {
            isLoading.value = false
        })
})

const newBook = ref({
    id: '',
    isbn: '',
    title: '',
    year: null,
    number: null,
    category: ''
})

const addBook = () => {
    const b = newBook.value
    // Simple validation: check all fields filled (trim strings)
    if (
        b.id.trim() &&
        b.isbn.trim() &&
        b.title.trim() &&
        b.year !== null &&
        b.number !== null &&
        b.category.trim()
    ) {
        books.value.push({ ...b })
        // Reset form
        newBook.value = {
            id: '',
            isbn: '',
            title: '',
            year: null,
            number: null,
            category: ''
        }
        showForm.value = false
    } else {
        alert('Please fill in all fields.')
    }
}

const filteredBooks = computed(() => {
    const search = searchQuery.value.toLowerCase()
    return books.value.filter(book => {
        const matchesSearch =
            book.title.toLowerCase().includes(search) ||
            book.id.toLowerCase().includes(search)
        const matchesCategory =
            selectedCategory.value === 'All' || book.category === selectedCategory.value
        return matchesSearch && matchesCategory
    })
})
</script>
  

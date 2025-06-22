<template>
    <div class="w-11/12 mx-auto">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h1 class="text-3xl font-bold text-left mt-5 ml-5">Book Page</h1>
            <Search v-model="searchQuery" />
            <Category :categories="categories" v-model:selectedCategory="selectedCategory" />
        </div>

        <hr class="border-gray-300 my-4" />

        <!-- Add Book Form -->
        <div v-if="showForm" class="w-full md:w-1/2 mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Add New Book</h2>
            <form @submit.prevent="addBook">
                <input v-model="newBook.title" type="text" placeholder="Title"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.author" type="text" placeholder="Author"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.published_date" type="date" placeholder="Published Date"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.ISBN" type="text" placeholder="ISBN"
                    class="w-full px-3 py-2 mb-2 rounded border" required />
                <input v-model="newBook.image" type="text" placeholder="Image URL (optional)"
                    class="w-full px-3 py-2 mb-2 rounded border" />
                <select v-model="newBook.category" class="w-full px-3 py-2 mb-2 rounded border" required>
                    <option disabled value="">Select Category</option>
                    <option v-for="cat in categories" :key="cat" v-if="cat !== 'All'">{{ cat }}</option>
                </select>
                <input v-model.number="newBook.publication_year" type="number" placeholder="Publication Year"
                    class="w-full px-3 py-2 mb-2 rounded border" required min="0" />
                <input v-model.number="newBook.available_copies" type="number" placeholder="Available Copies"
                    class="w-full px-3 py-2 mb-2 rounded border" required min="1" />
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
                    <p class="text-sm text-gray-600">Author: {{ book.author }}</p>
                    <p class="text-sm text-gray-600">ISBN: {{ book.ISBN }}</p>
                    <p class="text-sm text-gray-600">Published Date: {{ book.published_date }}</p>
                    <p class="text-sm text-gray-600">Publication Year: {{ book.publication_year }}</p>
                    <p class="text-sm text-gray-600">Available Copies: {{ book.available_copies }}</p>
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

const categories = ['All', 'Fiction', 'Science Fiction', 'Mystery', 'Programming']

const books = ref([])
const isLoading = ref(true)

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

onMounted(() => {
    fetchBooks()
})

const fetchBooks = () => {
    isLoading.value = true
    let url = 'http://192.168.108.11:8000/api/books'
    if (searchQuery.value) {
        url = `http://192.168.108.11:8000/api/search?query=${encodeURIComponent(searchQuery.value)}`
    }

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            return response.json()
        })
        .then(data => {
            books.value = data
        })
        .catch(error => {
            console.error('Error fetching books:', error)
        })
        .finally(() => {
            isLoading.value = false
        })
}

const addBook = () => {
    const b = newBook.value

    if (
        b.title &&
        b.author &&
        b.published_date &&
        b.ISBN &&
        b.category &&
        b.publication_year &&
        b.available_copies
    ) {
        fetch('http://192.168.108.11:8000/api/books', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(b)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`)
                }
                return response.json()
            })
            .then(data => {
                books.value.push(data)
                newBook.value = {
                    title: '',
                    author: '',
                    published_date: '',
                    ISBN: '',
                    image: '',
                    category: '',
                    publication_year: '',
                    available_copies: ''
                }
                showForm.value = false
            })
            .catch(error => {
                console.error('Error adding book:', error)
                alert('Failed to add book. Please check the input data.')
            })
    } else {
        alert('Please fill all required fields')
    }
}

const filteredBooks = computed(() => {
    return books.value.filter(book => {
        const matchesCategory =
            selectedCategory.value === 'All' || book.category === selectedCategory.value
        return matchesCategory
    })
})

// Watch for changes in searchQuery to refetch books
import { watch } from 'vue'
watch(searchQuery, () => {
    fetchBooks()
});


</script>
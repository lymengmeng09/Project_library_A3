<template>
    <div>
        <h1 class="text-3xl font-bold text-center mt-10">Book Page</h1>
        <p class="text-center mt-4">This is the book page where you can find information about books.</p>

        <!-- 🔍 Search Bar -->
        <div class="flex justify-center mt-8">
            <input v-model="searchQuery" type="text" placeholder="Search by title or ID..."
                class="border border-gray-300 rounded-full px-6 py-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
        </div>

        <!-- 📚 Category Filter -->
        <div class="flex flex-wrap justify-center mt-6 gap-2">
            <button v-for="cat in categories" :key="cat" @click="selectedCategory = cat" :class="[
                'px-4 py-2 rounded-full text-sm font-medium',
                selectedCategory === cat ? 'bg-black text-white' : 'bg-gray-100 text-black hover:bg-gray-200'
            ]">
                {{ cat }}
            </button>
        </div>

        <!-- ➕ Add Book Button -->
        <div class="flex justify-center mt-6">
            <button @click="showForm = !showForm"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                {{ showForm ? 'Close Form' : '+ Add Book' }}
            </button>
        </div>

        <!-- 📋 Add Book Form -->
        <div v-if="showForm" class="w-1/2 mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Add New Book</h2>
            <form @submit.prevent="addBook">
                <div class="mb-2">
                    <input v-model="newBook.id" type="text" placeholder="ID" class="w-full px-3 py-2 rounded border" />
                </div>
                <div class="mb-2">
                    <input v-model="newBook.isBn" type="text" placeholder="ISBN"
                        class="w-full px-3 py-2 rounded border" />
                </div>
                <div class="mb-2">
                    <input v-model="newBook.title" type="text" placeholder="Title"
                        class="w-full px-3 py-2 rounded border" />
                </div>
                <div class="mb-2">
                    <input v-model="newBook.year" type="number" placeholder="Year"
                        class="w-full px-3 py-2 rounded border" />
                </div>
                <div class="mb-2">
                    <input v-model="newBook.number" type="number" placeholder="Number"
                        class="w-full px-3 py-2 rounded border" />
                </div>
                <div class="mb-4">
                    <select v-model="newBook.category" class="w-full px-3 py-2 rounded border">
                        <option disabled value="">Select Category</option>
                        <option v-for="cat in categories" :key="cat" v-if="cat !== 'All'">{{ cat }}</option>
                    </select>
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Add Book
                </button>
            </form>
        </div>

        <!-- 📘 Filtered Book List -->
        <div class="flex justify-center mt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
                <div v-for="book in filteredBooks" :key="book.id"
                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-xl font-semibold mb-1">{{ book.title }}</h2>
                    <p class="text-sm text-gray-600">ID: {{ book.id }}</p>
                    <p class="text-sm text-gray-600">ISBN: {{ book.isBn }}</p>
                    <p class="text-sm text-gray-600">Year: {{ book.year }}</p>
                    <p class="text-sm text-gray-600">Number: {{ book.number }}</p>
                    <p class="text-sm text-gray-500">Category: {{ book.category }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedCategory = ref('All')
const showForm = ref(false)

const books = ref([
    {
        id: 'B001',
        isBn: '978-1234567890',
        title: 'The Time Machine',
        year: 1895,
        number: 1,
        category: 'Science Fiction'
    },
    {
        id: 'B002',
        isBn: '978-9876543210',
        title: 'Atomic Habits',
        year: 2018,
        number: 2,
        category: 'Non-Fiction'
    }
])

const categories = ['All', 'Fiction', 'Non-Fiction', 'Science Fiction', 'Mystery']

const newBook = ref({
    id: '',
    isBn: '',
    title: '',
    year: '',
    number: '',
    category: ''
})

const addBook = () => {
    if (
        newBook.value.id &&
        newBook.value.isBn &&
        newBook.value.title &&
        newBook.value.year &&
        newBook.value.number &&
        newBook.value.category
    ) {
        books.value.push({ ...newBook.value })
        // Clear form
        newBook.value = {
            id: '',
            isBn: '',
            title: '',
            year: '',
            number: '',
            category: ''
        }
        showForm.value = false
    } else {
        alert('Please fill all fields')
    }
}

const filteredBooks = computed(() => {
    return books.value.filter(book => {
        const matchesSearch =
            book.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            book.id.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchesCategory =
            selectedCategory.value === 'All' || book.category === selectedCategory.value
        return matchesSearch && matchesCategory
    })
})
</script>
  

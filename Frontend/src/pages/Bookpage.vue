<template>
    <div class="w-11/12 mx-auto mt-8">
        <div class="flex justify-between items-center mt-6 mx-5">
            <h1 class="text-3xl font-bold text-left mt-5 ml-5 ">Book Page</h1>
            <!-- 🔍 Search Bar -->
            <Search v-model="searchQuery" />

            <!-- 📚 Category Filter -->
            <Category :categories="categories" v-model:selectedCategory="selectedCategory" />
        </div>
        <hr class="border-gray-300 my-4" />
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

        <!-- ➕ Add Book Button and Book List -->
        <div>
            <Button :showForm="showForm" @toggle="showForm = !showForm" />

            <!-- Book List -->
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
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Category from '@/components/CategoryFilter.vue'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const searchQuery = ref('')
const selectedCategory = ref('All')
const showForm = ref(false)

const categories = ['All', 'Fiction', 'Non-Fiction', 'Science Fiction', 'Mystery']

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

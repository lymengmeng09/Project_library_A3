<template>
  <div>
    <h1>Books</h1>
    <ul>
      <li v-for="book in books" :key="book.id">
        {{ book.title }} - {{ book.author }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const books = ref([])

onMounted(() => {
  fetch("http://127.0.0.1:8080/api/books")
    .then(response => response.json())
    .then(data => {
      books.value = data
    })
    .catch(error => {
      console.error("Error fetching books:", error)
    })
})
</script>

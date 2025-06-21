<script setup>
import HelloWorld from './components/HelloWorld.vue'
import TheWelcome from './components/TheWelcome.vue'
import { ref,onMounted } from 'vue';
import axios from 'axios';
// axios.js or in App.vue
axios.defaults.baseURL = 'http://192.168.108.196:8000/api';


  const books = ref([]);
    onMounted(async ()=>{
        {
        try{
            const res = await axios.get('http://192.168.108.11:8000/api/books')
            books.value = res.data.data
            console.log(res.data.data)
        } catch(e){
            console.log("fail")
        }
    }
    })

</script>

<template>
  <h1 v-for="book in books" :key="book.id">{{ book.title }}</h1>
</template>




<style scoped>
header {
  line-height: 1.5;
}



.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style>

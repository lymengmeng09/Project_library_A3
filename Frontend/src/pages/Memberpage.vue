<!-- research name -->
<template>
    <div class="flex flex-col items-center justify-center h-screen">
        <h1 class="text-3xl font-bold mb-4">Member Page</h1>
        <p class="text-lg mb-8">Welcome to the Member Page!</p>
        <router-link to="/" class="text-blue-500 hover:underline">Go back to Home</router-link>
    </div>
</template>
<script setup>
    import { ref,onMounted } from 'vue';
    import axios from 'axios';

    const members = ref([]);

    onMounted(async ()=>{
        {
        try{
            const res = await axios.get('http://192.168.108.11:8000/api/members')
            members.value = res.data.data
            console.log(res.data.data)
        } catch(e){
            console.log("fail")
        }
    }
    })
</script>
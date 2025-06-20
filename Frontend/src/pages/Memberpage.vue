<template>
  <div>
    <!-- 🔍 Search Bar -->
    <div>
      <h1 class="text-3xl font-bold text-left mt-5 ml-20">Member Page</h1>
      <Search v-model="searchQuery" />
    </div>

    <!-- 👤 Add Member Form -->
    <div v-if="showForm" class="w-1/2 mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">Add New Member</h2>
      <form @submit.prevent="addMember">
        <div class="mb-2">
          <input v-model="newMember.id" type="text" placeholder="ID" class="w-full px-3 py-2 rounded border" />
        </div>
        <div class="mb-2">
          <input v-model="newMember.firstName" type="text" placeholder="First Name" class="w-full px-3 py-2 rounded border" />
        </div>
        <div class="mb-2">
          <input v-model="newMember.lastName" type="text" placeholder="Last Name" class="w-full px-3 py-2 rounded border" />
        </div>
        <div class="mb-2">
          <input v-model="newMember.phone" type="text" placeholder="Phone Number" class="w-full px-3 py-2 rounded border" />
        </div>
        <div class="mb-4">
          <input v-model="newMember.address" type="text" placeholder="Address" class="w-full px-3 py-2 rounded border" />
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
          Add Member
        </button>
      </form>
    </div>

    <!-- ➕ Add Member Button and Member List -->
    <div>
      <Button :showForm="showForm" @toggle="showForm = !showForm" />

      <!-- Member List -->
      <div class="flex justify-center mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
          <div v-for="member in filteredMembers" :key="member.id"
            class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-xl font-semibold mb-1">{{ member.firstName }} {{ member.lastName }}</h2>
            <p class="text-sm text-gray-600">ID: {{ member.id }}</p>
            <p class="text-sm text-gray-600">Phone: {{ member.phone }}</p>
            <p class="text-sm text-gray-600">Address: {{ member.address }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const searchQuery = ref('')
const showForm = ref(false)

const members = ref([
  {
    id: 'M001',
    firstName: 'John',
    lastName: 'Doe',
    phone: '012345678',
    address: 'Phnom Penh'
  },
  {
    id: 'M002',
    firstName: 'Jane',
    lastName: 'Smith',
    phone: '098765432',
    address: 'Siem Reap'
  }
])

const newMember = ref({
  id: '',
  firstName: '',
  lastName: '',
  phone: '',
  address: ''
})

const addMember = () => {
  const m = newMember.value
  if (m.id && m.firstName && m.lastName && m.phone && m.address) {
    members.value.push({ ...m })
    newMember.value = { id: '', firstName: '', lastName: '', phone: '', address: '' }
    showForm.value = false
  } else {
    alert('Please fill all fields')
  }
}

const filteredMembers = computed(() => {
  return members.value.filter(member => {
    const search = searchQuery.value.toLowerCase()
    return (
      member.id.toLowerCase().includes(search) ||
      member.firstName.toLowerCase().includes(search) ||
      member.lastName.toLowerCase().includes(search)
    )
  })
})
</script>

<template>
  <div class="w-11/12 mx-auto ">
    <!-- Title and Search Bar -->
    <div class="flex justify-between items-center  flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Member Page</h1>
      <Search v-model="searchQuery" class="flex-shrink-0" />
    </div>
    <hr class="border-gray-300 my-4" />

    <!-- Add Member Form -->
    <div v-if="showForm" class="w-full max-w-md mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">Add New Member</h2>
      <form @submit.prevent="addMember" novalidate>
        <input v-model="newMember.id" type="text" placeholder="ID" class="w-full px-3 py-2 mb-3 rounded border"
          required />
        <input v-model="newMember.firstName" type="text" placeholder="First Name"
          class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="newMember.lastName" type="text" placeholder="Last Name"
          class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="newMember.phone" type="text" placeholder="Phone Number"
          class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="newMember.address" type="text" placeholder="Address"
          class="w-full px-3 py-2 mb-4 rounded border" required />
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
          Add Member
        </button>
      </form>
    </div>

    <!-- Add Member Button -->
    <div class="mt-6 mx-20">
      <Button :showForm="showForm" @toggle="showForm = !showForm" />
    </div>

    <!-- Member List -->
    <div class="flex justify-center mt-8">
      <div v-if="filteredMembers.length"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
        <div v-for="member in filteredMembers" :key="member.id"
          class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
          <h2 class="text-xl font-semibold mb-1">{{ member.firstName }} {{ member.lastName }}</h2>
          <p class="text-sm text-gray-600">ID: {{ member.id }}</p>
          <p class="text-sm text-gray-600">Phone: {{ member.phone }}</p>
          <p class="text-sm text-gray-600">Address: {{ member.address }}</p>
        </div>
      </div>

      <p v-else class="text-center text-gray-500 mt-10">No members found matching your search.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const members = ref([])
const searchQuery = ref('')
const showForm = ref(false)

const newMember = ref({
  id: '',
  firstName: '',
  lastName: '',
  phone: '',
  address: ''
})

onMounted(async () => {
  try {
    const res = await axios.get('http://192.168.108.11:8000/api/members')
    members.value = res.data.data
  } catch (error) {
    console.error('Failed to fetch members:', error)
  }
})

const addMember = async () => {
  const m = newMember.value
  // Validate inputs (trim spaces)
  if (
    m.id.trim() &&
    m.firstName.trim() &&
    m.lastName.trim() &&
    m.phone.trim() &&
    m.address.trim()
  ) {
    // Uncomment below to POST to your API instead of local push
    /*
    try {
      const response = await axios.post('http://192.168.108.11:8000/api/members', m)
      members.value.push(response.data.data)
    } catch (error) {
      alert('Failed to add member. Please try again.')
      console.error(error)
      return
    }
    */
    // Local push (for demo)
    members.value.push({ ...m })

    // Reset form and close
    newMember.value = { id: '', firstName: '', lastName: '', phone: '', address: '' }
    showForm.value = false
  } else {
    alert('Please fill all fields')
  }
}

const editMember = (member, index) => {
  newMember.value = { ...member }
  isEditing.value = true
  editingIndex.value = index
  showForm.value = true
}

const deleteMember = (index) => {
  if (confirm('Are you sure you want to delete this member?')) {
    const deletedMember = members.value[index]
    members.value.splice(index, 1)
    alert(`Member "${deletedMember.firstName} ${deletedMember.lastName}" has been deleted.`)
  }
}

const cancelEdit = () => {
  newMember.value = { id: '', firstName: '', lastName: '', phone: '', address: '' }
  isEditing.value = false
  editingIndex.value = null
  showForm.value = false
}

const filteredMembers = computed(() => {
  const search = searchQuery.value.toLowerCase()
  return members.value.filter(member => {
    return (
      member.id.toLowerCase().includes(search) ||
      member.firstName.toLowerCase().includes(search) ||
      member.lastName.toLowerCase().includes(search) ||
      member.phone.toLowerCase().includes(search) ||
      member.address.toLowerCase().includes(search)
    )
  })
})
</script>

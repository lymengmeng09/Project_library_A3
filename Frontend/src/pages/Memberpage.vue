<template>
  <div class="w-11/12 mx-auto">
    <!-- Title and Search Bar -->
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Member Page</h1>
      <Search v-model="searchQuery" class="flex-shrink-0" />
    </div>
    <hr class="border-gray-300 my-4" />

    <!-- Add/Edit Member Form -->
    <div v-if="showForm" class="w-full max-w-md mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit' : 'Add' }} Member</h2>
      <form @submit.prevent="submit" novalidate>
        <input v-model="formMember.id" type="text" placeholder="ID" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.firstName" type="text" placeholder="First Name" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.lastName" type="text" placeholder="Last Name" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.email" type="email" placeholder="Email" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.phone_number" type="text" placeholder="Phone Number" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.address" type="text" placeholder="Address" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.about" type="text" placeholder="About" class="w-full px-3 py-2 mb-4 rounded border" required />

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
          {{ isEditing ? 'Update Member' : 'Add Member' }}
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
          <p class="text-sm text-gray-600">Email: {{ member.email }}</p>
          <p class="text-sm text-gray-600">Phone: {{ member.phone_number }}</p>
          <p class="text-sm text-gray-600">Address: {{ member.address }}</p>
          <p class="text-sm text-gray-600">About: {{ member.about }}</p>
        </div>
      </div>
      <p v-else class="text-center text-gray-500 mt-10">No members found matching your search.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

// State variables
const members = ref([])
const searchQuery = ref('')
const showForm = ref(false)

const isEditing = ref(false)
const editId = ref(null)

const formMember = ref({
  id: '',
  firstName: '',
  lastName: '',
  email: '',
  phone_number: '',
  address: '',
  about: ''
})

// Fetch members from backend
onMounted(async () => {
  try {
    const res = await axios.get('http://192.168.108.11:8000/api/members')
    members.value = res.data.data
  } catch (error) {
    console.error('Failed to fetch members:', error)
  }
})

// Submit (create or update)
const submit = async () => {
  const url = isEditing.value
    ? `http://192.168.108.11:8000/api/members/update/${editId.value}`
    : `http://192.168.108.11:8000/api/members/create`

  const method = isEditing.value ? 'PUT' : 'POST'

  try {
    const res = await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formMember.value),
    })

    const saved = await res.json()

    if (res.ok) {
      if (isEditing.value) {
        const i = members.value.findIndex((m) => m.id === editId.value)
        if (i !== -1) members.value[i] = saved.data
      } else {
        members.value.push(saved.data)
      }
      cancelForm()
    } else {
      console.error('Failed to save member:', saved.message || saved)
      alert('Failed to save member.')
    }
  } catch (error) {
    console.error('Error saving member:', error)
    alert('Error occurred while saving member.')
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
      (member.firstName || '').toLowerCase().includes(search) ||
      (member.lastName || '').toLowerCase().includes(search) ||
      (member.email || '').toLowerCase().includes(search) ||
      (member.phone_number || '').toLowerCase().includes(search) ||
      (member.about || '').toLowerCase().includes(search) ||
      (member.address || '').toLowerCase().includes(search)
    )
  })
})
</script>

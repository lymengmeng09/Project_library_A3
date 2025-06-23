<template>
  <div class="w-11/12 mx-auto">
    <!-- Title and Search Bar -->
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-left mt-5 ml-5">Member Page</h1>
      <Search v-model="searchQuery" class="flex-shrink-0" />
    </div>
    <hr class="border-gray-300 my-4" />

    <!-- Add/Edit Form -->
    <div v-if="showForm" class="w-full max-w-md mx-auto mt-6 bg-gray-100 p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit' : 'Add' }} Member</h2>
      <form @submit.prevent="submit">
        <input v-model="formMember.firstName" type="text" placeholder="First Name" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.lastName" type="text" placeholder="Last Name" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.email" type="email" placeholder="Email" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.phone_number" type="text" placeholder="Phone Number" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.address" type="text" placeholder="Address" class="w-full px-3 py-2 mb-3 rounded border" required />
        <input v-model="formMember.about" type="text" placeholder="About" class="w-full px-3 py-2 mb-4 rounded border" required />
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
          {{ isEditing ? 'Update' : 'Add' }} Member
        </button>
      </form>
    </div>

    <!-- Toggle Form Button -->
    <div class="mt-6 mx-20">
      <Button :showForm="showForm" @toggle="showForm = !showForm" />
    </div>

    <!-- Member List -->
    <div class="flex justify-center mt-8">
      <div v-if="filteredMembers.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-10/12">
        <div v-for="member in filteredMembers" :key="member.id" class="bg-white p-4 rounded-lg shadow hover:shadow-lg">
          <h2 class="text-xl font-semibold mb-1">{{ member.firstName }} {{ member.lastName }}</h2>
          <p class="text-sm text-gray-600">Email: {{ member.email }}</p>
          <p class="text-sm text-gray-600">Phone: {{ member.phone_number }}</p>
          <p class="text-sm text-gray-600">Address: {{ member.address }}</p>
          <p class="text-sm text-gray-600">About: {{ member.about }}</p>
          <div class="flex justify-end gap-2 mt-2">
            <button @click="editMember(member)" class="text-blue-600 hover:underline">Edit</button>
            <button @click="deleteMember(member.id)" class="text-red-600 hover:underline">Delete</button>
          </div>
        </div>
      </div>
      <p v-else class="text-center text-gray-500 mt-10">No members found.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Search from '@/components/Search.vue'
import Button from '@/components/Button.vue'

const members = ref([])
const searchQuery = ref('')
const showForm = ref(false)
const isEditing = ref(false)
const editId = ref(null)

const formMember = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone_number: '',
  address: '',
  about: ''
})

const resetForm = () => {
  formMember.value = {
    firstName: '',
    lastName: '',
    email: '',
    phone_number: '',
    address: '',
    about: ''
  }
  showForm.value = false
  isEditing.value = false
  editId.value = null
}

// Load members
onMounted(async () => {
  const res = await axios.get('http://192.168.108.11:8000/api/members')
  members.value = res.data.data
})

// Add or Update
const submit = async () => {
  const url = isEditing.value
    ? `http://192.168.108.11:8000/api/members/update/${editId.value}`
    : `http://192.168.108.11:8000/api/members/create`

  const method = isEditing.value ? 'PUT' : 'POST'
  const payload = { ...formMember.value }

  try {
    const res = await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })

    const data = await res.json()

    if (res.ok) {
      if (isEditing.value) {
        const i = members.value.findIndex(m => m.id === editId.value)
        if (i !== -1) members.value[i] = data.data
      } else {
        members.value.push(data.data)
      }
      resetForm()
    } else {
      console.error('Save failed:', data)
      alert('Failed to save member.')
    }
  } catch (err) {
    console.error('Error saving member:', err)
    alert('Internal error.')
  }
}

const editMember = (member) => {
  formMember.value = { ...member }
  editId.value = member.id
  isEditing.value = true
  showForm.value = true
}

const deleteMember = async (id) => {
  if (!confirm('Are you sure?')) return
  try {
    await axios.delete(`http://192.168.108.11:8000/api/members/delete/${id}`)
    members.value = members.value.filter(m => m.id !== id)
  } catch (err) {
    console.error('Delete failed:', err)
    alert('Failed to delete member.')
  }
}

const filteredMembers = computed(() => {
  const q = searchQuery.value.toLowerCase()
  return members.value.filter(m =>
    (m.firstName || '').toLowerCase().includes(q) ||
    (m.lastName || '').toLowerCase().includes(q) ||
    (m.email || '').toLowerCase().includes(q) ||
    (m.phone_number || '').toLowerCase().includes(q) ||
    (m.address || '').toLowerCase().includes(q) ||
    (m.about || '').toLowerCase().includes(q)
  )
})
</script>

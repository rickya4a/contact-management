<template>
  <div class="space-y-4">
    <!-- Search and Add Contact -->
    <div class="flex justify-between items-center">
      <div class="flex-1 max-w-sm">
        <input
          type="search"
          placeholder="Search contacts..."
          v-model="searchQuery"
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          @input="handleSearch"
        />
      </div>
      <button
        @click="showCreateModal = true"
        class="ml-3 inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
      >
        Add Contact
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="contactStore.loading" class="text-center py-4">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="contactStore.error" class="text-center py-4 text-red-600">
      {{ contactStore.error }}
    </div>

    <!-- Empty State -->
    <div v-else-if="!contactStore.contacts.length" class="text-center py-4 text-gray-500">
      No contacts found.
    </div>

    <!-- Contact List -->
    <div v-else class="overflow-hidden bg-white shadow sm:rounded-md">
      <ul role="list" class="divide-y divide-gray-200">
        <li v-for="contact in contactStore.contacts" :key="contact.id" class="px-4 py-4 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-indigo-600 truncate">{{ contact.name }}</p>
              <p class="mt-1 text-sm text-gray-500">{{ contact.email }}</p>
              <p class="mt-1 text-sm text-gray-500">{{ contact.phone }}</p>
            </div>
            <div class="flex space-x-2">
              <button
                @click="handleEdit(contact)"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
              >
                Edit
              </button>
              <button
                @click="handleDelete(contact.id!)"
                class="inline-flex items-center rounded-md border border-transparent bg-red-100 px-2.5 py-1.5 text-sm font-medium text-red-700 hover:bg-red-200"
              >
                Delete
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Pagination -->
    <div v-if="contactStore.pagination.lastPage > 1" class="flex justify-center space-x-2">
      <button
        v-for="page in contactStore.pagination.lastPage"
        :key="page"
        @click="handlePageChange(page)"
        :class="[
          'px-3 py-1 rounded-md text-sm',
          page === contactStore.pagination.currentPage
            ? 'bg-indigo-600 text-white'
            : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
        ]"
      >
        {{ page }}
      </button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || editingContact" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ editingContact ? 'Edit Contact' : 'Create Contact' }}
        </h3>
        <ContactForm
          :contact="editingContact || undefined"
          :loading="contactStore.loading"
          @submit="handleSubmit"
          @cancel="closeModal"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useContactStore } from '@/stores/contactStore';
import ContactForm from './ContactForm.vue';
import type { Contact } from '@/types/contact';

const contactStore = useContactStore();
const showCreateModal = ref(false);
const editingContact = ref<Contact | null>(null);
const searchQuery = ref('');

// Load initial data
contactStore.fetchContacts();

const handleSearch = () => {
  // Implement search logic here
  // You might want to debounce this
  contactStore.fetchContacts(1);
};

const handlePageChange = (page: number) => {
  contactStore.fetchContacts(page);
};

const handleEdit = (contact: Contact) => {
  editingContact.value = contact;
};

const handleDelete = async (id: number) => {
  if (confirm('Are you sure you want to delete this contact?')) {
    try {
      await contactStore.deleteContact(id);
    } catch (error) {
      // Error is already handled in the store
    }
  }
};

const handleSubmit = async (contact: Contact) => {
  try {
    if (editingContact.value) {
      await contactStore.updateContact(editingContact.value.id!, contact);
    } else {
      await contactStore.createContact(contact);
    }
    closeModal();
  } catch (error) {
    // Error is already handled in the store
  }
};

const closeModal = () => {
  showCreateModal.value = false;
  editingContact.value = null;
};
</script>
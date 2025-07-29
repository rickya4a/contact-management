<template>
  <div class="space-y-6">
    <!-- Search and Add Contact -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0">
      <div class="w-full sm:max-w-sm">
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            type="search"
            placeholder="Search by name, email, or phone..."
            v-model="searchQuery"
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            @input="handleSearch"
          />
          <div v-if="searchQuery" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <button
              @click="() => { searchQuery = ''; handleSearch(); }"
              class="text-gray-400 hover:text-gray-500 focus:outline-none"
            >
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <button
        @click="showCreateModal = true"
        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Contact
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="contactStore.loading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="contactStore.error" class="text-center py-8 bg-red-50 rounded-lg">
      <div class="flex items-center justify-center text-red-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ contactStore.error }}
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!contactStore.contacts.length" class="text-center py-8 bg-gray-50 rounded-lg">
      <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No contacts</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by creating a new contact.</p>
    </div>

    <!-- Contact List -->
    <div v-else class="overflow-hidden bg-white shadow-sm sm:rounded-lg border border-gray-200">
      <ul role="list" class="divide-y divide-gray-200">
        <li v-for="contact in displayedContacts" :key="contact.id"
            class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors duration-150">
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0 pr-4">
              <p class="text-sm font-semibold text-indigo-600 truncate">{{ contact.name }}</p>
              <div class="mt-2 flex flex-col sm:flex-row sm:items-center text-sm text-gray-500">
                <p class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  {{ contact.email }}
                </p>
                <p class="flex items-center mt-1 sm:mt-0 sm:ml-6">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  {{ contact.phone }}
                </p>
              </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
              <button
                @click="handleEdit(contact)"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
              </button>
              <button
                @click="handleDelete(contact.id!)"
                class="inline-flex items-center justify-center rounded-md bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Pagination -->
    <div v-if="contactStore.pagination.lastPage > 1" class="flex justify-center space-x-2 mt-6">
      <button
        v-for="page in contactStore.pagination.lastPage"
        :key="page"
        @click="handlePageChange(page)"
        :class="[
          'px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
          page === contactStore.pagination.currentPage
            ? 'bg-indigo-600 text-white hover:bg-indigo-500'
            : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
        ]"
      >
        {{ page }}
      </button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || editingContact" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 sm:mx-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">
            {{ editingContact ? 'Edit Contact' : 'Create Contact' }}
          </h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
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
import { ref, computed, onUnmounted } from 'vue';
import { useContactStore } from '@/stores/contactStore';
import ContactForm from './ContactForm.vue';
import type { Contact } from '@/types/contact';

const contactStore = useContactStore();
const showCreateModal = ref(false);
const editingContact = ref<Contact | null>(null);
const searchQuery = ref('');
let debounceTimer: number | null = null;

// Load initial data
contactStore.fetchContacts();

const handleSearch = () => {
  // Clear previous timer
  if (debounceTimer) {
    clearTimeout(debounceTimer);
  }

  // Set new timer
  debounceTimer = window.setTimeout(() => {
    contactStore.setSearchQuery(searchQuery.value);
    contactStore.fetchContacts(1, searchQuery.value);
  }, 300); // Wait for 300ms after last keystroke
};

// Clean up timer on component unmount
onUnmounted(() => {
  if (debounceTimer) {
    clearTimeout(debounceTimer);
  }
});

const displayedContacts = computed(() => {
  return contactStore.sortedContacts;
});

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
<template>
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Contacts</h1>
      </div>
      <ContactList />
    </div>
  </div>
</template>

<script setup lang="ts">
import ContactList from '@/components/ContactList.vue';
</script>
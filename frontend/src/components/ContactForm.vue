<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div class="space-y-1">
      <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
      <div class="relative h-10">
        <div class="absolute left-0 inset-y-0 pl-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <input
          type="text"
          id="name"
          v-model="form.name"
          :class="[
            'h-full block w-full pl-10 pr-3 rounded-md text-sm shadow-sm',
            errors.name ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'
          ]"
          placeholder="John Doe"
          @blur="validateField('name')"
          required
        />
      </div>
      <p v-if="errors.name" class="text-sm text-red-600">{{ errors.name }}</p>
    </div>

    <div class="space-y-1">
      <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
      <div class="relative h-10">
        <div class="absolute left-0 inset-y-0 pl-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
        <input
          type="email"
          id="email"
          v-model="form.email"
          :class="[
            'h-full block w-full pl-10 pr-3 rounded-md text-sm shadow-sm',
            errors.email ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'
          ]"
          placeholder="john@example.com"
          @blur="validateField('email')"
          required
        />
      </div>
      <p v-if="errors.email" class="text-sm text-red-600">{{ errors.email }}</p>
    </div>

    <div class="space-y-1">
      <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
      <div class="relative h-10">
        <div class="absolute left-0 inset-y-0 pl-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
          </svg>
        </div>
        <input
          type="tel"
          id="phone"
          v-model="form.phone"
          :class="[
            'h-full block w-full pl-10 pr-3 rounded-md text-sm shadow-sm',
            errors.phone ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'
          ]"
          placeholder="+62 812 345 6789"
          @blur="validateField('phone')"
          @input="formatPhoneNumber"
          required
        />
      </div>
      <p v-if="errors.phone" class="text-sm text-red-600">{{ errors.phone }}</p>
    </div>

    <div class="flex justify-end space-x-3 pt-2">
      <button
        type="button"
        @click="$emit('cancel')"
        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Cancel
      </button>
      <button
        type="submit"
        :disabled="loading || hasErrors"
        :class="[
          'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200',
          loading || hasErrors ? 'bg-indigo-400 cursor-not-allowed text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white'
        ]"
      >
        <svg v-if="!loading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <svg v-else class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loading ? 'Saving...' : submitLabel }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import type { Contact } from '@/types/contact';

const props = defineProps<{
  contact?: Contact;
  loading?: boolean;
}>();

const emit = defineEmits<{
  (e: 'submit', contact: Contact): void;
  (e: 'cancel'): void;
}>();

const form = ref({
  name: '',
  email: '',
  phone: ''
});

const errors = ref({
  name: '',
  email: '',
  phone: ''
});

const hasErrors = computed(() => {
  return Object.values(errors.value).some(error => error !== '');
});

const submitLabel = props.contact ? 'Update Contact' : 'Create Contact';

onMounted(() => {
  if (props.contact) {
    form.value = { ...props.contact };
  }
});

const validateField = (field: keyof typeof form.value) => {
  errors.value[field] = '';

  switch (field) {
    case 'name':
      if (form.value.name.length < 2) {
        errors.value.name = 'Name must be at least 2 characters long';
      }
      if (form.value.name.length > 100) {
        errors.value.name = 'Name must be less than 100 characters long';
      }
      const nameRegex = /^[a-zA-Z\s]+$/;
      if (!nameRegex.test(form.value.name)) {
        errors.value.name = 'Name must contain only letters and spaces';
      }
      break;
    case 'email':
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(form.value.email)) {
        errors.value.email = 'Please enter a valid email address';
      }
      break;
    case 'phone':
      // Allow international format with optional + and spaces between numbers
      const phoneRegex = /^\+?[0-9\s]{8,}$/;
      const cleanPhone = form.value.phone.replace(/\s+/g, '');
      if (!phoneRegex.test(form.value.phone)) {
        errors.value.phone = 'Please enter a valid phone number (min. 8 digits)';
      } else if (cleanPhone.length > 15) {
        errors.value.phone = 'Phone number is too long (max 15 digits)';
      }
      break;
  }
};

const formatPhoneNumber = (event: Event) => {
  const input = event.target as HTMLInputElement;
  let value = input.value.replace(/[^\d+]/g, ''); // Keep only digits and +

  // Allow + only at the start
  if (value.length > 1) {
    value = value.charAt(0) + value.slice(1).replace(/\+/g, '');
  }

  // Add spaces for readability
  if (value.length > 0) {
    // If starts with +, group the country code differently
    if (value.startsWith('+')) {
      // Format: +XX XXX XXX XXX
      value = value.replace(/(\+\d{2})(\d{3})(\d{3})(\d+)?/, '$1 $2 $3 $4').trim();
    } else {
      // Format without country code: XXX XXX XXX
      value = value.replace(/(\d{3})(\d{3})(\d+)?/, '$1 $2 $3').trim();
    }
  }

  form.value.phone = value;
};

const validateForm = () => {
  validateField('name');
  validateField('email');
  validateField('phone');
  return !hasErrors.value;
};

const handleSubmit = () => {
  if (validateForm()) {
    emit('submit', form.value);
  }
};
</script>
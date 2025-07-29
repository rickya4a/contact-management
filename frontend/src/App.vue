<script setup lang="ts">
import { computed } from 'vue';
import { RouterView, useRouter } from 'vue-router';

const router = useRouter();

const isAuthenticated = computed(() => {
  const token = localStorage.getItem('token');
  const currentRoute = router.currentRoute.value;
  return !!token && currentRoute.path !== '/login';
});

const handleLogout = () => {
  localStorage.removeItem('token');
  router.push('/login');
};
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <nav v-if="isAuthenticated" class="bg-white shadow-lg fixed w-full top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-indigo-600 hover:text-indigo-500 transition-colors duration-200">Contact Manager</h1>
            </div>
          </div>
          <div class="flex items-center">
            <button
              @click="handleLogout"
              class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main class="bg-gradient-to-br from-indigo-50 via-white to-purple-50">
      <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<style scoped>
.router-link-active {
  @apply text-indigo-600;
}

@media (max-width: 640px) {
  nav {
    @apply px-2;
  }

  h1 {
    @apply text-lg;
  }
}
</style>

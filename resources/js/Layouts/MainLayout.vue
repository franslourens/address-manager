<template>
  <header class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 w-full">
    <div class="container mx-auto relative">
      <nav class="p-4 flex items-center justify-between relative">
        
        <div class="absolute left-1/2 -translate-x-1/2 text-xl text-indigo-600 dark:text-indigo-300 font-bold">
          <Link :href="route('address.index')">Address Manager</Link>
        </div>

        <div v-if="user" class="flex items-center gap-4 ml-auto">
          <div class="text-sm text-gray-500">{{ user.name }}</div>
          <Link :href="route('address.create')" class="btn-primary">+ New Address</Link>
          <Link :href="route('logout')" method="delete" as="button">Logout</Link>
        </div>

        <div v-else class="flex items-center gap-2 ml-auto">
          <Link :href="route('user-account.create')">Register</Link>
          <Link :href="route('login')">Sign-In</Link>
        </div>
      </nav>
    </div>
  </header>
  
  <main class="container mx-auto p-4 w-full">
    <div v-if="flashSuccess" class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
      {{ flashSuccess }}
    </div>
    <slot>Default</slot>
  </main>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const flash = computed(
  () => page.props.flash || {}
)

const flashSuccess = computed(
  () => flash.value.success ?? null
)

const flashError = computed(
  () => flash.value.error ?? null
)

const user = computed(
  () => page.props.user ?? null
)
</script>
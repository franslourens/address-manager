<template>
  <div class="flex gap-2">
    <template v-for="(link, index) in links" :key="index">
      <Link
        v-if="link.url"
        :href="link.url"
        class="px-3 py-2 rounded-md text-sm font-medium transition 
               dark:text-gray-300 text-gray-700
               hover:bg-indigo-100 hover:dark:bg-indigo-900 hover:text-indigo-700 hover:dark:text-indigo-300"
        :class="{
          'bg-indigo-600 dark:bg-indigo-700 text-white shadow':
            link.active
        }"
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: Array
})
</script>
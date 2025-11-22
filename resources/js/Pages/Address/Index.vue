<template>
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <Listing v-for="address in addresses.data" :key="address.id" :address="address" />
  </div>

  <div v-if="addresses.data.length" class="w-full flex justify-center mt-4 mb-4">
    <Pagination :links="addresses.links" />
  </div>
</template>

<script setup>
import Listing from '@/Pages/Address/Index/Components/Listing.vue'
import Pagination from '@/Components/UI/Pagination.vue'

defineProps({
  addresses: Object,
})
</script>
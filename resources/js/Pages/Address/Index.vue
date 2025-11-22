<template>
  <h1 class="text-3xl mb-4">Addresses</h1>

  <section v-if="addresses.data.length" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
    <Box v-for="address in addresses.data" :key="address.id" :class="{ 'border-dashed': address.deleted_at }">
      <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
        <div :class="{ 'opacity-25': address.deleted_at }">
          <ListingAddress :address="address" />
        </div>
        <section>
          <div
            class="flex items-center gap-1 text-gray-600 dark:text-gray-300"
          >
            <a
              class="btn-outline text-xs font-medium"
              :href="route('address.show', { address: address.id })"
              target="_blank"
            >
              View
            </a>
            <template v-if="$page.props.user">
              <Link
                class="btn-outline text-xs font-medium"
                :href="route('address.edit', { address: address.id })"
              >
                Edit
              </Link>

              <Link
                v-if="!address.deleted_at"
                class="btn-outline text-xs font-medium"
                :href="route('address.destroy', { address: address.id })"
                as="button" method="delete"
              >
                Delete
              </Link>

              <Link
                v-else class="btn-outline text-xs font-medium"
                :href="route('address.restore', { address: address.id })"
                as="button" method="put"
              >
                Restore
              </Link>
            </template>
          </div>
        </section>
      </div>
    </Box>
  </section>
  <EmptyState v-else>No addresses yet</EmptyState>

  <section v-if="addresses.data.length" class="w-full flex justify-center mt-4 mb-4">
    <Pagination :links="addresses.links" />
  </section>
</template>

<script setup>

import Pagination from '@/Components/UI/Pagination.vue'
import ListingAddress from '@/Components/ListingAddress.vue'
import Box from '@/Components/UI/Box.vue'
import EmptyState from '@/Components/UI/EmptyState.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  addresses: Object,
})
</script>
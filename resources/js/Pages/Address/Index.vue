<template>
  <h1 class="text-3xl mb-4">Addresses</h1>

  <section v-if="addresses.data.length" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
    <Box v-for="address in addresses.data" :key="address.id" :class="{ 'border-dashed': address.deleted_at }">
      <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
        <div :class="{ 'opacity-25': address.deleted_at }">
          <ListingAddress :address="address" />
        </div>
        <section>
          <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300">
            <Link
              class="btn-outline text-xs font-medium"
              :href="route('address.show', { address: address.id })"
            >
              View
            </Link>
            <template v-if="address.belongs_to_user">
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
import { computed, onMounted, onBeforeUnmount, watch } from 'vue'
import { router } from '@inertiajs/vue3'

import Pagination from '@/Components/UI/Pagination.vue'
import ListingAddress from '@/Components/ListingAddress.vue'
import Box from '@/Components/UI/Box.vue'
import EmptyState from '@/Components/UI/EmptyState.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  addresses: Object,
})

const hasInProgressAddresses = computed(() =>
  props.addresses?.data?.some(address =>
    ['pending', 'processing'].includes(address.status)
  )
)

let pollTimer = null

const startPolling = () => {
  if (pollTimer || !hasInProgressAddresses.value) return

  pollTimer = setInterval(() => {

    if (!hasInProgressAddresses.value) {
      clearInterval(pollTimer)
      pollTimer = null
      return
    }

    router.reload({
      only: ['addresses'],
      preserveScroll: true,
      preserveState: true,
    })
  }, 3000) // every 3 seconds
}

onMounted(() => {
  if (hasInProgressAddresses.value) {
    startPolling()
  }
})

watch(hasInProgressAddresses, (newVal) => {
  if (newVal) {
    startPolling()
  } else if (!newVal && pollTimer) {
    clearInterval(pollTimer)
    pollTimer = null
  }
})

onBeforeUnmount(() => {
  if (pollTimer) {
    clearInterval(pollTimer)
  }
})
</script>
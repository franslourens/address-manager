<template>
  <div class="flex flex-col-reverse md:grid md:grid-cols-12 gap-4">
    <Box class="md:col-span-7 flex items-center">
      <div class="w-full text-center font-medium text-gray-500">
        <GoogleMap :address="address" />
      </div>
    </Box>
    <div class="md:col-span-5 flex flex-col gap-4">
      <Box>
        <template #header>
          <ListingAddress :address="address" />
        </template>
      </Box>

      <Box v-if="$page.props.user && $page.props.user.id === address.by_user_id">
        <template #header>
          <Link
            :href="route('address.rerun', { address: address.id })"
            method="post"
            as="button"
            class="btn-primary"
          >
            Re-run Geocode
          </Link>
        </template>
      </Box>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Box from '@/Components/UI/Box.vue'
import ListingAddress from '@/Components/ListingAddress.vue'
import GoogleMap from '@/Components/GoogleMap.vue'

const props = defineProps({
  address: Object,
})
</script>
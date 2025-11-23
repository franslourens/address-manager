<template>
  <GoogleMap
    v-if="address.status === 'success'"
    :api-key="apiKey"
    :center="{ lat: lat, lng: lng }"
    :zoom="16"
    style="width: 100%; height: 400px; border-radius: 0.75rem; overflow: hidden;"
  >
    <Marker
      :options="{
        position: { lat: lat, lng: lng }
      }"
    />
  </GoogleMap>

  <div
    v-else
    class="w-full text-center font-medium text-gray-500 py-10"
  >
    No coordinates available yet.
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { GoogleMap, Marker } from 'vue3-google-map'

const props = defineProps({
  address: Object,
})

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY

const lat = computed(() => Number(props.address.latitude))
const lng = computed(() => Number(props.address.longitude))

</script>
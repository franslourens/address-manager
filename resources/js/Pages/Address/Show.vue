<template>
  <div class="flex flex-col-reverse md:grid md:grid-cols-12 gap-4">
    <Box class="md:col-span-7 flex items-center">
      <div class="w-full text-center font-medium text-gray-500">Map</div>
    </Box>
    <div class="md:col-span-5 flex flex-col gap-4">
      <Box>
        <template #header>
          Basic info
        </template>
      </Box>

      <Box>
        <template #header>
          Address
        </template>
        <div>
          
        </div>
      </Box>
    </div>
  </div>
</template>

<script setup>
import Box from '@/Components/UI/Box.vue'

const props = defineProps({
  address: Object,
})
</script>
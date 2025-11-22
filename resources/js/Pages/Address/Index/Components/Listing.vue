<template>
  <Box>
    <div>
        <div class="flex items-center gap-1">
              {{ address.line1 }}
              {{ address.line2 }}
              {{ address.city }}
              {{ address.province }}
              {{ address.postal }}
              {{ address.country_code }}
              {{ address.status }}
              {{ address.last_error }}
        </div>
    </div>
  </Box>
</template>

<script setup>
import Box from '@/Components/UI/Box.vue'

const props = defineProps({address: Object})
</script>
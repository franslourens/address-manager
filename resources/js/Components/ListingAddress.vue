<template>
  <div>
    <span>
      {{ address.line1 }} |
    </span> 
    <span>
      {{ address.status }}
    </span>
    <span>
      {{ address.last_error }}
    </span> 
  </div>
  <span>
    {{ address.line2 }}
    {{ address.country_code }}
    {{ address.city }}
    {{ address.province }}
    {{ address.postal }}
  </span>
</template>

<script setup>
defineProps({
  address: Object,
})
</script>
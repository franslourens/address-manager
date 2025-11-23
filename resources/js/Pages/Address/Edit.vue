<template>
  <form @submit.prevent="update">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <label class="label">Address</label>
        <input v-model="form.line1" type="text" class="input" />
        <div v-if="form.errors.line1" class="input-error">
          {{ form.errors.line1 }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">City</label>
        <input v-model="form.city" type="text" class="input" />
        <div v-if="form.errors.city" class="input-error">
          {{ form.errors.city }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Province</label>
        <input v-model="form.province" type="text" class="input" />
        <div v-if="form.errors.province" class="input-error">
          {{ form.errors.province }}
        </div>
      </div>

      <div class="col-span-4">
        <label class="label">Postal</label>
        <input v-model="form.postal" type="text" class="input" />
        <div v-if="form.errors.postal" class="input-error">
          {{ form.errors.postal }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Country Code</label>
        <input v-model="form.country_code" type="text" class="input" />
        <div v-if="form.errors.country_code" class="input-error">
          {{ form.errors.country_code }}
        </div>
      </div>

      <div class="col-span-6">
        <label class="label">Status</label>
        <input 
          v-model="form.status" 
          type="text" 
          class="input bg-gray-100 dark:bg-gray-800 cursor-not-allowed" 
          readonly 
        />
        <div v-if="form.errors.status" class="input-error">
          {{ form.errors.status }}
        </div>
      </div>

      <div class="col-span-6">
        <button type="submit" class="btn-primary">Edit</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  address: Object,
})

const form = useForm({
  line1: props.address.line1,
  line2: props.address.line2,
  city: props.address.city,
  province: props.address.province,
  postal: props.address.postal,
  country_code: props.address.country_code,
  status: props.address.status,
  last_error: props.address.last_error,
})

const update = () => form.put(route('address.update', {address: props.address.id}))
</script>

<style scoped>
label {
  margin-right: 2em;
}

div {
  padding: 2px
}
</style>
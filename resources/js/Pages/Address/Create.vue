<template>
  <form @submit.prevent="create">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Address</label>
        <input v-model="form.address" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500"/>
        <div v-if="form.errors.address" class="input-error">
          {{ form.errors.address }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Country Code</label>
        <input v-model="form.countryCode" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.countryCode" class="input-error">
          {{ form.errors.countryCode }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Language Code</label>
        <input v-model="form.languageCode" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.languageCode" class="input-error">
          {{ form.errors.languageCode }}
        </div>
      </div>

      <div class="col-span-4">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Latitude</label>
        <input v-model="form.latitude" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.latitude" class="input-error">
          {{ form.errors.latitude }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Longitude</label>
        <input v-model="form.longitude" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.longitude" class="input-error">
          {{ form.errors.longitude }}
        </div>
      </div>

      <div class="col-span-4">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Postal Address</label>
        <input v-model="form.postalAddress" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.postalAddress" class="input-error">
          {{ form.errors.postalAddress }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">City</label>
        <input v-model.number="form.city" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.city" class="input-error">
          {{ form.errors.city }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">State</label>
        <input v-model.number="form.state" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.state" class="input-error">
          {{ form.errors.state }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Location Type</label>
        <input v-model.number="form.locationType" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.locationType" class="input-error">
          {{ form.errors.locationType }}
        </div>
      </div>

       <div class="col-span-2">
        <label class="block mb-1 text-gray-500 dark:text-gray-300 font-medium">Building Type</label>
        <input v-model.number="form.buildingType" class="block w-full p-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 text-gray-500" />
        <div v-if="form.errors.buildingType" class="input-error">
          {{ form.errors.buildingType }}
        </div>
      </div>

      <div class="col-span-6">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium p-2 rounded-md">Create</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  address: null,
  countryCode: null,
  languageCode: null,
  latitude: null,
  longitude: null,
  postalAddress: null,
  city: null,
  state: null,
  locationType: null,
  buildingType:null,
})

const create = () => form.post(route('address.store'))

</script>

<style scoped>
label {
  margin-right: 2em;
}

div {
  padding: 2px
}
</style>
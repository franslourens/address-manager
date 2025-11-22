<template>
  <form @submit.prevent="create">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <label class="label">Address</label>
        <input v-model="form.address" type="text" class="input"/>
        <div v-if="form.errors.address" class="input-error">
          {{ form.errors.address }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Country Code</label>
        <input v-model="form.countryCode" type="text" class="input" />
        <div v-if="form.errors.countryCode" class="input-error">
          {{ form.errors.countryCode }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Language Code</label>
        <input v-model="form.languageCode" type="text" class="input" />
        <div v-if="form.errors.languageCode" class="input-error">
          {{ form.errors.languageCode }}
        </div>
      </div>

      <div class="col-span-4">
        <label class="label">Latitude</label>
        <input v-model="form.latitude" type="text" class="input" />
        <div v-if="form.errors.latitude" class="input-error">
          {{ form.errors.latitude }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Longitude</label>
        <input v-model="form.longitude" type="text" class="input" />
        <div v-if="form.errors.longitude" class="input-error">
          {{ form.errors.longitude }}
        </div>
      </div>

      <div class="col-span-4">
        <label class="label">Postal Address</label>
        <input v-model="form.postalAddress" type="text" class="input" />
        <div v-if="form.errors.postalAddress" class="input-error">
          {{ form.errors.postalAddress }}
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
        <label class="label">State</label>
        <input v-model="form.state" type="text" class="input" />
        <div v-if="form.errors.state" class="input-error">
          {{ form.errors.state }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Location Type</label>
        <input v-model="form.locationType" type="text" class="input" />
        <div v-if="form.errors.locationType" class="input-error">
          {{ form.errors.locationType }}
        </div>
      </div>

       <div class="col-span-2">
        <label class="label">Building Type</label>
        <input v-model="form.buildingType" type="text" class="input" />
        <div v-if="form.errors.buildingType" class="input-error">
          {{ form.errors.buildingType }}
        </div>
      </div>

      <div class="col-span-6">
        <button type="submit" class="btn-primary">Create</button>
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
<template>
  <form @submit.prevent="create">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-xl mx-auto">

      <div class="md:col-span-2">
        <label class="label">
          Address <span class="text-red-500">*</span>
        </label>
        <input
          v-model="form.address"
          type="text"
          class="input"
          placeholder="Enter full address"
        />
        <div v-if="form.errors.address" class="input-error">
          {{ form.errors.address }}
        </div>
      </div>

      <div>
        <label class="label">Country Code <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.countryCode"
          type="text"
          class="input"
          placeholder="e.g. ZA"
        />
        <div v-if="form.errors.countryCode" class="input-error">
          {{ form.errors.countryCode }}
        </div>
      </div>

      <div>
        <label class="label">Language Code <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.languageCode"
          type="text"
          class="input"
          placeholder="e.g. en"
        />
        <div v-if="form.errors.languageCode" class="input-error">
          {{ form.errors.languageCode }}
        </div>
      </div>

      <div>
        <label class="label">Latitude <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.latitude"
          type="text"
          class="input"
          placeholder="Latitude"
        />
        <div v-if="form.errors.latitude" class="input-error">
          {{ form.errors.latitude }}
        </div>
      </div>

      <div>
        <label class="label">Longitude <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.longitude"
          type="text"
          class="input"
          placeholder="Longitude"
        />
        <div v-if="form.errors.longitude" class="input-error">
          {{ form.errors.longitude }}
        </div>
      </div>

      <div>
        <label class="label">Postal Code <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model.number="form.postalCode"
          type="text"
          class="input"
          placeholder="Postal Code"
        />
        <div v-if="form.errors.postalCode" class="input-error">
          {{ form.errors.postalCode }}
        </div>
      </div>

      <div>
        <label class="label">City <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.city"
          type="text"
          class="input"
          placeholder="City"
        />
        <div v-if="form.errors.city" class="input-error">
          {{ form.errors.city }}
        </div>
      </div>

      <div>
        <label class="label">State <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.state"
          type="text"
          class="input"
          placeholder="State or Province"
        />
        <div v-if="form.errors.state" class="input-error">
          {{ form.errors.state }}
        </div>
      </div>

      <div>
        <label class="label">Location Type <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.locationType"
          type="text"
          class="input"
          placeholder="Residential, business…"
        />
        <div v-if="form.errors.locationType" class="input-error">
          {{ form.errors.locationType }}
        </div>
      </div>

      <div>
        <label class="label">Building Type <span class="text-gray-500 text-sm">optional</span></label>
        <input
          v-model="form.buildingType"
          type="text"
          class="input"
          placeholder="House, apartment…"
        />
        <div v-if="form.errors.buildingType" class="input-error">
          {{ form.errors.buildingType }}
        </div>
      </div>

      <div class="md:col-span-2">
        <button type="submit" class="btn-primary w-full">Create</button>
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
  postalCode: null,
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
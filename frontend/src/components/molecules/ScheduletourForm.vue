<script setup lang="ts">
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

interface DogOption {
  id: string | number
  nome: string
  [key: string]: string | number
}

interface ScheduleTourForm {
  dog_id: string | number
  date: string
  hour: string
  duration: string
  value: string | number
  location: string
}

interface ScheduleTourFormProps {
  form: ScheduleTourForm
  labelButton: string
  dogs: DogOption[]
}

const props = defineProps<ScheduleTourFormProps>()

const emit = defineEmits<{
  submit: []
}>()
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <label class="form-label text-start w-100">🐶 Cachorro</label>
      <BaseSelect
        v-model="form.dog_id"
        :options="dogs"
        labelKey="nome"
        valueKey="id"
      />
    </div>

    <div class="row g-3">

      <div class="col-12 col-md-6">
        <label class="form-label text-start w-100">📅 Data</label>
        <BaseInput v-model="form.date" type="date" />
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label text-start w-100">⏰ Hora</label>
        <BaseInput v-model="form.hour" type="time" />
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label text-start w-100">⏳ Duração</label>
        <BaseInput v-model="form.duration" type="time" />
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label text-start w-100">💰 Valor</label>
        <BaseInput
          v-model="form.value"
          type="number"
          step="0.01"
          min="0"
        />
      </div>

      <div class="col-12">
        <label class="form-label text-start w-100">📍 Local</label>
        <BaseInput v-model="form.location" placeholder="Digite o local" />
      </div>

    </div>

    <div class="mt-4 d-grid">
      <BaseButton
        :label="labelButton"
        @click="emit('submit')"
      />
    </div>

  </div>
</template>

<style scoped>
.form {
  width: 100%;
}
</style>
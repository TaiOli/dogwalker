<script setup lang="ts">
import { computed } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

interface DogOption {
  id: string | number
  nome: string
  [key: string]: string | number
}

interface WalkerOption {
  id: string | number
  nome: string
  [key: string]: string | number | null | undefined
}

interface ScheduleTourForm {
  dog_id: string | number
  date: string
  hour: string
  duration: string
  value: string | number
  location: string
  passeador_id: string | number
}

interface ScheduleTourFormProps {
  form: ScheduleTourForm
  labelButton: string
  dogs: DogOption[]
  walkers?: WalkerOption[]
}

const props = withDefaults(defineProps<ScheduleTourFormProps>(), {
  walkers: () => []
})

const emit = defineEmits<{
  submit: []
}>()

// opção "qualquer passeador" + lista real de passeadores disponíveis
const walkerOptions = computed<WalkerOption[]>(() => [
  { id: "", nome: "Qualquer passeador disponível" },
  ...props.walkers
])
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

    <div class="mb-3">
      <label class="form-label text-start w-100">🎯 Passeador</label>
      <BaseSelect
        v-model="form.passeador_id"
        :options="walkerOptions"
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
<script setup lang="ts">
import { computed, ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

interface DogOption {
  id: string | number
  nome: string
  [key: string]: unknown
}

interface WalkerOption {
  id: string | number
  nome?: string
  name?: string
  [key: string]: unknown
}

interface ScheduleTourForm {
  dog_id: string | number
  date: string
  hour: string
  duration: string
  value: string | number
  location: string
  walker_id: string | number
}

interface Props {
  form: ScheduleTourForm
  labelButton: string
  dogs: DogOption[]
  walkers?: WalkerOption[]
}

const props = withDefaults(defineProps<Props>(), {
  walkers: () => []
})

const emit = defineEmits<{ submit: [] }>()

const walkerError = ref("")

const walkerOptions = computed(() => [
  { id: "", name: "Selecione um passeador..." },
  ...props.walkers.map(w => ({ id: w.id, name: w.nome ?? w.name ?? "" }))
])

function handleSubmit(): void {
  if (!props.form.walker_id) {
    walkerError.value = "Selecione um passeador!"
    return
  }
  walkerError.value = ""
  emit("submit")
}
</script>

<template>
  <v-container class="pa-0">

    <v-row class="ga-2 mt-5">

      <v-col cols="12" md="8" class="mx-auto">
        <BaseSelect
          v-model="form.dog_id"
          label="🐶 Cachorro"
          :options="dogs"
          labelKey="nome"
          valueKey="id"
        />
      </v-col>

      <v-col cols="12" md="8" class="mx-auto">
        <BaseSelect
          v-model="form.walker_id"
          required
          label="🎯 Passeador"
          :options="walkerOptions"
          labelKey="name"
          valueKey="id"
          :error-message="walkerError"
          @update:modelValue="walkerError = ''"
        />
      </v-col>

      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.date"
          type="date"
          label="📅 Data"
        />
      </v-col>

      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.hour"
          type="time"
          label="⏰ Hora"
        />
      </v-col>

      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.duration"
          type="time"
          label="⏳ Duração"
        />
      </v-col>

      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.value"
          type="number"
          label="💰 Valor"
        />
      </v-col>

      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.location"
          label="📍 Local"
        />
      </v-col>
    </v-row>
    <v-row justify="center" class="mt-4 mb-4">
      <v-col cols="12" sm="6">
        <BaseButton
          :label="labelButton"
          block
          @click="handleSubmit"
          class="btn-mustard"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.form {
  width: 100%;
}

.btn-mustard {
    background-color: #D4A017;
    border-color: #D4A017;
    color: #1F1F1F;
    font-weight: 600;
    transition: 0.3s ease;
    border-radius: 50px;
    padding: 10px 32px;
    max-width: 70%;
    border-radius: 50px;
    padding: 10px 32px;
}

.btn-mustard:hover {
    background-color: #B88A12;
    border-color: #B88A12;
    color: #1F1F1F;
}

.btn-mustard:focus,
.btn-mustard:active {
    background-color: #A97C10 !important;
    border-color: #A97C10 !important;
    color: #1F1F1F !important;
    box-shadow: 0 0 0 0.25rem rgba(212, 160, 23, 0.35);
}
</style>
<script setup lang="ts">
import { ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

interface DogForm {
  name: string
  age: string | number
  size: string
  breed: string
  observations: string
  photo: File | string | null
}

interface Props {
  form: DogForm
  labelButton: string
}

const props = defineProps<Props>()
const emit = defineEmits<{ submit: [] }>()
const nameError = ref("")
const sizeError = ref("")
const preview = ref<string | null>(null)

const sizeOptions = [
  { label: "Pequeno", value: "pequeno" },
  { label: "Médio",   value: "médio" },
  { label: "Grande",  value: "grande" }
]

function handleSubmit(): void {
  nameError.value = !props.form.name ? "Insira um nome!" : ""
  sizeError.value = !props.form.size ? "Selecione um porte!" : ""
  if (nameError.value || sizeError.value) return
  emit("submit")
}

function handlePhoto(value: string | number | File | File[] | null): void {
  const file = Array.isArray(value) ? value[0] : value instanceof File ? value : null
  if (!file) return

  props.form.photo = file
  preview.value = URL.createObjectURL(file)
}
</script>

<template>
  <v-container class="pa-0">

    <v-row>
      <v-col cols="12" md="8" class="mx-auto mt-3">
        <BaseInput
          v-model="form.name"
          required
          label="Nome do cachorro"
          :error-message="nameError"
          @update:modelValue="nameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.age"
          type="number"
          label="Idade"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseSelect
          v-model="form.size"
          required
          label="Porte"
          :options="sizeOptions"
          labelKey="label"
          valueKey="value"
          :error-message="sizeError"
          @update:modelValue="sizeError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.breed"
          label="Raça"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.observations"
          label="Observações"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          type="file"
          accept="image/*"
          label="Foto"
          @update:modelValue="handlePhoto"
        />
      </v-col>
    </v-row>

    <v-row v-if="preview">
      <v-col cols="12" md="8" class="text-center mx-auto">
        <v-img
          :src="preview"
          max-height="200"
          cover
          rounded="lg"
          class="mx-auto"
          style="max-width: 100%;"
        />
      </v-col>
    </v-row>

    <v-row class="mt-2">
      <v-col cols="12" md="8" class="mx-auto mb-3">
        <BaseButton
          class="btn-mustard"
          :label="labelButton"
          block
          @click="handleSubmit"
        />
      </v-col>
    </v-row>

  </v-container>
</template>

<style scoped>
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
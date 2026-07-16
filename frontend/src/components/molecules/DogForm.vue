<script setup lang="ts">
import { ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"
import BaseLabel from "../atoms/BaseLabel.vue"

interface DogForm {
  name: string
  age: string | number
  size: string
  breed: string
  observations: string
  photo: File | null
}

interface RegisterDogFormProps {
  form: DogForm
  labelButton: string
}

const props = defineProps<RegisterDogFormProps>()

const nameError = ref<string>("")
const sizeError = ref<string>("")

  function handleSubmit(): void {
  nameError.value = !props.form.name ? "Insira uma nome!" : ""
  sizeError.value = !props.form.size ? "Selecione um tipo um porte!" : ""

  if (nameError.value || sizeError.value ) {
    return
  }

  emit("submit")
}

const emit = defineEmits<{
  submit: []
}>()

const sizeOptions = [
  { label: "Pequeno", value: "pequeno" },
  { label: "Médio", value: "médio" },
  { label: "Grande", value: "grande" }
]

const preview = ref<string | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

function openFile(): void {
  fileInput.value?.click()
}

function handleFile(files: FileList | null): void {
  const file = files?.[0]
  if (!file) return

  props.form.photo = file
  preview.value = URL.createObjectURL(file)
}
</script>

<template>
  <div class="form">

    <div class="mb-2">
      <BaseInput 
        v-model="form.name" 
        required
        label="Nome do cachorro"
        :class="{ 'is-invalid': nameError }"
        @update:modelValue="nameError = ''"
      />
      <div v-if="nameError" class="text-danger small mt-1">
        {{ nameError }}
      </div>
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.age" type="number" label="Idade" />
    </div>

    <div class="mb-2">
      <BaseSelect
        v-model="form.size"
        :options="sizeOptions"
        required
        label="Porte"
        labelKey="label"
        valueKey="value"
        :class="{ 'is-invalid': sizeError }"
        @update:modelValue="sizeError = ''"
      />
      <div v-if="sizeError" class="text-danger small mt-1">
        {{ sizeError }}
      </div>
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.breed" label="Raça"/>
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.observations" label="Observações" />
    </div>

    <div class="mb-3">
      <div class="d-flex align-center ga-2">  
          <BaseInput
            v-model="form.photo"
            type="file"
            accept="image/*"
            label="Foto"
          />
        </div>
    </div>

    <div v-if="preview" class="text-center mb-3">
      <v-img :src="preview" class="img-preview" />
    </div>

    <BaseButton class="w-100 mt-2 btn-mustard" :label="labelButton" @click="handleSubmit" />
  </div>
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
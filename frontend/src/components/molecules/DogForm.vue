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
      <BaseLabel class="text-start w-100" required>
        Nome do cachorro 
      </BaseLabel>
      <BaseInput 
        v-model="form.name" 
        :class="{ 'is-invalid': nameError }"
        @update:modelValue="nameError = ''"
      />
      <div v-if="nameError" class="text-danger small mt-1">
        {{ nameError }}
      </div>
    </div>

    <div class="mb-2">
      <BaseLabel text="Idade" /> 
      <BaseInput v-model="form.age" type="number" />
    </div>

    <div class="mb-2">
      <BaseLabel class="text-start w-100" required>
        Porte
      </BaseLabel>
      <BaseSelect
        v-model="form.size"
        :options="sizeOptions"
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
      <BaseLabel text="Raça" /> 
      <BaseInput v-model="form.breed"/>
    </div>

    <div class="mb-2">
      <BaseLabel text="Observações" /> 
      <BaseInput v-model="form.observations" />
    </div>

    <div class="mb-3 d-flex align-items-center gap-2">
       <BaseInput
        ref="fileInput"
        type="file"
        accept="image/*"
        hidden
        @change="handleFile"
      />
      <BaseButton  type="button" class="clip-btn" @click="openFile" label="📎"/>
      <v-text-field class="text-muted small">Anexar foto</v-text-field>
    </div>

    <div v-if="preview" class="mb-3 text-center">
      <img :src="preview" class="img-preview" />
    </div>

    <BaseButton class="w-100" :label="labelButton" @click="handleSubmit" />

  </div>
</template>
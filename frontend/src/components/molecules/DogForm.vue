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


function handleFile(files: FileList | null): void {
  const file = files?.[0]
  if (!file) return

  props.form.photo = file
  preview.value = URL.createObjectURL(file)
}
</script>

<template>
  <v-container>

    <v-row>
      <v-col cols="12">
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
      <v-col cols="12">
      <BaseInput 
        v-model="form.age" 
        type="number" 
        label="Idade" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseSelect
        v-model="form.size"
        :options="sizeOptions"
        required
        label="Porte"
        labelKey="label"
        valueKey="value"
        :error-message="nameError"
        @update:modelValue="sizeError = ''"
      />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput v-model="form.breed" label="Raça"/>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput v-model="form.observations" label="Observações" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          type="file"
          accept="image/*"
          label="Foto"
          @change="handleFile"
        />
      </v-col>

      <v-col
        v-if="preview"
        cols="12"
        class="text-center"
      >
        <v-img
          :src="preview"
          class="img-preview"
        />
      </v-col>
    </v-row>

    <BaseButton class="w-100 mt-2 btn-mustard" :label="labelButton" @click="handleSubmit" />
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
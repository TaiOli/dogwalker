<script setup lang="ts">
import { ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"

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

const emit = defineEmits<{
  submit: []
}>()

const preview = ref<string | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

function openFile(): void {
  fileInput.value?.click()
}

function handleFile(event: Event): void {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (!file) return

  props.form.photo = file
  preview.value = URL.createObjectURL(file)
}
</script>

<template>
  <div class="form">

    <div class="mb-2">
      <BaseInput v-model="form.name" placeholder="Nome do cachorro" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.age" placeholder="Idade" type="number" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.size" placeholder="Porte (pequeno, médio, grande)" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.breed" placeholder="Raça" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.observations" placeholder="Observações" />
    </div>

    <div class="mb-3 d-flex align-items-center gap-2">

      <input
        ref="fileInput"
        type="file"
        accept="image/*"
        hidden
        @change="handleFile"
      />

      <button type="button" class="clip-btn" @click="openFile">
        📎
      </button>

      <span class="text-muted small">Anexar foto</span>

    </div>

    <div v-if="preview" class="mb-3 text-center">
      <img :src="preview" class="img-preview" />
    </div>

    <BaseButton class="w-100" :label="labelButton" @click="emit('submit')" />

  </div>
</template>

<style scoped>
.form {
  width: 100%;
}

.clip-btn {
  border: none;
  background: transparent;
  font-size: 22px;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.clip-btn:hover {
  transform: scale(1.2);
}

.img-preview {
  width: 100%;
  max-height: 200px;
  object-fit: cover;
  border-radius: 10px;
}
</style>
<script setup lang="ts">
import { ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

interface RegisterForm {
  username: string
  name: string
  email: string
  password: string
  phone: string
  type_user: string
  photo: File | string
}

interface Props {
  form: RegisterForm
  labelButton: string
}

const props = defineProps<Props>()
const emit = defineEmits<{ submit: [] }>()

const preview = ref<string | null>(null)
const usernameError = ref("")
const nameError = ref("")
const emailError = ref("")
const passwordError = ref("")
const typeuserError = ref("")

const typeUsers = [
  { label: "Tutor", value: "tutor" },
  { label: "Passeador", value: "passeador" }
]

function handleSubmit(): void {
  usernameError.value = !props.form.username ? "Insira um username!" : ""
  nameError.value = !props.form.name ? "Insira um nome!" : ""
  emailError.value = !props.form.email ? "Insira um e-mail!" : ""
  passwordError.value = !props.form.password ? "Insira uma senha!" : ""
  typeuserError.value = !props.form.type_user ? "Selecione um tipo de usuário!" : ""

  if (usernameError.value || nameError.value || emailError.value || passwordError.value || typeuserError.value) return

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
      <v-col cols="12">
        <BaseInput
          v-model="form.username"
          required
          label="Username"
          :error-message="usernameError"
          @update:modelValue="usernameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          v-model="form.name"
          required
          label="Nome Completo"
          :error-message="nameError"
          @update:modelValue="nameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          v-model="form.email"
          required
          label="Email"
          :error-message="emailError"
          @update:modelValue="emailError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          v-model="form.password"
          required
          type="password"
          label="Senha"
          :error-message="passwordError"
          @update:modelValue="passwordError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseSelect
          v-model="form.type_user"
          required
          label="Tipo de Usuário"
          :options="typeUsers"
          labelKey="label"
          valueKey="value"
          :error-message="typeuserError"
          @update:modelValue="typeuserError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          v-model="form.phone"
          label="📞 Telefone"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          type="file"
          accept="image/*"
          label="Foto"
          @update:modelValue="handlePhoto"
        />
      </v-col>
    </v-row>

    <v-row v-if="preview">
      <v-col cols="12" class="text-center">
        <v-img
          :src="preview"
          max-height="200"
          cover
          rounded="lg"
          class="mx-auto img-preview"
        />
      </v-col>
    </v-row>

    <v-row class="mt-2">
      <v-col cols="12">
        <BaseButton
          :label="labelButton"
          color="mustard"
          block
          @click="handleSubmit"
        />
      </v-col>
    </v-row>

  </v-container>
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
  max-width: 100%;
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
<script setup lang="ts">
import { ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"

interface UserAuthForm {
  email: string
  password: string
}

interface Props {
  form: UserAuthForm
  labelButton: string
}

const props = defineProps<Props>()
const emit = defineEmits<{ submit: [] }>()

const emailError = ref("")
const passwordError = ref("")

function handleSubmit(): void {
  emailError.value = !props.form.email ? "Insira um e-mail!" : ""
  passwordError.value = !props.form.password ? "Insira uma senha!" : ""
  if (emailError.value || passwordError.value) return
  emit("submit")
}
</script>

<template>
  <v-container class="pa-0">
    <v-row justify="center">
      <v-col cols="12" sm="8">
        <BaseInput
          v-model="form.email"
          required
          label="📧 Email"
          :error-message="emailError"
          @update:modelValue="emailError = ''"
        />
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-col cols="12" sm="8">
        <BaseInput
          v-model="form.password"
          required
          label="🔒 Senha"
          type="password"
          :error-message="passwordError"
          @update:modelValue="passwordError = ''"
        />
      </v-col>
    </v-row>

    <v-row justify="center" class="mt-2">
      <v-col cols="12" sm="8">
        <BaseButton
          :label="labelButton"
          class="btn-mustard"
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
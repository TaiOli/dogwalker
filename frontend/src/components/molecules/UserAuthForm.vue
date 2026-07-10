<script setup lang="ts">
import { ref } from "vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseInput from "../atoms/BaseInput.vue";

interface UserAuthForm {
  email: string
  password: string
}

interface UserAuthFormProps {
  form: UserAuthForm
  labelButton: string
}

const emailError = ref<string>("")
const passwordError = ref<string>("")

  function handleSubmit(): void {
  if (!props.form.email) {
    emailError.value = "Insira o email!"
    return
  } else {
    passwordError.value ="Insira a senha!"
  }

  emailError.value = ""
  passwordError.value = ""
  emit("submit")
}

const props = defineProps<UserAuthFormProps>();

const emit = defineEmits<{
  submit: []
}>();
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <label class="form-label text-start w-100">📧 Email</label>
      <BaseInput 
        v-model="form.email" 
        placeholder="Email" 
        :class="{ 'is-invalid': emailError }"
        @update:modelValue="emailError = ''"
      />
      <div v-if="emailError" class="text-danger text-start small mt-1">
        {{ emailError }}
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label text-start w-100">🔒 Senha</label>
      <BaseInput 
        v-model="form.password" 
        placeholder="Senha" 
        type="password" 
        :class="{ 'is-invalid': passwordError }"
        @update:modelValue="passwordError = ''"
      />
      <div v-if="passwordError" class="text-danger text-start small mt-1">
        {{ passwordError }}
      </div>
    </div>

    <BaseButton
      class="w-100 mt-2"
      :label="labelButton"
      @click="handleSubmit"
    />

  </div>
</template>

<style scoped>
.form {
  width: 100%;
}
</style>
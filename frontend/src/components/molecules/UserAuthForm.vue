<script setup lang="ts">
import { ref } from "vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseInput from "../atoms/BaseInput.vue";
import BaseLabel from "../atoms/BaseLabel.vue";

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
  emailError.value = !props.form.email ? "Insira um e-mail!" : ""
  passwordError.value = !props.form.password ? "Insira uma senha!" : ""

  if ( emailError.value || passwordError.value ) {
    return
  }

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
      <BaseLabel class="text-start w-100">
        📧 Email <span class="text-danger">*</span>
      </BaseLabel>
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
      <BaseLabel class="text-start w-100">
        🔒 Senha <span class="text-danger">*</span>
      </BaseLabel>
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
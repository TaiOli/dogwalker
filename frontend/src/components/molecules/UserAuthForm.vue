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
      <BaseLabel class="text-start w-100" required>
        📧 Email 
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
      <BaseLabel class="text-start w-100" required>
        🔒 Senha 
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
      class="w-100 mt-2 btn-mustard"
      :label="labelButton"
      @click="handleSubmit"
    />

  </div>
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
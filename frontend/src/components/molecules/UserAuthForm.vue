<script setup lang="ts">
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
const props = defineProps<UserAuthFormProps>();

const emit = defineEmits<{
  submit: []
}>();
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <label class="form-label text-start w-100">📧 Email</label>
      <BaseInput v-model="form.email" placeholder="Email" />
    </div>

    <div class="mb-3">
      <label class="form-label text-start w-100">🔒 Senha</label>
      <BaseInput v-model="form.password" placeholder="Senha" type="password" />
    </div>

    <BaseButton
      class="w-100 mt-2"
      :label="labelButton"
      @click="emit('submit')"
    />

  </div>
</template>

<style scoped>
.form {
  width: 100%;
}
</style>
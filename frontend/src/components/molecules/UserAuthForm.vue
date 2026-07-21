<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";

interface UserAuthForm {
  email: string;
  password: string;
}

interface Props {
  form: UserAuthForm;
  labelButton: string;
}

const props = defineProps<Props>();
const emit = defineEmits<{ submit: [] }>();

const emailError = ref("");
const passwordError = ref("");

function handleSubmit(): void {
  emailError.value = !props.form.email ? "Insira um e-mail!" : "";
  passwordError.value = !props.form.password ? "Insira uma senha!" : "";
  if (emailError.value || passwordError.value) return;
  emit("submit");
}
</script>

<template>
  <v-container class="pa-0">
    <v-row justify="center">
      <v-col cols="12" sm="8">
        <BaseInput
          v-model="form.email"
          required
          label="Email"
          prepend-inner-icon="mdi-email"
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
          label="Senha"
          prepend-inner-icon="mdi-lock"
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
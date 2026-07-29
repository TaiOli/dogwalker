<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";
import BaseTypography from "../atoms/BaseTypography.vue";

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
  <v-container class="pa-0 mx-auto">
    <v-row justify="center">
      <v-col cols="12" sm="8">
        <BaseInput
          v-model="form.email"
          required
          label="Email"
          prepend-inner-icon="mdi-email-outline"
          icon-color="primary mx-2"
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
          prepend-inner-icon="mdi-lock-outline"
          icon-color="primary mx-2"
          type="password"
          :error-message="passwordError"
          @update:modelValue="passwordError = ''"
        />
      </v-col>
    </v-row>
    <BaseTypography variant="subtitle-1">
      <router-link to="/recuperar-senha" class="text-decoration-none"
        >Esqueci a senha</router-link
      >
    </BaseTypography>

    <v-row justify="center" class="mt-2">
      <v-col cols="12" sm="8">
        <BaseButton
          :label="labelButton"
          class="btn-mustard"
          @click="handleSubmit"
        />
      </v-col>
    </v-row>
  </v-container>
</template>
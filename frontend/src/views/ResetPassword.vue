<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { api } from "../services/api";
import BaseInput from "../components/atoms/BaseInput.vue";
import BaseButton from "../components/atoms/BaseButton.vue";

interface ApiErrorResponse {
  response?: {
    data?: {
      message?: string;
    };
  };
}

const route = useRoute();
const router = useRouter();

const token = ref<string>("");
const email = ref<string>("");
const password = ref<string>("");
const passwordConfirmation = ref<string>("");

const passwordError = ref<string>("");
const confirmationError = ref<string>("");
const generalError = ref<string>("");

const sending = ref<boolean>(false);
const success = ref<boolean>(false);

onMounted(() => {
  token.value = String(route.query.token || "");
  email.value = String(route.query.email || "");

  if (!token.value || !email.value) {
    generalError.value = "Link de recuperação inválido ou expirado.";
  }
});

function validate(): boolean {
  passwordError.value = "";
  confirmationError.value = "";

  if (!password.value || password.value.length < 6) {
    passwordError.value = "A senha deve ter no mínimo 6 caracteres!";
  }

  if (password.value !== passwordConfirmation.value) {
    confirmationError.value = "As senhas não conferem!";
  }

  return !passwordError.value && !confirmationError.value;
}

async function handleSubmit(): Promise<void> {
  if (!token.value || !email.value) return;
  if (!validate()) return;

  sending.value = true;
  generalError.value = "";

  try {
    await api.post("/reset-password", {
      token: token.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });

    success.value = true;

    setTimeout(() => {
      router.push("/login");
    }, 2500);
  } catch (err) {
    console.error(err);
    const error = err as ApiErrorResponse;
    generalError.value =
      error.response?.data?.message || "Erro ao redefinir a senha.";
  } finally {
    sending.value = false;
  }
}
</script>

<template>
  <div class="page-wrapper">
    <v-card class="pa-6 card" max-width="440" elevation="3">
      <v-row>
        <v-col cols="12" class="text-center mb-2">
          <v-icon
            icon="mdi-lock-reset"
            color="primary"
            size="40"
            class="mb-2 mt-3"
          />
          <h2 class="text-black">Redefinir senha</h2>
          <p class="text-medium-emphasis text-body-2 text-black">
            Escolha uma nova senha para sua conta.
          </p>
        </v-col>
      </v-row>

      <v-alert
        v-if="generalError && !success"
        type="error"
        variant="tonal"
        class="mb-4"
      >
        {{ generalError }}
      </v-alert>

      <template v-if="!success">
        <v-row justify="center">
          <v-col cols="12" md="8">
            <BaseInput
              v-model="password"
              required
              type="password"
              label="Nova senha"
              :error-message="passwordError"
              @update:modelValue="passwordError = ''"
            />
          </v-col>
        </v-row>

        <v-row justify="center">
          <v-col cols="12" md="8">
            <BaseInput
              v-model="passwordConfirmation"
              required
              type="password"
              label="Confirmar nova senha"
              :error-message="confirmationError"
              @update:modelValue="confirmationError = ''"
            />
          </v-col>
        </v-row>

        <v-row class="mt-2 mb-3" justify="center">
          <v-col cols="12" md="8">
            <BaseButton
              :label="sending ? 'Salvando...' : 'Redefinir senha'"
              class="btn-mustard"
              :disabled="sending || !token || !email"
              @click="handleSubmit"
            />
          </v-col>
        </v-row>
      </template>

      <v-alert v-else type="success" variant="tonal" class="mt-2">
        Senha redefinida com sucesso! Redirecionando para o login...
      </v-alert>
    </v-card>
  </div>
</template>

<style scoped>
.page-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 16px;
}

.card {
  border-radius: 12px;
  background-color: white;
  font-size: 15px;
  width: 500px;
}
</style>

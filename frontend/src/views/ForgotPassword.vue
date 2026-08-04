<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { api } from "../services/api";
import BaseInput from "../components/atoms/BaseInput.vue";
import BaseButton from "../components/atoms/BaseButton.vue";
import BaseIcon from "../components/atoms/BaseIcon.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";
import { validateEmail } from "../utils/validations/userValidations";

interface ApiErrorResponse {
  response?: {
    data?: {
      message?: string;
    };
  };
}

const router = useRouter();

const email = ref<string>("");
const emailError = ref<string>("");
const sending = ref<boolean>(false);
const sent = ref<boolean>(false);

async function handleSubmit(): Promise<void> {
  emailError.value = validateEmail(email.value);
  if (emailError.value) return;

  sending.value = true;

  try {
    await api.post("/forgot-password", { email: email.value });
    sent.value = true;
  } catch (err) {
    console.error(err);
    const error = err as ApiErrorResponse;
    emailError.value =
      error.response?.data?.message || "Erro ao enviar o link de recuperação.";
  } finally {
    sending.value = false;
  }
}
</script>

<template>
  <div class="page-wrapper">
    <v-card class="pa-6 card w-100" max-width="440" elevation="3">
      <v-row>
        <v-col cols="12" class="text-center mb-2">
          <BaseIcon
            name="mdi-lock-reset"
            color="primary"
            size="40"
            class="mb-2 mt-3"
          />
          <BaseTypography variant="h2" weight="bold" class="text-title-large text-black mb-2">Recuperar senha</BaseTypography>
          <BaseTypography variant="overline" class="text-black">
            Digite seu e-mail para receber um link de redefinição
          </BaseTypography>
        </v-col>
      </v-row>

      <template v-if="!sent">
        <v-row justify="center">
          <v-col cols="12" md="8">
            <BaseInput
              v-model="email"
              required
              label="Email"
              :error-message="emailError"
              @update:modelValue="emailError = ''"
            />
          </v-col>
        </v-row>

        <v-row class="mt-2" justify="center">
          <v-col cols="12" md="8">
            <BaseButton
              :label="sending ? 'Enviando...' : 'Enviar link de recuperação'"
              class="btn-mustard"
              :disabled="sending"
              @click="handleSubmit"
            />
          </v-col>
        </v-row>
      </template>

      <v-alert v-else type="success" variant="tonal" class="mt-2">
        Se o e-mail informado estiver cadastrado, você receberá um link de
        recuperação em instantes.
      </v-alert>

      <v-row class="mt-4">
        <v-col cols="12" class="text-center mb-3">
          <BaseButton
            label="Voltar ao login"
            variant="text"
            class="text-primary text-decoration-none"
            @click="router.push('/login')"
          />
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<style scoped>
.card {
  border-radius: 12px;
  background-color: white;
  font-size: 15px;
}
</style>
<script setup lang="ts">
import { ref } from "vue";
import { useAuth } from "../composables/userAuth";
import { useRouter } from "vue-router";
import UserCadastroForm from "../components/molecules/UserRegisterForm.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";
import BaseIcon from "../components/atoms/BaseIcon.vue";
import BaseAlert from "../components/atoms/BaseAlert.vue";

const { formRegister, register, updateRegister, clearRegister } = useAuth();
const router = useRouter();

const alert = ref({
  show: false,
  type: "success" as "success" | "error",
  message: "",
});

function showFeedback(type: "success" | "error", message: string) {
  alert.value = { show: true, type, message };
}

async function salvar(): Promise<void> {
  try {
    if (formRegister.id) {
      await updateRegister();
      showFeedback("success", "Cadastro atualizado com sucesso!");
    } else {
      await register();
      showFeedback("success", "Usuário criado com sucesso!");
      setTimeout(() => router.push("/login"), 1500);
    }
    clearRegister();
  } catch (error) {
    console.error(error);
    showFeedback("error", "Erro ao salvar usuário.");
  }
}
</script>

<template>
  <v-container fluid class="page-user d-flex justify-center py-8">
    <BaseAlert v-model="alert.show" :type="alert.type" :text="alert.message" />

    <v-card
      class="register-card"
      elevation="4"
      color="white"
      rounded="xl"
      max-width="700"
      width="100%"
    >
      <v-card-text class="pa-8 mt-4 text-center">
        <BaseIcon
          name="mdi-paw-outline"
          size="56"
          color="primary"
          class="mb-2"
        />
        <BaseTypography
          variant="h2"
          weight="bold"
          class="mb-2 text-primary title"
          >Dog Walker</BaseTypography
        >
        <BaseTypography variant="subtitle-1" class="mb-4">
          {{ formRegister.id ? "Editar cadastro" : "Criar conta de usuário" }}
        </BaseTypography>

        <UserCadastroForm
          :form="formRegister"
          :labelButton="formRegister.id ? 'Atualizar' : 'Salvar'"
          @submit="salvar"
        />
      </v-card-text>
    </v-card>
  </v-container>
</template>
<style scoped>
.page-user {
  min-height: 100vh;
  width: 100%;
  background: #f4f6f8;
  display: flex;
  justify-content: center;
  padding: 40px 15px;
  overflow-y: auto;
}
</style>
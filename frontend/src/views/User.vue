<script setup lang="ts">
import { useAuth } from "../composables/userAuth";
import { useRouter } from "vue-router";
import UserCadastroForm from "../components/molecules/UserRegisterForm.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";

const { formRegister, register, updateRegister, clearRegister } = useAuth();
const router = useRouter();

async function salvar(): Promise<void> {
  try {
    if (formRegister.id) {
      await updateRegister();
      alert("Cadastro atualizado com sucesso!");
    } else {
      await register();
      alert("Usuário criado com sucesso!");
      router.push('/login');
    }
    clearRegister();
  } catch (error) {
    console.error(error);
    alert("Erro ao salvar usuário");
  }
}
</script>

<template>
  <v-container fluid class="page-user d-flex justify-center py-8">
    <v-card
      class="register-card"
      elevation="4"
      color="white"
      rounded="xl"
      max-width="700"
      width="100%"
    >
      <v-card-text class="pa-8 mt-4 text-center">
        <BaseIco name="mdi-paw-outline" size="56" color="primary" class="mb-2"/>
        <BaseTypography variant="h2" weight="bold" class="mb-2 text-primary title">Dog Walker</BaseTypography>

        <BaseTypography variant="subtitle-1"  class="mb-4">
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

.register-wrapper {
  width: 100%;
  max-width: 650px;
}
</style>
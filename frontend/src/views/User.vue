<script setup lang="ts">
import { useAuth } from "../composables/userAuth"
import UserCadastroForm from "../components/molecules/UserRegisterForm.vue"

const { formRegister, register, updateRegister, clearRegister } = useAuth()

async function salvar(): Promise<void> {
  try {
    if (formRegister.id) {
      await updateRegister()
      alert("Cadastro atualizado com sucesso!")
    } else {
      await register()
      alert("Usuário criado com sucesso!")
    }
    clearRegister()
  } catch (error) {
    console.error(error)
    alert("Erro ao salvar usuário")
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
        <v-icon size="56" color="primary" class="mb-2">mdi-dog</v-icon>
        <h2 class="mb-2 text-primary title">Dog Walker</h2>

        <p class="text-medium-emphasis mb-4">
          {{ formRegister.id ? "Editar cadastro" : "Criar conta de usuário" }}
        </p>

        <UserCadastroForm
          :form="formRegister"
          :labelButton="formRegister.id ? 'Atualizar' : 'Salvar'"
          @submit="salvar"
        />

        <p class="mt-4">
          <router-link to="/login" class="back-link">Voltar</router-link>
        </p>
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

h2 {
  font-size: 28px;
}

.back-link {
  text-decoration: none;
  font-weight: 700;
}
 
.back-link:hover {
  text-decoration: underline;
}
</style>
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
  <div class="page-user">

    <div class="cadastro-wrapper text-center">

      <h2>🐶 Dog Walker</h2>

      <p class="text-muted mb-4">
         {{ formRegister.id ? "Editar cadastro" : "Criar conta de usuário" }}
      </p>

      <UserCadastroForm
        :form="formRegister"
        :labelButton="formRegister.id ? 'Atualizar' : 'Salvar'"
        @submit="salvar"
      />

      <p class="mt-3">
        <router-link to="/login" class="text-decoration-none fw-bold text-success">
          Voltar
        </router-link>
      </p>

    </div>

  </div>
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

.cadastro-wrapper {
  width: 100%;
  max-width: 650px;
}

h2 {
  font-size: 28px;
}
</style>
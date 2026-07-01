<script setup>
import { useAuth } from "../composables/userAuth";
import UserCadastroForm from "../components/molecules/UserCadastroForm.vue";

const { formCadastro, cadastrar, clearCadastro } = useAuth();

async function novo() {
  try {
    await cadastrar();
    alert("Usuário criado com sucesso!");

    clearCadastro();
  } catch (error) {
    alert("Erro ao criar usuário");
  }
}
</script>

<template>
  <div class="page">

    <div class="cadastro-wrapper text-center">

      <h2>🐶 Dog Walker</h2>

      <p class="text-muted mb-4">
        Criar conta de usuário
      </p>

      <UserCadastroForm
        :form="formCadastro"
        labelButton="Salvar"
        @submit="novo"
      />

      <p class="mt-3">
        <router-link to="/" class="text-decoration-none fw-bold text-success">
          Voltar
        </router-link>
      </p>

    </div>

  </div>
</template>

<style scoped>
.page {
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
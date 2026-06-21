<template>
  <div class="container">
    <div class="content">
      <h2>🐶 Dog Walker</h2>
      <p>Criar conta de usuário</p>

      <UserCadastroForm
        :form="formCadastro"
        labelButton="Salvar"
        @submit="novo"
      />
    </div>
  </div>
</template>

<script setup>
import UserCadastroForm from "../components/molecules/UserCadastroForm.vue";
import { useAuth } from "../composables/userAuth";

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

<style scoped>
.container {
  height: 100vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f4f6f8;
}

.content {
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  text-align: center;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 15px;
}

h2 {
  margin: 0;
  font-size: 28px;
  color: #2c3e50;
}

p {
  margin: 5px 0 25px;
  color: #7f8c8d;
  font-size: 14px;
}

.link a {
  color: #42b983;
  font-weight: bold;
  text-decoration: none;
}
</style>
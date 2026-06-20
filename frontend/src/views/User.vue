<template>
  <div class="container">
    <div class="content">
      <h2>🐶 Dog Walker</h2>
      <p>Criar conta de usuário</p>

      <div class="form">
        <input v-model="form.name" placeholder="Nome de usuário" />
        <input v-model="form.nome" placeholder="Nome completo" />
        <input v-model="form.email" placeholder="Email" />
        <input v-model="form.password" placeholder="Senha" type="password" />
      </div>

      <button @click="novo">Salvar</button>
      <p class="link">
          <router-link to="/">Voltar</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
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

input {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
  background: white;
}

input:focus {
  border-color: #42b983;
}

button {
  width: 100%;
  padding: 10px;
  border: none;
  background: #42b983;
  color: white;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

button:hover {
  background: #369b6f;
}

.link a {
  color: #42b983;
  font-weight: bold;
  text-decoration: none;
}
</style>
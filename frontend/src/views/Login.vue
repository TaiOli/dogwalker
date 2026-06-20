<template>
  <div class="container">
    <div class="card">
      
      <h2>🐶 Dog Walker</h2>
      <p>Entrar no sistema</p>

      <div class="form">
        <input v-model="form.email" placeholder="Email" />
        <input v-model="form.password" type="password" placeholder="Senha" />

        <button @click="login">Entrar</button>

        <p class="link">
          Não tem conta?
          <router-link to="/cadastro-usuario">Criar conta</router-link>
        </p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { api } from "../services/api";

const form = reactive({
  email: "",
  password: ""
});

async function login() {
  try {
    const res = await api.post("/login", form);

    alert("Login realizado com sucesso!");

    console.log(res.data.user);

  } catch (error) {
    console.log(error);
    alert("Email ou senha inválidos");
  }
}
</script>

<style scoped>
.container {
  min-height: 100vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f4f6f8;
}

.card {
  width: 100%;
  max-width: 380px;
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.1);
  text-align: center;
}

h2 {
  margin: 0;
  font-size: 28px;
  color: #2c3e50;
}

p {
  color: #7f8c8d;
  font-size: 15px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin: 20px 0;
}

input {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
}

input:focus {
  border-color: #42b983;
}

button {
  width: 100%;
  padding: 12px;
  border: none;
  background: #42b983;
  color: white;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background: #369b6f;
}

.link {
  margin-top: 10px;
  font-size: 14px;
  color: #7f8c8d;
}

.link a {
  color: #42b983;
  font-weight: bold;
  text-decoration: none;
}

.link a:hover {
  text-decoration: underline;
}
</style>
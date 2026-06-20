<template>
  <div class="container">
    <div class="card">
      <h2>🐶 Dog Walker</h2>
      <p>Entrar no sistema</p>

      <div class="form">
        <input v-model="formLogin.email" placeholder="Email" />
        <input v-model="formLogin.password" type="password" placeholder="Senha" />

        <button @click="acessoLogin">Entrar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuth } from "../composables/userAuth";
const { formLogin, login, clearLogin } = useAuth();

async function acessoLogin() {
  try {
    const data = await login();
    alert("Login realizado com sucesso!");
    console.log(data.user);

    clearLogin();
  } catch (error) {
    alert("Email ou senha inválidos");
  }
}
</script>
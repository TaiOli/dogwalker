<template>
  <div class="container">
    <div class="card">

      <h2>🐶 Dog Walker</h2>
      <p>Entrar no sistema</p>

      <UserAuthForm
        :form="formLogin"
        labelButton="Entrar"
        @submit="acessoLogin"
      />

      <p class="link">
        Não tem conta?
        <router-link to="/cadastro-usuario">Criar conta</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { useAuth } from "../composables/userAuth";
import UserAuthForm from "../components/molecules/UserAuthForm.vue";
import { useRouter } from "vue-router";

const router = useRouter(); 

const { formLogin, login, clearLogin } = useAuth();

async function acessoLogin() {
  try {
    const data = await login();

    alert("Login realizado com sucesso!");
    console.log(data.user);

    localStorage.setItem("user", JSON.stringify(data.user));

    clearLogin(); 
    router.push("/home"); 
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
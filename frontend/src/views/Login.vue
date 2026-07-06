
<script setup>
import { useAuth } from "../composables/userAuth";
import UserAuthForm from "../components/molecules/UserAuthForm.vue";
import { useRouter } from "vue-router";

const router = useRouter();

const { formLogin, login, clearLogin } = useAuth();

async function loginAccess() {
  try {
    const data = await login();

    alert("Login realizado com sucesso!");

    localStorage.setItem("user", JSON.stringify(data.user));
    localStorage.setItem("token", data.token);

    clearLogin();

    router.push("/home");
  } catch (error) {
    console.log(error);
    alert("Email ou senha inválidos");
  }
}
</script>

<template>
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
    
    <div class="login-card shadow p-4">
      
      <div class="card-body text-center">

        <h2 class="mb-3">🐶 Dog Walker</h2>

        <p class="text-muted mb-4">
          Entrar no sistema
        </p>

        <UserAuthForm
          :form="formLogin"
          labelButton="Entrar"
          @submit="loginAccess"
        />

        <p class="mt-3 text-muted">
          Não tem conta?
         <router-link to="/cadastro-usuario" class="text-decoration-none fw-bold">
            Criar conta
         </router-link>
        </p>

      </div>

    </div>

  </div>
</template>

<style scoped>
.login-card {
  width: 100%;
  max-width: 550px;
}
</style>
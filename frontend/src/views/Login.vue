<script setup lang="ts">
import { useAuth } from "../composables/userAuth";
import UserAuthForm from "../components/molecules/UserAuthForm.vue";
import { useRouter } from "vue-router";

interface LoginResponse {
  user: {
    id: number;
    name: string;
    login: string;
    email: string;
    tipo_usuario: "tutor" | "passeador";
    telefone?: string;
    foto?: string;
  };
  token: string;
}

interface ApiErrorResponse {
  message?: string;
  errors?: Record<string, string[]>;
}

const router = useRouter();

const { formLogin, login, clearLogin } = useAuth();

async function loginAccess(): Promise<void> {
  try {
    const data = (await login()) as LoginResponse;

    alert("Login realizado com sucesso!");

    localStorage.setItem("user", JSON.stringify(data.user));
    localStorage.setItem("token", data.token);

    clearLogin();

    router.push("/dashboard");
  } catch (error) {
    const err = error as { response?: { data?: ApiErrorResponse } };

    console.log(err);

    const firstFieldError = err.response?.data?.errors
      ? Object.values(err.response.data.errors)[0]?.[0]
      : null;

    alert(firstFieldError || err.response?.data?.message || "Email ou senha inválidos");
  }
}
</script>

<template>
  <v-container fluid class="fill-height d-flex justify-center align-center">
 
    <v-card  class="login-card pa-4" elevation="4" color="white" rounded="xl">
 
      <div class="text-center">
 
        <h2 class="mb-3">🐶 Dog Walker</h2>
 
        <p class="text-medium-emphasis mb-4">
          Entrar no sistema
        </p>
 
        <UserAuthForm
          :form="formLogin"
          labelButton="Entrar"
          @submit="loginAccess"
        />
 
        <p class="mt-3">
          Não tem conta?
         <router-link 
            to="/cadastro-usuario" 
            class="signup-link"
          >
            Criar conta
         </router-link>
        </p>
 
      </div>
 
    </v-card>
 
  </v-container>
</template>

<style scoped>
.login-card {
  width: 100%;
  max-width: 550px;
  padding: 20px; 
}

.signup-link {
  text-decoration: none;
  font-weight: 700;
}
 
.signup-link:hover {
  text-decoration: underline;
}
</style>
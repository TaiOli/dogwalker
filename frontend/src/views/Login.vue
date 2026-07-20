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
  <v-container fluid class="fill-height d-flex justify-center align-center bg-grey-lighten-4">
    <v-card
      class="login-card"
      elevation="4"
      color="white"
      rounded="xl"
      max-width="420"
      width="100%"
    >

      <v-card-text class="pa-6 text-center">
        <v-icon size="56" color="primary" class="mb-3"> mdi-dog</v-icon>
        <h2 class="mb-2 text-primary">Dog Walker</h2>

        <p class="text-medium-emphasis mb-6">Entrar no sistema</p>
        
        <UserAuthForm
          :form="formLogin"
          labelButton="Entrar"
          @submit="loginAccess"
        />

        <p class="mt-4">
          Não tem conta?
          <router-link to="/cadastro-usuario" class="signup-link">Criar conta</router-link>
        </p>
      </v-card-text>
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
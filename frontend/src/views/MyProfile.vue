<script setup lang="ts">
import { ref, onMounted } from "vue"
import { api } from "../services/api"
import { getPhoto } from "../utils/image"
import { useRouter } from "vue-router"
import { useAuth } from "../composables/userAuth"
import BaseButton from "../components/atoms/BaseButton.vue"

interface User {
  id: number
  nome: string
  email: string
  telefone?: string
  foto?: string
  tipo_usuario: string
}

const user = ref<User | null>(null)
const loading = ref<boolean>(true)
const router = useRouter()

const { setRegister } = useAuth()

function editProfile(): void {
  if (!user.value) return
  setRegister(user.value)
  
  router.push("/usuario/editar")
}

onMounted(async () => {
  try {
    const res = await api.get("/me")
    user.value = res.data
  } catch (err) {
    console.error("Erro ao carregar perfil", err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="container py-5">

    <div v-if="!loading && user" class="card p-4 shadow-sm text-center">

      <div class="d-flex justify-center mb-3">
        <v-img
          :src="getPhoto(user?.foto)"
          width="120"
          height="120"
          max-width="120"
          cover
          class="rounded-circle"
        />
      </div>

      <h3>{{ user.nome }}</h3>

      <p class="d-flex justify-center align-center ga-2">
        <v-icon color="primary" size="18">
          mdi-email-outline
        </v-icon>

        {{ user.email }}
      </p>

      <p class="d-flex justify-center align-center ga-2">
        <v-icon color="primary" size="18">
          mdi-phone-outline
        </v-icon>

        {{ user.telefone }}
      </p>

      <p class="d-flex justify-center align-center ga-2">
        <v-icon color="primary" size="18">
          mdi-account-outline
        </v-icon>

        Tipo: {{ user.tipo_usuario }}
      </p>

      <BaseButton
        class="btn btn-primary mt-3 d-block mx-auto"
        label="Editar Cadastro"
        @click="editProfile"
      />

    </div>

    <div v-else class="text-center py-5">
      Carregando...
    </div>

  </div>
</template>

<style scoped>
.btn-primary{
   width: 30%;
}
</style>
<script setup>
import { ref, onMounted } from "vue"
import { api } from "../services/api"
import { getPhoto } from "../utils/image"

const user = ref(null)
const loading = ref(true)

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

      <img
        :src="getPhoto(user?.foto)"
        class="rounded-circle mb-3"
        width="120"
        height="120"
      />

      <h3>{{ user.nome }}</h3>

      <p>📧 {{ user.email }}</p>
      <p>📱 {{ user.telefone }}</p>

      <p>👤 Tipo: {{ user.tipo_usuario }}</p>

    </div>

    <div v-else class="text-center py-5">
      Carregando...
    </div>

  </div>
</template>
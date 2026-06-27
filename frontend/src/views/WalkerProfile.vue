<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 text-center">

          <img
            :src="getFoto(walker.foto)"
            class="profile-photo mx-auto mb-3"
            alt="Foto do passeador"
          >

          <h2>{{ walker.nome }}</h2>

          <p class="text-muted mb-4">
            Passeador
          </p>

          <hr>

          <div class="text-start">
            <p>
              <strong>📧 Email:</strong><br>
              {{ walker.email }}
            </p>

            <p>
              <strong>📱 Telefone:</strong><br>
              {{ walker.telefone }}
            </p>

            <p>
              <strong>⭐ Avaliação:</strong><br>
              {{ walker.media_avaliacao ?? "Sem avaliações" }}
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRoute } from "vue-router"
import { api } from "../services/api"
import { getFoto } from "../utils/image"

const route = useRoute()

const walker = ref({})

onMounted(async () => {
  const response = await api.get(`/walkers/${route.params.id}`)
  walker.value = response.data
})
</script>

<style scoped>
.profile-photo{
  width:150px;
  height:150px;
  border-radius:50%;
  object-fit:cover;
  border:4px solid #198754;
}
</style>
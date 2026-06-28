<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 text-center">

          <img
            :src="getFoto(tutor.foto)"
            class="profile-photo mx-auto mb-3"
            alt="Foto do tutor"
          >

          <h2>{{ tutor.nome }}</h2>

          <p class="text-muted mb-4">
            Tutor
          </p>

          <hr>

          <div class="text-start">
            <p>
              <strong>📧 Email:</strong><br>
              {{ tutor.email }}
            </p>

            <p>
              <strong>📱 Telefone:</strong><br>
              {{ tutor.telefone }}
            </p>
          </div>

          <hr>

          <div class="text-start">
            <h5 class="mb-3">💬 Avaliações de Passeadores</h5>

            <div v-if="!avaliacoes.length" class="alert alert-info">
              Este tutor ainda não recebeu avaliações.
            </div>

            <div
              v-else
              v-for="av in avaliacoes"
              :key="av.id"
              class="avaliacao-item mb-3"
            >
              <div class="mb-1">
                <span v-for="n in 5" :key="n">
                  {{ n <= av.nota ? "⭐" : "☆" }}
                </span>
                <span class="text-muted small ms-1">({{ av.nota }}/5)</span>
              </div>

              <p v-if="av.comentario" class="mb-1 fst-italic">
                "{{ av.comentario }}"
              </p>

              <p class="text-muted small mb-0">
                — {{ av.passeador?.nome ?? "Passeador" }} em {{ formatarData(av.created_at) }}
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import { useRoute } from "vue-router"
import { api } from "../services/api"
import { getFoto } from "../utils/image"

const route = useRoute()

const tutor = ref({})

// Avaliações que passeadores fizeram sobre esse tutor (já filtradas no backend por tipo_avaliador=passeador)
const avaliacoes = computed(() => tutor.value.avaliacoes_feitas ?? [])

function formatarData(data) {
  if (!data) return ""
  const date = new Date(data)
  return date.toLocaleDateString("pt-BR")
}

onMounted(async () => {
  const response = await api.get(`/tutors/${route.params.id}`)
  tutor.value = response.data
})
</script>

<style scoped>
.profile-photo {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #3498db;
}

.avaliacao-item {
  background: #f0f9ff;
  border-left: 3px solid #3498db;
  border-radius: 8px;
  padding: 12px;
}

.fst-italic {
  font-style: italic;
  color: #555;
}

.ms-1 {
  margin-left: 4px;
}

.small {
  font-size: 12px;
}
</style>
<script setup lang="ts">
import { ref, computed, onMounted } from "vue"
import { useRoute } from "vue-router"
import { api } from "../services/api"
import { getPhoto } from "../utils/image"

interface Review {
  id: number
  nota: number
  comentario?: string | null
  created_at: string
  tutor?: {
    id: number
    nome: string
  }
}

interface Walker {
  id: number
  nome: string
  email: string
  telefone?: string
  foto?: string
  received_reviews?: Review[]
}

const route = useRoute()

const walker = ref<Walker | Record<string, never>>({})

// Lista de avaliações recebidas pelo passeador (já filtradas por tipo_avaliador=tutor)
const evaluations = computed<Review[]>(() => (walker.value as Walker).received_reviews ?? [])

function formatDate(data: string | null | undefined): string {
  if (!data) return ""
  const date = new Date(data)
  return date.toLocaleDateString("pt-BR")
}

onMounted(async () => {
  const response = await api.get(`/walkers/${route.params.id}`)
  walker.value = response.data
})
</script>

<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 text-center">

          <v-img
            :src="getPhoto(walker.foto)"
            class="profile-photo mx-auto mb-3"
            alt="Foto do passeador"
          ></v-img>

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
          </div>

          <hr>

          <div class="text-start">
            <h5 class="mb-3">💬 Avaliações de Tutores</h5>

            <div v-if="!evaluations.length" class="alert alert-info">
              Este passeador ainda não recebeu avaliações.
            </div>

            <div
              v-else
              v-for="av in evaluations"
              :key="av.id"
              class="evaluation-item mb-3"
            >
              <div class="mb-1">
                <span v-for="n in 5" :key="n">
                  {{ n <= av.nota ? "⭐" : "☆" }}
                </span>
                <v-text-field class="text-muted small ms-1">({{ av.nota }}/5)</v-text-field>
              </div>

              <p v-if="av.comentario" class="mb-1 fst-italic">
                "{{ av.comentario }}"
              </p>

              <p class="text-muted small mb-0">
                — {{ av.tutor?.nome ?? "Tutor" }} em {{ formatDate(av.created_at) }}
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.profile-photo {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #198754;
}

.evaluation-item {
  background: #f0f9ff;
  border-left: 3px solid #198754;
  border-radius: 8px;
  padding: 12px;
}
</style>
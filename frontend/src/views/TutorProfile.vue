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
  passeador?: {
    id: number
    nome: string
  }
}

interface Tutor {
  id: number
  nome: string
  email: string
  telefone?: string
  foto?: string
  submitted_reviews?: Review[]
}

const route = useRoute()

const tutor = ref<Tutor | Record<string, never>>({})

// Avaliações que passeadores fizeram sobre esse tutor (já filtradas por tipo_avaliador=passeador)
const evaluations = computed<Review[]>(() => (tutor.value as Tutor).submitted_reviews ?? [])

function formatDate(data: string | null | undefined): string {
  if (!data) return ""
  const date = new Date(data)
  return date.toLocaleDateString("pt-BR")
}

onMounted(async () => {
  const response = await api.get(`/tutors/${route.params.id}`)
  tutor.value = response.data
})
</script>

<template>
  <v-container>
    <v-row class="justify-center">
      <v-col cols="12" md="6">

          <v-img
            :src="getPhoto(tutor.foto)"
            class="profile-photo mx-auto mb-3"
            alt="Foto do tutor">
          </v-img>

          <h2>{{ tutor.nome }}</h2>

          <p class="text-medium-emphasis mb-4">
            Tutor
          </p>

          <v-divider class="mb-4" />

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

          <v-divider class="my-4" />

          <div class="text-start">
            <h5 class="mb-3">💬 Avaliações de Passeadores</h5>

            <v-alert v-if="!evaluations.length" type="info" variant="tonal">
              Este tutor ainda não recebeu avaliações.
            </v-alert>

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
                <span class="text-medium-emphasis text-caption ms-1">({{ av.nota }}/5)</span>
              </div>

              <p v-if="av.comentario" class="mb-1 font-italic">
                "{{ av.comentario }}"
              </p>

              <p class="text-medium-emphasis text-caption mb-0">
                — {{ av.passeador?.nome ?? "Passeador" }} em {{ formatDate(av.created_at) }}
              </p>
            </div>
          </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.profile-photo {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #3498db;
}

.evaluation-item {
  background: #f0f9ff;
  border-left: 3px solid #3498db;
  border-radius: 8px;
  padding: 12px;
}
</style>
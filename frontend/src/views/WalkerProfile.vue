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
<v-container class="py-6">

  <v-row justify="center">
    <v-col cols="12" md="8" lg="6">

      <v-card
        elevation="3"
        rounded="xl"
        class="pa-6"
        color="white"
      >

        <div class="text-center mt-3">

          <v-img
            :src="getPhoto(walker.foto)"
            width="120"
            height="120"
            cover
            class="mx-auto rounded-circle mb-4"
          />

          <h3 class="text-h5 font-weight-bold">
            {{ walker.nome }}
          </h3>

          <v-chip
            color="primary"
            variant="tonal"
            size="small"
            class="text-capitalize ext-white text-caption font-weight-medium px-4 mt-2"
          >
            Passeador
          </v-chip>

        </div>

        <v-divider class="my-5" />

        <p>
          <strong>📧 Email:</strong><br>
          {{ walker.email }}
        </p>

        <p>
          <strong>📱 Telefone:</strong><br>
          {{ walker.telefone }}
        </p>

        <v-divider class="my-5" />

        <div class="d-flex justify-center align-center ga-2 mb-4">
          <v-icon color="primary">
            mdi-star
          </v-icon>

          <h4 class="text-h6 mb-0">
            Avaliações de Tutores
          </h4>
        </div>

        <v-alert
          v-if="!evaluations.length"
          type="info"
          variant="tonal"
        >
          Este tutor ainda não recebeu avaliações.
        </v-alert>

        <div
          v-else
          v-for="av in evaluations"
          :key="av.id"
          class="evaluation-item mb-4"
        >
          <div class="mb-1">
            <span v-for="n in 5" :key="n">
              {{ n <= av.nota ? "⭐" : "☆" }}
            </span>

            <span class="text-medium-emphasis text-caption ms-1">
              ({{ av.nota }}/5)
            </span>
          </div>

          <p v-if="av.comentario" class="mb-1 font-italic">
            "{{ av.comentario }}"
          </p>

          <p class="text-medium-emphasis text-caption mb-0">
            — {{ av.tutor?.nome ?? "Tutor" }}
            em {{ formatDate(av.created_at) }}
          </p>

          <v-divider class="mt-3" />
        </div>

      </v-card>
    </v-col>
  </v-row>
</v-container>
      
</template>

<style scoped>
.profile-photo {
  border: 4px solid #198754;
}

.evaluation-item {
  background: #f0f9ff;
  border-left: 3px solid #198754;
}
</style>
<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { api } from "../services/api";
import { getPhoto } from "../utils/image";

interface Review {
  id: number;
  nota: number;
  comentario?: string | null;
  created_at: string;
  passeador?: {
    id: number;
    nome: string;
  };
}

interface Tutor {
  id: number;
  nome: string;
  email: string;
  telefone?: string;
  foto?: string;
  submitted_reviews?: Review[];
}

const route = useRoute();

const tutor = ref<Tutor | Record<string, never>>({});

// Avaliações que passeadores fizeram sobre esse tutor (já filtradas por tipo_avaliador=passeador)
const evaluations = computed<Review[]>(
  () => (tutor.value as Tutor).submitted_reviews ?? [],
);

function formatDate(data: string | null | undefined): string {
  if (!data) return "";
  const date = new Date(data);
  return date.toLocaleDateString("pt-BR");
}

onMounted(async () => {
  const response = await api.get(`/tutors/${route.params.id}`);
  tutor.value = response.data;
});
</script>

<template>
  <v-container class="py-6">
    <v-row justify="center">
      <v-col cols="12" md="8" lg="6">
        <v-card elevation="3" rounded="xl" class="pa-6" color="white">
          <div class="text-center">
            <v-img
              :src="getPhoto(tutor.foto)"
              width="120"
              height="120"
              cover
              alt="Foto Perfil"
              class="mx-auto rounded-circle mb-4 mt-3"
            />

            <h3 class="text-h5 font-weight-bold">
              {{ tutor.nome }}
            </h3>

            <v-chip
              color="primary"
              size="small"
              variant="tonal"
              class="text-capitalize ext-white text-caption font-weight-medium px-4 mt-2"
            >
              Tutor
            </v-chip>
          </div>

          <v-divider class="my-5" />

          <div class="text-center">
            <p class="d-flex justify-center align-center ga-2 mb-2">
              <v-icon color="primary" size="18">mdi-email-outline</v-icon>
              {{ tutor.email }}
            </p>

            <p class="d-flex justify-center align-center ga-2 mb-0">
              <v-icon color="primary" size="18">mdi-phone-outline</v-icon>
              {{ tutor.telefone }}
            </p>
          </div>

          <v-divider class="my-5" />

          <div class="d-flex justify-center align-center ga-2 mb-4">
            <v-icon color="amber" size="24">mdi-star</v-icon>
            <span class="text-h6 font-weight-bold">
              Avaliações de Passeadores
            </span>
          </div>

          <v-alert v-if="!evaluations.length" type="info" variant="tonal">
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
                <v-icon
                  :icon="n <= av.nota ? 'mdi-star' : 'mdi-star-outline'"
                  color="amber"
                  size="20"
                />
              </span>
              <span class="text-medium-emphasis text-caption ms-1"
                >({{ av.nota }}/5)</span
              >
            </div>

            <p v-if="av.comentario" class="mb-1 font-italic">
              "{{ av.comentario }}"
            </p>

            <p class="text-medium-emphasis text-caption mb-0">
              — {{ av.passeador?.nome ?? "Passeador" }} em
              {{ formatDate(av.created_at) }}
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
  border: 4px solid #3498db;
}

.evaluation-item {
  background: #f0f9ff;
  border-left: 3px solid #3498db;
}
</style>
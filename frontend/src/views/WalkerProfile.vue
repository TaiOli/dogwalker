<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { api } from "../services/api";
import { getPhoto } from "../utils/image";
import BaseIcon from "../components/atoms/BaseIcon.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";

interface Review {
  id: number;
  nota: number;
  comentario?: string | null;
  created_at: string;
  tutor?: {
    id: number;
    nome: string;
  };
}

interface Walker {
  id: number;
  nome: string;
  email: string;
  telefone?: string;
  foto?: string;
  received_reviews?: Review[];
}

const route = useRoute();

const walker = ref<Walker | Record<string, never>>({});

// Lista de avaliações recebidas pelo passeador (já filtradas por tipo_avaliador=tutor)
const evaluations = computed<Review[]>(
  () => (walker.value as Walker).received_reviews ?? [],
);

function formatDate(data: string | null | undefined): string {
  if (!data) return "";
  const date = new Date(data);
  return date.toLocaleDateString("pt-BR");
}

onMounted(async () => {
  const response = await api.get(`/walkers/${route.params.id}`);
  walker.value = response.data;
});
</script>

<template>
  <v-container class="py-6">
    <v-row justify="center">
      <v-col cols="12" md="8" lg="6">
        <v-card elevation="3" rounded="xl" class="pa-6" color="white">
          <div class="text-center mt-3">
            <v-img
              :src="getPhoto(walker.foto)"
              width="120"
              height="120"
              cover
              alt="Foto Perfil"
              class="mx-auto rounded-circle mb-4"
            />

            <BaseTypography variant="h3" weight="bold">{{
              walker.nome
            }}</BaseTypography>

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

          <BaseTypography variant="subtitle-1" class="mb-4">
            <BaseTypography variant="body-1" weight="bold">
              <BaseIcon name="mdi-email-outline"  size="18" color="primary" />
              Email:
            </BaseTypography>
            {{ walker.email }}
          </BaseTypography>

          <BaseTypography variant="subtitle-1">
            <BaseTypography variant="body-1" weight="bold">
              <BaseIcon name="mdi-cellphone" size="18" color="primary" />
              Telefone:
            </BaseTypography>
            {{ walker.telefone }}
          </BaseTypography>

          <v-divider class="my-5" />

          <div class="d-flex justify-center align-center ga-2 mb-4">
            <BaseIcon name="mdi-star" size="22" color="amber" />
            <BaseTypography variant="h4" class="mb-0"
              >Avaliações de Tutores</BaseTypography
            >
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
                <BaseIcon
                  :name="n <= av.nota ? 'mdi-star' : 'mdi-star-outline'"
                  color="amber"
                  size="20"
                />
              </span>
              <span class="text-medium-emphasis text-caption ms-1"
                >({{ av.nota }}/5)</span
              >
            </div>

            <BaseTypography
              variant="subtitle-1"
              v-if="av.comentario"
              class="mb-1 font-italic"
            >
              "{{ av.comentario }}"
            </BaseTypography>

            <BaseTypography variant="subtitle-1" class="mb-0">
              — {{ av.tutor?.nome ?? "Tutor" }} em
              {{ formatDate(av.created_at) }}
            </BaseTypography>

            <v-divider class="mt-3" />
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.evaluation-item {
  background: #f0f9ff;
  border-left: 3px solid #198754;
}
</style>
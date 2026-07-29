<script setup lang="ts">
import { onMounted, computed, ref } from "vue";
import { useWalks } from "../composables/useWalks";
import BaseButton from "../components/atoms/BaseButton.vue";
import BaseIcon from "../components/atoms/BaseIcon.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";

interface Dog {
  id: number;
  nome: string;
}

interface Tutor {
  id: number;
  nome: string;
}

type TourStatus =
  | "pendente"
  | "aceito"
  | "recusado"
  | "finalizado"
  | "cancelado";

interface Tour {
  id: number;
  data: string;
  hora: string;
  local: string;
  status?: TourStatus;
  dog?: Dog;
  tutor?: Tutor;
}

interface ApiErrorResponse {
  response?: {
    data?: {
      message?: string;
    };
  };
}

const { tours, loadTours, tourAccept, tourReject } = useWalks();

const loadId = ref<number | null>(null);

const availableTours = computed<Tour[]>(() =>
  tours.value.filter((p: Tour) => !p.status || p.status === "pendente"),
);

onMounted(loadTours);

async function accept(id: number): Promise<void> {
  loadId.value = id;

  try {
    await tourAccept(id);

    tours.value = tours.value.filter((p: Tour) => p.id !== id);
  } catch (err) {
    console.error("Erro ao aceitar:", err);
    const error = err as ApiErrorResponse;
    alert(error.response?.data?.message || "Erro ao aceitar passeio");
  } finally {
    loadId.value = null;
  }
}

async function reject(id: number): Promise<void> {
  const confirmar = confirm("Tem certeza que deseja recusar este passeio?");

  if (!confirmar) return;

  loadId.value = id;

  try {
    await tourReject(id);

    tours.value = tours.value.filter((p: Tour) => p.id !== id);
  } catch (err) {
    console.error("Erro ao recusar:", err);
    const error = err as ApiErrorResponse;
    alert(error.response?.data?.message || "Erro ao recusar passeio");
  } finally {
    loadId.value = null;
  }
}
</script>

<template>
  <v-container class="py-4">
    <div class="d-flex align-center ga-2 mb-4">
      <BaseIcon name="mdi-walk" color="primary" />
      <BaseTypography variant="h2" class="title">Passeios Disponíveis</BaseTypography>
    </div>

    <v-alert
      v-if="availableTours.length === 0"
      type="info"
      variant="tonal"
      class="mb-4"
    >
      Nenhum passeio disponível.
    </v-alert>

    <v-row gap="1.5rem">
      <v-col
        v-for="p in availableTours"
        :key="p.id"
        cols="12"
        md="6"
        lg="4"
        xl="4"
      >
        <v-card class="elevation-1 d-flex flex-column h-100" color="white">
          <v-card-item class="flex-grow-1">
            <v-card-title class="mt-2 mb-3">
              <BaseIcon name="mdi-paw-outline" start color="amber" size="25" />
              {{ p.dog?.nome }}
            </v-card-title>

            <v-card-text class="pa-0 text-center">
              <div class="d-flex justify-center align-center ga-1 mb-2">
                <BaseIcon name="mdi-calendar" size="18" color="primary" />
                <span>{{ p.data }} - {{ p.hora }}</span>
              </div>

              <div class="d-flex justify-center align-center ga-1 mb-4">
                <BaseIcon
                  name="mdi-map-marker"
                  size="18"
                  color="red-darken-4"
                />
                <span>{{ p.local }}</span>
              </div>

              <div class="d-flex justify-center">
                <BaseButton
                  v-if="p.tutor"
                  label="Ver Perfil do Tutor"
                  icon="mdi-account"
                  color="primary"
                  variant="tonal"
                  size="small"
                  class="profile-btn mb-2"
                  :to="`/tutores/${p.tutor.id}`"
                />
              </div>
            </v-card-text>
          </v-card-item>

          <v-card-actions class="justify-center pa-4 pt-2 mb-3">
            <div class="d-flex justify-center ga-2">
              <BaseButton
                color="success"
                variant="flat"
                rounded="pill"
                size="small"
                class="action-btn"
                :disabled="loadId === p.id"
                @click="accept(p.id)"
              >
                <BaseIcon name="mdi-check" start />
                {{ loadId === p.id ? "Aguarde..." : "Aceitar" }}
              </BaseButton>

              <BaseButton
                color="error"
                variant="flat"
                class="action-btn"
                :disabled="loadId === p.id"
                @click="reject(p.id)"
              >
                <BaseIcon name="mdi-close" start />
                {{ loadId === p.id ? "Aguarde..." : "Recusar" }}
              </BaseButton>
            </div>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.profile-btn {
  min-width: 150px;
  min-height: 40px;
  border-radius: 999px !important;
  font-size: 12px !important;
  font-weight: 500;
  text-decoration: none;
}

.action-btn {
  width: 80px;
  height: 36px;
  font-size: 13px;
  border-radius: 999px !important;
}
</style>
<script setup lang="ts">
import { onMounted, computed, ref } from "vue"
import { useWalks } from "../composables/useWalks"
import BaseButton from "../components/atoms/BaseButton.vue"

interface Dog {
  id: number
  nome: string
}

interface Tutor {
  id: number
  nome: string
}

type TourStatus = "pendente" | "aceito" | "recusado" | "finalizado" | "cancelado"

interface Tour {
  id: number
  data: string
  hora: string
  local: string
  status?: TourStatus
  dog?: Dog
  tutor?: Tutor
}

interface ApiErrorResponse {
  response?: {
    data?: {
      message?: string
    }
  }
}

const {
  tours,
  loadTours,
  tourAccept,
  tourReject
} = useWalks()

const loadId = ref<number | null>(null)

const availableTours = computed<Tour[]>(() =>
  tours.value.filter((p: Tour) => !p.status || p.status === "pendente")
)

onMounted(loadTours)

async function accept(id: number): Promise<void> {
  loadId.value = id

  try {
    await tourAccept(id)

    tours.value = tours.value.filter((p: Tour) => p.id !== id)

  } catch (err) {
    console.error("Erro ao aceitar:", err)
    const error = err as ApiErrorResponse
    alert(error.response?.data?.message || "Erro ao aceitar passeio")
  } finally {
    loadId.value = null
  }
}

async function reject(id: number): Promise<void> {
  const confirmar = confirm(
    "Tem certeza que deseja recusar este passeio?"
  )

  if (!confirmar) return

  loadId.value = id

  try {
    await tourReject(id)

    tours.value = tours.value.filter((p: Tour) => p.id !== id)

  } catch (err) {
    console.error("Erro ao recusar:", err)
    const error = err as ApiErrorResponse
    alert(error.response?.data?.message || "Erro ao recusar passeio")
  } finally {
    loadId.value = null
  }
}
</script>

<template>
  <v-container class="py-4">

    <h2 class="mb-4 text-start">🚶 Passeios Disponíveis:</h2>

     <v-alert 
      v-if="availableTours.length === 0" 
      type="info" 
      variant="tonal" 
      class="mb-4"
    >
      Nenhum passeio disponível.
    </v-alert>

    <v-row gap="1.5rem">

      <v-col v-for="p in availableTours" :key="p.id" cols="12" md="6" lg="4" xl="3">
        
        <v-card class="elevation-1 d-flex flex-column h-100">

          <v-card-item class="flex-grow-1">
            
            <v-card-title class="text-h6 mb-2">
              🐶 {{ p.dog?.nome }}
            </v-card-title>

            <v-card-text class="pa-0">
              <p class="mb-2">
                📅 {{ p.data }} - {{ p.hora }}
              </p>

              <p class="mb-4">
                📍 {{ p.local }}
              </p>

              <!-- VER PERFIL DO TUTOR -->
              <v-btn
                v-if="p.tutor"
                :to="`/tutores/${p.tutor.id}`"
                variant="outlined"
                color="secondary"
                size="small"
                block
                class="mb-2"
              >
                👤 Ver Perfil do Tutor
              </v-btn>
            </v-card-text>

          </v-card-item>

          <v-card-actions class="pa-4 pt-0">
            <div class="d-flex ga-2 w-100">

              <v-btn
                color="success"
                variant="flat"
                class="flex-grow-1"
                :disabled="loadId === p.id"
                @click="accept(p.id)"
              >
                {{ loadId === p.id ? "⏳" : "Aceitar" }}
              </v-btn>

              <v-btn
                color="error"
                variant="flat"
                class="flex-grow-1"
                :disabled="loadId === p.id"
                @click="reject(p.id)"
              >
                {{ loadId === p.id ? "⏳" : "Recusar" }}
              </v-btn>

            </div>
          </v-card-actions>
        </v-card>
        
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
h2{
  font-size: 25px;
}
</style>
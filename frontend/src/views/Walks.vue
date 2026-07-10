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
  <div class="container py-4">

    <h2 class="mb-4 text-start">🚶 Passeios Disponíveis:</h2>

    <div v-if="availableTours.length === 0" class="alert alert-info">
      Nenhum passeio disponível.
    </div>

    <div class="row g-4">

      <div v-for="p in availableTours" :key="p.id" class="col-12 col-md-6 col-lg-4 col-xl-3">
        <div class="card shadow-sm h-100">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              🐶 {{ p.dog?.nome }}
            </h5>

            <p class="card-text mb-2">
              📅 {{ p.data }} - {{ p.hora }}
            </p>

            <p class="card-text mb-2">
              📍 {{ p.local }}
            </p>

            <!-- VER PERFIL DO TUTOR -->
            <router-link
              v-if="p.tutor"
              :to="`/tutores/${p.tutor.id}`"
              class="btn btn-outline-secondary btn-sm mb-2">
              👤 Ver Perfil do Tutor
            </router-link>

            <div class="d-flex gap-2 mt-auto pt-3">

              <BaseButton
                class="btn btn-success flex-fill"
                @click="accept(p.id)"
                :disabled="loadId === p.id">
                {{ loadId === p.id ? "⏳" : "Aceitar" }}
              </BaseButton>

              <BaseButton
                class="btn btn-danger flex-fill"
                @click="reject(p.id)"
                :disabled="loadId === p.id">
                {{ loadId === p.id ? "⏳" : "Recusar" }}
              </BaseButton>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
h2{
  font-size: 25px;
}
</style>
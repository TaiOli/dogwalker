<script setup lang="ts">
import { ref, computed, onMounted } from "vue"
import { useAuth } from "../composables/userAuth"
import { api } from "../services/api"
import { getPhoto } from "../utils/image"
import BaseButton from "../components/atoms/BaseButton.vue"
import BaseTextarea from "../components/atoms/BaseTextarea.vue"

interface Dog {
  id: number
  nome: string
}

interface Person {
  id: number
  nome: string
  telefone?: string
  foto?: string
  media_avaliacao?: number | null
}

interface Review {
  rating: number
  comment?: string | null
}

type TourStatus = "pendente" | "aceito" | "recusado" | "finalizado" | "cancelado"

interface Tour {
  id: number
  data: string
  hora: string
  local: string
  status: TourStatus
  dog?: Dog
  tutor?: Person
  walker?: Person
  rated_by_tutor?: boolean
  review_by_tutor?: Review | null
  review_by_walker?: Review | null
}

type Avaliador = "tutor" | "passeador"

interface ApiErrorResponse {
  response?: {
    data?: {
      message?: string
    }
  }
}

const auth = useAuth()

const tutor = computed(() => auth.tutor.value)
const walker = computed(() => auth.walker.value)

const walkers = ref<Person[]>([])
const tours = ref<Tour[]>([])

const reviewTutor = ref<Tour | null>(null)
const reviewWalker = ref<Tour | null>(null)

const rating = ref<number>(0)
const comment = ref<string>("")
const sending = ref<boolean>(false)

const DISMISSED_KEY = "dashboard_dismissed_tours"
const dismissedIds = ref<Set<number>>(new Set())

function loadDismissed(): void {
  try {
    const raw = localStorage.getItem(DISMISSED_KEY)
    if (raw) dismissedIds.value = new Set(JSON.parse(raw))
  } catch (e) {
    console.error(e)
  }
}

function saveDismissed(): void {
  try {
    localStorage.setItem(DISMISSED_KEY, JSON.stringify([...dismissedIds.value]))
  } catch (e) {
    console.error(e)
  }
}

function dismissTour(id: number): void {
  dismissedIds.value.add(id)

  dismissedIds.value = new Set(dismissedIds.value)
  saveDismissed()
}

async function cancelConfirm(passeio: Tour): Promise<void> {
  const confirmed = window.confirm("Tem certeza que deseja cancelar este passeio?")
  if (!confirmed) return

  try {
    await api.patch(`/tours/${passeio.id}/cancel`)

    passeio.status = "cancelado"
  } catch (err) {
    console.error(err)
    const error = err as ApiErrorResponse
    alert(error.response?.data?.message || "Erro ao cancelar passeio.")
  }
}

function onXClickTutor(p: Tour): void {
  if (p.status === "pendente" || p.status === "aceito") {
    cancelConfirm(p)
  } else {
    dismissTour(p.id)
  }
}

const toursTutor = computed(() => 
  tours.value.filter(p => {
    if (dismissedIds.value.has(p.id)) return false
    if (p.status === "cancelado") return false

    return (
      p.status === "pendente" ||
      p.status === "aceito" ||
      p.status === "recusado" ||
      (p.status === "finalizado" && !p.rated_by_tutor)
    )
  })
)

const toursWalker = computed(() =>
  tours.value.filter(p => {
    if (dismissedIds.value.has(p.id)) return false
    if (p.status === "finalizado") return false

    return p.status === "aceito" || p.status === "cancelado"
  })
)

async function loadWalkers(): Promise<void> {
  try {
    const res = await api.get("/walkers")
    walkers.value = res.data
  } catch (e) {
    console.error(e)
  }
}

async function loadTours(): Promise<void> {
  try {
    const res = await api.get("/my-tours")
    tours.value = res.data
  } catch (e) {
    console.error(e)
  }
}

function badgeStatus(status: TourStatus): string {
  switch (status) {
    case "pendente":
      return "warning"   
    case "aceito":
      return "success"   
    case "recusado":
      return "error"    
    case "finalizado":
      return "primary"   
    case "cancelado":
      return "secondary" 
    default:
      return "grey"   
  }
}

function openEvaluationTutor(passeio: Tour): void {
  reviewTutor.value = passeio
  rating.value = 0
  comment.value = ""
}

function cancelEvaluationTutor(): void {
  reviewTutor.value = null
  rating.value = 0
  comment.value = ""
}

function openEvaluationWalker(passeio: Tour): void {
  reviewWalker.value = passeio
  rating.value = 0
  comment.value = ""
}

function cancelEvaluationWalker(): void {
  reviewWalker.value = null
  rating.value = 0
  comment.value = ""
}

async function completeTour(passeio: Tour): Promise<void> {
  if (rating.value === 0) {
    alert("Escolha uma nota antes de finalizar.")
    return
  }

  if (!confirm("Deseja finalizar este passeio?")) return

  sending.value = true

  try {
    await api.patch(`/tours/${passeio.id}/complete`)

    // Envia avaliação do passeador sobre o tutor/passeio
    await api.post("/evaluation", {
      passeio_id: passeio.id,
      nota: rating.value,
      comentario: comment.value,
      tipo_avaliador: "passeador"
    })

    alert("Passeio finalizado e avaliação enviada!")

    cancelEvaluationWalker()
    await loadTours()

  } catch (err) {
    console.error(err)
    const error = err as ApiErrorResponse
    alert(error.response?.data?.message || "Erro ao finalizar passeio.")
  } finally {
    sending.value = false
  }
}

async function sendEvaluation(passeioId: number, tipo: Avaliador): Promise<void> {
  if (!rating.value) {
    alert("Escolha uma nota.")
    return
  }

  sending.value = true

  try {
    await api.post("/evaluation", {
      passeio_id: passeioId,
      nota: rating.value,
      comentario: comment.value,
      tipo_avaliador: tipo
    })

    alert("Avaliação enviada!")

    cancelEvaluationTutor()
    await loadWalkers()
    await loadTours()

  } catch (err) {
    console.error(err)
    const error = err as ApiErrorResponse
    alert(error.response?.data?.message || "Erro ao enviar avaliação.")
  } finally {
    sending.value = false
  }
}

function formatDate(data: string | null | undefined): string {
  if (!data) return ""
  const date = new Date(data)
  return date.toLocaleDateString("pt-BR")
}

onMounted(async () => {
  loadDismissed()
  await loadTours()
  if (tutor.value) {
    await loadWalkers()
  }
})
</script>

<template>
  <v-container class="py-4">
    <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
    <template v-if="tutor">
      <h2 class="mb-4">👨‍🦱 Passeadores Disponíveis</h2>

      <v-row class="mb-5">
        <v-col cols="12" md="4" v-for="w in walkers" :key="w.id">
          <v-card class="h-100" elevation="2">
            <v-card-text class="text-center">
              <v-img :src="getPhoto(w.foto)" class="rounded-circle mx-auto mb-3" width="110" height="110" cover></v-img>
              <h5>{{ w.nome }}</h5>
              <p>📱 {{ w.telefone }}</p>
              <p>
                ⭐
                <span v-if="w.media_avaliacao">{{ w.media_avaliacao }}/5</span>
                <span v-else>Sem avaliações</span>
              </p>
              <div class="d-flex flex-column ga-2">
                <v-btn :to="`/passeador-perfil/${w.id}`" color="success" block>
                  Ver perfil
                </v-btn>
                <v-btn
                  :to="{ path: '/agendar-passeio', query: { walkerId: w.id, walkerNome: w.nome } }"
                  color="success"
                  variant="outlined"
                  block
                >
                  🐾 Solicitar Passeio
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <h2 class="mb-3">📋 Meus Passeios</h2>

      <v-alert v-if="toursTutor.length === 0" type="info" variant="tonal">
        Você ainda não possui passeios.
      </v-alert>

      <v-card class="mb-3 position-relative" elevation="2" v-for="p in toursTutor" :key="p.id">
        <v-card-text>

          <v-btn
            v-if="p.status === 'pendente' || p.status === 'aceito' || p.status === 'recusado'"
            icon="mdi-close"
            size="small"
            variant="text"
            class="dismiss-btn"
            :aria-label="p.status === 'recusado' ? 'Remover do dashboard' : 'Cancelar passeio'"
            :title="p.status === 'recusado' ? 'Remover do dashboard' : 'Cancelar passeio'"
            @click="onXClickTutor(p)"
          />

          <h5>🐶 {{ p.dog?.nome }}</h5>
          <p>📅 {{ formatDate(p.data) }} - {{ p.hora }}</p>
          <p>📍 {{ p.local }}</p>
          <p class="text-medium-emphasis text-caption" v-if="p.walker">
            🚶 Passeador: <strong>{{ p.walker?.nome }}</strong>
          </p>
          <p class="text-medium-emphasis text-caption" v-else-if="p.status === 'recusado'">
            ❌ Nenhum passeador aceitou este passeio.
          </p>
          <p class="text-medium-emphasis text-caption" v-else>
            ⏳ Aguardando um passeador aceitar
          </p>

          <v-chip :color="badgeStatus(p.status)" size="small" class="text-capitalize">
            {{ p.status }}
          </v-chip>

          <div class="mt-3" v-if="p.status === 'finalizado' && !p.rated_by_tutor">
            <BaseButton color="primary" label="Avaliar Passeador" @click="openEvaluationTutor(p)">
              ⭐ Avaliar Passeador
            </BaseButton>
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO TUTOR-->
          <div class="mt-3 avaliacao-box" v-if="p.review_by_tutor">
            <h6 class="mb-2">✅ Sua avaliação sobre o passeador</h6>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                {{ n <= p.review_by_tutor.rating ? "⭐" : "☆" }}
              </span>
              <span class="text-medium-emphasis text-caption ms-1">({{ p.review_by_tutor.rating }}/5)</span>
            </div>
            <p v-if="p.review_by_tutor.comment" class="mb-0 font-italic">
              "{{ p.review_by_tutor.comment }}"
            </p>
          </div>

          <div class="mt-4 border-t pt-3" v-if="reviewTutor?.id === p.id">
            <h5>Como foi o passeador?</h5>

            <div class="mb-3">
              <span
                v-for="n in 5"
                :key="n"
                @click="rating = n"
                class="rating"
              >
                {{ n <= rating ? "⭐" : "☆" }}
            </span>
            </div>

            <BaseTextarea
              class="mb-3"
              :rows="3"
              placeholder="Comentário (opcional)"
              v-model="comment"
            />

            <div class="d-flex ga-2">
              <BaseButton
                color="success"
                :label="sending ? 'Enviando...' : 'Enviar avaliação'"
                @click="sendEvaluation(p.id, 'tutor')"
                :disabled="sending"
              >
                {{ sending ? "Enviando..." : "Enviar avaliação" }}
              </BaseButton>
              <BaseButton color="secondary" label="Cancelar" @click="cancelEvaluationTutor">
                Cancelar
              </BaseButton>
            </div>
          </div>

        </v-card-text>
      </v-card>

    </template>

    <!-- VISUALIZAÇÃO SOMENTE PASSEADOR -->
    <template v-else-if="walker">

      <h2 class="mb-4">🚶 Meus Passeios</h2>

      <v-alert v-if="toursWalker.length === 0" type="info" variant="tonal">
        Você ainda não possui passeios aceitos.
      </v-alert>

      <v-card class="mb-3 position-relative" elevation="2" v-for="p in toursWalker" :key="p.id">
        <v-card-text>

          <v-btn
              v-if="p.status === 'aceito' || p.status === 'cancelado'"
              icon="mdi-close"
              size="small"
              variant="text"
              class="dismiss-btn"
              aria-label="Remover do dashboard"
              title="Remover do dashboard"
              @click="dismissTour(p.id)"
            />

          <h5>🐶 {{ p.dog?.nome }}</h5>
          <p>📅 {{ formatDate(p.data) }} - {{ p.hora }}</p>
          <p>📍 {{ p.local }}</p>
          <p class="text-medium-emphasis text-caption">
            👨‍🦱 Tutor: <strong>{{ p.tutor?.nome }}</strong>
          </p>

          <p class="text-medium-emphasis text-caption" v-if="p.status === 'cancelado'">
            ❌ Este passeio foi cancelado pelo tutor.
          </p>

          <v-chip :color="badgeStatus(p.status)" size="small" class="text-capitalize">
            {{ p.status }}
          </v-chip>

          <div class="mt-3" v-if="p.status === 'aceito'">
            <BaseButton
              color="primary"
              label="Finalizar passeio"
              @click="openEvaluationWalker(p)" >
              ✔ Finalizar passeio
            </BaseButton>
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO PASSEADOR -->
          <div class="mt-3 evaluation-box" v-if="p.review_by_walker">
            <h6 class="mb-2">✅ Sua avaliação sobre o passeio</h6>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                {{ n <= p.review_by_walker.rating ? "⭐" : "☆" }}
              </span>
              <span class="text-medium-emphasis text-caption ms-1">({{ p.review_by_walker.rating }}/5)</span>
            </div>
            <p v-if="p.review_by_walker.comment" class="mb-0 font-italic">
              "{{ p.review_by_walker.comment }}"
            </p>
          </div>

          <div class="mt-4 border-t pt-3" v-if="reviewWalker?.id === p.id">
            <h5>Avaliar o tutor / passeio</h5>

            <div class="mb-3">
              <span
                v-for="n in 5"
                :key="n"
                @click="rating = n"
                class="rating"
              >
                {{ n <= rating ? "⭐" : "☆" }}
            </span>
            </div>

            <BaseTextarea
              class="mb-3"
              :rows="3"
              placeholder="Comentário (opcional)"
              v-model="comment"
            />

            <div class="d-flex ga-2">
              <BaseButton
                color="success"
                :label="sending ? 'Enviando...' : 'Finalizar e enviar'"
                @click="completeTour(p)"
                :disabled="sending"
              >
                {{ sending ? "Enviando..." : "Finalizar e enviar" }}
              </BaseButton>
              <BaseButton color="secondary" label="Cancelar" @click="cancelEvaluationWalker">
                Cancelar
              </BaseButton>
            </div>
          </div>
        </v-card-text>
      </v-card>
    </template>
  </v-container>
</template>

<style scoped>
.card {
  border: none;
  border-radius: 12px;
  transition: .25s;
}

.card:hover {
  transform: translateY(-3px);
}

.position-relative {
  position: relative;
}

.dismiss-btn {
  position: absolute;
  top: 14px;
  right: 14px;
  z-index: 2;
}

.badge {
  font-size: 14px;
  padding: 8px 12px;
  border-radius: 8px;
  text-transform: capitalize;
}

textarea {
  resize: none;
}

.evaluation-box {
  background: #f0f9ff;
  border-left: 3px solid #3498db;
  border-radius: 8px;
  padding: 12px;
}

button {
  border-radius: 8px;
}

.rating{
   font-size:28px;
   cursor:pointer;
}

:deep(.v-textarea .v-field__input) {
  padding-top: 16px;
  padding-bottom: 16px;
  min-height: 56px;
}

:deep(.v-textarea textarea) {
  line-height: 1.5;
}
</style>
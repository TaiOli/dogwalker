<script setup lang="ts">
import { ref, computed, onMounted } from "vue"
import { useAuth } from "../composables/userAuth"
import { api } from "../services/api"
import { getPhoto } from "../utils/image"

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
    if (p.status === "finalizado") return false
    if (p.status === "cancelado") return false

    return (
      p.status === "pendente" ||
      p.status === "aceito" ||
      p.status === "recusado"
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

function badgeStatus(status: TourStatus): Record<string, boolean> {
  return {
    "bg-warning text-dark": status === "pendente",
    "bg-success": status === "aceito",
    "bg-danger": status === "recusado",
    "bg-primary": status === "finalizado",
    "bg-secondary": status === "cancelado"
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
    // Marca como finalizado
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
  <div class="container py-4">
    <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
    <template v-if="tutor">
      <h2 class="mb-4">👨‍🦱 Passeadores Disponíveis</h2>

      <div class="row g-4 mb-5">
        <div class="col-md-4" v-for="w in walkers" :key="w.id">
          <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
              <img :src="getPhoto(w.foto)" class="rounded-circle mb-3" width="110" height="110">
              <h5>{{ w.nome }}</h5>
              <p>📱 {{ w.telefone }}</p>
              <p>
                ⭐
                <span v-if="w.media_avaliacao">{{ w.media_avaliacao }}/5</span>
                <span v-else>Sem avaliações</span>
              </p>
              <router-link :to="`/passeador-perfil/${w.id}`" class="btn btn-success w-100">
                Ver perfil
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <h2 class="mb-3">📋 Meus Passeios</h2>

      <div v-if="toursTutor.length === 0" class="alert alert-info">
        Você ainda não possui passeios.
      </div>

      <div class="card shadow-sm mb-3 position-relative" v-for="p in toursTutor" :key="p.id">
        <div class="card-body">

          <button
            v-if="p.status === 'pendente' || p.status === 'aceito' || p.status === 'recusado'"
            class="btn-close dismiss-btn"
            :aria-label="p.status === 'recusado' ? 'Remover do dashboard' : 'Cancelar passeio'"
            :title="p.status === 'recusado' ? 'Remover do dashboard' : 'Cancelar passeio'"
            @click="onXClickTutor(p)"
          ></button>

          <h5>🐶 {{ p.dog?.nome }}</h5>
          <p>📅 {{ formatDate(p.data) }} - {{ p.hora }}</p>
          <p>📍 {{ p.local }}</p>
          <p class="text-muted small" v-if="p.walker">
            🚶 Passeador: <strong>{{ p.walker?.nome }}</strong>
          </p>
          <p class="text-muted small" v-else-if="p.status === 'recusado'">
            ❌ Nenhum passeador aceitou este passeio.
          </p>
          <p class="text-muted small" v-else>
            ⏳ Aguardando um passeador aceitar
          </p>

          <span class="badge" :class="badgeStatus(p.status)">
            {{ p.status }}
          </span>

          <div class="mt-3" v-if="p.status === 'finalizado' && !p.rated_by_tutor">
            <button class="btn btn-primary" @click="openEvaluationTutor(p)">
              ⭐ Avaliar Passeador
            </button>
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO TUTOR-->
          <div class="mt-3 avaliacao-box" v-if="p.review_by_tutor">
            <h6 class="mb-2">✅ Sua avaliação sobre o passeador</h6>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                {{ n <= p.review_by_tutor.rating ? "⭐" : "☆" }}
              </span>
              <span class="text-muted small ms-1">({{ p.review_by_tutor.rating }}/5)</span>
            </div>
            <p v-if="p.review_by_tutor.comment" class="mb-0 fst-italic">
              "{{ p.review_by_tutor.comment }}"
            </p>
          </div>

          <div class="mt-4 border-top pt-3" v-if="reviewTutor?.id === p.id">
            <h5>Como foi o passeador?</h5>

            <div class="mb-3">
              <span
                v-for="n in 5"
                :key="n"
                @click="rating = n"
                style="font-size:28px;cursor:pointer"
              >
                {{ n <= rating ? "⭐" : "☆" }}
              </span>
            </div>

            <textarea
              class="form-control mb-3"
              rows="3"
              placeholder="Comentário (opcional)"
              v-model="comment"
            />

            <div class="d-flex gap-2">
              <button class="btn btn-success" @click="sendEvaluation(p.id, 'tutor')" :disabled="sending">
                {{ sending ? "Enviando..." : "Enviar avaliação" }}
              </button>
              <button class="btn btn-secondary" @click="cancelEvaluationTutor">
                Cancelar
              </button>
            </div>
          </div>

        </div>
      </div>

    </template>

    <!-- VISUALIZAÇÃO SOMENTE PASSEADOR -->
    <template v-else-if="walker">

      <h2 class="mb-4">🚶 Meus Passeios</h2>

      <div v-if="toursWalker.length === 0" class="alert alert-info">
        Você ainda não possui passeios aceitos.
      </div>

      <div class="card shadow-sm mb-3 position-relative" v-for="p in toursWalker" :key="p.id">
        <div class="card-body">

          <button
            v-if="p.status === 'aceito' || p.status === 'cancelado'"
            class="btn-close dismiss-btn"
            aria-label="Remover do dashboard"
            title="Remover do dashboard"
            @click="dismissTour(p.id)"
          ></button>

          <h5>🐶 {{ p.dog?.nome }}</h5>
          <p>📅 {{ formatDate(p.data) }} - {{ p.hora }}</p>
          <p>📍 {{ p.local }}</p>
          <p class="text-muted small">
            👨‍🦱 Tutor: <strong>{{ p.tutor?.nome }}</strong>
          </p>

          <p class="text-muted small" v-if="p.status === 'cancelado'">
            ❌ Este passeio foi cancelado pelo tutor.
          </p>

          <span class="badge" :class="badgeStatus(p.status)">
            {{ p.status }}
          </span>

          <div class="mt-3" v-if="p.status === 'aceito'">
            <button class="btn btn-primary" @click="openEvaluationWalker(p)">
              ✔ Finalizar passeio
            </button>
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO PASSEADOR -->
          <div class="mt-3 avaliacao-box" v-if="p.review_by_walker">
            <h6 class="mb-2">✅ Sua avaliação sobre o passeio</h6>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                {{ n <= p.review_by_walker.rating ? "⭐" : "☆" }}
              </span>
              <span class="text-muted small ms-1">({{ p.review_by_walker.rating }}/5)</span>
            </div>
            <p v-if="p.review_by_walker.comment" class="mb-0 fst-italic">
              "{{ p.review_by_walker.comment }}"
            </p>
          </div>

          <div class="mt-4 border-top pt-3" v-if="reviewWalker?.id === p.id">
            <h5>Avaliar o tutor / passeio</h5>

            <div class="mb-3">
              <span
                v-for="n in 5"
                :key="n"
                @click="rating = n"
                style="font-size:28px;cursor:pointer"
              >
                {{ n <= rating ? "⭐" : "☆" }}
              </span>
            </div>

            <textarea
              class="form-control mb-3"
              rows="3"
              placeholder="Comentário (opcional)"
              v-model="comment"
            />

            <div class="d-flex gap-2">
              <button class="btn btn-success" @click="completeTour(p)" :disabled="sending">
                {{ sending ? "Enviando..." : "Finalizar e enviar" }}
              </button>
              <button class="btn btn-secondary" @click="cancelEvaluationWalker">
                Cancelar
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
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

.avaliacao-box {
  background: #f0f9ff;
  border-left: 3px solid #3498db;
  border-radius: 8px;
  padding: 12px;
}

.fst-italic {
  font-style: italic;
}

button {
  border-radius: 8px;
}

.small {
  font-size: 12px;
}

.ms-1 {
  margin-left: 4px;
}
</style>
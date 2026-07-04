<script setup>
import { ref, computed, onMounted } from "vue"
import { useAuth } from "../composables/userAuth"
import { api } from "../services/api"
import { getFoto } from "../utils/image"

const auth = useAuth()

const tutor = computed(() => auth.tutor.value)
const walker = computed(() => auth.walker.value)

const passeadores = ref([])
const passeios = ref([])

const avaliandoTutor = ref(null)
const avaliandoWalker = ref(null)

const nota = ref(0)
const comentario = ref("")
const enviando = ref(false)

const DISMISSED_KEY = "dashboard_dismissed_passeios"
const dismissedIds = ref(new Set())

function loadDismissed() {
  try {
    const raw = localStorage.getItem(DISMISSED_KEY)
    if (raw) dismissedIds.value = new Set(JSON.parse(raw))
  } catch (e) {
    console.error(e)
  }
}

function saveDismissed() {
  try {
    localStorage.setItem(DISMISSED_KEY, JSON.stringify([...dismissedIds.value]))
  } catch (e) {
    console.error(e)
  }
}

function dismissPasseio(id) {
  dismissedIds.value.add(id)

  dismissedIds.value = new Set(dismissedIds.value)
  saveDismissed()
}

async function confirmarCancelamento(passeio) {
  const confirmar = confirm("Tem certeza que deseja cancelar este passeio?")
  if (!confirmar) return

  try {
    await api.patch(`/tours/${passeio.id}/cancel`)

    passeio.status = "cancelado"
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "Erro ao cancelar passeio.")
  }
}

function onXClickTutor(p) {
  if (p.status === "pendente" || p.status === "aceito") {
    confirmarCancelamento(p)
  } else {
    dismissPasseio(p.id)
  }
}

const passeiosTutor = computed(() =>
  passeios.value.filter(p => {
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

const passeiosWalker = computed(() =>
  passeios.value.filter(p => {
    if (dismissedIds.value.has(p.id)) return false
    if (p.status === "finalizado") return false

    return p.status === "aceito" || p.status === "cancelado"
  })
)

async function loadWalkers() {
  try {
    const res = await api.get("/walkers")
    passeadores.value = res.data
  } catch (e) {
    console.error(e)
  }
}

async function loadPasseios() {
  try {
    const res = await api.get("/my-tours")
    passeios.value = res.data
  } catch (e) {
    console.error(e)
  }
}

function badgeStatus(status) {
  return {
    "bg-warning text-dark": status === "pendente",
    "bg-success": status === "aceito",
    "bg-danger": status === "recusado",
    "bg-primary": status === "finalizado",
    "bg-secondary": status === "cancelado"
  }
}

function abrirAvaliacaoTutor(passeio) {
  avaliandoTutor.value = passeio
  nota.value = 0
  comentario.value = ""
}

function cancelarAvaliacaoTutor() {
  avaliandoTutor.value = null
  nota.value = 0
  comentario.value = ""
}

function abrirAvaliacaoWalker(passeio) {
  avaliandoWalker.value = passeio
  nota.value = 0
  comentario.value = ""
}

function cancelarAvaliacaoWalker() {
  avaliandoWalker.value = null
  nota.value = 0
  comentario.value = ""
}

async function finalizarPasseio(passeio) {
  if (nota.value === 0) {
    alert("Escolha uma nota antes de finalizar.")
    return
  }

  if (!confirm("Deseja finalizar este passeio?")) return

  enviando.value = true

  try {
    // Marca como finalizado
    await api.patch(`/tours/${passeio.id}/complete`)

    // Envia avaliação do passeador sobre o tutor/passeio
    await api.post("/avaliacoes", {
      passeio_id: passeio.id,
      nota: nota.value,
      comentario: comentario.value,
      tipo_avaliador: "passeador"
    })

    alert("Passeio finalizado e avaliação enviada!")

    cancelarAvaliacaoWalker()
    await loadPasseios()

  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "Erro ao finalizar passeio.")
  } finally {
    enviando.value = false
  }
}

async function enviarAvaliacao(passeioId, tipo) {
  if (!nota.value) {
    alert("Escolha uma nota.")
    return
  }

  enviando.value = true

  try {
    await api.post("/avaliacoes", {
      passeio_id: passeioId,
      nota: nota.value,
      comentario: comentario.value,
      tipo_avaliador: tipo
    })

    alert("Avaliação enviada!")

    cancelarAvaliacaoTutor()
    await loadWalkers()
    await loadPasseios()

  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "Erro ao enviar avaliação.")
  } finally {
    enviando.value = false
  }
}

function formatarData(data) {
  if (!data) return ""
  const date = new Date(data)
  return date.toLocaleDateString("pt-BR")
}

onMounted(async () => {
  loadDismissed()
  await loadPasseios()
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
        <div class="col-md-4" v-for="w in passeadores" :key="w.id">
          <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
              <img :src="getFoto(w.foto)" class="rounded-circle mb-3" width="110" height="110">
              <h5>{{ w.nome }}</h5>
              <p>📱 {{ w.telefone }}</p>
              <p>
                ⭐
                <span v-if="w.media_avaliacao">{{ w.media_avaliacao }}/5</span>
                <span v-else>Sem avaliações</span>
              </p>
              <router-link :to="`/walkers/${w.id}`" class="btn btn-success w-100">
                Ver perfil
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <h2 class="mb-3">📋 Meus Passeios</h2>

      <div v-if="passeiosTutor.length === 0" class="alert alert-info">
        Você ainda não possui passeios.
      </div>

      <div class="card shadow-sm mb-3 position-relative" v-for="p in passeiosTutor" :key="p.id">
        <div class="card-body">

          <button
            v-if="p.status === 'pendente' || p.status === 'aceito' || p.status === 'recusado'"
            class="btn-close dismiss-btn"
            :aria-label="p.status === 'recusado' ? 'Remover do dashboard' : 'Cancelar passeio'"
            :title="p.status === 'recusado' ? 'Remover do dashboard' : 'Cancelar passeio'"
            @click="onXClickTutor(p)"
          ></button>

          <h5>🐶 {{ p.dog?.nome }}</h5>
          <p>📅 {{ formatarData(p.data) }} - {{ p.hora }}</p>
          <p>📍 {{ p.local }}</p>
          <p class="text-muted small" v-if="p.passeador">
            🚶 Passeador: <strong>{{ p.passeador?.nome }}</strong>
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

          <div class="mt-3" v-if="p.status === 'finalizado' && !p.avaliado_pelo_tutor">
            <button class="btn btn-primary" @click="abrirAvaliacaoTutor(p)">
              ⭐ Avaliar Passeador
            </button>
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO TUTOR-->
          <div class="mt-3 avaliacao-box" v-if="p.avaliacao_do_tutor">
            <h6 class="mb-2">✅ Sua avaliação sobre o passeador</h6>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                {{ n <= p.avaliacao_do_tutor.nota ? "⭐" : "☆" }}
              </span>
              <span class="text-muted small ms-1">({{ p.avaliacao_do_tutor.nota }}/5)</span>
            </div>
            <p v-if="p.avaliacao_do_tutor.comentario" class="mb-0 fst-italic">
              "{{ p.avaliacao_do_tutor.comentario }}"
            </p>
          </div>

          <div class="mt-4 border-top pt-3" v-if="avaliandoTutor?.id === p.id">
            <h5>Como foi o passeador?</h5>

            <div class="mb-3">
              <span
                v-for="n in 5"
                :key="n"
                @click="nota = n"
                style="font-size:28px;cursor:pointer"
              >
                {{ n <= nota ? "⭐" : "☆" }}
              </span>
            </div>

            <textarea
              class="form-control mb-3"
              rows="3"
              placeholder="Comentário (opcional)"
              v-model="comentario"
            />

            <div class="d-flex gap-2">
              <button class="btn btn-success" @click="enviarAvaliacao(p.id, 'tutor')" :disabled="enviando">
                {{ enviando ? "Enviando..." : "Enviar avaliação" }}
              </button>
              <button class="btn btn-secondary" @click="cancelarAvaliacaoTutor">
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

      <div v-if="passeiosWalker.length === 0" class="alert alert-info">
        Você ainda não possui passeios aceitos.
      </div>

      <div class="card shadow-sm mb-3 position-relative" v-for="p in passeiosWalker" :key="p.id">
        <div class="card-body">

          <button
            v-if="p.status === 'aceito' || p.status === 'cancelado'"
            class="btn-close dismiss-btn"
            aria-label="Remover do dashboard"
            title="Remover do dashboard"
            @click="dismissPasseio(p.id)"
          ></button>

          <h5>🐶 {{ p.dog?.nome }}</h5>
          <p>📅 {{ formatarData(p.data) }} - {{ p.hora }}</p>
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
            <button class="btn btn-primary" @click="abrirAvaliacaoWalker(p)">
              ✔ Finalizar passeio
            </button>
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO PASSEADOR -->
          <div class="mt-3 avaliacao-box" v-if="p.avaliacao_do_passeador">
            <h6 class="mb-2">✅ Sua avaliação sobre o passeio</h6>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                {{ n <= p.avaliacao_do_passeador.nota ? "⭐" : "☆" }}
              </span>
              <span class="text-muted small ms-1">({{ p.avaliacao_do_passeador.nota }}/5)</span>
            </div>
            <p v-if="p.avaliacao_do_passeador.comentario" class="mb-0 fst-italic">
              "{{ p.avaliacao_do_passeador.comentario }}"
            </p>
          </div>

          <div class="mt-4 border-top pt-3" v-if="avaliandoWalker?.id === p.id">
            <h5>Avaliar o tutor / passeio</h5>

            <div class="mb-3">
              <span
                v-for="n in 5"
                :key="n"
                @click="nota = n"
                style="font-size:28px;cursor:pointer"
              >
                {{ n <= nota ? "⭐" : "☆" }}
              </span>
            </div>

            <textarea
              class="form-control mb-3"
              rows="3"
              placeholder="Comentário (opcional)"
              v-model="comentario"
            />

            <div class="d-flex gap-2">
              <button class="btn btn-success" @click="finalizarPasseio(p)" :disabled="enviando">
                {{ enviando ? "Enviando..." : "Finalizar e enviar" }}
              </button>
              <button class="btn btn-secondary" @click="cancelarAvaliacaoWalker">
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
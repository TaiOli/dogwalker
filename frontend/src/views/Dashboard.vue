<template>
  <div>
    <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
    <div v-if="tutor">
      <h2 class="title">👨‍🦱 Passeadores Disponíveis</h2>

      <div class="grid mb-5">
        <div
          v-for="walker in passeadores"
          :key="walker.id"
          class="card"
        >
          <img
            :src="getFoto(walker.foto)"
            class="walker-photo"
          />
          <h4>{{ walker.nome }}</h4>
          <p>📱 {{ walker.telefone }}</p>
          <p>⭐ {{ walker.media_avaliacao ?? "Sem avaliações" }}</p>
          <router-link
            :to="`/walkers/${walker.id}`"
            class="btn btn-success w-100"
          >
            Ver Perfil
          </router-link>
        </div>
      </div>

      <h2 class="title">📋 Meus Agendamentos</h2>
      <div class="grid">
        <div
          v-for="p in passeios"
          :key="p.id"
          class="card"
        >
          <button class="close" @click="remover(p.id)">×</button>
          <h4>🐶 {{ p.dog?.nome }}</h4>
          <p>{{ p.data }} - {{ p.hora }}</p>
          <p>{{ p.local }}</p>
          <span class="status" :class="p.status">{{ p.status }}</span>
        </div>
      </div>
    </div>

    <!-- VISUALIZAÇÃO SOMENTE PASSEADOR -->
    <div v-else-if="walker">
      <h2 class="title">🚶 Passeios Disponíveis</h2>
      <div class="grid">
        <div v-for="p in passeiosFiltrados" :key="p.id" class="card">
          <h4>🐶 {{ p.dog?.nome }}</h4>
          <p>{{ p.data }} - {{ p.hora }}</p>
          <p>{{ p.local }}</p>
          <span class="status" :class="p.status">{{ p.status }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue"
import { useAuth } from "../composables/userAuth"
import { api } from "../services/api"
import { getFoto } from "../utils/image.js"

const auth = useAuth()

const tutor = computed(() => auth.tutor.value)
const walker = computed(() => auth.walker.value)

const passeios = ref([])
const passeadores = ref([])

const passeiosFiltrados = computed(() =>
  passeios.value.filter(p => 
    p.status === "pendente" || p.status === "aceito"
  )
)

async function loadPasseios() {
  const res = await fetch("http://localhost:8000/api/meus-passeios", {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`
    }
  })
  const data = await res.json()
  passeios.value = data.filter(p => 
    p.status === "pendente" || p.status === "aceito"
  )
}

async function loadWalkers() {
  const response = await api.get("/walkers")
  passeadores.value = response.data
}

async function remover(id) {
  const confirmar = confirm("Deseja realmente excluir este agendamento?")
  if (!confirmar) return
  passeios.value = passeios.value.filter(p => p.id !== id)
}

onMounted(async () => {
  await loadPasseios()
  if (tutor.value) {
    await loadWalkers()
  }
})
</script>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 20px;
}

.title {
  font-size: 25px;
  font-weight: 600;
  margin-bottom: 20px;
}

.card {
  background: white;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: relative;
}

.walker-photo {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 10px;
}

.status {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  display: inline-block;
  margin-top: 10px;
}

.pendente { background: #f1c40f; }
.aceito { background: #2ecc71; color: white; }
.recusado { background: #e74c3c; color: white; }

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 28px;
  height: 28px;
  border: none;
  border-radius: 50%;
  background: #e74c3c;
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.2s ease;
}

.close:hover {
  background: #c0392b;
  transform: scale(1.1);
}
</style>
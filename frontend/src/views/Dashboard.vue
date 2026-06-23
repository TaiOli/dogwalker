<template>
  <div class="container">

    <div class="content">

      <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
      <div v-if="tutor">
        <h2>🐕 Meus passeios</h2>

        <div class="grid">
          <div v-for="p in meusPasseios" :key="p.id" class="card">

            <button class="close" @click="remover(p.id)">✖</button>
            
            <h3>🐶 {{ p.dog.nome }}</h3>

            <p>📅 {{ p.data }} - {{ p.hora }}</p>
            <p>📍 {{ p.local }}</p>

            <span class="status" :class="p.status">
              {{ p.status }}
            </span>

          </div>
        </div>
      </div>

      <!-- VISUALIZAÇÃO SOMENTE PASSEADOR -->
      <div v-else-if="walker">
        <h2>🚶 Passeios atribuídos a mim</h2>

        <div class="grid">
          <div v-for="p in meusPasseios" :key="p.id" class="card">

            <button class="close" @click="recusar(p.id)">✖</button>

            <h3>🐶 {{ p.dog.nome }}</h3>

            <p>📅 {{ p.data }} - {{ p.hora }}</p>

            <span class="status" :class="p.status">
              {{ p.status }}
            </span>

          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script setup>
import { onMounted, ref, computed} from "vue"
import { useAuth } from "../composables/userAuth"
import { useWalks } from "../composables/useWalks"
import { api } from "../services/api"

const auth = useAuth()
const tutor = computed(() => auth.tutor.value)
const walker = computed(() => auth.walker.value)

const {
  recusarPasseio,
  removerPasseio
} = useWalks()

const meusPasseios = ref([])

async function load() {
  const res = await api.get("/meus-passeios")
  meusPasseios.value = res.data
}

async function recusar(id) {
  await recusarPasseio(id)
  await load()
}

async function remover(id) {
  await removerPasseio(id)
  meusPasseios.value = meusPasseios.value.filter(p => p.id !== id)
}

onMounted(load)
</script>

<style scoped>
.container {
  width: 100%;
  min-height: 100vh;

  display: flex;
  justify-content: center;
  padding: 40px;
  background: #f5f6f8;
}

.content {
  width: 100%;
  max-width: 1100px;
}

h2 {
  margin-bottom: 50px;
  text-align: left;
  color: #2c3e50;
  font-size: 18px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.card {
  background: white;
  border-radius: 12px;
  padding: 18px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 8px;
  transition: 0.2s;
}

.card:hover {
  transform: translateY(-3px);
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;

  background: transparent;
  border: none;
  font-size: 16px;
  cursor: pointer;

  color: #999;
  transition: 0.2s;
}

.close:hover {
  color: #e74c3c;
  transform: scale(1.2);
}

.status {
  padding: 5px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: bold;
  width: fit-content;
}

.pendente {
  background: #f1c40f;
  color: #000;
}

.aceito {
  background: #2ecc71;
  color: white;
}

.recusado {
  background: #e74c3c;
  color: white;
}
</style>
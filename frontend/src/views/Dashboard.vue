<template>
  <div class="container">

    <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
    <div v-if="tutor">
      <div class="grid">
        <div v-for="p in passeios" :key="p.id" class="card">

          <button class="close" @click="remover(p.id)">✖</button>

          <h4>🐶 {{ p.dog?.nome }}</h4>
          <p>{{ p.data }} - {{ p.hora }}</p>
          <p>{{ p.local }}</p>

          <span class="status" :class="p.status">
            {{ p.status }}
          </span>

        </div>
      </div>
    </div>

    <!-- VISUALIZAÇÃO SOMENTE PASSEADOR -->
    <div v-else-if="walker">
      <div class="grid">
        <div v-for="p in passeiosFiltrados" :key="p.id" class="card">

          <h4>🐶 {{ p.dog?.nome }}</h4>
          <p>{{ p.data }} - {{ p.hora }}</p>
          <p>{{ p.local }}</p>

          <span class="status" :class="p.status">
            {{ p.status }}
          </span>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue"
import { useAuth } from "../composables/userAuth"

const auth = useAuth()

const tutor = computed(() => auth.tutor.value)
const walker = computed(() => auth.walker.value)

const passeios = ref([])

const passeiosFiltrados = computed(() =>
  passeios.value.filter(p =>
    p.status === "pendente" || p.status === "aceito"
  )
)

async function load() {
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

async function remover(id) {
  const passeio = passeios.value.find(p => p.id === id)

  if (!passeio) return

  if (passeio.status === "recusado") {
    passeios.value = passeios.value.filter(p => p.id !== id)
    return
  }

  const confirmar = confirm(
    "Deseja realmente excluir este agendamento?"
  )

  if (!confirmar) return
  passeios.value = passeios.value.filter(p => p.id !== id)
}

onMounted(load)
</script>

<style scoped>
.container {
  width: 100%;
  padding: 40px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 20px;
}

.passeios{
  font-size:15px;
}

.h3{
  font-size:18px;
}

.card {
  background: white;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: relative;
}

.status {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
}

.pendente {
  background: #f1c40f;
}

.aceito {
  background: #2ecc71;
  color: white;
}

.recusado {
  background: #e74c3c;
  color: white;
}

.close {
  position: absolute;
  top: 8px;
  right: 8px; 
  width: 26px;
  height: 26px;
  border: none;
  border-radius: 50%;
  background: #e74c3c;
  color: white;
  font-weight: bold;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: 0.2s;
}

.close:hover {
  background: #c0392b;
  transform: scale(1.1);
}
</style>
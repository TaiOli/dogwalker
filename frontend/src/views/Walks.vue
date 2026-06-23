<template>
  <div class="container">

    <h2>🚶 Passeios Disponíveis</h2>

    <div v-if="passeios.length === 0">
      Nenhum passeio disponível.
    </div>

    <div class="cards">
      <div v-for="p in passeios" :key="p.id" class="card">

        <h3>🐶 {{ p.dog?.nome }}</h3>
        <p>📅 {{ p.data }} - {{ p.hora }}</p>
        <p>📍 {{ p.local }}</p>

        <div class="actions">
          <button class="btn-aceitar" @click="aceitar(p.id)">
            Aceitar
          </button>

          <button class="btn-recusar" @click="recusar(p.id)">
            Recusar
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue"
import { useWalks } from "../composables/useWalks"

const {
  passeios,
  loadPasseios,
  aceitarPasseio,
  recusarPasseio
} = useWalks()

onMounted(loadPasseios)

// 🔥 ACEITAR
async function aceitar(id) {
  await aceitarPasseio(id)

  // 🔥 atualiza lista do backend (IMPORTANTE)
  await loadPasseios()
}

// 🔥 RECUSAR
async function recusar(id) {
  await recusarPasseio(id)

  // 🔥 atualiza lista do backend (IMPORTANTE)
  await loadPasseios()
}
</script>

<style scoped>
.container {
  width: 100%;
  padding: 40px;
}

.passeios{
  text-align: left;
  margin-top:30px;
  font-size: 14px;
}

.cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: 50px;
}

.card {
  background: white;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.card h3 {
  margin: 0;
  font-size: 18px;
}

.card p {
  margin: 0;
  font-size: 14px;
}

button {
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px;
  cursor: pointer;
  font-weight: bold;
}

.btn-aceitar {
  flex: 1;
  background: #42b983;
}

.btn-recusar {
  flex: 1;
  background: #e74c3c;
}

.btn-aceitar:hover {
  background: #369b6f;
}

.btn-recusar:hover {
  background: #c0392b;
}

h2{
    text-align: left;
    font-size: 20px;
}

.actions {
  display: flex;
  gap: 10px;
  margin-top: auto;
}
</style>
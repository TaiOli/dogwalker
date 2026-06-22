<template>
    <div class="container">
      <div class="form-wrapper">

        <h2>🚶 Solicitar Passeio</h2>
        <p>Escolha seu cachorro</p>

        <ScheduletourForm
          :form="form"
          :dogs="dogs"
          @submit="salvar"
        />
      </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue"
import ScheduletourForm from "../components/molecules/ScheduletourForm.vue"
import { useScheduletour } from "../composables/useScheduletour"

const {
  form,
  dogs,
  loadDogs,
  solicitarPasseio,
  clearPasseio
} = useScheduletour()

onMounted(async () => {
  await loadDogs()
})

async function salvar() {
  await solicitarPasseio()
  alert("Passeio solicitado com sucesso!")
  clearPasseio()
}
</script>

<style scoped>
.container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 40px;
}

.form-wrapper {
  width: 100%;
  max-width: 600px;
}

h2 {
  margin-bottom: 8px;
}

p {
  margin-bottom: 20px;
  color: #666;
  font-size: 15px;
}
</style>
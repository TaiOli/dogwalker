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

onMounted(() => {
  loadDogs()
})

async function salvar() {
  await solicitarPasseio()
  alert("Passeio solicitado com sucesso!")
  clearPasseio()
}
</script>

<template>
  <div class="container">
  <div class="form-wrapper">
    <h2>🚶 Solicitar Passeio</h2>
    <p>Escolha seu cachorro e preencha os dados</p>

    <ScheduletourForm
      :form="form"
      :dogs="dogs"
      @submit="salvar"
    />
  </div>
</div>
</template>

<style scoped>
.container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-wrapper {
  width: 100%;
  max-width: 600px;
}
</style>
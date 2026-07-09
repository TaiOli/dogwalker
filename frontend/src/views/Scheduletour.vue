<script setup lang="ts">
import { onMounted } from "vue"
import { useRoute } from "vue-router"
import ScheduletourForm from "../components/molecules/ScheduletourForm.vue"
import { useScheduletour } from "../composables/useScheduletour"

const route = useRoute()

const {
  form,
  dogs,
  walkers,
  loadDogs,
  loadWalkers,
  setWalker,
  requestTour,
  clearTour
} = useScheduletour()

onMounted(async () => {
  await Promise.all([loadDogs(), loadWalkers()])

  const walkerId = route.query.walkerId

  if (walkerId) {
    setWalker(Array.isArray(walkerId) ? walkerId[0] : walkerId)
  }
})

async function save(): Promise<void> {
  try {
    await requestTour()

    alert("Passeio solicitado com sucesso!")

    clearTour()

  } catch (error) {
    console.log(error)
    alert("Erro ao solicitar passeio")
  }
}
</script>

<template>
  <div class="container py-4 mt-5">

    <div class="row justify-content-center">
      <div class="col-12 col-lg-11 col-xl-10">

        <h2 class="page-title mb-4">🚶 Solicitar Passeio</h2>

        <div class="card shadow-sm p-3">

          <ScheduletourForm
            :form="form"
            :dogs="dogs"
            :walkers="walkers"
            labelButton="Salvar"
            @submit="save"
          />

        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-title {
  text-align: left;
  font-size: 25px;
  font-weight: 600;
}
</style>
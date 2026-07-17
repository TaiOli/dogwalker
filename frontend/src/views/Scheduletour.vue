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
  <v-container class="py-4 mt-5">

    <v-row class="justify-center">
      <v-col cols="12" lg="11" xl="10">

        <h2 class="page-title mb-4">🚶 Solicitar Passeio</h2>

        <v-card elevation="1" class="pa-3 card" color="white">

          <ScheduletourForm
            :form="form"
            :dogs="dogs"
            :walkers="walkers"
            labelButton="Salvar"
            @submit="save"
          />

        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.page-title {
  text-align: left;
  font-size: 25px;
  font-weight: 600;
}

.card{
  padding: 30px;
}
</style>
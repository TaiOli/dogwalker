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
  <v-container class="py-6">

    <v-row justify="center">
      <v-col
        cols="12"
        md="10"
        lg="8"
      >

        <div class="d-flex align-center ga-2 mb-5">
          <v-icon color="primary" size="32">
            mdi-plus
          </v-icon>

          <h2 class="text-h4 font-weight-bold">
            Solicitar Passeio
          </h2>
        </div>

        <v-card
          elevation="3"
          rounded="xl"
          class="pa-6"
          color="white"
        >
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

.card{
  padding: 30px;
}
</style>
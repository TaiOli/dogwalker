<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-7">
        <div class="card shadow-sm border-0 p-4 mt-4 mb-5">

          <div class="text-center mb-4">
            <h2>🚶 Solicitar Passeio</h2>
            <p class="text-muted">
              Escolha seu cachorro e preencha os dados
            </p>
          </div>

          <ScheduletourForm
            :form="form"
            :dogs="dogs"
            @submit="salvar"
          />
        </div>
      </div>
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

onMounted(() => {
  loadDogs()
})

async function salvar() {
  await solicitarPasseio()

  alert("Passeio solicitado com sucesso!")

  clearPasseio()
}
</script>
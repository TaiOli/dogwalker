<template>
  <div class="container py-4">

    <h2 class="mb-4 mt-5 text-start">🚶 Passeios Disponíveis:</h2>

    <div v-if="passeios.length === 0" class="alert alert-info">
      Nenhum passeio disponível.
    </div>

    <div class="row g-4">

      <div v-for="p in passeios" :key="p.id" class="col-12 col-md-6 col-lg-4 col-xl-3">
        <div class="card shadow-sm h-100">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              🐶 {{ p.dog?.nome }}
            </h5>

            <p class="card-text mb-2">
              📅 {{ p.data }} - {{ p.hora }}
            </p>

            <p class="card-text">
              📍 {{ p.local }}
            </p>

            <div class="d-flex gap-2 mt-auto pt-3">

              <button class="btn btn-success flex-fill" @click="aceitar(p.id)">
                Aceitar
              </button>

              <button class="btn btn-danger flex-fill" @click="recusar(p.id)">
                Recusar
              </button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useWalks } from "../composables/useWalks";

const {
  passeios,
  loadPasseios,
  aceitarPasseio,
  recusarPasseio
} = useWalks();

onMounted(loadPasseios);

async function aceitar(id) {
  await aceitarPasseio(id);
  await loadPasseios();
}

async function recusar(id) {
  const confirmar = confirm(
    "Tem certeza que deseja recusar este passeio?"
  );

  if (!confirmar) return;

  await recusarPasseio(id);
  await loadPasseios();
}
</script>

<style scoped>
h2{
  font-size: 25px;
}
</style>
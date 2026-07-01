<script setup>
import { onMounted, ref } from "vue";
import { useWalks } from "../composables/useWalks";

const {
  passeios,
  loadPasseios,
  aceitarPasseio,
  recusarPasseio
} = useWalks();

const carregandoId = ref(null)

onMounted(loadPasseios);

async function aceitar(id) {
  carregandoId.value = id

  try {
    await aceitarPasseio(id)

    passeios.value = passeios.value.filter(p => p.id !== id)

  } catch (err) {
    console.error("Erro ao aceitar:", err)
    alert(err.response?.data?.message || "Erro ao aceitar passeio")
  } finally {
    carregandoId.value = null
  }
}

async function recusar(id) {
  const confirmar = confirm(
    "Tem certeza que deseja recusar este passeio?"
  );

  if (!confirmar) return;

  carregandoId.value = id

  try {
    await recusarPasseio(id)

    passeios.value = passeios.value.filter(p => p.id !== id)

  } catch (err) {
    console.error("Erro ao recusar:", err)
    alert(err.response?.data?.message || "Erro ao recusar passeio")
  } finally {
    carregandoId.value = null
  }
}
</script>

<template>
  <div class="container py-4">

    <h2 class="mb-4 text-start">🚶 Passeios Disponíveis:</h2>

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

            <p class="card-text mb-2">
              📍 {{ p.local }}
            </p>

            <!-- VER PERFIL DO TUTOR -->
            <router-link
              v-if="p.tutor"
              :to="`/tutors/${p.tutor.id}`"
              class="btn btn-outline-secondary btn-sm mb-2"
            >
              👤 Ver Perfil do Tutor
            </router-link>

            <div class="d-flex gap-2 mt-auto pt-3">

              <button
                class="btn btn-success flex-fill"
                @click="aceitar(p.id)"
                :disabled="carregandoId === p.id"
              >
                {{ carregandoId === p.id ? "⏳" : "Aceitar" }}
              </button>

              <button
                class="btn btn-danger flex-fill"
                @click="recusar(p.id)"
                :disabled="carregandoId === p.id"
              >
                {{ carregandoId === p.id ? "⏳" : "Recusar" }}
              </button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
h2{
  font-size: 25px;
}
</style>
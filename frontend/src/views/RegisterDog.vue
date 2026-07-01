<script setup>
import { ref, computed, onMounted } from "vue"
import DogForm from "../components/molecules/DogForm.vue"
import { useDog } from "../composables/useDog"
import { api } from "../services/api"

const { formDog, cadastrarDog, clearDog } = useDog()

const dogs = ref([])
const search = ref("")

const filteredDogs = computed(() => {
  return dogs.value.filter(dog =>
    dog.nome.toLowerCase().includes(search.value.toLowerCase()) ||
    dog.raca?.toLowerCase().includes(search.value.toLowerCase())
  )
})

async function loadDogs() {
  const res = await api.get("/dogs/my")
  dogs.value = res.data
}

async function salvar() {
  try {
    await cadastrarDog()

    clearDog()
    await loadDogs()

    const modal = bootstrap.Modal.getInstance(
      document.getElementById("dogModal")
    )
    modal.hide()

  } catch (error) {
    console.log(error)
    alert("Erro ao cadastrar cachorro")
  }
}

onMounted(loadDogs)
</script>

<template>
  <div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Cadastro do dog</h2>

      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dogModal">
        Novo
      </button>
    </div>

    <!-- FILTRO -->
    <div class="mb-4">
      <input
        v-model="search"
        type="text"
        class="form-control"
        placeholder="🔎 Buscar cachorro por nome, raça..."
      />
    </div>

    <!-- LISTA -->
    <h2 class="mt-5 mb-4">🐕 Meus Doguinhos :</h2>

    <div v-if="filteredDogs.length === 0" class="text-center text-muted">
      Nenhum cachorro encontrado.
    </div>

    <div class="row g-4">
      <div
        v-for="dog in filteredDogs"
        :key="dog.id"
        class="col-12 col-sm-6 col-md-4 col-lg-3"
      >
        <div class="card h-100 shadow-sm dog-card">

          <img :src="dog.foto" class="card-img-top dog-img" alt="dog"/>

          <div class="card-body">
            <h5 class="card-title">{{ dog.nome }}</h5>

            <p class="mb-1">🐾 {{ dog.raca }}</p>
            <p class="mb-1">🎂 {{ dog.idade }} anos</p>
            <p class="mb-1">📦 {{ dog.porte }}</p>
          </div>

        </div>
      </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="dogModal" tabindex="-1">
      <div class="modal-dialog  modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Cadastrar cachorro</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <DogForm
              :form="formDog"
              labelButton="Salvar"
              @submit="salvar"
            />
          </div>

        </div>
      </div>
    </div>

  </div>
</template>


<style scoped>
.dog-img {
  height: 300px;
  object-fit: cover;
}

.dog-card {
  border-radius: 12px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dog-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.card-body {
  padding: 18px;
}

h2 {
  text-align: left;
  font-size: 25px;
}
</style>
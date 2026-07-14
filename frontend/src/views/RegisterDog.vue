<script setup lang="ts">
import { ref, watch, onMounted } from "vue"
import DogForm from "../components/molecules/DogForm.vue"
import { useDog } from "../composables/useDog"
import { api } from "../services/api"
import BaseInput from "../components/atoms/BaseInput.vue"
import BaseButton from "../components/atoms/BaseButton.vue"

interface Dog {
  id: number
  nome: string
  raca?: string
  idade?: number
  porte?: string
  foto?: string
}

const { formDog, registerDog, updateDog, setDog, clearDog } = useDog()

const dogs = ref<Dog[]>([])
const search = ref<string>("")
const showModal = ref<boolean>(false)
const excludingId = ref<number | null>(null)

async function loadDogs(): Promise<void> {
  const res = await api.get("/dogs/my", {
    params: { search: search.value }
  })
  dogs.value = res.data
}

function openModal(): void {
  clearDog()
  showModal.value = true
}

function editDog(dog: Dog): void {
  setDog(dog)
  showModal.value = true
}

function closeModal(): void {
  showModal.value = false
}

async function removeDog(dog: Dog): Promise<void> {
  const confirmed = confirm(`Tem certeza que deseja excluir o cadastro de "${dog.nome}"?`)
  if (!confirmed) return

  excludingId.value = dog.id

  try {
    await api.delete(`/dogs/${dog.id}`)

    dogs.value = dogs.value.filter(d => d.id !== dog.id)
    alert("Dog excluído com sucesso!")

  } catch (error: any) {
    console.error("Erro ao excluir cachorro:", error)
    alert(error.response?.data?.message || "Erro ao excluir cachorro")
  } finally {
    excludingId.value = null
  }
}

async function save(): Promise<void> {
  try {
    if (formDog.id) {
      await updateDog()
      alert("Cadastro atualizado com sucesso!")
    } else {
      await registerDog()
      alert("Dog cadastrado com sucesso!")
    }

    clearDog()
    await loadDogs()
    showModal.value = false
  } catch (error) {
    console.log(error)
    alert("Erro ao salvar cachorro")
  }
}

watch(search, () => {
  loadDogs()
})

onMounted(loadDogs)
</script>

<template>
  <div class="container py-4">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Cadastro do dog</h2>

      <BaseButton class="btn btn-primary" @click="openModal">
        Novo
      </BaseButton>
    </div>

    <!-- FILTRO -->
    <div class="mb-4">
      <BaseInput
        v-model="search"
        type="text"
        class="form-control"
        placeholder="🔎 Buscar cachorro por nome, raça..."
      />
    </div>

    <!-- LISTA -->
    <h2 class="mt-5 mb-4">🐕 Meus Doguinhos :</h2>

    <div v-if="dogs.length === 0" class="text-center text-muted">
      Nenhum cachorro encontrado.
    </div>

    <div class="row g-4">
      <div
        v-for="dog in dogs"
        :key="dog.id"
        class="col-12 col-sm-6 col-md-4 col-lg-3"
      >
        <div class="card h-100 shadow-sm dog-card position-relative">

          <BaseButton
            type="button"
            class="btn-close dismiss-btn"
            aria-label="Excluir cachorro"
            title="Excluir cachorro"
            :disabled="excludingId === dog.id"
            @click="removeDog(dog)"
          />

          <img :src="dog.foto" class="card-img-top dog-img" alt="dog" />

          <div class="card-body">
            <h5 class="card-title">{{ dog.nome }}</h5>
            <p class="mb-1">🐾 {{ dog.raca }}</p>
            <p class="mb-1">🎂 {{ dog.idade }} anos</p>
            <p class="mb-1">📦 {{ dog.porte }}</p>

            <BaseButton
              class="btn btn-sm btn-outline-secondary mt-2"
              @click="editDog(dog)">
              ✏️ Editar
            </BaseButton>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay">
      <div class="modal-box">
        <div class="modal-header d-flex justify-content-between align-items-center">
          <h5 class="modal-title">
            {{ formDog.id ? "Editar cachorro" : "Cadastrar cachorro" }}
          </h5>
          <BaseButton class="btn-close" @click="closeModal"/>
        </div>

        <div class="modal-body mt-3">
          <DogForm
            :form="formDog"
            :labelButton="formDog.id ? 'Atualizar' : 'Salvar'"
            @submit="save"
          />
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

.position-relative {
  position: relative;
}

.dismiss-btn {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 2;
  background-color: #fff;
  border-radius: 50%;
  padding: 6px;
  opacity: 0.9;
}

.dismiss-btn:hover {
  opacity: 1;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal-box {
  background: #fff;
  width: 90%;
  max-width: 700px;
  border-radius: 12px;
  padding: 20px;
}
</style>
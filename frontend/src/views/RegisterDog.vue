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
  const confirmar = confirm(`Tem certeza que deseja excluir o cadastro de "${dog.nome}"?`)
  if (!confirmar) return

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
  <v-container class="py-4">
    <!-- HEADER -->
    <v-row align="center" justify="space-between" class="mb-6">
      <v-col cols="auto">
        <div class="d-flex align-center ga-2">
          <v-icon color="primary">mdi-dog</v-icon>
          <h3 class="text-h4 font-weight-bold">
            Cadastro do Dog
          </h3>
        </div>
      </v-col>
      <v-col cols="auto"> 
        <BaseButton
          label="Novo"
          color="primary"
          class="rounded-pill"
          @click="openModal"
        />
      </v-col>
    </v-row>

    <!-- FILTRO -->
    <v-row class="mb-4 mt-3">
      <v-col cols="12">
        <BaseInput
          v-model="search"
          type="text"
          class="bg-white rounded px-4 elevation-1"
          placeholder="🔎 Buscar cachorro por nome, raça..."
        />
      </v-col>
    </v-row>

    <!-- LISTA -->
    <div class="d-flex align-center ga-2 mt-6 mb-4">
      <v-icon color="primary">
        mdi-paw
      </v-icon>

      <h2 class="text-h5 font-weight-bold">
        Meus Doguinhos
      </h2>
    </div>

    <div v-if="dogs.length === 0" class="text-center text-muted">
      Nenhum cachorro encontrado.
    </div>

    <v-row spacing="4">
      <v-col
        v-for="dog in dogs"
        :key="dog.id"
        cols="12"
        sm="6"
        md="4"
        lg="3"
      >
        <v-card
          class="dog-card position-relative h-100"
          color="white"
          elevation="3"
          rounded="xl"
        >
          <BaseButton
            icon="mdi-close"
            variant="text"
            size="small"
            class="dismiss-btn"
            :disabled="excludingId === dog.id"
            @click="removeDog(dog)"
          />

          <v-img :src="dog.foto" class="card-img-top dog-img" alt="dog" />

          <v-card class="card-body pa-4" rounded="xl" elevation="2">
            <v-card-title class="text-h5 font-weight-bold pb-2">
              {{ dog.nome }}
            </v-card-title>
            
            <v-card-text class="py-1">🐾 {{ dog.raca }}</v-card-text>
            <v-card-text class="py-1">🎂 {{ dog.idade }} anos</v-card-text>
            <v-card-text class="py-1 mb-2">📦 {{ dog.porte }}</v-card-text>

            <BaseButton
              label="Editar"
              color="primary"
              variant="outlined"
              class="edit-btn mt-3"
              @click="editDog(dog)"
            />
          </v-card>

        </v-card>
      </v-col>
    </v-row>

    <div v-if="showModal" class="modal-overlay">
      <div class="modal-box">
        <div class="modal-header d-flex justify-content-between align-items-center">
          <h5 class="modal-title pure-text">
            {{ formDog.id ? "Editar cachorro" : "Cadastrar cachorro" }}
          </h5>
          
          <BaseButton
            icon="mdi-close"
            variant="text"
            size="small"
            class="modal-close-btn"
            @click="closeModal"
          />

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
  </v-container>
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

.edit-btn {
  width: 100%;
  border-radius: 999px !important;
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
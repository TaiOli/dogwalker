<template>
  <div class="container">
    <h2>🐕 Cadastrar cachorro</h2>
    <p>Preencha os dados do pet</p>

    <div class="form-wrapper">
      <DogForm
        :form="formDog"
        labelButton="Salvar"
        @submit="salvar"
      />
    </div>
  </div>
</template>

<script setup>
import DogForm from "../components/molecules/DogForm.vue";
import { useDog } from "../composables/useDog";

const { formDog, cadastrarDog, clearDog } = useDog();

async function salvar() {
  try {
    await cadastrarDog();

    alert("Cachorro cadastrado com sucesso!");

    clearDog();
  } catch (error) {
    console.log(error.response?.data);
    alert("Erro ao cadastrar cachorro");
  }
}
</script>

<style scoped>
.container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 40px;
}

.form-wrapper {
  width: 100%;
  max-width: 600px;
}

h2 {
  margin-bottom: 8px;
}

p {
  margin-bottom: 20px;
  color: #666;
  font-size: 15px;
}
</style>
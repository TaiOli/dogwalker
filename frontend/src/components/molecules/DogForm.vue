<script setup>
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";

defineProps({
  form: Object,
  labelButton: String
});

const emit = defineEmits(["submit"]);
</script>

<template>
  <div class="form">

    <div class="mb-2">
      <BaseInput v-model="form.nome" placeholder="Nome do cachorro" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.idade" placeholder="Idade" type="number" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.porte" placeholder="Porte (pequeno, médio, grande)" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.raca" placeholder="Raça" />
    </div>

    <div class="mb-2">
      <BaseInput v-model="form.observacoes" placeholder="Observações" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.foto" placeholder="URL da foto" />
    </div>

    <BaseButton class="w-100" :label="labelButton" @click="emit('submit')"/>
  </div>
</template>

<style scoped>
.form {
  width: 100%;
}
</style>
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
    <BaseInput v-model="form.nome" placeholder="Nome do cachorro"/>
    <BaseInput v-model="form.idade" placeholder="Idade" type="number"/>
    <BaseInput v-model="form.porte" placeholder="Porte (pequeno, médio, grande)"/>
    <BaseInput v-model="form.raca" placeholder="Raça"/>
    <BaseInput v-model="form.observacoes" placeholder="Observações"/>
    <BaseInput v-model="form.foto" placeholder="URL da foto"/>

    <BaseButton :label="labelButton" @click="emit('submit')"/>
  </div>
</template>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
</style>
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
    <BaseInput v-model="form.name" placeholder="Nome de usuário" />
    <BaseInput v-model="form.nome" placeholder="Nome completo" />
    <BaseInput v-model="form.email" placeholder="Email" />
    <BaseInput v-model="form.password" placeholder="Senha" type="password" />

    <BaseButton :label="labelButton" @click="emit('submit')" />
  </div>
</template>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin: 20px 0;
}
</style>
<script setup>
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";
import BaseSelect from "../atoms/BaseSelect.vue";

const props = defineProps({
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

    <BaseSelect v-model="form.tipo_usuario" label="Tipo de usuário">
      <option value="tutor">Tutor</option>
      <option value="passeador">Passeador</option>
    </BaseSelect>

    <BaseInput v-model="form.telefone" placeholder="Telefone (opcional)" />

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
<script setup>
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";
import BaseSelect from "../atoms/BaseSelect.vue";

const props = defineProps({
  form: Object,
  labelButton: String
});

const emit = defineEmits(["submit"]);

const tiposUsuario = [
  { label: "Tutor", value: "tutor" },
  { label: "Passeador", value: "passeador" }
]
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <BaseInput v-model="form.name" placeholder="Nome de usuário" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.nome" placeholder="Nome completo" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.email" placeholder="Email" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.password" placeholder="Senha" type="password" />
    </div>

    <div class="mb-3">
      <label class="form-label text-start w-100">Tipo de usuário:</label>
      <div class="mb-3">
        <BaseSelect
          v-model="form.tipo_usuario"
          :options="tiposUsuario"
          labelKey="label"
          valueKey="value"
        />
      </div>
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.telefone" placeholder="Telefone (opcional)" />
    </div>

    <BaseButton
      class="w-100 mt-2"
      :label="labelButton"
      @click="emit('submit')"
    />

  </div>
</template>

<style scoped>
.form {
  width: 100%;
}
</style>
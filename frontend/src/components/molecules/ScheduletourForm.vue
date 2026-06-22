<script setup>
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

const props = defineProps({
  form: Object,
  dogs: Array
})

const emit = defineEmits(["submit"])

</script>

<template>
  <div class="form">

    <BaseSelect
      v-model="form.dog_id"
      :options="dogs"
      labelKey="nome"
      valueKey="id"
      label="Selecione"
    />

    <BaseInput v-model="form.data" type="date" />
    <BaseInput v-model="form.hora" type="time" />
    <BaseInput v-model="form.duracao" type="time" />
    <BaseInput v-model="form.local" placeholder="Local" />
    <BaseInput v-model="form.valor" placeholder="Valor a pagar" type="number" step="0.01" min="0"/>
    <BaseButton label="Solicitar passeio" @click="emit('submit')" />

  </div>
</template>

<style scoped>
.form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
</style>
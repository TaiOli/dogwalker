<script setup>
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";

const props = defineProps({
  form: Object,
  labelButton: String
});

const emit = defineEmits(["submit"]);
</script>

<template>
  <div class="form">
    <BaseInput v-model="form.email" placeholder="Email" />
    <BaseInput v-model="form.password" placeholder="Senha" type="password" />

    <BaseButton :label="labelButton" @click="emit('submit')" />
  </div>
</template>
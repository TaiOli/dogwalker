<script setup>
import BaseButton from "../atoms/BaseButton.vue"
import BaseInput from "../atoms/BaseInput.vue";

defineProps({
    form:Object,
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

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin: 20px 0;
}
</style>
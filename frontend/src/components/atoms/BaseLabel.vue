<script setup lang="ts">
interface BaseLabelProps {
  text?: string
  htmlFor?: string
  required?: boolean
}

withDefaults(defineProps<BaseLabelProps>(), {
  text: "",
  htmlFor: undefined,
  required: false
})
</script>

<template>
  <label :for="htmlFor" class="form-label text-start w-100">
    <slot>{{ text }}</slot>
    <span v-if="required" class="text-danger ms-1">*</span>
  </label>
</template>

<style scoped>
.form-label {
  font-weight: 500;
}
</style>
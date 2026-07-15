<script setup lang="ts">
interface BaseLabelProps {
  text?: string
  htmlFor?: string
}

withDefaults(defineProps<BaseLabelProps>(), {
  text: "",
  htmlFor: undefined
})
</script>

<template>
  <v-text-field :for="htmlFor" class="form-label text-start w-100">
    <slot>{{ text }}</slot>
  </v-text-field>
</template>

<style scoped>
.form-label {
  font-weight: 500;
}
</style>
<script setup lang="ts">
defineProps<{ name: string }>();
</script>

<template>
  <v-icon>
    {{name}}
    <slot></slot>
  </v-icon>
</template>
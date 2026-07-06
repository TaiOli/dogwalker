<script setup>
defineProps({
  label: String
})

const emit = defineEmits(["click"])
</script>

<template>
  <button
    type="button"
    class="btn btn-success w-100 py-2 fw-bold"
    @click="$emit('click')"
  >
    {{ label }}
  </button>
</template>
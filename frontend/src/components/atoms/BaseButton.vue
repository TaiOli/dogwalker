<script setup lang="ts">
interface BaseButtonProps {
  label: string
}

defineProps<BaseButtonProps>()

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()
</script>

<template>
  <v-btn
    type="button"
    class="btn btn-success w-100 py-2 fw-bold"
    @click="emit('click', $event)"
  >
    {{ label }}
</v-btn>
</template>
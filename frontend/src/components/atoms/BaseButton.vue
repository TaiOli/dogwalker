<script setup lang="ts">
interface BaseButtonProps {
  label: string
  type?: "button" | "submit" | "reset"
}

withDefaults(defineProps<BaseButtonProps>(), {
  type: "button",
})

const emit = defineEmits<{
  (e: "click", event: MouseEvent): void
}>()
</script>

<template>
  <v-btn
    :type="type"
    @click="emit('click', $event)"
  >
     <slot>
      {{ label }}
    </slot>
  </v-btn>
</template>
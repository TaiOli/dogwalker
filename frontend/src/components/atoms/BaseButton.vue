<script setup lang="ts">
interface BaseButtonProps {
  label?: string;
  type?: "button" | "submit" | "reset";
  color?: string;
  variant?: "flat" | "text" | "elevated" | "outlined" | "plain" | "tonal";
  size?: "x-small" | "small" | "default" | "large" | "x-large";
  icon?: string;
  disabled?: boolean;
}

withDefaults(defineProps<BaseButtonProps>(), {
  type: "button",
});

const emit = defineEmits<{
  (e: "click", event: MouseEvent): void;
}>();
</script>

<template>
  <v-btn
    :type="type"
    :color="color"
    :variant="variant"
    :size="size"
    :disabled="disabled"
    @click="emit('click', $event)"
  >
    <v-icon v-if="icon">{{ icon }}</v-icon>

    <slot>
      {{ label }}
    </slot>
  </v-btn>
</template>
<!-- components/atoms/BaseToast.vue -->
<script setup lang="ts">
import { computed } from "vue";

interface Props {
  modelValue: boolean;
  type?: "success" | "error" | "info" | "warning";
  text: string;
  timeout?: number;
}

const props = withDefaults(defineProps<Props>(), {
  type: "success",
  timeout: 4000,
});

const emit = defineEmits(["update:modelValue"]);

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const iconName = computed(() => {
  switch (props.type) {
    case "success":
      return "mdi-check-circle";
    case "error":
      return "mdi-alert-circle";
    case "warning":
      return "mdi-alert";
    default:
      return "mdi-information";
  }
});
</script>

<template>
  <v-snackbar
    v-model="isOpen"
    :color="type"
    :timeout="timeout"
    location="top right"
    elevation="4"
    rounded="lg"
  >
    <div class="d-flex align-center canvas-content">
      <v-icon :icon="iconName" class="me-2"></v-icon>
      <span>{{ text }}</span>
    </div>

    <template v-slot:actions>
      <v-btn
        variant="text"
        icon="mdi-close"
        density="comfortable"
        @click="isOpen = false"
      ></v-btn>
    </template>
  </v-snackbar>
</template>
<script setup lang="ts">
interface BaseTextareaProps {
  modelValue: string;
  placeholder?: string;
  rows?: number;
  label?: string;
}

withDefaults(defineProps<BaseTextareaProps>(), {
  placeholder: "",
  rows: 3,
});

const emit = defineEmits<{
  "update:modelValue": [value: string];
}>();
</script>

<template>
  <div>
    <label v-if="label" class="form-label mb-1">
      {{ label }}
    </label>

    <v-textarea
      :model-value="modelValue"
      @update:model-value="(value) => emit('update:modelValue', value)"
      :placeholder="placeholder"
      variant="outlined"
      density="comfortable"
      hide-details
    />
  </div>
</template>
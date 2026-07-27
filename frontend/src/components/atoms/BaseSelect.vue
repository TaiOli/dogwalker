<script setup lang="ts">
import { computed } from "vue";

interface SelectOption {
  id?: string | number;
  nome?: string;
  name?: string;
  label?: string;
  value?: string | number;
  [key: string]: unknown;
}

interface BaseSelectProps {
  modelValue?: string | number | null;
  items?: SelectOption[] | string[];
  labelKey?: string;
  valueKey?: string;
  label?: string;
  placeholder?: string;
  required?: boolean;
  errorMessage?: string;
  prependInnerIcon?: string;
  variant?: "underlined" | "outlined" | "filled" | "solo" | "plain";
}

const props = withDefaults(defineProps<BaseSelectProps>(), {
  labelKey: "label",
  valueKey: "value",
  placeholder: "",
  required: false,
  errorMessage: "",
  prependInnerIcon: "",
  variant: "underlined",
});

const emit = defineEmits<{
  (e: "update:modelValue", value: string | number | null): void;
}>();

const selectItems = computed(() => {
  return props.items || [];
});
</script>

<template>
  <v-select
    :label="label"
    :items="selectItems"
    :item-title="labelKey"
    :item-value="valueKey"
    :placeholder="placeholder"
    persistent-placeholder
    :model-value="modelValue"
    :error="!!errorMessage"
    :error-messages="errorMessage"
    :prepend-inner-icon="prependInnerIcon"
    :variant="variant"
    hide-details="auto"
    @update:modelValue="emit('update:modelValue', $event)"
  />
</template>
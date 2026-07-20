<script setup lang="ts">
interface SelectOption {
  id?: string | number
  nome?: string
  name?: string
  label?: string
  value?: string | number
  [key: string]: unknown
}

interface BaseSelectProps {
  modelValue?: string | number | null
  options: SelectOption[]
  labelKey?: string
  valueKey?: string
  label?: string
  placeholder?: string
  required?: boolean
  errorMessage?: string
  prependInnerIcon?: string
}

withDefaults(defineProps<BaseSelectProps>(), {
  labelKey: "label",
  valueKey: "value",
  placeholder: "",
  required: false,
  errorMessage: "",
  prependInnerIcon: "",
})

const emit = defineEmits<{
  (e: "update:modelValue", value: string | number | null): void
}>()
</script>

<template>
  <v-select
    :label="required ? `${label} *` : label"
    :items="options"
    :item-title="labelKey"
    :item-value="valueKey"
    :placeholder="placeholder"
    :model-value="modelValue"
    :error="!!errorMessage"
    :error-messages="errorMessage"
    :prepend-inner-icon="prependInnerIcon"
    hide-details="auto"
    @update:modelValue="emit('update:modelValue', $event)"
  />
</template>
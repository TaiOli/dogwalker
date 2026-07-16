<script setup lang="ts">
interface BaseInputProps {
  modelValue?: string | number | File | File[] | null
  placeholder?: string
  label?: string
  type?: string
  accept?: string
  required?: boolean
  readonly?: boolean
  errorMessage?: string
  
}

withDefaults(defineProps<BaseInputProps>(), {
  placeholder: "",
  label: "",
  type: "text",
  accept: "",
  required: false,
  readonly: false,
  errorMessage: "",
})

const emit = defineEmits<{
  (
    e: "update:modelValue",
    value: string | number | File | File[] | null
  ): void
}>()
</script>

<template>
  <v-file-input
    v-if="type === 'file'"
    :label="required ? `${label} *` : label"
    :accept="accept"
    :model-value="modelValue as any"
    :error="!!errorMessage"
    :error-messages="errorMessage"
    @update:modelValue="emit('update:modelValue', $event)"
  />

  <v-text-field
    v-else
    :label="required ? `${label} *` : label"
    :model-value="modelValue"
    :placeholder="placeholder"
    :type="type"
    :readonly="readonly"
    :error="!!errorMessage"
    :error-messages="errorMessage"
    @update:modelValue="emit('update:modelValue', $event)"
  />
</template>
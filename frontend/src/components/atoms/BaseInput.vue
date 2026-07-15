<script setup lang="ts">
interface BaseInputProps {
  modelValue?: string | number | null
  placeholder?: string
  label?: string
  type?: string
  accept?: string
  required?: boolean
  readonly?: boolean
}

withDefaults(defineProps<BaseInputProps>(), {
  placeholder: "",
  label: "",
  type: "text",
  accept: "",
  required: false,
  readonly: false,
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
    :model-value="modelValue"
    @update:modelValue="emit('update:modelValue', $event)"
  />

  <v-text-field
    v-else
    :label="required ? `${label} *` : label"
    :model-value="modelValue"
    :placeholder="placeholder"
    :type="type"
    :readonly="readonly"
    @update:modelValue="emit('update:modelValue', $event)"
  />
</template>
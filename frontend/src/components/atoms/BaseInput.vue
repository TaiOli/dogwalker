<script setup lang="ts">

interface BaseInputProps {
  modelValue: string
  placeholder: string
  type: {
    type: string
    default: "text"
  }
}
defineProps<BaseInputProps>()
  
const emit = defineEmits<{
  "update:modelValue": [value: string]
}>()
</script>

<template>
  <input
    class="form-control"
    :value="modelValue"
    :placeholder="placeholder"
    :type="type"
    @input="emit('update:modelValue', $event.target.value)"
  />
</template>
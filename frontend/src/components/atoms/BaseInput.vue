<script setup lang="ts">
interface BaseInputProps {
  modelValue: string | number
  placeholder?: string
  type?: string
}

withDefaults(defineProps<BaseInputProps>(), {
  placeholder: "",
  type: "text"
})

const emit = defineEmits<{
  "update:modelValue": [value: string]
}>()

function onInput(event: Event): void {
  const target = event.target as HTMLInputElement
  emit("update:modelValue", target.value)
}
</script>

<template>
  <input
    class="form-control"
    :value="modelValue"
    :placeholder="placeholder"
    :type="type"
    @input="onInput"
  />
</template>
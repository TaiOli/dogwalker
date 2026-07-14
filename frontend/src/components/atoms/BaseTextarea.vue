<script setup lang="ts">
interface BaseTextareaProps {
  modelValue: string
  placeholder?: string
  rows?: number
  label?: string
}

withDefaults(defineProps<BaseTextareaProps>(), {
  placeholder: "",
  rows: 3
})

const emit = defineEmits<{
  "update:modelValue": [value: string]
}>()

function onInput(event: Event): void {
  const target = event.target as HTMLTextAreaElement
  emit("update:modelValue", target.value)
}
</script>

<template>
  <div>
    <v-text-field v-if="label" class="form-label text-start w-100">{{ label }}</v-text-field>
    <v-textarea
      class="form-control"
      :value="modelValue"
      :placeholder="placeholder"
      :rows="rows"
      @input="onInput"
    />
  </div>
</template>

<style scoped>
textarea {
  resize: none;
}
</style>
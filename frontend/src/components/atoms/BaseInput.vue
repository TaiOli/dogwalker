<script setup lang="ts">
interface BaseInputProps {
  modelValue?: string | number | null
  placeholder?: string
  type?: string 
  accept?: string 
}

const props = withDefaults(defineProps<BaseInputProps>(), {
  placeholder: "",
  type: "text",
  accept: ""
})

const emit = defineEmits<{
  "update:modelValue": [value: string]
   "change": [value: FileList | null]
}>()

function onInput(event: Event): void {
  // Ignora o onInput se for arquivo para não quebrar o value
  if (props.type === 'file') return
  
  const target = event.target as HTMLInputElement
  emit("update:modelValue", target.value)
}

function onChange(event: Event): void {
  // Dispara o evento específico para arquivos
  if (props.type === 'file') {
    const target = event.target as HTMLInputElement
    emit("change", target.files)
  }
}
</script>

<template>
  <v-input
    class="form-control"
    :value="type !== 'file' ? modelValue : undefined" 
    :placeholder="placeholder"
    :type="type"
    :accept="accept"
    @input="onInput"
    @change="onChange"
  />
</template>
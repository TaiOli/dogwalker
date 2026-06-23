<script setup>
defineProps({
  modelValue: String,
  placeholder: String,
  type: {
    type: String,
    default: "text"
  }
})

const emit = defineEmits(["update:modelValue"])
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
<script setup>
defineProps({
  modelValue:String,
  placeholder:String,
  type:{
    type:String,
    default:"text"
  }
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
  <input
    :value="modelValue"
    :placeholder="placeholder"
    :type="type"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<style scoped>
input {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
  background: white;
}

input:focus {
  border-color: #42b983;
}
</style>
<script setup>
const props = defineProps({
  modelValue: String,
  label: String
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
  <select
    :value="modelValue"
    @change="emit('update:modelValue', $event.target.value)"
  >
    <option disabled value="">
      {{ label || "Selecione uma opção" }}
    </option>

    <slot />
  </select>
</template>

<style scoped>
select {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: white;
  outline: none;
}

select:focus {
  border-color: #42b983;
}
</style>
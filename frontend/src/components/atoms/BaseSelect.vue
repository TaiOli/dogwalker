<script setup>
const props = defineProps({
  modelValue: [String, Number],
  options: Array,
  labelKey: String,
  valueKey: String,
  label: String
})

const emit = defineEmits(["update:modelValue"])

function updateValue(event) {
  const value = event.target.value

  emit(
    "update:modelValue",
    value === "" ? "" : value
  )
}
</script>

<template>
  <select :value="modelValue" @change="updateValue">
    <option value="">
      {{ label || "Selecione uma opção" }}
    </option>

    <option
      v-for="item in options"
      :key="item[valueKey]"
      :value="item[valueKey]"
    >
      {{ item[labelKey] }}
    </option>
  </select>
</template>

<style scoped>
select {
  width: 100%;
  padding: 10px 12px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: white;
  outline: none;
}

select:focus {
  border-color: #42b983;
}
</style>
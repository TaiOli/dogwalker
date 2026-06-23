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
  emit("update:modelValue", value === "" ? "" : value)
}
</script>

<template>
  <select
    class="form-select"
    :value="modelValue"
    @change="updateValue"
  >
  
    <option
      v-for="item in options"
      :key="item[valueKey]"
      :value="item[valueKey]"
    >
      {{ item[labelKey] }}
    </option>
  </select>
</template>
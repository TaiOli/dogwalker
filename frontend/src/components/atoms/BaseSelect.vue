<script setup lang="ts">
interface SelectOption {
  [key: string]: string | number
}

interface BaseSelectProps {
  modelValue: string | number
  options: SelectOption[]
  labelKey: string
  valueKey: string
  label?: string
  placeholder?: string
}

const props = defineProps<BaseSelectProps>()

const emit = defineEmits<{
  "update:modelValue": [value: string | number]
}>()

function updateValue(event: Event) {
  const target = event.target as HTMLSelectElement
  const selected = props.options.find(
    (item) => String(item[props.valueKey]) === target.value
  )
  if (selected) {
    emit("update:modelValue", selected[props.valueKey])
  }
}
</script>

<template>
  <div>
    <label v-if="label" class="form-label text-start w-100">{{ label }}</label>
    <select class="form-select" :value="modelValue" @change="updateValue">
      <option value="" disabled>{{ placeholder }}</option>
      <option
        v-for="item in options"
        :key="item[valueKey]"
        :value="item[valueKey]"
      >
        {{ item[labelKey] }}
      </option>
    </select>
  </div>
</template>
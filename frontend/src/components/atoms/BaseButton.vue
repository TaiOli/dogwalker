<script setup>
defineProps({
  label: String
});

const emit = defineEmits(["click"]);
</script>

<template>
    <button @click="$emit('click')">
      {{ label }}
    </button>
</template>

<style scoped>
button {
  width: 100%;
  padding: 12px;
  font-size: 14px;
  border: none;
  background: #42b983;
  color: white;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

button:hover {
  background: #369b6f;
}

</style>
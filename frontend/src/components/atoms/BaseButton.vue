<script setup>
defineProps({
    label:String
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <button>
        {{ label }}
    </button>
</template>

<style scoped>
button {
  width: 100%;
  padding: 10px;
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
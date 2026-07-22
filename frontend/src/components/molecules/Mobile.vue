<script setup lang="ts">
import BaseButton from "../atoms/BaseButton.vue";
import dogPaw from "../../assets/images/pata-coracao.png";
import dogImageUrl from "../../assets/images/dog.png";
import { ref, computed } from "vue";

const dogImage = ref<string>(dogImageUrl);
const bgImageStyle = computed<string>(() => `url(${dogImage.value})`);

const emit = defineEmits<{
  start: [];
}>();

function start(): void {
  emit("start");
}
</script>

<template>
  <v-container fluid class="home-screen pa-0">
    <div class="brand-name">
      <v-img :src="dogPaw" alt="Pata" class="paw-icon" />
      <span>Dog Walker</span>
    </div>

    <div class="btn-wrapper">
      <BaseButton
        label="Começar"
        size="large"
        class="btn-mustard"
        @click="start"
      />
    </div>
  </v-container>
</template>

<style scoped>
.home-screen {
  position: relative;
  width: 100%;
  min-height: 100vh;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-image: v-bind(bgImageStyle);
}

.brand-name {
  position: absolute;
  top: 16px;
  right: 20px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 600;
  font-family: "Instrument Sans", sans-serif;
  font-size: 1.25rem;
  color: black;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

.paw-icon {
  width: 30px;
  height: 20px;
  object-fit: contain;
}

.btn-wrapper {
  position: absolute;
  bottom: 32px;
  left: 50%;
  transform: translateX(-50%);
}
</style>
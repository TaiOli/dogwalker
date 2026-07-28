<script setup lang="ts">
import BaseButton from "../atoms/BaseButton.vue";
import BaseIcon from "../atoms/BaseIcon.vue";
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
    <div class="d-flex d-md-none">
      <BaseIcon name="mdi-paw-outline" class="mr-2" color="primary"/>
      <span class="text-primary">Dog Walker</span>
    </div>

    <div class="btn-wrapper d-flex d-md-none position-absolute">
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

.btn-mustard {
  border: 2px solid #1f1f1f;
  box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.4);
}

.btn-wrapper {
  bottom: 32px;
  left: 58%;
  transform: translateX(-50%);
}
</style>
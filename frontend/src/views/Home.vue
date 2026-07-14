<script setup lang="ts">
import BaseButton from '../components/atoms/BaseButton.vue';
import { useRouter } from "vue-router"
import dogImage from '../assets/images/dog.png'

const router = useRouter()

function start(): void {
  router.push("/login")
}
</script>

<template>
  <div class="home-screen" :style="{ backgroundImage: `url(${dogImage})` }">
    <p class="brand-name">🐾 Dog Walker</p>

    <div class="content">
      <BaseButton
        class="btn btn-primary btn-rounded mt-3"
        label="Começar"
        @click="start"
      ></BaseButton>
    </div>
  </div>
</template>

<style scoped>
.home-screen {
  position: relative;
  width: 100%;
  min-height: 100vh;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.brand-name {
  position: absolute;
  top: 16px;
  right: 20px;
  margin: 0;
  font-weight: 600;
  color: #fff;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

.content {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.btn-rounded {
  border-radius: 50px;
  padding: 10px 32px;
}
</style>
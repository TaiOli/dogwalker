<script setup lang="ts">
import BaseButton from '../components/atoms/BaseButton.vue';
import { useRouter } from "vue-router"
import dogImage from '../assets/images/dog.png'
import dogPaw from '../assets/images/pata-coracao.png'

const router = useRouter()

function start(): void {
  router.push("/login")
}
</script>

<template>
  <div class="home-screen">
    <div class="brand-name">
      <img :src="dogPaw" alt="Pata" class="paw-icon" />
      <span>Dog Walker</span>
    </div>

    <div class="btn-wrapper">
      <BaseButton
        class="btn btn-primary btn-mustard"
        label="Começar"
        @click="start"
      />
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
  background-image: v-bind('`url(${dogImage})`'); 
}

.brand-name {
  position: absolute;
  top: 16px;
  right: 20px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 600;
  font-family: 'Instrument Sans', sans-serif;
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

.btn-mustard {
  background-color: #D4A017;
  border-color: #D4A017;
  color: #1F1F1F;
  font-weight: 600;
  transition: 0.3s ease;
  width: auto !important;    
  white-space: nowrap;
  border-radius: 50px;
  padding: 10px 48px;
}

.btn-mustard:hover {
  background-color: #B88A12;
  border-color: #B88A12;
  color: #1F1F1F;
}

.btn-mustard:focus,
.btn-mustard:active {
  background-color: #A97C10 !important;
  border-color: #A97C10 !important;
  color: #1F1F1F !important;
  box-shadow: 0 0 0 0.25rem rgba(212, 160, 23, 0.35);
}
</style>
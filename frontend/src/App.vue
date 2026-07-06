<script setup>
import { computed } from "vue"
import { useRoute } from "vue-router"
import SidebarMenu from "./components/organisms/SidebarMenu.vue"
import Topbar from "./components/organisms/TopBar.vue"

const route = useRoute()

// se está na página de login ou cadastro
const isLoginPage = computed(() => {
  return route.path === "/" || route.path === "/cadastro-usuario"
})
</script>

<template>
  <div class="layout">
    <SidebarMenu v-if="!isLoginPage" />

    <div class="right-container">
      <Topbar v-if="!isLoginPage" />

      <div class="page">
        <router-view />
      </div>
    </div>
  </div>
</template>

<style>
.layout {
  display: flex;
  width: 100%;
  height: 100vh;
}

.right-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #f5f6f8;
  overflow: hidden;
}

.page {
  flex: 1;
  overflow-y: auto;
  padding: 40px;
}
</style>
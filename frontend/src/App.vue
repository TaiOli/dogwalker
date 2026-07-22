<script setup lang="ts">
import { computed } from "vue";
import { useRoute } from "vue-router";
import SidebarMenu from "./components/organisms/SidebarMenu.vue";
import Topbar from "./components/organisms/TopBar.vue";
import BottomNavigation from "./components/organisms/BottomNavigation.vue";
import { useDisplay } from 'vuetify'

const route = useRoute();
const { mobile } = useDisplay()

// se está na página de login ou cadastro
const isLoginPage = computed<boolean>(() => {
  return route.path === "/login" || route.path === "/cadastro-usuario";
});
</script>

<template>
  <div class="layout">
    <SidebarMenu v-if="!isLoginPage && !mobile" />

    <div class="right-container">
      <Topbar v-if="mobile && !isLoginPage"/>

      <div class="page-app">
        <router-view />
      </div>
    </div>
    <BottomNavigation v-if="mobile && !isLoginPage"></BottomNavigation>
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

.page-app {
  flex: 1;
  overflow-y: auto;
  padding: 40px;
}
</style>
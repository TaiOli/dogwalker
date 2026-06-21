<template>
  <div class="layout">
    <SidebarMenu />
  </div>
</template>

<script setup>
import SidebarMenu from "../components/organisms/SidebarMenu.vue";
</script>


<style scoped>
.layout {
  display: flex;
  min-height: 100vh;
}
</style>
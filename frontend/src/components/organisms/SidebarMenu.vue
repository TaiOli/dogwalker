<script setup lang="ts">
import { ref } from "vue";
import { useAuth } from "../../composables/userAuth";
import BaseButton from "../atoms/BaseButton.vue";

const { tutor, walker } = useAuth();
const collapsed = ref(false);

function toggle(): void {
  collapsed.value = !collapsed.value;
}
</script>

<template>
  <aside :class="['sidebar', { collapsed }]">
    <div class="header">
      <h2 v-if="!collapsed" class="brand-title">
        <v-icon icon="mdi-paw-outline" color="white" class="me-2" />
        Dog Walker
      </h2>

      <BaseButton
        icon="mdi-menu"
        variant="text"
        size="small"
        color="white"
        class="toggle-btn"
        @click="toggle"
      >
      </BaseButton>
    </div>

    <v-list
      nav
      class="sidebar-nav"
      bg-color="transparent"
      density="comfortable"
    >
      <v-list-item
        to="/inicio"
        class="nav-item"
        prepend-icon="mdi-home"
        title="Inicio"
      />

      <v-list-item
        v-if="tutor"
        to="/cadastro-cachorro"
        class="nav-item"
        prepend-icon="mdi-dog"
        title="Meus cachorros"
      />

      <v-list-item
        v-if="tutor"
        to="/solicitar-passeio"
        class="nav-item"
        prepend-icon="mdi-plus"
        title="Solicitar passeio"
      />

      <v-list-item
        v-if="walker"
        to="/passeios"
        class="nav-item"
        prepend-icon="mdi-walk"
        title="Passeios disponíveis"
      />
    </v-list>
  </aside>
</template>

<style scoped>
.sidebar {
  width: 280px;
  height: 100vh;
  background: linear-gradient(135deg, #01071f 0%, #0a0e27 100%);
  color: white;
  padding: 16px;
  display: flex;
  flex-direction: column;
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border-right: 1px solid rgba(255, 255, 255, 0.1);
  overflow: hidden;
}

.sidebar.collapsed {
  width: 80px;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
  gap: 8px;
}

.brand-title {
  font-size: 1.0rem;
  font-weight: 600;
  margin: 0;
  white-space: nowrap;
  transition: opacity 0.3s ease;
}

.sidebar.collapsed .brand-title {
  display: none;
}

.toggle-btn {
  flex-shrink: 0;
  transition: transform 0.3s ease;
  padding-right: 15px;
}

.toggle-btn:hover {
  transform: scale(1.1);
}

.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  padding: 0;
}

.nav-item {
  color: rgba(255, 255, 255, 0.7) !important;
  border-radius: 8px;
  margin-bottom: 8px;
  min-height: 44px;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  text-decoration: none;
}

.nav-item:hover {
  background: rgba(255, 193, 7, 0.15) !important; 
  color: #FFC107 !important;                    
}

.nav-item.logout {
  color: rgba(231, 76, 60, 0.7) !important;
}

.nav-item.logout:hover {
  background: rgba(231, 76, 60, 0.1) !important;
  color: #e74c3c !important;
}

.nav-item :deep(.v-list-item__prepend) {
  color: inherit;
  margin-right: 16px;
}

.nav-item:hover :deep(.v-list-item__prepend) {
  color: inherit;
}

.sidebar.collapsed .nav-item {
  justify-content: center !important;
  --indent-padding: 0 !important;
  padding-inline: 0 !important;
}

.sidebar.collapsed .nav-item :deep(.v-list-item__prepend) {
  margin-right: 0 !important;
}

.sidebar.collapsed .nav-item :deep(.v-list-item__content) {
  display: none;
}

.sidebar.collapsed .nav-item :deep(.v-list-item__spacer) {
  display: none;
}

.sidebar-nav::-webkit-scrollbar {
  width: 6px;
}

.sidebar-nav::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}

.spacer {
  flex: 1;
}
</style>
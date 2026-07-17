<script setup lang="ts">
import { ref } from "vue"
import { useAuth } from "../../composables/userAuth"
import BaseButton from "../atoms/BaseButton.vue"

const { tutor, walker } = useAuth()
const collapsed = ref(false)

function toggle(): void {
  collapsed.value = !collapsed.value
}
</script>

<template>
  <aside :class="['sidebar', { collapsed }]">
    <v-row no-gutters align="center" :justify="collapsed ? 'center' : 'space-between'" class="header">
      <v-col v-if="!collapsed">
        <h2 class="m-0">🐶 Dog Walker</h2>
      </v-col>
       <v-col cols="auto">
        <BaseButton
          class="toggle"
          icon
          variant="outlined"
          color="white"
          size="small"
          label="Menu"
          @click="toggle"
        >
          ☰
        </BaseButton>
      </v-col>
    </v-row>

    <v-list nav class="nav mt-3" bg-color="transparent" density="comfortable">
      <v-list-item
        to="/dashboard"
        class="nav-item"
        prepend-icon="mdi-home"
      >
        <span v-if="!collapsed">Dashboard</span>
      </v-list-item>

      <v-list-item
        v-if="tutor"
        to="/cadastro-cachorro"
        class="nav-item"
        prepend-icon="mdi-dog"
      >
        <span v-if="!collapsed">Meus cachorros</span>
      </v-list-item>

      <v-list-item
        v-if="tutor"
        to="/agendar-passeio"
        class="nav-item"
        prepend-icon="mdi-plus"
      >
        <span v-if="!collapsed">Solicitar passeio</span>
      </v-list-item>

      <v-list-item
        v-if="walker"
        to="/passeios"
        class="nav-item text-nowrap"
        prepend-icon="mdi-walk"
      >
        <span v-if="!collapsed">Passeios disponíveis</span>
      </v-list-item>
    </v-list>
  </aside>
</template>

<style scoped>
.sidebar {
  width: 280px;
  background: #01071F;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  transition: 0.3s;
  overflow-y: auto;
  border-right: 1px solid #1a252f;
}

.sidebar.collapsed {
  width: 90px;
  padding: 20px 8px;
}

.header {
  margin-bottom: 20px;
}

.toggle {
  font-size: 16px;
}

.nav :deep(.v-list-item) {
  color: white;
  border-radius: 6px;
  margin-bottom: 8px;
  min-height: 44px;
  transition: background-color 0.2s ease;
}

.nav :deep(.v-list-item:hover) {
  background: #34495e;
}

.nav :deep(.v-list-item:hover .v-icon) {
  color: #2ecc71;
}

.nav-item{
  text-decoration: none;
}

.sidebar.collapsed .nav :deep(.v-list-item) {
  --indent-padding: 0px !important;
  padding-inline: 0 !important;
  justify-content: center !important;
}

.sidebar.collapsed .nav :deep(.v-list-item__prepend) {
  margin-inline: auto !important;
}

.sidebar.collapsed .nav :deep(.v-list-item__spacer) {
  display: none !important;
}

.sidebar.collapsed .nav :deep(.v-list-item-title) {
  display: none;
}
</style>
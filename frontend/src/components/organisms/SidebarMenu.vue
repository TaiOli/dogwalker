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
    <v-row no-gutters align="center" justify="space-between" class="header">
      <v-col v-if="!collapsed">
        <h2 class="m-0">🐶 Dog Walker</h2>
      </v-col>
       <v-col>
        <BaseButton
          class="toggle"
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

    <v-list nav class="nav mt-3" bg-color="transparent">
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
  width: 70px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.nav-item {
  color: white;
  text-decoration: none;
  padding: 10px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: 0.2s;
  font-size: 16px;
  margin-bottom: 8px;
}

.nav-item:hover {
  background: #34495e;
  color: #2ecc71;
}

.toggle {
  font-size: 14px;
}
</style>
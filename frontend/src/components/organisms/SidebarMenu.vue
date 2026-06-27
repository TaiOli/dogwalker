<template>
  <aside :class="['sidebar', { collapsed }]">
    <div class="header">
      <h2 v-if="!collapsed" class="m-0">🐶 Dog Walker</h2>
      <button class="toggle btn btn-outline-light btn-md" @click="toggle">☰</button>
    </div>

    <nav class="nav flex-column mt-3">
      <router-link to="/home" class="nav-item">
        🏠 <span v-if="!collapsed">Dashboard</span>
      </router-link>
      <router-link v-if="tutor" to="/dogs" class="nav-item">
        🐕 <span v-if="!collapsed">Meus cachorros</span>
      </router-link>
      <router-link v-if="tutor" to="/scheduletour" class="nav-item">
        ➕ <span v-if="!collapsed">Solicitar passeio</span>
      </router-link>
      <router-link v-if="walker" to="/walks" class="nav-item text-nowrap">
        🚶 <span v-if="!collapsed">Passeios disponíveis</span>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from "vue"
import { useAuth } from "../../composables/userAuth"

const auth = useAuth()
const tutor = auth.tutor
const walker = auth.walker
const collapsed = ref(false)

function toggle() {
  collapsed.value = !collapsed.value
}
</script>

<style scoped>
.sidebar {
  width: 280px;
  background: #2c3e50;
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
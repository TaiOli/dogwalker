<template>
  <aside :class="['sidebar', { collapsed }]">
    
    <div class="header">
      <h2 v-if="!collapsed">🐶 Dog Walker</h2>

      <button class="toggle" @click="toggle">
        ☰
      </button>
    </div>

    <nav class="nav">

      <router-link to="/home">
        🏠 <span v-if="!collapsed">Dashboard</span>
      </router-link>

      <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
      <router-link v-if="tutor()" to="/dogs">
        🐕 <span v-if="!collapsed">Meus cachorros</span>
      </router-link>

      <router-link v-if="tutor()" to="/scheduletour">
        ➕ <span v-if="!collapsed">Solicitar passeio</span>
      </router-link>

      <!-- VISUALIZAÇÃO SOMENTE PASSEADOR -->
      <router-link v-if="walker()" to="/walks">
        🚶 <span v-if="!collapsed">Passeios disponíveis</span>
      </router-link>

      <router-link v-if="walker()" to="/mytours">
        📋 <span v-if="!collapsed">Meus passeios</span>
      </router-link>

    </nav>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuth } from "../../composables/userAuth";

const router = useRouter();

const {
  tutor,
  walker,
  logout
} = useAuth();

const collapsed = ref(false);

function toggle() {
  collapsed.value = !collapsed.value;
}
</script>

<style scoped>
.sidebar {
  width: 220px;
  height: 100vh;
  background: #2c3e50;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  transition: 0.3s;
  overflow: hidden;
}

.sidebar.collapsed {
  width: 70px;
}

.sidebar.collapsed .header {
  justify-content: center;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 20px;
}

h2 {
  margin: 0;
  color: white;
  font-size: 18px;
  white-space: nowrap;
}

.toggle {
  background: transparent;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 17px;
  margin-left: 10px;
  
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 20px;
}

.nav span{
    font-size: 15px;
}

a {
  color: white;
  text-decoration: none;
  padding: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  border-radius: 6px;
  white-space: nowrap;
}

a:hover {
  background: #34495e;
}
</style>
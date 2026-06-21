<template>
  <aside :class="['sidebar', { collapsed }]">
    
    <div class="header">
      <h2 v-if="!collapsed">🐶 Dog Walker</h2>

      <button class="toggle" @click="toggle">
        ☰
      </button>
    </div>

    <nav class="nav">
      <router-link to="/home">🏠 <span v-if="!collapsed">Dashboard</span></router-link>
      <router-link to="/dogs">🐕 <span v-if="!collapsed">Dogs</span></router-link>
      <router-link to="/walks">🚶 <span v-if="!collapsed">Passeios</span></router-link>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuth } from "../../composables/userAuth";

const router = useRouter();
const { clearLogin } = useAuth();

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
  font-size: 20px;
  cursor: pointer;
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 20px;
}

a {
  color: white;
  text-decoration: none;
  padding: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  border-radius: 6px;
}

a:hover {
  background: #34495e;
}

/* mobile */
@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
  }

  .sidebar.collapsed {
    transform: translateX(-100%);
  }
}
</style>
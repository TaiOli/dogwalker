<template>
  <div class="layout">
    <SidebarMenu v-if="!isLoginPage" />
    <div class="right-container">
      
      <div v-if="!isLoginPage" class="topbar">
        <div></div>
        <div class="dropdown">
          <img
            :src="getFoto(auth.user.value?.foto)"
            class="profile-photo dropdown-toggle"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            alt="Perfil"
          />
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <router-link class="dropdown-item" to="/meu-perfil">
                👤 Meu Perfil
              </router-link>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a
                class="dropdown-item text-danger"
                href="#"
                @click.prevent="logout"
              >
                🚪 Sair
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="page">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter, useRoute } from "vue-router"
import { useAuth } from "./composables/userAuth"
import SidebarMenu from "./components/organisms/SidebarMenu.vue"
import { getFoto } from "./utils/image.js"
import { computed } from "vue"

const auth = useAuth()
const router = useRouter()
const route = useRoute()

// se está na página de login
const isLoginPage = computed(() =>
  ["/", "/cadastro-usuario"].includes(route.path)
)

function logout() {
  localStorage.removeItem("token")
  localStorage.removeItem("user")
  auth.clearAuth?.()
  router.push("/")
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body, #app {
  width: 100%;
  height: 100%;
}

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

.topbar {
  height: 60px;
  background: #2c3e50;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 0 20px;
  border-bottom: 1px solid #eee;
  z-index: 10;
  flex-shrink: 0;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.page {
  flex: 1;
  overflow-y: auto;
  padding: 40px;
}

.profile-photo {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
  cursor: pointer;
  border: 2px solid #2ecc71;
  display: block;
  transition: all 0.3s ease;
}

.profile-photo:hover {
  border-color: #27ae60;
  box-shadow: 0 0 8px rgba(46, 204, 113, 0.3);
}

.dropdown-menu {
  min-width: 200px;
}

.dropdown-item {
  cursor: pointer;
  padding: 0.5rem 1rem;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  color: #2c3e50;
}

.dropdown-item.text-danger {
  color: #e74c3c;
}

.dropdown-item.text-danger:hover {
  background-color: #ffe5e5;
  color: #c0392b;
}
</style>
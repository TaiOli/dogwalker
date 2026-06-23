<template>
  <div class="layout">
    <SidebarMenu v-if="isLogged && !isLoginPage" />
    <div class="page">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import SidebarMenu from "./components/organisms/SidebarMenu.vue"
import { useAuth } from "./composables/userAuth"
import { computed } from "vue"
import { useRoute } from "vue-router"

const auth = useAuth()
const route = useRoute()

// usuário logado
const isLogged = computed(() => !!auth.user.value)

// se está na página de login
const isLoginPage = computed(() =>
  ["/", "/cadastro-usuario"].includes(route.path)
)
</script>

<style>
.layout {
  display: flex;
  min-height: 100vh;
}

.page {
  flex: 1;
  background: #f5f6f8;
}
</style>
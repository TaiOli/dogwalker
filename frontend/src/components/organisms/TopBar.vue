<script setup>
import { useAuth } from "../../composables/userAuth"
import { getFoto } from "../../utils/image"

const auth = useAuth()

function logout(){
  auth.logout()
}
</script>

<template>
  <header class="topbar">

    <div></div>

    <div class="dropdown">

      <img
        :src="getFoto(auth.user.value?.foto)"
        class="profile-photo dropdown-toggle"
        data-bs-toggle="dropdown"
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

  </header>
</template>

<style scoped>
.topbar{
  height:60px;
  background:white;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:0 25px;
  border-bottom:1px solid #ddd;
}

.profile-photo{
  width:42px;
  height:42px;
  border-radius:50%;
  object-fit:cover;
  cursor:pointer;
}
</style>
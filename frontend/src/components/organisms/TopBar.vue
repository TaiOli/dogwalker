<script setup lang="ts">
import { useAuth } from "../../composables/userAuth"
import { getPhoto } from "../../utils/image"

const { user, logout } = useAuth()
</script>

<template>
  <header class="topbar">

    <div></div>

    <v-menu location="bottom end">
      <template #activator="{ props }">
        <v-avatar v-bind="props" size="42" class="profile-photo">
          <v-img :src="getPhoto(user?.foto)" cover />
        </v-avatar>
      </template>

      <v-list bg-color="white">
        <v-list-item to="/meu-perfil">
          👤 Meu Perfil
        </v-list-item>

        <v-divider />

        <v-list-item class="text-danger" @click="logout">
          🚪 Sair
        </v-list-item>
      </v-list>
    </v-menu>

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
  cursor:pointer;
}
</style>
<script setup lang="ts">
import { useAuth } from "../../composables/userAuth";
import { getPhoto } from "../../utils/image";

const { user, logout } = useAuth();

function handleLogout(): void {
  logout();
}
</script>

<template>
  <header class="topbar">
    <div class="page-info" />
    <div class="user-section">
      <v-menu location="bottom end" offset="8">
        <template #activator="{ props }">
          <v-avatar v-bind="props" size="44" class="profile-avatar">
            <v-img :src="getPhoto(user?.foto)" cover alt="Foto Perfil" />
          </v-avatar>
        </template>

        <v-card elevation="2" rounded="lg" min-width="200" color="white">
          <v-card-text class="pa-3">
            <div class="user-info">
              <div class="ml-3">
                <p class="text-body-2 font-weight-600 mb-0">{{ user?.nome }}</p>
                <p class="text-caption text-medium-emphasis mb-0">
                  {{ user?.email }}
                </p>
              </div>
            </div>
          </v-card-text>

          <v-divider />

          <v-list class="pa-0" density="comfortable">
            <v-list-item
              to="/meu-perfil"
              prepend-icon="mdi-account"
              title="Meu Perfil"
              class="profile-item"
            />
          </v-list>

          <v-divider />

          <v-list class="pa-0" density="comfortable">
            <v-list-item
              prepend-icon="mdi-logout"
              title="Sair"
              class="logout-item"
              @click="handleLogout"
            />
          </v-list>
        </v-card>
      </v-menu>
    </div>
  </header>
</template>

<style scoped>
.topbar {
  height: 70px;
  background: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 32px;
  border-bottom: 1px solid #e0e0e0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 0;
  z-index: 10;
}

.page-info {
  flex: 1;
}

.v-list-item {
  text-decoration: none;
  padding: 15px;
}

.user-section {
  display: flex;
  align-items: center;
  gap: 16px;
}

.profile-avatar {
  cursor: pointer;
  transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-avatar:hover {
  transform: scale(1.05);
}

.profile-item {
  color: rgb(var(--v-theme-primary)) !important;
  background-color: white;
  border-radius: 12px;
  transition: all 0.2s ease;
}

.profile-item:hover {
  background: rgba(255, 255, 255, 0.2) !important;
  color: #ffffff !important;
  cursor: pointer;
  border-radius: 12px;
}

.logout-item {
  color: #d32f2f !important;
  border-radius: 12px;
  transition: all 0.2s ease;
  background-color: white;
}

.logout-item:hover {
  background: rgba(255, 255, 255, 0.2) !important;
  color: #ffffff !important;
  cursor: pointer;
  border-radius: 12px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 15px;
}

@media (max-width: 768px) {
  .topbar {
    padding: 0 16px;
  }
}
</style>
<script setup lang="ts">
import { ref } from "vue";
import BaseButton from "../atoms/BaseButton.vue";
import { useAuth } from "../../composables/userAuth";

const { tutor, walker } = useAuth();
const activeTab = ref("recents");
</script>

<template>
  <v-app>
    <v-bottom-navigation v-model="activeTab" class="bottom-navigation" grow>
      <BaseButton to="/inicio" value="recents">
        <v-icon>mdi-home</v-icon>
        Inicio
      </BaseButton>

      <BaseButton v-if="tutor" to="/cadastro-cachorro">
        <v-icon>mdi-dog</v-icon>
        Meus cachorros
      </BaseButton>

      <BaseButton v-if="tutor" to="/solicitar-passeio">
        <v-icon>mdi-plus</v-icon>
        Solicitar passeio
      </BaseButton>

      <BaseButton v-if="walker" to="/passeios">
        <v-icon>mdi-walk</v-icon>
        Passeios disponíveis
      </BaseButton>
    </v-bottom-navigation>
  </v-app>
</template>

<style scoped>
.bottom-navigation {
  background: linear-gradient(135deg, #01071f 0%, #0a0e27 100%);
  border-radius: 50px;
}

.bottom-navigation basebutton,
.bottom-navigation a {
  color: white;
  text-decoration: none;
}
</style>
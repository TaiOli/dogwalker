<script setup lang="ts">
import { ref } from "vue";
import BaseButton from "../atoms/BaseButton.vue";
import { useAuth } from "../../composables/userAuth";
import BaseIcon from "../atoms/BaseIcon.vue";

const { tutor, walker } = useAuth();
const activeTab = ref("recents");
</script>

<template>
  <v-app>
    <v-bottom-navigation v-model="activeTab" class="bottom-navigation" grow>
      <BaseButton to="/inicio" value="recents">
        <BaseIcon name="mdi-home"/>
        Inicio
      </BaseButton>

      <BaseButton v-if="tutor" to="/cadastro-cachorro">
        <BaseIcon name="mdi-dog"/>
        Meus cachorros
      </BaseButton>

      <BaseButton v-if="tutor" to="/solicitar-passeio">
        <BaseIcon name="mdi-plus"/>
        Solicitar passeio
      </BaseButton>

      <BaseButton v-if="walker" to="/passeios">
        <BaseIcon name="mdi-walk"/>
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
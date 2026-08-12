<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import ScheduletourForm from "../components/molecules/ScheduletourForm.vue";
import { useScheduletour } from "../composables/useScheduletour";
import BaseIcon from "../components/atoms/BaseIcon.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";
import BaseAlert from "../components/atoms/BaseAlert.vue";

const route = useRoute();

const {
  form,
  dogs,
  walkers,
  loadDogs,
  loadWalkers,
  setWalker,
  requestTour,
  clearTour,
} = useScheduletour();

const alert = ref({
  show: false,
  type: "success" as "success" | "error",
  message: "",
});

function showFeedback(type: "success" | "error", message: string) {
  alert.value = { show: true, type, message };
}

onMounted(async () => {
  await Promise.all([loadDogs(), loadWalkers()]);

  const walkerId = route.query.walkerId;

  if (walkerId) {
    setWalker(Array.isArray(walkerId) ? walkerId[0] : walkerId);
  }
});

async function save(): Promise<void> {
  try {
    await requestTour();

    showFeedback("success", "Passeio solicitado com sucesso!");

    clearTour();
  } catch (error) {
    console.log(error);
    showFeedback("error", "Erro ao solicitar passeio");
  }
}
</script>

<template>
  <v-container class="py-6">
    <BaseAlert v-model="alert.show" :type="alert.type" :text="alert.message" />
    <v-row justify="center">
      <v-col cols="12" md="10" lg="8">
        <div class="d-flex align-center ga-2 mb-5">
          <BaseIcon name="mdi-plus" color="primary" size="32" />
          <BaseTypography variant="h2" class="text-black"
            >Solicitar Passeio</BaseTypography
          >
        </div>

        <v-card elevation="3" rounded="xl" class="pa-6" color="white">
          <ScheduletourForm
            :form="form"
            :dogs="dogs"
            :walkers="walkers"
            labelButton="Salvar"
            @submit="save"
          />
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuth } from "../composables/userAuth";
import { api } from "../services/api";
import { getPhoto } from "../utils/image";
import BaseButton from "../components/atoms/BaseButton.vue";
import BaseTextarea from "../components/atoms/BaseTextarea.vue";
import BaseIcon from "../components/atoms/BaseIcon.vue";
import BaseTypography from "../components/atoms/BaseTypography.vue";

interface Dog {
  id: number;
  nome: string;
}

interface Person {
  id: number;
  nome: string;
  telefone?: string;
  foto?: string;
  media_avaliacao?: number | null;
}

interface Review {
  rating: number;
  comment?: string | null;
}

type TourStatus =
  | "pendente"
  | "aceito"
  | "recusado"
  | "finalizado"
  | "cancelado";

interface Tour {
  id: number;
  data: string;
  hora: string;
  local: string;
  status: TourStatus;
  dog?: Dog;
  tutor?: Person;
  walker?: Person;
  rated_by_tutor?: boolean;
  review_by_tutor?: Review | null;
  review_by_walker?: Review | null;
}

type Evaluator = "tutor" | "passeador";

interface ApiErrorResponse {
  response?: {
    data?: {
      message?: string;
    };
  };
}

const auth = useAuth();

const tutor = computed(() => auth.tutor.value);
const walker = computed(() => auth.walker.value);

const walkers = ref<Person[]>([]);
const tours = ref<Tour[]>([]);

const reviewTutor = ref<Tour | null>(null);
const reviewWalker = ref<Tour | null>(null);

const rating = ref<number>(0);
const comment = ref<string>("");
const sending = ref<boolean>(false);

const DISMISSED_KEY = "dashboard_dismissed_tours";
const dismissedIds = ref<Set<number>>(new Set());

function loadDismissed(): void {
  try {
    const raw = localStorage.getItem(DISMISSED_KEY);
    if (raw) dismissedIds.value = new Set(JSON.parse(raw));
  } catch (e) {
    console.error(e);
  }
}

function saveDismissed(): void {
  try {
    localStorage.setItem(
      DISMISSED_KEY,
      JSON.stringify([...dismissedIds.value]),
    );
  } catch (e) {
    console.error(e);
  }
}

function dismissTour(id: number): void {
  dismissedIds.value.add(id);

  dismissedIds.value = new Set(dismissedIds.value);
  saveDismissed();
}

async function cancelConfirm(passeio: Tour): Promise<void> {
  const confirmed = window.confirm(
    "Tem certeza que deseja cancelar este passeio?",
  );
  if (!confirmed) return;

  try {
    await api.patch(`/tours/${passeio.id}/cancel`);
    passeio.status = "cancelado";
  } catch (err) {
    console.error(err);
    const error = err as ApiErrorResponse;
    alert(error.response?.data?.message || "Erro ao cancelar passeio.");
  }
}

function onXClickTutor(p: Tour): void {
  if (p.status === "pendente" || p.status === "aceito") {
    cancelConfirm(p);
  } else {
    dismissTour(p.id);
  }
}

const toursTutor = computed(() =>
  tours.value.filter((p) => {
    if (dismissedIds.value.has(p.id)) return false;
    if (p.status === "cancelado") return false;

    return (
      p.status === "pendente" ||
      p.status === "aceito" ||
      p.status === "recusado" ||
      (p.status === "finalizado" && !p.rated_by_tutor)
    );
  }),
);

const toursWalker = computed(() =>
  tours.value.filter((p) => {
    if (dismissedIds.value.has(p.id)) return false;
    if (p.status === "finalizado") return false;

    return p.status === "aceito" || p.status === "cancelado";
  }),
);

async function loadWalkers(): Promise<void> {
  try {
    const res = await api.get("/walkers");
    walkers.value = res.data;
  } catch (e) {
    console.error(e);
  }
}

async function loadTours(): Promise<void> {
  try {
    const res = await api.get("/my-tours");
    tours.value = res.data;
  } catch (e) {
    console.error(e);
  }
}

type BadgeStatus =
  | { variant: "flat"; style: { backgroundColor: string; color: string } }
  | { variant: "outlined"; color: string };

function badgeStatus(status: TourStatus): BadgeStatus {
  switch (status) {
    case "pendente":
      return {
        variant: "flat",
        style: {
          backgroundColor: "#fbecdc",
          color: "#9d673d",
        },
      };
    case "aceito":
      return {
        variant: "flat",
        style: {
          backgroundColor: "#EAF3DE",
          color: "#27500A",
        },
      };
    case "recusado":
      return {
        variant: "flat",
        style: {
          backgroundColor: "#FCEBEB",
          color: "#791F1F",
        },
      };
    case "finalizado":
      return {
        variant: "flat",
        style: {
          backgroundColor: "#E6F1FB",
          color: "#0C447C",
        },
      };
    case "cancelado":
      return {
        variant: "flat",
        style: {
          backgroundColor: "#F1EFE8",
          color: "#444441",
        },
      };
    default:
      return {
        variant: "flat",
        style: {
          backgroundColor: "#F1EFE8",
          color: "#444441",
        },
      };
  }
}

function openEvaluationTutor(passeio: Tour): void {
  reviewTutor.value = passeio;
  rating.value = 0;
  comment.value = "";
}

function cancelEvaluationTutor(): void {
  reviewTutor.value = null;
  rating.value = 0;
  comment.value = "";
}

function openEvaluationWalker(passeio: Tour): void {
  reviewWalker.value = passeio;
  rating.value = 0;
  comment.value = "";
}

function cancelEvaluationWalker(): void {
  reviewWalker.value = null;
  rating.value = 0;
  comment.value = "";
}

async function completeTour(passeio: Tour): Promise<void> {
  if (rating.value === 0) {
    alert("Escolha uma nota antes de finalizar.");
    return;
  }

  if (!confirm("Deseja finalizar este passeio?")) return;

  sending.value = true;

  try {
    await api.patch(`/tours/${passeio.id}/complete`);

    await api.post("/evaluation", {
      passeio_id: passeio.id,
      nota: rating.value,
      comentario: comment.value,
      tipo_avaliador: "passeador",
    });

    alert("Passeio finalizado e avaliação enviada!");

    cancelEvaluationWalker();
    await loadTours();
  } catch (err) {
    console.error(err);
    const error = err as ApiErrorResponse;
    alert(error.response?.data?.message || "Erro ao finalizar passeio.");
  } finally {
    sending.value = false;
  }
}

async function sendEvaluation(
  passeioId: number,
  tipo: Evaluator,
): Promise<void> {
  if (!rating.value) {
    alert("Escolha uma nota.");
    return;
  }

  sending.value = true;

  try {
    await api.post("/evaluation", {
      passeio_id: passeioId,
      nota: rating.value,
      comentario: comment.value,
      tipo_avaliador: tipo,
    });

    alert("Avaliação enviada!");

    cancelEvaluationTutor();
    await loadWalkers();
    await loadTours();
  } catch (err) {
    console.error(err);
    const error = err as ApiErrorResponse;
    alert(error.response?.data?.message || "Erro ao enviar avaliação.");
  } finally {
    sending.value = false;
  }
}

function formatDate(data: string | null | undefined): string {
  if (!data) return "";
  const date = new Date(data);
  return date.toLocaleDateString("pt-BR");
}

onMounted(async () => {
  loadDismissed();
  await loadTours();
  if (tutor.value) {
    await loadWalkers();
  }
});
</script>

<template>
  <v-container class="py-4">
    <!-- VISUALIZAÇÃO SOMENTE TUTOR -->
    <template v-if="tutor">
      <BaseTypography variant="h2" class="title mb-4 text-black">
        <BaseIcon color="primary" name="mdi-account" size="23" class="me-2" />
        Passeadores Disponíveis
      </BaseTypography>

      <v-row class="mb-5">
        <v-col cols="12" md="4" v-for="w in walkers" :key="w.id">
          <v-card class="h-100 card" elevation="2" color="white">
            <v-card-text class="text-center">
              <v-img
                :src="getPhoto(w.foto)"
                class="rounded-circle mx-auto mb-3"
                width="110"
                height="110"
                cover
              />
              <BaseTypography variant="h5">{{ w.nome }}</BaseTypography>
              <BaseTypography variant="subtitle-1">
                <BaseIcon name="mdi-phone" color="primary" />
                {{ w.telefone }}
              </BaseTypography>
              <BaseTypography variant="subtitle-1">
                <BaseIcon name="mdi-star-four-points" color="amber" />
                <span v-if="w.media_avaliacao">{{ w.media_avaliacao }}/5</span>
                <span v-else>Sem avaliações</span>
              </BaseTypography>
              <div class="d-flex flex-column ga-2">
                <BaseButton
                  label="Ver perfil"
                  class="text-decoration-none text-white"
                  :to="`/passeador-perfil/${w.id}`"
                  color="blue-lighten-1"
                  block
                />
                <BaseButton
                  :to="{
                    path: '/solicitar-passeio',
                    query: { walkerId: w.id, walkerNome: w.nome },
                  }"
                  color="success"
                  class="text-decoration-none"
                  variant="tonal"
                  label="Solicitar Passeio"
                  prepend-icon="mdi-paw"
                  block
                />
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <BaseTypography variant="h2" class="title mb-3">
        <BaseIcon
          name="mdi-note-text-outline"
          color="primary"
          size="23"
          class="me-2"
        />
        Meus Passeios
      </BaseTypography>

      <v-alert v-if="toursTutor.length === 0" type="info" variant="tonal">
        Você ainda não possui passeios.
      </v-alert>

      <v-card
        v-for="p in toursTutor"
        :key="p.id"
        class="mb-3 card"
        color="white"
        elevation="2"
      >
        <v-card-text>
          <div class="d-flex justify-end">
            <BaseButton
              v-if="['pendente', 'aceito', 'recusado'].includes(p.status)"
              icon="mdi-close"
              size="large"
              variant="text"
              label=""
              :aria-label="
                p.status === 'pendente' || p.status === 'aceito'
                  ? 'Cancelar passeio'
                  : 'Remover do dashboard'
              "
              :title="
                p.status === 'pendente' || p.status === 'aceito'
                  ? 'Cancelar passeio'
                  : 'Remover do dashboard'
              "
              @click="onXClickTutor(p)"
            />
          </div>

          <div class="text-black text-center">
            <BaseTypography
              variant="h5"
              class="d-flex justify-center align-center ga-2 mb-2"
            >
              <BaseIcon name="mdi-paw-outline" size="20" color="amber" />
              {{ p.dog?.nome }}
            </BaseTypography>

            <BaseTypography
              variant="subtitle-1"
              class="d-flex justify-center align-center ga-2 mb-2"
            >
              <BaseIcon name="mdi-calendar-outline" size="18" color="primary" />
              {{ formatDate(p.data) }} - {{ p.hora }}
            </BaseTypography>

            <BaseTypography
              variant="subtitle-1"
              class="d-flex justify-center align-center ga-2  mb-2"
            >
              <BaseIcon
                name="mdi-map-marker-outline"
                size="18"
                color="red-darken-4"
              />
              {{ p.local }}
            </BaseTypography>

            <BaseTypography
              variant="subtitle-1"
              class="d-flex justify-center align-center ga-2 text-black mb-2"
              v-if="p.walker"
            >
              <BaseIcon name="mdi-walk" size="16" color="primary" />
              Passeador:
              <BaseTypography variant="body-1" weight="bold">{{
                p.walker?.nome
              }}</BaseTypography>
            </BaseTypography>

            <BaseTypography
              variant="subtitle-1"
              class="d-flex justify-center align-center ga-2 text-black"
              v-else-if="p.status === 'recusado'"
            >
              <BaseIcon name="mdi-close-circle" size="16" color="error" />
              Nenhum passeador aceitou este passeio.
            </BaseTypography>

            <BaseTypography
              variant="subtitle-1"
              class="d-flex justify-center align-center ga-2 text-black mb-3"
              v-else
            >
              <BaseIcon
                name="mdi-timer-sand"
                size="16"
                color="orange-darken-2"
              />
              Aguardando um passeador aceitar
            </BaseTypography>
          </div>

          <v-chip
            v-bind="badgeStatus(p.status)"
            size="small"
            class="text-capitalize text-caption font-weight-medium px-4"
          >
            {{ p.status }}
          </v-chip>

          <div
            class="text-center"
            v-if="p.status === 'finalizado' && !p.rated_by_tutor"
          >
            <BaseButton
              color="amber"
              label="Avaliar Passeador"
              prepend-icon="mdi-star-four-points"
              class="btn-evaluation"
              @click="openEvaluationTutor(p)"
            />
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO TUTOR -->
          <v-sheet
            v-if="p.review_by_tutor"
            color="blue-lighten-5"
            rounded="lg"
            class="pa-3 mt-3"
            border="s-lg"
          >
            <BaseTypography variant="subtitle-1" class="mb-2 text-black">
              <BaseIcon name="mdi-check" color="green-darken-4" />
              Sua avaliação sobre o passeador
            </BaseTypography>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                <BaseIcon
                  :name="
                    n <= p.review_by_tutor.rating
                      ? 'mdi-star'
                      : 'mdi-star-outline'
                  "
                  color="amber"
                  size="20"
                />
              </span>
              <span class="text-medium-emphasis text-caption ms-1"
                >({{ p.review_by_tutor.rating }}/5)</span
              >
            </div>
            <BaseTypography
              variant="subtitle-1"
              v-if="p.review_by_tutor.comment"
              class="font-italic mb-0"
            >
              "{{ p.review_by_tutor.comment }}"
            </BaseTypography>
          </v-sheet>

          <v-expand-transition>
            <div v-if="reviewTutor?.id === p.id" class="mt-4">
              <v-divider class="mb-4" />

              <div class="d-flex justify-center align-center ga-2 mb-4">
                <BaseIcon name="mdi-star" color="amber" />

                <BaseTypography variant="h5" class="mb-0 text-black"
                  >Como foi o passeador?</BaseTypography
                >
              </div>

              <div class="text-center mb-4">
                <span
                  v-for="n in 5"
                  :key="n"
                  class="rating"
                  @click="rating = n"
                >
                  <BaseIcon
                    :name="n <= rating ? 'mdi-star' : 'mdi-star-outline'"
                    color="amber"
                    size="20"
                  />
                </span>
              </div>

              <BaseTextarea
                class="mb-4"
                :rows="3"
                placeholder=" Conte como foi sua experiência"
                v-model="comment"
              />

              <div class="d-flex justify-center ga-3">
                <BaseButton
                  color="success"
                  icon="mdi-send"
                  class="btn-evaluation"
                  :label="sending ? 'Enviando...' : 'Enviar avaliação'"
                  :disabled="sending"
                  @click="sendEvaluation(p.id, 'tutor')"
                />

                <BaseButton
                  color="error"
                  icon="mdi-close"
                  class="btn-evaluation"
                  label="Cancelar"
                  @click="cancelEvaluationTutor"
                />
              </div>
            </div>
          </v-expand-transition>
        </v-card-text>
      </v-card>
    </template>

    <template v-else-if="walker">
      <BaseTypography variant="h2" class="title mb-4">
        <BaseIcon name="mdi-walk" color="primary" size="23" class="me-2" />
        Meus Passeios
      </BaseTypography>

      <v-alert v-if="toursWalker.length === 0" type="info" variant="tonal">
        Você ainda não possui passeios aceitos.
      </v-alert>

      <v-card
        v-for="p in toursWalker"
        :key="p.id"
        class="mb-3 card"
        elevation="2"
      >
        <v-card-text>
          <BaseButton
            v-if="p.status === 'aceito' || p.status === 'cancelado'"
            icon="mdi-close"
            size="small"
            variant="text"
            label=""
            class="dismiss-btn"
            aria-label="Remover do dashboard"
            title="Remover do dashboard"
            @click="dismissTour(p.id)"
          />

          <BaseTypography variant="h5">
            <BaseIcon name="mdi-paw-outline" color="amber" size="25" />
            {{ p.dog?.nome }}
          </BaseTypography>
          <BaseTypography variant="subtitle-1">
            <BaseIcon
              name="mdi-calendar"
              color="primary"
              class="me-2"
              size="18"
            />
            {{ formatDate(p.data) }} - {{ p.hora }}
          </BaseTypography>
          <BaseTypography variant="subtitle-1">
            <BaseIcon
              name="mdi-map-marker-outline"
              color="red-darken-4"
              class="me-2"
              size="18"
            />
            {{ p.local }}
          </BaseTypography>
          <BaseTypography variant="subtitle-1" class="text-black">
            <BaseIcon
              name="mdi-account-outline"
              color="primary"
              class="me-2"
              size="18"
            />
            Tutor:
            <BaseTypography variant="body-1" weight="bold">{{
              p.tutor?.nome
            }}</BaseTypography>
          </BaseTypography>

          <BaseTypography
            variant="subtitle-1"
            class="text-medium-emphasis text-caption text-black"
            v-if="p.status === 'cancelado'"
          >
            <BaseIcon name="mdi-close" class="me-2" color="red-darken-4" />
            Este passeio foi cancelado pelo tutor.
          </BaseTypography>

          <v-chip
            v-bind="badgeStatus(p.status)"
            size="small"
            variant="tonal"
            class="text-capitalize ext-white text-caption font-weight-medium px-4"
          >
            {{ p.status }}
          </v-chip>

          <div class="mt-3" v-if="p.status === 'aceito'">
            <BaseButton
              color="success"
              variant="tonal"
              size="small"
              class="finish text-success"
              label="Finalizar passeio"
              prepend-icon="mdi-check-circle"
              @click="openEvaluationWalker(p)"
            />
          </div>

          <!-- AVALIAÇÃO ENVIADA PELO PASSEADOR -->
          <v-sheet
            v-if="p.review_by_walker"
            color="blue-lighten-5"
            rounded="lg"
            class="pa-3 mt-3"
            border="s-lg"
          >
            <BaseTypography variant="subtitle-1" class="mb-2 text-black">
              <BaseIcon name="mdi-check" class="me-2" color="green-darken-4" />
              Sua avaliação sobre o passeio
            </BaseTypography>
            <div class="mb-1">
              <span v-for="n in 5" :key="n">
                <BaseIcon
                  :name="
                    n <= p.review_by_walker.rating
                      ? 'mdi-star'
                      : 'mdi-star-outline'
                  "
                  size="20"
                  color="amber"
                />
              </span>
              <span class="text-medium-emphasis text-caption ms-1"
                >({{ p.review_by_walker.rating }}/5)</span
              >
            </div>
            <BaseTypography
              variant="subtitle-1"
              v-if="p.review_by_walker.comment"
              class="font-italic mb-0"
            >
              "{{ p.review_by_walker.comment }}"
            </BaseTypography>
          </v-sheet>

          <!-- FORMULÁRIO FINALIZAR + AVALIAR -->
          <v-expand-transition>
            <div v-if="reviewWalker?.id === p.id" class="mt-4">
              <v-divider class="mb-4" />
              <BaseTypography variant="h5" class="mb-3 text-black"
                >Avaliar o tutor / passeio</BaseTypography
              >

              <div class="mb-3">
                <span
                  v-for="n in 5"
                  :key="n"
                  class="rating"
                  @click="rating = n"
                >
                  <BaseIcon
                    :name="n <= rating ? 'mdi-star' : 'mdi-star-outline'"
                    color="amber"
                    size="20"
                  />
                </span>
              </div>

              <BaseTextarea
                class="mb-3 no-resize"
                :rows="3"
                placeholder="Comentário"
                v-model="comment"
              />

              <div class="d-flex ga-2">
                <BaseButton
                  color="success"
                  :label="sending ? 'Enviando...' : 'Finalizar e enviar'"
                  :disabled="sending"
                  @click="completeTour(p)"
                />
                <BaseButton
                  color="secondary"
                  label="Cancelar"
                  @click="cancelEvaluationWalker"
                />
              </div>
            </div>
          </v-expand-transition>
        </v-card-text>
      </v-card>
    </template>
  </v-container>
</template>

<style scoped>
.card {
  position: relative;
  border: none;
  border-radius: 12px;
  transition: 0.25s;
  padding: 20px;
  max-width: 700px;
  margin: 0 auto 16px auto;
}

.card:hover {
  transform: translateY(-3px);
}

.finish {
  width: 155px;
  height: 36px;
  border-radius: 999px !important;
}

.btn-evaluation {
  min-width: 220px;
  min-height: 42px;
  border-radius: 999px !important;
  font-size: 14px !important;
  font-weight: 600;
}

.rating {
  font-size: 28px;
  cursor: pointer;
  transition: transform 0.2s;
  user-select: none;
}

.rating:hover {
  transform: scale(1.15);
}

:deep(.v-textarea .v-field__input) {
  padding-top: 16px;
  padding-bottom: 16px;
  min-height: 56px;
}

:deep(.v-textarea textarea) {
  line-height: 1.5;
}
</style>
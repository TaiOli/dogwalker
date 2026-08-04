<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";
import BaseSelect from "../atoms/BaseSelect.vue";

interface RegisterForm {
  username: string;
  name: string;
  email: string;
  password: string;
  phone: string;
  type_user: string;
  photo: File | string;
}

interface Props {
  form: RegisterForm;
  labelButton: string;
}

const props = defineProps<Props>();
const emit = defineEmits<{ submit: [] }>();

const preview = ref<string | null>(null);
const usernameError = ref("");
const nameError = ref("");
const emailError = ref("");
const passwordError = ref("");
const typeuserError = ref("");

function handleSubmit(): void {
  usernameError.value = !props.form.username ? "Insira um username!" : "";
  nameError.value = !props.form.name ? "Insira um nome!" : "";
  emailError.value = !props.form.email ? "Insira um e-mail!" : "";
  passwordError.value = !props.form.password ? "Insira uma senha!" : "";
  typeuserError.value = !props.form.type_user
    ? "Selecione um tipo de usuário!"
    : "";

  if (
    usernameError.value ||
    nameError.value ||
    emailError.value ||
    passwordError.value ||
    typeuserError.value
  )
    return;

  emit("submit");
}

function handlePhoto(value: string | number | File | File[] | null): void {
  const file = Array.isArray(value)
    ? value[0]
    : value instanceof File
      ? value
      : null;
  if (!file) return;
  props.form.photo = file;
  preview.value = URL.createObjectURL(file);
}
</script>

<template>
  <v-container class="pa-0 mx-auto">
    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.username"
          required
          label="Username"
          prepend-inner-icon="mdi-account-outline"
          icon-color="primary mx-2"
          :error-message="usernameError"
          @update:modelValue="usernameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.name"
          required
          label="Nome Completo"
          prepend-inner-icon="mdi-account-outline"
          icon-color="primary mx-2"
          :error-message="nameError"
          @update:modelValue="nameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.email"
          required
          label="Email"
          prepend-inner-icon="mdi-email-outline"
          icon-color="primary mx-2"
          :error-message="emailError"
          @update:modelValue="emailError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.password"
          required
          type="password"
          label="Senha"
          prepend-inner-icon="mdi-lock-outline"
          icon-color="primary mx-2"
          :error-message="passwordError"
          @update:modelValue="passwordError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseSelect
          v-model="form.type_user"
          required
          class="mb-3"
          :items="[
            { label: 'Selecione o tipo de usuário', value: '' },
            { label: 'Tutor', value: 'tutor' },
            { label: 'Passeador', value: 'passeador' },
          ]"
          labelKey="label"
          valueKey="value"
          prepend-inner-icon="mdi-account-outline"
          icon-color="primary mx-2 mt-2"
          :error-message="typeuserError"
          @update:modelValue="typeuserError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          v-model="form.phone"
          label="Telefone"
          prepend-inner-icon="mdi-phone-outline"
          icon-color="primary mx-2"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8" class="mx-auto">
        <BaseInput
          type="file"
          accept="image/*"
          label="Foto"
          icon-color="primary mx-2"
          @update:modelValue="handlePhoto"
        />
      </v-col>
    </v-row>

    <v-row v-if="preview">
      <v-col cols="12" md="8" class="text-center mx-auto">
        <v-img
          :src="preview"
          alt="Foto Perfil"
          max-height="200"
          cover
          rounded="lg"
          class="mx-auto img-preview"
        />
      </v-col>
    </v-row>

    <v-row class="mt-2">
      <v-col cols="12" md="8" class="mx-auto text-center">
        <BaseButton
          :label="labelButton"
          class="btn-mustard mx-auto mb-4"
          @click="handleSubmit"
        />
      </v-col>
    </v-row>
  </v-container>
</template>
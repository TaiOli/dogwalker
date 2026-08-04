<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "../atoms/BaseInput.vue";
import BaseButton from "../atoms/BaseButton.vue";
import BaseSelect from "../atoms/BaseSelect.vue";
import {
  type RegisterForm,
  validateUsername,
  validateName,
  validateEmail,
  validatePassword,
  validateTypeUser,
  validatePhone,
  onlyDigits,
} from "../../utils/validations/userValidations";

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
const phoneError = ref("");

function handleSubmit(): void {
  usernameError.value = validateUsername(props.form.username);
  nameError.value = validateName(props.form.name);
  emailError.value = validateEmail(props.form.email);
  passwordError.value = validatePassword(props.form.password);
  typeuserError.value = validateTypeUser(props.form.type_user);
  phoneError.value = validatePhone(props.form.phone);

  if (
    usernameError.value ||
    nameError.value ||
    emailError.value ||
    passwordError.value ||
    typeuserError.value ||
    phoneError.value
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

function handlePhone(value: string | number | File | File[] | null): void {
  props.form.phone = onlyDigits(value as string | number | null);
  phoneError.value = "";
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
          :model-value="form.phone"
          label="Telefone"
          prepend-inner-icon="mdi-phone-outline"
          icon-color="primary mx-2"
          :error-message="phoneError"
          @update:modelValue="handlePhone"
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
<script setup lang="ts">
import { ref, computed } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

interface RegisterForm {
  username: string
  name: string
  email: string
  password: string
  phone: string
  type_user: string
  photo: File | string
}

interface UserRegisterFormProps {
  form: RegisterForm
  labelButton: string
}

const props = defineProps<UserRegisterFormProps>()

const preview = ref<string | null>(null)
const usernameError = ref<string>("")
const emailError = ref<string>("")
const passwordError = ref<string>("")
const nameError = ref<string>("")
const typeuserError = ref<string>("")

function handleSubmit(): void {
  usernameError.value = !props.form.username ? "Insira um username!" : ""
  emailError.value = !props.form.email ? "Insira um e-mail!" : ""
  passwordError.value = !props.form.password ? "Insira uma senha!" : ""
  nameError.value = !props.form.name ? "Insira uma nome!" : ""
  typeuserError.value = !props.form.type_user ? "Selecione um tipo de usuário!" : ""

  if (usernameError.value || emailError.value || passwordError.value || nameError.value || typeuserError.value ) {
    return
  }

  emit("submit")
}

const emit = defineEmits<{
  submit: []
}>()

const typeUsers = [
  { label: "Tutor", value: "tutor" },
  { label: "Passeador", value: "passeador" }
]
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <BaseInput 
        label="Username"
        required
        v-model="form.username" 
        :class="{ 'is-invalid': usernameError }"
        @update:modelValue="usernameError = ''"
      />
      <div v-if="usernameError" class="text-danger text-start small mt-1">
        {{ usernameError }}
      </div>
    </div>

    <div class="mb-3">
      <BaseInput
        label="Nome Completo"
        required
        v-model="form.name"  
        :class="{ 'is-invalid': nameError }"
        @update:modelValue="nameError = ''"
      />
      <div v-if="nameError" class="text-danger text-start small mt-1">
        {{ nameError }}
      </div>
    </div>

    <div class="mb-3">
      <BaseInput 
        label="Email"
        required
        v-model="form.email" 
        :class="{ 'is-invalid': emailError }"
        @update:modelValue="emailError = ''"
      />
      <div v-if="emailError" class="text-danger text-start small mt-1">
        {{ emailError }}
      </div>
    </div>

    <div class="mb-3">
      <BaseInput 
        label="Senha"
        required
        v-model="form.password" 
        type="password" 
        :class="{ 'is-invalid': passwordError }"
        @update:modelValue="passwordError = ''"
      />
      <div v-if="passwordError" class="text-danger text-start small mt-1">
        {{ passwordError }}
      </div>
    </div>

      <div class="mb-3">
        <BaseSelect
          label="Tipo de Usuário"
          required
          v-model="form.type_user"
          :options="typeUsers"
          labelKey="label"
          valueKey="value"
          :class="{ 'is-invalid': typeuserError }"
          @update:modelValue="typeuserError = ''"
        />
        <div v-if="typeuserError" class="text-danger text-start small mt-1">
          {{ typeuserError }}
        </div>
      </div>

    <div class="mb-3">
      <BaseInput v-model="form.phone" label="📞 Telefone" />
    </div>

    <div class="mb-3">
      <div class="d-flex align-center ga-2">  
          <BaseInput
            v-model="form.photo"
            type="file"
            accept="image/*"
            label="Foto"
          />
        </div>
    </div>

    <div v-if="preview" class="text-center mb-3">
      <v-img :src="preview" class="img-preview" />
    </div>

    <BaseButton class="w-100 mt-2 btn-mustard" :label="labelButton" @click="handleSubmit" />

  </div>
</template>

<style scoped>
.form {
  width: 100%;
}

.clip-btn {
  border: none;
  background: transparent;
  font-size: 22px;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.clip-btn:hover {
  transform: scale(1.2);
}

.img-preview {
  width: 100%;
  max-height: 200px;
  object-fit: cover;
  border-radius: 10px;
}

.btn-mustard {
    background-color: #D4A017;
    border-color: #D4A017;
    color: #1F1F1F;
    font-weight: 600;
    transition: 0.3s ease;
    border-radius: 50px;
    padding: 10px 32px;
    max-width: 70%;
    border-radius: 50px;
    padding: 10px 32px;
}

.btn-mustard:hover {
    background-color: #B88A12;
    border-color: #B88A12;
    color: #1F1F1F;
}

.btn-mustard:focus,
.btn-mustard:active {
    background-color: #A97C10 !important;
    border-color: #A97C10 !important;
    color: #1F1F1F !important;
    box-shadow: 0 0 0 0.25rem rgba(212, 160, 23, 0.35);
}
</style>
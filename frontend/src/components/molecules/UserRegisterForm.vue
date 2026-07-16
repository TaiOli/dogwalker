<script setup lang="ts">
import { ref} from "vue"
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
  <v-container>

    <v-row>
      <v-col cols="12">
        <BaseInput 
          label="Username"
          required
          v-model="form.username" 
          :error-message="usernameError"
          @update:modelValue="usernameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput
          label="Nome Completo"
          required
          v-model="form.name"  
          :error-message="nameError"
          @update:modelValue="nameError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
        <v-col cols="12">
        <BaseInput 
          label="Email"
          required
          v-model="form.email" 
          :error-message="emailError"
          @update:modelValue="emailError = ''"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <BaseInput 
          label="Senha"
          required
          v-model="form.password" 
          type="password" 
          :error-message="passwordError"
          @update:modelValue="passwordError = ''"
        />
      </v-col>
    </v-row>

     <v-row>
        <v-col cols="12">
          <BaseSelect
            label="Tipo de Usuário"
            required
            v-model="form.type_user"
            :options="typeUsers"
            labelKey="label"
            valueKey="value"
            :error-message="typeuserError"
            @update:modelValue="typeuserError = ''"
          />
       </v-col>
      </v-row>
       
     <v-row>
      <v-col cols="12">
        <BaseInput v-model="form.phone" label="📞 Telefone" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" class="d-flex align-center ga-2">
        <BaseInput
          v-model="form.photo"
          type="file"
          accept="image/*"
          label="Foto"
        />
      </v-col>
    </v-row>

    <v-row v-if="preview">
      <v-col cols="12" class="text-center">
        <v-img
          :src="preview"
          class="img-preview"
        />
      </v-col>
    </v-row>

    <BaseButton class="w-100 mt-2 btn-mustard" :label="labelButton" @click="handleSubmit" />

  </v-container>
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
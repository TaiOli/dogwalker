<script setup lang="ts">
import { ref, computed } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"
import BaseLabel from "../atoms/BaseLabel.vue"

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

const fileInput = ref<HTMLInputElement | null>(null)
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

const photoFileName = computed(() => {
  const photo = props.form.photo
  return photo instanceof File ? photo.name : ""
})

function openFile(): void {
  fileInput.value?.click()
}

function handleFile(files: FileList | null): void {
  const file = files?.[0]
  if (!file) return

  props.form.photo = file
  preview.value = URL.createObjectURL(file)
}
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <BaseLabel class="text-start w-100" required>
        Username 
      </BaseLabel>
      <BaseInput 
        v-model="form.username" 
        :class="{ 'is-invalid': usernameError }"
        @update:modelValue="usernameError = ''"
      />
      <div v-if="usernameError" class="text-danger text-start small mt-1">
        {{ usernameError }}
      </div>
    </div>

    <div class="mb-3">
      <BaseLabel class="text-start w-100" required>
        Nome Completo 
      </BaseLabel>
      <BaseInput 
        v-model="form.name"  
        :class="{ 'is-invalid': nameError }"
        @update:modelValue="nameError = ''"
      />
      <div v-if="nameError" class="text-danger text-start small mt-1">
        {{ nameError }}
      </div>
    </div>

    <div class="mb-3">
      <BaseLabel class="text-start w-100" required>
        Email
      </BaseLabel>
      <BaseInput 
        v-model="form.email" 
        :class="{ 'is-invalid': emailError }"
        @update:modelValue="emailError = ''"
      />
      <div v-if="emailError" class="text-danger text-start small mt-1">
        {{ emailError }}
      </div>
    </div>

    <div class="mb-3">
      <BaseLabel class="text-start w-100" required>
        Senha
      </BaseLabel>
      <BaseInput 
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
        <BaseLabel class="text-start w-100" required>
          Tipo de Usuário
        </BaseLabel>
        <BaseSelect
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
      <BaseLabel text="📞 Telefone" /> 
      <BaseInput v-model="form.phone" />
    </div>

    <div class="mb-3">
      <BaseLabel text="Foto" /> 
      <div class="input-group">
        <BaseButton type="button" class="btn btn-outline-secondary" @click="openFile" label="📎"/>
        <BaseInput 
          class="form-control"
          :value="photoFileName"
          placeholder="Nenhuma foto selecionada"
          readonly
        />

        <BaseInput
          ref="fileInput"
          type="file"
          accept="image/*"
          hidden
          @change="handleFile"
        />
      </div>
    </div>

    <div v-if="preview" class="text-center mb-3">
      <img :src="preview" class="img-preview" />
    </div>

    <BaseButton class="w-100 mt-2" :label="labelButton" @click="handleSubmit" />

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
</style>
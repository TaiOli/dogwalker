<script setup>
import { ref } from "vue"
import BaseInput from "../atoms/BaseInput.vue"
import BaseButton from "../atoms/BaseButton.vue"
import BaseSelect from "../atoms/BaseSelect.vue"

const props = defineProps({
  form: Object,
  labelButton: String
})

const emit = defineEmits(["submit"])

const typeUsers = [
  { label: "Tutor", value: "tutor" },
  { label: "Passeador", value: "passeador" }
]

const fileInput = ref(null)
const preview = ref(null)

function openFile() {
  fileInput.value.click()
}

function handleFile(event) {
  const file = event.target.files[0]
  if (!file) return

  props.form.foto = file
  preview.value = URL.createObjectURL(file)
}
</script>

<template>
  <div class="form">

    <div class="mb-3">
      <BaseInput v-model="form.username" placeholder="Nome de usuário" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.name" placeholder="Nome completo" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.email" placeholder="Email" />
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.password" placeholder="Senha" type="password" />
    </div>

    <div class="mb-3">
      <label class="form-label text-start w-100">Tipo de usuário:</label>
      <div class="mb-3">
        <BaseSelect
          v-model="form.type_user"
          :options="typeUsers"
          labelKey="label"
          valueKey="value"
        />
      </div>
    </div>

    <div class="mb-3">
      <BaseInput v-model="form.phone" placeholder="Telefone (opcional)" />
    </div>

    <div class="mb-3">
      <label class="form-label text-start w-100">Foto</label>

      <div class="input-group">

        <button
          type="button"
          class="btn btn-outline-secondary"
          @click="openFile"
        >
          📎
        </button>

        <input
          class="form-control"
          :value="form.photo ? form.foto.name : ''"
          placeholder="Nenhuma foto selecionada"
          readonly
        />

        <input
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

    <BaseButton
      class="w-100 mt-2"
      :label="labelButton"
      @click="emit('submit')"
    />

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
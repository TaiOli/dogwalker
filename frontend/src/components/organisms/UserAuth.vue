<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow-sm p-4 w-100">

      <h3 class="text-center mb-3">🔐 Login</h3>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <BaseInput v-model="form.email" placeholder="Email" />
      </div>

      <div class="mb-3">
        <label class="form-label">Senha</label>
        <BaseInput v-model="form.password" type="password" placeholder="Senha" />
      </div>

      <div class="d-grid">
        <BaseButton :label="labelButton" @click="emit('submit')"/>
      </div>

    </div>

  </div>
</template>

<style scoped>
.card{
  max-width: 400px;
}
</style>
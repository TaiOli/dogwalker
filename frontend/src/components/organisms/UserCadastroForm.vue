<template>
  <div class="container d-flex justify-content-center py-5">

    <div class="card shadow-sm p-4 w-100">

      <h3 class="text-center mb-4">👤 Cadastro de Usuário</h3>

      <div class="mb-3">
        <label class="form-label">Nome de usuário</label>
        <BaseInput v-model="form.name" placeholder="Nome de usuário" />
      </div>

      <div class="mb-3">
        <label class="form-label">Nome completo</label>
        <BaseInput v-model="form.nome" placeholder="Nome completo" />
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <BaseInput v-model="form.email" placeholder="Email" />
      </div>

      <div class="mb-3">
        <label class="form-label">Senha</label>
        <BaseInput v-model="form.password" placeholder="Senha" type="password" />
      </div>

      <div class="d-grid">
        <BaseButton :label="labelButton" @click="emit('submit')"/>
      </div>

    </div>

  </div>
</template>

<style scoped>
.card{
  max-width: 500px;
}
</style>
<template>
  <div class="min-h-screen flex items-center justify-center" :style="{ backgroundColor: 'var(--color-background)' }">
    <div class="w-[400px] shadow-md rounded-lg"
      style="padding: 2.5rem !important; background-color: var(--color-panel); color: var(--color-text) !important;">
      <h1 class="text-3xl font-bold text-center mb-4" :style="{ color: 'var(--color-secondary)' }">
        Registro
      </h1>

      <form @submit.prevent="handleRegister">
        <div class="w-full" style="margin-bottom: 1rem !important;">
          <label class="block font-medium" style="margin-bottom: 0.5rem !important;">
            Nome: <span style="color: red;">*</span>
          </label>
          <input
            type="text"
            v-model="name"
            required
            placeholder="Digite o seu Nome"
             class="w-[90%] mx-auto border-2 focus:outline-none focus:ring-2"
            style="height: 1.5rem; padding: 0.375rem 1rem; border-radius: 0.5rem; background-color: #ffffff0d; color: var(--color-text); border-color: var(--color-primary); font-size: 1rem;" />
        </div>

        <div class="w-full" style="margin-bottom: 1rem !important;">
          <label class="block font-medium" style="margin-bottom: 0.5rem !important;">
            Email: <span style="color: red;">*</span>
          </label>
          <input
            type="email"
            v-model="email"
            required
            placeholder="Digite o seu Email"
             class="w-[90%] mx-auto border-2 focus:outline-none focus:ring-2"
            style="height: 1.5rem; padding: 0.375rem 1rem; border-radius: 0.5rem; background-color: #ffffff0d; color: var(--color-text); border-color: var(--color-primary); font-size: 1rem;" />
        </div>

        <div class="w-full" style="margin-bottom: 1rem !important;">
          <label class="block font-medium" style="margin-bottom: 0.5rem !important;">
            Senha: <span style="color: red;">*</span>
          </label>
          <input
            type="password"
            v-model="password"
            required
            placeholder="Digite a sua Senha"
             class="w-[90%] mx-auto border-2 focus:outline-none focus:ring-2"
            style="height: 1.5rem; padding: 0.375rem 1rem; border-radius: 0.5rem; background-color: #ffffff0d; color: var(--color-text); border-color: var(--color-primary); font-size: 1rem;" />
          <small style="display: block; margin-top: 0.25rem; color: var(--color-primary); font-size: 0.875rem;">
            A senha deve conter no mínimo 6 caracteres.
          </small>
        </div>

        <div class="w-full" style="margin-bottom: 1.5rem !important;">
          <label class="block font-medium" style="margin-bottom: 0.5rem !important;">
            Confirmação de Senha: <span style="color: red;">*</span>
          </label>
          <input
            type="password"
            v-model="password_confirmation"
            required
            placeholder="Confirme a sua Senha"
             class="w-[90%] mx-auto border-2 focus:outline-none focus:ring-2"
            style="height: 1.5rem; padding: 0.375rem 1rem; border-radius: 0.5rem; background-color: #ffffff0d; color: var(--color-text); border-color: var(--color-primary); font-size: 1rem;" />
        </div>

        <button type="submit" class="w-full py-3 hover:transition-colors"
          style="border-radius: 0.5rem; margin-top: 0.5rem;">
          Registrar
        </button>
      </form>

      <p v-if="error" class="mt-3 text-center text-red-500">{{ error }}</p>

      <p class="mt-3 text-center">
        Já tem conta?
        <router-link to="/" :style="{ color: 'var(--color-secondary)' }" class="hover:underline">
          Entre
        </router-link>
      </p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Register',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      error: null
    }
  },
  methods: {
    async handleRegister() {
      try {
        // Envia a requisição POST para o endpoint de registro
        const response = await axios.post('http://127.0.0.1:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        })
        console.log('Registro efetuado:', response.data)
        // Após registro bem-sucedido, redirecione para a página de login, por exemplo
        this.$router.push({ name: 'Login' })
      } catch (error) {
        console.error('Erro ao registrar usuário:', error)
        // Caso a API retorne erro com dados, você pode usar:
        if (error.response && error.response.data) {
          this.error = error.response.data.message || 'Erro ao registrar usuário.'
        } else {
          this.error = 'Erro ao registrar usuário.'
        }
      }
    }
  }
}
</script>

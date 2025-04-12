<template>
  <div class="min-h-screen flex items-center justify-center" :style="{ backgroundColor: 'var(--color-background)' }">
 
    <div class="w-[400px] shadow-md rounded-lg"
      style="padding: 4rem !important; background-color: var(--color-panel); color: var(--color-text) !important;">
      <h1 class="text-3xl font-bold text-center mb-6" :style="{ color: 'var(--color-secondary)' }">
        Gerenciador de Tarefas
      </h1>

      <form @submit.prevent="handleLogin">
        <div class="w-full" style="margin-bottom: 1rem !important;">
          <label class="block font-medium" for="email" style="margin-bottom: 0.5rem !important;">
            Email: <span style="color: red;">*</span>
          </label>
          <input id="email" type="email" v-model="email" required placeholder="admin@example.com"
            style="height: 1.5rem !important; padding: 0.375rem 1rem !important; border-radius: 0.5rem !important; font-size: 1rem !important;"
            class="w-[90%] mx-auto border-2 focus:outline-none focus:ring-2" :style="{
              borderColor: 'var(--color-primary)',
              backgroundColor: '#ffffff0d',
              color: 'var(--color-text)'
            }" />
        </div>

        <div class="w-full" style="margin-bottom: 1rem !important;">
          <label class="block font-medium" for="password" style="margin-bottom: 0.5rem !important;">
            Senha: <span style="color: red;">*</span>
          </label>
          <input id="password" type="password" v-model="password" required placeholder="Digite sua senha"
            style="height: 1.5rem !important; padding: 0.375rem 1rem !important; border-radius: 0.5rem !important; font-size: 1rem !important;"
            class="w-[90%] mx-auto border-2 focus:outline-none focus:ring-2" :style="{
              borderColor: 'var(--color-primary)',
              backgroundColor: '#ffffff0d',
              color: 'var(--color-text)'
            }" />

        </div>
        <button type="submit" style="border-radius: 0.5rem !important; margin-top: 0.5rem !important;"
          class="w-full py-3 hover:transition-colors">
          Entrar
        </button>
      </form>

      <p class="mt-4 text-center">
        Ainda não tem conta?
        <router-link to="/register" :style="{ color: 'var(--color-secondary)' }" class="hover:underline">
          Registre-se
        </router-link>
      </p>

      <p v-if="error" class="mt-2 text-center text-red-500">{{ error }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: null,
    }
  },
  methods: {
    async handleLogin() {
      try {
        const response = await axios.post(
          'http://127.0.0.1:8000/api/login',
          {
            email: this.email,
            password: this.password,
          },
          {
            headers: { 'Content-Type': 'application/json' },
          }
        )
        const token = response.data.token
        localStorage.setItem('auth_token', token)
        this.$router.push({ name: 'Dashboard' })
      } catch (err) {
        console.error('Erro no login:', err)
        this.error = 'Erro no login, verifique suas credenciais.'
      }
    },
  },
}
</script>

<style scoped></style>

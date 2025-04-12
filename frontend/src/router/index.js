import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guarda simples para proteção de rotas, se o token não existir, redireciona para a página de login.
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('auth_token')) {
    next({ name: 'Login' })
  } else {
    next()
  }
})

export default router

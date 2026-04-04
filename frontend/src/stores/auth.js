import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../api/axios.js'
import router from '../router/index.js'

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  const token = ref(localStorage.getItem('token') || null)

  const isAuthenticated = computed(() => !!token.value)

  async function register(data) {
    const res = await api.post('/register', data)
    _setSession(res.data)
  }

  async function login(credentials) {
    const res = await api.post('/login', credentials)
    _setSession(res.data)
  }

  async function logout() {
    try { await api.post('/logout') } catch {}
    _clearSession()
    router.push('/login')
  }

  function _setSession({ token: t, user: u }) {
    token.value = t
    user.value  = u
    localStorage.setItem('token', t)
    localStorage.setItem('user', JSON.stringify(u))
    router.push('/')
  }

  function _clearSession() {
    token.value = null
    user.value  = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  return { user, token, isAuthenticated, register, login, logout }
})

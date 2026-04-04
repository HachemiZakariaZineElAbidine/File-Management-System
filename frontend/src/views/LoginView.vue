<template>
  <div class="auth-wrap">
    <div class="card auth-card">
      <h1>File Manager</h1>

      <div class="tabs">
        <button :class="{ active: tab === 'login' }"    @click="tab = 'login'">Login</button>
        <button :class="{ active: tab === 'register' }" @click="tab = 'register'">Register</button>
      </div>

      <!-- Login form -->
      <form v-if="tab === 'login'" @submit.prevent="handleLogin">
        <label>Email
          <input v-model="loginForm.email" type="email" required autocomplete="email" />
        </label>
        <label>Password
          <input v-model="loginForm.password" type="password" required autocomplete="current-password" />
        </label>
        <p v-if="error" class="error-msg">{{ error }}</p>
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Logging in…' : 'Login' }}
        </button>
      </form>

      <!-- Register form -->
      <form v-else @submit.prevent="handleRegister">
        <label>Name
          <input v-model="regForm.name" type="text" required />
        </label>
        <label>Email
          <input v-model="regForm.email" type="email" required autocomplete="email" />
        </label>
        <label>Password
          <input v-model="regForm.password" type="password" required autocomplete="new-password" />
        </label>
        <label>Confirm Password
          <input v-model="regForm.password_confirmation" type="password" required autocomplete="new-password" />
        </label>
        <p v-if="error" class="error-msg">{{ error }}</p>
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Registering…' : 'Register' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useAuthStore } from '../stores/auth.js'

const auth   = useAuthStore()
const tab    = ref('login')
const error  = ref('')
const loading = ref(false)

const loginForm = ref({ email: '', password: '' })
const regForm   = ref({ name: '', email: '', password: '', password_confirmation: '' })

watch(tab, () => { error.value = '' })

function extractError(err) {
  const data = err.response?.data
  if (!data) return 'Something went wrong.'
  if (data.errors) return Object.values(data.errors).flat().join(' ')
  return data.message || 'Something went wrong.'
}

async function handleLogin() {
  error.value  = ''
  loading.value = true
  try {
    await auth.login(loginForm.value)
  } catch (err) {
    error.value = extractError(err)
  } finally {
    loading.value = false
  }
}

async function handleRegister() {
  error.value  = ''
  loading.value = true
  try {
    await auth.register(regForm.value)
  } catch (err) {
    error.value = extractError(err)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-wrap {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
}
.auth-card {
  width: 100%;
  max-width: 400px;
}
h1 { font-size: 22px; font-weight: 700; margin-bottom: 20px; text-align: center; }
.tabs { display: flex; gap: 8px; margin-bottom: 20px; }
.tabs button {
  flex: 1;
  background: #f1f5f9;
  color: #64748b;
  padding: 8px;
  border-radius: 6px;
}
.tabs button.active { background: #6366f1; color: #fff; }
form { display: flex; flex-direction: column; gap: 14px; }
label { display: flex; flex-direction: column; gap: 4px; font-size: 13px; font-weight: 500; color: #475569; }
button[type="submit"] { margin-top: 4px; width: 100%; padding: 10px; }
</style>

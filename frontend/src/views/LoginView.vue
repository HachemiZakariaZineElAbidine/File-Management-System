<template>
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-header">
        <div class="logo-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
        <h1>File Manager</h1>
        <p class="subtitle">{{ tab === 'login' ? 'Sign in to your account' : 'Create a new account' }}</p>
      </div>

      <div class="tabs">
        <button :class="{ active: tab === 'login' }" @click="tab = 'login'">Login</button>
        <button :class="{ active: tab === 'register' }" @click="tab = 'register'">Register</button>
      </div>

      <!-- Login form -->
      <form v-if="tab === 'login'" @submit.prevent="handleLogin">
        <div class="field">
          <label>Email address</label>
          <input v-model="loginForm.email" type="email" placeholder="you@example.com" required autocomplete="email" />
        </div>
        <div class="field">
          <label>Password</label>
          <input v-model="loginForm.password" type="password" placeholder="••••••••" required autocomplete="current-password" />
        </div>
        <p v-if="error" class="error-msg">{{ error }}</p>
        <button type="submit" class="btn-primary submit-btn" :disabled="loading">
          <svg v-if="loading" class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
          {{ loading ? 'Signing in…' : 'Sign in' }}
        </button>
      </form>

      <!-- Register form -->
      <form v-else @submit.prevent="handleRegister">
        <div class="field">
          <label>Full name</label>
          <input v-model="regForm.name" type="text" placeholder="John Doe" required />
        </div>
        <div class="field">
          <label>Email address</label>
          <input v-model="regForm.email" type="email" placeholder="you@example.com" required autocomplete="email" />
        </div>
        <div class="field">
          <label>Password</label>
          <input v-model="regForm.password" type="password" placeholder="••••••••" required autocomplete="new-password" />
        </div>
        <div class="field">
          <label>Confirm password</label>
          <input v-model="regForm.password_confirmation" type="password" placeholder="••••••••" required autocomplete="new-password" />
        </div>
        <p v-if="error" class="error-msg">{{ error }}</p>
        <button type="submit" class="btn-primary submit-btn" :disabled="loading">
          <svg v-if="loading" class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
          {{ loading ? 'Creating account…' : 'Create account' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useAuthStore } from '../stores/auth.js'

const auth    = useAuthStore()
const tab     = ref('login')
const error   = ref('')
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
  background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 50%, #faf5ff 100%);
}

.auth-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.05);
  padding: 36px 32px;
  width: 100%;
  max-width: 400px;
}

.auth-header {
  text-align: center;
  margin-bottom: 28px;
}
.logo-icon {
  width: 52px;
  height: 52px;
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  margin: 0 auto 14px;
  box-shadow: 0 4px 14px rgba(99,102,241,0.35);
}
h1 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 5px; }
.subtitle { font-size: 14px; color: #94a3b8; }

.tabs {
  display: flex;
  background: #f1f5f9;
  border-radius: 10px;
  padding: 4px;
  margin-bottom: 24px;
  gap: 4px;
}
.tabs button {
  flex: 1;
  background: transparent;
  color: #64748b;
  padding: 8px;
  border-radius: 7px;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.2s;
}
.tabs button.active {
  background: #fff;
  color: #6366f1;
  font-weight: 600;
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

form { display: flex; flex-direction: column; gap: 16px; }
.field { display: flex; flex-direction: column; gap: 6px; }
label { font-size: 13px; font-weight: 600; color: #374151; }

.submit-btn {
  width: 100%;
  padding: 11px;
  justify-content: center;
  font-size: 15px;
  margin-top: 4px;
}

@keyframes spin { to { transform: rotate(360deg); } }
.spinner { animation: spin 0.8s linear infinite; }
</style>

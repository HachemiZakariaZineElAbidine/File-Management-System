<template>
  <nav class="navbar">
    <span class="brand">File Manager</span>
    <div class="right">
      <span class="user-name">{{ auth.user?.name }}</span>
      <button class="btn-ghost" @click="auth.logout()">Logout</button>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '../stores/auth.js'
const auth = useAuthStore()
</script>

<style scoped>
.navbar {
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  padding: 0 24px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.brand { font-weight: 700; font-size: 16px; color: #6366f1; }
.right { display: flex; align-items: center; gap: 12px; }
.user-name { font-size: 14px; color: #64748b; }
</style>

<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="overlay" @click.self="$emit('cancel')">
        <div class="modal">
          <div class="modal-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              <path d="M10 11v6M14 11v6"/>
              <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
          </div>
          <h3>Delete file?</h3>
          <p>
            Are you sure you want to delete
            <strong>{{ file?.name }}</strong>?
            This action cannot be undone.
          </p>
          <div class="actions">
            <button class="btn-ghost" @click="$emit('cancel')">Cancel</button>
            <button class="btn-danger confirm-btn" @click="$emit('confirm')">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              </svg>
              Delete
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({ show: Boolean, file: Object })
defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.overlay {
  position: fixed; inset: 0;
  background: rgba(15, 23, 42, 0.45);
  backdrop-filter: blur(3px);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000;
  padding: 24px;
}
.modal {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.18);
  padding: 32px;
  max-width: 400px;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 12px;
}
.modal-icon {
  width: 56px;
  height: 56px;
  background: #fee2e2;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #dc2626;
  margin-bottom: 4px;
}
h3 { font-size: 18px; font-weight: 700; color: #0f172a; }
p  { font-size: 14px; color: #64748b; line-height: 1.6; max-width: 300px; }
p strong { color: #334155; font-weight: 600; }
.actions { display: flex; gap: 10px; margin-top: 8px; width: 100%; }
.actions button { flex: 1; justify-content: center; padding: 10px; }
.confirm-btn {
  background: #dc2626;
  color: #fff;
  border: none;
}
.confirm-btn:hover:not(:disabled) {
  background: #b91c1c;
  transform: translateY(-1px);
}

/* Transition */
.modal-enter-active { animation: modal-in 0.2s ease; }
.modal-leave-active { animation: modal-in 0.15s ease reverse; }
@keyframes modal-in {
  from { opacity: 0; transform: scale(0.94) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-enter-active .overlay,
.modal-leave-active .overlay {
  transition: opacity 0.2s;
}
</style>

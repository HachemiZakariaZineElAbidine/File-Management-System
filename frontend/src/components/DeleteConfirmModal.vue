<template>
  <Teleport to="body">
    <div v-if="show" class="overlay" @click.self="$emit('cancel')">
      <div class="modal card">
        <h3>Delete file?</h3>
        <p>
          Are you sure you want to delete
          <strong>{{ file?.name }}</strong>?
          This cannot be undone.
        </p>
        <div class="actions">
          <button class="btn-ghost" @click="$emit('cancel')">Cancel</button>
          <button class="btn-danger" @click="$emit('confirm')">Delete</button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
defineProps({ show: Boolean, file: Object })
defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000;
}
.modal { max-width: 420px; width: 90%; }
h3 { font-size: 17px; font-weight: 700; margin-bottom: 10px; }
p  { font-size: 14px; color: #475569; line-height: 1.5; }
.actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
</style>

<template>
  <div v-if="lastPage > 1" class="pagination">
    <button
      class="btn-ghost page-btn"
      :disabled="currentPage <= 1"
      @click="$emit('page-changed', currentPage - 1)"
    >
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
      Prev
    </button>

    <span class="page-info">
      <span class="current">{{ currentPage }}</span>
      <span class="sep">/</span>
      <span class="total">{{ lastPage }}</span>
    </span>

    <button
      class="btn-ghost page-btn"
      :disabled="currentPage >= lastPage"
      @click="$emit('page-changed', currentPage + 1)"
    >
      Next
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
    </button>
  </div>
</template>

<script setup>
defineProps({ currentPage: Number, lastPage: Number })
defineEmits(['page-changed'])
</script>

<style scoped>
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  margin-top: 20px;
  padding-bottom: 4px;
}
.page-btn { font-size: 13px; padding: 7px 14px; }
.page-info {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 14px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 5px 14px;
}
.current { font-weight: 700; color: #6366f1; }
.sep     { color: #cbd5e1; }
.total   { color: #94a3b8; }
</style>

<template>
  <div v-if="lastPage > 1" class="pagination">
    <button
      class="btn-ghost"
      :disabled="currentPage <= 1"
      @click="$emit('page-changed', currentPage - 1)"
    >← Prev</button>

    <span class="page-info">Page {{ currentPage }} of {{ lastPage }}</span>

    <button
      class="btn-ghost"
      :disabled="currentPage >= lastPage"
      @click="$emit('page-changed', currentPage + 1)"
    >Next →</button>
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
  gap: 16px;
  margin-top: 16px;
}
.page-info { font-size: 14px; color: #64748b; }
</style>

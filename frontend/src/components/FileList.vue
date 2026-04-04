<template>
  <div class="card file-list">
    <!-- Loading skeleton -->
    <template v-if="loading">
      <div v-for="n in 5" :key="n" class="skeleton-row" />
    </template>

    <!-- Empty state -->
    <div v-else-if="files.length === 0" class="empty">
      No files yet. Upload something above.
    </div>

    <!-- Table -->
    <table v-else>
      <thead>
        <tr>
          <th>Name</th>
          <th>Size</th>
          <th>Type</th>
          <th>Uploaded</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <FileRow
          v-for="file in files"
          :key="file.id"
          :file="file"
          @download="$emit('download-requested', file)"
          @delete="$emit('delete-requested', file)"
        />
      </tbody>
    </table>
  </div>
</template>

<script setup>
import FileRow from './FileRow.vue'
defineProps({ files: Array, loading: Boolean })
defineEmits(['delete-requested', 'download-requested'])
</script>

<style scoped>
.file-list { margin-top: 16px; padding: 0; overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
th {
  text-align: left;
  font-size: 12px;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 12px 16px;
  border-bottom: 1px solid #f1f5f9;
}
.empty { padding: 40px; text-align: center; color: #94a3b8; font-size: 14px; }
.skeleton-row {
  height: 48px;
  background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
  background-size: 200% 100%;
  animation: shimmer 1.2s infinite;
  margin: 1px 0;
}
@keyframes shimmer { 0% { background-position: 200% 0 } 100% { background-position: -200% 0 } }
</style>

<template>
  <div class="file-list card">
    <!-- Loading skeleton -->
    <template v-if="loading">
      <div v-for="n in 4" :key="n" class="skeleton-row">
        <div class="skel skel-icon" />
        <div class="skel-lines">
          <div class="skel skel-name" />
          <div class="skel skel-meta" />
        </div>
      </div>
    </template>

    <!-- Empty state -->
    <div v-else-if="files.length === 0" class="empty">
      <div class="empty-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
      </div>
      <p class="empty-title">No files yet</p>
      <p class="empty-sub">Upload your first file using the zone above</p>
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
.file-list { padding: 0; overflow: hidden; }

table { width: 100%; border-collapse: collapse; }
th {
  text-align: left;
  font-size: 11px;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  padding: 14px 20px;
  background: #fafafa;
  border-bottom: 1px solid #f1f5f9;
}
th:last-child { text-align: right; }

.empty {
  padding: 60px 24px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}
.empty-icon {
  width: 64px;
  height: 64px;
  background: #f1f5f9;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  margin-bottom: 4px;
}
.empty-title { font-size: 15px; font-weight: 600; color: #334155; }
.empty-sub   { font-size: 13px; color: #94a3b8; }

/* Skeleton */
.skeleton-row {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 20px;
  border-bottom: 1px solid #f8fafc;
}
.skel {
  background: linear-gradient(90deg, #f1f5f9 25%, #e8edf2 50%, #f1f5f9 75%);
  background-size: 200% 100%;
  animation: shimmer 1.3s infinite;
  border-radius: 6px;
}
.skel-icon  { width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0; }
.skel-lines { flex: 1; display: flex; flex-direction: column; gap: 7px; }
.skel-name  { height: 13px; width: 45%; }
.skel-meta  { height: 11px; width: 25%; }
@keyframes shimmer { 0% { background-position: 200% 0 } 100% { background-position: -200% 0 } }
</style>

<template>
  <tr class="file-row">
    <td class="name-cell">
      <div class="file-info">
        <div class="file-icon" :style="{ background: typeColor(file.type).bg, color: typeColor(file.type).text }">
          {{ shortType(file.type) }}
        </div>
        <span class="file-name">{{ file.name }}</span>
      </div>
    </td>
    <td class="meta">{{ formatSize(file.size) }}</td>
    <td>
      <span class="badge" :style="{ background: typeColor(file.type).bg, color: typeColor(file.type).text }">
        {{ shortType(file.type) }}
      </span>
    </td>
    <td class="meta">{{ formatDate(file.created_at) }}</td>
    <td class="actions-cell">
      <button class="btn-ghost action-btn" @click="$emit('download')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Download
      </button>
      <button class="btn-danger action-btn" @click="$emit('delete')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
          <path d="M10 11v6M14 11v6"/>
          <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
        Delete
      </button>
    </td>
  </tr>
</template>

<script setup>
defineProps({ file: Object })
defineEmits(['download', 'delete'])

const TYPE_COLORS = {
  'application/pdf': { bg: '#fee2e2', text: '#dc2626' },
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document': { bg: '#dbeafe', text: '#2563eb' },
  'image/png':  { bg: '#d1fae5', text: '#059669' },
  'image/jpeg': { bg: '#d1fae5', text: '#059669' },
  'application/vnd.oasis.opendocument.text': { bg: '#fef9c3', text: '#ca8a04' },
}

const TYPE_LABELS = {
  'application/pdf': 'PDF',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'DOCX',
  'image/png':  'PNG',
  'image/jpeg': 'JPG',
  'application/vnd.oasis.opendocument.text': 'ODT',
}

function typeColor(mime) {
  return TYPE_COLORS[mime] ?? { bg: '#f1f5f9', text: '#64748b' }
}

function shortType(mime) {
  return TYPE_LABELS[mime] ?? mime.split('/')[1]?.toUpperCase() ?? mime
}

function formatSize(bytes) {
  if (bytes < 1024)        return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>

<style scoped>
.file-row { transition: background 0.12s; }
.file-row:hover { background: #fafafe; }

td {
  padding: 14px 20px;
  font-size: 14px;
  border-bottom: 1px solid #f8fafc;
  vertical-align: middle;
  color: #334155;
}

.name-cell { min-width: 200px; max-width: 280px; }
.file-info { display: flex; align-items: center; gap: 12px; }
.file-icon {
  width: 36px;
  height: 36px;
  border-radius: 9px;
  font-size: 9px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.file-name {
  font-weight: 500;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  color: #0f172a;
}

.meta { color: #64748b; font-size: 13px; white-space: nowrap; }

.badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 5px;
  letter-spacing: 0.03em;
}

.actions-cell {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
}
.action-btn { font-size: 13px; padding: 7px 12px; }
</style>

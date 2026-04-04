<template>
  <tr>
    <td class="name">{{ file.name }}</td>
    <td>{{ formatSize(file.size) }}</td>
    <td><span class="badge">{{ shortType(file.type) }}</span></td>
    <td>{{ formatDate(file.created_at) }}</td>
    <td class="actions">
      <button class="btn-ghost" @click="$emit('download')">Download</button>
      <button class="btn-danger" @click="$emit('delete')">Delete</button>
    </td>
  </tr>
</template>

<script setup>
defineProps({ file: Object })
defineEmits(['download', 'delete'])

function formatSize(bytes) {
  if (bytes < 1024)        return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
}

function shortType(mime) {
  const map = {
    'application/pdf': 'PDF',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'DOCX',
    'image/png': 'PNG',
    'image/jpeg': 'JPG',
    'application/vnd.oasis.opendocument.text': 'ODT',
  }
  return map[mime] ?? mime.split('/')[1]?.toUpperCase() ?? mime
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' })
}
</script>

<style scoped>
td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.name { font-weight: 500; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.badge {
  display: inline-block;
  background: #ede9fe;
  color: #6d28d9;
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 4px;
}
.actions { display: flex; gap: 8px; }
</style>

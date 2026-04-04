<template>
  <div
    class="upload-zone"
    :class="{ dragging }"
    @dragover.prevent="dragging = true"
    @dragleave.prevent="dragging = false"
    @drop.prevent="onDrop"
    @click="$refs.input.click()"
  >
    <input
      ref="input"
      type="file"
      multiple
      accept=".pdf,.docx,.png,.jpg,.jpeg,.odt"
      style="display:none"
      @change="onInputChange"
    />
    <div class="zone-inner">
      <div class="upload-icon" :class="{ pulse: dragging }">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="16 16 12 12 8 16"/>
          <line x1="12" y1="12" x2="12" y2="21"/>
          <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
        </svg>
      </div>
      <p class="zone-title">
        <span v-if="dragging">Drop your files here</span>
        <span v-else>Drag & drop files or <strong>browse</strong></span>
      </p>
      <p class="hint">PDF, DOCX, PNG, JPG, ODT &nbsp;·&nbsp; max 5 files &nbsp;·&nbsp; 20 MB each</p>
      <p v-if="validationError" class="error-msg">{{ validationError }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['files-selected'])
const dragging = ref(false)
const validationError = ref('')

const ALLOWED_TYPES = [
  'application/pdf',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'image/png',
  'image/jpeg',
  'application/vnd.oasis.opendocument.text',
]

function validate(files) {
  if (files.length === 0)  return 'No files selected.'
  if (files.length > 5)    return 'Maximum 5 files at once.'
  for (const f of files) {
    if (!ALLOWED_TYPES.includes(f.type)) return `"${f.name}" is not an allowed file type.`
    if (f.size > 20 * 1024 * 1024)       return `"${f.name}" exceeds the 20 MB limit.`
  }
  return null
}

function handle(fileList) {
  dragging.value = false
  validationError.value = ''
  const files = Array.from(fileList)
  const err = validate(files)
  if (err) { validationError.value = err; return }
  emit('files-selected', files)
}

function onDrop(e)        { handle(e.dataTransfer.files) }
function onInputChange(e) { handle(e.target.files); e.target.value = '' }
</script>

<style scoped>
.upload-zone {
  border: 2px dashed #e2e8f0;
  border-radius: 16px;
  padding: 36px 24px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
  background: #fff;
}
.upload-zone:hover {
  border-color: #a5b4fc;
  background: #fafafe;
}
.upload-zone.dragging {
  border-color: #6366f1;
  background: #eef2ff;
  transform: scale(1.01);
  box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
}

.zone-inner { display: flex; flex-direction: column; align-items: center; gap: 8px; }

.upload-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  background: #eef2ff;
  color: #6366f1;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 4px;
  transition: all 0.2s;
}
.upload-zone:hover .upload-icon,
.dragging .upload-icon { background: #e0e7ff; }

@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
.pulse { animation: pulse 0.8s ease-in-out infinite; }

.zone-title { font-size: 15px; color: #475569; font-weight: 500; }
.zone-title strong { color: #6366f1; font-weight: 600; }
.hint { font-size: 12px; color: #94a3b8; }
</style>

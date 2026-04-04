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
      <div class="icon">↑</div>
      <p>Drag & drop files here or <strong>click to browse</strong></p>
      <p class="hint">PDF, DOCX, PNG, JPG, ODT · max 5 files · max 20 MB each</p>
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
    if (!ALLOWED_TYPES.includes(f.type)) {
      return `"${f.name}" is not an allowed file type.`
    }
    if (f.size > 20 * 1024 * 1024) {
      return `"${f.name}" exceeds the 20 MB limit.`
    }
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
  border: 2px dashed #cbd5e1;
  border-radius: 12px;
  padding: 32px;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.15s, background 0.15s;
  background: #fff;
}
.upload-zone:hover, .upload-zone.dragging {
  border-color: #6366f1;
  background: #f5f3ff;
}
.icon { font-size: 32px; margin-bottom: 8px; color: #6366f1; }
p    { font-size: 14px; color: #64748b; }
.hint { font-size: 12px; margin-top: 4px; }
.error-msg { margin-top: 8px; }
</style>

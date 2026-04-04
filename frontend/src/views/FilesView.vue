<template>
  <div class="page">
    <NavBar />
    <main class="container">
      <div class="page-header">
        <div>
          <h2>My Files</h2>
          <p class="page-sub">Upload, manage and download your files</p>
        </div>
      </div>

      <FileUploadZone @files-selected="handleUpload" />

      <div v-if="uploadError" class="upload-error">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ uploadError }}
      </div>

      <div class="toolbar">
        <div class="search-wrap">
          <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <input
            v-model="filesStore.search"
            type="search"
            placeholder="Search files…"
            class="search-input"
          />
        </div>
      </div>

      <FileList
        :files="filesStore.files"
        :loading="filesStore.loading"
        @delete-requested="openDeleteModal"
        @download-requested="handleDownload"
      />

      <Pagination
        :current-page="filesStore.pagination.current_page"
        :last-page="filesStore.pagination.last_page"
        @page-changed="filesStore.fetchFiles"
      />
    </main>

    <DeleteConfirmModal
      :show="showModal"
      :file="fileToDelete"
      @confirm="confirmDelete"
      @cancel="showModal = false"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useFilesStore } from '../stores/files.js'
import NavBar from '../components/NavBar.vue'
import FileUploadZone from '../components/FileUploadZone.vue'
import FileList from '../components/FileList.vue'
import Pagination from '../components/Pagination.vue'
import DeleteConfirmModal from '../components/DeleteConfirmModal.vue'

const filesStore   = useFilesStore()
const showModal    = ref(false)
const fileToDelete = ref(null)
const uploadError  = ref('')

onMounted(() => filesStore.fetchFiles(1))

let searchTimer = null
watch(() => filesStore.search, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => filesStore.fetchFiles(1), 300)
})

async function handleUpload(fileList) {
  uploadError.value = ''
  try {
    await filesStore.uploadFiles(fileList)
  } catch (err) {
    const data = err.response?.data
    uploadError.value = data?.errors
      ? Object.values(data.errors).flat().join(' ')
      : data?.message || 'Upload failed.'
  }
}

function openDeleteModal(file) {
  fileToDelete.value = file
  showModal.value    = true
}

async function confirmDelete() {
  showModal.value = false
  await filesStore.deleteFile(fileToDelete.value.id)
  fileToDelete.value = null
}

function handleDownload(file) {
  filesStore.downloadFile(file.id, file.name)
}
</script>

<style scoped>
.page { min-height: 100vh; background: #f8fafc; }
.container { max-width: 920px; margin: 0 auto; padding: 32px 20px; }

.page-header {
  margin-bottom: 24px;
}
h2 { font-size: 22px; font-weight: 700; color: #0f172a; }
.page-sub { font-size: 14px; color: #94a3b8; margin-top: 3px; }

.upload-error {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #fee2e2;
  color: #dc2626;
  border-radius: 10px;
  padding: 10px 16px;
  font-size: 13px;
  font-weight: 500;
  margin-top: 14px;
}

.toolbar { margin: 18px 0 0; }
.search-wrap {
  position: relative;
  max-width: 300px;
}
.search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  pointer-events: none;
}
.search-input { padding-left: 36px; }
</style>

<template>
  <div>
    <NavBar />
    <main class="container">
      <FileUploadZone @files-selected="handleUpload" />

      <div class="toolbar">
        <input
          v-model="filesStore.search"
          type="search"
          placeholder="Search files…"
          class="search-input"
        />
      </div>

      <div v-if="uploadError" class="error-msg" style="margin-bottom:12px">{{ uploadError }}</div>

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
    if (data?.errors) {
      uploadError.value = Object.values(data.errors).flat().join(' ')
    } else {
      uploadError.value = data?.message || 'Upload failed.'
    }
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
.container { max-width: 900px; margin: 0 auto; padding: 24px 16px; }
.toolbar   { margin: 16px 0; }
.search-input { max-width: 320px; }
</style>

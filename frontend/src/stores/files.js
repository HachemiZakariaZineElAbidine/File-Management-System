import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api/axios.js'

export const useFilesStore = defineStore('files', () => {
  const files       = ref([])
  const pagination  = ref({ current_page: 1, last_page: 1 })
  const loading     = ref(false)
  const search      = ref('')

  async function fetchFiles(page = 1) {
    loading.value = true
    try {
      const res = await api.get('/files', {
        params: { page, search: search.value || undefined }
      })
      files.value      = res.data.data
      pagination.value = {
        current_page: res.data.current_page,
        last_page:    res.data.last_page,
      }
    } finally {
      loading.value = false
    }
  }

  async function uploadFiles(fileList) {
    const form = new FormData()
    for (const file of fileList) form.append('files[]', file)
    await api.post('/files', form)
    await fetchFiles(1)
  }

  async function deleteFile(id) {
    await api.delete(`/files/${id}`)
    await fetchFiles(pagination.value.current_page)
  }

  async function downloadFile(id, name) {
    const res = await api.get(`/files/${id}/download`, { responseType: 'blob' })
    const url = URL.createObjectURL(res.data)
    const a   = document.createElement('a')
    a.href     = url
    a.download = name
    a.click()
    URL.revokeObjectURL(url)
  }

  return { files, pagination, loading, search, fetchFiles, uploadFiles, deleteFile, downloadFile }
})

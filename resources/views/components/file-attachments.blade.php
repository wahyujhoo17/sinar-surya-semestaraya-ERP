<!-- File Attachment Component -->
<div x-data="attachmentManager()" class="space-y-4">
    <!-- Upload Area -->
    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <div class="mt-2">
                <label for="attachments" class="cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload File</span>
                    <input 
                        id="attachments" 
                        name="attachments[]" 
                        type="file" 
                        multiple 
                        accept=".pdf,.jpg,.jpeg,.png,.gif"
                        class="sr-only" 
                        @change="handleFileSelect($event)"
                    >
                </label>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                PDF, JPG, PNG, GIF hingga 10MB per file
            </p>
        </div>
    </div>

    <!-- Selected Files Preview -->
    <div x-show="selectedFiles.length > 0" class="space-y-2">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">File yang akan diupload:</h4>
        <template x-for="(file, index) in selectedFiles" :key="index">
            <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <template x-if="file.type.startsWith('image/')">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="file.type === 'application/pdf'">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="!file.type.startsWith('image/') && file.type !== 'application/pdf'">
                            <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="file.name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatFileSize(file.size)"></p>
                    </div>
                </div>
                <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700 p-1">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Existing Attachments (for edit mode) -->
    <div x-show="existingAttachments.length > 0" class="space-y-2">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">File yang sudah ada:</h4>
        <template x-for="(attachment, index) in existingAttachments" :key="index">
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <template x-if="attachment.mime_type && attachment.mime_type.startsWith('image/')">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="attachment.mime_type === 'application/pdf'">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="!attachment.mime_type || (!attachment.mime_type.startsWith('image/') && attachment.mime_type !== 'application/pdf')">
                            <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="attachment.original_name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <span x-text="formatFileSize(attachment.size)"></span>
                            <span x-text="' • ' + formatDate(attachment.uploaded_at)"></span>
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a :href="attachment.download_url" 
                       class="text-blue-500 hover:text-blue-700 p-1" 
                       title="Download">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <button type="button" @click="deleteExistingAttachment(index, attachment)" 
                            class="text-red-500 hover:text-red-700 p-1" 
                            title="Hapus">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function attachmentManager() {
    return {
        selectedFiles: [],
        existingAttachments: @json($existingAttachments ?? []),

        init() {
            // Process existing attachments to add download URLs
            this.existingAttachments = this.existingAttachments.map((attachment, index) => {
                return {
                    ...attachment,
                    download_url: this.getDownloadUrl(index)
                };
            });
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            const validFiles = [];

            files.forEach(file => {
                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.showNotification('error', `File ${file.name} tidak didukung. Hanya PDF dan gambar yang diperbolehkan.`);
                    return;
                }

                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    this.showNotification('error', `File ${file.name} terlalu besar. Maksimal 10MB per file.`);
                    return;
                }

                validFiles.push(file);
            });

            this.selectedFiles = [...this.selectedFiles, ...validFiles];
            
            // Reset the input
            event.target.value = '';
        },

        removeFile(index) {
            this.selectedFiles.splice(index, 1);
        },

        async deleteExistingAttachment(index, attachment) {
            if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                return;
            }

            try {
                const response = await fetch(attachment.delete_url || this.getDeleteUrl(index), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });

                const result = await response.json();

                if (result.success) {
                    this.existingAttachments.splice(index, 1);
                    this.showNotification('success', 'File berhasil dihapus.');
                } else {
                    this.showNotification('error', result.message || 'Gagal menghapus file.');
                }
            } catch (error) {
                this.showNotification('error', 'Terjadi kesalahan saat menghapus file.');
                console.error('Error:', error);
            }
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID');
        },

        getDownloadUrl(index) {
            // This should be overridden by the parent component
            return '#';
        },

        getDeleteUrl(index) {
            // This should be overridden by the parent component  
            return '#';
        },

        showNotification(type, message) {
            // Dispatch notification event
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    type: type,
                    message: message,
                    duration: 5000
                }
            }));
        }
    };
}
</script>
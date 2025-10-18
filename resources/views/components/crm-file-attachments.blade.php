<!-- File Attachment Component -->
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
<div x-data="attachmentManager()" class="space-y-4">
    <!-- Upload Area - Enhanced Design -->
    <div
        class="border-2 border-dashed border-gray-300 hover:border-blue-400 transition-colors rounded-xl p-8 text-center bg-gray-50 hover:bg-blue-50">
        <div class="mx-auto max-w-md">
            <!-- Upload Icon -->
            <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </div>

            <!-- Upload Text -->
            <div class="mb-4">
                <label for="attachments-{{ $modelType }}" class="cursor-pointer">
                    <div class="text-lg font-semibold text-gray-700 mb-2">Upload File Lampiran</div>
                    <div class="text-sm text-gray-500 mb-4">
                        Klik atau drag & drop file ke area ini
                    </div>
                    <div
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                clip-rule="evenodd" />
                        </svg>
                        Pilih File
                    </div>
                    <input id="attachments-{{ $modelType }}" name="attachments[]" type="file" multiple
                        accept=".pdf,.jpg,.jpeg,.png,.gif" class="sr-only" @change="handleFileSelect($event)">
                </label>
            </div>

            <!-- File Info - Only shown when no files selected -->
            <div class="text-xs text-gray-400 space-y-1" x-show="selectedFiles.length === 0">
                <div>📄 PDF, JPG, PNG, GIF</div>
                <div>📏 Maksimal 10MB per file</div>
                <div>📁 Multiple files diperbolehkan</div>
            </div>

            <!-- Selected Files List - Only shown when files are selected -->
            <div x-show="selectedFiles.length > 0" class="mt-4">
                <div class="text-sm font-medium text-gray-700 mb-2">File yang akan diupload:</div>
                <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
                    <template x-for="(fileItem, idx) in selectedFiles" :key="'upload-file-' + idx">
                        <div class="px-3 py-2 flex items-center justify-between">
                            <div class="flex items-center space-x-2 flex-1 min-w-0">
                                <!-- File Icon -->
                                <div class="flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <!-- File Name -->
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-gray-900 truncate"
                                        x-text="fileItem?.name || fileItem?.originalFile?.name || 'File ' + (idx + 1)">
                                        Loading...</div>
                                    <div class="text-xs text-gray-500"
                                        x-text="fileItem?.size ? formatFileSize(fileItem.size) : (fileItem?.originalFile?.size ? formatFileSize(fileItem.originalFile.size) : 'Unknown size')">
                                        Size loading...</div>
                                </div>
                            </div>
                            <!-- Remove Button -->
                            <button type="button" @click="removeFile(idx)"
                                class="text-red-500 hover:text-red-700 p-1 rounded">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                <!-- Files Count -->
                <div class="mt-2 text-xs text-gray-500 text-center">
                    <span
                        x-text="selectedFiles.length + (selectedFiles.length === 1 ? ' file dipilih' : ' files dipilih')"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Attachments (for edit mode) -->
    <div x-show="existingAttachments.length > 0" style="display: none;" class="space-y-2 mt-4">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">File yang sudah ada:</h4>
        <template x-for="(attachment, index) in existingAttachments" :key="index">
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <template x-if="attachment.mime_type && attachment.mime_type.startsWith('image/')">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="attachment.mime_type === 'application/pdf'">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template
                            x-if="!attachment.mime_type || (!attachment.mime_type.startsWith('image/') && attachment.mime_type !== 'application/pdf')">
                            <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate"
                            x-text="attachment.original_name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <span x-text="formatFileSize(attachment.size)"></span>
                            <span x-text="' • ' + formatDate(attachment.uploaded_at)"></span>
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a :href="attachment.download_url" class="text-blue-500 hover:text-blue-700 p-1" title="Download">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <button type="button" @click="deleteExistingAttachment(index, attachment)"
                        class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
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
            modelType: '{{ $modelType ?? 'prospek' }}',
            modelId: {{ $modelId ?? 'null' }},

            init() {
                // Process existing attachments to add download URLs
                this.existingAttachments = this.existingAttachments.map((attachment, index) => {
                    return {
                        ...attachment,
                        download_url: this.getDownloadUrl(index),
                        delete_url: this.getDeleteUrl(index)
                    };
                });
            },

            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                const validFiles = [];

                files.forEach(file => {
                    // Validate file type
                    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png',
                        'image/gif'
                    ];
                    if (!allowedTypes.includes(file.type)) {
                        this.showNotification('error',
                            `File ${file.name} tidak didukung. Hanya PDF dan gambar yang diperbolehkan.`);
                        return;
                    }

                    // Validate file size (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        this.showNotification('error',
                            `File ${file.name} terlalu besar. Maksimal 10MB per file.`);
                        return;
                    }

                    validFiles.push(file);
                });

                // Update preview - Simplified approach
                this.selectedFiles = [];

                // Direct assignment - keep both plain object and original file
                const fileList = validFiles.map((file, index) => {
                    return {
                        // Plain properties for display
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        // Keep original file for form submission
                        originalFile: file,
                        // Add index for debugging
                        index: index
                    };
                });

                console.log('File list created:', fileList);

                // Set files - this should trigger Alpine.js reactivity immediately
                this.selectedFiles = fileList;

                console.log('selectedFiles after assignment:', this.selectedFiles);

                // Show success notification
                if (fileList.length > 0) {
                    const fileNames = fileList.map(f => f.name).join(', ');
                    this.showNotification('success',
                        fileList.length === 1 ?
                        `File "${fileNames}" berhasil dipilih dan akan diupload.` :
                        `${fileList.length} file berhasil dipilih: ${fileNames}`
                    );
                }

                // Filter invalid files from input
                if (validFiles.length !== files.length && validFiles.length > 0) {
                    try {
                        const dt = new DataTransfer();
                        validFiles.forEach(file => dt.items.add(file)); // Use original File objects
                        event.target.files = dt.files;
                    } catch (error) {
                        console.error('Error updating file input:', error);
                    }
                }
            },

            removeFile(index) {
                // Remove from selectedFiles array
                this.selectedFiles.splice(index, 1);

                // Update the actual file input using originalFile references
                const input = document.getElementById('attachments-' + this.modelType);
                if (input && this.selectedFiles.length > 0) {
                    const dt = new DataTransfer();
                    this.selectedFiles.forEach(fileData => {
                        if (fileData.originalFile) {
                            dt.items.add(fileData.originalFile);
                        }
                    });
                    input.files = dt.files;
                } else if (input) {
                    // Clear input if no files left
                    input.value = '';
                }
            },

            async deleteExistingAttachment(index, attachment) {
                if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                    return;
                }

                try {
                    const response = await fetch(attachment.delete_url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
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
                if (this.modelType === 'prospek' && this.modelId) {
                    return `/crm/prospek/${this.modelId}/attachment/${index}/download`;
                } else if (this.modelType === 'aktivitas' && this.modelId) {
                    return `/crm/aktivitas/${this.modelId}/attachment/${index}/download`;
                }
                return '#';
            },

            getDeleteUrl(index) {
                if (this.modelType === 'prospek' && this.modelId) {
                    return `/crm/prospek/${this.modelId}/attachment/${index}`;
                } else if (this.modelType === 'aktivitas' && this.modelId) {
                    return `/crm/aktivitas/${this.modelId}/attachment/${index}`;
                }
                return '#';
            },

            showNotification(type, message) {
                // Try to use existing notification system or create basic alert
                if (typeof window.showToast === 'function') {
                    window.showToast(type, message);
                } else if (typeof window.notify === 'function') {
                    window.notify(type, message);
                } else {
                    // Fallback to simple alert
                    alert(`${type.toUpperCase()}: ${message}`);
                }
            }
        };
    }
</script>

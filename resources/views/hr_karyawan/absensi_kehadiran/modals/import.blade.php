{{-- Import Attendance Modal --}}
<div x-data="importAttendanceModal" x-show="open" @open-import-modal.window="openModal()"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        {{-- Modal centering trick --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        {{-- Modal panel --}}
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

            {{-- Modal header --}}
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-start">
                    <div
                        class="flex-shrink-0 mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Import Data Absensi
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Upload file CSV atau Excel untuk mengimpor data absensi karyawan secara massal
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal body --}}
            <div class="bg-white dark:bg-gray-800 px-4 pb-4 sm:p-6 sm:pb-4">
                {{-- File Upload Section --}}
                <div x-show="!showResults" class="space-y-6">
                    {{-- File Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            File CSV/Excel <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="file-upload"
                                        class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input id="file-upload" name="file-upload" type="file"
                                            accept=".csv,.xlsx,.xls" class="sr-only" @change="handleFileSelect($event)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    File CSV/Excel hingga 2MB (Format: .csv, .xlsx, .xls)
                                </p>
                            </div>
                        </div>
                        <div x-show="selectedFile" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            File terpilih: <span x-text="selectedFile?.name"></span>
                        </div>
                    </div>

                    {{-- CSV Format Guide --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Format File (CSV/Excel)
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>File CSV atau Excel harus memiliki kolom header berikut:</p>
                                    <ul class="list-disc pl-5 mt-1">
                                        <li><strong>nama_karyawan</strong> atau <strong>nip</strong> - Nama atau NIP
                                            karyawan</li>
                                        <li><strong>tanggal</strong> - Format: YYYY-MM-DD</li>
                                        <li><strong>jam_masuk</strong> - Format: HH:MM (opsional)</li>
                                        <li><strong>jam_keluar</strong> - Format: HH:MM (opsional)</li>
                                        <li><strong>status</strong> - hadir, terlambat, alpha, izin, cuti, dinas_luar
                                        </li>
                                        <li><strong>keterangan</strong> - Keterangan tambahan (opsional)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sample Data --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-md">
                        <div
                            class="px-4 py-2 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Template Import:</h4>
                            <div class="flex gap-2">
                                <a href="{{ route('hr.absensi.template') }}"
                                    class="inline-flex items-center px-3 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Download Template Excel
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-medium mb-2">Petunjuk Penggunaan:</p>
                                <ol class="list-decimal pl-5 space-y-1">
                                    <li>Download template Excel di atas</li>
                                    <li>Isi data absensi sesuai format yang sudah disediakan</li>
                                    <li>Pastikan nama karyawan sesuai dengan data yang ada di sistem</li>
                                    <li>Format jam masuk/keluar yang didukung:
                                        <ul class="list-disc pl-4 mt-1 text-xs">
                                            <li>08:30 (standar)</li>
                                            <li>08.30 (dengan titik)</li>
                                            <li>08:30:00 atau 08.30.00 (dengan detik, akan diabaikan)</li>
                                        </ul>
                                    </li>
                                    <li>Jam 00:00 tidak akan dianggap sebagai jam masuk/keluar yang valid</li>
                                    <li>Sistem akan otomatis mendeteksi keterlambatan jika jam masuk > 08:30</li>
                                    <li>Upload file yang sudah diisi</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Import Results Section --}}
                <div x-show="showResults" class="space-y-4">
                    {{-- Success Summary --}}
                    <div x-show="importResult.success" class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    Import Berhasil
                                </h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    <p x-text="importResult.message"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Errors --}}
                    <div x-show="importResult.errors && importResult.errors.length > 0"
                        class="rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    Terdapat beberapa kesalahan:
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <template x-for="error in importResult.errors" :key="error">
                                            <li x-text="error"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal footer --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button x-show="!showResults" @click="uploadFile()" :disabled="!selectedFile || loading"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Import</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Mengimpor...
                    </span>
                </button>

                <button x-show="showResults" @click="closeModal()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Selesai
                </button>

                <button x-show="!showResults" @click="closeModal()" :disabled="loading"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('importAttendanceModal', () => ({
            open: false,
            loading: false,
            selectedFile: null,
            showResults: false,
            importResult: {},

            openModal() {
                this.open = true;
                this.resetModal();
            },

            closeModal() {
                this.open = false;
                this.resetModal();
                // Trigger data reload if import was successful
                if (this.importResult.success) {
                    this.$dispatch('filters-changed', {});
                }
            },

            resetModal() {
                this.selectedFile = null;
                this.showResults = false;
                this.importResult = {};
                this.loading = false;
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    // Accept CSV and Excel files
                    const allowedTypes = [
                        'text/csv',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ];

                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    const allowedExtensions = ['csv', 'xls', 'xlsx'];

                    if (allowedTypes.includes(file.type) || allowedExtensions.includes(
                            fileExtension)) {
                        this.selectedFile = file;
                    } else {
                        alert('Silakan pilih file CSV atau Excel (.csv, .xls, .xlsx) yang valid');
                        event.target.value = '';
                    }
                } else {
                    this.selectedFile = null;
                }
            },

            async uploadFile() {
                if (!this.selectedFile) return;

                this.loading = true;

                try {
                    const formData = new FormData();
                    formData.append('file', this.selectedFile);

                    const response = await fetch('{{ route('hr.absensi.import') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    this.importResult = result;
                    this.showResults = true;

                    if (result.success) {
                        // Show toast notification
                        window.dispatchEvent(new CustomEvent('show-notification', {
                            detail: {
                                message: result.message,
                                type: 'success'
                            }
                        }));
                        // Trigger data reload
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('refresh-data'));
                        }, 1000);
                    }

                } catch (error) {
                    console.error('Error:', error);
                    this.importResult = {
                        success: false,
                        message: 'Terjadi kesalahan saat mengimpor file',
                        errors: []
                    };
                    this.showResults = true;
                    // Show error notification
                    window.dispatchEvent(new CustomEvent('show-notification', {
                        detail: {
                            message: 'Terjadi kesalahan saat mengimpor file',
                            type: 'error'
                        }
                    }));
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>

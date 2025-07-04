{{-- Approve/Reject Cuti Modal --}}
<div x-data="approveCutiModal" x-show="open" @approve-cuti.window="openModal($event.detail)"
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
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            {{-- Modal header --}}
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                        :class="{
                            'bg-green-100 dark:bg-green-900': actionType === 'disetujui',
                            'bg-red-100 dark:bg-red-900': actionType === 'ditolak'
                        }">
                        <svg x-show="actionType === 'disetujui'" class="h-6 w-6 text-green-600 dark:text-green-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="actionType === 'ditolak'" class="h-6 w-6 text-red-600 dark:text-red-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                            x-text="actionType === 'disetujui' ? 'Setujui Pengajuan Cuti' : 'Tolak Pengajuan Cuti'">
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400"
                                x-text="actionType === 'disetujui' ? 'Apakah Anda yakin ingin menyetujui pengajuan cuti ini?' : 'Apakah Anda yakin ingin menolak pengajuan cuti ini?'">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cuti Summary --}}
            <div
                class="bg-gray-50 dark:bg-gray-700/50 mx-4 rounded-lg p-4 border border-gray-200 dark:border-gray-600 mb-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Detail Pengajuan Cuti</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Karyawan:</span>
                        <span class="ml-1 font-medium text-gray-900 dark:text-white"
                            x-text="cuti.karyawan?.nama_lengkap || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Jenis Cuti:</span>
                        <span class="ml-1 font-medium text-gray-900 dark:text-white"
                            x-text="getJenisCutiLabel(cuti.jenis_cuti)"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Periode:</span>
                        <span class="ml-1 font-medium text-gray-900 dark:text-white">
                            <span x-text="formatDate(cuti.tanggal_mulai)"></span> - <span
                                x-text="formatDate(cuti.tanggal_selesai)"></span>
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Durasi:</span>
                        <span class="ml-1 font-medium text-gray-900 dark:text-white"
                            x-text="(cuti.jumlah_hari || 0) + ' hari'"></span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-gray-500 dark:text-gray-400">Keterangan:</span>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="cuti.keterangan || '-'"></p>
                </div>
            </div>

            {{-- Modal body --}}
            <form @submit.prevent="submitForm()">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 sm:p-6 sm:pb-4">
                    <div>
                        <label for="catatan_persetujuan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan <span x-show="actionType === 'ditolak'" class="text-red-500">*</span>
                        </label>
                        <textarea id="catatan_persetujuan" x-model="form.catatan_persetujuan" rows="4"
                            :required="actionType === 'ditolak'"
                            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400"
                            :placeholder="actionType === 'disetujui' ? 'Catatan persetujuan (opsional)...' :
                                'Alasan penolakan (wajib)...'"></textarea>
                        <p x-show="errors.catatan_persetujuan" x-text="errors.catatan_persetujuan"
                            class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                    </div>
                </div>

                {{-- Modal footer --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" :disabled="loading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="{
                            'bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:ring-green-500': actionType === 'disetujui',
                            'bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 focus:ring-red-500': actionType === 'ditolak'
                        }">
                        <span x-show="!loading" x-text="actionType === 'disetujui' ? 'Setujui' : 'Tolak'"></span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="actionType === 'disetujui' ? 'Menyetujui...' : 'Menolak...'"></span>
                        </span>
                    </button>
                    <button type="button" @click="closeModal()" :disabled="loading"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('approveCutiModal', () => ({
            open: false,
            loading: false,
            cuti: {},
            actionType: 'disetujui', // 'disetujui' or 'ditolak'
            form: {
                catatan_persetujuan: ''
            },
            errors: {},

            openModal(data) {
                this.open = true;
                this.cuti = data.item;
                this.actionType = data.status;
                this.resetForm();
            },

            closeModal() {
                this.open = false;
                this.resetForm();
            },

            resetForm() {
                this.form = {
                    catatan_persetujuan: ''
                };
                this.errors = {};
            },

            formatDate(date) {
                if (!date) return '-';
                return new Date(date).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            },

            getJenisCutiLabel(jenis) {
                const labels = {
                    'tahunan': 'Cuti Tahunan',
                    'sakit': 'Sakit',
                    'melahirkan': 'Melahirkan',
                    'penting': 'Keperluan Penting',
                    'lainnya': 'Lainnya'
                };
                return labels[jenis] || jenis;
            },

            async submitForm() {
                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch(
                        `{{ route('hr.cuti.index') }}/${this.cuti.id}/status`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: this.actionType,
                                catatan_persetujuan: this.form.catatan_persetujuan
                            })
                        });

                    const result = await response.json();

                    if (result.success) {
                        showNotification(result.message, 'success');
                        this.closeModal();
                        window.dispatchEvent(new CustomEvent('cuti-updated'));
                    } else {
                        if (result.errors) {
                            this.errors = result.errors;
                        } else {
                            showNotification(result.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    showNotification('Terjadi kesalahan saat mengirim data', 'error');
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>

{{-- View Cuti Modal --}}
<div x-data="viewCutiModal" x-show="open" @view-cuti.window="openModal($event.detail)"
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
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Detail Pengajuan Cuti
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Informasi lengkap pengajuan cuti karyawan
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal body --}}
            <div class="bg-white dark:bg-gray-800 px-4 pb-4 sm:p-6 sm:pb-4">
                <div class="space-y-6">
                    {{-- Employee Info --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Informasi Karyawan
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama Lengkap</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                    x-text="cuti.karyawan?.nama_lengkap || '-'"></p>
                            </div>
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    NIP</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                    x-text="cuti.karyawan?.nip || '-'"></p>
                            </div>
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Departemen</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                    x-text="cuti.karyawan?.department?.nama || '-'"></p>
                            </div>
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jabatan</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                    x-text="cuti.karyawan?.jabatan?.nama || '-'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Cuti Details --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jenis Cuti</p>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="{
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': cuti
                                        .jenis_cuti === 'tahunan',
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': cuti
                                        .jenis_cuti === 'sakit',
                                    'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400': cuti
                                        .jenis_cuti === 'melahirkan',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': cuti
                                        .jenis_cuti === 'penting',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400': cuti
                                        .jenis_cuti === 'lainnya'
                                }"
                                x-text="getJenisCutiLabel(cuti.jenis_cuti)">
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</p>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="{
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': cuti
                                        .status === 'diajukan',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': cuti
                                        .status === 'disetujui',
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': cuti
                                        .status === 'ditolak'
                                }"
                                x-text="getStatusLabel(cuti.status)">
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal Mulai</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                x-text="formatDate(cuti.tanggal_mulai)"></p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal Selesai</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                x-text="formatDate(cuti.tanggal_selesai)"></p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Durasi</p>
                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="cuti.jumlah_hari || 0"></span> hari
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal Pengajuan</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                x-text="formatDateTime(cuti.created_at)"></p>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Keterangan</p>
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-900 dark:text-white" x-text="cuti.keterangan || '-'"></p>
                        </div>
                    </div>

                    {{-- Approval Info --}}
                    <div x-show="cuti.status !== 'diajukan'">
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-primary-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Persetujuan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Diproses Oleh</p>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="cuti.approver?.name || '-'"></p>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal Diproses</p>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="formatDateTime(cuti.updated_at)"></p>
                                </div>
                            </div>
                            <div x-show="cuti.catatan_persetujuan" class="mt-4">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                    Catatan Persetujuan</p>
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-900 dark:text-white"
                                        x-text="cuti.catatan_persetujuan"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Info --}}
                    <div x-show="cuti.status === 'disetujui' && isCurrentlyCuti()"
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium text-green-800 dark:text-green-200">
                                Karyawan sedang dalam masa cuti
                            </span>
                        </div>
                        <p class="text-xs text-green-600 dark:text-green-300 mt-1 ml-7">
                            Cuti akan berakhir pada <span x-text="formatDate(cuti.tanggal_selesai)"></span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal footer --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="closeModal()"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('viewCutiModal', () => ({
            open: false,
            cuti: {},

            openModal(cuti) {
                this.open = true;
                this.cuti = cuti;
            },

            closeModal() {
                this.open = false;
                this.cuti = {};
            },

            formatDate(date) {
                if (!date) return '-';
                return new Date(date).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            },

            formatDateTime(datetime) {
                if (!datetime) return '-';
                return new Date(datetime).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
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

            getStatusLabel(status) {
                const labels = {
                    'diajukan': 'Menunggu Persetujuan',
                    'disetujui': 'Disetujui',
                    'ditolak': 'Ditolak'
                };
                return labels[status] || status;
            },

            isCurrentlyCuti() {
                if (this.cuti.status !== 'disetujui') return false;

                const today = new Date();
                const startDate = new Date(this.cuti.tanggal_mulai);
                const endDate = new Date(this.cuti.tanggal_selesai);

                return today >= startDate && today <= endDate;
            }
        }));
    });
</script>

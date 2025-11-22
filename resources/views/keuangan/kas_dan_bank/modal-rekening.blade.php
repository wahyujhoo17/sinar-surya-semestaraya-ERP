<div x-data="modalRekeningManager()" x-init="$nextTick(() => {
    window.addEventListener('open-rekening-modal', event => {
        openModal(event.detail || {});
    });
});" x-show="isOpen" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="isOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            x-show="isOpen" x-transition:enter="transition ease-out duration-300 delay-150"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="modalTitle"></h3>
                <button @click="closeModal()" type="button"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4">
                <form id="rekening-form" @submit.prevent="submitForm()">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="nama_bank"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank <span
                                    class="text-red-600">*</span></label>
                            <input type="text" name="nama_bank" id="nama_bank" x-model="formData.nama_bank" required
                                placeholder="Nama Bank"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.nama_bank"
                                x-text="errors.nama_bank"></p>
                        </div>
                        <div>
                            <label for="nomor_rekening"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Rekening
                                <span class="text-red-600">*</span></label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening"
                                x-model="formData.nomor_rekening" required placeholder="Nomor Rekening"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.nomor_rekening"
                                x-text="errors.nomor_rekening"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="atas_nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Atas Nama <span
                                    class="text-red-600">*</span></label>
                            <input type="text" name="atas_nama" id="atas_nama" x-model="formData.atas_nama" required
                                placeholder="Atas Nama"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.atas_nama"
                                x-text="errors.atas_nama"></p>
                        </div>
                        <div>
                            <label for="cabang"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cabang</label>
                            <input type="text" name="cabang" id="cabang" x-model="formData.cabang"
                                placeholder="Cabang Bank"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.cabang"
                                x-text="errors.cabang"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="rekening_saldo"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo Awal <span
                                class="text-red-600" x-show="!isEdit">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="saldo" id="rekening_saldo" x-model="formData.saldo"
                                :required="!isEdit" placeholder="0" :readonly="isEdit"
                                class="w-full pl-12 pr-4 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm"
                                :class="{ 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed': isEdit }">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="!isEdit">Input saldo rekening
                            saat ini. Sistem akan otomatis membuat jurnal pembukaan.</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="isEdit">Saldo hanya dapat
                            diubah melalui transaksi</p>
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.saldo"
                            x-text="errors.saldo"></p>
                    </div>

                    <div class="flex items-center mb-4">
                        <label class="flex items-center mr-8">
                            <input type="checkbox" name="is_aktif" x-model="formData.is_aktif"
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Rekening Aktif</span>
                        </label>
                    </div>

                    <div class="flex justify-end mt-4 space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="closeModal"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </button>
                        <button type="submit" :disabled="loading"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
                            <span x-show="loading" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Memproses...</span>
                            </span>
                            <span x-show="!loading" x-text="isEdit ? 'Perbarui' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function modalRekeningManager() {
        return {
            isOpen: false,
            isEdit: false,
            modalTitle: 'Tambah Rekening Bank',
            loading: false,
            rekeningId: null,
            formData: {
                nama_bank: '',
                nomor_rekening: '',
                atas_nama: '',
                cabang: '',
                saldo: 0,
                is_aktif: true,
                is_perusahaan: true, // Selalu true karena ini rekening perusahaan
            },
            errors: {},
            openModal(data = {}) {
                // console.log('Opening rekening modal', data);
                this.resetForm();
                if (data.mode === 'edit' && data.rekening) {
                    this.isEdit = true;
                    this.modalTitle = 'Edit Rekening Bank';
                    this.rekeningId = data.rekening.id;
                    this.formData.nama_bank = data.rekening.nama_bank;
                    this.formData.nomor_rekening = data.rekening.nomor_rekening;
                    this.formData.atas_nama = data.rekening.atas_nama;
                    this.formData.cabang = data.rekening.cabang;
                    this.formData.saldo = data.rekening.saldo;
                    this.formData.is_aktif = !!data.rekening.is_aktif;
                    this.formData.is_perusahaan = true;
                } else {
                    this.isEdit = false;
                    this.modalTitle = 'Tambah Rekening Bank';
                }
                document.body.classList.add('overflow-hidden'); // Prevent scrolling when modal is open
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => this.resetForm(), 300);
            },
            resetForm() {
                this.formData = {
                    nama_bank: '',
                    nomor_rekening: '',
                    atas_nama: '',
                    cabang: '',
                    saldo: 0,
                    is_aktif: true,
                    is_perusahaan: true
                };
                this.errors = {};
                this.loading = false;
            },
            submitForm() {
                this.loading = true;
                this.errors = {};
                const form = document.getElementById('rekening-form');
                const formData = new FormData(form);
                formData.set('is_aktif', this.formData.is_aktif ? '1' : '0');
                formData.set('is_perusahaan', '1'); // Selalu set ke true
                let url, method;
                if (this.isEdit) {
                    url = `/keuangan/kas-dan-bank/rekening/${this.rekeningId}`;
                    method = 'POST';
                    formData.append('_method', 'PUT');
                } else {
                    url = '{{ route('keuangan.kas-dan-bank.store-rekening') }}';
                    method = 'POST';
                }
                fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw data;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.loading = false;
                        console.log('Success response:', data); // Debug log

                        // Use the global notify function
                        if (typeof window.notify === 'function') {
                            window.notify(data.message || 'Rekening bank berhasil disimpan', 'success');
                        } else if (typeof window.showSuccess === 'function') {
                            window.showSuccess(data.message || 'Rekening bank berhasil disimpan');
                        } else {
                            alert(data.message || 'Rekening bank berhasil disimpan');
                        }

                        this.closeModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        this.loading = false;
                        console.error('Error response:', error); // Debug log
                        if (error.errors) {
                            this.errors = error.errors;
                        } else {
                            if (typeof window.notify === 'function') {
                                window.notify(error.message || 'Terjadi kesalahan saat memproses data', 'error');
                            } else if (typeof window.showError === 'function') {
                                window.showError(error.message || 'Terjadi kesalahan saat memproses data');
                            } else {
                                alert('Error: ' + (error.message || 'Terjadi kesalahan saat memproses data'));
                            }
                        }
                    });
            }
        };
    }
</script>

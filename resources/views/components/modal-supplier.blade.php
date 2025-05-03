<div x-data="modalSupplierManager()" x-show="isOpen" @open-supplier-modal.window="openModal($event.detail)"
    @close-supplier-modal.window="closeModal()" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="isOpen" x-cloak
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
            x-show="isOpen" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
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
                <form id="supplier-form" @submit.prevent="submitForm()">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="kode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Supplier
                                <span class="text-red-600">*</span></label>
                            <div class="flex space-x-2">
                                <input type="text" name="kode" id="kode" x-model="formData.kode" required
                                    class="w-full rounded-md bg-gray-50 dark:bg-gray-600 border-gray-300 dark:border-gray-600 dark:text-gray-300 shadow-sm text-sm">
                                <button type="button" @click="generateKodeSupplier"
                                    class="px-2 py-1 bg-primary-600 text-white rounded-md text-xs hover:bg-primary-700 focus:outline-none">
                                    Generate
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama <span
                                    class="text-red-600">*</span></label>
                            <input type="text" name="nama" id="nama" x-model="formData.nama" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="telepon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                            <input type="text" name="telepon" id="telepon" x-model="formData.telepon"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" id="email" x-model="formData.email"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="alamat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                            <input type="text" name="alamat" id="alamat" x-model="formData.alamat"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="type_produksi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe
                                Produksi</label>
                            <input type="text" name="type_produksi" id="type_produksi"
                                x-model="formData.type_produksi"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="nama_kontak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                Kontak</label>
                            <input type="text" name="nama_kontak" id="nama_kontak" x-model="formData.nama_kontak"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <input type="text" name="catatan" id="catatan" x-model="formData.catatan"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div class="flex items-center mt-2 md:col-span-2">
                            <input type="checkbox" id="is_active" name="is_active" x-model="formData.is_active"
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 mr-2">
                            <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Aktif</label>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="closeModal"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </button>
                        <button type="submit" :disabled="loading"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
                            <span x-show="loading" x-cloak class="mr-2">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span x-text="isEdit ? 'Perbarui' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function modalSupplierManager() {
        return {
            isOpen: false,
            isEdit: false,
            modalTitle: 'Tambah Supplier',
            loading: false,
            supplierId: null,
            formData: {
                kode: '',
                nama: '',
                alamat: '',
                telepon: '',
                email: '',
                nama_kontak: '', // Tambah field nama_kontak
                type_produksi: '',
                catatan: '',
                is_active: true, // Set default ke true
            },
            errors: {},
            openModal(data = {}) {
                this.resetForm();
                if (data.mode === 'edit' && data.supplier) {
                    this.isEdit = true;
                    this.modalTitle = 'Edit Supplier';
                    this.supplierId = data.supplier.id;
                    // Convert is_active to boolean
                    data.supplier.is_active = Boolean(data.supplier.is_active);
                    Object.assign(this.formData, data.supplier);
                } else {
                    this.isEdit = false;
                    this.modalTitle = 'Tambah Supplier';
                    this.formData.is_active = true; // Set default ke true untuk supplier baru
                }
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
                setTimeout(() => this.resetForm(), 300);
            },
            resetForm() {
                this.formData = {
                    kode: '',
                    nama: '',
                    alamat: '',
                    telepon: '',
                    email: '',
                    nama_kontak: '', // Tambah field nama_kontak
                    type_produksi: '',
                    catatan: '',
                    is_active: true,
                };
                this.errors = {};
                this.loading = false;
            },
            submitForm() {
                this.loading = true;
                this.errors = {};
                const form = document.getElementById('supplier-form');
                const formData = new FormData(form);

                // Ubah cara mengirim is_active
                formData.set('is_active', this.formData.is_active ? '1' : '0');

                let url, method;
                if (this.isEdit) {
                    url = `/master-data/supplier/${this.supplierId}`;
                    method = 'POST';
                    formData.append('_method', 'PUT');
                } else {
                    url = '{{ route('master.supplier.store') }}';
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
                        return response.json ? response.json() : {};
                    })
                    .then(data => {
                        this.loading = false;
                        notify('Data supplier berhasil disimpan.', 'success');
                        window.dispatchEvent(new CustomEvent('refresh-supplier-table'));
                        this.closeModal();
                    })
                    .catch(error => {
                        this.loading = false;
                        if (error && error.errors) {
                            this.errors = error.errors;
                        } else {
                            notify(error.message || 'Terjadi kesalahan saat menyimpan data.', 'error', 'Error');
                        }
                    });
            },
            generateKodeSupplier() {
                // Logic to generate kode supplier
                this.formData.kode = 'SUP-' + Math.random().toString(36).substr(2, 5).toUpperCase();
            }
        };
    }
</script>

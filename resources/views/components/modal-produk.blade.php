<div x-data="modalProdukManager()" x-show="isOpen" @open-produk-modal.window="openModal($event.detail)"
    @close-produk-modal.window="closeModal()" x-cloak class="fixed inset-0 z-50 overflow-y-auto">

    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="isOpen" x-cloak
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
            x-show="isOpen" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Modal Header -->
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

            <!-- Modal Content -->
            <div class="px-6 py-4">
                <form id="produk-form" @submit.prevent="submitForm()" enctype="multipart/form-data">
                    @csrf

                    <!-- Perbaikan: Gunakan template x-if alih-alih x-show untuk  -->
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Modify the grid layout to be more compact -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Kode Produk (Auto-generated, readonly) -->
                        <div>
                            <label for="kode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                                Produk</label>
                            <input type="text" name="kode" id="kode" x-model="formData.kode" readonly
                                class="w-full rounded-md bg-gray-50 dark:bg-gray-600 border-gray-300 dark:border-gray-600 dark:text-gray-300 shadow-sm text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode akan dibuat otomatis</p>
                        </div>

                        <!-- SKU & Nama in same row -->
                        <div>
                            <label for="product_sku"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU</label>
                            <input type="text" name="product_sku" id="product_sku" x-model="formData.product_sku"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>

                        <div>
                            <label for="nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama <span
                                    class="text-red-600">*</span></label>
                            <input type="text" name="nama" id="nama" x-model="formData.nama" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>

                        <!-- Kategori, Jenis, Satuan -->
                        <div>
                            <label for="kategori_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span
                                    class="text-red-600">*</span></label>
                            <select name="kategori_id" id="kategori_id" x-model="formData.kategori_id" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jenis_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis</label>
                            <select name="jenis_id" id="jenis_id" x-model="formData.jenis_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                <option value="">Pilih Jenis</option>
                                @foreach ($jenisProduks as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="satuan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan <span
                                    class="text-red-600">*</span></label>
                            <select name="satuan_id" id="satuan_id" x-model="formData.satuan_id" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                <option value="">Pilih Satuan</option>
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Merek, Sub Kategori, Tipe Material -->
                        <div>
                            <label for="merek"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Merek</label>
                            <input type="text" name="merek" id="merek" x-model="formData.merek"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>

                        <div>
                            <label for="sub_kategori"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub
                                Kategori</label>
                            <select name="sub_kategori" id="sub_kategori" x-model="formData.sub_kategori"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                <option value="">Pilih Sub Kategori</option>
                                <option value="Barang Baku">Barang Baku</option>
                                <option value="Barang Jadi">Barang Jadi</option>
                            </select>
                        </div>

                        <div>
                            <label for="type_material"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe
                                Material</label>
                            <input type="text" name="type_material" id="type_material"
                                x-model="formData.type_material"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>

                        <!-- Kualitas, Harga -->
                        <div>
                            <label for="kualitas"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kualitas</label>
                            <input type="text" name="kualitas" id="kualitas" x-model="formData.kualitas"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>

                        <div>
                            <label for="harga_jual"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Jual
                                <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 text-xs">Rp</span>
                                <input type="number" name="harga_jual" id="harga_jual"
                                    x-model="formData.harga_jual" required min="0"
                                    class="pl-9 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                            </div>
                        </div>

                        <!-- Add this after harga_jual input -->
                        <div>
                            <label for="harga_beli"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga
                                Beli</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 text-xs">Rp</span>
                                <input type="number" name="harga_beli" id="harga_beli"
                                    x-model="formData.harga_beli" min="0" placeholder="0"
                                    class="pl-9 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="stok_minimum"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok
                                Minimum</label>
                            <input type="number" name="stok_minimum" id="stok_minimum"
                                x-model="formData.stok_minimum" min="0"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <!-- Status Produk Dropdown di samping Stok Minimum -->
                        <div>
                            <label for="is_active"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select id="is_active" name="is_active" x-model="formData.is_active"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" x-model="formData.deskripsi" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"></textarea>
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.deskripsi"
                            x-text="errors.deskripsi"></p>
                    </div>

                    <!-- Gambar -->
                    <div class="mb-4">
                        <label for="gambar"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar
                            Produk</label>

                        <!-- Preview gambar yang sudah ada -->
                        <div x-show="imagePreview || formData.current_image" class="mb-3">
                            <div
                                class="relative w-32 h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                <img x-bind:src="imagePreview || formData.current_image" alt="Preview gambar"
                                    class="w-full h-full object-cover">
                                <button type="button" @click="removeImage"
                                    class="absolute top-1 right-1 bg-red-100 dark:bg-red-900 text-red-500 dark:text-red-400 rounded-full p-1 hover:bg-red-200 dark:hover:bg-red-800">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Perbaikan: gunakan satu input file saja, jangan duplikat -->
                        <div class="flex items-center justify-center w-full"
                            x-show="!imagePreview && !formData.current_image">
                            <label for="gambar"
                                class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed dark:border-gray-600 rounded-lg cursor-pointer dark:hover:bg-gray-700/30 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 002-2V6a2 2 2z" />
                                    </svg>
                                    <p class="pt-1 text-sm text-gray-500 dark:text-gray-400">Upload gambar</p>
                                </div>
                                <input id="gambar" name="gambar" type="file" class="hidden"
                                    @change="handleImageUpload($event)">
                            </label>
                        </div>

                        <div class="mt-2 flex items-center" x-show="imagePreview || formData.current_image">
                            <label for="gambar"
                                class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer">
                                Ganti Gambar
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.gambar"
                            x-text="errors.gambar"></p>
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
    function modalProdukManager() {
        return {
            isOpen: false,
            isEdit: false,
            modalTitle: 'Tambah Produk',
            loading: false,
            imagePreview: null,
            productId: null,
            formData: {
                kode: '',
                nama: '',
                product_sku: '',
                kategori_id: '',
                jenis_id: '',
                ukuran: '',
                satuan_id: '',
                merek: '',
                sub_kategori: '',
                type_material: '',
                kualitas: '',
                deskripsi: '',
                harga_jual: '',
                harga_beli: '',
                stok_minimum: '',
                is_active: '1',
                current_image: null
            },
            errors: {},

            async generateProductCode() {
                try {
                    const response = await fetch('/master-data/produk/generate-code');
                    const data = await response.json();
                    if (data.success) {
                        this.formData.kode = data.code;
                    }
                } catch (error) {
                    console.error('Error generating product code:', error);
                }
            },

            openModal(data = {}) {
                this.resetForm();

                if (data.mode === 'edit' && data.product) {
                    this.isEdit = true;
                    this.modalTitle = 'Edit Produk';
                    this.productId = data.product.id;

                    this.formData.kode = data.product.kode;
                    this.formData.nama = data.product.nama;
                    this.formData.product_sku = data.product.product_sku;
                    this.formData.kategori_id = data.product.kategori_id;
                    this.formData.jenis_id = data.product.jenis_id;
                    this.formData.ukuran = data.product.ukuran;
                    this.formData.satuan_id = data.product.satuan_id;
                    this.formData.merek = data.product.merek;
                    this.formData.sub_kategori = data.product.sub_kategori;
                    this.formData.type_material = data.product.type_material;
                    this.formData.kualitas = data.product.kualitas;
                    this.formData.deskripsi = data.product.deskripsi;
                    this.formData.harga_jual = data.product.harga_jual;
                    this.formData.harga_beli = data.product.harga_beli;
                    this.formData.stok_minimum = data.product.stok_minimum;
                    this.formData.is_active = data.product.is_active ? '1' : '0';

                    if (data.product.gambar_url) {
                        this.formData.current_image = data.product.gambar_url;
                    }
                } else {
                    this.isEdit = false;
                    this.modalTitle = 'Tambah Produk';
                    this.generateProductCode();
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
                    product_sku: '',
                    kategori_id: '',
                    jenis_id: '',
                    ukuran: '',
                    satuan_id: '',
                    merek: '',
                    sub_kategori: '',
                    type_material: '',
                    kualitas: '',
                    deskripsi: '',
                    harga_jual: '',
                    harga_beli: '',
                    stok_minimum: '',
                    is_active: '1',
                    current_image: null
                };
                this.errors = {};
                this.imagePreview = null;
                this.loading = false;
            },

            handleImageUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                const maxSize = 2 * 1024 * 1024;

                if (!validTypes.includes(file.type)) {
                    this.errors.gambar = 'File harus berupa gambar (JPEG, PNG, JPG, GIF, SVG)';
                    return;
                }

                if (file.size > maxSize) {
                    this.errors.gambar = 'Ukuran gambar maksimal 2MB';
                    return;
                }

                this.errors.gambar = null;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            removeImage() {
                this.imagePreview = null;
                this.formData.current_image = null;
                document.getElementById('gambar').value = '';
            },

            submitForm() {
                this.loading = true;
                this.errors = {};

                const form = document.getElementById('produk-form');
                const formData = new FormData(form);

                const fileInput = document.getElementById('gambar');

                if (fileInput.files[0]) {
                    formData.set('gambar', fileInput.files[0]);
                }

                let url, method;

                if (this.isEdit) {
                    url = `/master-data/produk/${this.productId}`;
                    method = 'POST';
                    formData.append('_method', 'PUT');
                } else {
                    url = '{{ route('master.produk.store') }}';
                    method = 'POST';
                }

                formData.set('is_active', this.formData.is_active);

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

                        notify(data.message, 'success');

                        window.dispatchEvent(new CustomEvent('refresh-product-table', {
                            detail: {
                                action: this.isEdit ? 'update' : 'create',
                                product: data.product
                            }
                        }));

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
            }
        };
    }
</script>

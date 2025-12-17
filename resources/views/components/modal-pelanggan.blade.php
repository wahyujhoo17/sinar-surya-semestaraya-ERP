{{-- Include styles and scripts at the top level (outside any blocks) --}}
@include('components.custom-dropdown-styles')
@include('components.custom-dropdown')

<div x-data="modalPelangganManager()" x-init="init()" x-show="isOpen" @open-pelanggan-modal.window="openModal($event.detail)"
    @close-pelanggan-modal.window="closeModal()" x-cloak
    class="fixed inset-0 z-50 overflow-y-auto modal-pelanggan-container">
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
                <form id="pelanggan-form" @submit.prevent="submitForm()">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="kode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                                Pelanggan</label>
                            <input type="text" name="kode" id="kode" x-model="formData.kode" readonly
                                class="w-full rounded-md bg-gray-50 dark:bg-gray-600 border-gray-300 dark:border-gray-600 dark:text-gray-300 shadow-sm text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode akan dibuat otomatis</p>
                        </div>
                        <div>
                            <label for="company"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Perusahaan</label>
                            <input type="text" name="company" id="company" x-model="formData.company"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                            <input type="text" name="nama" id="nama" x-model="formData.nama"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="tipe"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe</label>
                            <input type="text" name="tipe" id="tipe" x-model="formData.tipe"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="group"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group</label>
                            <div x-data="editableCombobox()" class="relative w-full" x-init="init('{{ route('master.pelanggan.get-customer-groups') }}')">
                                <input type="text" name="group" id="group" x-model="formData.group"
                                    @input="handleInput" @focus="showDropdown" @blur="hideDropdown"
                                    placeholder="Pilih atau ketik group..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500"
                                    autocomplete="off">
                                <div x-show="isOpen" @click.away="hideDropdown"
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-48 overflow-y-auto"
                                    style="display: none;">
                                    <template x-for="option in filteredOptions" :key="option">
                                        <div @click="selectOption(option)"
                                            class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white"
                                            x-text="option"></div>
                                    </template>
                                    <div x-show="filteredOptions.length === 0 && searchTerm"
                                        class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        Tidak ada group yang cocok
                                    </div>
                                    <div x-show="filteredOptions.length === 0 && !searchTerm && options.length === 0"
                                        class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        Memuat data...
                                    </div>
                                </div>
                            </div>
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
                            <label for="jalan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jalan</label>
                            <input type="text" name="jalan" id="jalan" x-model="formData.jalan"
                                @input="updateAddress()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="kota"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kota</label>
                            <input type="text" name="kota" id="kota" x-model="formData.kota"
                                @input="updateAddress()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="provinsi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" x-model="formData.provinsi"
                                @input="updateAddress()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="kode_pos"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                                Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos" x-model="formData.kode_pos"
                                @input="updateAddress()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="negara"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Negara</label>
                            <input type="text" name="negara" id="negara" x-model="formData.negara"
                                @input="updateAddress()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="npwp"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPWP</label>
                            <input type="text" name="npwp" id="npwp" x-model="formData.npwp"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="kontak_person"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                Kontak</label>
                            <input type="text" name="kontak_person" id="kontak_person"
                                x-model="formData.kontak_person"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="no_hp_kontak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. HP
                                Kontak</label>
                            <input type="text" name="no_hp_kontak" id="no_hp_kontak"
                                x-model="formData.no_hp_kontak"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="is_active"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="is_active" id="is_active" x-model.number="formData.is_active"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label for="sales_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sales</label>
                            <div x-data="customDropdown()" class="custom-dropdown w-full"
                                @sales-debug.window="debugAndFixDropdown($event.detail)" x-init="init()">
                                <div @click="toggleDropdown" @keydown.enter="toggleDropdown" tabindex="0"
                                    class="flex items-center justify-between cursor-pointer custom-dropdown-input bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 dark:text-white shadow-sm">
                                    <span x-text="selectedOption ? selectedOption.text : 'Pilih Sales'"
                                        :class="!selectedOption ? 'placeholder' : ''"></span>
                                    <div class="flex items-center">
                                        <button x-show="selectedOption" @click.stop="clearSelection" type="button"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div x-show="isOpen" @click.away="closeDropdown"
                                    class="custom-dropdown-menu bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                                    style="display: none;">
                                    <div class="p-2">
                                        <input type="text" x-model="searchTerm"
                                            @keydown.escape.prevent="closeDropdown"
                                            @keydown.arrow-down.prevent="navigateOptions('down')"
                                            @keydown.arrow-up.prevent="navigateOptions('up')"
                                            @keydown.enter.prevent="selectHighlightedOption"
                                            placeholder="Cari sales..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    </div>
                                    <div class="max-h-48 overflow-y-auto">
                                        <template x-for="(option, index) in filteredOptions" :key="option.id">
                                            <div @click="selectOption(option)" @mouseover="highlightedIndex = index"
                                                :class="{
                                                    'custom-dropdown-item': true,
                                                    'selected': selectedOption &&
                                                        selectedOption.id === option
                                                        .id,
                                                    'bg-gray-100 dark:bg-gray-700': highlightedIndex ===
                                                        index
                                                }"
                                                x-text="option.text"></div>
                                        </template>
                                        <div x-show="filteredOptions.length === 0"
                                            class="p-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Tidak ada data sales
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="sales_id" :value="selectedOption ? selectedOption.id : ''"
                                    x-model="selectedValue" id="sales_id_input">
                            </div>
                        </div>
                    </div>
                    <!-- Input alamat dihapus karena terisi otomatis -->
                    <div class="mb-4">
                        <label for="alamat_pengiriman"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat
                            Pengiriman</label>
                        <textarea name="alamat_pengiriman" id="alamat_pengiriman" x-model="formData.alamat_pengiriman" rows="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="catatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                        <textarea name="catatan" id="catatan" x-model="formData.catatan" rows="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"></textarea>
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
    function modalPelangganManager() {
        return {
            isOpen: false,
            isEdit: false,
            modalTitle: 'Tambah Pelanggan',
            loading: false,
            customerId: null,
            salesUsers: [],

            init() {
                // Listen for group selection events
                this.$el.addEventListener('group-selected', (event) => {
                    this.formData.group = event.detail.group;
                });
            },
            formData: {
                kode: '',
                nama: '',
                tipe: '',
                jalan: '',
                kota: '',
                provinsi: '',
                kode_pos: '',
                negara: '',
                company: '',
                group: '',
                industri: '',
                sales_name: '',
                alamat: '',
                alamat_pengiriman: '',
                telepon: '',
                email: '',
                npwp: '',
                kontak_person: '',
                jabatan_kontak: '',
                no_hp_kontak: '',
                catatan: '',
                is_active: 1,
                sales_id: '',
            },
            errors: {},

            // Metode untuk memperbarui alamat otomatis berdasarkan komponen-komponennya
            updateAddress() {
                const parts = [
                    this.formData.jalan,
                    this.formData.kota,
                    this.formData.provinsi,
                    this.formData.kode_pos,
                    this.formData.negara
                ].filter(part => part && part.trim() !== '');

                this.formData.alamat = parts.length > 0 ? parts.join(', ') : '';
            },

            async generateCustomerCode() {
                try {
                    const response = await fetch('/master-data/pelanggan/generate-code');
                    const data = await response.json();
                    if (data.success) {
                        this.formData.kode = data.code;
                    }
                } catch (error) {}
            },
            async fetchSalesUsers() {
                try {
                    const response = await fetch('/master-data/pelanggan/get-sales-users');
                    const data = await response.json();
                    if (data.success) {
                        // Normalize sales users data format
                        this.salesUsers = data.salesUsers.map(user => ({
                            id: String(user.id), // Ensure ID is string for consistent comparison
                            name: user.name || user.text || `User ${user.id}`
                        }));

                        // Save current sales_id value to preserve selection
                        const currentSalesId = this.formData.sales_id;

                        // Dispatch event for child components with delay to ensure processing order
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('sales-users-updated', {
                                detail: {
                                    users: this.salesUsers
                                }
                            }));
                        }, 50);
                    } else {}
                } catch (error) {}
            },
            openModal(data = {}) {
                // Reset form first to clear any previous data
                this.resetForm();

                // Determine if we're in edit mode
                if (data.mode === 'edit' && data.customer) {
                    this.isEdit = true;
                    this.modalTitle = 'Edit Pelanggan';
                    this.customerId = data.customer.id;
                    // First, ensure there's a deep copy of the customer data to prevent reference issues
                    const customerData = JSON.parse(JSON.stringify(data.customer));

                    // Store the initial sales_id from the customer data
                    const initialSalesId = customerData.sales_id ? String(customerData.sales_id) : '';

                    // Populate form data with customer data
                    Object.assign(this.formData, customerData);

                    // Ensure sales_id is properly set as string
                    this.formData.sales_id = initialSalesId;

                    // Create a global reference to help debugging
                    window._lastEditSalesId = initialSalesId;

                    // Now, set up the sales users if available or fetch them
                    if (data.salesUsers && Array.isArray(data.salesUsers)) {
                        this.salesUsers = data.salesUsers.map(user => ({
                            id: String(user.id),
                            name: user.name || user.text || `User ${user.id}`
                        }));
                    } else {
                        // Fetch sales users if not provided
                        this.fetchSalesUsers();
                    }

                    // Dispatch sales users event with a delay to ensure form data is set
                    if (this.salesUsers && this.salesUsers.length) {
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('sales-users-updated', {
                                detail: {
                                    users: this.salesUsers
                                }
                            }));
                        }, 200); // Longer delay to ensure dropdown is ready
                    }
                } else {
                    // New customer mode
                    this.isEdit = false;
                    this.modalTitle = 'Tambah Pelanggan';
                    this.generateCustomerCode();

                    // For new customer, fetch sales users list
                    this.fetchSalesUsers();
                }

                // Implement a staged initialization for better sequence control
                const initStages = () => {
                    // Stage 1: Open the modal (immediately)
                    this.isOpen = true;

                    // For edit mode, ensure the sales_id is set correctly immediately
                    if (this.isEdit && this.formData.sales_id) {
                        // Set a global variable to help with debugging
                        window._currentEditSalesId = String(this.formData.sales_id);
                    }

                    // Stage 2: Update the address (after 50ms)
                    setTimeout(() => {
                        this.updateAddress();

                        // Stage 3: Dispatch Alpine initialization (after 100ms more)
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('alpine:initialized'));

                            // Stage 4: Re-dispatch sales users (after 150ms more)
                            setTimeout(() => {
                                if (this.salesUsers && this.salesUsers.length) {
                                    // Make sure sales_id is still set correctly before dispatching
                                    if (this.isEdit && window._currentEditSalesId) {
                                        this.formData.sales_id = window._currentEditSalesId;
                                    }

                                    // Dispatch the event with the current sales users
                                    window.dispatchEvent(new CustomEvent(
                                        'sales-users-updated', {
                                            detail: {
                                                users: this.salesUsers,
                                                selectedId: this.isEdit ? this
                                                    .formData.sales_id : null
                                            }
                                        }));

                                    // For edit mode, dispatch a direct debug trigger after everything else
                                    if (this.isEdit) {
                                        setTimeout(() => {
                                            window.dispatchEvent(new CustomEvent(
                                                'sales-debug', {
                                                    detail: {
                                                        formData: this
                                                            .formData,
                                                        salesUsers: this
                                                            .salesUsers,
                                                        isEdit: true
                                                    }
                                                }));
                                        }, 300);

                                        // Additional final check with longer delay
                                        setTimeout(() => {
                                            if (this.formData.sales_id) {
                                                window.dispatchEvent(
                                                    new CustomEvent(
                                                        'sales-debug', {
                                                            detail: {
                                                                formData: this
                                                                    .formData,
                                                                salesUsers: this
                                                                    .salesUsers,
                                                                isEdit: true,
                                                                finalCheck: true
                                                            }
                                                        }));
                                            }
                                        }, 500);
                                    }
                                }
                            }, 150);
                        }, 100);
                    }, 50);
                };

                // Start the staged initialization
                initStages();
            },
            closeModal() {
                this.isOpen = false;
                setTimeout(() => this.resetForm(), 300);
            },
            resetForm() {
                this.formData = {
                    kode: '',
                    nama: '',
                    tipe: '',
                    jalan: '',
                    kota: '',
                    provinsi: '',
                    kode_pos: '',
                    negara: '',
                    company: '',
                    group: '',
                    industri: '',
                    sales_name: '',
                    alamat: '',
                    alamat_pengiriman: '',
                    telepon: '',
                    email: '',
                    npwp: '',
                    kontak_person: '',
                    jabatan_kontak: '',
                    no_hp_kontak: '',
                    catatan: '',
                    is_active: 1,
                    sales_id: '',
                };
                this.errors = {};
                this.loading = false;
            },
            submitForm() {
                // Pastikan alamat diupdate sebelum submit
                this.updateAddress();

                this.loading = true;
                this.errors = {};
                const form = document.getElementById('pelanggan-form');
                const formData = new FormData(form);
                let url, method;
                if (this.isEdit) {
                    url = `/master-data/pelanggan/${this.customerId}`;
                    method = 'POST';
                    formData.append('_method', 'PUT');
                } else {
                    url = '{{ route('master.pelanggan.store') }}';
                    method = 'POST';
                }

                // Ubah cara mengirim is_active
                formData.set('is_active', this.formData.is_active === '1' || this.formData.is_active === 1 ? '1' : '0');

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
                        window.dispatchEvent(new CustomEvent('refresh-customer-table', {
                            detail: {
                                action: this.isEdit ? 'update' : 'create',
                                customer: data.customer
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

    // Editable Combobox for Group field
    function editableCombobox() {
        return {
            isOpen: false,
            options: [],
            filteredOptions: [],
            searchTerm: '',
            apiUrl: '',

            init(url) {
                this.apiUrl = url;
                this.loadOptions();
            },

            async loadOptions() {
                try {
                    const response = await fetch(this.apiUrl);
                    const data = await response.json();
                    if (data.success) {
                        this.options = data.groups || [];
                        this.filteredOptions = this.options;
                    }
                } catch (error) {
                    console.error('Error loading options:', error);
                }
            },

            handleInput(event) {
                this.searchTerm = event.target.value;
                this.filterOptions();
                if (this.filteredOptions.length > 0 || this.searchTerm) {
                    this.showDropdown();
                }
            },

            filterOptions() {
                if (!this.searchTerm) {
                    this.filteredOptions = this.options;
                } else {
                    this.filteredOptions = this.options.filter(option =>
                        option.toLowerCase().includes(this.searchTerm.toLowerCase())
                    );
                }
            },

            selectOption(option) {
                // Dispatch custom event to update parent formData
                this.$dispatch('group-selected', {
                    group: option
                });
                this.hideDropdown();
            },

            showDropdown() {
                this.isOpen = true;
            },

            hideDropdown() {
                setTimeout(() => {
                    this.isOpen = false;
                }, 150);
            }
        };
    }
</script>

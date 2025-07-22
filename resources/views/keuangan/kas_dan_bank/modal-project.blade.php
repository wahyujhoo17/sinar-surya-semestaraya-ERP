{{-- Modal Project --}}
<div x-data="projectModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
    @open-project-modal.window="openModal($event.detail || {})" @keydown.escape.window="closeModal()">

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="isOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="closeModal()"></div>

        <!-- Modal content -->
        <div x-show="isOpen" x-transition:enter="transition-all ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition-all ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

            <form @submit.prevent="submitForm()" enctype="multipart/form-data">
                @csrf

                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full"
                        :class="mode === 'edit' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-green-100 dark:bg-green-900/30'">
                        <svg class="h-6 w-6"
                            :class="mode === 'edit' ? 'text-blue-600 dark:text-blue-400' :
                                'text-green-600 dark:text-green-400'"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0c-.027.113-.064.224-.109.332l-.457.916a1.125 1.125 0 01-1.007.622H9.478a1.125 1.125 0 01-1.007-.622l-.457-.916c-.045-.108-.082-.219-.109-.332m7.5 0a48.394 48.394 0 00-7.5 0" />
                        </svg>
                    </div>

                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" x-text="modalTitle">
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="modalDescription"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <!-- Nama Project -->
                    <div>
                        <label for="project_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Project <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="project_nama" x-model="form.nama" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm"
                            placeholder="Masukkan nama project">
                        <p x-show="errors.nama" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.nama"></p>
                    </div>

                    <!-- Kode Project (Auto Generate) -->
                    <div x-show="mode === 'create'">
                        <label for="project_kode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Project
                        </label>
                        <input type="text" id="project_kode" x-model="form.kode" readonly
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300 sm:text-sm"
                            placeholder="Auto generate">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kode akan dibuat otomatis</p>
                    </div>

                    <!-- Budget -->
                    <div>
                        <label for="project_budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Budget <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" id="project_budget" x-model="form.budget" required min="0"
                                step="1000"
                                class="block w-full pl-8 pr-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm"
                                placeholder="0">
                        </div>
                        <p x-show="errors.budget" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.budget"></p>
                    </div>



                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="project_tanggal_mulai"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Mulai
                        </label>
                        <input type="date" id="project_tanggal_mulai" x-model="form.tanggal_mulai"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                        <p x-show="errors.tanggal_mulai" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.tanggal_mulai"></p>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="project_tanggal_selesai"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Target Selesai
                        </label>
                        <input type="date" id="project_tanggal_selesai" x-model="form.tanggal_selesai"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                        <p x-show="errors.tanggal_selesai" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.tanggal_selesai"></p>
                    </div>

                    <!-- Status (hanya untuk edit) -->
                    <div x-show="mode === 'edit'">
                        <label for="project_status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>
                        <select id="project_status" x-model="form.status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditunda">Ditunda</option>
                        </select>
                        <p x-show="errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.status"></p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="project_deskripsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Deskripsi
                        </label>
                        <textarea id="project_deskripsi" x-model="form.deskripsi" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm"
                            placeholder="Deskripsi project (optional)"></textarea>
                        <p x-show="errors.deskripsi" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.deskripsi"></p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" :disabled="isLoading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:col-start-2 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="mode === 'edit' ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' :
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500'">
                        <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span
                            x-text="isLoading ? 'Processing...' : (mode === 'edit' ? 'Update Project' : 'Simpan Project')"></span>
                    </button>
                    <button type="button" @click="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function projectModal() {
            return {
                isOpen: false,
                isLoading: false,
                mode: 'create', // 'create' or 'edit'
                projectId: null,
                form: {
                    nama: '',
                    kode: '',
                    budget: '',
                    customer_id: '',
                    sales_order_id: '',
                    tanggal_mulai: '',
                    tanggal_selesai: '',
                    status: 'aktif',
                    deskripsi: ''
                },
                errors: {},

                get modalTitle() {
                    return this.mode === 'edit' ? 'Edit Project' : 'Tambah Project Baru';
                },

                get modalDescription() {
                    return this.mode === 'edit' ? 'Update informasi project' :
                        'Buat project baru untuk manajemen keuangan';
                },

                openModal(data = {}) {
                    // Ensure data is object and not null
                    data = data || {};

                    this.mode = data.mode || 'create';
                    this.resetForm();
                    this.resetErrors();

                    if (this.mode === 'edit' && data.project) {
                        this.projectId = data.project.id;
                        this.form = {
                            nama: data.project.nama || '',
                            kode: data.project.kode || '',
                            budget: data.project.budget || '',
                            customer_id: data.project.customer_id || '',
                            sales_order_id: data.project.sales_order_id || '',
                            tanggal_mulai: this.formatDateForInput(data.project.tanggal_mulai),
                            tanggal_selesai: this.formatDateForInput(data.project.tanggal_selesai),
                            status: data.project.status || 'aktif',
                            deskripsi: data.project.deskripsi || ''
                        };
                    }

                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                    this.resetForm();
                    this.resetErrors();
                },

                resetForm() {
                    this.form = {
                        nama: '',
                        kode: '',
                        budget: '',
                        customer_id: '',
                        sales_order_id: '',
                        tanggal_mulai: '',
                        tanggal_selesai: '',
                        status: 'aktif',
                        deskripsi: ''
                    };
                    this.projectId = null;
                },

                resetErrors() {
                    this.errors = {};
                },

                async submitForm() {
                    if (this.isLoading) return;

                    this.isLoading = true;
                    this.resetErrors();

                    try {
                        const url = this.mode === 'edit' ?
                            `/keuangan/projects/${this.projectId}` :
                            '/keuangan/projects';

                        const method = this.mode === 'edit' ? 'PUT' : 'POST';

                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));

                        if (this.mode === 'edit') {
                            formData.append('_method', 'PUT');
                        }

                        Object.keys(this.form).forEach(key => {
                            if (this.form[key] !== null && this.form[key] !== '') {
                                formData.append(key, this.form[key]);
                            }
                        });

                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Show success message
                            const message = result.message || 'Project berhasil disimpan';
                            this.showNotification(message, 'success');

                            // Close modal and refresh page
                            this.closeModal();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Handle validation errors
                            if (result.errors) {
                                this.errors = result.errors;
                            } else {
                                throw new Error(result.message || 'Terjadi kesalahan');
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showNotification(error.message || 'Terjadi kesalahan saat menyimpan project', 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                showNotification(message, type = 'info') {
                    console.log('showNotification called with:', message, type);

                    // Use the global toast function
                    if (typeof window.showToast === 'function') {
                        console.log('Using global showToast function');
                        window.showToast(message, type);
                    } else {
                        console.log('Global showToast not found, using fallback alert');
                        // Fallback to alert if toast is not available
                        if (type === 'error') {
                            alert('Error: ' + message);
                        } else {
                            alert(message);
                        }
                    }
                },

                /**
                 * Format date from database to HTML5 date input format (YYYY-MM-DD)
                 */
                formatDateForInput(dateValue) {
                    if (!dateValue) return '';

                    try {
                        // If already in YYYY-MM-DD format, return as is
                        if (typeof dateValue === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
                            return dateValue;
                        }

                        // Try parsing various date formats
                        let date;
                        if (typeof dateValue === 'string') {
                            // Handle different date formats from database
                            if (dateValue.includes('/')) {
                                // Handle DD/MM/YYYY or MM/DD/YYYY format
                                const parts = dateValue.split('/');
                                if (parts.length === 3) {
                                    // Assume DD/MM/YYYY format (Indonesian format)
                                    date = new Date(parts[2], parts[1] - 1, parts[0]);
                                }
                            } else if (dateValue.includes('-')) {
                                // Handle YYYY-MM-DD or DD-MM-YYYY format
                                date = new Date(dateValue);
                            } else {
                                date = new Date(dateValue);
                            }
                        } else {
                            date = new Date(dateValue);
                        }

                        // Check if date is valid
                        if (isNaN(date.getTime())) {
                            console.warn('Invalid date value:', dateValue);
                            return '';
                        }

                        // Format to YYYY-MM-DD
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');

                        return `${year}-${month}-${day}`;
                    } catch (error) {
                        console.error('Error formatting date:', error, dateValue);
                        return '';
                    }
                }
            };
        }
    </script>
@endpush

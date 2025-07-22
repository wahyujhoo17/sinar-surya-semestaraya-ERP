{{-- Modal Transaksi Project --}}
<div x-data="transaksiProjectModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
    @open-transaksi-project-modal.window="openModal($event.detail || {})" @keydown.escape.window="closeModal()">

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
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                    </div>

                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Transaksi Project</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola alokasi, penggunaan, dan
                                pengembalian dana project</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <!-- Project -->
                    <div>
                        <label for="transaksi_project_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Project <span class="text-red-500">*</span>
                        </label>
                        <select id="transaksi_project_id" x-model="form.project_id" required
                            @change="updateProjectInfo()"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Project</option>
                            @foreach (App\Models\Project::aktif()->get() as $project)
                                <option value="{{ $project->id }}" data-saldo="{{ $project->saldo }}"
                                    data-budget="{{ $project->budget }}">{{ $project->nama }} ({{ $project->kode }})
                                </option>
                            @endforeach
                        </select>
                        <p x-show="errors.project_id" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.project_id"></p>

                        <!-- Project Info -->
                        <div x-show="projectInfo.id" class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <div class="text-xs text-gray-600 dark:text-gray-300 space-y-1">
                                <div class="flex justify-between">
                                    <span>Budget:</span>
                                    <span x-text="formatCurrency(projectInfo.budget)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Saldo:</span>
                                    <span x-text="formatCurrency(projectInfo.saldo)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div>
                        <label for="transaksi_jenis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Transaksi <span class="text-red-500">*</span>
                        </label>
                        <select id="transaksi_jenis" x-model="form.jenis" required @change="updateJenisTransaksi()"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Jenis</option>
                            <option value="alokasi">Alokasi Dana (ke project)</option>
                            <option value="penggunaan">Penggunaan Dana (project keluar dana)</option>
                            <option value="pengembalian">Pengembalian Dana (project kembali ke kas/bank)</option>
                        </select>
                        <p x-show="errors.jenis" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.jenis"></p>
                    </div>

                    <!-- Sumber Dana (untuk alokasi dan pengembalian) -->
                    <div x-show="form.jenis === 'alokasi' || form.jenis === 'pengembalian'">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Sumber Dana <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-2 space-y-3">
                            <!-- Radio buttons untuk memilih kas atau bank -->
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" x-model="form.sumber_dana_type" value="kas"
                                        name="sumber_dana_type"
                                        class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Kas</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" x-model="form.sumber_dana_type" value="bank"
                                        name="sumber_dana_type"
                                        class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Bank</span>
                                </label>
                            </div>

                            <!-- Select Kas -->
                            <div x-show="form.sumber_dana_type === 'kas'">
                                <select x-model="form.kas_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                                    <option value="">Pilih Kas</option>
                                    @foreach (App\Models\Kas::where('is_aktif', true)->get() as $kas)
                                        <option value="{{ $kas->id }}">{{ $kas->nama }} (Saldo: Rp
                                            {{ number_format($kas->saldo, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Bank -->
                            <div x-show="form.sumber_dana_type === 'bank'">
                                <select x-model="form.rekening_bank_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                                    <option value="">Pilih Rekening Bank</option>
                                    @foreach (App\Models\RekeningBank::where('is_aktif', true)->where('is_perusahaan', true)->get() as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }} -
                                            {{ $bank->nomor_rekening }} (Saldo: Rp
                                            {{ number_format($bank->saldo, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p x-show="errors.sumber_dana_type" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.sumber_dana_type"></p>
                    </div>

                    <!-- Kategori Penggunaan (untuk penggunaan) -->
                    <div x-show="form.jenis === 'penggunaan'">
                        <label for="kategori_penggunaan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kategori Penggunaan <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori_penggunaan" x-model="form.kategori_penggunaan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Kategori</option>
                            <option value="material">Material</option>
                            <option value="tenaga_kerja">Tenaga Kerja</option>
                            <option value="operasional">Operasional</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <p x-show="errors.kategori_penggunaan" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.kategori_penggunaan"></p>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal_transaksi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_transaksi" x-model="form.tanggal" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm">
                        <p x-show="errors.tanggal" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.tanggal"></p>
                    </div>

                    <!-- Nominal -->
                    <div>
                        <label for="nominal_transaksi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nominal <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" id="nominal_transaksi" x-model="form.nominal" required
                                min="1000" step="1000"
                                class="block w-full pl-8 pr-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm"
                                placeholder="0">
                        </div>
                        <p x-show="errors.nominal" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.nominal"></p>

                        <!-- Validation alert for penggunaan -->
                        <div x-show="form.jenis === 'penggunaan' && form.nominal > projectInfo.saldo && projectInfo.saldo > 0"
                            class="mt-1 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <p class="text-xs text-yellow-800 dark:text-yellow-400">
                                ⚠️ Nominal melebihi saldo project (Rp <span
                                    x-text="formatNumber(projectInfo.saldo)"></span>)
                            </p>
                        </div>
                    </div>

                    <!-- No Bukti -->
                    <div>
                        <label for="no_bukti_transaksi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            No. Bukti
                        </label>
                        <input type="text" id="no_bukti_transaksi" x-model="form.no_bukti"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm"
                            placeholder="Nomor bukti (optional)">
                        <p x-show="errors.no_bukti" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.no_bukti"></p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan_transaksi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="keterangan_transaksi" x-model="form.keterangan" required rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 sm:text-sm"
                            placeholder="Deskripsi transaksi"></textarea>
                        <p x-show="errors.keterangan" class="mt-1 text-sm text-red-600 dark:text-red-400"
                            x-text="errors.keterangan"></p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" :disabled="isLoading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:col-start-2 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="isLoading ? 'Processing...' : 'Simpan Transaksi'"></span>
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
        function transaksiProjectModal() {
            return {
                isOpen: false,
                isLoading: false,
                form: {
                    project_id: '',
                    jenis: '',
                    tanggal: new Date().toISOString().split('T')[0],
                    nominal: '', // Changed from 'jumlah'
                    keterangan: '',
                    no_bukti: '',
                    sumber_dana_type: '',
                    sumber_kas_id: '',
                    sumber_bank_id: '',
                    kategori_penggunaan: ''
                },
                errors: {},
                projectInfo: {
                    id: null,
                    saldo: 0,
                    budget: 0
                },

                openModal(data = {}) {
                    this.resetForm();
                    this.resetErrors();

                    // Ensure data is object and not null
                    data = data || {};

                    // Pre-fill form if data provided
                    if (data.project_id) {
                        this.form.project_id = data.project_id;
                        this.updateProjectInfo();
                    }
                    if (data.kas_id) {
                        this.form.kas_id = data.kas_id;
                        this.form.sumber_dana_type = 'kas';
                    }
                    if (data.rekening_bank_id) {
                        this.form.rekening_bank_id = data.rekening_bank_id;
                        this.form.sumber_dana_type = 'bank';
                    }
                    if (data.sumber_dana_type) {
                        this.form.sumber_dana_type = data.sumber_dana_type;
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
                        project_id: '',
                        jenis: '',
                        tanggal: new Date().toISOString().split('T')[0],
                        nominal: '',
                        keterangan: '',
                        no_bukti: '',
                        sumber_dana_type: '',
                        kas_id: '',
                        rekening_bank_id: '',
                        kategori_penggunaan: ''
                    };
                    this.projectInfo = {
                        id: null,
                        saldo: 0,
                        budget: 0
                    };
                },

                resetErrors() {
                    this.errors = {};
                },

                updateProjectInfo() {
                    const select = document.getElementById('transaksi_project_id');
                    const selectedOption = select.options[select.selectedIndex];

                    if (selectedOption && selectedOption.value) {
                        this.projectInfo = {
                            id: selectedOption.value,
                            saldo: parseFloat(selectedOption.dataset.saldo || 0),
                            budget: parseFloat(selectedOption.dataset.budget || 0)
                        };
                    } else {
                        this.projectInfo = {
                            id: null,
                            saldo: 0,
                            budget: 0
                        };
                    }
                },

                updateJenisTransaksi() {
                    // Reset sumber dana when changing jenis
                    if (this.form.jenis === 'penggunaan') {
                        this.form.sumber_dana_type = '';
                        this.form.kas_id = '';
                        this.form.rekening_bank_id = '';
                    } else {
                        this.form.kategori_penggunaan = '';
                    }
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value || 0);
                },

                formatNumber(value) {
                    return new Intl.NumberFormat('id-ID').format(value || 0);
                },

                async submitForm() {
                    if (this.isLoading) return;

                    this.isLoading = true;
                    this.resetErrors();

                    try {
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));

                        Object.keys(this.form).forEach(key => {
                            if (this.form[key] !== null && this.form[key] !== '') {
                                formData.append(key, this.form[key]);
                            }
                        });

                        const response = await fetch('/keuangan/transaksi-projects', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Show success message
                            const message = result.message || 'Transaksi project berhasil disimpan';
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
                        this.showNotification(error.message || 'Terjadi kesalahan saat menyimpan transaksi', 'error');
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
                }
            };
        }
    </script>
@endpush

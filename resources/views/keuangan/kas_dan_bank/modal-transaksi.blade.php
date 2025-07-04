{{-- Define modalTransaksiManager function globally before Alpine initialization --}}
<script>
    // console.log('modalTransaksiManager script loading...'); // Debug log
    window.modalTransaksiManager = function() {
        // console.log('modalTransaksiManager function called'); // Debug log
        return {
            isOpen: false,
            loading: false,
            modalTitle: 'Tambah Transaksi',
            modalSubtitle: 'Tambah transaksi kas atau bank',
            showJournalPreview: false,
            alert: {
                show: false,
                type: 'success',
                message: ''
            },

            formData: {
                tanggal: new Date().toISOString().substr(0, 10),
                jenis: '',
                account_type: '',
                account_id: '',
                contra_account_id: '',
                jumlah: 0,
                jumlah_display: '',
                no_referensi: '',
                keterangan: ''
            },

            errors: {},
            availableAccounts: [],
            contraAccounts: [],
            selectedAccount: null,
            journalPreview: [],

            init() {
                // console.log('Modal component initialized');
                // Listen for global modal open events
                window.addEventListener('open-transaksi-modal', (event) => {
                    // console.log('Received open-transaksi-modal event:', event.detail);
                    this.openModal(event.detail || {});
                });
            },

            openModal(data = {}) {
                // console.log('openModal called with data:', data);
                this.resetForm();

                // Set account data if provided
                if (data.account_type) {
                    this.formData.account_type = data.account_type;
                    this.handleAccountTypeChange();
                }

                if (data.account_id) {
                    this.formData.account_id = data.account_id;
                    this.handleAccountChange();
                }

                this.modalTitle = `Tambah Transaksi ${data.account_type === 'kas' ? 'Kas' : 'Bank'}`;
                this.modalSubtitle = `Tambah transaksi ${data.account_type === 'kas' ? 'kas' : 'rekening bank'}`;

                this.isOpen = true;
                this.hideAlert();

                // Load contra accounts when modal opens
                this.loadContraAccounts();
            },

            closeModal() {
                this.isOpen = false;
                this.resetForm();
            },

            resetForm() {
                this.formData = {
                    tanggal: new Date().toISOString().substr(0, 10),
                    jenis: '',
                    account_type: '',
                    account_id: '',
                    contra_account_id: '',
                    jumlah: 0,
                    jumlah_display: '',
                    no_referensi: '',
                    keterangan: ''
                };
                this.errors = {};
                this.availableAccounts = [];
                this.contraAccounts = [];
                this.selectedAccount = null;
                this.journalPreview = [];
                this.showJournalPreview = false;
                this.hideAlert();
            },

            async handleJenisChange() {
                this.formData.contra_account_id = '';
                this.updateJournalPreview();
                await this.loadContraAccounts();
            },

            async handleAccountTypeChange() {
                this.formData.account_id = '';
                this.selectedAccount = null;
                await this.loadAvailableAccounts();
                this.updateJournalPreview();
            },

            async handleAccountChange() {
                if (this.formData.account_id) {
                    const account = this.availableAccounts.find(acc => acc.id == this.formData.account_id);
                    this.selectedAccount = account;
                    this.updateJournalPreview();
                }
            },

            async loadAvailableAccounts() {
                if (!this.formData.account_type) {
                    // console.log('No account type selected');
                    return;
                }

                // console.log('Loading accounts for type:', this.formData.account_type);

                try {
                    const endpoint = this.formData.account_type === 'kas' ? '/api/kas/active' :
                        '/api/rekening-bank/active';

                    // console.log('Fetching from endpoint:', endpoint);
                    const response = await fetch(endpoint);
                    const data = await response.json();

                    // console.log('API Response:', data);

                    if (data.success) {
                        this.availableAccounts = data.data;
                        // console.log('Available accounts loaded:', this.availableAccounts);
                    } else {
                        console.error('API Error:', data.message);
                        this.showAlert('error', data.message || 'Gagal memuat data akun');
                    }
                } catch (error) {
                    console.error('Error loading accounts:', error);
                    this.showAlert('error', 'Gagal memuat data akun');
                }
            },

            async loadContraAccounts() {
                // console.log('Loading contra accounts...');
                try {
                    const response = await fetch('/api/accounts/chart-of-accounts');
                    const data = await response.json();

                    // console.log('Contra accounts API response:', data);

                    if (data.success) {
                        this.contraAccounts = data.data;
                        // console.log('Contra accounts loaded:', this.contraAccounts);
                    } else {
                        console.error('API Error:', data.message);
                        this.showAlert('error', data.message || 'Gagal memuat chart of accounts');
                    }
                } catch (error) {
                    console.error('Error loading contra accounts:', error);
                    this.showAlert('error', 'Gagal memuat chart of accounts');
                }
            },

            get filteredContraAccounts() {
                // console.log('filteredContraAccounts called');
                // console.log('formData.jenis:', this.formData.jenis);
                // console.log('contraAccounts:', this.contraAccounts);

                if (!this.formData.jenis) {
                    // console.log('No jenis selected, returning all contra accounts');
                    return this.contraAccounts;
                }

                const filtered = this.contraAccounts.filter(account => {
                    const accountKategori = account.kategori?.toLowerCase();
                    const accountNama = account.nama?.toLowerCase();

                    // console.log(`Checking account: ${account.nama} (kategori: ${account.kategori})`);

                    if (this.formData.jenis === 'masuk') {
                        // For income: show income, liability, equity accounts
                        const isValid = ['income', 'liability', 'equity'].includes(accountKategori) || [
                            'pendapatan', 'penjualan', 'hutang', 'modal', 'kewajiban'
                        ].some(keyword =>
                            accountNama?.includes(keyword)
                        );
                        // console.log(`Income check for ${account.nama}: ${isValid}`);
                        return isValid;
                    } else if (this.formData.jenis === 'keluar') {
                        // For expense: show expense, asset accounts (excluding kas/bank)
                        const isValid = ['expense', 'asset'].includes(accountKategori) || ['beban',
                            'biaya', 'aset'
                        ].some(keyword =>
                            accountNama?.includes(keyword)
                        );
                        // console.log(`Expense check for ${account.nama}: ${isValid}`);
                        return isValid;
                    }

                    return true;
                });

                // console.log('Filtered contra accounts:', filtered);
                return filtered;
            },

            updateJournalPreview() {
                if (!this.formData.jenis || !this.formData.account_type || !this.formData.jumlah || !this
                    .selectedAccount) {
                    this.showJournalPreview = false;
                    return;
                }

                this.journalPreview = [];
                const amount = this.formData.jumlah;

                if (this.formData.jenis === 'masuk') {
                    // Income: Debit Kas/Bank, Credit Contra Account
                    const kasAccount = this.selectedAccount;
                    const accountName = this.formData.account_type === 'kas' ?
                        kasAccount.nama :
                        `${kasAccount.nama_bank} - ${kasAccount.nomor_rekening} (${kasAccount.atas_nama})`;

                    this.journalPreview.push({
                        account: accountName,
                        debit: amount,
                        kredit: 0
                    });

                    const contraAccount = this.contraAccounts.find(acc => acc.id == this.formData
                        .contra_account_id);
                    if (contraAccount) {
                        this.journalPreview.push({
                            account: `${contraAccount.kode} - ${contraAccount.nama}`,
                            debit: 0,
                            kredit: amount
                        });
                    }
                } else if (this.formData.jenis === 'keluar') {
                    // Expense: Debit Contra Account, Credit Kas/Bank
                    const contraAccount = this.contraAccounts.find(acc => acc.id == this.formData
                        .contra_account_id);
                    if (contraAccount) {
                        this.journalPreview.push({
                            account: `${contraAccount.kode} - ${contraAccount.nama}`,
                            debit: amount,
                            kredit: 0
                        });
                    }

                    const kasAccount = this.selectedAccount;
                    const accountName = this.formData.account_type === 'kas' ?
                        kasAccount.nama :
                        `${kasAccount.nama_bank} - ${kasAccount.nomor_rekening} (${kasAccount.atas_nama})`;

                    this.journalPreview.push({
                        account: accountName,
                        debit: 0,
                        kredit: amount
                    });
                }

                this.showJournalPreview = true;
            },

            formatCurrency(event) {
                let value = event.target.value.replace(/[^\d]/g, '');
                let numericValue = parseInt(value) || 0;

                this.formData.jumlah = numericValue;
                this.formData.jumlah_display = this.formatNumber(numericValue);

                this.updateJournalPreview();
            },

            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            },

            validateAmount() {
                if (this.formData.jenis === 'keluar' && this.selectedAccount) {
                    if (this.formData.jumlah > this.selectedAccount.saldo) {
                        this.showAlert('warning', 'Jumlah melebihi saldo yang tersedia');
                        return false;
                    }
                }
                return true;
            },

            isFormValid() {
                return this.formData.tanggal &&
                    this.formData.jenis &&
                    this.formData.account_type &&
                    this.formData.account_id &&
                    this.formData.contra_account_id &&
                    this.formData.jumlah > 0 &&
                    this.formData.keterangan &&
                    this.validateAmount();
            },

            async submitForm() {
                if (!this.isFormValid()) {
                    this.showAlert('error', 'Mohon lengkapi semua field yang diperlukan');
                    return;
                }

                this.loading = true;
                this.hideAlert();

                try {
                    const response = await fetch('/keuangan/kas-dan-bank/transaksi/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        this.showAlert('success', result.message || 'Transaksi berhasil disimpan');

                        // Show success toast notification
                        this.showSuccessToast(result.message || 'Transaksi berhasil disimpan', result.data);

                        // Close modal and refresh the page after showing success
                        setTimeout(() => {
                            this.closeModal();
                            window.location.reload();
                        }, 2500);
                    } else {
                        this.showAlert('error', result.message || 'Terjadi kesalahan saat menyimpan transaksi');
                        this.errors = result.errors || {};
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    this.showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                } finally {
                    this.loading = false;
                }
            },

            showAlert(type, message) {
                this.alert = {
                    show: true,
                    type: type,
                    message: message
                };
            },

            hideAlert() {
                this.alert.show = false;
            },

            showSuccessToast(message, data = null) {
                // Create success toast element
                const toast = document.createElement('div');
                toast.className =
                    'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 ease-in-out translate-x-full';

                let toastContent = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <div class="font-medium">${message}</div>`;

                if (data) {
                    toastContent += `<div class="text-sm text-green-100 mt-1">
                        No. Referensi: ${data.no_referensi || '-'}<br>
                        Saldo Baru: Rp ${this.formatNumber(data.saldo_baru || 0)}
                    </div>`;
                }

                toastContent += `
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 ml-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;

                toast.innerHTML = toastContent;
                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }, 5000);
            },

            closeModal() {
                this.isOpen = false;
                this.resetForm();
            }
        };
    }
</script>

{{-- Modal Transaksi Kas dan Bank --}}
<div x-data="window.modalTransaksiManager()" x-init="$nextTick(() => {
    window.addEventListener('open-transaksi-modal', event => {
        openModal(event.detail || {});
    });
});" x-show="isOpen" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-50 overflow-y-auto">

    {{-- Background Overlay --}}
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="isOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>

        {{-- Modal Content --}}
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
            x-show="isOpen" x-transition:enter="transition ease-out duration-300 delay-150"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4">

            {{-- Header --}}
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-primary-500 to-primary-600">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.897-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white" x-text="modalTitle"></h3>
                        <p class="text-primary-100 text-sm" x-text="modalSubtitle"></p>
                    </div>
                </div>
                <button @click="closeModal()" type="button"
                    class="text-primary-200 hover:text-white focus:outline-none focus:text-white transition-colors duration-200">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="px-6 py-6">
                <form @submit.prevent="submitForm()">
                    {{-- Alert Component --}}
                    <div x-show="alert.show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-2" class="mb-4 rounded-md p-4 border"
                        :class="{
                            'bg-red-50 border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400': alert
                                .type === 'error',
                            'bg-green-50 border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400': alert
                                .type === 'success',
                            'bg-yellow-50 border-yellow-200 text-yellow-700 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-400': alert
                                .type === 'warning'
                        }">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg x-show="alert.type === 'error'" class="h-5 w-5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg x-show="alert.type === 'success'" class="h-5 w-5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg x-show="alert.type === 'warning'" class="h-5 w-5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium" x-text="alert.message"></p>
                            </div>
                            <div class="ml-auto pl-3">
                                <button @click="alert.show = false"
                                    class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    :class="{
                                        'text-red-400 hover:bg-red-100 focus:ring-red-600 dark:hover:bg-red-900/30': alert
                                            .type === 'error',
                                        'text-green-400 hover:bg-green-100 focus:ring-green-600 dark:hover:bg-green-900/30': alert
                                            .type === 'success',
                                        'text-yellow-400 hover:bg-yellow-100 focus:ring-yellow-600 dark:hover:bg-yellow-900/30': alert
                                            .type === 'warning'
                                    }">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        {{-- Row 1: Basic Information --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" x-model="formData.tanggal"
                                    required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.tanggal"
                                    x-text="errors.tanggal"></p>
                            </div>

                            {{-- Jenis Transaksi --}}
                            <div>
                                <label for="jenis"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Transaksi <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis" id="jenis" x-model="formData.jenis" required
                                    @change="handleJenisChange()"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    <option value="">Pilih Jenis Transaksi</option>
                                    <option value="masuk">Pemasukan / Penerimaan</option>
                                    <option value="keluar">Pengeluaran / Pembayaran</option>
                                </select>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.jenis"
                                    x-text="errors.jenis"></p>
                            </div>
                        </div>

                        {{-- Row 2: Account Selection --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Sumber/Tujuan Dana --}}
                            <div>
                                <label for="account_type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span x-text="formData.jenis === 'masuk' ? 'Tujuan Dana' : 'Sumber Dana'"></span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="account_type" id="account_type" x-model="formData.account_type"
                                    required @change="handleAccountTypeChange()"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    <option value="">Pilih Tipe Akun</option>
                                    <option value="kas">Kas</option>
                                    <option value="bank">Rekening Bank</option>
                                </select>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.account_type"
                                    x-text="errors.account_type"></p>
                            </div>

                            {{-- Pilih Kas/Rekening --}}
                            <div x-show="formData.account_type">
                                <label for="account_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span
                                        x-text="formData.account_type === 'kas' ? 'Pilih Kas' : 'Pilih Rekening Bank'"></span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="account_id" id="account_id" x-model="formData.account_id" required
                                    @change="handleAccountChange()"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    <option value="">
                                        <span
                                            x-text="formData.account_type === 'kas' ? 'Pilih Kas' : 'Pilih Rekening Bank'"></span>
                                    </option>
                                    <template x-for="account in availableAccounts" :key="account.id">
                                        <option :value="account.id" x-text="account.display_name"></option>
                                    </template>
                                </select>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.account_id"
                                    x-text="errors.account_id"></p>

                                {{-- Saldo Info --}}
                                <div x-show="selectedAccount"
                                    class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-md border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center text-sm text-blue-700 dark:text-blue-300">
                                        <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>Saldo Tersedia: Rp <span
                                                x-text="selectedAccount ? formatNumber(selectedAccount.saldo) : '0'"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Row 3: Lawan Transaksi --}}
                        <div>
                            <label for="contra_account_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Akun Lawan Transaksi <span class="text-red-500">*</span>
                            </label>
                            <select name="contra_account_id" id="contra_account_id"
                                x-model="formData.contra_account_id" required @change="updateJournalPreview()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="">Pilih Akun</option>
                                <template x-for="account in filteredContraAccounts" :key="account.id">
                                    <option :value="account.id" x-text="account.kode + ' - ' + account.nama">
                                    </option>
                                </template>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span x-show="formData.jenis === 'masuk'">Pilih akun sumber penerimaan (misal:
                                    Pendapatan Penjualan, Piutang Usaha, dll)</span>
                                <span x-show="formData.jenis === 'keluar'">Pilih akun tujuan pengeluaran (misal: Beban
                                    Operasional, Hutang Usaha, dll)</span>
                            </p>
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.contra_account_id"
                                x-text="errors.contra_account_id"></p>
                        </div>

                        {{-- Row 4: Amount and Reference --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Jumlah --}}
                            <div>
                                <label for="jumlah_display"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jumlah <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="text" name="jumlah_display" id="jumlah_display"
                                        x-model="formData.jumlah_display" @input="formatCurrency($event)"
                                        @blur="validateAmount()" required placeholder="0"
                                        class="w-full pl-12 pr-4 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    <input type="hidden" name="jumlah" x-model="formData.jumlah">
                                </div>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.jumlah"
                                    x-text="errors.jumlah"></p>
                            </div>

                            {{-- No Referensi --}}
                            <div>
                                <label for="no_referensi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    No. Referensi
                                </label>
                                <input type="text" name="no_referensi" id="no_referensi"
                                    x-model="formData.no_referensi" placeholder="Nomor referensi transaksi (opsional)"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor bukti atau referensi
                                    transaksi</p>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.no_referensi"
                                    x-text="errors.no_referensi"></p>
                            </div>
                        </div>

                        {{-- Row 5: Description --}}
                        <div>
                            <label for="keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="keterangan" id="keterangan" x-model="formData.keterangan" rows="3" required
                                placeholder="Jelaskan detail transaksi ini..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"></textarea>
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="errors.keterangan"
                                x-text="errors.keterangan"></p>
                        </div>

                        {{-- Journal Preview --}}
                        <div x-show="showJournalPreview"
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="h-4 w-4 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45-1.5a1.5 1.5 0 103 0 1.5 1.5 0 00-3 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pratinjau Jurnal Otomatis
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead>
                                        <tr class="bg-gray-100 dark:bg-gray-800">
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Akun</th>
                                            <th
                                                class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Debit</th>
                                            <th
                                                class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        <template x-for="entry in journalPreview" :key="entry.account">
                                            <tr>
                                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-white"
                                                    x-text="entry.account"></td>
                                                <td class="px-3 py-2 text-sm text-right text-gray-900 dark:text-white"
                                                    x-text="entry.debit ? formatNumber(entry.debit) : '-'"></td>
                                                <td class="px-3 py-2 text-sm text-right text-gray-900 dark:text-white"
                                                    x-text="entry.kredit ? formatNumber(entry.kredit) : '-'"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end mt-8 space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit" :disabled="loading || !isFormValid()"
                            class="px-6 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:-translate-y-0.5 disabled:hover:transform-none"
                            :class="{ 'opacity-50 cursor-not-allowed': loading || !isFormValid() }">
                            <span x-show="loading" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                            <span x-show="!loading" class="inline-flex items-center">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Simpan Transaksi
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

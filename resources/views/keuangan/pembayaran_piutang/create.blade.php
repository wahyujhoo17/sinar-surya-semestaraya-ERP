<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Piutang Usaha', 'url' => route('keuangan.piutang-usaha.index')],
    ['name' => $invoice ? 'Proses Pembayaran' : 'Buat Pembayaran Multi-Invoice', 'url' => '#'],
]" :currentPage="$invoice ? 'Proses Pembayaran Piutang' : 'Buat Pembayaran Piutang'">

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8"
        x-data="{
            customerId: '{{ old('customer_id', $customer->id ?? '') }}',
            metode: '{{ old('metode_pembayaran', 'Bank Transfer') }}',
            totalBayar: {{ old('jumlah_pembayaran', $sisaPiutang ?? 0) }},
            availableInvoices: [],
            selectedInvoiceIdToAppend: '',
            selectedInvoices: [],
            allocations: {},
            catatanAllocations: {},
            isLoadingInvoices: false,
            initialInvoiceId: '{{ $invoice->id ?? '' }}',
            initialSisaPiutang: {{ $sisaPiutang ?? 0 }},
            isSubmitting: false,
            errorMessage: '',

            init() {
                this.$nextTick(() => {
                    const self = this;

                    // Init Customer Select2
                    const $custSelect = $('#customer_select');
                    if ($custSelect.length) {
                        $custSelect.select2({
                            placeholder: '-- Pilih / Cari Customer --',
                            allowClear: true,
                            width: '100%'
                        }).on('change', function() {
                            self.customerId = $(this).val();
                            self.loadInvoices(self.customerId);
                        });
                    }

                    // Init Invoice Select2
                    this.initInvoiceSelect2();

                    if (this.customerId) {
                        this.loadInvoices(this.customerId);
                    }
                });
            },

            initInvoiceSelect2() {
                const self = this;
                const $invSelect = $('#invoice_select2');
                if ($invSelect.length) {
                    $invSelect.select2({
                        placeholder: '-- Cari & Pilih Invoice yang Ingin Dibayar --',
                        allowClear: true,
                        width: '100%'
                    }).off('change.app').on('change.app', function() {
                        self.selectedInvoiceIdToAppend = $(this).val();
                    });
                }
            },

            loadInvoices(customerId) {
                if (!customerId) {
                    this.availableInvoices = [];
                    this.selectedInvoices = [];
                    this.allocations = {};
                    this.catatanAllocations = {};
                    this.selectedInvoiceIdToAppend = '';
                    this.renderInvoiceSelect2Options();
                    return;
                }

                this.isLoadingInvoices = true;
                fetch(`/keuangan/customers/${customerId}/invoices`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.availableInvoices = data;
                    this.selectedInvoices = [];
                    this.allocations = {};
                    this.catatanAllocations = {};
                    this.selectedInvoiceIdToAppend = '';

                    // If coming from a specific invoice
                    if (this.initialInvoiceId) {
                        const targetId = parseInt(this.initialInvoiceId);
                        const exists = this.availableInvoices.find(inv => inv.id === targetId);
                        if (exists) {
                            this.addInvoice(exists);
                            this.allocations[targetId] = exists.sisa_piutang;
                            this.totalBayar = exists.sisa_piutang;
                        }
                    }

                    this.$nextTick(() => {
                        this.renderInvoiceSelect2Options();
                    });
                })
                .catch(err => {
                    console.error('Error loading invoices:', err);
                })
                .finally(() => {
                    this.isLoadingInvoices = false;
                });
            },

            renderInvoiceSelect2Options() {
                const self = this;
                const $select = $('#invoice_select2');
                if (!$select.length) return;

                $select.empty();
                $select.append(new Option('-- Cari & Pilih Invoice yang Ingin Dibayar --', ''));

                this.availableInvoices.forEach(inv => {
                    const isAdded = self.selectedInvoices.some(i => i.id === inv.id);
                    const text = `${inv.nomor_invoice} (${inv.tanggal_invoice}) - Sisa Piutang: Rp ${self.formatRupiah(inv.sisa_piutang)}${isAdded ? ' [Sudah Masuk List]' : ''}`;
                    const option = new Option(text, inv.id, false, false);
                    if (isAdded) {
                        $(option).attr('disabled', 'disabled');
                    }
                    $select.append(option);
                });

                $select.val('').trigger('change.select2');
            },

            addSelectedFromDropdown() {
                if (!this.selectedInvoiceIdToAppend) return;
                const targetId = parseInt(this.selectedInvoiceIdToAppend);
                const inv = this.availableInvoices.find(i => i.id === targetId);
                if (inv) {
                    this.addInvoice(inv);
                    this.selectedInvoiceIdToAppend = '';
                    this.renderInvoiceSelect2Options();
                }
            },

            addInvoice(inv) {
                if (this.selectedInvoices.some(i => i.id === inv.id)) return;
                this.selectedInvoices.push(inv);
                
                // Calculate suggested allocation
                const currentAllocated = this.getTotalAllocated();
                const unallocated = Math.max(0, parseFloat(this.totalBayar || 0) - currentAllocated);
                
                if (unallocated > 0) {
                    this.allocations[inv.id] = Math.min(unallocated, parseFloat(inv.sisa_piutang));
                } else if (parseFloat(this.totalBayar || 0) === 0) {
                    this.allocations[inv.id] = parseFloat(inv.sisa_piutang);
                    this.recalculateTotalBayarFromAllocations();
                } else {
                    this.allocations[inv.id] = 0;
                }
            },

            removeInvoice(invId) {
                this.selectedInvoices = this.selectedInvoices.filter(i => i.id !== invId);
                delete this.allocations[invId];
                delete this.catatanAllocations[invId];
                this.renderInvoiceSelect2Options();
            },

            removeAllInvoices() {
                this.selectedInvoices = [];
                this.allocations = {};
                this.catatanAllocations = {};
                this.renderInvoiceSelect2Options();
            },

            payInFull(inv) {
                this.allocations[inv.id] = parseFloat(inv.sisa_piutang);
            },

            formatRupiahInput(angka) {
                if (angka === null || angka === undefined || angka === '') return '';
                const num = parseFloat(angka);
                if (isNaN(num)) return '';
                if (num === 0) return '';
                
                const parts = num.toString().split('.');
                const intFormatted = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                if (parts.length > 1 && parts[1] && parts[1] !== '0' && parts[1] !== '00') {
                    return intFormatted + ',' + parts[1];
                }
                return intFormatted;
            },

            parseRupiahInput(str) {
                if (!str && str !== 0) return 0;
                let cleaned = str.toString().trim();
                if (!cleaned) return 0;

                const lastCommaIndex = cleaned.lastIndexOf(',');
                if (lastCommaIndex !== -1) {
                    cleaned = cleaned.substring(0, lastCommaIndex).replace(/\./g, '').replace(/[^0-9]/g, '') +
                        '.' + cleaned.substring(lastCommaIndex + 1).replace(/[^0-9]/g, '');
                } else {
                    cleaned = cleaned.replace(/\./g, '').replace(/[^0-9]/g, '');
                }

                const num = parseFloat(cleaned);
                return isNaN(num) ? 0 : num;
            },

            handleTotalBayarInput(event) {
                const rawStr = event.target.value;
                if (rawStr === '') {
                    this.totalBayar = 0;
                    return;
                }
                const rawVal = this.parseRupiahInput(rawStr);
                this.totalBayar = rawVal;
                event.target.value = this.formatRupiahInput(rawVal);
            },

            onAllocationInput(inv, event) {
                const rawStr = event.target.value;
                if (rawStr === '') {
                    this.allocations[inv.id] = 0;
                    return;
                }
                let rawVal = this.parseRupiahInput(rawStr);
                const maxLimit = parseFloat(inv.sisa_piutang || 0);
                if (rawVal > maxLimit) {
                    rawVal = maxLimit;
                } else if (rawVal < 0) {
                    rawVal = 0;
                }
                this.allocations[inv.id] = rawVal;
                event.target.value = this.formatRupiahInput(rawVal);
            },

            recalculateTotalBayarFromAllocations() {
                let sum = 0;
                for (let id in this.allocations) {
                    sum += parseFloat(this.allocations[id] || 0);
                }
                this.totalBayar = sum;
            },

            syncTotalBayarWithAllocations() {
                this.totalBayar = this.getTotalAllocated();
            },

            getTotalAllocated() {
                let sum = 0;
                for (let id in this.allocations) {
                    sum += parseFloat(this.allocations[id] || 0);
                }
                return sum;
            },

            getUnallocatedDifference() {
                return (parseFloat(this.totalBayar || 0) - this.getTotalAllocated()).toFixed(2);
            },

            validateAndSubmit(e) {
                if (this.isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                if (parseFloat(this.totalBayar || 0) <= 0) {
                    this.errorMessage = 'Jumlah pembayaran harus lebih dari 0.';
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return false;
                }

                if (this.selectedInvoices.length === 0 && this.availableInvoices.length > 0) {
                    this.errorMessage = 'Pilih dan tambahkan minimal satu invoice yang akan dibayar menggunakan dropdown di bawah.';
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return false;
                }

                const totalAlloc = this.getTotalAllocated();
                if (totalAlloc <= 0 && this.selectedInvoices.length > 0) {
                    this.errorMessage = 'Tentukan nominal alokasi pembayaran untuk invoice yang dipilih.';
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return false;
                }

                if (Math.abs(totalAlloc - parseFloat(this.totalBayar || 0)) > 0.05 && this.selectedInvoices.length > 0) {
                    this.errorMessage = 'Total alokasi invoice (Rp ' + this.formatRupiah(totalAlloc) + ') belum seimbang dengan Jumlah Pembayaran (Rp ' + this.formatRupiah(this.totalBayar) + '). Klik tombol Samakan dengan Total Alokasi.';
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return false;
                }

                this.errorMessage = '';
                this.isSubmitting = true;
                return true;
            },

            formatRupiah(val) {
                return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(val || 0);
            }
        }">

        @push('styles')
            <!-- Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <style>
                .form-card { transition: all 0.3s ease; }
                [x-cloak] { display: none !important; }

                /* Custom Select2 styling */
                .select2-container {
                    width: 100% !important;
                }
                .select2-container--default .select2-selection--single {
                    height: 42px;
                    padding: 6px 12px;
                    border-color: #D1D5DB;
                    border-radius: 0.5rem;
                    display: flex;
                    align-items: center;
                    font-size: 0.875rem;
                }
                .select2-container--default .select2-selection--single:focus,
                .select2-container--default.select2-container--focus .select2-selection--single {
                    border-color: #10B981;
                    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
                }
                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 40px;
                    right: 8px;
                }
                .select2-dropdown {
                    border-color: #D1D5DB;
                    border-radius: 0.5rem;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    font-size: 0.875rem;
                    z-index: 9999;
                }
                .select2-container--default .select2-results__option--highlighted[aria-selected] {
                    background-color: #10B981;
                }
                .select2-container--default .select2-search--dropdown .select2-search__field {
                    border-color: #D1D5DB;
                    border-radius: 0.375rem;
                    padding: 0.4rem 0.75rem;
                    font-size: 0.875rem;
                }
                .select2-container--default .select2-search--dropdown .select2-search__field:focus {
                    border-color: #10B981;
                    outline: none;
                    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
                }

                /* Dark mode Select2 */
                .dark .select2-container--default .select2-selection--single {
                    background-color: #374151;
                    border-color: #4B5563;
                }
                .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                    color: #F9FAFB;
                }
                .dark .select2-dropdown {
                    background-color: #1F2937;
                    border-color: #4B5563;
                }
                .dark .select2-container--default .select2-results__option {
                    color: #F9FAFB;
                }
                .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                    background-color: #374151;
                    border-color: #4B5563;
                    color: #F9FAFB;
                }
                .dark .select2-container--default .select2-results__option[aria-selected=true] {
                    background-color: #374151;
                }
            </style>
        @endpush

        {{-- Overview Header with Back Button --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Tambah Pembayaran Piutang</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Rekam penerimaan kas/bank dan alokasikan ke satu atau banyak tagihan invoice customer.
                </p>
            </div>
            <div>
                <a href="{{ $invoice ? route('keuangan.piutang-usaha.show', $invoice->id) : route('keuangan.piutang-usaha.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-red-500 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800 dark:text-red-300">Terdapat kesalahan pengisian formulir:</h4>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-700 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Dynamic JS Error Alert --}}
        <div x-show="errorMessage" x-cloak class="mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-amber-500 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Peringatan:</h4>
                    <p class="text-sm text-amber-700 dark:text-amber-400" x-text="errorMessage"></p>
                </div>
                <button type="button" @click="errorMessage = ''" class="text-amber-500 hover:text-amber-700">
                    <span class="sr-only">Tutup</span>
                    &times;
                </button>
            </div>
        </div>

        <form action="{{ route('keuangan.pembayaran-piutang.store') }}" method="POST" @submit="validateAndSubmit($event)">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden form-card mb-8">
                {{-- Form Card Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-750">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Formulir Pembayaran Piutang</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Masukkan rincian pembayaran dan tentukan alokasi nominal per invoice.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                {{ $nomorPembayaran }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Left Column: Customer & Amount --}}
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Customer <span class="text-red-500">*</span>
                                </label>
                                @if ($customer)
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $customer->nama }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $customer->company ?? 'Pelanggan Langsung' }} &bull; {{ $customer->alamat_utama ?? '-' }}</div>
                                    </div>
                                @else
                                    <select id="customer_select" name="customer_id" x-model="customerId" required
                                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Pilih Customer --</option>
                                        @foreach ($customers as $c)
                                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->nama }} {{ $c->company ? "({$c->company})" : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', old('tanggal', date('Y-m-d'))) }}" required
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jumlah Pembayaran Diterima (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" @click="syncTotalBayarWithAllocations()" x-show="getTotalAllocated() > 0"
                                        class="text-xs text-green-600 hover:text-green-700 font-medium">
                                        Samakan dengan Total Alokasi
                                    </button>
                                </div>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm font-semibold">Rp</span>
                                    </div>
                                    <input type="hidden" name="jumlah_pembayaran" :value="totalBayar">
                                    <input type="text"
                                        :value="formatRupiahInput(totalBayar)"
                                        @input="handleTotalBayarInput($event)"
                                        required
                                        placeholder="0"
                                        class="pl-10 text-lg font-bold border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg text-gray-900 focus:ring-green-500 focus:border-green-500 font-mono">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Total nominal uang yang masuk ke rekening/kas perusahaan.
                                </p>
                            </div>
                        </div>

                        {{-- Right Column: Payment Method & Details --}}
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="metode_pembayaran" x-model="metode" required
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="Bank Transfer">Transfer Bank</option>
                                    <option value="Kas">Kas / Tunai</option>
                                    <option value="Giro">Giro</option>
                                    <option value="Cek">Cek</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Referensi / Bukti Transfer
                                </label>
                                <input type="text" name="no_referensi" value="{{ old('no_referensi') }}"
                                    placeholder="Contoh: TRF-BCA-981240"
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                            </div>

                            {{-- Bank Selection --}}
                            <div x-show="metode === 'Bank Transfer' || metode === 'Giro' || metode === 'Cek'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Rekening Bank Penerima <span class="text-red-500">*</span>
                                </label>
                                <select name="rekening_bank_id" :required="metode !== 'Kas'"
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Pilih Rekening Bank --</option>
                                    @foreach ($bankAccounts as $bank)
                                        <option value="{{ $bank->id }}" {{ old('rekening_bank_id') == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} (a/n {{ $bank->atas_nama }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Cash Selection --}}
                            <div x-show="metode === 'Kas'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Akun Kas Penerima <span class="text-red-500">*</span>
                                </label>
                                <select name="kas_id" :required="metode === 'Kas'"
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Pilih Akun Kas --</option>
                                    @foreach ($kasAccounts as $kas)
                                        <option value="{{ $kas->id }}" {{ old('kas_id') == $kas->id ? 'selected' : '' }}>
                                            {{ $kas->nama }} (Saldo: Rp {{ number_format($kas->saldo, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan Umum
                                </label>
                                <textarea name="catatan" rows="2" placeholder="Catatan transaksi pembayaran..."
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Invoice Allocation Section --}}
                <div class="px-6 pb-6">
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Alokasi Pembayaran ke Invoice
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Cari invoice melalui dropdown Select2 di bawah, lalu klik Tambah Invoice untuk memasukkan ke daftar alokasi.
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" @click="removeAllInvoices()" x-show="selectedInvoices.length > 0"
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg text-xs font-medium hover:bg-red-100 transition-colors">
                                    Kosongkan Daftar
                                </button>
                            </div>
                        </div>

                        {{-- Search & Add Invoice Select2 Dropdown Row --}}
                        <div class="mb-5 p-4 bg-gray-50 dark:bg-gray-750/70 border border-gray-200 dark:border-gray-700 rounded-xl">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                                Cari & Pilih Invoice yang Ingin Dibayar
                            </label>
                            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                                <div class="flex-1">
                                    <select id="invoice_select2" :disabled="isLoadingInvoices || availableInvoices.length === 0"
                                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg shadow-sm text-sm">
                                        <option value="">-- Cari & Pilih Invoice yang Ingin Dibayar --</option>
                                    </select>
                                </div>
                                <button type="button" @click="addSelectedFromDropdown()"
                                    :disabled="!selectedInvoiceIdToAppend || isLoadingInvoices"
                                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold shadow-sm transition-colors disabled:opacity-50 flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Invoice
                                </button>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="'Tersedia ' + availableInvoices.length + ' invoice belum lunas untuk customer ini.'"></span>
                                <span x-text="selectedInvoices.length + ' invoice dipilih'"></span>
                            </div>
                        </div>

                        {{-- Loading Indicator --}}
                        <div x-show="isLoadingInvoices" class="py-12 text-center">
                            <svg class="animate-spin h-8 w-8 text-green-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Memuat daftar tagihan invoice...</p>
                        </div>

                        {{-- Empty State: No Customer Selected or No Invoices Available --}}
                        <div x-show="!isLoadingInvoices && availableInvoices.length === 0" class="py-10 text-center bg-gray-50 dark:bg-gray-750 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                            <svg class="h-10 w-10 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300" x-text="customerId ? 'Tidak ada tagihan invoice yang belum lunas untuk customer ini.' : 'Pilih customer terlebih dahulu untuk melihat daftar invoice.'"></p>
                        </div>

                        {{-- Empty State: Invoices Available but none added yet --}}
                        <div x-show="!isLoadingInvoices && availableInvoices.length > 0 && selectedInvoices.length === 0" class="py-8 text-center bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-dashed border-blue-200 dark:border-blue-800">
                            <svg class="h-8 w-8 text-blue-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Belum ada invoice yang ditambahkan ke daftar pembayaran.</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Cari invoice pada input dropdown di atas lalu klik <strong>"Tambah Invoice"</strong>.</p>
                        </div>

                        {{-- Active Allocated Invoices Table --}}
                        <div x-show="!isLoadingInvoices && selectedInvoices.length > 0" class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-750 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <tr>
                                        <th scope="col" class="px-4 py-3.5 text-left">Nomor Invoice</th>
                                        <th scope="col" class="px-4 py-3.5 text-left">Tanggal & Jatuh Tempo</th>
                                        <th scope="col" class="px-4 py-3.5 text-right">Total Invoice</th>
                                        <th scope="col" class="px-4 py-3.5 text-right">Sisa Piutang</th>
                                        <th scope="col" class="px-4 py-3.5 text-right w-60">Alokasi Pembayaran (Rp)</th>
                                        <th scope="col" class="px-4 py-3.5 text-left">Catatan Alokasi</th>
                                        <th scope="col" class="px-3 py-3.5 text-center w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(inv, idx) in selectedInvoices" :key="inv.id">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                            <td class="px-4 py-3.5 font-semibold text-gray-900 dark:text-white">
                                                <div class="flex items-center gap-1.5">
                                                    <span x-text="inv.nomor_invoice"></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300">
                                                <div x-text="inv.tanggal_invoice"></div>
                                                <div class="text-xs text-gray-400" x-text="'Tempo: ' + (inv.jatuh_tempo || '-')"></div>
                                            </td>
                                            <td class="px-4 py-3.5 text-right text-gray-600 dark:text-gray-300 font-mono" x-text="'Rp ' + formatRupiah(inv.total_invoice)"></td>
                                            <td class="px-4 py-3.5 text-right font-bold text-red-600 dark:text-red-400 font-mono" x-text="'Rp ' + formatRupiah(inv.sisa_piutang)"></td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="space-y-1">
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                            <span class="text-gray-400 text-xs font-semibold">Rp</span>
                                                        </div>
                                                        <input type="hidden"
                                                            :name="'allocations[' + inv.id + ']'"
                                                            :value="allocations[inv.id] || 0">
                                                        <input type="text"
                                                            :value="formatRupiahInput(allocations[inv.id])"
                                                            @input="onAllocationInput(inv, $event)"
                                                            placeholder="0"
                                                            class="pl-8 text-right font-semibold border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg text-sm focus:ring-green-500 focus:border-green-500 font-mono">
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="button" @click="payInFull(inv)"
                                                            class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                                                            Bayar Penuh
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text"
                                                    :name="'catatan_allocations[' + inv.id + ']'"
                                                    x-model="catatanAllocations[inv.id]"
                                                    placeholder="Catatan..."
                                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white block w-full rounded-lg text-xs py-1.5">
                                            </td>
                                            <td class="px-3 py-3.5 text-center">
                                                <button type="button" @click="removeInvoice(inv.id)"
                                                    title="Hapus dari daftar alokasi"
                                                    class="text-red-500 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        {{-- Calculation Summary Widget --}}
                        <div x-show="selectedInvoices.length > 0" class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-750 border border-gray-200 dark:border-gray-700">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pembayaran</span>
                                <div class="mt-1 text-xl font-extrabold text-gray-900 dark:text-white" x-text="'Rp ' + formatRupiah(totalBayar)"></div>
                            </div>
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-750 border border-gray-200 dark:border-gray-700">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Teralokasi ke Invoice</span>
                                <div class="mt-1 text-xl font-extrabold text-green-600 dark:text-green-400" x-text="'Rp ' + formatRupiah(getTotalAllocated())"></div>
                            </div>
                            <div class="p-4 rounded-xl border transition-all"
                                :class="Math.abs(getUnallocatedDifference()) <= 0.05 ? 'bg-green-50/60 dark:bg-green-900/20 border-green-300 dark:border-green-800 text-green-800 dark:text-green-300' : 'bg-amber-50 dark:bg-amber-900/20 border-amber-300 dark:border-amber-800 text-amber-800 dark:text-amber-300'">
                                <span class="text-xs font-medium uppercase tracking-wider">Selisih Alokasi</span>
                                <div class="mt-1 text-xl font-extrabold flex items-center gap-2">
                                    <span x-text="'Rp ' + formatRupiah(getUnallocatedDifference())"></span>
                                    <span x-show="Math.abs(getUnallocatedDifference()) <= 0.05" class="text-xs px-2 py-0.5 rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-100">
                                        Pas (Seimbang)
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Form Actions Footer --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ $invoice ? route('keuangan.piutang-usaha.show', $invoice->id) : route('keuangan.piutang-usaha.index') }}"
                        class="px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </a>
                    <button type="submit" :disabled="isSubmitting"
                        class="px-6 py-2.5 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors disabled:opacity-50 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Pembayaran'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush
</x-app-layout>

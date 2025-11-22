<x-app-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'link' => route('dashboard')],
    ['name' => 'Keuangan', 'link' => '#'],
    ['name' => 'Kalibrasi Akun', 'link' => '#'],
]" :currentPage="'accounting-configuration'">

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />
        <style>
            .select2-container--bootstrap-5 .select2-selection {
                min-height: 38px;
            }

            .dark .select2-container--bootstrap-5 .select2-selection {
                background-color: rgb(55 65 81);
                border-color: rgb(75 85 99);
                color: white;
            }

            .dark .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                color: white;
            }

            .dark .select2-dropdown {
                background-color: rgb(55 65 81);
                border-color: rgb(75 85 99);
            }

            .dark .select2-container--bootstrap-5 .select2-results__option {
                color: white;
            }

            .dark .select2-container--bootstrap-5 .select2-results__option--highlighted {
                background-color: rgb(59 130 246);
            }

            .dark .select2-search--dropdown .select2-search__field {
                background-color: rgb(31 41 55);
                border-color: rgb(75 85 99);
                color: white;
            }
        </style>
    @endpush

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Kalibrasi Akun Akuntansi
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Atur mapping akun akuntansi untuk transaksi otomatis sistem
                    </p>
                </div>
                <a href="{{ route('keuangan.coa.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke COA
                </a>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Informasi:</strong> Hanya akun yang terintegrasi dengan transaksi otomatis (pembelian,
                        penjualan, penggajian, dll) yang perlu dikalibrasi.
                        Akun bertanda <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Wajib</span>
                        harus diisi.
                    </p>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <form id="calibration-form" method="POST"
            action="{{ route('keuangan.accounting-configuration.bulk-update') }}">
            @csrf
            @method('PUT')

            {{-- Configurations by Transaction Type --}}
            @foreach ($configurations as $transactionType => $configs)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600">
                        <h2 class="text-lg font-semibold text-white">
                            {{ ucwords(str_replace('_', ' ', $transactionType)) }}
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($configs as $config)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                        {{-- Account Name --}}
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                {{ $config->account_name }}
                                                @if ($config->is_required)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Wajib
                                                    </span>
                                                @endif
                                            </label>
                                            @if ($config->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $config->description }}
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Account Selection --}}
                                        <div class="md:col-span-2">
                                            <select name="configurations[{{ $config->id }}][akun_id]"
                                                id="akun_select_{{ $config->id }}"
                                                class="select2-akun shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md {{ $config->is_required ? 'required' : '' }}"
                                                {{ $config->is_required ? 'required' : '' }}
                                                data-placeholder="-- Pilih Akun --">
                                                <option value="">-- Pilih Akun --</option>
                                                @foreach ($akunList as $akun)
                                                    <option value="{{ $akun->id }}"
                                                        {{ $config->akun_id == $akun->id ? 'selected' : '' }}
                                                        data-kategori="{{ $akun->kategori }}">
                                                        {{ $akun->kode }} - {{ $akun->nama }}
                                                        ({{ ucfirst($akun->kategori) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($config->akun)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Saat ini: <span class="font-medium">{{ $config->akun->kode }} -
                                                        {{ $config->akun->nama }}</span>
                                                </p>
                                            @else
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">
                                                    ⚠️ Belum dikonfigurasi
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-3 mt-8">
                <a href="{{ route('keuangan.coa.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Kalibrasi
                </button>
            </div>
        </form>

        {{-- Statistics Card --}}
        <div
            class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Statistik Kalibrasi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Konfigurasi</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                @php
                                    $total = $configurations->flatten()->count();
                                @endphp
                                {{ $total }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terkonfigurasi</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                @php
                                    $configured = $configurations
                                        ->flatten()
                                        ->filter(function ($c) {
                                            return $c->akun_id != null;
                                        })
                                        ->count();
                                @endphp
                                {{ $configured }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Dikonfigurasi</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $total - $configured }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($total > 0)
                <div class="mt-4">
                    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Progress Kalibrasi</span>
                        <span class="font-medium">{{ number_format(($configured / $total) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ ($configured / $total) * 100 }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-70 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white text-center mt-4">
                    Konfirmasi Penyimpanan
                </h3>
                <div class="mt-4 px-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                        Anda akan menyimpan <span id="changesCount"
                            class="font-bold text-blue-600 dark:text-blue-400">0</span> perubahan konfigurasi akun.
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-2">
                        Perubahan ini akan mempengaruhi cara sistem mencatat transaksi otomatis. Apakah Anda yakin ingin
                        melanjutkan?
                    </p>
                </div>
                <div class="flex gap-3 mt-6 px-4">
                    <button type="button" id="cancelBtn"
                        class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button type="button" id="confirmBtn"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-70 overflow-y-auto h-full w-full z-50">
        <div class="relative top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
            <p class="mt-4 text-white text-lg font-medium">Menyimpan konfigurasi...</p>
        </div>
    </div>

    {{-- Success Modal --}}
    <div id="successModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-70 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white text-center mt-4">
                    Kalibrasi Berhasil Disimpan!
                </h3>
                <div class="mt-4 px-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                        Konfigurasi akun akuntansi telah berhasil diperbarui. Sistem akan menggunakan mapping akun
                        terbaru untuk transaksi selanjutnya.
                    </p>
                </div>
                <div class="flex justify-center mt-6 px-4">
                    <button type="button" id="closeSuccessBtn"
                        class="px-6 py-2 bg-green-600 text-white text-base font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2
                $('.select2-akun').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih Akun --',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Tidak ada hasil ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        },
                        inputTooShort: function() {
                            return "Ketik untuk mencari";
                        }
                    }
                });

                // Track original values
                const originalValues = {};
                $('.select2-akun').each(function() {
                    originalValues[$(this).attr('id')] = $(this).val();
                });

                // Count changes
                function countChanges() {
                    let changes = 0;
                    $('.select2-akun').each(function() {
                        const id = $(this).attr('id');
                        if ($(this).val() !== originalValues[id]) {
                            changes++;
                        }
                    });
                    return changes;
                }

                // Modal elements
                const confirmModal = document.getElementById('confirmModal');
                const successModal = document.getElementById('successModal');
                const loadingOverlay = document.getElementById('loadingOverlay');
                const changesCountSpan = document.getElementById('changesCount');
                const cancelBtn = document.getElementById('cancelBtn');
                const confirmBtn = document.getElementById('confirmBtn');
                const closeSuccessBtn = document.getElementById('closeSuccessBtn');

                // Form submission with custom modal
                const form = document.getElementById('calibration-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Validate required fields
                        const requiredFields = form.querySelectorAll('select.required');
                        let hasEmpty = false;
                        let emptyFieldNames = [];

                        requiredFields.forEach(field => {
                            if (!field.value) {
                                hasEmpty = true;
                                const label = field.closest('.grid').querySelector('label').textContent
                                    .trim();
                                emptyFieldNames.push(label.replace('Wajib', '').trim());
                            }
                        });

                        if (hasEmpty) {
                            alert('❌ Mohon lengkapi konfigurasi yang wajib diisi:\n\n' + emptyFieldNames.join(
                                '\n'));
                            return false;
                        }

                        // Count changes
                        const changes = countChanges();
                        if (changes === 0) {
                            alert('ℹ️ Tidak ada perubahan yang perlu disimpan.');
                            return false;
                        }

                        // Show confirmation modal
                        changesCountSpan.textContent = changes;
                        confirmModal.classList.remove('hidden');
                    });
                }

                // Cancel button
                cancelBtn.addEventListener('click', function() {
                    confirmModal.classList.add('hidden');
                });

                // Confirm button - submit form via AJAX
                confirmBtn.addEventListener('click', function() {
                    confirmModal.classList.add('hidden');
                    loadingOverlay.classList.remove('hidden');

                    // Prepare form data
                    const formData = new FormData(form);

                    // Submit via AJAX
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            loadingOverlay.classList.add('hidden');

                            if (data.success) {
                                // Update original values
                                $('.select2-akun').each(function() {
                                    originalValues[$(this).attr('id')] = $(this).val();
                                });

                                // Show success modal
                                successModal.classList.remove('hidden');
                            } else {
                                alert('❌ Gagal menyimpan konfigurasi: ' + (data.message ||
                                    'Terjadi kesalahan'));
                            }
                        })
                        .catch(error => {
                            loadingOverlay.classList.add('hidden');
                            console.error('Error:', error);
                            alert('❌ Terjadi kesalahan saat menyimpan konfigurasi. Silakan coba lagi.');
                        });
                });

                // Close success modal and reload
                closeSuccessBtn.addEventListener('click', function() {
                    successModal.classList.add('hidden');
                    location.reload();
                });

                // Auto-save indication with Select2
                $('.select2-akun').on('select2:select select2:clear', function() {
                    $(this).next('.select2-container').find('.select2-selection').addClass('border-yellow-400');
                    setTimeout(() => {
                        $(this).next('.select2-container').find('.select2-selection').removeClass(
                            'border-yellow-400');
                    }, 300);
                });

                // Close modal when clicking outside
                confirmModal.addEventListener('click', function(e) {
                    if (e.target === confirmModal) {
                        confirmModal.classList.add('hidden');
                    }
                });

                successModal.addEventListener('click', function(e) {
                    if (e.target === successModal) {
                        successModal.classList.add('hidden');
                        location.reload();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>

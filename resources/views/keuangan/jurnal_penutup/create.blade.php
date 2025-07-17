<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Buat Jurnal Penutup'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-red-600 dark:text-red-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Jurnal Penutup {{ ucfirst($mode === 'auto' ? 'Otomatis' : 'Manual') }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @if ($mode === 'auto')
                        Buat jurnal penutup otomatis untuk periode yang dipilih
                    @else
                        Buat jurnal penutup secara manual dengan entry detail
                    @endif
                </p>
            </div>
            <div class="mt-4 md:mt-0 space-x-2">
                @if ($mode === 'manual')
                    <a href="{{ route('keuangan.jurnal-penutup.create', ['mode' => 'auto']) }}"
                        class="inline-flex items-center px-4 py-2 border border-green-600 dark:border-green-500 rounded-md shadow-sm text-sm font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Mode Otomatis
                    </a>
                @else
                    <a href="{{ route('keuangan.jurnal-penutup.create', ['mode' => 'manual']) }}"
                        class="inline-flex items-center px-4 py-2 border border-blue-600 dark:border-blue-500 rounded-md shadow-sm text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Mode Manual
                    </a>
                @endif
                <a href="{{ route('keuangan.jurnal-penutup.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('keuangan.jurnal-penutup.store') }}" method="POST" id="jurnalForm">
            @csrf
            <input type="hidden" name="mode" value="{{ $mode }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Form --}}
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Jurnal</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Basic Info --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 @error('tanggal') !border-red-500 @enderror">
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="periode_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Periode Akuntansi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="periode_id" id="periode_id" required
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 @error('periode_id') !border-red-500 @enderror">
                                        <option value="">Pilih Periode</option>
                                        @foreach ($periods as $periode)
                                            <option value="{{ $periode->id }}"
                                                {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->nama }} ({{ ucfirst($periode->status) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('periode_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Keterangan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="keterangan" id="keterangan" rows="3" required
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 @error('keterangan') !border-red-500 @enderror"
                                    placeholder="Keterangan jurnal penutup...">{{ old('keterangan', $mode === 'auto' ? 'Jurnal Penutup Otomatis' : '') }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if ($mode === 'auto')
                        {{-- Auto Closing Preview --}}
                        <div
                            class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Preview Jurnal Penutup
                                    Otomatis</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih periode untuk melihat entry
                                    yang akan dibuat</p>
                            </div>

                            <div class="p-6" id="autoClosingPreview">
                                <div class="text-center py-12">
                                    <div
                                        class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Pilih Periode
                                        Akuntansi</h3>
                                    <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                                        Pilih periode dari dropdown di atas untuk melihat preview jurnal penutup yang
                                        akan dibuat secara otomatis.
                                    </p>
                                    <div class="mt-6 flex items-center justify-center">
                                        <div
                                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3 max-w-sm">
                                            <div
                                                class="flex items-center space-x-2 text-sm text-blue-700 dark:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Sistem akan otomatis membuat jurnal penutup</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Manual Journal Details --}}
                        <div
                            class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <div
                                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Jurnal</h3>
                                <button type="button" onclick="addJournalEntry()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Baris
                                </button>
                            </div>

                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <th
                                                    class="text-left py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Akun</th>
                                                <th
                                                    class="text-left py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Keterangan</th>
                                                <th
                                                    class="text-right py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Debit</th>
                                                <th
                                                    class="text-right py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Kredit</th>
                                                <th
                                                    class="text-center py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="journalEntries">
                                            <!-- Journal entries will be added here -->
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Balance Summary --}}
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Total Debit:</span>
                                            <span id="totalDebit"
                                                class="font-medium text-gray-900 dark:text-gray-100 ml-2">Rp 0</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Total Kredit:</span>
                                            <span id="totalKredit"
                                                class="font-medium text-gray-900 dark:text-gray-100 ml-2">Rp 0</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Balance:</span>
                                            <span id="balance" class="font-medium ml-2">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aksi</h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn" {{ $mode === 'auto' ? 'disabled' : '' }}>
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                @if ($mode === 'auto')
                                    Buat Jurnal Otomatis
                                @else
                                    Simpan Jurnal
                                @endif
                            </button>

                            <a href="{{ route('keuangan.jurnal-penutup.index') }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Batal
                            </a>
                        </div>

                        {{-- Help Text --}}
                        <div class="px-6 pb-6">
                            @if ($mode === 'auto')
                                <div
                                    class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                                    <h4 class="text-sm font-medium text-green-900 dark:text-green-100 mb-2">Jurnal
                                        Penutup Otomatis</h4>
                                    <ul class="text-xs text-green-800 dark:text-green-200 space-y-1">
                                        <li>• Otomatis tutup semua akun pendapatan</li>
                                        <li>• Otomatis tutup semua akun beban</li>
                                        <li>• Hitung laba/rugi periode berjalan</li>
                                        <li>• Transfer ke akun modal/laba ditahan</li>
                                        <li>• Buat akun yang belum ada otomatis</li>
                                    </ul>
                                </div>
                            @else
                                <div
                                    class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Tips Jurnal
                                        Penutup</h4>
                                    <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-1">
                                        <li>• Tutup akun pendapatan ke Ikhtisar L/R</li>
                                        <li>• Tutup akun beban ke Ikhtisar L/R</li>
                                        <li>• Tutup Ikhtisar L/R ke Modal</li>
                                        <li>• Tutup akun Prive ke Modal</li>
                                        <li>• Pastikan total debit = total kredit</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (
                        $mode === 'auto' &&
                            isset($autoClosingData) &&
                            (count($autoClosingData['income_accounts'] ?? []) > 0 || count($autoClosingData['expense_accounts'] ?? []) > 0))
                        {{-- Auto Closing Summary --}}
                        <div
                            class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ringkasan</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Pendapatan:</span>
                                        <span class="font-medium text-green-600 dark:text-green-400"
                                            id="totalPendapatan">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Beban:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400"
                                            id="totalBeban">-</span>
                                    </div>
                                    <hr class="border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">Laba/Rugi
                                            Bersih:</span>
                                        <span class="font-bold" id="netIncome">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Account Search Modal (only for manual mode) --}}
    @if ($mode === 'manual')
        <div id="accountModal"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pilih Akun</h3>
                        <button onclick="closeAccountModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <input type="text" id="accountSearch" placeholder="Cari akun..."
                        class="w-full mb-4 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500">

                    <div id="accountList" class="max-h-96 overflow-y-auto">
                        @foreach ($accounts as $account)
                            <div class="account-item p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700"
                                data-id="{{ $account->id }}" data-code="{{ $account->kode }}"
                                data-name="{{ $account->nama }}">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $account->kode }} -
                                    {{ $account->nama }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $account->kategori }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        /* Custom animations for preview */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .animate-pulse-slow {
            animation: pulse-slow 2s infinite;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }

        /* Hover effects */
        .preview-table-row {
            transition: all 0.2s ease;
        }

        .preview-table-row:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Loading dots animation */
        .loading-dot {
            animation: pulse 1.4s ease-in-out infinite both;
        }

        .loading-dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loading-dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        .loading-dot:nth-child(3) {
            animation-delay: 0s;
        }

        @keyframes pulse {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }
    </style>

    <script>
        const mode = '{{ $mode }}';
        let entryIndex = 0;
        let currentAccountInput = null;

        // Initialize based on mode
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, mode:', mode); // Debug log

            if (mode === 'manual') {
                // Add initial journal entries for manual mode
                addJournalEntry();
                addJournalEntry();
            } else if (mode === 'auto') {
                console.log('Auto mode detected'); // Debug log

                // Add event listener for periode dropdown
                const periodeSelect = document.getElementById('periode_id');
                if (periodeSelect) {
                    console.log('Adding change listener to periode dropdown'); // Debug log

                    periodeSelect.addEventListener('change', function() {
                        console.log('Periode changed to:', this.value); // Debug log
                        loadAutoClosingData();
                    });

                    // If a periode is already selected, load data immediately
                    if (periodeSelect.value) {
                        console.log('Initial periode selected:', periodeSelect.value); // Debug log
                        loadAutoClosingData();
                    }
                }
            }
        });

        // Auto closing functionality - available globally
        async function loadAutoClosingData() {
            // console.log('loadAutoClosingData called'); // Debug log

            const periodeId = document.getElementById('periode_id').value;
            // console.log('Selected periode ID:', periodeId); // Debug log
            if (!periodeId) {
                document.getElementById('autoClosingPreview').innerHTML = `
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Pilih Periode Akuntansi</h3>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                            Pilih periode dari dropdown di atas untuk melihat preview jurnal penutup yang akan dibuat secara otomatis.
                        </p>
                        <div class="mt-6 flex items-center justify-center">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3 max-w-sm">
                                <div class="flex items-center space-x-2 text-sm text-blue-700 dark:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Sistem akan otomatis membuat jurnal penutup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('submitBtn').disabled = true;
                return;
            }

            // Show loading
            document.getElementById('autoClosingPreview').innerHTML = `
                <div class="text-center py-12 animate-fade-in-up">
                    <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Memuat Data...</h3>
                    <p class="text-gray-600 dark:text-gray-400">Sedang menganalisis akun dan menyiapkan jurnal penutup</p>
                    <div class="mt-4 flex items-center justify-center space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full loading-dot"></div>
                        <div class="w-2 h-2 bg-blue-500 rounded-full loading-dot"></div>
                        <div class="w-2 h-2 bg-blue-500 rounded-full loading-dot"></div>
                    </div>
                </div>
            `;

            try {
                const url = `{{ route('keuangan.jurnal-penutup.auto-preview') }}?periode_id=${periodeId}`;
                // console.log('Fetching URL:', url); // Debug log

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response error text:', errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                if (data.success) {
                    displayAutoClosingPreview(data);
                    updateAutoClosingSummary(data);
                    document.getElementById('submitBtn').disabled = false;
                } else {
                    throw new Error(data.message || 'Unknown error');
                }

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('autoClosingPreview').innerHTML = `
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Terjadi Kesalahan</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            ${error.message.includes('404') ? 'Periode tidak ditemukan' : 
                              error.message.includes('500') ? 'Terjadi kesalahan server' : 
                              'Error memuat data jurnal penutup'}
                        </p>
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4 max-w-md mx-auto mb-6">
                            <div class="text-sm text-red-800 dark:text-red-200">
                                <strong>Detail Error:</strong> ${error.message}
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-3">
                            <button onclick="loadAutoClosingData()" class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Coba Lagi
                            </button>
                            <button onclick="console.log('Error details:', error)" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Debug Info
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById('submitBtn').disabled = true;
            }
        }

        function displayAutoClosingPreview(data) {
            if (!data.success) {
                document.getElementById('autoClosingPreview').innerHTML = `
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Terjadi Kesalahan</h3>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">${data.message || 'Error memuat data jurnal penutup'}</p>
                        <div class="mt-6">
                            <button onclick="loadAutoClosingData()" class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Coba Lagi
                            </button>
                        </div>
                    </div>
                `;
                return;
            }

            if (!data.entries || data.entries.length === 0) {
                document.getElementById('autoClosingPreview').innerHTML = `
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tidak Ada Data</h3>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-4">
                            Tidak ada transaksi untuk periode ini atau periode sudah ditutup sebelumnya.
                        </p>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 max-w-md mx-auto">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-yellow-800 dark:text-yellow-200">
                                    Pastikan ada jurnal umum di periode yang dipilih
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }

            let html = `
                <!-- Header Info -->
                <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-800 dark:to-indigo-800 rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Preview Jurnal Penutup</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        ${data.entries.length} entri jurnal akan dibuat
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-1">Status</div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">Siap Diproses</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3 text-center border border-blue-100 dark:border-blue-800">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Entri</div>
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">${data.entries.length}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3 text-center border border-green-100 dark:border-green-800">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Akun Pendapatan</div>
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">${data.entries.filter(e => e.akun_jenis === 'Pendapatan').length}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3 text-center border border-red-100 dark:border-red-800">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Akun Beban</div>
                            <div class="text-lg font-bold text-red-600 dark:text-red-400">${data.entries.filter(e => e.akun_jenis === 'Beban').length}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3 text-center border border-purple-100 dark:border-purple-800">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Lainnya</div>
                            <div class="text-lg font-bold text-purple-600 dark:text-purple-400">${data.entries.filter(e => !['Pendapatan', 'Beban'].includes(e.akun_jenis)).length}</div>
                        </div>
                    </div>
                </div>

                <!-- Entries Table -->
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4v8a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2zM9 11h6" />
                                            </svg>
                                            <span>Akun</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                            <span>Keterangan</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center justify-end space-x-1">
                                            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                            </svg>
                                            <span>Debit</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center justify-end space-x-1">
                                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                            </svg>
                                            <span>Kredit</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            `;

            data.entries.forEach((entry, index) => {
                const isEven = index % 2 === 0;
                const bgClass = isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800';

                html += `
                    <tr class="${bgClass} preview-table-row hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150 animate-slide-in-right" style="animation-delay: ${index * 0.1}s">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-800 dark:to-purple-800 rounded-lg flex items-center justify-center shadow-sm">
                                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">${entry.akun_code.substring(0, 2)}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        ${entry.akun_code} - ${entry.akun_name}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-200">
                                            ${entry.akun_jenis}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100 leading-relaxed">
                                ${entry.keterangan}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            ${entry.debit > 0 ? `
                                    <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        Rp ${entry.debit.toLocaleString('id-ID')}
                                    </div>
                                    <div class="text-xs text-green-500 dark:text-green-400">
                                        ${(entry.debit / 1000000).toFixed(1)}M
                                    </div>
                                ` : `
                                    <div class="text-sm text-gray-400 dark:text-gray-600">-</div>
                                `}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            ${entry.kredit > 0 ? `
                                    <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                        Rp ${entry.kredit.toLocaleString('id-ID')}
                                    </div>
                                    <div class="text-xs text-red-500 dark:text-red-400">
                                        ${(entry.kredit / 1000000).toFixed(1)}M
                                    </div>
                                ` : `
                                    <div class="text-sm text-gray-400 dark:text-gray-600">-</div>
                                `}
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Summary Footer -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Debit</p>
                                <p class="text-lg font-bold text-green-900 dark:text-green-100">Rp ${data.total_debit.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-600 dark:text-red-400">Total Kredit</p>
                                <p class="text-lg font-bold text-red-900 dark:text-red-100">Rp ${data.total_kredit.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Balance Status</p>
                                <p class="text-lg font-bold text-blue-900 dark:text-blue-100">Seimbang ✓</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Preview jurnal sudah diperiksa dan siap untuk diproses</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Generated on ${new Date().toLocaleString('id-ID')}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('autoClosingPreview').innerHTML = html;

            // Remove existing animation classes and add fade-in animation
            document.getElementById('autoClosingPreview').classList.remove('animate-fade-in-up');
            setTimeout(() => {
                document.getElementById('autoClosingPreview').classList.add('animate-fade-in-up');
            }, 10);
        }

        function updateAutoClosingSummary(data) {
            if (data.summary) {
                document.getElementById('totalPendapatan').textContent = 'Rp ' + data.summary.total_pendapatan
                    .toLocaleString('id-ID');
                document.getElementById('totalBeban').textContent = 'Rp ' + data.summary.total_beban.toLocaleString(
                    'id-ID');

                const netIncome = data.summary.net_income;
                const netIncomeEl = document.getElementById('netIncome');
                netIncomeEl.textContent = 'Rp ' + Math.abs(netIncome).toLocaleString('id-ID');

                if (netIncome > 0) {
                    netIncomeEl.className = 'font-bold text-green-600 dark:text-green-400';
                } else if (netIncome < 0) {
                    netIncomeEl.className = 'font-bold text-red-600 dark:text-red-400';
                } else {
                    netIncomeEl.className = 'font-bold text-gray-600 dark:text-gray-400';
                }
            }
        }

        @if ($mode === 'manual')
            // Manual mode functionality
            function addJournalEntry() {
                const tbody = document.getElementById('journalEntries');
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 dark:border-gray-700';
                row.innerHTML = `
                <td class="py-3">
                    <input type="hidden" name="details[${entryIndex}][akun_id]" class="akun-id-input">
                    <input type="text" name="details[${entryIndex}][akun_display]" 
                           class="akun-display-input w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500" 
                           placeholder="Pilih akun..." readonly onclick="openAccountModal(this)">
                </td>
                <td class="py-3">
                    <input type="text" name="details[${entryIndex}][keterangan]" 
                           class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500" 
                           placeholder="Keterangan...">
                </td>
                <td class="py-3">
                    <input type="number" name="details[${entryIndex}][debit]" step="0.01" min="0"
                           class="debit-input w-full text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500" 
                           placeholder="0.00" onchange="updateBalance()">
                </td>
                <td class="py-3">
                    <input type="number" name="details[${entryIndex}][kredit]" step="0.01" min="0"
                           class="kredit-input w-full text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500" 
                           placeholder="0.00" onchange="updateBalance()">
                </td>
                <td class="py-3 text-center">
                    <button type="button" onclick="removeJournalEntry(this)" 
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            `;
                tbody.appendChild(row);
                entryIndex++;
                updateRowNumbers();
            }

            function removeJournalEntry(button) {
                const tbody = document.getElementById('journalEntries');
                if (tbody.children.length > 2) {
                    button.closest('tr').remove();
                    updateRowNumbers();
                    updateBalance();
                } else {
                    alert('Minimal harus ada 2 baris entry dalam jurnal');
                }
            }

            function updateRowNumbers() {
                const rows = document.querySelectorAll('#journalEntries tr');
                rows.forEach((row, index) => {
                    row.querySelectorAll('input[name*="details["]').forEach(input => {
                        const name = input.getAttribute('name');
                        const newName = name.replace(/details\[\d+\]/, `details[${index}]`);
                        input.setAttribute('name', newName);
                    });
                });
            }

            function openAccountModal(input) {
                currentAccountInput = input;
                document.getElementById('accountModal').classList.remove('hidden');
                document.getElementById('accountSearch').focus();
            }

            function closeAccountModal() {
                document.getElementById('accountModal').classList.add('hidden');
                currentAccountInput = null;
                document.getElementById('accountSearch').value = '';
                showAllAccounts();
            }

            function updateBalance() {
                let totalDebit = 0;
                let totalKredit = 0;

                document.querySelectorAll('.debit-input').forEach(input => {
                    totalDebit += parseFloat(input.value) || 0;
                });

                document.querySelectorAll('.kredit-input').forEach(input => {
                    totalKredit += parseFloat(input.value) || 0;
                });

                const balance = totalDebit - totalKredit;

                document.getElementById('totalDebit').textContent = 'Rp ' + totalDebit.toLocaleString('id-ID');
                document.getElementById('totalKredit').textContent = 'Rp ' + totalKredit.toLocaleString('id-ID');

                const balanceEl = document.getElementById('balance');
                balanceEl.textContent = 'Rp ' + Math.abs(balance).toLocaleString('id-ID');

                if (balance === 0) {
                    balanceEl.className = 'font-medium ml-2 text-green-600 dark:text-green-400';
                    document.getElementById('submitBtn').disabled = false;
                } else {
                    balanceEl.className = 'font-medium ml-2 text-red-600 dark:text-red-400';
                    document.getElementById('submitBtn').disabled = true;
                }
            }

            // Account selection
            document.querySelectorAll('.account-item').forEach(item => {
                item.addEventListener('click', function() {
                    if (currentAccountInput) {
                        const akunId = this.getAttribute('data-id');
                        const akunCode = this.getAttribute('data-code');
                        const akunName = this.getAttribute('data-name');

                        const row = currentAccountInput.closest('tr');
                        row.querySelector('.akun-id-input').value = akunId;
                        currentAccountInput.value = `${akunCode} - ${akunName}`;

                        closeAccountModal();
                    }
                });
            });

            // Account search
            document.getElementById('accountSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.account-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            function showAllAccounts() {
                document.querySelectorAll('.account-item').forEach(item => {
                    item.style.display = 'block';
                });
            }

            // Close modal when clicking outside
            document.getElementById('accountModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAccountModal();
                }
            });

            // Form validation for manual mode
            document.getElementById('jurnalForm').addEventListener('submit', function(e) {
                const totalDebit = Array.from(document.querySelectorAll('.debit-input')).reduce((sum, input) =>
                    sum + (parseFloat(input.value) || 0), 0);
                const totalKredit = Array.from(document.querySelectorAll('.kredit-input')).reduce((sum, input) =>
                    sum + (parseFloat(input.value) || 0), 0);

                if (Math.abs(totalDebit - totalKredit) > 0.01) {
                    e.preventDefault();
                    alert('Total debit dan kredit harus seimbang!');
                    return;
                }

                // Check if at least one account is selected
                const hasAccounts = Array.from(document.querySelectorAll('.akun-id-input')).some(input => input
                    .value);
                if (!hasAccounts) {
                    e.preventDefault();
                    alert('Minimal harus ada satu akun yang dipilih!');
                    return;
                }
            });

            // Initialize balance calculation
            updateBalance();
        @endif
    </script>
</x-app-layout>

<x-app-layout>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Akun</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Edit informasi akun di Chart of Accounts (COA)
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('keuangan.coa.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 border-gray-300">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <form action="{{ route('keuangan.coa.update', $akun->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Akun -->
                        <div class="col-span-1">
                            <label for="kode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <input type="text" name="kode" id="kode" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('kode') border-red-500 @enderror"
                                    value="{{ old('kode', $akun->kode) }}">
                            </div>
                            @error('kode')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode akun harus unik dan sesuai
                                dengan standar akuntansi</p>
                        </div>

                        <!-- Nama Akun -->
                        <div class="col-span-1">
                            <label for="nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <input type="text" name="nama" id="nama" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nama') border-red-500 @enderror"
                                    value="{{ old('nama', $akun->nama) }}">
                            </div>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Akun -->
                        <div class="col-span-1">
                            <label for="kategori"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <select name="kategori" id="kategori" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('kategori') border-red-500 @enderror">
                                    @foreach ($kategoriAkun as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('kategori', $akun->kategori) == $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Akun -->
                        <div class="col-span-1">
                            <label for="tipe"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <select name="tipe" id="tipe" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('tipe') border-red-500 @enderror">
                                    @foreach ($tipeAkun as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('tipe', $akun->tipe) == $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('tipe')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Header untuk kelompok akun, Detail
                                untuk akun transaksi</p>
                        </div>

                        <!-- Parent Akun -->
                        <div class="col-span-2">
                            <label for="parent_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Akun Induk</label>
                            <div class="mt-1">
                                <select name="parent_id" id="parent_id"
                                    class="select2-parent-akun block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('parent_id') border-red-500 @enderror"
                                    data-placeholder="Pilih Akun Induk (Opsional)">
                                    <option value="">Tidak Ada (Akun Level Atas)</option>

                                    @php
                                        $groupedAccounts = $allAccounts
                                            ->where('id', '!=', $akun->id)
                                            ->groupBy('kategori');
                                        $kategoriLabels = [
                                            'asset' => '💰 ASET',
                                            'liability' => '📋 KEWAJIBAN',
                                            'equity' => '🏦 EKUITAS',
                                            'income' => '💵 PENDAPATAN',
                                            'expense' => '💸 BEBAN',
                                            'purchase' => '🛒 PEMBELIAN',
                                            'other_income' => '💎 PENDAPATAN LAIN',
                                            'other_expense' => '💳 BIAYA LAIN',
                                            'other' => '📦 LAINNYA',
                                        ];

                                        function renderAllAccountOptionsEdit(
                                            $accounts,
                                            $parent_id = null,
                                            $level = 0,
                                            $current_id = null,
                                            $selected_id = null,
                                        ) {
                                            $result = '';
                                            foreach ($accounts as $account) {
                                                // Hanya tampilkan akun HEADER sebagai parent
                                                if (
                                                    $account->parent_id == $parent_id &&
                                                    $account->id != $current_id &&
                                                    $account->tipe == 'header'
                                                ) {
                                                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                                    $prefix = $level > 0 ? '├─ ' : '';
                                                    $selected = $account->id == $selected_id ? ' selected' : '';
                                                    $result .=
                                                        '<option value="' .
                                                        $account->id .
                                                        '"' .
                                                        $selected .
                                                        ' data-tipe="' .
                                                        $account->tipe .
                                                        '" data-level="' .
                                                        $level .
                                                        '">' .
                                                        $indent .
                                                        $prefix .
                                                        $account->kode .
                                                        ' - ' .
                                                        $account->nama .
                                                        '</option>';
                                                    $result .= renderAllAccountOptionsEdit(
                                                        $accounts,
                                                        $account->id,
                                                        $level + 1,
                                                        $current_id,
                                                        $selected_id,
                                                    );
                                                }
                                            }
                                            return $result;
                                        }
                                    @endphp

                                    @foreach ($kategoriLabels as $kategori => $label)
                                        @if ($groupedAccounts->has($kategori))
                                            <optgroup label="{{ $label }}">
                                                {!! renderAllAccountOptionsEdit(
                                                    $groupedAccounts[$kategori],
                                                    null,
                                                    0,
                                                    $akun->id,
                                                    old('parent_id', $akun->parent_id),
                                                ) !!}
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya akun tipe Header yang bisa
                                menjadi induk</p>
                        </div>

                        <!-- Reference Type (Kas/Rekening) -->
                        <div class="col-span-1">
                            <label for="reference_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referensi Ke</label>
                            <div class="mt-1">
                                <select name="reference_type" id="reference_type"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    onchange="toggleReferenceOptions()">
                                    <option value="">Tidak Ada</option>
                                    <option value="kas"
                                        {{ $akun->ref_type == App\Models\Kas::class ? 'selected' : '' }}>Kas</option>
                                    <option value="rekening"
                                        {{ $akun->ref_type == App\Models\RekeningBank::class ? 'selected' : '' }}>
                                        Rekening Bank</option>
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hubungkan akun dengan kas atau
                                rekening bank yang sudah ada</p>
                        </div>

                        <!-- Kas Options -->
                        <div class="col-span-2" id="kas_options"
                            style="display: {{ $akun->ref_type == App\Models\Kas::class ? 'block' : 'none' }}">
                            <label for="reference_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Kas</label>
                            <div class="mt-1">
                                <select name="reference_id" id="kas_select"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Pilih Kas</option>
                                    @foreach ($kasOptions as $kas)
                                        <option value="{{ $kas->id }}"
                                            {{ $akun->ref_type == App\Models\Kas::class && $akun->ref_id == $kas->id ? 'selected' : '' }}>
                                            {{ $kas->nama }} (Saldo: Rp
                                            {{ number_format($kas->saldo, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Rekening Options -->
                        <div class="col-span-2" id="rekening_options"
                            style="display: {{ $akun->ref_type == App\Models\RekeningBank::class ? 'block' : 'none' }}">
                            <label for="reference_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Rekening
                                Bank</label>
                            <div class="mt-1">
                                <select name="reference_id" id="rekening_select"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Pilih Rekening Bank</option>
                                    @foreach ($rekeningOptions as $rekening)
                                        <option value="{{ $rekening->id }}"
                                            {{ $akun->ref_type == App\Models\RekeningBank::class && $akun->ref_id == $rekening->id ? 'selected' : '' }}>
                                            {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }}
                                            ({{ $rekening->atas_nama }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-span-1">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded"
                                    {{ old('is_active', $akun->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Akun Aktif
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nonaktifkan jika akun tidak
                                digunakan</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right flex justify-end space-x-3">
                    <button type="button" onclick="window.location.href='{{ route('keuangan.coa.index') }}'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />
        <style>
            /* Select2 Dark Mode Support */
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

            /* Indentation visual */
            .select2-results__option[data-level="1"] {
                padding-left: 30px !important;
            }

            .select2-results__option[data-level="2"] {
                padding-left: 50px !important;
            }

            .select2-results__option[data-level="3"] {
                padding-left: 70px !important;
            }

            /* Header styling */
            .select2-results__option[data-tipe="header"] {
                font-weight: 600;
                background-color: #f3f4f6;
            }

            .dark .select2-results__option[data-tipe="header"] {
                background-color: rgb(31 41 55);
            }
        </style>
    @endpush

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        /* Styling untuk dropdown akun induk */
        #parent_id option[disabled] {
            font-weight: 700;
            font-size: 0.875rem;
            color: #1f2937;
            background-color: #f3f4f6;
            padding: 8px 12px;
            margin-top: 4px;
        }

        .dark #parent_id option[disabled] {
            color: #d1d5db;
            background-color: #1f2937;
        }

        #parent_id option:not([disabled]) {
            padding: 6px 12px;
        }

        #parent_id option:not([disabled]):hover {
            background-color: #e5e7eb;
        }

        .dark #parent_id option:not([disabled]):hover {
            background-color: #374151;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for parent akun
            $('.select2-parent-akun').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Akun Induk (Opsional)',
                allowClear: true,
                templateResult: formatAccountOption,
                templateSelection: formatAccountSelection
            });

            // Format option display in dropdown
            function formatAccountOption(option) {
                if (!option.id) {
                    return option.text;
                }

                var $option = $(option.element);
                var level = $option.data('level') || 0;
                var indent = '&nbsp;&nbsp;&nbsp;'.repeat(level);
                var icon = '📁 '; // Semua adalah header

                return $('<span>' + indent + icon + option.text + '</span>');
            }

            // Format selected display
            function formatAccountSelection(option) {
                return option.text || 'Tidak Ada (Akun Level Atas)';
            }

            // Initialize reference options
            toggleReferenceOptions();
        });

        function toggleReferenceOptions() {
            const referenceType = document.getElementById('reference_type').value;
            const kasOptions = document.getElementById('kas_options');
            const rekeningOptions = document.getElementById('rekening_options');
            const kasSelect = document.getElementById('kas_select');
            const rekeningSelect = document.getElementById('rekening_select');

            if (referenceType === 'kas') {
                kasOptions.style.display = 'block';
                rekeningOptions.style.display = 'none';
                kasSelect.name = 'reference_id';
                rekeningSelect.name = 'rekening_id_disabled';
            } else if (referenceType === 'rekening') {
                kasOptions.style.display = 'none';
                rekeningOptions.style.display = 'block';
                kasSelect.name = 'kas_id_disabled';
                rekeningSelect.name = 'reference_id';
            } else {
                kasOptions.style.display = 'none';
                rekeningOptions.style.display = 'none';
                kasSelect.name = 'kas_id_disabled';
                rekeningSelect.name = 'rekening_id_disabled';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleReferenceOptions();
        });
    </script>
</x-app-layout>

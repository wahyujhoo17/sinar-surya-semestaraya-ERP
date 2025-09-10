<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Detail', 'url' => route('produksi.work-order.show', $workOrder->id)],
    ['label' => 'Pengambilan Bahan Baku'],
]" :currentPage="'Pengambilan Bahan Baku'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    Pengambilan Bahan Baku
                </h1>
                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-6">
            {{-- Informasi WO --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Work Order</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Work Order</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $workOrder->nomor }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->produk->nama ?? 'Produk tidak ditemukan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->quantity }} {{ $workOrder->satuan->nama ?? '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->gudangProduksi->nama ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Form Pengambilan --}}
            <div>
                <form action="{{ route('produksi.work-order.store-pengambilan', $workOrder->id) }}" method="POST"
                    id="form-pengambilan">
                    @csrf
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Form Pengambilan Bahan Baku
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" name="tanggal" id="tanggal"
                                            value="{{ old('tanggal', date('d/m/Y')) }}"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md datepicker"
                                            placeholder="Pilih tanggal">
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" id="catatan" rows="3"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Material</h2>
                            <button type="button" id="btn-add-manual-material"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Material Manual
                            </button>
                        </div>
                        <div class="p-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                                id="materials-table">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kebutuhan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Stok Tersedia
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jumlah Ambil
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                                    id="materials-tbody">
                                    {{-- Material dari BOM --}}
                                    @foreach ($workOrder->materials as $index => $material)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" data-type="bom">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $material->produk->nama ?? 'Produk tidak ditemukan' }}
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $material->produk->kode ?? '-' }}
                                                </div>
                                                <div class="text-xs text-blue-600 dark:text-blue-400">
                                                    Dari BOM
                                                </div>
                                                <input type="hidden" name="detail[{{ $index }}][produk_id]"
                                                    value="{{ $material->produk_id }}">
                                                <input type="hidden" name="detail[{{ $index }}][satuan_id]"
                                                    value="{{ $material->satuan_id }}">
                                                <input type="hidden" name="detail[{{ $index }}][tipe]"
                                                    value="bom">
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">
                                                {{ number_format($material->quantity, 2, ',', '.') }}
                                                {{ $material->satuan->nama ?? '-' }}
                                                <input type="hidden"
                                                    name="detail[{{ $index }}][jumlah_diminta]"
                                                    value="{{ number_format($material->quantity, 2, '.', '') }}">
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right 
                                                {{ $material->stok_tersedia < $material->quantity ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                                                {{ number_format($material->stok_tersedia, 2, ',', '.') }}
                                                {{ $material->satuan->nama ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <input type="number" step="0.01" min="0"
                                                    name="detail[{{ $index }}][jumlah_diambil]"
                                                    id="jumlah-diminta-{{ $index }}"
                                                    value="{{ old('detail.' . $index . '.jumlah_diambil', min($material->quantity, $material->stok_tersedia)) }}"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    {{ $material->stok_tersedia == 0 ? 'disabled' : '' }}>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <input type="text" name="detail[{{ $index }}][keterangan]"
                                                    id="keterangan-{{ $index }}"
                                                    value="{{ old('detail.' . $index . '.keterangan', $material->stok_tersedia < $material->quantity ? 'Stok tidak mencukupi' : '') }}"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    placeholder="Keterangan (opsional)">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Dari BOM</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Simpan Pengambilan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 36px;
                padding-left: 12px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: #374151;
                border-color: #4b5563;
                color: #f9fafb;
            }

            .dark .select2-dropdown {
                background-color: #374151;
                border-color: #4b5563;
            }

            .dark .select2-container--default .select2-results__option {
                background-color: #374151;
                color: #f9fafb;
            }

            .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #4f46e5;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Datepicker
                flatpickr('.datepicker', {
                    dateFormat: 'd/m/Y',
                    locale: 'id'
                });

                // Form validation
                document.getElementById('form-pengambilan').addEventListener('submit', function(e) {
                    // Hapus baris material manual yang kosong sebelum validasi
                    const emptyManualRows = document.querySelectorAll('.manual-material');
                    emptyManualRows.forEach(row => {
                        const produkSelect = row.querySelector('select[name*="produk_id"]');
                        const jumlahDiminta = row.querySelector('input[name*="jumlah_diminta"]');
                        const jumlahDiambil = row.querySelector('input[name*="jumlah_diambil"]');

                        // Jika tidak ada yang diisi sama sekali, hapus baris
                        if (!produkSelect.value && !jumlahDiminta.value && !jumlahDiambil.value) {
                            row.remove();
                        }
                    });

                    // Check if at least one material has a non-zero quantity
                    let hasNonZeroQuantity = false;
                    const quantityInputs = document.querySelectorAll(
                        'input[name^="detail"][name$="[jumlah_diambil]"]');

                    for (const input of quantityInputs) {
                        if (parseFloat(input.value) > 0) {
                            hasNonZeroQuantity = true;
                            break;
                        }
                    }

                    if (!hasNonZeroQuantity) {
                        e.preventDefault();
                        alert('Setidaknya satu material harus memiliki jumlah pengambilan lebih dari 0.');
                        return false;
                    }

                    // Validasi material manual harus lengkap
                    const manualRows = document.querySelectorAll('.manual-material');
                    for (const row of manualRows) {
                        const produkSelect = row.querySelector('select[name*="produk_id"]');
                        const jumlahDiminta = row.querySelector('input[name*="jumlah_diminta"]');
                        const jumlahDiambil = row.querySelector('input[name*="jumlah_diambil"]');

                        if (produkSelect && produkSelect.value) {
                            // Jika produk dipilih, maka jumlah harus diisi
                            if (!jumlahDiminta.value || !jumlahDiambil.value) {
                                e.preventDefault();
                                alert(
                                    'Jumlah diminta dan jumlah diambil harus diisi untuk material yang dipilih.'
                                    );
                                jumlahDiminta.focus();
                                return false;
                            }

                            // Validasi jumlah diambil tidak boleh melebihi stok
                            const stokElement = row.querySelector('.stok-value');
                            const stokTersedia = parseFloat(stokElement.textContent.replace(/[.,]/g, '')) || 0;
                            const jumlahDiambilValue = parseFloat(jumlahDiambil.value) || 0;

                            if (jumlahDiambilValue > stokTersedia) {
                                e.preventDefault();
                                alert(
                                    `Jumlah diambil (${jumlahDiambilValue}) tidak boleh melebihi stok tersedia (${stokTersedia}).`
                                    );
                                jumlahDiambil.focus();
                                return false;
                            }
                        } else if (jumlahDiminta.value || jumlahDiambil.value) {
                            // Jika ada input jumlah tapi produk belum dipilih
                            e.preventDefault();
                            alert('Pilih material terlebih dahulu sebelum mengisi jumlah.');
                            produkSelect.focus();
                            return false;
                        }
                    }
                });

                // Tambah Material Manual
                let materialIndex = @json($workOrder->materials->count());
                let isAddingMaterial = false; // Flag untuk mencegah multiple clicks

                // Remove existing event listeners first
                const btnAddMaterial = document.getElementById('btn-add-manual-material');
                btnAddMaterial.replaceWith(btnAddMaterial.cloneNode(true));

                document.getElementById('btn-add-manual-material').addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default button behavior
                    e.stopPropagation(); // Stop event propagation

                    // Prevent multiple clicks
                    if (isAddingMaterial) {
                        console.log('Already adding material, please wait...');
                        return;
                    }

                    isAddingMaterial = true;
                    addManualMaterialRow();

                    // Reset flag after a short delay
                    setTimeout(() => {
                        isAddingMaterial = false;
                    }, 500);
                });

                function addManualMaterialRow() {
                    const tbody = document.getElementById('materials-tbody');
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 manual-material';
                    row.setAttribute('data-type', 'manual');

                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <select name="detail[${materialIndex}][produk_id]" 
                                    class="produk-select w-full" 
                                    data-index="${materialIndex}">
                                <option value="">Pilih Material dari Stok Gudang...</option>
                            </select>
                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                <span class="material-info">Tambahan Manual</span>
                            </div>
                            <input type="hidden" name="detail[${materialIndex}][satuan_id]" class="satuan-id-input">
                            <input type="hidden" name="detail[${materialIndex}][tipe]" value="manual">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">
                            <input type="number" step="0.01" min="0.01" 
                                   name="detail[${materialIndex}][jumlah_diminta]" 
                                   class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-right" 
                                   placeholder="0.00" 
                                   required>
                            <div class="satuan-display text-xs text-gray-500 dark:text-gray-400 mt-1"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right stok-tersedia text-gray-500 dark:text-gray-300">
                            <span class="stok-value">-</span>
                            <div class="satuan-display text-xs text-gray-500 dark:text-gray-400 mt-1"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <input type="number" step="0.01" min="0.01" 
                                   name="detail[${materialIndex}][jumlah_diambil]" 
                                   class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" 
                                   placeholder="0.00" 
                                   required>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <input type="text" name="detail[${materialIndex}][keterangan]" 
                                   class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" 
                                   placeholder="Keterangan (opsional)">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <button type="button" onclick="removeManualMaterial(this)" 
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>
                    `;

                    tbody.appendChild(row);

                    // Initialize Select2 untuk dropdown yang baru ditambahkan
                    initializeSelect2ForRow(row, materialIndex);

                    materialIndex++;
                }

                // Function untuk initialize Select2
                function initializeSelect2ForRow(row, index) {
                    const select = row.querySelector('.produk-select');

                    $(select).select2({
                        placeholder: 'Pilih Material dari Stok Gudang...',
                        allowClear: true,
                        width: '100%',
                        ajax: {
                            url: '{{ route('produksi.work-order.get-available-materials', $workOrder->id) }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                    page: params.page || 1
                                };
                            },
                            processResults: function(data, params) {
                                params.page = params.page || 1;

                                if (data.error) {
                                    console.error('AJAX Error:', data.error);
                                    return {
                                        results: [],
                                        pagination: {
                                            more: false
                                        }
                                    };
                                }

                                return {
                                    results: data.items || [],
                                    pagination: {
                                        more: data.has_more || false
                                    }
                                };
                            },
                            cache: true
                        },
                        templateResult: function(item) {
                            if (item.loading) return item.text;

                            return $(`
                                <div>
                                    <div class="font-medium">${item.nama}</div>
                                    <div class="text-xs text-gray-500">
                                        Kode: ${item.kode} | Stok: ${item.stok_tersedia} ${item.satuan_nama}
                                    </div>
                                </div>
                            `);
                        },
                        templateSelection: function(item) {
                            return item.nama || item.text;
                        }
                    });

                    // Event handler ketika material dipilih
                    $(select).on('select2:select', function(e) {
                        const data = e.params.data;
                        updateMaterialInfo(this, index, data);
                    });

                    // Event handler ketika material di-clear
                    $(select).on('select2:clear', function(e) {
                        clearMaterialInfo(this);
                    });
                }

                // Function untuk update informasi material saat dipilih
                window.updateMaterialInfo = function(selectElement, index, data = null) {
                    const row = selectElement.closest('tr');

                    if (data && data.id) {
                        // Update satuan ID
                        const satuanIdInput = row.querySelector('.satuan-id-input');
                        satuanIdInput.value = data.satuan_id;

                        // Update display satuan
                        const satuanDisplays = row.querySelectorAll('.satuan-display');
                        satuanDisplays.forEach(display => {
                            display.textContent = data.satuan_nama || '';
                        });

                        // Update stok tersedia
                        const stokElement = row.querySelector('.stok-value');
                        const stokContainer = row.querySelector('.stok-tersedia');

                        stokElement.textContent = parseFloat(data.stok_tersedia || 0).toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        // Update warna berdasarkan stok
                        if (data.stok_tersedia > 0) {
                            stokContainer.className = stokContainer.className.replace(/text-red-\d+/,
                                'text-green-500');
                            stokContainer.className = stokContainer.className.replace(/dark:text-red-\d+/,
                                'dark:text-green-400');
                        } else {
                            stokContainer.className = stokContainer.className.replace(/text-green-\d+/,
                                'text-red-500');
                            stokContainer.className = stokContainer.className.replace(/dark:text-green-\d+/,
                                'dark:text-red-400');
                        }

                        // Update material info
                        const materialInfo = row.querySelector('.material-info');
                        if (materialInfo) {
                            materialInfo.textContent = `${data.nama} (${data.kode})`;
                        }

                        // Set max value untuk input jumlah berdasarkan stok
                        const jumlahInputs = row.querySelectorAll('input[name*="jumlah"]');
                        jumlahInputs.forEach(input => {
                            input.setAttribute('max', data.stok_tersedia);
                        });

                    } else {
                        clearMaterialInfo(selectElement);
                    }
                };

                // Function untuk clear informasi material
                function clearMaterialInfo(selectElement) {
                    const row = selectElement.closest('tr');

                    // Clear satuan ID
                    const satuanIdInput = row.querySelector('.satuan-id-input');
                    satuanIdInput.value = '';

                    // Clear display satuan
                    const satuanDisplays = row.querySelectorAll('.satuan-display');
                    satuanDisplays.forEach(display => {
                        display.textContent = '';
                    });

                    // Clear stok tersedia
                    const stokElement = row.querySelector('.stok-value');
                    stokElement.textContent = '-';

                    // Reset material info
                    const materialInfo = row.querySelector('.material-info');
                    if (materialInfo) {
                        materialInfo.textContent = 'Tambahan Manual';
                    }

                    // Remove max value dari input
                    const jumlahInputs = row.querySelectorAll('input[name*="jumlah"]');
                    jumlahInputs.forEach(input => {
                        input.removeAttribute('max');
                        input.value = '';
                    });
                }

                // Function untuk hapus material manual
                window.removeManualMaterial = function(button) {
                    if (confirm('Hapus material ini?')) {
                        button.closest('tr').remove();
                    }
                };
            });
        </script>
    @endpush
</x-app-layout>

<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Delivery Order', 'url' => route('penjualan.delivery-order.index')],
    ['label' => 'Tambah (Tanpa SO)'],
]" :currentPage="'Tambah Delivery Order'">
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container { width: 100% !important; }
            .select2-container--default .select2-selection--single {
                height: 38px; border-color: #D1D5DB; border-radius: 0.375rem; display: flex; align-items: center;
            }
            .select2-container--default .select2-selection--single:focus,
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 38px; display: flex; align-items: center;
            }
            .dark .select2-container--default .select2-selection--single {
                background-color: #374151; border-color: #4B5563; color: #F9FAFB;
            }
            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #F9FAFB;
            }
            .dark .select2-dropdown { background-color: #1F2937; border-color: #4B5563; }
            .dark .select2-container--default .select2-results__option { color: #F9FAFB; }
            .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: #374151; border-color: #4B5563; color: #F9FAFB;
            }
            .dark .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: #374151;
            }
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
            input[type=number] { -moz-appearance: textfield; }
        </style>
    @endpush

    <div class="py-8 max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-6">
            
            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/30 p-4 rounded-lg border border-red-200 dark:border-red-800">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Terdapat kesalahan dalam pengisian form:</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('penjualan.delivery-order.store') }}" method="POST" id="formDeliveryOrder">
                @csrf

                <!-- header DO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="nomor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor DO <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor" id="nomor" value="{{ old('nomor', $nomor) }}" readonly
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    </div>
                    <div wire:ignore>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer <span class="text-red-500">*</span></label>
                        <select name="customer_id" id="customer_id" required class="w-full">
                            <option value="">Pilih Customer...</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama }} - {{ $customer->company ?? 'Personal' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div wire:ignore>
                        <label for="gudang_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gudang Asal <span class="text-red-500">*</span></label>
                        <select name="gudang_id" id="gudang_id" required class="w-full">
                            <option value="">Pilih Gudang...</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                    {{ $gudang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat_pengiriman" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Pengiriman <span class="text-red-500">*</span></label>
                        <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">{{ old('alamat_pengiriman') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="2"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <!-- Products -->
                <div class="mb-8 bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Produk</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar produk yang akan dikirim</p>
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" id="btnAddItem"
                                class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium rounded shadow-sm focus:outline-none">
                                <i class="fas fa-plus mr-1"></i> Tambah Produk
                            </button>
                            <button type="button" id="btnOpenBundleModal"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded shadow-sm focus:outline-none">
                                <i class="fas fa-boxes mr-1"></i> Tambah Bundle
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left" style="table-layout:fixed;">
                            <colgroup>
                                <col style="width:35%;">
                                <col style="width:10%;">
                                <col style="width:10%;">
                                <col style="width:12%;">
                                <col style="width:25%;">
                                <col style="width:8%;">
                            </colgroup>
                            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satuan</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dikirim</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody" class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                        </table>
                    </div>
                </div>


                <!-- Bundle Modal -->
                <div id="bundleModalContainer" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity btnCloseBundleModal" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="fas fa-boxes text-indigo-600 dark:text-indigo-400"></i>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Pilih Paket Bundle</h3>
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Paket Bundle</label>
                                            <select id="bundle_select" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                <option value="">-- Pilih Paket Bundle --</option>
                                                @foreach($bundles as $bundle)
                                                    <option value="{{ $bundle->id }}">{{ $bundle->nama }}</option>
                                                @endforeach
                                            </select>
                                            
                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kuantitas Paket</label>
                                                <input type="number" id="bundle_quantity" min="1" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" id="btnConfirmBundle" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Tambahkan
                                </button>
                                <button type="button" class="btnCloseBundleModal mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" id="submitBtn"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i> Simpan Delivery Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            var rowCounter = 0;
            var produkMap = {!! json_encode($produks->mapWithKeys(function ($item) {
                return [$item->id => ['satuan_id' => $item->satuan_id]];
            })) !!};

            var produkOptions = `<option value="">Pilih Produk...</option>@foreach($produks as $produk)<option value="{{ $produk->id }}">{{ $produk->kode }} - {{ $produk->nama }}</option>@endforeach`;
            var satuanOptions = `@foreach($satuans as $satuan)<option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>@endforeach`;

            function addRow(data = {}) {
                rowCounter++;
                const id = rowCounter;
                
                let isBundle = data.is_bundle_item || false;
                
                let html = `
                    <tr id="row_${id}" class="product-row bg-white dark:bg-gray-900">
                        <td class="px-4 py-3">
                            <input type="hidden" name="is_bundle_item[]" value="${isBundle ? 1 : 0}">
                            <input type="hidden" name="bundle_name[]" value="${data.bundle_name || ''}">
                `;
                
                if (isBundle) {
                    html += `
                            <div class="mb-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded inline-flex items-center">
                                <i class="fas fa-box-open mr-1"></i> Paket: ${data.bundle_name}
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${data.produk_nama}</div>
                            <input type="hidden" name="produk_id[]" value="${data.produk_id}">
                    `;
                } else {
                    html += `
                            <select name="produk_id[]" id="produk_${id}" class="w-full select2-produk" required>
                                ${produkOptions}
                            </select>
                    `;
                }
                
                html += `
                        </td>
                        <td class="px-3 py-3">
                            <select name="satuan_id[]" id="satuan_${id}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-center" required>
                                ${satuanOptions}
                            </select>
                        </td>
                        <td class="px-3 py-3 text-center stock-cell" id="stock_cell_${id}">
                            <span class="text-gray-400 text-xs">-</span>
                        </td>
                        <td class="px-3 py-3">
                            <input type="number" name="quantity[]" id="qty_${id}" class="qty-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-center" value="${data.quantity || 0}" min="0" step="0.01" required>
                        </td>
                        <td class="px-3 py-3">
                            <input type="text" name="keterangan[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="${data.keterangan || ''}" placeholder="Keterangan">
                        </td>
                        <td class="px-3 py-3 text-center">
                            <button type="button" class="text-red-500 hover:text-red-700 transition-colors btn-remove-row" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#productTableBody').append(html);
                
                if (!isBundle) {
                    $(`#produk_${id}`).select2({
                        width: '100%',
                        placeholder: 'Pilih Produk...'
                    }).on('change', function() {
                        const produkId = $(this).val();
                        if (produkId && produkMap[produkId]) {
                            $(`#satuan_${id}`).val(produkMap[produkId].satuan_id);
                        }
                        fetchStockInfo();
                    });
                } else {
                    if (data.satuan_id) {
                        $(`#satuan_${id}`).val(data.satuan_id);
                    }
                }
                
                $(`#qty_${id}`).on('input', function() {
                    updateRowColor($(`#row_${id}`));
                });

                if(data.produk_id && !isBundle) {
                    $(`#produk_${id}`).val(data.produk_id).trigger('change');
                }

                checkEmptyState();
                return id;
            }

            function fetchStockInfo() {
                const gudangId = $('#gudang_id').val();
                if (!gudangId) return;

                const productIds = [];
                $('.product-row').each(function() {
                    let pid = $(this).find('select[name="produk_id[]"]').val() || $(this).find('input[name="produk_id[]"]').val();
                    if(pid) productIds.push(pid);
                });
                
                if (productIds.length === 0) return;

                // Set loading state
                $('.product-row').each(function() {
                    let pid = $(this).find('select[name="produk_id[]"]').val() || $(this).find('input[name="produk_id[]"]').val();
                    if(pid) {
                        $(this).find('.stock-cell').html('<span class="animate-pulse bg-gray-200 dark:bg-gray-600 h-6 w-16 rounded inline-block"></span>');
                    }
                });

                $.ajax({
                    url: "{{ route('penjualan.delivery-order.get-stock-info') }}",
                    type: 'GET',
                    data: { gudang_id: gudangId, product_ids: productIds },
                    success: function(response) {
                        if (response.success && response.stocks) {
                            $('.product-row').each(function() {
                                let pid = $(this).find('select[name="produk_id[]"]').val() || $(this).find('input[name="produk_id[]"]').val();
                                if(pid) {
                                    let stock = response.stocks[pid];
                                    let cell = $(this).find('.stock-cell');
                                    let qtyInput = $(this).find('.qty-input');
                                    
                                    $(this).data('stock', stock !== undefined ? stock : 0);
                                    
                                    if(stock > 0) {
                                        cell.html(`<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${stock}</span>`);
                                        qtyInput.attr('max', stock);
                                    } else {
                                        cell.html(`<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Stok kosong</span>`);
                                    }
                                    updateRowColor($(this));
                                }
                            });
                        }
                    }
                });
            }

            function updateRowColor(row) {
                let stock = row.data('stock');
                let qty = parseFloat(row.find('.qty-input').val()) || 0;
                let pid = row.find('select[name="produk_id[]"]').val() || row.find('input[name="produk_id[]"]').val();
                let qtyInput = row.find('.qty-input');
                
                row.removeClass('bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400');
                qtyInput.removeClass('border-red-500 ring-1 ring-red-500');

                if (pid && stock !== undefined && stock !== null) {
                    if (stock <= 0) {
                        row.addClass('bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400');
                    } else if (qty > stock) {
                        row.addClass('bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400');
                        qtyInput.addClass('border-red-500 ring-1 ring-red-500');
                    }
                }
            }

            function checkEmptyState() {
                if ($('.product-row').length === 0) {
                    if ($('#emptyRow').length === 0) {
                        $('#productTableBody').html(`
                            <tr id="emptyRow">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-box-open text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <p>Belum ada produk ditambahkan. Klik <strong>Tambah Produk</strong> di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $('#emptyRow').remove();
                }
            }

            $(document).ready(function() {
                $('#customer_id, #gudang_id').select2({ width: '100%' });
                $('#bundle_select').select2({ width: '100%', dropdownParent: $('#bundleModalContainer') });

                // Auto-fill alamat pengiriman from customer
                $('#customer_id').on('change', function() {
                    const customerId = $(this).val();
                    if (customerId) {
                        fetch(`/api/customers/${customerId}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.alamat_pengiriman) {
                                    $('#alamat_pengiriman').val(data.alamat_pengiriman);
                                } else {
                                    $('#alamat_pengiriman').val('');
                                }
                            });
                    } else {
                        $('#alamat_pengiriman').val('');
                    }
                });

                $('#gudang_id').on('change', function() {
                    fetchStockInfo();
                });

                $('#btnAddItem').click(function() {
                    addRow();
                });

                $('#btnOpenBundleModal').click(function() {
                    $('#bundle_select').val('').trigger('change');
                    $('#bundle_quantity').val(1);
                    $('#bundleModalContainer').removeClass('hidden');
                });

                $('.btnCloseBundleModal').click(function() {
                    $('#bundleModalContainer').addClass('hidden');
                });

                $('#btnConfirmBundle').click(function() {
                    const bundleId = $('#bundle_select').val();
                    const qty = parseInt($('#bundle_quantity').val()) || 1;
                    
                    if (!bundleId) return;

                    let btn = $(this);
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...');

                    fetch(`/penjualan/sales-order/get-bundle-data/${bundleId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                data.items.forEach(bundleItem => {
                                    addRow({
                                        produk_id: bundleItem.produk_id,
                                        produk_nama: bundleItem.produk_nama,
                                        quantity: bundleItem.quantity * qty,
                                        satuan_id: bundleItem.satuan_id,
                                        keterangan: 'Bagian dari paket ' + data.bundle.nama,
                                        is_bundle_item: true,
                                        bundle_name: data.bundle.nama
                                    });
                                });
                                setTimeout(fetchStockInfo, 100);
                                $('#bundleModalContainer').addClass('hidden');
                            } else {
                                alert(data.message || 'Gagal memuat data bundle');
                            }
                        })
                        .finally(() => {
                            btn.prop('disabled', false).html('Tambahkan');
                        });
                });

                $(document).on('click', '.btn-remove-row', function() {
                    let row = $(this).closest('tr');
                    let isBundle = row.find('input[name="is_bundle_item[]"]').val() == "1";
                    
                    if (isBundle) {
                        let bundleName = row.find('input[name="bundle_name[]"]').val();
                        if (confirm('Item ini bagian dari Paket: ' + bundleName + '. Menghapusnya akan menghapus seluruh isi paket ini. Lanjutkan?')) {
                            $('.product-row').each(function() {
                                if ($(this).find('input[name="bundle_name[]"]').val() === bundleName) {
                                    let select = $(this).find('.select2-produk');
                                    if(select.length && select.data('select2')) select.select2('destroy');
                                    $(this).remove();
                                }
                            });
                        }
                    } else {
                        let select = row.find('.select2-produk');
                        if(select.length && select.data('select2')) select.select2('destroy');
                        row.remove();
                    }
                    checkEmptyState();
                });

                $('#formDeliveryOrder').submit(function(e) {
                    if ($('.product-row').length === 0) {
                        e.preventDefault();
                        alert('Silakan tambahkan minimal satu produk.');
                        return false;
                    }

                    let valid = true;
                    $('.product-row').each(function(i) {
                        let pid = $(this).find('select[name="produk_id[]"]').val() || $(this).find('input[name="produk_id[]"]').val();
                        if (!pid) {
                            alert(`Pilih produk pada baris ke-${i + 1}`);
                            valid = false;
                            return false;
                        }
                    });

                    if(!valid) {
                        e.preventDefault();
                        return false;
                    }

                    setTimeout(function() {
                        $('#submitBtn').prop('disabled', true);
                    }, 50);
                    $('#submitBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
                });

                // Init with one empty row
                if ($('.product-row').length === 0) {
                    $('#productTableBody').empty();
                    addRow();
                }
            });
        </script>
    @endpush
</x-app-layout>

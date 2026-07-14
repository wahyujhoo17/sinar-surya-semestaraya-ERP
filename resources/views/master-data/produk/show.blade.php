<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    @php
        // Hitung margin keuntungan
        $margin =
            $produk->harga_jual > 0 && $produk->harga_beli > 0
                ? (($produk->harga_jual - $produk->harga_beli) / $produk->harga_beli) * 100
                : 0;

        // Status stok berdasarkan stok_minimum
        $stokStatus = 'normal';
        if ($produk->total_stok <= 0) {
            $stokStatus = 'habis';
        } elseif ($produk->total_stok <= $produk->stok_minimum) {
            $stokStatus = 'warning';
        }

        $totalQtySales = $riwayatPenjualan->sum('quantity');
        $totalNilaiSales = $riwayatPenjualan->sum('subtotal');
        $avgHargaSales = $riwayatPenjualan->count() > 0
            ? $riwayatPenjualan->sum('harga') / $riwayatPenjualan->count()
            : 0;

        $totalQtyPembelian = $riwayatPembelian->sum('quantity');
        $totalValuePembelian = $riwayatPembelian->sum('subtotal');
        $avgPricePembelian = $riwayatPembelian->avg('harga') ?? 0;
    @endphp

    <div class="py-6 px-4 sm:px-6 lg:px-8 mx-auto max-w-7xl" x-data="{ activeTab: 'details' }">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Detail Produk: {{ $produk->nama }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Master Data / Produk / Detail</p>
            </div>

            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('master.produk.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Kembali
                </a>
                <button
                    @click="window.dispatchEvent(new CustomEvent('open-produk-modal', {detail: {mode: 'edit', product: {{ json_encode($produk) }} }}))"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Edit Produk
                </button>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="md:w-1/3 bg-gray-100 dark:bg-gray-900 flex items-center justify-center p-6 border-r border-gray-200 dark:border-gray-700">
                @if ($produk->gambar)
                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="max-h-64 object-contain rounded">
                @else
                    <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                @endif
            </div>
            <div class="md:w-2/3 p-6 flex flex-col justify-center">
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        {{ $produk->kategori->nama ?? 'Tanpa Kategori' }}
                    </span>
                    @if ($produk->is_active)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                    @endif
                    @if ($stokStatus === 'habis')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Stok Habis</span>
                    @elseif($stokStatus === 'warning')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Stok Minimum</span>
                    @endif
                </div>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $produk->nama }}</h2>
                <div class="text-sm border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <span class="text-gray-500 font-mono mr-3">Kode: {{ $produk->kode }}</span>
                    @if($produk->product_sku) <span class="text-gray-500 font-mono mr-3">SKU: {{ $produk->product_sku }}</span> @endif
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Stok</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ number_format($produk->total_stok, 0, ',', '.') }} {{ $produk->satuan->nama ?? 'unit' }}</p>
                    </div>
                    @if (auth()->user()->hasPermission('produk.lihat_harga'))
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Harga Jual</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">Rp{{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Satuan</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $produk->satuan->nama ?? '—' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab Nav -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="-mb-px flex space-x-6 overflow-x-auto">
                @php
                    $tabs = [
                        'details' => 'Informasi Dasar',
                        'specifications' => 'Spesifikasi',
                    ];
                    if (auth()->user()->hasPermission('produk.lihat_harga')) {
                        $tabs['pricing'] = 'Harga & Margin';
                    }
                    $tabs['stock'] = 'Stok & Gudang';
                    $tabs['history'] = 'Riwayat Pembelian';
                    $tabs['sales_history'] = 'Riwayat Penjualan';
                @endphp
                @foreach ($tabs as $key => $label)
                    <button @click="activeTab = '{{ $key }}'"
                        :class="activeTab === '{{ $key }}' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Detail Tab -->
        <div x-show="activeTab === 'details'" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                @php
                    $detailRows = [
                        'Kode Produk' => $produk->kode,
                        'SKU' => $produk->product_sku ?: '—',
                        'Nama Produk' => $produk->nama,
                        'Merek' => $produk->merek ?: '—',
                        'Kategori' => $produk->kategori->nama ?? '—',
                        'Sub Kategori' => $produk->sub_kategori ?: '—',
                        'Jenis Produk' => $produk->jenis->nama ?? '—',
                        'Status' => $produk->is_active ? 'Aktif' : 'Nonaktif',
                    ];
                @endphp
                @foreach ($detailRows as $label => $value)
                    <div class="flex flex-col border-b border-gray-100 dark:border-gray-700/50 pb-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $value }}</span>
                    </div>
                @endforeach
                <div class="md:col-span-2 pt-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400 block mb-2">Deskripsi</span>
                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line bg-gray-50 dark:bg-gray-900/50 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                        {{ $produk->deskripsi ?: 'Tidak ada deskripsi' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Spesifikasi Tab -->
        <div x-show="activeTab === 'specifications'" x-cloak class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">Spesifikasi Detail</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                @php
                    $specRows = [
                        'Satuan' => $produk->satuan->nama ?? '—',
                        'Ukuran' => $produk->ukuran ?: '—',
                        'Tipe Material' => $produk->type_material ?: '—',
                        'Kualitas' => $produk->kualitas ?: '—',
                    ];
                @endphp
                @foreach ($specRows as $label => $value)
                    <div class="flex flex-col border-b border-gray-100 dark:border-gray-700/50 pb-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pricing Tab -->
        @if (auth()->user()->hasPermission('produk.lihat_harga'))
        <div x-show="activeTab === 'pricing'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Harga</h3>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="py-3 flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Harga Beli</span>
                        <span class="text-sm text-gray-900 dark:text-white font-bold">Rp{{ number_format($produk->harga_beli, 0, ',', '.') }}</span>
                    </div>
                    <div class="py-3 flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Harga Jual</span>
                        <span class="text-sm text-gray-900 dark:text-white font-bold text-indigo-600 dark:text-indigo-400">Rp{{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Margin & Keuntungan</h3>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-500 font-medium">Margin Keuntungan</span>
                        <span class="text-sm font-bold @if($margin >= 30) text-green-600 dark:text-green-400 @elseif($margin >= 15) text-blue-600 dark:text-blue-400 @else text-yellow-600 dark:text-yellow-400 @endif">
                            {{ number_format($margin, 1) }}%
                        </span>
                    </div>
                    <div class="py-3 flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Selisih Profit</span>
                        <span class="text-sm text-gray-900 dark:text-white font-bold">Rp{{ number_format($produk->harga_jual - $produk->harga_beli, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Stock Tab -->
        <div x-show="activeTab === 'stock'" x-cloak class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Distribusi Gudang</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 uppercase tracking-widest text-center mb-1">Total Stok</p>
                    <p class="text-2xl font-bold text-center text-gray-900 dark:text-white">{{ number_format($produk->total_stok, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 uppercase tracking-widest text-center mb-1">Stok Minimum</p>
                    <p class="text-2xl font-bold text-center text-gray-900 dark:text-white">{{ $produk->stok_minimum ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded border border-gray-200 dark:border-gray-700 flex flex-col justify-center items-center">
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Status</p>
                    @if ($stokStatus === 'habis')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Stok Habis</span>
                    @elseif($stokStatus === 'warning')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Stok Minimum</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Stok Aman</span>
                    @endif
                </div>
            </div>

            @if (count($produk->stok) > 0)
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Gudang</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jumlah Tersedia</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($produk->stok as $stok)
                                <tr>
                                    <td class="px-4 py-3 text-gray-900 dark:text-white font-medium whitespace-nowrap">{{ $stok->gudang->nama ?? 'Gudang #' . $stok->gudang_id }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-white whitespace-nowrap">{{ $stok->jumlah }} {{ $produk->satuan->nama ?? '' }}</td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $stok->updated_at ? $stok->updated_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-6 text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                    Belum ada data stok di gudang manapun.
                </div>
            @endif
        </div>

        <!-- History Pembelian Tab -->
        <div x-show="activeTab === 'history'" x-cloak class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Pembelian</h3>
                <span class="text-sm text-gray-500">10 transaksi terbaru</span>
            </div>

            @if (count($riwayatPembelian) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block mb-1">Total Qty Pembelian</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalQtyPembelian, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block mb-1">Total Nilai</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Rp{{ number_format($totalValuePembelian, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block mb-1">Harga Rata-rata</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Rp{{ number_format($avgPricePembelian, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">PO</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Supplier</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Qty</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Harga</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($riwayatPembelian as $riwayat)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('pembelian.purchasing-order.show', $riwayat->purchase_order_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-medium">
                                            {{ $riwayat->nomor_po }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white">{{ $riwayat->supplier_nama }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ date('d/m/Y', strtotime($riwayat->tanggal)) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white">{{ number_format($riwayat->quantity, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">Rp{{ number_format($riwayat->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white font-medium">Rp{{ number_format($riwayat->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                    Belum ada riwayat pembelian untuk produk ini.
                </div>
            @endif
        </div>

        <!-- History Penjualan Tab -->
        <div x-show="activeTab === 'sales_history'" x-cloak class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Penjualan</h3>
                <span class="text-sm text-gray-500">10 transaksi terbaru</span>
            </div>

            @if (count($riwayatPenjualan) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block mb-1">Total Qty Terjual</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalQtySales, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block mb-1">Total Nilai Penjualan</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Rp{{ number_format($totalNilaiSales, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block mb-1">Harga Rata-rata Jual</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Rp{{ number_format($avgHargaSales, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Invoice</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Customer</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Qty</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Harga</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($riwayatPenjualan as $penjualan)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('penjualan.invoice.show', $penjualan->invoice_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-medium">
                                            {{ $penjualan->nomor_invoice }}
                                        </a>
                                        @if($penjualan->nomor_so)
                                            <div class="text-xs text-gray-500">SO: {{ $penjualan->nomor_so }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white">{{ $penjualan->customer_company ?: $penjualan->customer_nama }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ date('d/m/Y', strtotime($penjualan->tanggal)) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white">{{ number_format($penjualan->quantity, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">Rp{{ number_format($penjualan->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white font-medium">Rp{{ number_format($penjualan->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('laporan.penjualan.index') }}?search={{ urlencode($produk->nama) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Lihat Laporan Lengkap &rarr;
                    </a>
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                    Belum ada riwayat penjualan untuk produk ini.
                </div>
            @endif
        </div>

    </div>

    <x-modal-produk :kategoris="$kategoris" :satuans="$satuans" :jenisProduks="$jenisProduks" />

    @push('styles')
        <style>
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    @push('scripts')
        <script>
            window.addEventListener('refresh-product-table', function(e) {
                if (e.detail.action === 'update') {
                    setTimeout(() => { window.location.reload(); }, 500);
                }
            });
        </script>
    @endpush
</x-app-layout>
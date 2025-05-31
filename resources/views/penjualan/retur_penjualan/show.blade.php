<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Retur
                                Penjualan</h1>
                            <div class="mt-1 flex items-center gap-2">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400">{{ $returPenjualan->nomor }}</span>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $returPenjualan->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                    {{ $returPenjualan->status === 'menunggu_persetujuan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                    {{ $returPenjualan->status === 'disetujui' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $returPenjualan->status === 'ditolak' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $returPenjualan->status === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                    {{ $returPenjualan->status === 'menunggu_barang_pengganti' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' : '' }}
                                    {{ $returPenjualan->status === 'selesai' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $returPenjualan->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('penjualan.retur.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>

                    @if ($returPenjualan->status === 'draft')
                        <a href="{{ route('penjualan.retur.edit', $returPenjualan) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>

                        <button type="button" onclick="confirmSubmitApproval()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ajukan Persetujuan
                        </button>
                    @endif

                    @if ($returPenjualan->status === 'menunggu_persetujuan')
                        <button type="button" onclick="showApprovalModal()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Setujui
                        </button>

                        <button type="button" onclick="showRejectionModal()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak
                        </button>
                    @endif

                    <a href="{{ route('penjualan.retur.pdf', $returPenjualan) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Cetak PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Workflow Progress Timeline --}}
        <div
            class="mb-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Progres Retur Penjualan</h3>
            </div>
            <div class="p-6 overflow-x-auto">
                @php
                    // Define step statuses
                    $workflowSteps = [
                        'draft' => [
                            'label' => 'Draft',
                            'description' => 'Retur dibuat',
                            'icon' =>
                                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                            'status' => 'completed', // draft is always completed since it's created
    ],
    'menunggu_persetujuan' => [
        'label' => 'Menunggu Persetujuan',
        'description' => 'Menunggu persetujuan dari kepala bagian',
        'icon' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'status' => in_array($returPenjualan->status, [
            'menunggu_persetujuan',
            'disetujui',
            'ditolak',
            'diproses',
            'menunggu_barang_pengganti',
            'selesai',
        ])
            ? 'completed'
            : 'pending',
    ],
    'persetujuan' => [
        'label' => $returPenjualan->status === 'ditolak' ? 'Ditolak' : 'Disetujui',
        'description' =>
            $returPenjualan->status === 'ditolak'
                ? 'Retur ditolak'
                : 'Retur disetujui untuk diproses',
        'icon' =>
            $returPenjualan->status === 'ditolak'
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'status' => in_array($returPenjualan->status, [
            'disetujui',
            'diproses',
            'menunggu_barang_pengganti',
            'selesai',
        ])
            ? 'completed'
            : ($returPenjualan->status === 'ditolak'
                ? 'rejected'
                : 'pending'),
    ],
    'diproses' => [
        'label' => 'Diproses',
        'description' => 'Barang diterima dan sedang diproses',
        'icon' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
        'status' => in_array($returPenjualan->status, [
            'diproses',
            'menunggu_barang_pengganti',
            'selesai',
        ])
            ? 'completed'
            : 'pending',
    ],
    'menunggu_barang_pengganti' => [
        'label' => 'Menunggu Barang Pengganti',
        'description' => 'Menunggu pengiriman barang pengganti',
        'icon' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>',
        'status' => in_array($returPenjualan->status, ['menunggu_barang_pengganti', 'selesai'])
            ? 'completed'
            : ($returPenjualan->tipe_retur === 'tukar_barang'
                ? 'pending'
                : 'hidden'),
    ],
    'selesai' => [
        'label' => 'Selesai',
        'description' => 'Retur selesai diproses',
        'icon' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'status' => $returPenjualan->status === 'selesai' ? 'completed' : 'pending',
    ],
]; // If status is ditolak, we don't show diproses, menunggu_barang_pengganti and selesai
                    if ($returPenjualan->status === 'ditolak') {
                        unset($workflowSteps['diproses']);
                        unset($workflowSteps['menunggu_barang_pengganti']);
                        unset($workflowSteps['selesai']);
                    }

                    // If tipe_retur is not tukar_barang, don't show menunggu_barang_pengganti step
if ($returPenjualan->tipe_retur !== 'tukar_barang') {
    unset($workflowSteps['menunggu_barang_pengganti']);
                    }
                @endphp

                <div class="relative flex items-start justify-between w-full min-w-[600px]">
                    {{-- Connecting line --}}
                    <div class="absolute top-6 left-0 right-0 h-0.5 bg-gray-200 dark:bg-gray-700"></div>

                    {{-- Steps --}}
                    @foreach ($workflowSteps as $step => $data)
                        @if ($data['status'] !== 'hidden')
                            <div class="relative flex flex-col items-center flex-1">
                                <div
                                    class="z-10 flex items-center justify-center w-12 h-12 rounded-full
                                {{ $data['status'] === 'completed' ? 'bg-green-100 dark:bg-green-900/30' : '' }}
                                {{ $data['status'] === 'rejected' ? 'bg-red-100 dark:bg-red-900/30' : '' }}
                                {{ $data['status'] === 'pending' ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                                    <span
                                        class="
                                    {{ $data['status'] === 'completed' ? 'text-green-600 dark:text-green-400' : '' }}
                                    {{ $data['status'] === 'rejected' ? 'text-red-600 dark:text-red-400' : '' }}
                                    {{ $data['status'] === 'pending' ? 'text-gray-400 dark:text-gray-500' : '' }}">
                                        {!! $data['icon'] !!}
                                    </span>
                                </div>
                                <div class="mt-3 space-y-1 text-center">
                                    <h4
                                        class="font-medium
                                    {{ $data['status'] === 'completed' ? 'text-green-600 dark:text-green-400' : '' }}
                                    {{ $data['status'] === 'rejected' ? 'text-red-600 dark:text-red-400' : '' }}
                                    {{ $data['status'] === 'pending' ? 'text-gray-500 dark:text-gray-400' : '' }}">
                                        {{ $data['label'] }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-[120px] mx-auto">
                                        {{ $data['description'] }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Retur Information --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Retur</h3>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $returPenjualan->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                            {{ $returPenjualan->status === 'menunggu_persetujuan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                            {{ $returPenjualan->status === 'disetujui' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                            {{ $returPenjualan->status === 'ditolak' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                            {{ $returPenjualan->status === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                            {{ $returPenjualan->status === 'menunggu_barang_pengganti' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' : '' }}
                            {{ $returPenjualan->status === 'selesai' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $returPenjualan->status)) }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                    Retur</label>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $returPenjualan->nomor }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($returPenjualan->tanggal)->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->customer->nama ?? ($returPenjualan->customer->company ?? '-') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $returPenjualan->customer->kode ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sales
                                    Order</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->salesOrder->nomor ?? '-' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-medium
                                    {{ $returPenjualan->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                    {{ $returPenjualan->status === 'menunggu_persetujuan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                    {{ $returPenjualan->status === 'disetujui' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $returPenjualan->status === 'ditolak' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $returPenjualan->status === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                    {{ $returPenjualan->status === 'menunggu_barang_pengganti' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' : '' }}
                                    {{ $returPenjualan->status === 'selesai' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full 
                                        {{ $returPenjualan->status === 'draft' ? 'bg-gray-500' : '' }}
                                        {{ $returPenjualan->status === 'menunggu_persetujuan' ? 'bg-yellow-500' : '' }}
                                        {{ $returPenjualan->status === 'disetujui' ? 'bg-green-500' : '' }}
                                        {{ $returPenjualan->status === 'ditolak' ? 'bg-red-500' : '' }}
                                        {{ $returPenjualan->status === 'diproses' ? 'bg-blue-500' : '' }}
                                        {{ $returPenjualan->status === 'menunggu_barang_pengganti' ? 'bg-amber-500' : '' }}
                                        {{ $returPenjualan->status === 'selesai' ? 'bg-purple-500' : '' }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $returPenjualan->status)) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat
                                    oleh</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->user->name ?? '-' }}</p>
                            </div>
                        </div>

                        @if ($returPenjualan->catatan)
                            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $returPenjualan->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Product Details --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Produk</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Qty Retur
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Harga Satuan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Alasan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $totalRetur = 0;
                                @endphp
                                @foreach ($returPenjualan->details as $detail)
                                    @php
                                        // Get the sales order detail to get the price
                                        $salesOrderDetail = \App\Models\SalesOrderDetail::where(
                                            'sales_order_id',
                                            $returPenjualan->sales_order_id,
                                        )
                                            ->where('produk_id', $detail->produk_id)
                                            ->first();

                                        $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
                                        $subtotal = $hargaSatuan * $detail->quantity;
                                        $totalRetur += $subtotal;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama ?? '-' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">
                                                {{ $detail->produk->kode ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ number_format($detail->quantity, 2, ',', '.') }}
                                            {{ $detail->satuan->nama ?? ($detail->produk->satuan->nama ?? '') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                                                {{ $detail->alasan }}
                                            </span>
                                            @if ($detail->keterangan)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $detail->keterangan }}</div>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Total Retur:
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary-600 dark:text-primary-400">
                                        Rp {{ number_format($totalRetur, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Replacement Products Section --}}
                @if (
                    $returPenjualan->tipe_retur === 'tukar_barang' ||
                        $returPenjualan->status === 'menunggu_barang_pengganti' ||
                        $returPenjualan->barangPengganti->count() > 0)
                    @include('penjualan.retur_penjualan._barang_pengganti', [
                        'returPenjualan' => $returPenjualan,
                    ])
                @endif

                {{-- QC Status (if applicable) --}}
                @if ($returPenjualan->qc_at)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quality Control</h3>
                            <a href="{{ route('penjualan.retur.quality-control-detail', $returPenjualan->id) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-600 font-medium text-xs rounded-lg transition-colors duration-200 border border-primary-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat Detail QC
                            </a>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Status
                                    QC:</span>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $returPenjualan->qc_passed ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-1 {{ $returPenjualan->qc_passed ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $returPenjualan->qc_passed ? 'Lulus QC' : 'Tidak Lulus QC' }}
                                </span>
                            </div>

                            <div class="flex items-center mb-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Diperiksa
                                    oleh:</span>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->qcByUser->name ?? 'Unknown' }}
                                </span>
                            </div>

                            <div class="flex items-center mb-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Tanggal
                                    QC:</span>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($returPenjualan->qc_at)->format('d F Y H:i') }}
                                </span>
                            </div>

                            @if ($returPenjualan->qc_notes)
                                <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                    <span
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan
                                        QC:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $returPenjualan->qc_notes }}
                                    </p>
                                </div>
                            @endif

                            <div class="mt-4 text-right">
                                <a href="{{ route('penjualan.retur.quality-control-detail', $returPenjualan->id) }}"
                                    class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    Lihat detail lengkap
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Log Activity --}}
                @if (isset($logAktivitas) && $logAktivitas->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Aktivitas</h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($logAktivitas as $log)
                                        <li class="py-3">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full 
                                                {{ $log->aktivitas === 'tambah' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                                {{ $log->aktivitas === 'ubah' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                                {{ $log->aktivitas === 'hapus' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                {{ $log->aktivitas === 'detail' ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' : '' }}
                                                {{ $log->aktivitas === 'ajukan_persetujuan' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                                {{ $log->aktivitas === 'setujui' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                                {{ $log->aktivitas === 'tolak' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                {{ $log->aktivitas === 'proses' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                                {{ $log->aktivitas === 'selesai' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                                {{ $log->aktivitas === 'quality_control' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' : '' }}
                                            ">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            @if ($log->aktivitas === 'tambah')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                                </path>
                                                            @elseif($log->aktivitas === 'ubah')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            @elseif($log->aktivitas === 'hapus')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            @elseif($log->aktivitas === 'detail')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            @elseif($log->aktivitas === 'ajukan_persetujuan')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                                                </path>
                                                            @elseif($log->aktivitas === 'setujui')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            @elseif($log->aktivitas === 'tolak')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            @elseif($log->aktivitas === 'proses')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                                                                </path>
                                                            @elseif($log->aktivitas === 'selesai')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            @elseif($log->aktivitas === 'quality_control')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                                </path>
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            @endif
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ ucfirst(str_replace('_', ' ', $log->aktivitas)) }}
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            oleh {{ $log->user->name ?? 'Unknown' }}
                                                        </span>
                                                    </div>
                                                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $log->detail }}
                                                    </p>
                                                    <span class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status Card --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status & Aksi</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            @php
                                $statusColor = match ($returPenjualan->status) {
                                    'draft' => 'gray',
                                    'menunggu_persetujuan' => 'yellow',
                                    'disetujui' => 'green',
                                    'ditolak' => 'red',
                                    'diproses' => 'blue',
                                    'menunggu_barang_pengganti' => 'amber',
                                    'selesai' => 'purple',
                                    default => 'gray',
                                };
                                $statusText = match ($returPenjualan->status) {
                                    'draft' => 'Draft',
                                    'menunggu_persetujuan' => 'Menunggu Persetujuan',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'diproses' => 'Diproses',
                                    'menunggu_barang_pengganti' => 'Menunggu Barang Pengganti',
                                    'selesai' => 'Selesai',
                                    default => 'Unknown',
                                };
                                $statusIcon = match ($returPenjualan->status) {
                                    'draft'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                                    'menunggu_persetujuan'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                    'disetujui'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                    'ditolak'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
                                    'diproses'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                                    'menunggu_barang_pengganti'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>',
                                    'selesai'
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                                    default
                                        => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                };
                            @endphp
                            <div class="flex items-center gap-2 mb-3">
                                <div
                                    class="w-full p-4 rounded-lg 
                                    bg-{{ $statusColor }}-50 text-{{ $statusColor }}-800 
                                    dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-300
                                    border border-{{ $statusColor }}-200 dark:border-{{ $statusColor }}-800/30">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-800/50">
                                                {!! $statusIcon !!}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium">Status Retur</h4>
                                            <p class="text-base font-semibold mt-0.5">{{ $statusText }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if ($returPenjualan->status === 'draft')
                                <a href="{{ route('penjualan.retur.edit', $returPenjualan) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Retur
                                </a>

                                <button type="button" onclick="confirmSubmitApproval()"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ajukan Persetujuan
                                </button>

                                <button type="button" onclick="showDeleteModal()"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus Retur
                                </button>
                            @endif

                            @if ($returPenjualan->status === 'menunggu_persetujuan')
                                <button type="button" onclick="showApprovalModal()"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Setujui Retur
                                </button>

                                <button type="button" onclick="showRejectionModal()"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak Retur
                                </button>
                            @endif

                            @if ($returPenjualan->status === 'disetujui')
                                <a href="{{ route('penjualan.retur.quality-control', $returPenjualan->id) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                    Quality Control
                                </a>

                                <form action="{{ route('penjualan.retur.proses', $returPenjualan) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin memproses retur ini? Status tidak dapat diubah setelah diproses.')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z">
                                            </path>
                                        </svg>
                                        Proses Retur
                                    </button>
                                </form>
                            @endif

                            @if ($returPenjualan->status === 'diproses')
                                <button type="button" @click="$dispatch('open-finish-modal')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Selesaikan Retur
                                </button>
                            @endif

                            @if ($returPenjualan->status === 'selesai')
                                @if ($returPenjualan->nota_kredit_id)
                                    <a href="{{ route('penjualan.nota-kredit.show', $returPenjualan->nota_kredit_id) }}"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat Nota Kredit
                                    </a>
                                @elseif ($returPenjualan->tipe_retur === 'pengembalian_dana')
                                    <a href="{{ route('penjualan.retur.create-credit-note', $returPenjualan->id) }}"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Buat Nota Kredit
                                    </a>
                                @endif
                            @endif

                            @if ($returPenjualan->status === 'menunggu_barang_pengganti')
                                <a href="{{ route('penjualan.retur.kirim-barang-pengganti', $returPenjualan->id) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z">
                                        </path>
                                    </svg>
                                    Kirim Barang Pengganti
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Summary Information --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Jumlah Item:</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">{{ $returPenjualan->details->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Qty:</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($returPenjualan->details->sum('quantity'), 2, ',', '.') }}</span>
                        </div>
                        <hr class="border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-medium text-gray-900 dark:text-white">Total Retur:</span>
                            <span class="text-base font-bold text-primary-600 dark:text-primary-400">Rp
                                {{ number_format($totalRetur, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Timestamps --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Waktu</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($returPenjualan->created_at)->format('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Terakhir diupdate:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($returPenjualan->updated_at)->format('d F Y H:i') }}</p>
                        </div>
                        @if ($returPenjualan->status === 'disetujui' || $returPenjualan->status === 'ditolak')
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $returPenjualan->status === 'disetujui' ? 'Disetujui pada:' : 'Ditolak pada:' }}
                                </span>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    @php
                                        $activity = $returPenjualan->status === 'disetujui' ? 'setujui' : 'tolak';
                                        $activityLog = $logAktivitas->where('aktivitas', $activity)->first();
                                        $activityDate =
                                            $activityLog && $activityLog->created_at
                                                ? \Carbon\Carbon::parse($activityLog->created_at)->format('d F Y H:i')
                                                : '-';
                                    @endphp
                                    {{ $activityDate }}
                                </p>
                            </div>
                        @endif
                        @if ($returPenjualan->status === 'diproses' || $returPenjualan->status === 'selesai')
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Diproses pada:</span>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    @php
                                        $prosesLog = $logAktivitas->where('aktivitas', 'proses')->first();
                                        $prosesDate =
                                            $prosesLog && $prosesLog->created_at
                                                ? \Carbon\Carbon::parse($prosesLog->created_at)->format('d F Y H:i')
                                                : '-';
                                    @endphp
                                    {{ $prosesDate }}
                                </p>
                            </div>
                        @endif
                        @if ($returPenjualan->status === 'selesai')
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Diselesaikan pada:</span>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    @php
                                        $selesaiLog = $logAktivitas->where('aktivitas', 'selesai')->first();
                                        $selesaiDate =
                                            $selesaiLog && $selesaiLog->created_at
                                                ? \Carbon\Carbon::parse($selesaiLog->created_at)->format('d F Y H:i')
                                                : '-';
                                    @endphp
                                    {{ $selesaiDate }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Approval Confirmation Modal --}}
    <div id="submitApprovalModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Pengajuan</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah Anda yakin ingin mengajukan retur penjualan ini
                    untuk persetujuan?</p>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSubmitApprovalModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                    <form action="{{ route('penjualan.retur.submit-approval', $returPenjualan->id) }}"
                        method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">Ya,
                            Ajukan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Modal --}}
    <div id="approvalModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Persetujuan</h3>
                <form action="{{ route('penjualan.retur.approve', $returPenjualan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan
                            (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeApprovalModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div id="rejectionModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Penolakan</h3>
                <form action="{{ route('penjualan.retur.reject', $returPenjualan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan Penolakan
                            <span class="text-red-500">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRejectionModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Selesaikan Retur Modal --}}
    <div x-data="{ open: false }" @open-finish-modal.window="open = true" x-cloak>
        <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    @click="open = false">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                    id="modal-title">
                                    Selesaikan Retur Penjualan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Selesaikan proses retur penjualan dengan menentukan gudang tempat produk
                                        dikembalikan.
                                    </p>
                                </div>
                                <form id="completeForm"
                                    action="{{ route('penjualan.retur.selesai', $returPenjualan->id) }}"
                                    method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="gudang_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                                            Gudang <span class="text-red-500">*</span></label>
                                        <select id="gudang_id" name="gudang_id"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            required>
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}
                                                    ({{ $gudang->kode }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="gudang-error">
                                            Pilih gudang terlebih dahulu
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="submitCompleteForm()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Selesaikan
                        </button>
                        <button type="button" @click="open = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Approval Confirmation Modal --}}
    <div id="submitApprovalModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Pengajuan</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah Anda yakin ingin mengajukan retur penjualan ini
                    untuk persetujuan?</p>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSubmitApprovalModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                    <form action="{{ route('penjualan.retur.submit-approval', $returPenjualan->id) }}"
                        method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">Ya,
                            Ajukan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Modal --}}
    <div id="approvalModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Persetujuan</h3>
                <form action="{{ route('penjualan.retur.approve', $returPenjualan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan
                            (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeApprovalModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div id="rejectionModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Penolakan</h3>
                <form action="{{ route('penjualan.retur.reject', $returPenjualan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan Penolakan
                            <span class="text-red-500">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRejectionModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Selesaikan Retur Modal --}}
    <div x-data="{ open: false }" @open-finish-modal.window="open = true" x-cloak>
        <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    @click="open = false">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                    id="modal-title">
                                    Selesaikan Retur Penjualan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Selesaikan proses retur penjualan dengan menentukan gudang tempat produk
                                        dikembalikan.
                                    </p>
                                </div>
                                <form id="completeForm"
                                    action="{{ route('penjualan.retur.selesai', $returPenjualan->id) }}"
                                    method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="gudang_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                                            Gudang <span class="text-red-500">*</span></label>
                                        <select id="gudang_id" name="gudang_id"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            required>
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}
                                                    ({{ $gudang->kode }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="gudang-error">
                                            Pilih gudang terlebih dahulu
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="submitCompleteForm()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Selesaikan
                        </button>
                        <button type="button" @click="open = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        // Submit Approval Modal
        function confirmSubmitApproval() {
            const modal = document.getElementById('submitApprovalModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSubmitApprovalModal() {
            const modal = document.getElementById('submitApprovalModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Approval Modal
        function showApprovalModal() {
            const modal = document.getElementById('approvalModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeApprovalModal() {
            const modal = document.getElementById('approvalModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Rejection Modal
        function showRejectionModal() {
            const modal = document.getElementById('rejectionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectionModal() {
            const modal = document.getElementById('rejectionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Function to validate and submit the complete form
        function submitCompleteForm() {
            const gudangSelect = document.getElementById('gudang_id');
            const gudangError = document.getElementById('gudang-error');

            if (!gudangSelect.value) {
                // Show error message
                gudangError.classList.remove('hidden');
                return false;
            } else {
                // Hide error message if visible
                gudangError.classList.add('hidden');
                // Submit the form
                document.getElementById('completeForm').submit();
            }
        }

        // Bind all modal events
        document.addEventListener('DOMContentLoaded', function() {
            // Close modals when clicking outside
            const modals = [
                'submitApprovalModal',
                'approvalModal',
                'rejectionModal',
                'deleteModal'
            ];

            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(event) {
                        if (event.target === this) {
                            // If clicking on the background (not the modal content)
                            if (modalId === 'submitApprovalModal') closeSubmitApprovalModal();
                            if (modalId === 'approvalModal') closeApprovalModal();
                            if (modalId === 'rejectionModal') closeRejectionModal();
                            if (modalId === 'deleteModal') closeDeleteModal();
                        }
                    });
                }
            });

            // Bind ESC key to close modals
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    modals.forEach(modalId => {
                        const modal = document.getElementById(modalId);
                        if (modal && !modal.classList.contains('hidden')) {
                            if (modalId === 'submitApprovalModal') closeSubmitApprovalModal();
                            if (modalId === 'approvalModal') closeApprovalModal();
                            if (modalId === 'rejectionModal') closeRejectionModal();
                            if (modalId === 'deleteModal') closeDeleteModal();
                        }
                    });
                }
            });
        });

        // Delete Modal
        function showDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto mt-28">
            <div class="p-6">
                <div class="flex items-start mb-4">
                    <div
                        class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hapus Retur Penjualan</h3>
                        <p class="text-gray-700 dark:text-gray-300 mt-2">
                            Apakah Anda yakin ingin menghapus retur penjualan ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 rounded-lg transition-colors duration-200">Batal</button>
                    <form id="delete-form" action="{{ route('penjualan.retur.destroy', $returPenjualan) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

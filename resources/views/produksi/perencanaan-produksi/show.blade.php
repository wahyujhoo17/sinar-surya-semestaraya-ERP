<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Perencanaan Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Detail'],
]" :currentPage="'Detail Perencanaan Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h1
                    class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-4 md:mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Detail Perencanaan Produksi
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('produksi.perencanaan-produksi.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>

                    @if ($perencanaan->status == 'draft')
                        <a href="{{ route('produksi.perencanaan-produksi.edit', $perencanaan->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <form action="{{ route('produksi.perencanaan-produksi.submit', $perencanaan->id) }}"
                            method="POST" class="inline-block" id="submitForm">
                            @csrf
                            @method('PUT')
                            <button type="button" @click="confirmSubmit()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Ajukan Persetujuan
                            </button>
                        </form>
                    @endif

                    @if ($perencanaan->status == 'menunggu_persetujuan')
                        <form action="{{ route('produksi.perencanaan-produksi.approve', $perencanaan->id) }}"
                            method="POST" class="inline-block" id="approveForm">
                            @csrf
                            @method('PUT')
                            <button type="button" @click="confirmApprove()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui
                            </button>
                        </form>

                        <form action="{{ route('produksi.perencanaan-produksi.reject', $perencanaan->id) }}"
                            method="POST" class="inline-block" id="rejectForm">
                            @csrf
                            @method('PUT')
                            <button type="button" @click="confirmReject()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak
                            </button>
                        </form>
                    @endif

                    @if ($perencanaan->status == 'disetujui' && !$perencanaan->workOrders->count())
                        <a href="{{ route('produksi.work-order.select-product', $perencanaan->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buat Work Order
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Perencanaan</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $perencanaan->nomor }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->tanggal->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                <p class="mt-1 text-sm">
                                    @php
                                        $statusMap = [
                                            'draft' => [
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                'Draft',
                                            ],
                                            'menunggu_persetujuan' => [
                                                'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300',
                                                'Menunggu Persetujuan',
                                            ],
                                            'disetujui' => [
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
                                                'Disetujui',
                                            ],
                                            'ditolak' => [
                                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300',
                                                'Ditolak',
                                            ],
                                            'berjalan' => [
                                                'bg-primary-100 text-primary-800 dark:bg-primary-700 dark:text-primary-300',
                                                'Berjalan',
                                            ],
                                            'selesai' => [
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
                                                'Selesai',
                                            ],
                                            'dibatalkan' => [
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                'Dibatalkan',
                                            ],
                                        ];

                                        $statusClass =
                                            $statusMap[$perencanaan->status][0] ??
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        $statusText = $statusMap[$perencanaan->status][1] ?? 'Unknown';
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">DiuatOleh</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->creator->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                {{ $perencanaan->catatan ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Sales Order</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Sales Order</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if ($perencanaan->salesOrder)
                                        <a href="{{ route('penjualan.sales-order.show', $perencanaan->salesOrder->id) }}"
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                            {{ $perencanaan->salesOrder->nomor }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Sales Order
                                </h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if ($perencanaan->salesOrder && $perencanaan->salesOrder->tanggal)
                                        @if (is_string($perencanaan->salesOrder->tanggal))
                                            {{ $perencanaan->salesOrder->tanggal }}
                                        @else
                                            {{ $perencanaan->salesOrder->tanggal->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->salesOrder && $perencanaan->salesOrder->customer ? $perencanaan->salesOrder->customer->nama ?? $perencanaan->salesOrder->customer->company : '-' }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->gudang ? $perencanaan->gudang->nama : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Produk</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah Order
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Stok Tersedia
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah Produksi
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Keterangan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($perencanaan->detailPerencanaan && $perencanaan->detailPerencanaan->count() > 0)
                                @foreach ($perencanaan->detailPerencanaan as $index => $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $detail->produk->nama ?? '-' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->jumlah }} {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->stok_tersedia }} {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->jumlah_produksi }} {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->keterangan ?: '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6"
                                        class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada detail produk yang tersedia
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($perencanaan->workOrders && $perencanaan->workOrders->count() > 0)
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Work Orders</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nomor
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @if ($perencanaan->workOrders && $perencanaan->workOrders->count() > 0)
                                    @foreach ($perencanaan->workOrders as $index => $wo)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $index + 1 }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $wo->nomor }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                @if(is_string($wo->tanggal))
                                                    {{ $wo->tanggal }}
                                                @else
                                                    {{ $wo->tanggal->format('d/m/Y') }}
                                                @endif
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                @php
                                                    $woStatusMap = [
                                                        'draft' => [
                                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                            'Draft',
                                                        ],
                                                        'menunggu_material' => [
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300',
                                                            'Menunggu Material',
                                                        ],
                                                        'berjalan' => [
                                                            'bg-primary-100 text-primary-800 dark:bg-primary-700 dark:text-primary-300',
                                                            'Berjalan',
                                                        ],
                                                        'selesai' => [
                                                            'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
                                                            'Selesai',
                                                        ],
                                                        'dibatalkan' => [
                                                            'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300',
                                                            'Dibatalkan',
                                                        ],
                                                    ];

                                                    $woStatusClass =
                                                        $woStatusMap[$wo->status][0] ??
                                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                                    $woStatusText = $woStatusMap[$wo->status][1] ?? 'Unknown';
                                                @endphp
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $woStatusClass }}">
                                                    {{ $woStatusText }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('produksi.work-order.show', $wo->id) }}"
                                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada work order yang tersedia
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Prevent multiple modal triggers
        let modalInProgress = false;

        // Helper function to ensure only one modal is triggered at a time
        function triggerModal(config) {
            if (modalInProgress) return;

            modalInProgress = true;

            // Dispatch event after a slight delay
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('alert-modal', {
                    detail: config
                }));

                // Reset flag after a delay
                setTimeout(() => {
                    modalInProgress = false;
                }, 300);
            }, 10);
        }

        function confirmSubmit() {
            triggerModal({
                title: 'Konfirmasi Pengajuan',
                message: 'Yakin ingin mengajukan perencanaan produksi ini?',
                type: 'info',
                confirmText: 'Ya, Ajukan',
                cancelText: 'Batal',
                showCancel: true,
                onConfirm: () => {
                    document.getElementById('submitForm').submit();
                }
            });
        }

        function confirmApprove() {
            triggerModal({
                title: 'Konfirmasi Persetujuan',
                message: 'Yakin ingin menyetujui perencanaan produksi ini?',
                type: 'success',
                confirmText: 'Ya, Setujui',
                cancelText: 'Batal',
                showCancel: true,
                onConfirm: () => {
                    document.getElementById('approveForm').submit();
                }
            });
        }

        function confirmReject() {
            triggerModal({
                title: 'Konfirmasi Penolakan',
                message: 'Yakin ingin menolak perencanaan produksi ini?',
                type: 'error',
                confirmText: 'Ya, Tolak',
                cancelText: 'Batal',
                showCancel: true,
                onConfirm: () => {
                    document.getElementById('rejectForm').submit();
                }
            });
        }
    </script>
</x-app-layout>

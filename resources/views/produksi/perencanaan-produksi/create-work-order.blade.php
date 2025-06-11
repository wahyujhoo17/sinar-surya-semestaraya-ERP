<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Perencanaan Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Buat Work Order'],
]" :currentPage="'Buat Work Order'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Buat Work Order
                </h1>
                <a href="{{ route('produksi.perencanaan-produksi.show', $perencanaan->id) }}"
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

        <form action="{{ route('produksi.work-order.store') }}" method="POST" id="form-work-order">
            @csrf
            <input type="hidden" name="perencanaan_produksi_id" value="{{ $perencanaan->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan
                            Produksi</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Perencanaan
                                    Produksi</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $perencanaan->nomor }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Perencanaan
                                </h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->tanggal->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sales Order</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->salesOrder->nomor ?? 'Tidak ada' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->salesOrder->customer->nama ?? 'Tidak ada' }}</p>
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
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $perencanaan->gudang->nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Work Order</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="nomor"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Work
                                    Order <span class="text-red-500">*</span></label>
                                <input type="text" id="nomor" name="nomor" value="{{ $nomor }}"
                                    readonly
                                    class="bg-gray-100 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                @error('nomor')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                    <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', date('Y-m-d')) }}" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_target"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                    Target <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_target" name="tanggal_target"
                                    value="{{ old('tanggal_target') }}" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                @error('tanggal_target')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
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
                                        Jumlah Produksi
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($perencanaan->details as $index => $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $detail->produk->nama ?? '-' }}
                                            <input type="hidden" name="detail[{{ $index }}][produk_id]"
                                                value="{{ $detail->produk_id }}">
                                            <input type="hidden" name="detail[{{ $index }}][satuan_id]"
                                                value="{{ $detail->satuan_id }}">
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <input type="number"
                                                class="w-full focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                name="detail[{{ $index }}][jumlah]"
                                                value="{{ old('detail.' . $index . '.jumlah', $detail->jumlah_produksi) }}"
                                                min="0.01" step="0.01" required>
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <input type="text"
                                                class="w-full focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                name="detail[{{ $index }}][keterangan]"
                                                value="{{ old('detail.' . $index . '.keterangan', $detail->keterangan) }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-8">
                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('produksi.perencanaan-produksi.show', $perencanaan->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

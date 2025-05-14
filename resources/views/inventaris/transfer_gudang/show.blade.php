<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Header & Quick Actions -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Detail Transfer #{{ $transfer->nomor }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Detail informasi transfer barang dari gudang {{ $transfer->gudangAsal->nama }} ke gudang
                        {{ $transfer->gudangTujuan->nama }}.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                    @if ($transfer->status == 'draft')
                        <a href="{{ route('inventaris.transfer-gudang.edit', $transfer->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Transfer
                        </a>

                        <form action="{{ route('inventaris.transfer-gudang.proses', $transfer->id) }}" method="POST"
                            class="inline-block">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Proses Transfer
                            </button>
                        </form>
                    @elseif($transfer->status == 'diproses')
                        <form action="{{ route('inventaris.transfer-gudang.selesai', $transfer->id) }}" method="POST"
                            class="inline-block">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Selesaikan Transfer
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('inventaris.transfer-gudang.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Transfer Information -->
            <div
                class="col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Transfer</h3>
                </div>
                <div class="p-6">
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                @if ($transfer->status == 'draft')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100">
                                        Draft
                                    </span>
                                @elseif($transfer->status == 'diproses')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100">
                                        Sedang Diproses
                                    </span>
                                @elseif($transfer->status == 'selesai')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                        Selesai
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Transfer</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">{{ $transfer->nomor }}</dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                {{ \Carbon\Carbon::parse($transfer->tanggal)->format('d/m/Y') }}</dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang Asal</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                {{ $transfer->gudangAsal->nama }}</dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang Tujuan</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                {{ $transfer->gudangTujuan->nama }}</dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">{{ $transfer->user->name }}
                            </dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                {{ $transfer->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="py-3 grid grid-cols-3 gap-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                            <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                {{ $transfer->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Right Column - Items & Catatan -->
            <div class="col-span-1 lg:col-span-2">
                <!-- Transfer Items -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Barang</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No.</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Quantity</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Satuan</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($transfer->details as $index => $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            <div class="font-medium">{{ $detail->produk->nama }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->produk->kode }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ number_format($detail->quantity, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $detail->satuan->nama }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $detail->keterangan ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
                    <div
                        class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Catatan</h3>
                    </div>
                    <div class="p-6">
                        <div
                            class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            {{ $transfer->catatan ?: 'Tidak ada catatan' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline / Status History -->
        <div
            class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Pengiriman</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="flex flex-col items-center">
                        <div class="rounded-full bg-green-500 text-white h-8 w-8 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div
                            class="h-16 w-0.5 {{ $transfer->status != 'draft' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Draft</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if (isset($logsByStatus['draft']))
                                Transfer telah dibuat pada
                                {{ \Carbon\Carbon::parse($logsByStatus['draft']->created_at)->format('d M Y, H:i') }}
                                oleh {{ $logsByStatus['draft']->user->name ?? 'System' }}
                            @else
                                Transfer telah dibuat pada
                                {{ \Carbon\Carbon::parse($transfer->created_at)->format('d M Y, H:i') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex flex-col items-center">
                        <div
                            class="rounded-full {{ $transfer->status != 'draft' ? 'bg-green-500 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400' }} h-8 w-8 flex items-center justify-center">
                            @if ($transfer->status != 'draft')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                2
                            @endif
                        </div>
                        <div
                            class="h-16 w-0.5 {{ $transfer->status == 'selesai' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                        </div>
                    </div>
                    <div>
                        <h4
                            class="font-medium {{ $transfer->status != 'draft' ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            Diproses</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if (isset($logsByStatus['diproses']))
                                Diproses pada
                                {{ \Carbon\Carbon::parse($logsByStatus['diproses']->created_at)->format('d M Y, H:i') }}
                                oleh {{ $logsByStatus['diproses']->user->name ?? 'System' }}
                            @elseif($transfer->status != 'draft')
                                Barang sedang dalam proses transfer
                            @else
                                Menunggu untuk diproses
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex flex-col items-center">
                        <div
                            class="rounded-full {{ $transfer->status == 'selesai' ? 'bg-green-500 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400' }} h-8 w-8 flex items-center justify-center">
                            @if ($transfer->status == 'selesai')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                3
                            @endif
                        </div>
                    </div>
                    <div>
                        <h4
                            class="font-medium {{ $transfer->status == 'selesai' ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            Selesai</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if (isset($logsByStatus['selesai']))
                                Diselesaikan pada
                                {{ \Carbon\Carbon::parse($logsByStatus['selesai']->created_at)->format('d M Y, H:i') }}
                                oleh {{ $logsByStatus['selesai']->user->name ?? 'System' }}
                            @elseif($transfer->status == 'selesai')
                                Transfer barang telah selesai
                            @else
                                Menunggu untuk diselesaikan
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

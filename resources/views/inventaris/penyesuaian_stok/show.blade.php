<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Enhanced Overview Header --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <span class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Detail Penyesuaian Stok
                            </span>
                        </h1>
                        @if ($penyesuaian->status == 'draft')
                            <span
                                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Draft
                            </span>
                        @elseif ($penyesuaian->status == 'selesai')
                            <span
                                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Selesai
                            </span>
                        @endif
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Nomor penyesuaian: {{ $penyesuaian->nomor }}
                    </p>
                </div>
                <div class="mt-5 flex xl:ml-4 md:mt-0">
                    <span class="block md:ml-3">
                        <a href="{{ route('inventaris.penyesuaian-stok.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </span>

                    <span class="block ml-3">
                        <a href="{{ route('inventaris.penyesuaian-stok.pdf', $penyesuaian->id) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            Cetak PDF
                        </a>
                    </span>

                    @if ($penyesuaian->status == 'draft')
                        <span class="block ml-3">
                            <a href="{{ route('inventaris.penyesuaian-stok.edit', $penyesuaian->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="space-y-6 md:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <h3
                            class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Penyesuaian
                        </h3>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</dt>
                                    <dd class="text-sm font-semibold text-gray-900 dark:text-white ml-auto">
                                        {{ $penyesuaian->nomor }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ \Carbon\Carbon::parse($penyesuaian->tanggal)->format('d F Y') }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $penyesuaian->gudang->nama }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $penyesuaian->user->name }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="text-sm ml-auto">
                                        @if ($penyesuaian->status == 'draft')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Draft
                                            </span>
                                        @elseif ($penyesuaian->status == 'selesai')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Selesai
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $penyesuaian->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui
                                    </dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $penyesuaian->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <h3
                            class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Catatan
                        </h3>
                        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
                            <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                                {{ $penyesuaian->catatan ?? 'Tidak ada catatan' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($penyesuaian->status == 'draft')
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-5 sm:p-6">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tindakan
                            </h3>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="space-y-3">
                                    <form action="{{ route('inventaris.penyesuaian-stok.proses', $penyesuaian->id) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin memproses penyesuaian stok ini? Tindakan ini akan menyesuaikan stok di sistem sesuai dengan stok fisik yang dicatat dan tidak dapat dibatalkan.')">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center w-full justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Proses Penyesuaian
                                        </button>
                                    </form>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('inventaris.penyesuaian-stok.edit', $penyesuaian->id) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 flex-1 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('inventaris.penyesuaian-stok.destroy', $penyesuaian->id) }}"
                                            method="POST" class="inline-block flex-1"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyesuaian stok ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex w-full items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-red-700 dark:text-red-400 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Item List --}}
            <div class="space-y-6 md:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Rincian Produk
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                Daftar produk yang disesuaikan stoknya
                            </p>
                        </div>
                        <span
                            class="bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-400 text-xs font-medium py-1 px-2 rounded">
                            {{ $penyesuaian->details->count() }} produk
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Stok Tercatat
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Stok Fisik
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Selisih
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($penyesuaian->details as $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            @if ($detail->produk)
                                                <div class="font-medium">{{ $detail->produk->kode }} -
                                                    {{ $detail->produk->nama }}</div>
                                            @else
                                                <div class="font-medium text-gray-400">Produk tidak ditemukan</div>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->stok_tercatat }} {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->stok_fisik }} {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $selisih = $detail->selisih;
                                                $selisihClass =
                                                    $selisih > 0
                                                        ? 'text-green-600 dark:text-green-400'
                                                        : ($selisih < 0
                                                            ? 'text-red-600 dark:text-red-400'
                                                            : 'text-gray-500 dark:text-gray-400');
                                                $selisihPrefix = $selisih > 0 ? '+' : '';
                                            @endphp
                                            <span class="{{ $selisihClass }}">
                                                {{ $selisihPrefix }}{{ $selisih }}
                                                {{ $detail->satuan->nama ?? '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada produk yang disesuaikan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6">
                        <h3
                            class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Ringkasan Penyesuaian
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-5">
                            @php
                                $countPositive = 0;
                                $countNegative = 0;
                                $countNoChange = 0;
                                $totalPositive = 0;
                                $totalNegative = 0;
                                $totalItems = count($penyesuaian->details);
                                $uniqueSatuans = [];

                                foreach ($penyesuaian->details as $detail) {
                                    if ($detail->selisih > 0) {
                                        $countPositive++;
                                        $totalPositive += $detail->selisih;
                                    } elseif ($detail->selisih < 0) {
                                        $countNegative++;
                                        $totalNegative += abs($detail->selisih); // Store as positive number
                                    } else {
                                        $countNoChange++;
                                    }

                                    // Track unique units for display purposes
                                    if (isset($detail->satuan) && $detail->satuan->nama) {
                                        $uniqueSatuans[$detail->satuan->id] = $detail->satuan->nama;
                                    }
                                }

                                // If there are multiple units, we'll just show the counts
$multipleUnits = count($uniqueSatuans) > 1;
$unitName = count($uniqueSatuans) === 1 ? reset($uniqueSatuans) : '';
                            @endphp

                            <div
                                class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-900/50">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-green-800 dark:text-green-400">
                                            Produk yang bertambah
                                        </dt>
                                        <dd class="mt-1 text-3xl font-semibold text-green-900 dark:text-green-500">
                                            {{ $countPositive }} <span class="text-sm font-normal">dari
                                                {{ $totalItems }}</span>
                                        </dd>
                                        @if ($countPositive > 0)
                                            <p class="mt-1 text-sm text-green-700 dark:text-green-400">
                                                Total: +{{ $totalPositive }}
                                                {{ $multipleUnits ? '(berbagai satuan)' : $unitName }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-900/50">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-red-600 dark:text-red-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-red-800 dark:text-red-400">
                                            Produk yang berkurang
                                        </dt>
                                        <dd class="mt-1 text-3xl font-semibold text-red-900 dark:text-red-500">
                                            {{ $countNegative }} <span class="text-sm font-normal">dari
                                                {{ $totalItems }}</span>
                                        </dd>
                                        @if ($countNegative > 0)
                                            <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                                Total: -{{ $totalNegative }}
                                                {{ $multipleUnits ? '(berbagai satuan)' : $unitName }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-700/20 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <dt class="text-sm font-medium text-gray-800 dark:text-gray-300">
                                            Produk sesuai dengan stok sistem
                                        </dt>
                                        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-200">
                                            {{ $countNoChange }} <span class="text-sm font-normal">dari
                                                {{ $totalItems }}</span>
                                        </dd>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            Stok tercatat = stok fisik
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

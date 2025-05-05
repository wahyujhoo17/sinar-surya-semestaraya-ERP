@php
    // Helper function to get status color
    function statusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'diajukan':
                return 'blue';
            case 'disetujui':
                return 'emerald';
            case 'ditolak':
                return 'red';
            case 'selesai':
                return 'purple';
            default:
                return 'primary';
        }
    }

    // Get the badge color and icon for the current status
    $statusBgColor =
        'bg-' .
        statusColor($permintaanPembelian->status) .
        '-100 dark:bg-' .
        statusColor($permintaanPembelian->status) .
        '-900/30';
    $statusTextColor =
        'text-' .
        statusColor($permintaanPembelian->status) .
        '-800 dark:text-' .
        statusColor($permintaanPembelian->status) .
        '-300';
    $statusIconColor =
        'text-' .
        statusColor($permintaanPembelian->status) .
        '-500 dark:text-' .
        statusColor($permintaanPembelian->status) .
        '-400';

    // Calculate total estimated cost
    $totalEstimasi = $permintaanPembelian->details->sum(function ($detail) {
        return $detail->quantity * $detail->harga_estimasi;
    });

    // Define allowed status transitions
    $allowedTransitions = [
        'draft' => ['diajukan'],
        'diajukan' => ['disetujui', 'ditolak'],
        'disetujui' => ['selesai'],
        'ditolak' => ['draft'],
        'selesai' => [],
    ];
@endphp

<x-app-layout>
    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('pembelian.permintaan-pembelian.index') }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Permintaan Pembelian
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $permintaanPembelian->nomor }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header with Status and Action Buttons -->
        <div class="md:flex md:items-center md:justify-between border-b border-gray-200 dark:border-gray-700 pb-5 mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $permintaanPembelian->nomor }}
                    </h1>
                    <div class="flex items-center px-3 py-1 rounded-full {{ $statusBgColor }} {{ $statusTextColor }}">
                        <span
                            class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ statusColor($permintaanPembelian->status) }}-500"></span>
                        <span class="text-sm font-medium capitalize">{{ $permintaanPembelian->status }}</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Dibuat oleh {{ $permintaanPembelian->user->name }} pada
                    {{ \Carbon\Carbon::parse($permintaanPembelian->created_at)->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 mt-4 md:mt-0">
                @if ($permintaanPembelian->status === 'draft')
                    <a href="{{ route('pembelian.permintaan-pembelian.edit', $permintaanPembelian->id) }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>

                    <form action="{{ route('pembelian.permintaan-pembelian.destroy', $permintaanPembelian->id) }}"
                        method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan pembelian ini?');"
                        class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4 text-red-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif

                <a href="{{ route('pembelian.permintaan-pembelian.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Purchase Request Details -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Permintaan</h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                        Permintaan</p>
                                    <p class="text-base text-gray-900 dark:text-white">
                                        {{ $permintaanPembelian->nomor }}</p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                        Permintaan</p>
                                    <p class="text-base text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($permintaanPembelian->tanggal)->format('d M Y') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</p>
                                    <div
                                        class="inline-flex items-center px-2.5 py-1 rounded-full {{ $statusBgColor }} {{ $statusTextColor }}">
                                        <span
                                            class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ statusColor($permintaanPembelian->status) }}-500"></span>
                                        <span
                                            class="text-xs font-medium capitalize">{{ $permintaanPembelian->status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pemohon</p>
                                    <p class="text-base text-gray-900 dark:text-white">
                                        {{ $permintaanPembelian->user->name }}</p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Departemen</p>
                                    <p class="text-base text-gray-900 dark:text-white">
                                        {{ $permintaanPembelian->department->nama }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Estimasi Biaya
                                        Total</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-white">Rp
                                        {{ number_format($totalEstimasi, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($permintaanPembelian->catatan)
                            <div class="mt-6">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</p>
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-gray-700 dark:text-gray-300">
                                    {{ $permintaanPembelian->catatan }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Item List -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mt-6">
                    <div
                        class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Item</h2>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $permintaanPembelian->details->count() }}
                            item</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Item</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Spesifikasi</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Harga Est.</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($permintaanPembelian->details as $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->nama_item }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->produk->kode ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white line-clamp-2">
                                                @if ($detail->produk)
                                                    <div class="space-y-1">
                                                        @if ($detail->produk->ukuran)
                                                            <div class="flex items-start">
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 w-16">Ukuran:</span>
                                                                <span
                                                                    class="text-sm text-gray-800 dark:text-gray-300">{{ $detail->produk->ukuran }}</span>
                                                            </div>
                                                        @endif
                                                        @if ($detail->produk->merek)
                                                            <div class="flex items-start">
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 w-16">Merek:</span>
                                                                <span
                                                                    class="text-sm text-gray-800 dark:text-gray-300">{{ $detail->produk->merek }}</span>
                                                            </div>
                                                        @endif
                                                        @if ($detail->deskripsi)
                                                            <div class="flex items-start">
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 w-16">Catatan:</span>
                                                                <span
                                                                    class="text-sm text-gray-800 dark:text-gray-300">{{ $detail->deskripsi }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $detail->deskripsi ?? '-' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ number_format($detail->quantity, 2, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->satuan->nama }}</div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                            Rp {{ number_format($detail->harga_estimasi, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white">
                                            Rp
                                            {{ number_format($detail->quantity * $detail->harga_estimasi, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Total Estimasi:</td>
                                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($totalEstimasi, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column - Status Updates & Actions -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Status & Tindakan</h2>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center {{ $statusBgColor }} {{ $statusIconColor }} mr-4">
                                @switch($permintaanPembelian->status)
                                    @case('draft')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    @break

                                    @case('diajukan')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    @break

                                    @case('disetujui')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @break

                                    @case('ditolak')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @break

                                    @case('selesai')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @break

                                    @default
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                @endswitch
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                    {{ $permintaanPembelian->status }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui:
                                    {{ $permintaanPembelian->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Status Actions -->
                        @if (count($allowedTransitions[$permintaanPembelian->status]) > 0)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Ubah Status:</p>
                                <div class="space-y-3">
                                    @if (in_array('diajukan', $allowedTransitions[$permintaanPembelian->status]))
                                        <form
                                            action="{{ route('pembelian.permintaan-pembelian.change-status', $permintaanPembelian->id) }}"
                                            method="POST" class="w-full status-change-form" data-action="diajukan"
                                            data-confirm-message="Apakah Anda yakin ingin mengajukan permintaan pembelian ini?"
                                            data-confirm-title="Ajukan Permintaan" data-confirm-icon="info"
                                            data-confirm-color="blue">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="diajukan">
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                                </svg>
                                                Ajukan Permintaan
                                            </button>
                                        </form>
                                    @endif

                                    @if (in_array('disetujui', $allowedTransitions[$permintaanPembelian->status]))
                                        <form
                                            action="{{ route('pembelian.permintaan-pembelian.change-status', $permintaanPembelian->id) }}"
                                            method="POST" class="w-full status-change-form" data-action="disetujui"
                                            data-confirm-message="Apakah Anda yakin ingin menyetujui permintaan pembelian ini?"
                                            data-confirm-title="Setujui Permintaan" data-confirm-icon="success"
                                            data-confirm-color="emerald">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Setujui Permintaan
                                            </button>
                                        </form>
                                    @endif

                                    @if (in_array('ditolak', $allowedTransitions[$permintaanPembelian->status]))
                                        <form
                                            action="{{ route('pembelian.permintaan-pembelian.change-status', $permintaanPembelian->id) }}"
                                            method="POST" class="w-full status-change-form" data-action="ditolak"
                                            data-confirm-message="Apakah Anda yakin ingin menolak permintaan pembelian ini?"
                                            data-confirm-title="Tolak Permintaan" data-confirm-icon="warning"
                                            data-confirm-color="red">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 focus:bg-red-700 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak Permintaan
                                            </button>
                                        </form>
                                    @endif

                                    @if (in_array('draft', $allowedTransitions[$permintaanPembelian->status]))
                                        <form
                                            action="{{ route('pembelian.permintaan-pembelian.change-status', $permintaanPembelian->id) }}"
                                            method="POST" class="w-full status-change-form" data-action="draft"
                                            data-confirm-message="Apakah Anda yakin ingin mengembalikan permintaan pembelian ini ke status draft?"
                                            data-confirm-title="Kembalikan ke Draft" data-confirm-icon="question"
                                            data-confirm-color="gray">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="draft">
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 focus:bg-gray-700 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-sm transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Kembalikan ke Draft
                                            </button>
                                        </form>
                                    @endif

                                    @if (in_array('selesai', $allowedTransitions[$permintaanPembelian->status]))
                                        <form
                                            action="{{ route('pembelian.permintaan-pembelian.change-status', $permintaanPembelian->id) }}"
                                            method="POST" class="w-full status-change-form" data-action="selesai"
                                            data-confirm-message="Apakah Anda yakin ingin menandai permintaan pembelian ini sebagai selesai?"
                                            data-confirm-title="Tandai Selesai" data-confirm-icon="info"
                                            data-confirm-color="purple">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 focus:bg-purple-700 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-sm transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Status Flow Timeline -->
                        <div class="mt-8">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Alur Status</h3>
                            <ol class="relative border-l border-gray-300 dark:border-gray-700 ml-3">
                                <li class="mb-6 ml-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 {{ $permintaanPembelian->status == 'draft' || $permintaanPembelian->status == 'diajukan' || $permintaanPembelian->status == 'disetujui' || $permintaanPembelian->status == 'ditolak' || $permintaanPembelian->status == 'selesai' ? 'bg-gray-200 dark:bg-gray-700 ring-4 ring-white dark:ring-gray-800' : 'bg-gray-100 dark:bg-gray-800' }}">
                                        <svg class="w-3 h-3 {{ $permintaanPembelian->status == 'draft' || $permintaanPembelian->status == 'diajukan' || $permintaanPembelian->status == 'disetujui' || $permintaanPembelian->status == 'ditolak' || $permintaanPembelian->status == 'selesai' ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400' }}"
                                            fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Draft</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Permintaan dibuat namun belum
                                        diajukan</p>
                                </li>

                                <li class="mb-6 ml-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 {{ $permintaanPembelian->status == 'diajukan' || $permintaanPembelian->status == 'disetujui' || $permintaanPembelian->status == 'ditolak' || $permintaanPembelian->status == 'selesai' ? 'bg-blue-200 dark:bg-blue-900 ring-4 ring-white dark:ring-gray-800' : 'bg-gray-100 dark:bg-gray-800' }}">
                                        <svg class="w-3 h-3 {{ $permintaanPembelian->status == 'diajukan' || $permintaanPembelian->status == 'disetujui' || $permintaanPembelian->status == 'ditolak' || $permintaanPembelian->status == 'selesai' ? 'text-blue-600 dark:text-blue-300' : 'text-gray-400' }}"
                                            fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Diajukan</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Permintaan diajukan untuk
                                        persetujuan</p>
                                </li>

                                <li class="mb-6 ml-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 {{ $permintaanPembelian->status == 'disetujui' || $permintaanPembelian->status == 'selesai' ? 'bg-emerald-200 dark:bg-emerald-900 ring-4 ring-white dark:ring-gray-800' : ($permintaanPembelian->status == 'ditolak' ? 'bg-red-200 dark:bg-red-900 ring-4 ring-white dark:ring-gray-800' : 'bg-gray-100 dark:bg-gray-800') }}">
                                        @if ($permintaanPembelian->status == 'ditolak')
                                            <svg class="w-3 h-3 text-red-600 dark:text-red-300" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 {{ $permintaanPembelian->status == 'disetujui' || $permintaanPembelian->status == 'selesai' ? 'text-emerald-600 dark:text-emerald-300' : 'text-gray-400' }}"
                                                fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </span>
                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                        {{ $permintaanPembelian->status == 'ditolak' ? 'Ditolak' : 'Disetujui' }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $permintaanPembelian->status == 'ditolak' ? 'Permintaan ditolak oleh persetujuan' : 'Permintaan disetujui dan siap diproses' }}
                                    </p>
                                </li>

                                <li class="ml-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 {{ $permintaanPembelian->status == 'selesai' ? 'bg-purple-200 dark:bg-purple-900 ring-4 ring-white dark:ring-gray-800' : 'bg-gray-100 dark:bg-gray-800' }}">
                                        <svg class="w-3 h-3 {{ $permintaanPembelian->status == 'selesai' ? 'text-purple-600 dark:text-purple-300' : 'text-gray-400' }}"
                                            fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Selesai</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Permintaan telah selesai
                                        diproses</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Optional: If you want to add a section for related purchase orders -->
                @if ($permintaanPembelian->purchaseOrders && $permintaanPembelian->purchaseOrders->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mt-6">
                        <div
                            class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Purchase Order Terkait</h2>
                        </div>

                        <div class="p-6">
                            <ul class="space-y-3">
                                @foreach ($permintaanPembelian->purchaseOrders as $po)
                                    <li>
                                        <a href="#"
                                            class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $po->nomor }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</p>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div id="modal-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity opacity-0">
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                id="modal-panel">
                <div class="relative bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div id="modal-icon-container"
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icon will be inserted here -->
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="modal-message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Lanjutkan
                    </button>
                    <button type="button" id="cancelBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:focus:ring-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Replace the existing script with this new one -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modal = document.getElementById('confirmationModal');
            const modalBackdrop = document.getElementById('modal-backdrop');
            const modalPanel = document.getElementById('modal-panel');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const confirmBtn = document.getElementById('confirmBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const modalIconContainer = document.getElementById('modal-icon-container');

            // Status change forms
            const statusChangeForms = document.querySelectorAll('.status-change-form');

            // Showing modal with animation
            function showModal() {
                modal.classList.remove('hidden');
                // Start animation after a small delay
                setTimeout(() => {
                    modalBackdrop.classList.remove('opacity-0');
                    modalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                    modalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
                }, 10);
            }

            // Hiding modal with animation
            function hideModal() {
                modalBackdrop.classList.add('opacity-0');
                modalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                modalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');

                // Remove from DOM after animation completes
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // Icons for different confirmation types
            const icons = {
                success: `<svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                         </svg>`,
                warning: `<svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                         </svg>`,
                info: `<svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                      </svg>`,
                question: `<svg class="h-6 w-6 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                          </svg>`
            };

            // Confirm button colors
            const btnColors = {
                blue: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
                emerald: 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
                red: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
                gray: 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
                purple: 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500'
            };

            // Icon container background colors
            const iconContainerColors = {
                blue: 'bg-blue-100 dark:bg-blue-900/30',
                emerald: 'bg-emerald-100 dark:bg-emerald-900/30',
                red: 'bg-red-100 dark:bg-red-900/30',
                gray: 'bg-gray-100 dark:bg-gray-900/30',
                purple: 'bg-purple-100 dark:bg-purple-900/30'
            };

            let currentForm = null;

            // Event listeners for all status change forms
            statusChangeForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    currentForm = this;
                    const action = this.dataset.action;
                    const confirmMessage = this.dataset.confirmMessage;
                    const confirmTitle = this.dataset.confirmTitle;
                    const iconType = this.dataset.confirmIcon || 'question';
                    const colorType = this.dataset.confirmColor || 'blue';

                    // Set modal content
                    modalTitle.textContent = confirmTitle;
                    modalMessage.textContent = confirmMessage;

                    // Set icon
                    modalIconContainer.innerHTML = icons[iconType];

                    // Remove any existing classes and add the new color class
                    modalIconContainer.className =
                        'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10 ' +
                        iconContainerColors[colorType];

                    // Set button color
                    confirmBtn.className =
                        `w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white ${btnColors[colorType]} focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm`;

                    // Show modal
                    showModal();
                });
            });

            // Confirm button action
            confirmBtn.addEventListener('click', function() {
                hideModal();
                if (currentForm) {
                    setTimeout(() => {
                        currentForm.submit();
                    }, 300);
                }
            });

            // Cancel button action and clicking outside the modal
            cancelBtn.addEventListener('click', hideModal);
            modalBackdrop.addEventListener('click', hideModal);

            // Prevent propagation when clicking on the modal panel
            modalPanel.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });
        });
    </script>
</x-app-layout>

@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <a href="{{ route('produksi.pengambilan-bahan-baku.index') }}"
                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pengambilan Bahan Baku
                                </h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $pengambilan->nomor }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if (auth()->user()->hasPermission('work_order.view'))
                                <a href="{{ route('produksi.pengambilan-bahan-baku.pdf', $pengambilan->id) }}"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Export PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2">
                    <!-- Pengambilan Info -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Pengambilan</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                                        {{ $pengambilan->nomor }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $pengambilan->tanggal ? $pengambilan->tanggal->format('d F Y') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $pengambilan->workOrder->nomor ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $pengambilan->gudang->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $pengambilan->status == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300' }}">
                                            {{ ucfirst($pengambilan->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $pengambilan->creator->name ?? '-' }}</dd>
                                </div>
                            </dl>
                            @if ($pengambilan->catatan)
                                <div class="mt-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $pengambilan->catatan }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Items -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Bahan Baku</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Bahan Baku</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Diminta</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Diambil</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Satuan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($pengambilan->detail as $detail)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $detail->produk->nama }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $detail->produk->kode }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">
                                                {{ number_format($detail->jumlah_diminta, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="text-sm font-medium {{ $detail->jumlah_diambil == $detail->jumlah_diminta ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                                                    {{ number_format($detail->jumlah_diambil, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">
                                                {{ $detail->satuan->nama ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                                Tidak ada detail bahan baku
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Work Order Info -->
                <div class="lg:col-span-1">
                    @if ($pengambilan->workOrder)
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Work Order Terkait</h2>
                            </div>
                            <div class="p-6">
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor WO</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $pengambilan->workOrder->nomor }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                                        <dd class="mt-1">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $pengambilan->workOrder->produk->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $pengambilan->workOrder->produk->kode ?? '' }}</div>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kuantitas</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ number_format($pengambilan->workOrder->quantity ?? 0, 0, ',', '.') }}
                                            {{ $pengambilan->workOrder->satuan->nama ?? '' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status WO</dt>
                                        <dd class="mt-1">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $pengambilan->workOrder->status == 'completed'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300'
                                                    : ($pengambilan->workOrder->status == 'in_progress'
                                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300'
                                                        : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                {{ ucwords(str_replace('_', ' ', $pengambilan->workOrder->status ?? '')) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>

                                @if (auth()->user()->hasPermission('work_order.view'))
                                    <div class="mt-6">
                                        <a href="{{ route('produksi.work-order.show', $pengambilan->workOrder->id) }}"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-center inline-block">
                                            Lihat Work Order
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

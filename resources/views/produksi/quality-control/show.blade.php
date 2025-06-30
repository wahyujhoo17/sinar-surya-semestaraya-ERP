@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <a href="{{ route('produksi.quality-control.index') }}"
                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Quality Control</h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $qc->nomor ?? 'QC-' . $qc->id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if (auth()->user()->hasPermission('quality_control.print'))
                                <a href="{{ route('produksi.quality-control.pdf', $qc->id) }}"
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
                            @if (auth()->user()->hasPermission('quality_control.approve'))
                                @if ($qc->status != 'semua_lolos')
                                    <button onclick="approveQC({{ $qc->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                @endif
                                @if ($qc->status != 'semua_gagal')
                                    <button onclick="rejectQC({{ $qc->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main QC Info -->
                <div class="lg:col-span-2">
                    <!-- QC General Info -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Quality Control</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor QC</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                                        {{ $qc->nomor ?? 'QC-' . $qc->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Inspeksi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $qc->tanggal ? $qc->tanggal->format('d F Y') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $qc->workOrder->nomor ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Inspector</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $qc->inspector->name ?? '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $qc->status == 'semua_lolos'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300'
                                                : ($qc->status == 'sebagian_gagal'
                                                    ? 'bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-300'
                                                    : 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300') }}">
                                            {{ ucwords(str_replace('_', ' ', $qc->status ?? '')) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                            @if ($qc->catatan)
                                <div class="mt-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $qc->catatan }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- QC Results Summary -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Hasil Quality Control</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-6">
                                <!-- Jumlah Lolos -->
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300">Jumlah
                                            Lolos</span>
                                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ number_format($qc->jumlah_lolos ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                        {{ $qc->workOrder->satuan->nama ?? '' }}</div>
                                    @php
                                        $total = ($qc->jumlah_lolos ?? 0) + ($qc->jumlah_gagal ?? 0);
                                        $percentagePass = $total > 0 ? (($qc->jumlah_lolos ?? 0) / $total) * 100 : 0;
                                    @endphp
                                    <div class="mt-2 h-2 bg-green-200 dark:bg-green-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full"
                                            style="width: {{ $percentagePass }}%"></div>
                                    </div>
                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                        {{ number_format($percentagePass, 1) }}% dari total
                                    </div>
                                </div>

                                <!-- Jumlah Gagal -->
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-red-700 dark:text-red-300">Jumlah Gagal</span>
                                        <span class="text-2xl font-bold text-red-600 dark:text-red-400">
                                            {{ number_format($qc->jumlah_gagal ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        {{ $qc->workOrder->satuan->nama ?? '' }}</div>
                                    @php
                                        $percentageFail = $total > 0 ? (($qc->jumlah_gagal ?? 0) / $total) * 100 : 0;
                                    @endphp
                                    <div class="mt-2 h-2 bg-red-200 dark:bg-red-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 rounded-full" style="width: {{ $percentageFail }}%">
                                        </div>
                                    </div>
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        {{ number_format($percentageFail, 1) }}% dari total
                                    </div>
                                </div>
                            </div>

                            <!-- Total Summary -->
                            <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total
                                        Diperiksa</span>
                                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($total, 0, ',', '.') }} {{ $qc->workOrder->satuan->nama ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QC Detail Items (if exists) -->
                    @if ($qc->detail && $qc->detail->count() > 0)
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Parameter QC</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Parameter</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Standar</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Hasil</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($qc->detail as $detail)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $detail->parameter }}
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">
                                                    {{ $detail->standar }}
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">
                                                    {{ $detail->hasil }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded-full
                                                        {{ $detail->status == 'pass' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' }}">
                                                        {{ ucfirst($detail->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                    {{ $detail->keterangan ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Work Order Info -->
                <div class="lg:col-span-1">
                    @if ($qc->workOrder)
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Work Order Terkait</h2>
                            </div>
                            <div class="p-6">
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor WO</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $qc->workOrder->nomor }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                                        <dd class="mt-1">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $qc->workOrder->produk->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $qc->workOrder->produk->kode ?? '' }}</div>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Target Produksi
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ number_format($qc->workOrder->quantity ?? 0, 0, ',', '.') }}
                                            {{ $qc->workOrder->satuan->nama ?? '' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status WO</dt>
                                        <dd class="mt-1">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $qc->workOrder->status == 'completed'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300'
                                                    : ($qc->workOrder->status == 'in_progress'
                                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300'
                                                        : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                {{ ucwords(str_replace('_', ' ', $qc->workOrder->status ?? '')) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $qc->workOrder->tanggal_mulai ? $qc->workOrder->tanggal_mulai->format('d/m/Y') : '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Target Selesai
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $qc->workOrder->tanggal_selesai ? $qc->workOrder->tanggal_selesai->format('d/m/Y') : '-' }}
                                        </dd>
                                    </div>
                                </dl>

                                @if (auth()->user()->hasPermission('work_order.view'))
                                    <div class="mt-6">
                                        <a href="{{ route('produksi.work-order.show', $qc->workOrder->id) }}"
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

@push('scripts')
    <script>
        function approveQC(id) {
            if (confirm('Apakah Anda yakin ingin menyetujui quality control ini?')) {
                fetch(`/produksi/quality-control/${id}/approve`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem');
                    });
            }
        }

        function rejectQC(id) {
            if (confirm('Apakah Anda yakin ingin menolak quality control ini?')) {
                fetch(`/produksi/quality-control/${id}/reject`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem');
                    });
            }
        }
    </script>
@endpush

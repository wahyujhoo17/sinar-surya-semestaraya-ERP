@php
    use App\Models\LogAktivitas;

    if (!function_exists('pelangganActivityColor')) {
        function pelangganActivityColor($activity)
        {
            switch ($activity) {
                case 'create': return 'green';
                case 'update': return 'blue';
                case 'delete': return 'red';
                case 'change_status': return 'amber';
                default: return 'gray';
            }
        }
    }

    if (!function_exists('pelangganActivityIcon')) {
        function pelangganActivityIcon($activity)
        {
            switch ($activity) {
                case 'create':
                    return '<svg class="h-4 w-4 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>';
                case 'update':
                    return '<svg class="h-4 w-4 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                case 'delete':
                    return '<svg class="h-4 w-4 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                default:
                    return '<svg class="h-4 w-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            }
        }
    }

    $totalQuotation = $customer->quotations->count();
    $totalSalesOrder = $customer->salesOrders->count();
    $totalNilaiPesanan = $customer->salesOrders->sum('total');
    // Hitung total terbayar: lunas = nilai penuh (total), non-lunas = total_pembayaran
    $totalTerbayar = $customer->salesOrders->sum(function ($so) {
        return $so->status_pembayaran === 'lunas' ? ($so->total ?? 0) : ($so->total_pembayaran ?? 0);
    });
    $totalPembayaran = $totalTerbayar; // alias untuk kompatibilitas
    $totalLunas = $customer->salesOrders->where('status_pembayaran', 'lunas')->count();
    $totalPending = $customer->salesOrders->whereNotIn('status_pembayaran', ['lunas'])->count();
    $persentaseBayar = $totalNilaiPesanan > 0 ? ($totalTerbayar / $totalNilaiPesanan) * 100 : 0;
    $salesName = ($customer->sales_id && $customer->sales) ? $customer->sales->name : ($customer->sales_name ?: null);
@endphp

<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="{ activeTab: 'details' }">

        {{-- ===== HEADER CARD ===== --}}
        <div class="mb-6">
            {{-- Gradient Banner --}}
            <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-blue-700 px-6 pt-6 pb-20 rounded-2xl shadow-lg">
                <div class="absolute inset-0 rounded-2xl opacity-10"
                     style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>

                <div class="relative flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-5">
                        {{-- Avatar --}}
                        <div class="h-16 w-16 flex-shrink-0 flex items-center justify-center rounded-2xl bg-white/15 backdrop-blur ring-2 ring-white/25 text-white text-2xl font-bold uppercase">
                            {{ substr($customer->nama ?? $customer->kode, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-white leading-tight">{{ $customer->nama ?: $customer->kode }}</h1>
                                @if ($customer->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-400/20 text-green-100 ring-1 ring-inset ring-green-300/40">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-300 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-400"></span>
                                        </span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-400/20 text-red-100 ring-1 ring-inset ring-red-300/40">
                                        <svg class="h-1.5 w-1.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-white/70">
                                <span class="font-mono bg-white/10 px-1.5 py-0.5 rounded text-white/90">{{ $customer->kode }}</span>
                                @if ($customer->tipe) <span class="ml-2 text-white/50">•</span> <span class="ml-2">{{ $customer->tipe }}</span> @endif
                                @if ($customer->company) <span class="ml-2 text-white/50">•</span> <span class="ml-2">{{ $customer->company }}</span> @endif
                            </p>
                            {{-- Quick contact --}}
                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5">
                                @if ($customer->telepon)
                                    <a href="tel:{{ $customer->telepon }}" class="inline-flex items-center text-sm text-white/80 hover:text-white transition-colors">
                                        <svg class="h-3.5 w-3.5 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        {{ $customer->telepon }}
                                    </a>
                                @endif
                                @if ($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="inline-flex items-center text-sm text-white/80 hover:text-white transition-colors">
                                        <svg class="h-3.5 w-3.5 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ $customer->email }}
                                    </a>
                                @endif
                                @if ($salesName)
                                    <span class="inline-flex items-center text-sm text-white/80">
                                        <svg class="h-3.5 w-3.5 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        Sales: {{ $salesName }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button @click="window.dispatchEvent(new CustomEvent('open-pelanggan-modal', {detail: {mode: 'edit', customer: {{ json_encode($customer) }} }}))"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 hover:bg-white/25 backdrop-blur text-white text-sm font-medium rounded-xl ring-1 ring-inset ring-white/30 transition-all duration-150">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            Edit
                        </button>
                        <a href="{{ route('master.pelanggan.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 rounded-xl text-sm font-medium text-gray-700 shadow-sm transition-all duration-150">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats Strip — overlapping the banner --}}
            <div class="relative -mt-10 px-4 grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Quotation</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $totalQuotation }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Penawaran dibuat</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sales Order</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSalesOrder }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $totalLunas }} lunas &bull; {{ $totalPending }} pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nilai Pesanan</p>
                    <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white leading-tight">Rp {{ number_format($totalNilaiPesanan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Total akumulasi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pembayaran</p>
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded {{ $persentaseBayar >= 100 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($persentaseBayar > 50 ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400') }}">
                            {{ number_format($persentaseBayar, 0) }}%
                        </span>
                    </div>
                    <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white leading-tight">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                    <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $persentaseBayar >= 100 ? 'bg-green-500' : ($persentaseBayar > 50 ? 'bg-blue-500' : 'bg-amber-500') }}"
                             style="width: {{ min($persentaseBayar, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TABS ===== --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            {{-- Tab Bar --}}
            <div class="border-b border-gray-200 dark:border-gray-700 px-4">
                <nav class="-mb-px flex gap-1 overflow-x-auto">
                    <button @click="activeTab = 'details'"
                        :class="activeTab === 'details'
                            ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700/50'"
                        class="inline-flex items-center gap-2 whitespace-nowrap py-3.5 px-4 border-b-2 font-medium text-sm rounded-t-lg transition-colors focus:outline-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Detail Pelanggan
                    </button>
                    <button @click="activeTab = 'transactions'"
                        :class="activeTab === 'transactions'
                            ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700/50'"
                        class="inline-flex items-center gap-2 whitespace-nowrap py-3.5 px-4 border-b-2 font-medium text-sm rounded-t-lg transition-colors focus:outline-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        Riwayat Transaksi
                        @if($totalSalesOrder + $totalQuotation > 0)
                            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $totalSalesOrder + $totalQuotation }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'logs'"
                        :class="activeTab === 'logs'
                            ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700/50'"
                        class="inline-flex items-center gap-2 whitespace-nowrap py-3.5 px-4 border-b-2 font-medium text-sm rounded-t-lg transition-colors focus:outline-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat Perubahan
                        @if($logs->isNotEmpty())
                            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">{{ $logs->count() }}</span>
                        @endif
                    </button>
                </nav>
            </div>

            {{-- ===== TAB: DETAIL ===== --}}
            <div x-show="activeTab === 'details'" class="p-6 animate-fade-in">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Informasi Kontak --}}
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                            <div class="h-7 w-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Informasi Kontak</h3>
                        </div>
                        <dl class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @php
                                $contactRows = [
                                    ['label' => 'Telepon', 'value' => $customer->telepon, 'link' => $customer->telepon ? 'tel:'.$customer->telepon : null],
                                    ['label' => 'Email', 'value' => $customer->email, 'link' => $customer->email ? 'mailto:'.$customer->email : null],
                                    ['label' => 'Tipe', 'value' => $customer->tipe, 'link' => null],
                                    ['label' => 'Kontak Person', 'value' => $customer->kontak_person, 'link' => null],
                                    ['label' => 'No. HP Kontak', 'value' => $customer->no_hp_kontak, 'link' => $customer->no_hp_kontak ? 'tel:'.$customer->no_hp_kontak : null],
                                    ['label' => 'Jabatan Kontak', 'value' => $customer->jabatan_kontak ?? null, 'link' => null],
                                ];
                            @endphp
                            @foreach ($contactRows as $row)
                                <div class="flex items-start justify-between gap-3 px-4 py-3">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 flex-shrink-0 pt-0.5">{{ $row['label'] }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white text-right font-medium break-all">
                                        @if($row['value'] && $row['link'])
                                            <a href="{{ $row['link'] }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $row['value'] }}</a>
                                        @elseif($row['value'])
                                            {{ $row['value'] }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs font-normal italic">Tidak ada</span>
                                        @endif
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>

                    {{-- Alamat --}}
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                            <div class="h-7 w-7 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Alamat</h3>
                        </div>
                        <div class="px-4 py-3">
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                @if ($customer->jalan || $customer->kota || $customer->provinsi || $customer->kode_pos || $customer->negara)
                                    {{ collect([$customer->jalan, $customer->kota, $customer->provinsi, $customer->kode_pos, $customer->negara])->filter()->implode(', ') }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic text-xs">Tidak ada alamat tersimpan</span>
                                @endif
                            </p>
                        </div>
                        <dl class="divide-y divide-gray-100 dark:divide-gray-700/60 border-t border-gray-100 dark:border-gray-700">
                            @foreach (['Kota' => $customer->kota, 'Provinsi' => $customer->provinsi, 'Kode Pos' => $customer->kode_pos, 'Negara' => $customer->negara] as $label => $value)
                                <div class="flex items-center justify-between gap-3 px-4 py-2.5">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $value ?: '-' }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        {{-- Alamat Pengiriman --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Alamat Pengiriman</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-700/40 rounded-lg p-3">
                                {!! nl2br(e($customer->alamat_pengiriman ?: 'Tidak ada alamat pengiriman.')) !!}
                            </p>
                        </div>
                    </div>

                    {{-- Detail Bisnis & Catatan --}}
                    <div class="flex flex-col gap-5">
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                                <div class="h-7 w-7 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Detail Bisnis</h3>
                            </div>
                            <dl class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                @php
                                    $businessRows = [
                                        ['label' => 'Perusahaan', 'value' => $customer->company],
                                        ['label' => 'Grup', 'value' => $customer->group ?? null],
                                        ['label' => 'Industri', 'value' => $customer->industri ?? null],
                                        ['label' => 'NPWP', 'value' => $customer->npwp],
                                        ['label' => 'Sales Rep.', 'value' => $salesName],
                                    ];
                                @endphp
                                @foreach ($businessRows as $row)
                                    <div class="flex items-start justify-between gap-3 px-4 py-2.5">
                                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $row['label'] }}</dt>
                                        <dd class="text-sm font-medium text-gray-900 dark:text-white text-right break-all">
                                            {{ $row['value'] ?: '-' }}
                                        </dd>
                                    </div>
                                @endforeach
                                <div class="flex items-center justify-between gap-3 px-4 py-2.5">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd>
                                        @if ($customer->is_active)
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Catatan --}}
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex-1">
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                                <div class="h-7 w-7 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Catatan</h3>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {!! nl2br(e($customer->catatan ?: 'Tidak ada catatan.')) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: TRANSAKSI ===== --}}
            <div x-show="activeTab === 'transactions'" class="p-6 animate-fade-in" x-cloak>

                {{-- Quotations --}}
                <div class="mb-6 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-7 w-7 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Quotation</h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $totalQuotation }}</span>
                        </div>
                    </div>
                    @if ($customer->quotations->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50/70 dark:bg-gray-700/30">
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Quotation</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                                    @foreach ($customer->quotations as $quotation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white whitespace-nowrap">{{ $quotation->nomor }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($quotation->tanggal_quotation)->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($quotation->total ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">{{ $quotation->status ?? 'Draft' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                <a href="{{ route('penjualan.quotation.show', $quotation->id) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 hover:underline">Detail →</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400 dark:text-gray-500">
                            <svg class="h-10 w-10 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Belum ada quotation</p>
                        </div>
                    @endif
                </div>

                {{-- Sales Orders --}}
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-7 w-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Sales Order</h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $totalSalesOrder }}</span>
                        </div>
                    </div>
                    @if ($customer->salesOrders->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50/70 dark:bg-gray-700/30">
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. SO</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Terbayar</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                                    @foreach ($customer->salesOrders as $so)
                                        @php
                                            $terbayar = $so->status_pembayaran === 'lunas' ? ($so->total ?? 0) : ($so->total_pembayaran ?? 0);
                                            $statusClass = match($so->status_pembayaran) {
                                                'lunas' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'sebagian' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                default => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            };
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white whitespace-nowrap">{{ $so->nomor }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($so->tanggal_so)->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($so->total ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($terbayar, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusClass }} capitalize">{{ $so->status_pembayaran ?? 'Open' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                <a href="{{ route('penjualan.sales-order.show', $so->id) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 hover:underline">Detail →</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Ringkasan Footer --}}
                        <div class="px-4 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Nilai</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">Rp {{ number_format($customer->salesOrders->sum('total'), 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Terbayar</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @if($totalLunas > 0) <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">{{ $totalLunas }} Lunas</span> @endif
                                        @if($totalPending > 0) <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">{{ $totalPending }} Pending</span> @endif
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Persentase Bayar</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-600 rounded-full">
                                            <div class="h-1.5 rounded-full {{ $persentaseBayar >= 100 ? 'bg-green-500' : 'bg-primary-500' }}" style="width: {{ min($persentaseBayar, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">{{ number_format($persentaseBayar, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400 dark:text-gray-500">
                            <svg class="h-10 w-10 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Belum ada sales order</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== TAB: LOG PERUBAHAN ===== --}}
            <div x-show="activeTab === 'logs'" class="p-6 animate-fade-in" x-cloak>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Riwayat Perubahan</h3>
                    <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs font-medium">{{ $logs->count() }} aktivitas</span>
                </div>
                @if ($logs->isNotEmpty())
                    <ol class="relative border-l border-gray-200 dark:border-gray-700 ml-3 space-y-0">
                        @foreach ($logs as $log)
                            <li class="mb-6 ml-6">
                                <span class="absolute -left-3.5 flex h-7 w-7 items-center justify-center rounded-full bg-{{ pelangganActivityColor($log->aktivitas) }}-100 dark:bg-{{ pelangganActivityColor($log->aktivitas) }}-900/30 ring-4 ring-white dark:ring-gray-800">
                                    {!! pelangganActivityIcon($log->aktivitas) !!}
                                </span>
                                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $log->user->name ?? 'Sistem' }}</span>
                                            @if ($log->ip_address)
                                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $log->ip_address }}</span>
                                            @endif
                                        </div>
                                        <time class="text-xs text-gray-400 dark:text-gray-500" title="{{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : '' }}">
                                            {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}
                                        </time>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{!! formatActivityLog($log) !!}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center text-gray-400 dark:text-gray-500">
                        <svg class="h-10 w-10 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Belum ada riwayat perubahan</p>
                        <p class="text-xs mt-1">Perubahan data pelanggan akan tercatat di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Pelanggan --}}
    <x-modal-pelanggan />

    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in { animation: fadeIn 0.25s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>

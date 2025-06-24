<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Detail Penggajian'">
    <div class="py-8 px-4 mx-auto max-w-full xl:max-w-screen-xl lg:py-10">
        <!-- Hero Banner with Action Bar -->
        <div
            class="relative mb-8 bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
            <div
                class="px-6 py-6 sm:px-10 sm:py-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <h1 class="text-2xl font-bold text-white sm:text-3xl mr-4">
                        Slip Gaji {{ $penggajian->karyawan->nama_lengkap }}
                    </h1>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2 sm:mt-0 
                        {{ $penggajian->status === 'draft'
                            ? 'bg-yellow-100 text-yellow-800'
                            : ($penggajian->status === 'disetujui'
                                ? 'bg-blue-100 text-blue-800'
                                : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($penggajian->status) }}
                    </span>
                </div>

                <div class="flex gap-3 w-full sm:w-auto justify-end mt-4 sm:mt-0">
                    <a href="{{ route('hr.penggajian.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary-800 bg-white backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>

                    @if ($penggajian->status === 'draft')
                        <a href="{{ route('hr.penggajian.edit', $penggajian->id) }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-900/40 backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-primary-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                    @endif

                    <button onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-900/40 backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-primary-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div id="payroll-detail-content" class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <!-- Left side (2 cards) -->
            <div class="xl:col-span-2 space-y-4 sm:space-y-6">
                <!-- Employee Info Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Karyawan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="md:w-1/4">
                                @if ($penggajian->karyawan->foto)
                                    <img src="{{ asset('storage/' . $penggajian->karyawan->foto) }}"
                                        alt="Foto {{ $penggajian->karyawan->nama_lengkap }}"
                                        class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                                @else
                                    <div
                                        class="h-32 w-32 rounded-full bg-primary-100 dark:bg-primary-900/30 border-4 border-white dark:border-gray-800 shadow-md flex items-center justify-center text-4xl text-primary-700 dark:text-primary-300 font-bold">
                                        {{ strtoupper(substr($penggajian->karyawan->nama_lengkap, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="md:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $penggajian->karyawan->nama_lengkap }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">NIP</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $penggajian->karyawan->nip }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $penggajian->karyawan->jabatan->nama ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Departemen</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $penggajian->karyawan->department->nama ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $penggajian->karyawan->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $penggajian->karyawan->telepon }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payroll Details Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Rincian Penggajian
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Periode</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::createFromDate($penggajian->tahun, $penggajian->bulan, 1)->isoFormat('MMMM Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pembayaran</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $penggajian->tanggal_bayar ? \Carbon\Carbon::parse($penggajian->tanggal_bayar)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $penggajian->status === 'draft'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($penggajian->status === 'disetujui'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($penggajian->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Salary Components Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Komponen
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Nilai
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Gaji Pokok -->
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            Gaji Pokok
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    <!-- Komponen Pendapatan -->
                                    @foreach ($penggajian->komponenGaji->where('jenis', 'pendapatan') as $komponen)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $komponen->nama_komponen }}
                                                @if ($komponen->keterangan)
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400 block">{{ $komponen->keterangan }}</span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($komponen->nilai, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Tunjangan, Bonus, Lembur -->
                                    @if ($penggajian->tunjangan > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($penggajian->tunjangan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($penggajian->bonus > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Bonus
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($penggajian->bonus, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($penggajian->lembur > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Lembur
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($penggajian->lembur, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Komponen Potongan -->
                                    @foreach ($penggajian->komponenGaji->where('jenis', 'potongan') as $komponen)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $komponen->nama_komponen }}
                                                @if ($komponen->keterangan)
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400 block">{{ $komponen->keterangan }}</span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                ({{ number_format($komponen->nilai, 0, ',', '.') }})
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Potongan -->
                                    @if ($penggajian->potongan > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Potongan
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                ({{ number_format($penggajian->potongan, 0, ',', '.') }})
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Divider -->
                                    <tr class="bg-gray-50 dark:bg-gray-700/30">
                                        <td colspan="2" class="px-6 py-1"></td>
                                    </tr>

                                    <!-- Total -->
                                    <tr class="bg-gray-50 dark:bg-gray-700/30">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900 dark:text-white">
                                            Total Gaji
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-base text-right font-bold text-gray-900 dark:text-white">
                                            {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if ($penggajian->catatan)
                            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan:</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $penggajian->catatan }}</p>
                            </div>
                        @endif

                        <!-- Commission Details -->
                        @php
                            $komisiKomponen = $penggajian->komponenGaji
                                ->where('nama_komponen', 'Komisi Penjualan')
                                ->first();
                        @endphp

                        @if ($komisiKomponen)
                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Detail Komisi Penjualan:
                                    </span>
                                </h3>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <p><span class="font-medium">Total Komisi:</span> Rp
                                        {{ number_format($komisiKomponen->nilai, 0, ',', '.') }}</p>
                                    <p><span class="font-medium">Periode:</span>
                                        {{ \Carbon\Carbon::createFromDate($penggajian->tahun, $penggajian->bulan, 1)->isoFormat('MMMM Y') }}
                                    </p> @php
                                        $salesOrderInfo = [];
                                        $salesOrderCount = 0;

                                        // Check for new format with sales order nomors
                                        if (strpos($komisiKomponen->keterangan, 'SO:') !== false) {
                                            preg_match('/SO: ([\w\-,\s]+)/', $komisiKomponen->keterangan, $matches);
                                            if (isset($matches[1])) {
                                                $salesOrderNomors = array_map('trim', explode(',', $matches[1]));
                                                $salesOrderInfo = $salesOrderNomors;
                                                $salesOrderCount = count($salesOrderNomors);
                                            }
                                        }
                                        // Check for old format with sales order IDs (backward compatibility)
                                        elseif (strpos($komisiKomponen->keterangan, 'sales order ID:') !== false) {
                                            preg_match(
                                                '/sales order ID: ([\d,]+)/',
                                                $komisiKomponen->keterangan,
                                                $matches,
                                            );
                                            if (isset($matches[1])) {
                                                $salesOrderIds = explode(',', $matches[1]);
                                                $salesOrderCount = count($salesOrderIds);
                                                // Convert IDs to nomors for display
                                                $salesOrderNomors = \App\Models\SalesOrder::whereIn(
                                                    'id',
                                                    $salesOrderIds,
                                                )
                                                    ->pluck('nomor')
                                                    ->toArray();
                                                $salesOrderInfo = $salesOrderNomors;
                                            }
                                        }
                                    @endphp

                                    @if ($salesOrderCount > 0)
                                        <p class="mt-1"><span class="font-medium">Berdasarkan
                                                {{ $salesOrderCount }} Sales Order:</span></p>
                                        @if (!empty($salesOrderInfo))
                                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 ml-2">
                                                {{ implode(', ', $salesOrderInfo) }}
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Komisi dihitung berdasarkan margin penjualan dengan rate:
                                            <span class="block mt-1 ml-2">• 5.5% untuk margin ≤ 30%</span>
                                            <span class="block ml-2">• 7% untuk margin 30% - 50%</span>
                                            <span class="block ml-2">• 6% untuk margin 50% - 100%</span>
                                            <span class="block ml-2">• 11.5% untuk margin > 100%</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right side (Action & Info Cards) -->
            <div class="xl:col-span-1 space-y-4 sm:space-y-6">
                <!-- Total Salary Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Total Gaji</h2>
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                            Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Periode:
                            {{ \Carbon\Carbon::createFromDate($penggajian->tahun, $penggajian->bulan, 1)->isoFormat('MMMM Y') }}
                        </p>
                    </div>
                </div>

                <!-- Status Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Status Penggajian</h2>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full 
                                    {{ $penggajian->status == 'draft' ? 'bg-yellow-500' : ($penggajian->status == 'disetujui' || $penggajian->status == 'dibayar' ? 'bg-green-500' : 'bg-gray-200') }} 
                                    flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Draft</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Penggajian dibuat</p>
                                </div>
                            </div>

                            <div class="relative flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full
                                    {{ $penggajian->status == 'disetujui' || $penggajian->status == 'dibayar' ? 'bg-green-500' : 'bg-gray-200' }}
                                    flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Disetujui</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @if ($penggajian->status == 'disetujui' || $penggajian->status == 'dibayar')
                                            Disetujui oleh: {{ $penggajian->approver->name ?? '-' }}
                                        @else
                                            Menunggu persetujuan
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full
                                    {{ $penggajian->status == 'dibayar' ? 'bg-green-500' : 'bg-gray-200' }}
                                    flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Dibayar</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @if ($penggajian->status == 'dibayar')
                                            Dibayar pada:
                                            {{ \Carbon\Carbon::parse($penggajian->tanggal_bayar)->format('d F Y') }}
                                        @else
                                            Menunggu pembayaran
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Aksi</h2>
                    </div>
                    <div class="p-6">
                        @if ($penggajian->status == 'draft')
                            <form action="{{ route('hr.penggajian.approve', $penggajian->id) }}" method="POST"
                                class="mb-4">
                                @csrf
                                <button type="submit"
                                    class="w-full flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Penggajian
                                </button>
                            </form>
                        @elseif($penggajian->status == 'disetujui')
                            <form action="{{ route('hr.penggajian.process-payment', $penggajian->id) }}"
                                method="POST" id="payment-form">
                                @csrf

                                <div class="mb-4">
                                    <label for="payment_date"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                        Bayar</label>
                                    <input type="date" name="payment_date" id="payment_date" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>

                                <div class="mb-6">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metode
                                        Pembayaran</h3>

                                    <div id="payment-method-selector" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div id="cash_payment_card"
                                            class="payment-method-card border rounded-lg p-4 cursor-pointer hover:border-primary-500 border-gray-200 dark:border-gray-700"
                                            onclick="selectPaymentMethod('cash')" role="radio" tabindex="0"
                                            aria-checked="true">
                                            <div class="flex items-center space-x-3">
                                                <input type="radio" id="payment_cash" name="payment_method"
                                                    value="cash" class="hidden" checked>
                                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                <span class="font-medium text-gray-900 dark:text-white">Kas /
                                                    Tunai</span>
                                            </div>
                                        </div>

                                        <div id="bank_payment_card"
                                            class="payment-method-card border rounded-lg p-4 cursor-pointer hover:border-primary-500 border-gray-200 dark:border-gray-700"
                                            onclick="selectPaymentMethod('bank')" role="radio" tabindex="0"
                                            aria-checked="false">
                                            <div class="flex items-center space-x-3">
                                                <input type="radio" id="payment_bank" name="payment_method"
                                                    value="bank" class="hidden">
                                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                                <span class="font-medium text-gray-900 dark:text-white">Transfer
                                                    Bank</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="payment-method-options" class="mt-4">
                                        <div id="cash_accounts" class="payment-option">
                                            <label for="kas_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih
                                                Akun Kas</label>
                                            <select name="kas_id" id="kas_id" required
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                <option value="">Pilih Akun Kas</option>
                                                @if (isset($cashAccounts))
                                                    @foreach ($cashAccounts as $kas)
                                                        <option value="{{ $kas->id }}">{{ $kas->nama }} -
                                                            {{ number_format($kas->saldo, 0, ',', '.') }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div id="kas-summary"
                                                class="mt-2 text-xs text-gray-500 dark:text-gray-400 hidden"></div>
                                        </div>

                                        <div id="bank_accounts" class="payment-option hidden">
                                            <label for="rekening_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih
                                                Rekening Bank</label>
                                            <select name="rekening_id" id="rekening_id"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                <option value="">Pilih Rekening Bank</option>
                                                @if (isset($bankAccounts))
                                                    @foreach ($bankAccounts as $bank)
                                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }} -
                                                            {{ $bank->nomor_rekening }}
                                                            ({{ number_format($bank->saldo, 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div id="rekening-summary"
                                                class="mt-2 text-xs text-gray-500 dark:text-gray-400 hidden"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label for="payment_note"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan
                                        Pembayaran
                                        <span class="text-gray-500">(Opsional)</span></label>
                                    <textarea name="payment_note" id="payment_note" rows="3"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        placeholder="Masukkan catatan untuk pembayaran ini..."></textarea>
                                </div>

                                <button type="submit" id="submit-payment-btn"
                                    class="w-full flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Proses Pembayaran
                                </button>
                            </form>
                        @else
                            <div
                                class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <svg class="w-6 h-6 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Penggajian sudah
                                    dibayar</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Action Bar -->
    <div id="mobile-action-bar"
        class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 lg:hidden transform translate-y-full transition-transform duration-300 ease-in-out">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Gaji</p>
                <p class="text-xl font-bold text-primary-600 dark:text-primary-400">
                    Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex space-x-2">
                <button id="mobile-print-btn" onclick="window.print()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </button>

                @if ($penggajian->status == 'draft')
                    <form action="{{ route('hr.penggajian.approve', $penggajian->id) }}" method="POST">
                        @csrf
                        <button id="mobile-submit-btn" type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                            Setujui
                        </button>
                    </form>
                @elseif($penggajian->status == 'disetujui')
                    <button id="mobile-submit-btn" type="button"
                        onclick="document.querySelector('#payment-form').scrollIntoView({behavior: 'smooth'})"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg">
                        Bayar
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-5 max-w-md w-full mx-4">
            <div class="flex items-center justify-center space-x-4">
                <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p id="loading-message" class="text-lg font-medium text-gray-900 dark:text-white">Memproses...</p>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast-notification"
        class="hidden fixed top-4 right-4 z-50 max-w-xs bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
        <div class="p-4 flex items-center">
            <div id="toast-icon"
                class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-3">
                <svg class="h-5 w-5 text-green-500 dark:text-green-300" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p id="toast-message" class="text-sm font-medium text-gray-900 dark:text-white">Berhasil disimpan!</p>
            </div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 text-gray-400 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                onclick="document.getElementById('toast-notification').classList.add('hidden')">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {
                body {
                    background-color: white;
                    font-size: 12pt;
                }

                .no-print,
                .no-print * {
                    display: none !important;
                }

                #payroll-detail-content {
                    display: block;
                }

                .payroll-card {
                    page-break-inside: avoid;
                    margin-bottom: 15pt;
                    border: 1px solid #eee;
                }

                .shadow-sm,
                .shadow-md,
                .shadow-lg {
                    box-shadow: none !important;
                }

                .rounded-xl {
                    border-radius: 0 !important;
                }

                .border {
                    border: 1px solid #eee !important;
                }
            }

            .payment-method-card.selected {
                border-color: #3b82f6;
                background-color: rgba(59, 130, 246, 0.05);
            }

            .bg-pattern-dots {
                background-image: radial-gradient(currentColor 1px, transparent 1px);
                background-size: calc(10 * 1px) calc(10 * 1px);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize UI components
                setupStickyActionBar();
                addKeyboardNavigation();

                // Handle form submission
                const paymentForm = document.getElementById('payment-form');
                if (paymentForm) {
                    paymentForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Validate form
                        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')
                            ?.value;
                        let isValid = true;

                        if (paymentMethod === 'cash') {
                            const kasId = document.getElementById('kas_id').value;
                            if (!kasId) {
                                showValidationError(document.getElementById('kas_id'),
                                    'Silahkan pilih akun kas');
                                isValid = false;
                            }
                        } else if (paymentMethod === 'bank') {
                            const rekeningId = document.getElementById('rekening_id').value;
                            if (!rekeningId) {
                                showValidationError(document.getElementById('rekening_id'),
                                    'Silahkan pilih rekening bank');
                                isValid = false;
                            }
                        }

                        const paymentDate = document.getElementById('payment_date').value;
                        if (!paymentDate) {
                            showValidationError(document.getElementById('payment_date'),
                                'Tanggal pembayaran harus diisi');
                            isValid = false;
                        }

                        if (isValid) {
                            // Show loading overlay
                            showLoadingOverlay('Memproses pembayaran...');
                            // Submit the form
                            this.submit();
                        }
                    });
                }
            });

            // Function to set up sticky action bar on mobile
            function setupStickyActionBar() {
                const mobileActionBar = document.getElementById('mobile-action-bar');

                if (mobileActionBar) {
                    let lastScrollTop = 0;
                    window.addEventListener('scroll', function() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                        if (scrollTop > lastScrollTop && scrollTop > 300) {
                            mobileActionBar.classList.remove('translate-y-full');
                        } else if (scrollTop < 100 || scrollTop < lastScrollTop) {
                            mobileActionBar.classList.add('translate-y-full');
                        }

                        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                    });
                }
            }

            // Function to switch between payment methods
            function selectPaymentMethod(method) {
                const cashCard = document.getElementById('cash_payment_card');
                const bankCard = document.getElementById('bank_payment_card');
                const cashAccounts = document.getElementById('cash_accounts');
                const bankAccounts = document.getElementById('bank_accounts');

                clearValidationErrors();

                if (method === 'cash') {
                    document.getElementById('payment_cash').checked = true;
                    if (cashCard) {
                        cashCard.classList.add('selected', 'border-primary-500');
                        cashCard.classList.remove('border-gray-200', 'dark:border-gray-700');
                        cashCard.setAttribute('aria-checked', 'true');
                    }
                    if (bankCard) {
                        bankCard.classList.remove('selected', 'border-primary-500');
                        bankCard.classList.add('border-gray-200', 'dark:border-gray-700');
                        bankCard.setAttribute('aria-checked', 'false');
                    }
                    if (cashAccounts) cashAccounts.classList.remove('hidden');
                    if (bankAccounts) bankAccounts.classList.add('hidden');

                    if (document.getElementById('kas_id')) document.getElementById('kas_id').required = true;
                    if (document.getElementById('rekening_id')) document.getElementById('rekening_id').required = false;
                } else if (method === 'bank') {
                    document.getElementById('payment_bank').checked = true;
                    if (bankCard) {
                        bankCard.classList.add('selected', 'border-primary-500');
                        bankCard.classList.remove('border-gray-200', 'dark:border-gray-700');
                        bankCard.setAttribute('aria-checked', 'true');
                    }
                    if (cashCard) {
                        cashCard.classList.remove('selected', 'border-primary-500');
                        cashCard.classList.add('border-gray-200', 'dark:border-gray-700');
                        cashCard.setAttribute('aria-checked', 'false');
                    }
                    if (bankAccounts) bankAccounts.classList.remove('hidden');
                    if (cashAccounts) cashAccounts.classList.add('hidden');

                    if (document.getElementById('kas_id')) document.getElementById('kas_id').required = false;
                    if (document.getElementById('rekening_id')) document.getElementById('rekening_id').required = true;
                }
            }

            // Add keyboard navigation for accessibility
            function addKeyboardNavigation() {
                const paymentCards = document.querySelectorAll('.payment-method-card');

                paymentCards.forEach(card => {
                    card.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            this.click();
                        }
                    });
                });
            }

            // Validation error display
            function showValidationError(element, message) {
                clearValidationErrors();

                element.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');

                const errorElement = document.createElement('p');
                errorElement.classList.add('text-red-500', 'text-xs', 'mt-1', 'validation-error');
                errorElement.innerText = message;

                element.parentNode.insertBefore(errorElement, element.nextSibling);
                showToast(message, 'error');
                element.focus();
            }

            function clearValidationErrors() {
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                });

                document.querySelectorAll('.validation-error').forEach(el => {
                    el.remove();
                });
            }

            // Loading overlay
            function showLoadingOverlay(message = 'Memproses...') {
                const overlay = document.getElementById('loading-overlay');
                const messageEl = document.getElementById('loading-message');

                if (messageEl) messageEl.textContent = message;
                if (overlay) {
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                }
            }

            function hideLoadingOverlay() {
                const overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                }
            }

            // Toast notifications
            function showToast(message, type = 'info') {
                const toast = document.getElementById('toast-notification');
                const toastMessage = document.getElementById('toast-message');
                const toastIcon = document.getElementById('toast-icon');

                if (!toast || !toastMessage || !toastIcon) return;

                toastMessage.textContent = message;

                if (type === 'success') {
                    toastIcon.className =
                        'flex-shrink-0 h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-3';
                    toastIcon.innerHTML =
                        '<svg class="h-5 w-5 text-green-500 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                } else if (type === 'error') {
                    toastIcon.className =
                        'flex-shrink-0 h-8 w-8 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center mr-3';
                    toastIcon.innerHTML =
                        '<svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                } else if (type === 'warning') {
                    toastIcon.className =
                        'flex-shrink-0 h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center mr-3';
                    toastIcon.innerHTML =
                        '<svg class="h-5 w-5 text-yellow-500 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
                } else {
                    toastIcon.className =
                        'flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3';
                    toastIcon.innerHTML =
                        '<svg class="h-5 w-5 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                }

                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }
        </script>
    @endpush
</x-app-layout>

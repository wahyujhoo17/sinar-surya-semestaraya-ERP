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

                    <a href="{{ route('hr.penggajian.pdf', $penggajian->id) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-900/40 backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-primary-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Download PDF
                    </a>

                    <a href="{{ route('hr.penggajian.print', $penggajian->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-900/40 backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-primary-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Print PDF
                    </a>
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
                                            Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    <!-- Employee Salary Components from Karyawan -->
                                    @if (($penggajian->karyawan->tunjangan_keluarga ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan Keluarga
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_keluarga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (($penggajian->karyawan->tunjangan_jabatan ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan Jabatan
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_jabatan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (($penggajian->karyawan->tunjangan_transport ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan Transport
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_transport, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (($penggajian->karyawan->tunjangan_makan ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan Makan
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_makan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

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
                                                Rp {{ number_format($komponen->nilai, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Tunjangan, Bonus, Lembur -->
                                    @if ($penggajian->tunjangan > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan Lainnya
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                Rp {{ number_format($penggajian->tunjangan, 0, ',', '.') }}
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
                                                Rp {{ number_format($penggajian->bonus, 0, ',', '.') }}
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
                                                Rp {{ number_format($penggajian->lembur, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Deduction Section Header -->
                                    @if (
                                        ($penggajian->bpjs_karyawan ?? 0) > 0 ||
                                            ($penggajian->cash_bon ?? 0) > 0 ||
                                            ($penggajian->keterlambatan ?? 0) > 0 ||
                                            $penggajian->komponenGaji->where('jenis', 'potongan')->count() > 0 ||
                                            $penggajian->potongan > 0)
                                        <tr class="bg-red-50 dark:bg-red-900/20">
                                            <td colspan="2" class="px-6 py-3">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M20 12H4">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="text-sm font-medium text-red-700 dark:text-red-300">Potongan
                                                        Gaji</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- BPJS -->
                                    @if (($penggajian->bpjs_karyawan ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                BPJS
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                (Rp {{ number_format($penggajian->bpjs_karyawan, 0, ',', '.') }})
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Cash Bon -->
                                    @if (($penggajian->cash_bon ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Cash Bon
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                (Rp {{ number_format($penggajian->cash_bon, 0, ',', '.') }})
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Keterlambatan -->
                                    @if (($penggajian->keterlambatan ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Keterlambatan
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                (Rp {{ number_format($penggajian->keterlambatan, 0, ',', '.') }})
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
                                                (Rp {{ number_format($komponen->nilai, 0, ',', '.') }})
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Potongan Lainnya -->
                                    @if ($penggajian->potongan > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Potongan Lainnya
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                (Rp {{ number_format($penggajian->potongan, 0, ',', '.') }})
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
                                            Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
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
                                ->where('jenis', 'pendapatan');

                            $totalKomisi = $komisiKomponen->sum('nilai');
                        @endphp

                        @if ($komisiKomponen->count() > 0)
                            <div class="mt-6 p-4">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Detail Komisi Penjualan
                                    </span>
                                </h3>

                                <!-- Summary Info -->
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 mb-4 border border-blue-200 dark:border-blue-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Total Komisi:</span>
                                            <div class="font-bold text-lg text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($totalKomisi, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Jumlah Sales Order:</span>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $komisiKomponen->count() }} SO
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Periode:</span>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::createFromDate($penggajian->tahun, $penggajian->bulan, 1)->isoFormat('MMMM Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Komisi Per Sales Order -->
                                <div class="space-y-4">
                                    @foreach ($komisiKomponen as $komponen)
                                        @php
                                            $salesOrder = $komponen->salesOrder;
                                            $hasAdjustments =
                                                $komponen->cashback_nominal > 0 || $komponen->overhead_persen > 0;
                                        @endphp

                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                                            <!-- Header Sales Order -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                                        SO: {{ $salesOrder ? $salesOrder->nomor : 'N/A' }}
                                                    </h4>
                                                    @if ($salesOrder)
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d M Y') }}
                                                            •
                                                            {{ $salesOrder->customer->company ?? ($salesOrder->customer->nama ?? 'N/A') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <div
                                                        class="text-lg font-semibold text-green-600 dark:text-green-400">
                                                        Rp {{ number_format($komponen->nilai, 0, ',', '.') }}
                                                    </div>
                                                    @if ($komponen->margin_persen)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            Margin: {{ number_format($komponen->margin_persen, 2) }}%
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Detail Perhitungan -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                @if ($komponen->netto_penjualan_original)
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Netto
                                                            Penjualan:</span>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($komponen->netto_penjualan_original, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($komponen->netto_beli_original)
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Netto
                                                            Beli:</span>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($komponen->netto_beli_original, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($komponen->komisi_rate)
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Rate
                                                            Komisi:</span>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            {{ $komponen->komisi_rate }}%
                                                        </div>
                                                    </div>
                                                @endif

                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                                    <div
                                                        class="font-medium {{ $hasAdjustments ? 'text-orange-600' : 'text-green-600' }}">
                                                        {{ $hasAdjustments ? 'Disesuaikan' : 'Normal' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Per Produk (jika ada) -->
                                            @if (!empty($komponen->product_details) && is_array($komponen->product_details))
                                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                                    <h5
                                                        class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                            </path>
                                                        </svg>
                                                        Detail Perhitungan Per Produk
                                                        @if ($komponen->has_sales_ppn)
                                                            <span
                                                                class="ml-2 text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full">
                                                                PPN {{ $komponen->sales_ppn }}%
                                                            </span>
                                                        @else
                                                            <span
                                                                class="ml-2 text-xs px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full">
                                                                Non-PPN
                                                            </span>
                                                        @endif
                                                    </h5>
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full text-xs">
                                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                                <tr>
                                                                    <th
                                                                        class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">
                                                                        Produk</th>
                                                                    <th
                                                                        class="px-3 py-2 text-right font-medium text-gray-700 dark:text-gray-300">
                                                                        Qty</th>
                                                                    <th
                                                                        class="px-3 py-2 text-right font-medium text-gray-700 dark:text-gray-300">
                                                                        Harga Jual</th>
                                                                    <th
                                                                        class="px-3 py-2 text-right font-medium text-gray-700 dark:text-gray-300">
                                                                        Harga Beli</th>
                                                                    <th
                                                                        class="px-3 py-2 text-center font-medium text-gray-700 dark:text-gray-300">
                                                                        Status PPN</th>
                                                                    <th
                                                                        class="px-3 py-2 text-right font-medium text-gray-700 dark:text-gray-300">
                                                                        Margin</th>
                                                                    <th
                                                                        class="px-3 py-2 text-right font-medium text-gray-700 dark:text-gray-300">
                                                                        Margin %</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="divide-y divide-gray-200 dark:divide-gray-600">
                                                                @foreach ($komponen->product_details as $product)
                                                                    @php
                                                                        $ppnRuleBadge = '';
                                                                        $ppnRuleText = '';
                                                                        $ppnRuleColor = '';

                                                                        if (isset($product['ppn_rule'])) {
                                                                            switch ($product['ppn_rule']) {
                                                                                case 'rule_1':
                                                                                    $ppnRuleBadge =
                                                                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
                                                                                    $ppnRuleText = 'PPN + PPN';
                                                                                    break;
                                                                                case 'rule_2':
                                                                                    $ppnRuleBadge =
                                                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
                                                                                    $ppnRuleText = 'PPN + Non-PPN';
                                                                                    break;
                                                                                case 'rule_3':
                                                                                    $ppnRuleBadge =
                                                                                        'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300';
                                                                                    $ppnRuleText = 'Non-PPN + PPN';
                                                                                    break;
                                                                                default:
                                                                                    $ppnRuleBadge =
                                                                                        'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                                                                                    $ppnRuleText = 'Standard';
                                                                            }
                                                                        }

                                                                        $margin =
                                                                            ($product['harga_jual_adjusted'] ??
                                                                                $product['harga_jual']) -
                                                                            ($product['harga_beli_adjusted'] ??
                                                                                $product['harga_beli']);
                                                                    @endphp
                                                                    <tr
                                                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                                        <td
                                                                            class="px-3 py-2 text-gray-900 dark:text-white">
                                                                            <div class="font-medium">
                                                                                {{ $product['nama_produk'] ?? 'N/A' }}
                                                                            </div>
                                                                            <div class="text-gray-500">
                                                                                {{ $product['kode_produk'] ?? '' }}
                                                                            </div>
                                                                        </td>
                                                                        <td
                                                                            class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                                                            {{ number_format($product['quantity'] ?? 0, 0, ',', '.') }}
                                                                        </td>
                                                                        <td class="px-3 py-2 text-right">
                                                                            <div
                                                                                class="text-gray-900 dark:text-white font-medium">
                                                                                Rp
                                                                                {{ number_format($product['harga_jual_adjusted'] ?? ($product['harga_jual'] ?? 0), 0, ',', '.') }}
                                                                            </div>
                                                                            @if (isset($product['harga_jual_adjusted']) && $product['harga_jual_adjusted'] != $product['harga_jual'])
                                                                                <div
                                                                                    class="text-gray-500 text-xs line-through">
                                                                                    Rp
                                                                                    {{ number_format($product['harga_jual'], 0, ',', '.') }}
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="px-3 py-2 text-right">
                                                                            <div
                                                                                class="text-gray-900 dark:text-white font-medium">
                                                                                Rp
                                                                                {{ number_format($product['harga_beli_adjusted'] ?? ($product['harga_beli'] ?? 0), 0, ',', '.') }}
                                                                            </div>
                                                                            @if (isset($product['harga_beli_adjusted']) && $product['harga_beli_adjusted'] != $product['harga_beli'])
                                                                                <div
                                                                                    class="text-gray-500 text-xs line-through">
                                                                                    Rp
                                                                                    {{ number_format($product['harga_beli'], 0, ',', '.') }}
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="px-3 py-2 text-center">
                                                                            @if ($ppnRuleText)
                                                                                <span
                                                                                    class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $ppnRuleBadge }}">
                                                                                    {{ $ppnRuleText }}
                                                                                </span>
                                                                            @else
                                                                                <span class="text-gray-400">-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td
                                                                            class="px-3 py-2 text-right text-gray-900 dark:text-white font-medium">
                                                                            Rp
                                                                            {{ number_format($margin, 0, ',', '.') }}
                                                                        </td>
                                                                        <td class="px-3 py-2 text-right">
                                                                            <span
                                                                                class="font-medium {{ $product['margin_persen'] >= 18 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                                {{ number_format($product['margin_persen'] ?? 0, 2) }}%
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Legend PPN Rules -->
                                                    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                        <h6
                                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            Keterangan Aturan PPN:</h6>
                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="inline-flex px-2 py-0.5 mr-2 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                                    PPN + PPN
                                                                </span>
                                                                <span class="text-gray-600 dark:text-gray-400">Beli
                                                                    non-PPN</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="inline-flex px-2 py-0.5 mr-2 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                                    PPN + Non-PPN
                                                                </span>
                                                                <span class="text-gray-600 dark:text-gray-400">Harga
                                                                    sesuai</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="inline-flex px-2 py-0.5 mr-2 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                                    Non-PPN + PPN
                                                                </span>
                                                                <span class="text-gray-600 dark:text-gray-400">Beli
                                                                    include PPN</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Penyesuaian (jika ada) -->
                                            @if ($hasAdjustments)
                                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                                    <h5
                                                        class="text-sm font-medium text-orange-900 dark:text-orange-100 mb-2">
                                                        Penyesuaian yang Diterapkan:
                                                    </h5>
                                                    <div style="display: block;">
                                                        @php
                                                            $cashbackValue = floatval($komponen->cashback_nominal);
                                                            $overheadValue = floatval($komponen->overhead_persen);
                                                        @endphp

                                                        <!-- Cashback Display -->
                                                        @if ($cashbackValue > 0)
                                                            <div
                                                                style="background: #ffebee; padding: 12px; margin: 8px 0; border-radius: 6px; border: 1px solid #ef5350; display: flex; justify-content: space-between; align-items: center;">
                                                                <span style="color: #c62828; font-weight: 500;">💸
                                                                    Cashback:</span>
                                                                <span style="color: #b71c1c; font-weight: 600;">
                                                                    -Rp
                                                                    {{ number_format($cashbackValue, 0, ',', '.') }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <!-- Overhead Display -->
                                                        @if ($overheadValue > 0)
                                                            <div
                                                                style="background: #fff8e1; padding: 12px; margin: 8px 0; border-radius: 6px; border: 1px solid #ffb74d; display: flex; justify-content: space-between; align-items: center;">
                                                                <span style="color: #ef6c00; font-weight: 500;">⚡
                                                                    Overhead:</span>
                                                                <span style="color: #e65100; font-weight: 600;">
                                                                    +{{ $overheadValue }}%
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <!-- No Adjustments Message -->
                                                        @if ($cashbackValue <= 0 && $overheadValue <= 0)
                                                            <div
                                                                style="background: #f5f5f5; padding: 12px; margin: 8px 0; border-radius: 6px; text-align: center; color: #666;">
                                                                ℹ️ Tidak ada penyesuaian untuk sales order ini
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if ($komponen->netto_penjualan_adjusted && $komponen->netto_beli_adjusted)
                                                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                                            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded">
                                                                <span class="text-blue-700 dark:text-blue-300">Netto
                                                                    Penjualan (disesuaikan):</span>
                                                                <div
                                                                    class="font-medium text-blue-900 dark:text-blue-100">
                                                                    Rp
                                                                    {{ number_format($komponen->netto_penjualan_adjusted, 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded">
                                                                <span class="text-blue-700 dark:text-blue-300">Netto
                                                                    Beli (disesuaikan):</span>
                                                                <div
                                                                    class="font-medium text-blue-900 dark:text-blue-100">
                                                                    Rp
                                                                    {{ number_format($komponen->netto_beli_adjusted, 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Informasi Sistem Komisi -->
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                        Informasi Sistem Komisi:
                                    </h4>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        <p>• Komisi dihitung per sales order berdasarkan total margin</p>
                                        <p>• Cashback mengurangi netto penjualan dalam bentuk nominal</p>
                                        <p>• Overhead menambah netto beli dalam bentuk persentase</p>
                                        <p>• Rate komisi ditentukan berdasarkan tier margin (1% - 30%)</p>
                                        <p>• Margin minimum untuk mendapat komisi: 18%</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right side (Action & Info Cards) -->
            <div class="xl:col-span-1 space-y-4 sm:space-y-6">
                <!-- Salary Components Summary Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Ringkasan Gaji
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            // Calculate totals
                            $totalPendapatan = $penggajian->gaji_pokok;
                            $totalPendapatan += $penggajian->karyawan->tunjangan_keluarga ?? 0;
                            $totalPendapatan += $penggajian->karyawan->tunjangan_jabatan ?? 0;
                            $totalPendapatan += $penggajian->karyawan->tunjangan_transport ?? 0;
                            $totalPendapatan += $penggajian->karyawan->tunjangan_makan ?? 0;
                            $totalPendapatan += $penggajian->tunjangan;
                            $totalPendapatan += $penggajian->bonus;
                            $totalPendapatan += $penggajian->lembur;
                            $totalPendapatan += $penggajian->komponenGaji->where('jenis', 'pendapatan')->sum('nilai');

                            $totalPotongan = $penggajian->bpjs_karyawan ?? 0;
                            $totalPotongan += $penggajian->cash_bon ?? 0;
                            $totalPotongan += $penggajian->keterlambatan ?? 0;
                            $totalPotongan += $penggajian->potongan;
                            $totalPotongan += $penggajian->komponenGaji->where('jenis', 'potongan')->sum('nilai');
                        @endphp

                        <div class="space-y-4">
                            <!-- Pendapatan -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-sm font-medium text-green-800 dark:text-green-300">Total Pendapatan
                                    </h3>
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="text-xs text-green-600 dark:text-green-400 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Gaji Pokok:</span>
                                        <span>Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}</span>
                                    </div>
                                    @if (($penggajian->karyawan->tunjangan_keluarga ?? 0) > 0)
                                        <div class="flex justify-between">
                                            <span>Tunjangan Keluarga:</span>
                                            <span>Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_keluarga, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if (($penggajian->karyawan->tunjangan_jabatan ?? 0) > 0)
                                        <div class="flex justify-between">
                                            <span>Tunjangan Jabatan:</span>
                                            <span>Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_jabatan, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if (($penggajian->karyawan->tunjangan_transport ?? 0) > 0)
                                        <div class="flex justify-between">
                                            <span>Tunjangan Transport:</span>
                                            <span>Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_transport, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if (($penggajian->karyawan->tunjangan_makan ?? 0) > 0)
                                        <div class="flex justify-between">
                                            <span>Tunjangan Makan:</span>
                                            <span>Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_makan, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if ($penggajian->tunjangan > 0)
                                        <div class="flex justify-between">
                                            <span>Tunjangan Lainnya:</span>
                                            <span>Rp {{ number_format($penggajian->tunjangan, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if ($penggajian->bonus > 0)
                                        <div class="flex justify-between">
                                            <span>Bonus:</span>
                                            <span>Rp {{ number_format($penggajian->bonus, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if ($penggajian->lembur > 0)
                                        <div class="flex justify-between">
                                            <span>Lembur:</span>
                                            <span>Rp {{ number_format($penggajian->lembur, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @foreach ($penggajian->komponenGaji->where('jenis', 'pendapatan') as $komponen)
                                        <div class="flex justify-between">
                                            <span>{{ $komponen->nama_komponen }}:</span>
                                            <span>Rp {{ number_format($komponen->nilai, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Potongan -->
                            @if ($totalPotongan > 0)
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Total Potongan
                                        </h3>
                                        <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                            Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-red-600 dark:text-red-400 space-y-1">
                                        @if (($penggajian->bpjs_karyawan ?? 0) > 0)
                                            <div class="flex justify-between">
                                                <span>BPJS:</span>
                                                <span>Rp
                                                    {{ number_format($penggajian->bpjs_karyawan, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if (($penggajian->cash_bon ?? 0) > 0)
                                            <div class="flex justify-between">
                                                <span>Cash Bon:</span>
                                                <span>Rp {{ number_format($penggajian->cash_bon, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if (($penggajian->keterlambatan ?? 0) > 0)
                                            <div class="flex justify-between">
                                                <span>Keterlambatan:</span>
                                                <span>Rp
                                                    {{ number_format($penggajian->keterlambatan, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if ($penggajian->potongan > 0)
                                            <div class="flex justify-between">
                                                <span>Potongan Lainnya:</span>
                                                <span>Rp {{ number_format($penggajian->potongan, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @foreach ($penggajian->komponenGaji->where('jenis', 'potongan') as $komponen)
                                            <div class="flex justify-between">
                                                <span>{{ $komponen->nama_komponen }}:</span>
                                                <span>Rp {{ number_format($komponen->nilai, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

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

                                            @if ($penggajian->metode_pembayaran)
                                                <br>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    {{ $penggajian->metode_pembayaran === 'kas' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                                    @if ($penggajian->metode_pembayaran === 'kas')
                                                        Kas
                                                        @if ($penggajian->kas)
                                                            - {{ $penggajian->kas->nama }}
                                                        @endif
                                                    @else
                                                        Bank Transfer
                                                        @if ($penggajian->rekeningBank)
                                                            - {{ $penggajian->rekeningBank->nama_bank }}
                                                        @endif
                                                    @endif
                                                </span>
                                            @endif
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
                                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}
                                                            -
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
            // Ensure script only runs once
            if (!window.payrollDetailInitialized) {
                window.payrollDetailInitialized = true;

                document.addEventListener('DOMContentLoaded', function() {
                    // Delay initialization to ensure all DOM elements are fully rendered
                    setTimeout(function() {
                        try {
                            // Initialize UI components
                            setupStickyActionBar();
                            addKeyboardNavigation();
                            initializePaymentForm();
                        } catch (error) {
                            console.warn('Error initializing UI components:', error);
                        }
                    }, 100);
                });

                function initializePaymentForm() {
                    // Handle form submission
                    const paymentForm = document.getElementById('payment-form');
                    if (paymentForm) {
                        // Remove existing listeners to prevent duplicates
                        const newForm = paymentForm.cloneNode(true);
                        paymentForm.parentNode.replaceChild(newForm, paymentForm);

                        newForm.addEventListener('submit', function(e) {
                            e.preventDefault();

                            // Validate form
                            const paymentMethodEl = document.querySelector('input[name="payment_method"]:checked');
                            const paymentMethod = paymentMethodEl ? paymentMethodEl.value : null;
                            let isValid = true;

                            if (paymentMethod === 'cash') {
                                const kasIdEl = document.getElementById('kas_id');
                                if (!kasIdEl || !kasIdEl.value) {
                                    if (kasIdEl) {
                                        showValidationError(kasIdEl, 'Silahkan pilih akun kas');
                                    }
                                    isValid = false;
                                }
                            } else if (paymentMethod === 'bank') {
                                const rekeningIdEl = document.getElementById('rekening_id');
                                if (!rekeningIdEl || !rekeningIdEl.value) {
                                    if (rekeningIdEl) {
                                        showValidationError(rekeningIdEl, 'Silahkan pilih rekening bank');
                                    }
                                    isValid = false;
                                }
                            }

                            const paymentDateEl = document.getElementById('payment_date');
                            if (!paymentDateEl || !paymentDateEl.value) {
                                if (paymentDateEl) {
                                    showValidationError(paymentDateEl, 'Tanggal pembayaran harus diisi');
                                }
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
                }

                // Function to set up sticky action bar on mobile
                function setupStickyActionBar() {
                    const mobileActionBar = document.getElementById('mobile-action-bar');

                    if (mobileActionBar) {
                        let lastScrollTop = 0;
                        const handleScroll = function() {
                            try {
                                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                                if (scrollTop > lastScrollTop && scrollTop > 300) {
                                    mobileActionBar.classList.remove('translate-y-full');
                                } else if (scrollTop < 100 || scrollTop < lastScrollTop) {
                                    mobileActionBar.classList.add('translate-y-full');
                                }

                                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                            } catch (error) {
                                console.warn('Error in scroll handler:', error);
                            }
                        };

                        window.addEventListener('scroll', handleScroll, {
                            passive: true
                        });
                    }
                }

                // Function to switch between payment methods
                window.selectPaymentMethod = function(method) {
                    try {
                        const cashCard = document.getElementById('cash_payment_card');
                        const bankCard = document.getElementById('bank_payment_card');
                        const cashAccounts = document.getElementById('cash_accounts');
                        const bankAccounts = document.getElementById('bank_accounts');

                        clearValidationErrors();

                        if (method === 'cash') {
                            const paymentCashEl = document.getElementById('payment_cash');
                            if (paymentCashEl) paymentCashEl.checked = true;

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

                            const kasIdEl = document.getElementById('kas_id');
                            const rekeningIdEl = document.getElementById('rekening_id');
                            if (kasIdEl) kasIdEl.required = true;
                            if (rekeningIdEl) rekeningIdEl.required = false;
                        } else if (method === 'bank') {
                            const paymentBankEl = document.getElementById('payment_bank');
                            if (paymentBankEl) paymentBankEl.checked = true;

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

                            const kasIdEl = document.getElementById('kas_id');
                            const rekeningIdEl = document.getElementById('rekening_id');
                            if (kasIdEl) kasIdEl.required = false;
                            if (rekeningIdEl) rekeningIdEl.required = true;
                        }
                    } catch (error) {
                        console.warn('Error in selectPaymentMethod:', error);
                    }
                };

                // Add keyboard navigation for accessibility
                function addKeyboardNavigation() {
                    try {
                        const paymentCards = document.querySelectorAll('.payment-method-card');

                        paymentCards.forEach(card => {
                            if (card) {
                                card.addEventListener('keydown', function(e) {
                                    if (e.key === 'Enter' || e.key === ' ') {
                                        e.preventDefault();
                                        this.click();
                                    }
                                });
                            }
                        });
                    } catch (error) {
                        console.warn('Error in addKeyboardNavigation:', error);
                    }
                }

                // Validation error display
                function showValidationError(element, message) {
                    if (!element) return;

                    try {
                        clearValidationErrors();

                        element.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');

                        const errorElement = document.createElement('p');
                        errorElement.classList.add('text-red-500', 'text-xs', 'mt-1', 'validation-error');
                        errorElement.innerText = message;

                        if (element.parentNode) {
                            element.parentNode.insertBefore(errorElement, element.nextSibling);
                        }
                        showToast(message, 'error');
                        element.focus();
                    } catch (error) {
                        console.warn('Error in showValidationError:', error);
                    }
                }

                function clearValidationErrors() {
                    try {
                        document.querySelectorAll('.border-red-500').forEach(el => {
                            if (el) {
                                el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            }
                        });

                        document.querySelectorAll('.validation-error').forEach(el => {
                            if (el) {
                                el.remove();
                            }
                        });
                    } catch (error) {
                        console.warn('Error in clearValidationErrors:', error);
                    }
                }

                // Loading overlay
                function showLoadingOverlay(message = 'Memproses...') {
                    try {
                        const overlay = document.getElementById('loading-overlay');
                        const messageEl = document.getElementById('loading-message');

                        if (messageEl) messageEl.textContent = message;
                        if (overlay) {
                            overlay.classList.remove('hidden');
                            overlay.classList.add('flex');
                        }
                    } catch (error) {
                        console.warn('Error in showLoadingOverlay:', error);
                    }
                }

                function hideLoadingOverlay() {
                    try {
                        const overlay = document.getElementById('loading-overlay');
                        if (overlay) {
                            overlay.classList.add('hidden');
                            overlay.classList.remove('flex');
                        }
                    } catch (error) {
                        console.warn('Error in hideLoadingOverlay:', error);
                    }
                }

                // Toast notifications
                function showToast(message, type = 'info') {
                    try {
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
                            if (toast) {
                                toast.classList.add('hidden');
                            }
                        }, 3000);
                    } catch (error) {
                        console.warn('Error in showToast:', error);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>

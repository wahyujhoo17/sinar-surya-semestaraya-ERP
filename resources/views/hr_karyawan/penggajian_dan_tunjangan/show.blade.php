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
                                    @if (($penggajian->karyawan->tunjangan_btn ?? 0) > 0)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                Tunjangan BTN
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_btn, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

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
                                ->where('jenis', 'pendapatan')
                                ->first();
                        @endphp

                        @if ($komisiKomponen)
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Total Komisi:</span>
                                            <div class="font-bold text-lg text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($komisiKomponen->nilai, 0, ',', '.') }}
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

                                @php
                                    $salesOrderInfo = [];
                                    $salesOrderCount = 0;
                                    $salesOrderDetails = [];

                                    // Helper function untuk menghitung rate komisi sesuai controller
                                    $getKomisiRateByMargin = function ($marginPersen) {
                                        $komisiTiers = [
                                            ['min' => 18.0, 'max' => 20.0, 'rate' => 1.0],
                                            ['min' => 20.5, 'max' => 25.0, 'rate' => 1.25],
                                            ['min' => 25.5, 'max' => 30.0, 'rate' => 1.5],
                                            ['min' => 30.5, 'max' => 35.0, 'rate' => 1.75],
                                            ['min' => 35.5, 'max' => 40.0, 'rate' => 2.0],
                                            ['min' => 40.5, 'max' => 45.0, 'rate' => 2.25],
                                            ['min' => 45.5, 'max' => 50.0, 'rate' => 2.5],
                                            ['min' => 50.5, 'max' => 55.0, 'rate' => 2.75],
                                            ['min' => 55.5, 'max' => 60.0, 'rate' => 3.0],
                                            ['min' => 60.5, 'max' => 65.0, 'rate' => 3.25],
                                            ['min' => 65.5, 'max' => 70.0, 'rate' => 3.5],
                                            ['min' => 70.5, 'max' => 75.0, 'rate' => 3.75],
                                            ['min' => 75.5, 'max' => 80.0, 'rate' => 4.0],
                                            ['min' => 80.5, 'max' => 85.0, 'rate' => 4.25],
                                            ['min' => 85.5, 'max' => 90.0, 'rate' => 4.5],
                                            ['min' => 90.5, 'max' => 95.0, 'rate' => 4.75],
                                            ['min' => 95.5, 'max' => 100.0, 'rate' => 5.0],
                                            ['min' => 101.0, 'max' => 110.0, 'rate' => 5.25],
                                            ['min' => 111.0, 'max' => 125.0, 'rate' => 5.5],
                                            ['min' => 126.0, 'max' => 140.0, 'rate' => 5.75],
                                            ['min' => 141.0, 'max' => 160.0, 'rate' => 6.0],
                                            ['min' => 161.0, 'max' => 180.0, 'rate' => 6.25],
                                            ['min' => 181.0, 'max' => 200.0, 'rate' => 6.5],
                                            ['min' => 201.0, 'max' => 225.0, 'rate' => 7.0],
                                            ['min' => 226.0, 'max' => 250.0, 'rate' => 7.25],
                                            ['min' => 251.0, 'max' => 275.0, 'rate' => 7.5],
                                            ['min' => 276.0, 'max' => 300.0, 'rate' => 8.0],
                                            ['min' => 301.0, 'max' => 325.0, 'rate' => 8.25],
                                            ['min' => 326.0, 'max' => 350.0, 'rate' => 8.5],
                                            ['min' => 351.0, 'max' => 400.0, 'rate' => 9.0],
                                            ['min' => 401.0, 'max' => 450.0, 'rate' => 9.5],
                                            ['min' => 451.0, 'max' => 500.0, 'rate' => 10.0],
                                            ['min' => 501.0, 'max' => 600.0, 'rate' => 10.5],
                                            ['min' => 601.0, 'max' => 700.0, 'rate' => 11.0],
                                            ['min' => 701.0, 'max' => 800.0, 'rate' => 11.5],
                                            ['min' => 801.0, 'max' => 900.0, 'rate' => 12.0],
                                            ['min' => 901.0, 'max' => 1000.0, 'rate' => 12.5],
                                            ['min' => 1001.0, 'max' => 1100.0, 'rate' => 13.0],
                                            ['min' => 1101.0, 'max' => 1200.0, 'rate' => 13.5],
                                            ['min' => 1201.0, 'max' => 1300.0, 'rate' => 14.0],
                                            ['min' => 1301.0, 'max' => 1400.0, 'rate' => 14.5],
                                            ['min' => 1401.0, 'max' => 1500.0, 'rate' => 15.0],
                                            ['min' => 1501.0, 'max' => 1600.0, 'rate' => 15.5],
                                            ['min' => 1601.0, 'max' => 1700.0, 'rate' => 16.0],
                                            ['min' => 1701.0, 'max' => 1800.0, 'rate' => 16.5],
                                            ['min' => 1801.0, 'max' => 1900.0, 'rate' => 17.0],
                                            ['min' => 1901.0, 'max' => 2000.0, 'rate' => 17.5],
                                            ['min' => 2001.0, 'max' => 2100.0, 'rate' => 18.0],
                                            ['min' => 2101.0, 'max' => 2200.0, 'rate' => 18.5],
                                            ['min' => 2201.0, 'max' => 2300.0, 'rate' => 19.0],
                                            ['min' => 2301.0, 'max' => 2400.0, 'rate' => 19.5],
                                            ['min' => 2401.0, 'max' => 2501.0, 'rate' => 20.0],
                                            ['min' => 2501.0, 'max' => 2600.0, 'rate' => 20.5],
                                            ['min' => 2601.0, 'max' => 2700.0, 'rate' => 21.0],
                                            ['min' => 2701.0, 'max' => 2800.0, 'rate' => 21.5],
                                            ['min' => 2801.0, 'max' => 2900.0, 'rate' => 22.0],
                                            ['min' => 2901.0, 'max' => 3000.0, 'rate' => 22.5],
                                            ['min' => 3001.0, 'max' => 3100.0, 'rate' => 23.0],
                                            ['min' => 3101.0, 'max' => 3200.0, 'rate' => 23.5],
                                            ['min' => 3201.0, 'max' => 3300.0, 'rate' => 24.0],
                                            ['min' => 3301.0, 'max' => 3400.0, 'rate' => 24.5],
                                            ['min' => 3401.0, 'max' => 3500.0, 'rate' => 25.0],
                                            ['min' => 3501.0, 'max' => 3600.0, 'rate' => 25.5],
                                            ['min' => 3601.0, 'max' => 3700.0, 'rate' => 26.0],
                                            ['min' => 3701.0, 'max' => 3800.0, 'rate' => 26.5],
                                            ['min' => 3801.0, 'max' => 3900.0, 'rate' => 27.0],
                                            ['min' => 3901.0, 'max' => 4000.0, 'rate' => 27.5],
                                            ['min' => 4001.0, 'max' => 4100.0, 'rate' => 28.0],
                                            ['min' => 4101.0, 'max' => 4200.0, 'rate' => 28.5],
                                            ['min' => 4201.0, 'max' => 4300.0, 'rate' => 29.0],
                                            ['min' => 4301.0, 'max' => 4400.0, 'rate' => 29.5],
                                            ['min' => 4401.0, 'max' => 4500.0, 'rate' => 30.0],
                                        ];

                                        // Cari tier yang sesuai dengan margin
                                        foreach ($komisiTiers as $tier) {
                                            if ($marginPersen >= $tier['min'] && $marginPersen <= $tier['max']) {
                                                return $tier['rate'];
                                            }
                                        }

                                        // Jika margin lebih dari 4500%, gunakan rate tertinggi
                                        if ($marginPersen > 4500.0) {
                                            return 30.0;
                                        }

                                        // Jika margin kurang dari 18%, tidak ada komisi
                                        return 0;
                                    };

                                    // Enhanced pattern matching to be more flexible
                                    $keterangan = $komisiKomponen->keterangan;

                                    // Check for new format with sales order nomors
                                    if (strpos($keterangan, 'SO:') !== false) {
                                        // More robust regex pattern that handles various formats
                                        preg_match('/SO:\s*([A-Z0-9\-,\s]+)/i', $keterangan, $matches);

                                        if (isset($matches[1])) {
                                            // Clean and split the SO numbers - remove any trailing parentheses
                                            $rawSoString = preg_replace('/\)+\s*$/', '', trim($matches[1]));
                                            $salesOrderNomors = array_map('trim', explode(',', $rawSoString));
                                            // Remove empty values and ensure clean data
                                            $salesOrderNomors = array_filter(
                                                array_map(function ($so) {
                                                    return trim(preg_replace('/[\)\(\s]+$/', '', trim($so)));
                                                }, $salesOrderNomors),
                                            );
                                            $salesOrderInfo = $salesOrderNomors;
                                            $salesOrderCount = count($salesOrderNomors);

                                            // Get detailed sales order information
                                            try {
                                                $salesOrders = \App\Models\SalesOrder::whereIn(
                                                    'nomor',
                                                    $salesOrderNomors,
                                                )
                                                    ->with(['details.produk', 'customer'])
                                                    ->get();

                                                $salesOrderDetails = $salesOrders
                                                    ->map(function ($so) use ($getKomisiRateByMargin) {
                                                        $totalPenjualan = 0;
                                                        $totalHpp = 0;
                                                        $totalMargin = 0;
                                                        $totalKomisi = 0;

                                                        foreach ($so->details as $detail) {
                                                            $hargaSatuan = $detail->harga ?? 0;
                                                            $quantity = $detail->quantity ?? 0;
                                                            $hargaBeli = $detail->produk->harga_beli ?? 0;

                                                            // Netto Penjualan dan Netto Beli per item
                                                            $nettoPenjualan = $hargaSatuan * $quantity;
                                                            $nettoBeli = $hargaBeli * $quantity;

                                                            $totalPenjualan += $nettoPenjualan;
                                                            $totalHpp += $nettoBeli;

                                                            // Hitung margin per item dan komisi sesuai controller
                                                            if ($nettoBeli > 0) {
                                                                // Margin % = (Netto Penjualan - Netto Beli) / Netto Beli × 100
                                                                $marginPersen =
                                                                    (($nettoPenjualan - $nettoBeli) / $nettoBeli) * 100;

                                                                // Dapatkan rate komisi berdasarkan margin
                                                                $komisiRate = $getKomisiRateByMargin($marginPersen);

                                                                // Komisi = Netto Penjualan × %Komisi
                                                                $komisiItem = $nettoPenjualan * ($komisiRate / 100);
                                                                $totalKomisi += $komisiItem;
                                                            }
                                                        }

                                                        $totalMargin = $totalPenjualan - $totalHpp;
                                                        // Margin percentage untuk display (berdasarkan total penjualan untuk tampilan)
                                                        $marginPercentageDisplay =
                                                            $totalPenjualan > 0
                                                                ? ($totalMargin / $totalPenjualan) * 100
                                                                : 0;

                                                        return [
                                                            'nomor' => $so->nomor,
                                                            'tanggal' => $so->tanggal,
                                                            'customer' =>
                                                                $so->customer->nama ??
                                                                ($so->customer->company ?? 'N/A'),
                                                            'total_penjualan' => $totalPenjualan,
                                                            'total_hpp' => $totalHpp,
                                                            'margin' => $totalMargin,
                                                            'margin_percentage' => $marginPercentageDisplay,
                                                            'commission_amount' => $totalKomisi,
                                                            'detail_count' => $so->details->count(),
                                                        ];
                                                    })
                                                    ->toArray();
                                            } catch (\Exception $e) {
                                                $salesOrderDetails = [];
                                            }
                                        }
                                    }
                                    // Check for old format with sales order IDs (backward compatibility)
                                    elseif (strpos($keterangan, 'sales order ID:') !== false) {
                                        preg_match('/sales order ID:\s*([\d,\s]+)/', $keterangan, $matches);

                                        if (isset($matches[1])) {
                                            $salesOrderIds = array_map('trim', explode(',', $matches[1]));
                                            // Remove empty values and convert to integers
                                            $salesOrderIds = array_filter(array_map('intval', $salesOrderIds));
                                            $salesOrderCount = count($salesOrderIds);

                                            // Get detailed sales order information
                                            try {
                                                $salesOrders = \App\Models\SalesOrder::whereIn('id', $salesOrderIds)
                                                    ->with(['details.produk', 'customer'])
                                                    ->get();

                                                $salesOrderDetails = $salesOrders
                                                    ->map(function ($so) use ($getKomisiRateByMargin) {
                                                        $totalPenjualan = 0;
                                                        $totalHpp = 0;
                                                        $totalMargin = 0;
                                                        $totalKomisi = 0;

                                                        foreach ($so->details as $detail) {
                                                            $hargaSatuan = $detail->harga ?? 0;
                                                            $quantity = $detail->quantity ?? 0;
                                                            $hargaBeli = $detail->produk->harga_beli ?? 0;

                                                            // Netto Penjualan dan Netto Beli per item
                                                            $nettoPenjualan = $hargaSatuan * $quantity;
                                                            $nettoBeli = $hargaBeli * $quantity;

                                                            $totalPenjualan += $nettoPenjualan;
                                                            $totalHpp += $nettoBeli;

                                                            // Hitung margin per item dan komisi sesuai controller
                                                            if ($nettoBeli > 0) {
                                                                // Margin % = (Netto Penjualan - Netto Beli) / Netto Beli × 100
                                                                $marginPersen =
                                                                    (($nettoPenjualan - $nettoBeli) / $nettoBeli) * 100;

                                                                // Dapatkan rate komisi berdasarkan margin
                                                                $komisiRate = $getKomisiRateByMargin($marginPersen);

                                                                // Komisi = Netto Penjualan × %Komisi
                                                                $komisiItem = $nettoPenjualan * ($komisiRate / 100);
                                                                $totalKomisi += $komisiItem;
                                                            }
                                                        }

                                                        $totalMargin = $totalPenjualan - $totalHpp;
                                                        // Margin percentage untuk display (berdasarkan total penjualan untuk tampilan)
                                                        $marginPercentageDisplay =
                                                            $totalPenjualan > 0
                                                                ? ($totalMargin / $totalPenjualan) * 100
                                                                : 0;

                                                        return [
                                                            'nomor' => $so->nomor,
                                                            'tanggal' => $so->tanggal,
                                                            'customer' => $so->customer->nama ?? 'N/A',
                                                            'total_penjualan' => $totalPenjualan,
                                                            'total_hpp' => $totalHpp,
                                                            'margin' => $totalMargin,
                                                            'margin_percentage' => $marginPercentageDisplay,
                                                            'commission_amount' => $totalKomisi,
                                                            'detail_count' => $so->details->count(),
                                                        ];
                                                    })
                                                    ->toArray();

                                                $salesOrderInfo = array_column($salesOrderDetails, 'nomor');
                                            } catch (\Exception $e) {
                                                $salesOrderDetails = [];
                                            }
                                        }
                                    } else {
                                        // No recognizable pattern found in keterangan
                                    }
                                @endphp

                                <!-- Force Display: ALWAYS show commission details section -->
                                <div class="mt-4 mb-6">
                                    <h4
                                        style="font-weight: 600; color: #374151; margin-bottom: 12px; font-size: 16px;">
                                        📊 Detail Komisi Penjualan
                                    </h4>

                                    @if (!empty($salesOrderDetails))
                                        <!-- Sales Order Details dengan style inline -->
                                        <div style="margin-bottom: 20px;">
                                            @foreach ($salesOrderDetails as $index => $soDetail)
                                                <div
                                                    style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                                    <!-- Header SO -->
                                                    <div
                                                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                                        <div style="flex: 1;">
                                                            <h5
                                                                style="font-weight: 600; color: #111827; font-size: 16px; margin: 0 0 4px 0;">
                                                                {{ $soDetail['nomor'] }}
                                                            </h5>
                                                            <p style="font-size: 14px; color: #6b7280; margin: 0;">
                                                                {{ \Carbon\Carbon::parse($soDetail['tanggal'])->format('d M Y') }}
                                                                •
                                                                {{ $soDetail['customer'] }} •
                                                                {{ $soDetail['detail_count'] }} item(s)
                                                            </p>
                                                        </div>
                                                        <span
                                                            style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
                                                            {{ $soDetail['margin_percentage'] <= 30
                                                                ? 'background: #fee2e2; color: #991b1b;'
                                                                : ($soDetail['margin_percentage'] <= 50
                                                                    ? 'background: #fef3c7; color: #92400e;'
                                                                    : ($soDetail['margin_percentage'] <= 100
                                                                        ? 'background: #d1fae5; color: #065f46;'
                                                                        : 'background: #dbeafe; color: #1e40af;')) }}">
                                                            {{ number_format($soDetail['margin_percentage'], 1) }}%
                                                            Margin
                                                        </span>
                                                    </div>

                                                    <!-- Financial Details dengan grid sederhana -->
                                                    <div
                                                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 8px;">
                                                        <div
                                                            style="background: #f9fafb; border-radius: 6px; padding: 8px;">
                                                            <span
                                                                style="color: #6b7280; font-size: 10px; display: block;">Total
                                                                Penjualan</span>
                                                            <div
                                                                style="font-weight: 600; color: #111827; font-size: 14px;">
                                                                Rp
                                                                {{ number_format($soDetail['total_penjualan'], 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                        <div
                                                            style="background: #f9fafb; border-radius: 6px; padding: 8px;">
                                                            <span
                                                                style="color: #6b7280; font-size: 10px; display: block;">HPP</span>
                                                            <div
                                                                style="font-weight: 600; color: #111827; font-size: 14px;">
                                                                Rp
                                                                {{ number_format($soDetail['total_hpp'], 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                        <div
                                                            style="background: #ecfdf5; border-radius: 6px; padding: 8px;">
                                                            <span
                                                                style="color: #047857; font-size: 10px; display: block;">Margin</span>
                                                            <div
                                                                style="font-weight: 600; color: #047857; font-size: 14px;">
                                                                Rp
                                                                {{ number_format($soDetail['margin'], 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                        <div
                                                            style="background: #eff6ff; border-radius: 6px; padding: 8px;">
                                                            <span
                                                                style="color: #1d4ed8; font-size: 10px; display: block;">Komisi</span>
                                                            <div
                                                                style="font-weight: 600; color: #1d4ed8; font-size: 14px;">
                                                                Rp
                                                                {{ number_format($soDetail['commission_amount'], 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <!-- Total Summary -->
                                            <div
                                                style="background: #eff6ff; border: 1px solid #93c5fd; border-radius: 8px; padding: 16px;">
                                                <div
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div>
                                                        <span
                                                            style="font-weight: 500; color: #1e3a8a; font-size: 16px;">💰
                                                            Total Komisi Keseluruhan:</span>
                                                        <div style="color: #1e40af; font-size: 12px; margin-top: 4px;">
                                                            Dari {{ count($salesOrderDetails) }} sales order
                                                        </div>
                                                    </div>
                                                    <span style="font-size: 20px; font-weight: 700; color: #1e3a8a;">
                                                        Rp
                                                        {{ number_format(array_sum(array_column($salesOrderDetails, 'commission_amount')), 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif (!empty($salesOrderInfo))
                                        <!-- Fallback dengan style inline -->
                                        <div
                                            style="background: #fffbeb; border: 1px solid #fbbf24; border-radius: 8px; padding: 12px;">
                                            <p style="font-size: 14px; color: #92400e; margin: 0 0 4px 0;">
                                                <strong>Sales Order:</strong> {{ implode(', ', $salesOrderInfo) }}
                                            </p>
                                            <p style="font-size: 12px; color: #a16207; margin: 0;">
                                                Detail perhitungan tidak tersedia. Hubungi administrator untuk informasi
                                                lebih lanjut.
                                            </p>
                                        </div>
                                    @else
                                        <!-- Jika tidak ada data dengan style inline -->
                                        <div
                                            style="background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px;">
                                            <p style="font-size: 14px; color: #374151; margin: 0 0 4px 0;">
                                                <strong>Keterangan:</strong> {{ $komisiKomponen->keterangan }}
                                            </p>
                                            <p style="font-size: 12px; color: #6b7280; margin: 0;">
                                                Detail sales order tidak dapat diambil dari keterangan ini.
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Commission Rate Info dengan style inline -->
                                <div style="margin-top: 16px; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                    <p style="font-weight: 500; color: #374151; margin: 0 0 8px 0; font-size: 14px;">📋
                                        Informasi Sistem Komisi:</p>
                                    <div style="font-size: 12px; color: #6b7280; line-height: 1.4;">
                                        <p style="margin: 0 0 4px 0;">• Komisi dihitung berdasarkan margin penjualan
                                            per item</p>
                                        <p style="margin: 0 0 4px 0;">• Rate komisi menggunakan sistem tier dari 1%
                                            hingga 30%</p>
                                        <p style="margin: 0 0 4px 0;">• Margin minimum untuk mendapat komisi: 18%</p>
                                        <p style="margin: 0;">• Komisi total adalah akumulasi dari semua item dalam
                                            sales order</p>
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
                            $totalPendapatan += $penggajian->karyawan->tunjangan_btn ?? 0;
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
                                    @if (($penggajian->karyawan->tunjangan_btn ?? 0) > 0)
                                        <div class="flex justify-between">
                                            <span>Tunjangan BTN:</span>
                                            <span>Rp
                                                {{ number_format($penggajian->karyawan->tunjangan_btn, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
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
                                                        💰 Kas
                                                        @if ($penggajian->kas)
                                                            - {{ $penggajian->kas->nama }}
                                                        @endif
                                                    @else
                                                        🏦 Bank Transfer
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

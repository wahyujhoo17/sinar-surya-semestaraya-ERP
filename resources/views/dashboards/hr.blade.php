<x-app-layout :breadcrumbs="[]" :currentPage="__('Dashboard HR & Karyawan')">

    @push('styles')
        <style>
            .stat-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 4px 8px rgba(0, 0, 0, 0.04);
            }

            .attendance-bar {
                height: 8px;
                border-radius: 4px;
                transition: all 0.3s;
            }
        </style>
    @endpush

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard HR & Karyawan</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Monitoring kehadiran dan manajemen sumber daya manusia
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- HR Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Karyawan -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Karyawan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalKaryawan) }}</p>
                </div>
            </div>
        </div>

        <!-- Hadir Hari Ini -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($absensiHariIni->count()) }}</p>
                </div>
            </div>
        </div>

        <!-- Persentase Kehadiran -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Persentase Kehadiran</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($persentaseKehadiran, 1) }}%</p>
                </div>
            </div>
        </div>

        <!-- Cuti Pending -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cuti Pending</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($cutiPending->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Overview & Attendance Today -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Department Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Karyawan per Departemen</h3>
            <div class="space-y-4">
                @forelse($karyawanPerDept as $dept)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $dept->nama }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $dept->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $dept->karyawan_count }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Karyawan</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada data departemen</p>
                @endforelse
            </div>
        </div>

        <!-- Attendance Today -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kehadiran Hari Ini</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($absensiHariIni as $absensi)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                    {{ substr($absensi->karyawan->nama ?? 'N', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $absensi->karyawan->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $absensi->karyawan->department->nama ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : 'Belum keluar' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Belum ada absensi hari ini</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Leave Requests & Monthly Attendance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Pending Leave Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pengajuan Cuti Pending</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($cutiPending as $cuti)
                    <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $cuti->karyawan->nama ?? 'N/A' }}</p>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                {{ ucfirst($cuti->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $cuti->jenis_cuti }}</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Mulai:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Selesai:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        @if ($cuti->keterangan)
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                                {{ Str::limit($cuti->keterangan, 50) }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada pengajuan cuti pending</p>
                @endforelse
            </div>
        </div>

        <!-- Current Leave -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Karyawan Sedang Cuti</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($cutiApproved as $cuti)
                    <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $cuti->karyawan->nama ?? 'N/A' }}</p>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                Sedang Cuti
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $cuti->jenis_cuti }}</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Mulai:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Selesai:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada karyawan yang sedang cuti
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Monthly Attendance Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Grafik Kehadiran 30 Hari Terakhir</h3>
        <div class="h-64">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <!-- Recent HR Activities -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas HR Terbaru</h3>
        <div class="space-y-3">
            @forelse($aktivitasTerbaru as $aktivitas)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex-shrink-0">
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $aktivitas->user ? $aktivitas->user->name : 'System' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $aktivitas->deskripsi }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            {{ $aktivitas->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada aktivitas HR terbaru</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if (auth()->user()->hasPermission('karyawan.create'))
                <a href="{{ route('karyawan.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Karyawan</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('absensi.create'))
                <a href="{{ route('absensi.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Input Absensi</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('cuti.view'))
                <a href="{{ route('cuti.index') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Kelola Cuti</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('laporan.view'))
                <a href="{{ route('laporan.hr') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Laporan HR</span>
                </a>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(
                        collect($kehadiranBulanan)->pluck('tanggal')->map(function ($date) {
                                return \Carbon\Carbon::parse($date)->format('d/m');
                            }),
                    ) !!},
                    datasets: [{
                        label: 'Kehadiran Harian',
                        data: {!! json_encode(collect($kehadiranBulanan)->pluck('total')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>

<x-app-layout :breadcrumbs="[['label' => 'Daily Aktivitas', 'url' => route('daily-aktivitas.index')], ['label' => 'Detail Aktivitas']]" :currentPage="'Detail Aktivitas'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Header with activity information -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Left colored status bar -->
                <div class="w-full md:w-1.5 md:h-auto"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ $dailyAktivitas->status }}'
                        === 'pending',
                        'bg-blue-500 dark:bg-blue-600': '{{ $dailyAktivitas->status }}'
                        === 'dalam_proses',
                        'bg-green-500 dark:bg-green-600': '{{ $dailyAktivitas->status }}'
                        === 'selesai',
                        'bg-red-500 dark:bg-red-600': '{{ $dailyAktivitas->status }}'
                        === 'dibatalkan'
                    }">
                </div>

                <!-- Header content -->
                <div class="flex-1 p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                                <h1
                                    class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    {{ $dailyAktivitas->judul }}
                                </h1>

                                <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dailyAktivitas->status_color }}">
                                        {{ $dailyAktivitas->status_label }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dailyAktivitas->prioritas_color }}">
                                        {{ $dailyAktivitas->prioritas_label }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $dailyAktivitas->tipe_aktivitas_label }}
                                    </span>
                                </div>
                            </div>

                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Dibuat oleh {{ $dailyAktivitas->user->name }}
                                <span class="mx-2">•</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $dailyAktivitas->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="mt-4 md:mt-0 flex-shrink-0 flex space-x-3">
                            @php
                                $hasEditAccess =
                                    $dailyAktivitas->user_id === auth()->id() ||
                                    $dailyAktivitas->assigned_to === auth()->id() ||
                                    $dailyAktivitas->assignedUsers->contains('id', auth()->id());
                            @endphp
                            @if ($hasEditAccess)
                                <a href="{{ route('daily-aktivitas.edit', $dailyAktivitas) }}"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            @endif

                            <a href="{{ route('daily-aktivitas.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Activity Details -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Aktivitas</h2>

                        @if ($dailyAktivitas->deskripsi)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h3>
                                <p class="text-gray-900 dark:text-white leading-relaxed">
                                    {{ $dailyAktivitas->deskripsi }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Aktivitas
                                </h3>
                                <p class="text-gray-900 dark:text-white">{{ $dailyAktivitas->tipe_aktivitas_label }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prioritas</h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dailyAktivitas->prioritas_color }}">
                                    {{ $dailyAktivitas->prioritas_label }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dailyAktivitas->status_color }}">
                                    {{ $dailyAktivitas->status_label }}
                                </span>
                            </div>

                            @if ($dailyAktivitas->lokasi)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi</h3>
                                    <p class="text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $dailyAktivitas->lokasi }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Schedule Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Jadwal & Waktu</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal & Waktu
                                    Mulai</h3>
                                <p class="text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $dailyAktivitas->tanggal_mulai->format('d M Y, H:i') }}
                                </p>
                            </div>

                            @if ($dailyAktivitas->tanggal_selesai)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal &
                                        Waktu Selesai</h3>
                                    <p class="text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $dailyAktivitas->tanggal_selesai->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endif

                            @if ($dailyAktivitas->deadline)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deadline</h3>
                                    <p class="text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $dailyAktivitas->deadline->format('d M Y, H:i') }}
                                        @if ($dailyAktivitas->is_overdue)
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Terlambat
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if ($dailyAktivitas->estimasi_durasi)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estimasi
                                        Durasi</h3>
                                    <p class="text-gray-900 dark:text-white">{{ $dailyAktivitas->estimasi_durasi }}
                                        jam</p>
                                </div>
                            @endif

                            @if ($dailyAktivitas->durasi_aktual)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durasi Aktual
                                    </h3>
                                    <p class="text-gray-900 dark:text-white">{{ $dailyAktivitas->durasi_aktual }} jam
                                    </p>
                                </div>
                            @endif

                            @if ($dailyAktivitas->reminder_at)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reminder</h3>
                                    <p class="text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z">
                                            </path>
                                        </svg>
                                        {{ $dailyAktivitas->reminder_at->format('d M Y, H:i') }}
                                        @if ($dailyAktivitas->reminder_sent)
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Terkirim
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                @if ($dailyAktivitas->peserta || $dailyAktivitas->catatan || $dailyAktivitas->hasil)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Tambahan
                            </h2>

                            @if ($dailyAktivitas->peserta)
                                <div class="mb-6">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Peserta</h3>
                                    <p class="text-gray-900 dark:text-white leading-relaxed">
                                        {{ $dailyAktivitas->peserta }}</p>
                                </div>
                            @endif

                            @if ($dailyAktivitas->catatan)
                                <div class="mb-6">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</h3>
                                    <p class="text-gray-900 dark:text-white leading-relaxed">
                                        {{ $dailyAktivitas->catatan }}</p>
                                </div>
                            @endif

                            @if ($dailyAktivitas->hasil)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hasil</h3>
                                    <p class="text-gray-900 dark:text-white leading-relaxed">
                                        {{ $dailyAktivitas->hasil }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Attachments -->
                @if ($dailyAktivitas->attachments && count($dailyAktivitas->attachments) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">File Lampiran</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($dailyAktivitas->attachments as $attachment)
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $attachment['filename'] ?? 'File' }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ isset($attachment['size']) ? number_format($attachment['size'] / 1024, 1) . ' KB' : '' }}
                                            </p>
                                        </div>
                                        @if (isset($attachment['path']))
                                            <div class="ml-3">
                                                <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                @php
                    $hasEditAccess =
                        $dailyAktivitas->user_id === auth()->id() ||
                        $dailyAktivitas->assigned_to === auth()->id() ||
                        $dailyAktivitas->assignedUsers->contains('id', auth()->id());
                @endphp
                @if ($hasEditAccess)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>

                            <div class="space-y-3">
                                @if ($dailyAktivitas->status !== 'selesai')
                                    <button onclick="updateStatus('selesai')"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Tandai Selesai
                                    </button>
                                @endif

                                @if ($dailyAktivitas->status === 'pending')
                                    <button onclick="updateStatus('dalam_proses')"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mulai Aktivitas
                                    </button>
                                @endif

                                @if ($dailyAktivitas->status !== 'dibatalkan' && $dailyAktivitas->status !== 'selesai')
                                    <button onclick="updateStatus('dibatalkan')"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Batalkan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Activity Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Aktivitas</h2>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        {{ substr($dailyAktivitas->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Dibuat oleh</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $dailyAktivitas->user->name }}</p>
                                </div>
                            </div>

                            @if ($dailyAktivitas->assignedTo || $dailyAktivitas->assignedUsers->isNotEmpty())
                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Ditugaskan kepada
                                        </p>

                                        <!-- Legacy assigned_to user -->
                                        @if ($dailyAktivitas->assignedTo)
                                            <div class="mt-1 flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                                        {{ substr($dailyAktivitas->assignedTo->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $dailyAktivitas->assignedTo->name }}
                                                    <span class="text-xs text-gray-500">(Primary)</span>
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Multi-user assignments -->
                                        @if ($dailyAktivitas->assignedUsers->isNotEmpty())
                                            <div class="mt-2 space-y-1">
                                                @foreach ($dailyAktivitas->assignedUsers as $assignedUser)
                                                    <div class="flex items-center">
                                                        <div
                                                            class="flex-shrink-0 h-6 w-6 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                                            <span
                                                                class="text-xs font-medium text-green-600 dark:text-green-400">
                                                                {{ substr($assignedUser->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $assignedUser->name }}
                                                            <span
                                                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium 
                                                                {{ $assignedUser->pivot->role === 'assigned'
                                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                                    : ($assignedUser->pivot->role === 'watcher'
                                                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                                                        : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200') }}">
                                                                {{ ucfirst($assignedUser->pivot->role) }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Dibuat pada</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $dailyAktivitas->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            @if ($dailyAktivitas->updated_at != $dailyAktivitas->created_at)
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Terakhir diupdate
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $dailyAktivitas->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function updateStatus(status) {
            try {
                const response = await fetch(`{{ route('daily-aktivitas.update-status', $dailyAktivitas) }}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        status
                    })
                });

                const data = await response.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal mengupdate status');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Terjadi kesalahan saat mengupdate status');
            }
        }
    </script>
</x-app-layout>

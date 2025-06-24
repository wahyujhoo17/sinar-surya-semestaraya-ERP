<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="container mx-auto py-6">
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 mb-6">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 mr-3 text-primary-600 dark:text-primary-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail Log Aktivitas #{{ $logAktivitas->id }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">Informasi lengkap aktivitas pengguna dalam sistem
                        </p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row gap-3">
                        @if ($logAktivitas->data_id && $logAktivitas->modul)
                            <a href="{{ route('pengaturan.log-aktivitas.index', ['modul' => $logAktivitas->modul, 'data_id' => $logAktivitas->data_id]) }}"
                                class="inline-flex items-center px-4 py-2 border border-indigo-300 dark:border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-700 dark:text-indigo-300 bg-white dark:bg-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors duration-200"
                                title="Lihat semua log untuk data ini">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                Log Terkait
                            </a>
                        @endif
                        @if ($logAktivitas->user_id)
                            <a href="{{ route('pengaturan.log-aktivitas.index', ['user_id' => $logAktivitas->user_id]) }}"
                                class="inline-flex items-center px-4 py-2 border border-green-300 dark:border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200"
                                title="Lihat semua aktivitas pengguna ini">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Aktivitas User
                            </a>
                        @endif
                        <a href="{{ route('pengaturan.log-aktivitas.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Log Information Card -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Log
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            <!-- Activity -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Aktivitas</dt>
                                <dd>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium 
                                        @switch($logAktivitas->aktivitas)
                                            @case('create')
                                                bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @break
                                            @case('update')
                                                bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @break
                                            @case('delete')
                                                bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                @break
                                            @case('login')
                                                bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                                @break
                                            @case('logout')
                                                bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                                @break
                                            @default
                                                bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                        @endswitch">
                                        {{ ucfirst($logAktivitas->aktivitas) }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Module -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Modul</dt>
                                <dd>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        {{ ucfirst(str_replace('_', ' ', $logAktivitas->modul)) }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Timestamp -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Waktu</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $logAktivitas->created_at ? $logAktivitas->created_at->format('d/m/Y H:i:s') : 'Tidak tersedia' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $logAktivitas->created_at ? $logAktivitas->created_at->diffForHumans() : 'Tidak tersedia' }}
                                    </div>
                                </dd>
                            </div>

                            <!-- Data ID -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Data ID</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    @if ($logAktivitas->data_id)
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">
                                            #{{ $logAktivitas->data_id }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </dd>
                            </div>

                            <!-- IP Address -->
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">IP Address</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    @if ($logAktivitas->ip_address)
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center">
                                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                                </svg>
                                                <span class="font-mono">{{ $logAktivitas->ip_address }}</span>
                                            </span>
                                            <a href="{{ route('pengaturan.log-aktivitas.index', ['ip_address' => $logAktivitas->ip_address]) }}"
                                                class="text-xs text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                                                title="Lihat log lain dari IP ini">
                                                Lihat lainnya →
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </dd>
                            </div>

                            <!-- Summary -->
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Ringkasan</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                        <p class="text-sm">
                                            {{ ucfirst($logAktivitas->aktivitas) }} pada
                                            {{ ucfirst(str_replace('_', ' ', $logAktivitas->modul)) }}
                                            @if ($logAktivitas->data_id)
                                                #{{ $logAktivitas->data_id }}
                                            @endif
                                            oleh {{ $logAktivitas->user->name ?? 'User tidak diketahui' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $logAktivitas->created_at ? $logAktivitas->created_at->diffForHumans() : '' }}
                                        </p>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Activity Detail Card -->
                @if ($logAktivitas->detail)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Detail Aktivitas
                            </h3>
                        </div>
                        <div class="p-6">
                            @php
                                $isJsonDetail = false;
                                $parsedDetail = null;

                                // Try to decode JSON
                                if ($logAktivitas->detail) {
                                    $decoded = json_decode($logAktivitas->detail, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $isJsonDetail = true;
                                        $parsedDetail = $decoded;
                                    }
                                }
                            @endphp

                            @if ($isJsonDetail && $parsedDetail)
                                <!-- JSON Detail Display -->
                                <div class="space-y-4">
                                    @foreach ($parsedDetail as $key => $value)
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                            <dt
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            </dt>
                                            <dd class="text-sm text-gray-900 dark:text-white">
                                                @if (is_array($value))
                                                    @if (empty($value))
                                                        <span class="italic text-gray-500 dark:text-gray-400">Tidak ada
                                                            data</span>
                                                    @else
                                                        <div
                                                            class="bg-white dark:bg-gray-800 rounded border p-3 overflow-x-auto">
                                                            <table class="min-w-full">
                                                                <tbody class="space-y-2">
                                                                    @foreach ($value as $subKey => $subValue)
                                                                        <tr
                                                                            class="border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                                                            <td
                                                                                class="py-2 pr-4 text-xs font-medium text-gray-600 dark:text-gray-400 align-top min-w-0">
                                                                                {{ is_numeric($subKey) ? '#' . ($subKey + 1) : ucfirst(str_replace('_', ' ', $subKey)) }}:
                                                                            </td>
                                                                            <td
                                                                                class="py-2 text-xs text-gray-900 dark:text-white">
                                                                                @if (is_array($subValue))
                                                                                    <div
                                                                                        class="bg-gray-100 dark:bg-gray-700 rounded p-2">
                                                                                        <pre class="text-xs whitespace-pre-wrap">{{ json_encode($subValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                                    </div>
                                                                                @else
                                                                                    <span
                                                                                        class="break-words">{{ $subValue ?? '-' }}</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300">
                                                        {{ $value ?? '-' }}
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- String Detail Display -->
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div
                                        class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Detail Aktivitas
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded border p-3">
                                        <div
                                            class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap break-words">
                                            {{ $logAktivitas->detail }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- No Detail Message -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                        <div class="p-6 text-center">
                            <div
                                class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Detail</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Log aktivitas ini tidak memiliki informasi detail tambahan.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- User Information Card -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pengguna
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($logAktivitas->user)
                            <div class="text-center">
                                <!-- User Avatar -->
                                <div
                                    class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
                                    <span class="text-2xl font-medium text-primary-600 dark:text-primary-400">
                                        {{ substr($logAktivitas->user->name, 0, 2) }}
                                    </span>
                                </div>

                                <!-- User Name -->
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                    {{ $logAktivitas->user->name }}
                                </h4>

                                <!-- User Email -->
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    {{ $logAktivitas->user->email }}
                                </p>

                                <!-- User Stats -->
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                    <dl class="grid grid-cols-1 gap-3">
                                        <div class="text-center">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Log
                                                Aktivitas</dt>
                                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format(\App\Models\LogAktivitas::where('user_id', $logAktivitas->user_id)->count()) }}
                                            </dd>
                                        </div>
                                        <div class="text-center">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktivitas
                                                Hari Ini</dt>
                                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format(\App\Models\LogAktivitas::where('user_id', $logAktivitas->user_id)->whereDate('created_at', today())->count()) }}
                                            </dd>
                                        </div>
                                        <div class="text-center">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir
                                                Aktif</dt>
                                            <dd class="text-sm text-gray-900 dark:text-white">
                                                {{ \App\Models\LogAktivitas::where('user_id', $logAktivitas->user_id)->latest()->first()?->created_at?->diffForHumans() ?? 'Tidak diketahui' }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 space-y-3">
                                    <a href="{{ route('pengaturan.log-aktivitas.index', ['user_id' => $logAktivitas->user_id]) }}"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-primary-300 dark:border-primary-600 rounded-md shadow-sm text-sm font-medium text-primary-700 dark:text-primary-300 bg-white dark:bg-gray-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors duration-200">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 100 4 2 2 0 000-4z" />
                                        </svg>
                                        Lihat Semua Log User
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">User Tidak Ditemukan
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Data pengguna mungkin telah dihapus dari sistem.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Logs Card -->
                @if ($logAktivitas->data_id && $logAktivitas->modul)
                    @if ($logAktivitas->data_id && $logAktivitas->modul)
                        <!-- Related Logs Section -->
                        @if ($relatedLogs->count() > 0)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Log Terkait ({{ $relatedLogs->count() }})
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Aktivitas lain pada data yang sama
                                        ({{ ucfirst(str_replace('_', ' ', $logAktivitas->modul)) }}
                                        #{{ $logAktivitas->data_id }})
                                    </p>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-3">
                                        @foreach ($relatedLogs as $relatedLog)
                                            <div
                                                class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                                <div class="flex-shrink-0">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                        @switch($relatedLog->aktivitas)
                                                            @case('create')
                                                                bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                                                @break
                                                            @case('update')
                                                                bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                                @break
                                                            @case('delete')
                                                                bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                                                @break
                                                            @default
                                                                bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400
                                                        @endswitch">
                                                        {{ ucfirst($relatedLog->aktivitas) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ ucfirst($relatedLog->aktivitas) }}
                                                        </p>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $relatedLog->created_at ? $relatedLog->created_at->format('d/m H:i') : '-' }}
                                                        </span>
                                                    </div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                                        <svg class="w-3 h-3 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $relatedLog->user->name ?? 'User Tidak Ditemukan' }}
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('pengaturan.log-aktivitas.show', $relatedLog) }}"
                                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 p-1 rounded hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all duration-200"
                                                        title="Lihat detail log">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- User Recent Activities -->
                    @if ($userRecentLogs->count() > 0)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Aktivitas Terbaru Pengguna
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Aktivitas terbaru dari {{ $logAktivitas->user->name ?? 'pengguna ini' }}
                                </p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach ($userRecentLogs as $userLog)
                                        <div
                                            class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                            <div class="flex-shrink-0">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                                    {{ substr(ucfirst(str_replace('_', ' ', $userLog->modul)), 0, 3) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ ucfirst($userLog->aktivitas) }}
                                                    </p>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $userLog->created_at ? $userLog->created_at->diffForHumans() : '-' }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ ucfirst(str_replace('_', ' ', $userLog->modul)) }}
                                                    @if ($userLog->data_id)
                                                        • ID: {{ $userLog->data_id }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('pengaturan.log-aktivitas.show', $userLog) }}"
                                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 p-1 rounded hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all duration-200"
                                                    title="Lihat detail log">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

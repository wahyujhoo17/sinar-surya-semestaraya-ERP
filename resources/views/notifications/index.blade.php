<x-app-layout :breadcrumbs="[['label' => 'Notifikasi']]" :currentPage="'Notifikasi'">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <div
                            class="mr-3 p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        Notifikasi
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Kelola semua notifikasi dan pengingat Anda
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if ($notifications->where('read_at', null)->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif

                    <!-- Dropdown for cleanup actions -->
                    @if ($notifications->count() > 0)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Bersihkan
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 dark:divide-gray-700 z-10">

                                <div class="p-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Pilih jenis pembersihan:
                                    </p>

                                    <form id="cleanOldForm" action="{{ route('notifications.cleanOld') }}"
                                        method="POST" class="block">
                                        @csrf
                                        <button type="button"
                                            onclick="confirmCleanup('Apakah Anda yakin ingin menghapus notifikasi yang lebih lama dari 30 hari?', function() { document.getElementById('cleanOldForm').submit(); })"
                                            class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <div class="font-medium">Hapus Notifikasi Lama</div>
                                                    <div class="text-xs text-gray-500">Lebih dari 30 hari</div>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                </div>

                                <div class="p-3">
                                    <form id="deleteAllForm" action="{{ route('notifications.deleteAll') }}"
                                        method="POST" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete('Apakah Anda yakin ingin menghapus SEMUA notifikasi? Tindakan ini tidak dapat dibatalkan!', function() { document.getElementById('deleteAllForm').submit(); })"
                                            class="w-full text-left px-3 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors duration-200">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <div>
                                                    <div class="font-medium">Hapus Semua Notifikasi</div>
                                                    <div class="text-xs text-gray-500">Tidak dapat dibatalkan</div>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Stats Badge -->
                    <div
                        class="inline-flex items-center px-3 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg border border-blue-200 dark:border-blue-800">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                        {{ $notifications->where('read_at', null)->count() }} Belum Dibaca
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Container -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm"
            x-data="{ activeFilter: 'all' }">
            <!-- Filter Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-750/50">
                <div class="px-6 py-4">
                    <div class="flex space-x-8">
                        <button @click="activeFilter = 'all'"
                            :class="activeFilter === 'all' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Semua ({{ $notifications->total() }})
                        </button>
                        <button @click="activeFilter = 'unread'"
                            :class="activeFilter === 'unread' ? 'border-red-500 text-red-600 dark:text-red-400' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium transition-colors duration-200">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                            Belum Dibaca ({{ $notifications->where('read_at', null)->count() }})
                        </button>
                        <button @click="activeFilter = 'read'"
                            :class="activeFilter === 'read' ? 'border-green-500 text-green-600 dark:text-green-400' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Sudah Dibaca ({{ $notifications->where('read_at', '!=', null)->count() }})
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @forelse($notifications as $notification)
                    <div x-show="activeFilter === 'all' || 
                                (activeFilter === 'unread' && {{ is_null($notification->read_at) ? 'true' : 'false' }})
||
                                (activeFilter === 'read' && {{ is_null($notification->read_at) ? 'false' : 'true' }})"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="group relative transition-all duration-200 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 
                        {{ is_null($notification->read_at) ? 'bg-gradient-to-r from-blue-50/50 via-transparent to-transparent dark:from-blue-900/20' : '' }}
                        {{ $notification->link ? 'cursor-pointer' : '' }}">

                        <!-- Unread indicator -->
                        @if (is_null($notification->read_at))
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-600">
                            </div>
                        @endif

                        <!-- Make entire notification clickable if has link -->
                        <div class="p-6 pl-8 {{ $notification->link ? 'cursor-pointer' : '' }}"
                            @if ($notification->link) onclick="markAsReadAndNavigate(event, {{ $notification->id }}, '{{ $notification->link }}')" @endif>
                            <div class="flex items-start space-x-4">
                                <!-- Notification Icon -->
                                <div class="flex-shrink-0 mt-1">
                                    <div
                                        class="w-10 h-10 rounded-xl flex items-center justify-center
                                        {{ is_null($notification->read_at)
                                            ? 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                                        @php
                                            $type = $notification->type ?? 'default';
                                            $iconClass = match ($type) {
                                                'order' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                                                'payment'
                                                    => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
                                                'user'
                                                    => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                                'warning'
                                                    => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z',
                                                'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                default
                                                    => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                                            };
                                        @endphp
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $iconClass }}" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 pr-4">
                                            <!-- Title with read status -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <h3
                                                        class="text-base font-semibold text-gray-900 dark:text-white leading-tight
                                                        {{ is_null($notification->read_at) ? 'font-bold' : 'font-medium' }}
                                                        {{ $notification->link ? 'group-hover:text-blue-600 dark:group-hover:text-blue-400' : '' }}">
                                                        {{ $notification->title }}
                                                    </h3>
                                                    @if ($notification->link)
                                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                @if (is_null($notification->read_at))
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 dark:bg-blue-900/50 dark:text-blue-300 rounded-full flex-shrink-0">
                                                        Baru
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Message -->
                                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                                                {{ $notification->message }}
                                            </p>

                                            <!-- Link indicator for clickable notifications -->
                                            @if ($notification->link)
                                                <div
                                                    class="flex items-center text-xs text-blue-600 dark:text-blue-400 mb-2 opacity-70 group-hover:opacity-100 transition-opacity duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                    </svg>
                                                    <span class="font-medium">Klik untuk melihat detail</span>
                                                </div>
                                            @endif

                                            <!-- Timestamp and Read Status -->
                                            <div
                                                class="flex items-center text-xs text-gray-500 dark:text-gray-400 space-x-2">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="font-medium">{{ $notification->created_at->diffForHumans() }}</span>

                                                @if ($notification->read_at !== null)
                                                    <span class="text-gray-300 dark:text-gray-600">&bull;</span>
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-3 h-3 text-green-500" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-green-600 dark:text-green-400">Dibaca
                                                            {{ $notification->read_at->diffForHumans() }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div
                                    class="flex-shrink-0 flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    @if (is_null($notification->read_at))
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Tandai sebagai dibaca"
                                                onclick="event.stopPropagation()"
                                                class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($notification->link)
                                        <a href="{{ $notification->link }}" title="Lihat detail"
                                            onclick="event.stopPropagation(); markAsReadAndNavigate(event, {{ $notification->id }}, '{{ $notification->link }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif

                                    <form id="deleteNotification{{ $notification->id }}Form"
                                        action="{{ route('notifications.destroy', $notification->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="Hapus notifikasi"
                                            onclick="event.stopPropagation(); confirmDelete('Apakah Anda yakin ingin menghapus notifikasi ini?', function() { document.getElementById('deleteNotification{{ $notification->id }}Form').submit(); })"
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div
                            class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada notifikasi</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            Anda belum memiliki notifikasi. Notifikasi akan muncul di sini ketika ada aktivitas baru.
                        </p>
                    </div>
                @endforelse

                <!-- Dynamic Empty State for Filters -->
                <div x-show="activeFilter !== 'all' && 
                           ((activeFilter === 'unread' && {{ $notifications->where('read_at', null)->count() }} === 0) ||
                            (activeFilter === 'read' && {{ $notifications->where('read_at', '!=', null)->count() }} === 0))"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" class="p-12 text-center">
                    <div
                        class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0118 12a7.962 7.962 0 01-2 5.291m2-10.582A11.947 11.947 0 0112 2.5a11.947 11.947 0 01-6 10.209A11.947 11.947 0 0112 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2"
                        x-text="activeFilter === 'unread' ? 'Tidak ada notifikasi belum dibaca' : 'Tidak ada notifikasi yang sudah dibaca'">
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto"
                        x-text="activeFilter === 'unread' ? 'Semua notifikasi sudah dibaca. Notifikasi baru akan muncul di sini.' : 'Belum ada notifikasi yang ditandai sebagai dibaca.'">
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/30">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Existing notification functionality
            async function markAsReadAndNavigate(event, notificationId, url) {
                event.preventDefault();

                try {
                    // Mark notification as read first
                    const response = await fetch(`/notifications/${notificationId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON');
                    }

                    const data = await response.json();

                    if (data.success) {
                        // Navigate to the URL
                        window.location.href = url;
                    } else {
                        // If marking as read fails, still navigate
                        console.warn('Failed to mark notification as read:', data.message || data.error);
                        window.location.href = url;
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                    // Still navigate even if marking as read fails
                    window.location.href = url;
                }
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Enhanced notification styling for better link appearance */
            .notification-item:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .dark .notification-item:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }

            /* Smooth transitions for clickable notifications */
            .notification-clickable {
                transition: all 0.2s ease-in-out;
            }

            .notification-clickable:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
            }

            .dark .notification-clickable:hover {
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.25);
            }

            /* Link indicator animation */
            .link-indicator {
                opacity: 0;
                transform: translateY(5px);
                transition: all 0.3s ease;
            }

            .group:hover .link-indicator {
                opacity: 1;
                transform: translateY(0);
            }

            /* Better button spacing and alignment */
            .action-buttons {
                gap: 0.5rem;
            }

            /* Prevent text selection on clickable notifications */
            .notification-clickable {
                user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
            }
        </style>
    @endpush
</x-app-layout>

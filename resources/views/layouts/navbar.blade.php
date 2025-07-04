<!-- Top Navigation Bar -->
<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-30">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Left side - Hamburger and Search -->
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Right side - User menu and settings -->
            <div class="flex items-center gap-3">
                <!-- Dark mode toggle -->
                <button x-cloak @click="toggleDarkMode()"
                    class="p-2 rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <span class="sr-only">Toggle dark mode</span>
                    <!-- Sun icon -->
                    <svg x-show="darkMode" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon -->
                    <svg x-show="!darkMode" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Notifications dropdown -->
                <div x-data="notificationSystem()" class="relative">
                    <button @click="toggleNotifications"
                        class="p-2 relative rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-200"
                        :class="{ 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20': isOpen }">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'scale-110': isOpen }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Enhanced notification badge with pulse animation -->
                        <div x-show="unreadCount > 0" x-cloak
                            class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-gradient-to-r from-red-500 to-red-600 shadow-lg flex items-center justify-center ring-2 ring-white dark:ring-gray-800 animate-pulse notification-badge">
                            <span x-text="unreadCount" class="text-xs text-white font-bold"></span>
                        </div>
                    </button>

                    <!-- Enhanced Notifications Panel -->
                    <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="transform opacity-0 scale-95 translate-y-2" @click.away="isOpen = false"
                        class="absolute right-0 mt-3 w-96 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-gray-700 overflow-hidden z-50 border border-gray-200 dark:border-gray-700">

                        <!-- Header with gradient -->
                        <div
                            class="bg-gradient-to-r from-primary-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                                        <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notifikasi
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-show="unreadCount > 0"
                                            x-text="`${unreadCount} belum dibaca`"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-show="unreadCount === 0">
                                            Semua sudah dibaca</p>
                                    </div>
                                </div>
                                <template x-if="unreadCount > 0">
                                    <button @click="markAllAsRead"
                                        class="text-xs px-3 py-1 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition-colors duration-200 font-medium">
                                        Baca semua
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Notifications list with enhanced styling -->
                        <div class="max-h-96 overflow-y-auto notification-scroll">
                            <!-- Empty state with illustration -->
                            <div x-show="notifications.length === 0" class="px-6 py-12 text-center">
                                <div
                                    class="mx-auto h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Belum ada notifikasi</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Notifikasi baru akan muncul di
                                    sini</p>
                            </div>

                            <!-- Notification items with enhanced design -->
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="getNotificationUrl(notification)"
                                    @click="handleNotificationClick($event, notification)"
                                    class="group block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 border-b border-gray-100 dark:border-gray-700 last:border-b-0 notification-item"
                                    :class="{
                                        'bg-blue-50/50 dark:bg-blue-900/10 border-l-4 border-l-blue-500 pl-5': notification
                                            .read_at === null,
                                        'opacity-75': notification.read_at !== null
                                    }">
                                    <div class="flex items-start gap-3">
                                        <!-- Notification type icon -->
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                                :class="getNotificationIconClass(notification.type)">
                                                <span x-html="getNotificationIcon(notification.type)"></span>
                                            </div>
                                        </div>

                                        <!-- Notification content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200"
                                                    x-text="notification.title"></p>
                                                <template x-if="notification.read_at === null">
                                                    <div class="flex-shrink-0 h-2 w-2 bg-blue-500 rounded-full"></div>
                                                </template>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed"
                                                x-text="notification.message"></p>
                                            <div class="flex items-center justify-between mt-2">
                                                <p class="text-xs text-gray-400 dark:text-gray-500"
                                                    x-text="formatRelativeTime(notification.created_at)"></p>
                                                <div
                                                    class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    <svg class="h-3 w-3 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <!-- Footer with enhanced styling -->
                        <div
                            class="bg-gray-50 dark:bg-gray-750 px-6 py-3 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('notifications.index') }}"
                                class="flex items-center justify-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200 group">
                                <span>Lihat semua notifikasi</span>
                                <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="hidden md:block h-6 w-px bg-gray-200 dark:bg-gray-700"></div>

                <!-- User dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @if (auth()->user()->photo_url)
                            <img class="h-8 w-8 rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                                src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->display_name }}">
                        @else
                            <div
                                class="h-8 w-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                <span class="text-sm font-medium text-primary-700 dark:text-primary-300">
                                    {{ auth()->user()->initials }}
                                </span>
                            </div>
                        @endif
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ auth()->user()->display_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->display_email }}</p>
                        </div>
                    </button>
                    <!-- User Dropdown Panel -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Profil Anda
                            </a>
                            @if (auth()->user()->hasPermission('pengaturan_umum.view'))
                                <a href="/pengaturan/umum"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Pengaturan
                                </a>
                            @endif
                        </div>
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        function notificationSystem() {
            return {
                isOpen: false,
                notifications: [],
                unreadCount: 0,

                init() {
                    this.loadNotifications();
                    this.listenForNewNotifications();
                },

                toggleNotifications() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.loadNotifications();
                    }
                },

                async loadNotifications() {
                    try {
                        const response = await fetch('{{ route('notifications.latest') }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
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
                        if (data.error) {
                            throw new Error(data.message || data.error);
                        }
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                        this.notifications = [];
                        this.unreadCount = 0;
                    }
                },

                async markAsRead(notification) {
                    console.log('Marking as read:', notification.id, 'current read_at:', notification.read_at);

                    if (notification.read_at !== null) {
                        console.log('Already read, redirecting...');
                        window.location.href = this.getNotificationUrl(notification);
                        return;
                    }

                    try {
                        const response = await fetch(`/notifications/${notification.id}/mark-as-read`, {
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
                            this.unreadCount = data.unread_count;
                            notification.read_at = new Date().toISOString();
                            window.location.href = this.getNotificationUrl(notification);
                        } else {
                            throw new Error(data.message || data.error || 'Failed to mark as read');
                        }
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                        // Still navigate even if marking as read fails
                        window.location.href = this.getNotificationUrl(notification);
                    }
                },

                handleNotificationClick(event, notification) {
                    event.preventDefault();
                    this.markAsRead(notification);
                },

                async markAllAsRead() {
                    try {
                        const response = await fetch('{{ route('notifications.markAllAsRead') }}', {
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
                            this.unreadCount = 0;
                            this.notifications.forEach(notification => {
                                notification.read_at = new Date().toISOString();
                            });
                        } else {
                            throw new Error(data.message || data.error || 'Failed to mark all as read');
                        }
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
                        alert('Gagal menandai semua notifikasi sebagai telah dibaca');
                    }
                },

                getNotificationUrl(notification) {
                    // If notification has a specific link, use it
                    if (notification.link && notification.link !== '#') {
                        return notification.link;
                    }

                    // Otherwise, default to notifications index
                    return '{{ route('notifications.index') }}';
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                formatRelativeTime(date) {
                    const now = new Date();
                    const notificationDate = new Date(date);
                    const diffInSeconds = Math.floor((now - notificationDate) / 1000);

                    if (diffInSeconds < 60) {
                        return 'Baru saja';
                    } else if (diffInSeconds < 3600) {
                        const minutes = Math.floor(diffInSeconds / 60);
                        return `${minutes} menit yang lalu`;
                    } else if (diffInSeconds < 86400) {
                        const hours = Math.floor(diffInSeconds / 3600);
                        return `${hours} jam yang lalu`;
                    } else if (diffInSeconds < 604800) {
                        const days = Math.floor(diffInSeconds / 86400);
                        return `${days} hari yang lalu`;
                    } else {
                        return this.formatDate(date);
                    }
                },

                getNotificationIcon(type) {
                    const icons = {
                        'order': '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path></svg>',
                        'payment': '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>',
                        'warning': '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                        'success': '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                        'user': '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>',
                        'default': '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"></path></svg>'
                    };
                    return icons[type] || icons['default'];
                },

                getNotificationIconClass(type) {
                    const classes = {
                        'order': 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                        'payment': 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
                        'warning': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400',
                        'success': 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
                        'user': 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
                        'default': 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                    };
                    return classes[type] || classes['default'];
                },

                listenForNewNotifications() {
                    // If you have Laravel Echo set up
                    @auth
                    const userId = {{ auth()->id() }};
                    window.Echo?.private(`App.Models.User.${userId}`)
                        .notification((notification) => {
                            this.notifications.unshift(notification);
                            this.unreadCount++;
                        });
                @endauth
            }
        }
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Enhanced notification styles */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-badge {
            box-shadow: 0 0 0 2px white;
        }

        .dark .notification-badge {
            box-shadow: 0 0 0 2px rgb(31, 41, 55);
        }

        /* Smooth scrollbar for notifications */
        .notification-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .notification-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .notification-scroll::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 2px;
        }

        .notification-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }

        .dark .notification-scroll::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }

        .dark .notification-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.7);
        }

        /* Pulse animation for notification badge */
        @keyframes notification-pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .notification-badge.animate-pulse {
            animation: notification-pulse 2s infinite;
        }

        /* Enhanced hover effects */
        .notification-item:hover .notification-icon {
            transform: scale(1.1);
        }

        .notification-item .notification-icon {
            transition: transform 0.2s ease;
        }

        /* Gradient border for unread notifications */
        .notification-unread {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 197, 253, 0.05) 100%);
        }

        .dark .notification-unread {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(147, 197, 253, 0.08) 100%);
        }
    </style>
@endpush

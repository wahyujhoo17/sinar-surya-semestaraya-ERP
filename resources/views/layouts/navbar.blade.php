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
                        class="p-2 relative rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <div x-show="unreadCount > 0" x-cloak
                            class="notification-badge absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500">
                        </div>
                    </button>

                    <!-- Notifications Panel -->
                    <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95" @click.away="isOpen = false"
                        class="absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 dark:divide-gray-700">

                        <div class="px-4 py-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-200">Notifikasi</h3>
                        </div>

                        <div class="max-h-[calc(100vh-20rem)] overflow-y-auto">
                            <template x-if="notifications.length > 0">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="getNotificationUrl(notification)"
                                        @click.prevent="markAsRead(notification)"
                                        class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.read_at }">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-200"
                                            x-text="notification.data.title"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="notification.data.message"></p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"
                                            x-text="formatDate(notification.created_at)"></p>
                                    </a>
                                </template>
                            </template>
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
                                </div>
                            </template>
                        </div>

                        <div class="py-1">
                            <div class="flex justify-between px-4 py-2">
                                <a href="{{ route('notifications.index') }}"
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                    Lihat semua
                                </a>
                                <template x-if="unreadCount > 0">
                                    <button @click="markAllAsRead"
                                        class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                                        Tandai sudah dibaca
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="hidden md:block h-6 w-px bg-gray-200 dark:bg-gray-700"></div>

                <!-- User dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <img class="h-8 w-8 rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="">
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Administrator</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">admin@sinarsurya.com</p>
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
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Pengaturan
                            </a>
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
                        const response = await fetch('{{ route('notifications.latest') }}');
                        const data = await response.json();
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                    }
                },

                async markAsRead(notification) {
                    if (notification.read_at) {
                        window.location.href = this.getNotificationUrl(notification);
                        return;
                    }

                    try {
                        const response = await fetch(`/notifications/${notification.id}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.unreadCount = data.unread_count;
                            notification.read_at = new Date().toISOString();
                            window.location.href = this.getNotificationUrl(notification);
                        }
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                    }
                },

                async markAllAsRead() {
                    try {
                        const response = await fetch('{{ route('notifications.markAllAsRead') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.unreadCount = 0;
                            this.notifications.forEach(notification => {
                                notification.read_at = new Date().toISOString();
                            });
                        }
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
                    }
                },

                getNotificationUrl(notification) {
                    return notification.data.link || '{{ route('notifications.index') }}';
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

                listenForNewNotifications() {
                    // If you have Laravel Echo set up
                    window.Echo?.private(`App.Models.User.${userId}`)
                        .notification((notification) => {
                            this.notifications.unshift(notification);
                            this.unreadCount++;
                        });
                }
            }
        }
    </script>
@endpush

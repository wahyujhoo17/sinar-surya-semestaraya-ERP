<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" x-init="darkMode = localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
if (darkMode) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}"
    :class="{ 'dark': darkMode }">

<head>
    <!-- Prioritaskan CSS x-cloak agar flicker hilang -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - PT. Sinar Surya Semestaraya</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <!-- Styles & Scripts with Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- Sidebar enhancement styles -->
    <style>
        /* Enhanced mobile sidebar styles */
        @media (max-width: 1024px) {
            .touch-handler {
                z-index: 10;
            }
        }

        /* Add a subtle shadow to sidebar on light mode */
        .sidebar-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        /* Add a subtle glow to sidebar in dark mode */
        .dark .sidebar-glow {
            box-shadow: 0 0 15px rgba(30, 41, 59, 0.5);
        }

        /* Better scrollbar experience on mobile */
        .touch-pan-y {
            touch-action: pan-y;
        }

        /* Reduce motion for users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .sidebar-transition {
                transition: none !important;
            }
        }

        /* Active menu item styles with subtle indicator line */
        .menu-active-item {
            position: relative;
        }

        .menu-active-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.35rem;
            bottom: 0.35rem;
            width: 0.25rem;
            background: currentColor;
            border-radius: 0 0.25rem 0.25rem 0;
            opacity: 0.7;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true'
}" x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 flex md:hidden" style="display: none;">
            <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>

            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative flex flex-col flex-1 w-full max-w-xs bg-primary-800">
                <div class="absolute top-0 right-0 pt-2">
                    <button @click="sidebarOpen = false"
                        class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Sidebar -->
                <div class="flex-1 h-0 overflow-y-auto">
                    @include('layouts.sidebar')
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0 relative"
            :class="{ 'md:w-64': !sidebarCollapsed, 'md:w-20': sidebarCollapsed }">
            @include('layouts.sidebar')
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Main content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-4">
                @if (isset($header))
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $header }}</h1>
                        @if (isset($description))
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
                        @endif
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
        <!-- Toast Notification Component (Global) -->
        <x-toast-notification />

        <!-- Alert Modal Component (Global) -->
        <x-alert-modal />

        <!-- Existing Scripts -->
        @stack('scripts')
    </div>
    @stack('scripts')

    <script>
        // Fungsi helper global
        window.notify = function(message, type = 'info', title = null, timeout = 5000) {
            // Jika message adalah string yang mengandung HTML
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    message: message,
                    type: type,
                    title: title,
                    timeout: timeout
                }
            }));
        };

        window.showAlert = function(options) {
            window.dispatchEvent(new CustomEvent('alert-modal', {
                detail: options
            }));
        };

        // Helper untuk konfirmasi hapus
        window.confirmDelete = function(message, callback) {
            showAlert({
                title: 'Konfirmasi Hapus',
                message: message || 'Apakah Anda yakin ingin menghapus item ini?',
                type: 'error',
                confirmText: 'Hapus',
                cancelText: 'Batal',
                showConfirm: true,
                showCancel: true,
                onConfirm: callback,
            });
        };

        // Inisialisasi MutationObserver untuk mencegah alert lama muncul
        if (!window.alertObserver) {
            const selector =
                '.rounded-md.bg-green-50, .rounded-md.bg-red-50, .rounded-md.bg-blue-50, .rounded-md.bg-yellow-50, .bg-green-50, .bg-red-50, .bg-blue-50, .bg-yellow-50';

            window.alertObserver = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach(node => {
                            // Check if node is an element and matches our selector
                            if (node.nodeType === 1) {
                                // Check direct match
                                if (node.matches && node.matches(selector)) {
                                    if (!node.closest('[id="toast-notification"]')) {
                                        node.remove();
                                    }
                                }

                                // Check children match
                                if (node.querySelectorAll) {
                                    const alerts = node.querySelectorAll(selector);
                                    alerts.forEach(alert => {
                                        if (!alert.closest('[id="toast-notification"]')) {
                                            alert.remove();
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // Start observing before DOM is fully loaded to catch early additions
            window.alertObserver.observe(document, {
                childList: true,
                subtree: true
            });
        }

        // Flag untuk menghindari multiple notifications
        if (!window.notificationsInitialized) {
            window.notificationsInitialized = true;

            // Untuk memastikan kode hanya dijalankan sekali
            document.addEventListener('DOMContentLoaded', function() {
                let flashMessageShown = false;

                @if (session('success'))
                    notify({!! json_encode(session('success')) !!}, 'success', 'Berhasil');
                    flashMessageShown = true;
                @endif

                @if (session('error'))
                    notify({!! json_encode(session('error')) !!}, 'error', 'Error');
                    flashMessageShown = true;
                @endif

                @if (session('info'))
                    notify({!! json_encode(session('info')) !!}, 'info', 'Informasi');
                    flashMessageShown = true;
                @endif

                @if (session('warning'))
                    notify({!! json_encode(session('warning')) !!}, 'warning', 'Peringatan');
                    flashMessageShown = true;
                @endif

                // Hapus alert yang mungkin sudah ada di DOM
                const selector =
                    '.rounded-md.bg-green-50, .rounded-md.bg-red-50, .rounded-md.bg-blue-50, .rounded-md.bg-yellow-50, .bg-green-50, .bg-red-50, .bg-blue-50, .bg-yellow-50';
                document.querySelectorAll(selector).forEach(alert => {
                    if (!alert.closest('[id="toast-notification"]')) {
                        alert.remove();
                    }
                });
            });
        }
    </script>

    <!-- Untuk mencegah alert lama muncul sebelum JavaScript loaded -->
    <style>
        /* Hide old alerts by default */
        .bg-green-50:not(.notification-toast *),
        .bg-red-50:not(.notification-toast *),
        .bg-blue-50:not(.notification-toast *),
        .bg-yellow-50:not(.notification-toast *),
        .rounded-md.bg-green-50:not(.notification-toast *),
        .rounded-md.bg-red-50:not(.notification-toast *),
        .rounded-md.bg-blue-50:not(.notification-toast *),
        .rounded-md.bg-yellow-50:not(.notification-toast *) {
            display: none !important;
        }
    </style>

    <script>
        // Tambahkan listener untuk reset selectedProducts setelah halaman reload
        document.addEventListener('DOMContentLoaded', function() {
            // Cari semua Alpine components dengan properti selectedProducts
            const components = document.querySelectorAll('[x-data]');
            components.forEach(component => {
                if (component.__x && component.__x.$data.selectedProducts !== undefined) {
                    component.__x.$data.selectedProducts = [];
                    component.__x.$data.allSelected = false;
                }
            });
        });
    </script>
</body>

</html>

<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div x-data="hakAksesManager()" class="container mx-auto py-6">
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 mb-6">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Pengaturan Hak Akses</h1>
                <p class="text-gray-600 dark:text-gray-400">Atur hak akses untuk setiap role dalam sistem.</p>
            </div>
        </div>

        <!-- Notification -->
        <div x-show="successMessage" x-transition
            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md flex justify-between items-center">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span x-text="successMessage"></span>
            </div>
            <button @click="successMessage = ''" class="text-green-700 hover:text-green-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Main Content -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <!-- Filter & Search Section -->
                <div class="flex flex-col lg:flex-row justify-between mb-6 space-y-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-grow">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2 lg:w-64">
                                <label for="filter-role"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                                    Role:</label>
                                <select id="filter-role" x-model="selectedRole"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                                    <option value="all">Semua Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama ?? $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-1/2 lg:w-64">
                                <label for="filter-modul"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                                    Modul:</label>
                                <select id="filter-modul" x-model="selectedModul"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                                    <option value="all">Semua Modul</option>
                                    <template x-for="modul in availableModules" :key="modul">
                                        <option :value="modul" x-text="formatModulName(modul)"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-64">
                        <label for="search-permission"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari
                            Permission:</label>
                        <div class="relative">
                            <input type="search" id="search-permission" x-model="searchQuery"
                                placeholder="Cari permission..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 pl-10 dark:text-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-auto flex lg:items-end">
                        <div class="flex items-center mt-6">
                            <button type="button" @click="toggleAllPermissions(true)"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-l text-sm">
                                Pilih Semua
                            </button>
                            <button type="button" @click="toggleAllPermissions(false)"
                                class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 rounded-r text-sm">
                                Batalkan Semua
                            </button>
                        </div>
                    </div>
                </div>

                <!-- View Type Selector -->
                <div class="mb-6 flex items-center space-x-4 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tampilan:</span>
                    <button @click="viewType = 'grouped'"
                        :class="{ 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400': viewType === 'grouped', 'text-gray-600 dark:text-gray-400': viewType !== 'grouped' }"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                        Grup Modul
                    </button>
                    <button @click="viewType = 'table'"
                        :class="{ 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400': viewType === 'table', 'text-gray-600 dark:text-gray-400': viewType !== 'table' }"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                        Tabel
                    </button>
                </div>

                <form id="permissions-form" method="POST" action="{{ route('pengaturan.hak-akses.update') }}"
                    @submit.prevent="submitForm">
                    @csrf

                    <!-- Loading Indicator -->
                    <div x-show="isLoading" class="flex justify-center items-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
                    </div>

                    <!-- Grouped by Module View -->
                    <div x-show="viewType === 'grouped' && !isLoading" x-transition>
                        <template x-if="filteredRoles.length === 0">
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p>Tidak ada role yang ditemukan.</p>
                            </div>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(modulPermissions, modulName) in groupedPermissions"
                                :key="modulName">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <!-- Module Header -->
                                    <div @click="toggleModule(modulName)"
                                        class="bg-gray-50 dark:bg-gray-700/70 px-4 py-3 cursor-pointer flex items-center justify-between">
                                        <h3 class="font-medium text-gray-800 dark:text-white"
                                            x-text="formatModulName(modulName)"></h3>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="`${modulPermissions.length} permission`"></span>
                                            <svg :class="{ 'transform rotate-180': openModules[modulName] }"
                                                class="w-5 h-5 transition-transform"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Permission Table -->
                                    <div x-show="openModules[modulName]" x-transition class="overflow-x-auto">
                                        <table class="min-w-full">
                                            <thead class="bg-gray-50 dark:bg-gray-800">
                                                <tr>
                                                    <th
                                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Permission</th>
                                                    <template x-for="role in filteredRoles" :key="role.id">
                                                        <th class="px-2 py-3 text-center">
                                                            <div class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300"
                                                                x-text="role.nama || role.name"></div>
                                                        </th>
                                                    </template>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="permission in modulPermissions"
                                                    :key="permission.id">
                                                    <tr
                                                        class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                        <td class="px-4 py-3">
                                                            <div class="flex flex-col">
                                                                <span
                                                                    class="text-sm font-medium text-gray-800 dark:text-white"
                                                                    x-text="permission.nama || permission.name"></span>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                                                    x-text="permission.deskripsi || '-'"></span>
                                                            </div>
                                                        </td>
                                                        <template x-for="role in filteredRoles" :key="role.id">
                                                            <td class="px-2 py-3 text-center">
                                                                <label class="inline-flex">
                                                                    <input type="checkbox"
                                                                        :name="`permissions[${role.id}][]`"
                                                                        :value="permission.id"
                                                                        :checked="hasPermission(role, permission)"
                                                                        @change="togglePermission(role, permission)"
                                                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                                </label>
                                                            </td>
                                                        </template>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Original Table View -->
                    <div x-show="viewType === 'table' && !isLoading" x-transition class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-left font-medium text-sm text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Role / Permission
                                    </th>
                                    <template x-for="permission in filteredPermissionsForTableView"
                                        :key="permission.id">
                                        <th class="px-2 py-4 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider group"
                                            :title="permission.deskripsi || 'Tidak ada deskripsi'">
                                            <div class="whitespace-nowrap px-1"
                                                x-text="permission.nama || permission.name"></div>
                                            <div class="text-xxs text-gray-500 dark:text-gray-400 mt-1"
                                                x-text="formatModulName(permission.modul) || '—'"></div>
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="role in filteredRoles" :key="role.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td
                                            class="px-6 py-4 border-b border-r border-gray-200 dark:border-gray-700 text-sm">
                                            <div class="font-medium text-gray-800 dark:text-white"
                                                x-text="role.nama || role.name"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="role.deskripsi || '—'"></div>
                                        </td>
                                        <template x-for="permission in filteredPermissionsForTableView"
                                            :key="permission.id">
                                            <td
                                                class="px-2 py-2 border-b border-gray-200 dark:border-gray-700 text-center">
                                                <label class="inline-flex">
                                                    <input type="checkbox" :name="`permissions[${role.id}][]`"
                                                        :value="permission.id"
                                                        :checked="hasPermission(role, permission)"
                                                        @change="togglePermission(role, permission)"
                                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                </label>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" :disabled="isSubmitting"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 focus:ring-offset-primary-200 text-white rounded-lg transition ease-in-out duration-150 shadow-md disabled:opacity-50">
                            <template x-if="isSubmitting">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function hakAksesManager() {
                return {
                    isLoading: false,
                    isSubmitting: false,
                    successMessage: "{{ session('success') }}",
                    selectedRole: 'all',
                    selectedModul: 'all',
                    searchQuery: '',
                    viewType: 'grouped', // grouped or table
                    openModules: {}, // Track which modules are open/expanded
                    roles: @json($roles),
                    permissions: @json($permissions),
                    rolePermissions: {},

                    init() {
                        // Inisialisasi role permissions dari data server
                        this.roles.forEach(role => {
                            this.rolePermissions[role.id] = role.permissions.map(p => p.id);
                        });

                        // Default semua modul terbuka
                        this.availableModules.forEach(modul => {
                            this.openModules[modul] = true;
                        });
                    },

                    get availableModules() {
                        // Get unique modules from permissions
                        return [...new Set(this.permissions.map(p => p.modul))].sort();
                    },

                    get filteredRoles() {
                        if (this.selectedRole === 'all') {
                            return this.roles;
                        } else {
                            return this.roles.filter(role => role.id == this.selectedRole);
                        }
                    },

                    get filteredPermissionsForTableView() {
                        let filtered = this.permissions;

                        // Filter by module if selected
                        if (this.selectedModul !== 'all') {
                            filtered = filtered.filter(p => p.modul === this.selectedModul);
                        }

                        // Filter by search query
                        if (this.searchQuery) {
                            const query = this.searchQuery.toLowerCase();
                            filtered = filtered.filter(permission => {
                                const name = (permission.nama || permission.name || '').toLowerCase();
                                const modul = (permission.modul || '').toLowerCase();
                                const deskripsi = (permission.deskripsi || '').toLowerCase();

                                return name.includes(query) || modul.includes(query) || deskripsi.includes(query);
                            });
                        }

                        return filtered;
                    },

                    get groupedPermissions() {
                        // Group permissions by module
                        const filtered = this.filteredPermissionsForTableView;
                        const grouped = {};

                        filtered.forEach(permission => {
                            const modul = permission.modul || 'other';
                            if (!grouped[modul]) {
                                grouped[modul] = [];
                            }
                            grouped[modul].push(permission);
                        });

                        // Sort permissions within each module by name
                        Object.keys(grouped).forEach(modul => {
                            grouped[modul].sort((a, b) => {
                                const nameA = (a.nama || a.name || '').toLowerCase();
                                const nameB = (b.nama || b.name || '').toLowerCase();
                                return nameA.localeCompare(nameB);
                            });
                        });

                        return grouped;
                    },

                    formatModulName(modul) {
                        if (!modul) return 'Lainnya';
                        // Convert snake_case to Title Case
                        return modul.split('_').map(word =>
                            word.charAt(0).toUpperCase() + word.slice(1)
                        ).join(' ');
                    },

                    toggleModule(modulName) {
                        this.openModules[modulName] = !this.openModules[modulName];
                    },

                    toggleAllPermissions(value) {
                        const roleIds = this.filteredRoles.map(r => r.id);
                        let permissionIds = this.filteredPermissionsForTableView.map(p => p.id);

                        roleIds.forEach(roleId => {
                            if (value) {
                                // Set all permissions
                                this.rolePermissions[roleId] = [
                                    ...new Set([
                                        ...(this.rolePermissions[roleId] || []),
                                        ...permissionIds
                                    ])
                                ];
                            } else {
                                // Remove filtered permissions
                                this.rolePermissions[roleId] = (this.rolePermissions[roleId] || [])
                                    .filter(id => !permissionIds.includes(id));
                            }
                        });
                    },

                    hasPermission(role, permission) {
                        return this.rolePermissions[role.id]?.includes(permission.id);
                    },

                    togglePermission(role, permission) {
                        if (!this.rolePermissions[role.id]) {
                            this.rolePermissions[role.id] = [];
                        }

                        const index = this.rolePermissions[role.id].indexOf(permission.id);
                        if (index === -1) {
                            this.rolePermissions[role.id].push(permission.id);
                        } else {
                            this.rolePermissions[role.id].splice(index, 1);
                        }
                    },

                    async submitForm() {
                        this.isSubmitting = true;

                        try {
                            const form = document.getElementById('permissions-form');
                            const formData = new FormData(form);

                            // Tambahkan CSRF token
                            formData.append('_token', '{{ csrf_token() }}');

                            // Bersihkan dan tambahkan data permissions
                            for (const [roleId, permissions] of Object.entries(this.rolePermissions)) {
                                permissions.forEach(permissionId => {
                                    formData.append(`permissions[${roleId}][]`, permissionId);
                                });
                            }

                            const response = await fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (response.ok) {
                                this.successMessage = 'Hak akses berhasil diperbarui.';

                                // Scroll ke atas
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });

                                // Hilangkan pesan setelah 5 detik
                                setTimeout(() => {
                                    this.successMessage = '';
                                }, 5000);
                            } else {
                                console.error('Error saving permissions');
                                alert('Terjadi kesalahan saat menyimpan hak akses.');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menyimpan hak akses.');
                        } finally {
                            this.isSubmitting = false;
                        }
                    }
                };
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .text-xxs {
                font-size: 0.65rem;
            }

            /* Custom checkbox style */
            input[type="checkbox"] {
                cursor: pointer;
            }

            /* Responsive table */
            @media (max-width: 640px) {
                .overflow-x-auto {
                    -webkit-overflow-scrolling: touch;
                }
            }
        </style>
    @endpush
</x-app-layout>

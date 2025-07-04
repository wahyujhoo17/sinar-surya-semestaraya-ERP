<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div x-data="managementPengguna()" class="container mx-auto py-6">
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Management Pengguna & Role</h1>
                        <p class="text-gray-600 dark:text-gray-400">Kelola pengguna sistem, role, dan permissions mereka.
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex space-x-2">
                        <button @click="activeTab === 'users' ? openCreateModal() : openCreateRoleModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span x-text="activeTab === 'users' ? 'Tambah Pengguna' : 'Tambah Role'"></span>
                        </button>
                        <button @click="bulkDeleteSelected()" x-show="selectedUsers.length > 0 && activeTab === 'users'"
                            x-transition
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus Terpilih (<span x-text="selectedUsers.length"></span>)
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="mt-6 border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'users'; loadUsers()"
                            :class="activeTab === 'users' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                Pengguna
                            </div>
                        </button>
                        <button @click="activeTab = 'roles'; loadRoles()"
                            :class="activeTab === 'roles' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Role & Permissions
                            </div>
                        </button>
                    </nav>
                </div>
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

        <div x-show="errorMessage" x-transition
            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md flex justify-between items-center">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span x-text="errorMessage"></span>
            </div>
            <button @click="errorMessage = ''" class="text-red-700 hover:text-red-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Filter & Search Section -->
        <div x-show="activeTab === 'users'"
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari
                            Pengguna:</label>
                        <input type="text" id="search" x-model="filters.search"
                            @input.debounce.300ms="loadUsers()" placeholder="Nama, email, atau username..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                    </div>

                    <!-- Role Filter -->
                    <div>
                        <label for="role-filter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                            Role:</label>
                        <select id="role-filter" x-model="filters.role" @change="loadUsers()"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                            <option value="">Semua Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nama ?? $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status-filter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                            Status:</label>
                        <select id="status-filter" x-model="filters.status" @change="loadUsers()"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Search Section -->
        <div x-show="activeTab === 'roles'"
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="role-search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Role:</label>
                        <input type="text" id="role-search" x-model="roleFilters.search"
                            @input.debounce.300ms="loadRoles()" placeholder="Nama role, kode, atau deskripsi..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div x-show="activeTab === 'users'"
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" @change="toggleSelectAll($event)"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('name')">
                                <div class="flex items-center space-x-1">
                                    <span>Nama</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('email')">
                                <div class="flex items-center space-x-1">
                                    <span>Email</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                @click="sortBy('created_at')">
                                <div class="flex items-center space-x-1">
                                    <span>Dibuat</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody x-show="!loading"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600"
                        id="users-table-body">
                        @include('pengaturan.management_pengguna._table_body', ['users' => $users])
                    </tbody>
                </table>

                <!-- Loading State -->
                <div x-show="loading" class="p-8 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Memuat data...</p>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && users.length === 0" class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada pengguna</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan pengguna baru.
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div x-show="!loading && pagination && pagination.total > 0 && activeTab === 'users'"
                class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-600 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <button @click="changePage(pagination.current_page + 1)"
                        :disabled="pagination.current_page >= pagination.last_page"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Menampilkan
                            <span class="font-medium" x-text="pagination.from || 0"></span>
                            sampai
                            <span class="font-medium" x-text="pagination.to || 0"></span>
                            dari
                            <span class="font-medium" x-text="pagination.total || 0"></span>
                            hasil
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            <button @click="changePage(pagination.current_page - 1)"
                                :disabled="pagination.current_page <= 1"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <template x-for="page in paginationPages" :key="page">
                                <button @click="changePage(page)"
                                    :class="page === pagination.current_page ?
                                        'z-10 bg-primary-50 dark:bg-primary-900 border-primary-500 text-primary-600 dark:text-primary-300' :
                                        'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600'"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    <span x-text="page"></span>
                                </button>
                            </template>

                            <button @click="changePage(pagination.current_page + 1)"
                                :disabled="pagination.current_page >= pagination.last_page"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div x-show="activeTab === 'roles'"
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Role
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kode
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jumlah Pengguna
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody x-show="!roleLoading"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600"
                        id="roles-table-body">
                        <template x-for="role in roles" :key="role.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                x-text="role.nama"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                        x-text="role.kode"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white"
                                        x-text="role.deskripsi || 'Tidak ada deskripsi'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <span x-text="role.users_count || 0"></span> pengguna
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Edit Button -->
                                        <button @click="openEditRoleModal(role)"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                            title="Edit Role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button @click="openDeleteRoleModal(role)"
                                            :class="['admin', 'user', 'super_admin'].includes(role.kode) ?
                                                'opacity-50 cursor-not-allowed' :
                                                'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300'"
                                            :disabled="['admin', 'user', 'super_admin'].includes(role.kode)"
                                            class="transition-colors duration-150" title="Hapus Role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Role Loading State -->
                <div x-show="roleLoading" class="p-8 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Memuat data role...</p>
                </div>

                <!-- Role Empty State -->
                <div x-show="!roleLoading && roles.length === 0" class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada role</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan role baru.</p>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="showModal" x-transition.opacity
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeModal()">
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="modalTitle"></h3>
                        <button @click="closeModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="submitForm()">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="name" x-model="formData.name" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                            </div>

                            <div class="md:col-span-2">
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" id="email" x-model="formData.email" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                            </div>

                            <div x-show="!editMode">
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password
                                    <span class="text-red-500">*</span></label>
                                <input type="password" id="password" x-model="formData.password"
                                    :required="!editMode"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                            </div>

                            <div x-show="!editMode">
                                <label for="user_password_confirmation"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi
                                    Password <span class="text-red-500">*</span></label>
                                <input type="password" id="user_password_confirmation"
                                    x-model="formData.password_confirmation" :required="!editMode"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                            </div>

                            <div class="md:col-span-2">
                                <label for="roles"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role <span
                                        class="text-red-500">*</span></label>
                                <select id="roles" x-model="formData.roles" multiple required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama ?? $role->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tahan Ctrl (Windows) atau Cmd
                                    (Mac) untuk memilih multiple role</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="formData.is_active"
                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pengguna Aktif</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batal
                            </button>
                            <button type="submit" :disabled="loading"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
                                <span x-show="!loading" x-text="editMode ? 'Update' : 'Simpan'"></span>
                                <span x-show="loading">Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Detail Modal -->
        <div x-show="showViewModal" x-transition.opacity
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeViewModal()">
            <div
                class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div
                                    class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-xl font-bold text-white"
                                        x-text="viewUser.name ? viewUser.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : ''"></span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="viewUser.name">
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Detail Informasi Pengguna</p>
                            </div>
                        </div>
                        <button @click="closeViewModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Personal
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama
                                        Lengkap</label>
                                    <p class="text-sm text-gray-900 dark:text-white font-medium"
                                        x-text="viewUser.name"></p>
                                </div>
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</label>
                                    <p class="text-sm text-gray-900 dark:text-white" x-text="viewUser.username"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="text-sm text-gray-900 dark:text-white" x-text="viewUser.email"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                    <div class="flex items-center mt-1">
                                        <div :class="viewUser.is_active ?
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <div :class="viewUser.is_active ? 'bg-green-400' : 'bg-red-400'"
                                                class="w-2 h-2 rounded-full mr-1"></div>
                                            <span x-text="viewUser.is_active ? 'Aktif' : 'Tidak Aktif'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Role & Permissions
                            </h4>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Role yang
                                    Dimiliki</label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <template x-for="role in viewUser.roles" :key="role.id">
                                        <span
                                            :class="role.kode === 'admin' ?
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                                (role.kode === 'manager' ?
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200')"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span x-text="role.nama || role.name"></span>
                                        </span>
                                    </template>
                                    <div x-show="!viewUser.roles || viewUser.roles.length === 0"
                                        class="text-sm text-gray-500 dark:text-gray-400 italic">
                                        Tidak ada role yang ditetapkan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Information (if exists) -->
                        <div x-show="viewUser.karyawan" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z">
                                    </path>
                                </svg>
                                Informasi Karyawan
                            </h4>
                            <div class="space-y-3">
                                <div x-show="viewUser.karyawan?.nip">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">NIP</label>
                                    <p class="text-sm text-gray-900 dark:text-white" x-text="viewUser.karyawan?.nip">
                                    </p>
                                </div>
                                <div x-show="viewUser.karyawan?.jabatan">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</label>
                                    <p class="text-sm text-gray-900 dark:text-white"
                                        x-text="viewUser.karyawan?.jabatan"></p>
                                </div>
                                <div x-show="viewUser.karyawan?.departemen">
                                    <label
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400">Departemen</label>
                                    <p class="text-sm text-gray-900 dark:text-white"
                                        x-text="viewUser.karyawan?.departemen"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Akun
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal
                                        Dibuat</label>
                                    <p class="text-sm text-gray-900 dark:text-white"
                                        x-text="viewUser.created_at_formatted"></p>
                                </div>
                                <div x-show="viewUser.last_login_at">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Login
                                        Terakhir</label>
                                    <p class="text-sm text-gray-900 dark:text-white"
                                        x-text="viewUser.last_login_at_formatted || 'Belum pernah login'"></p>
                                </div>
                                <div x-show="viewUser.updated_at_formatted">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir
                                        Diupdate</label>
                                    <p class="text-sm text-gray-900 dark:text-white"
                                        x-text="viewUser.updated_at_formatted"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <button @click="closeViewModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Tutup
                        </button>
                        <button @click="editFromView()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Pengguna
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-transition.opacity
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeDeleteModal()">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Hapus Pengguna</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="deleteMessage"></p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button @click="confirmDelete()"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Hapus
                        </button>
                        <button @click="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md w-24 hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Password Modal -->
        <div x-show="showResetPasswordModal" x-transition.opacity
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeResetPasswordModal()">
            <div
                class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Reset Password
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400"
                                    x-text="'Reset password untuk: ' + resetPasswordUser.name"></p>
                            </div>
                        </div>
                        <button @click="closeResetPasswordModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="confirmResetPassword()">
                        <div class="space-y-4">
                            <!-- New Password -->
                            <div>
                                <label for="new_password"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showNewPassword ? 'text' : 'password'" id="new_password"
                                        x-model="resetPasswordForm.new_password" required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:text-white pr-10"
                                        placeholder="Masukkan password baru (min. 8 karakter)">
                                    <button type="button" @click="showNewPassword = !showNewPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg x-show="!showNewPassword" class="h-5 w-5 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showNewPassword" class="h-5 w-5 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="reset_password_confirmation"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showConfirmPassword ? 'text' : 'password'"
                                        id="reset_password_confirmation"
                                        x-model="resetPasswordForm.password_confirmation" required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:text-white pr-10"
                                        placeholder="Ulangi password baru">
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg x-show="!showConfirmPassword" class="h-5 w-5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="h-5 w-5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Force Change Password Option -->
                            <div class="flex items-center">
                                <input type="checkbox" id="force_change" x-model="resetPasswordForm.force_change"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="force_change" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Paksa pengguna mengganti password saat login berikutnya
                                </label>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div x-show="resetPasswordForm.new_password.length > 0" class="text-xs">
                                <div class="flex space-x-1 mb-1">
                                    <div :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-gray-300'"
                                        class="flex-1 h-1 rounded"></div>
                                    <div :class="passwordStrength >= 2 ? 'bg-yellow-500' : 'bg-gray-300'"
                                        class="flex-1 h-1 rounded"></div>
                                    <div :class="passwordStrength >= 3 ? 'bg-green-500' : 'bg-gray-300'"
                                        class="flex-1 h-1 rounded"></div>
                                    <div :class="passwordStrength >= 4 ? 'bg-green-600' : 'bg-gray-300'"
                                        class="flex-1 h-1 rounded"></div>
                                </div>
                                <p :class="passwordStrength === 1 ? 'text-red-600' : passwordStrength === 2 ? 'text-yellow-600' :
                                    passwordStrength >= 3 ? 'text-green-600' : 'text-gray-500'"
                                    x-text="passwordStrengthText"></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" @click="closeResetPasswordModal()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="!isResetPasswordFormValid || loading"
                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                                <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span x-text="loading ? 'Memproses...' : 'Reset Password'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role Create/Edit Modal -->
        <div x-show="showRoleModal" x-transition.opacity
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeRoleModal()">
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="roleModalTitle"></h3>
                        <button @click="closeRoleModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="submitRoleForm()">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="role_nama"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Role
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="role_nama" x-model="roleFormData.nama" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:text-white"
                                    placeholder="Contoh: Manager">
                            </div>

                            <div>
                                <label for="role_kode"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Role
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="role_kode" x-model="roleFormData.kode" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:text-white"
                                    placeholder="Contoh: manager">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kode unik untuk role (huruf
                                    kecil, tanpa spasi)</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="role_deskripsi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                                <textarea id="role_deskripsi" x-model="roleFormData.deskripsi" rows="3"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:text-white"
                                    placeholder="Deskripsi singkat tentang role ini..."></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" @click="closeRoleModal()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batal
                            </button>
                            <button type="submit" :disabled="roleLoading"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                <span x-show="!roleLoading" x-text="roleEditMode ? 'Update' : 'Simpan'"></span>
                                <span x-show="roleLoading">Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role Delete Confirmation Modal -->
        <div x-show="showDeleteRoleModal" x-transition.opacity
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeDeleteRoleModal()">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Hapus Role</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="deleteRoleMessage"></p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button @click="confirmDeleteRole()"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Hapus
                        </button>
                        <button @click="closeDeleteRoleModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md w-24 hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function managementPengguna() {
            return {
                // Active tab state
                activeTab: 'users',

                // State
                users: [],
                roles: [],
                loading: false,
                roleLoading: false,
                successMessage: '',
                errorMessage: '',
                selectedUsers: [],

                // Modal state
                showModal: false,
                showDeleteModal: false,
                showViewModal: false,
                showResetPasswordModal: false,
                showRoleModal: false,
                showDeleteRoleModal: false,
                editMode: false,
                roleEditMode: false,
                modalTitle: 'Tambah Pengguna',
                roleModalTitle: 'Tambah Role',
                deleteMessage: '',
                deleteRoleMessage: '',
                userToDelete: null,
                roleToDelete: null,
                viewUser: {},
                resetPasswordUser: {},

                // Reset Password Form
                resetPasswordForm: {
                    new_password: '',
                    password_confirmation: '',
                    force_change: true
                },
                showNewPassword: false,
                showConfirmPassword: false,

                // Form data
                formData: {
                    id: null,
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    roles: [],
                    is_active: true
                },

                // Role form data
                roleFormData: {
                    id: null,
                    nama: '',
                    kode: '',
                    deskripsi: ''
                },

                // Filters and sorting
                filters: {
                    search: '',
                    role: '',
                    status: ''
                },
                roleFilters: {
                    search: ''
                },
                sorting: {
                    field: 'name',
                    direction: 'asc'
                },

                // Pagination
                pagination: {},

                // Computed property for pagination pages
                get paginationPages() {
                    const pages = [];
                    const current = this.pagination.current_page || 1;
                    const total = this.pagination.last_page || 1;
                    const delta = 2;

                    const range = {
                        start: Math.max(2, current - delta),
                        end: Math.min(total - 1, current + delta)
                    };

                    if (current - delta > 2) {
                        pages.push(1, '...');
                    } else {
                        pages.push(1);
                    }

                    for (let i = range.start; i <= range.end; i++) {
                        pages.push(i);
                    }

                    if (current + delta < total - 1) {
                        pages.push('...', total);
                    } else if (total > 1) {
                        pages.push(total);
                    }

                    return pages.filter(page => page !== 1 || total === 1);
                },

                // Password strength validation
                get passwordStrength() {
                    const password = this.resetPasswordForm.new_password;
                    let strength = 0;

                    if (password.length >= 8) strength++;
                    if (/[A-Z]/.test(password)) strength++;
                    if (/[a-z]/.test(password)) strength++;
                    if (/\d/.test(password)) strength++;
                    if (/[^A-Za-z0-9]/.test(password)) strength++;

                    return Math.min(strength, 4);
                },

                get passwordStrengthText() {
                    const strength = this.passwordStrength;
                    if (strength === 1) return 'Lemah';
                    if (strength === 2) return 'Sedang';
                    if (strength === 3) return 'Kuat';
                    if (strength === 4) return 'Sangat Kuat';
                    return '';
                },

                get isResetPasswordFormValid() {
                    return this.resetPasswordForm.new_password.length >= 8 &&
                        this.resetPasswordForm.new_password === this.resetPasswordForm.password_confirmation;
                },

                init() {
                    // Initialize data from server
                    this.users = @json($users->items() ?? []);
                    this.roles = []; // Will be loaded when roles tab is activated
                    this.pagination = @json($users->toArray());

                    @if (session('success'))
                        this.successMessage = '{{ session('success') }}';
                        setTimeout(() => this.successMessage = '', 5000);
                    @endif

                    @if (session('error'))
                        this.errorMessage = '{{ session('error') }}';
                        setTimeout(() => this.errorMessage = '', 5000);
                    @endif

                    // Attach event listeners for table buttons after DOM is ready
                    this.$nextTick(() => {
                        this.attachTableEventListeners();
                    });
                },

                // Attach event listeners to dynamically loaded table content
                attachTableEventListeners() {
                    // This will be called after table content is updated
                    setTimeout(() => {
                        // Update checkboxes state
                        document.querySelectorAll('input[name="selected_users[]"]').forEach(checkbox => {
                            checkbox.checked = this.selectedUsers.includes(parseInt(checkbox.value));
                        });
                    }, 100);
                },

                // Load users with filters and pagination
                async loadUsers(page = 1) {
                    this.loading = true;
                    try {
                        const params = new URLSearchParams({
                            page: page,
                            search: this.filters.search,
                            role: this.filters.role,
                            status: this.filters.status,
                            sort_field: this.sorting.field,
                            sort_direction: this.sorting.direction
                        });

                        const response = await fetch(`{{ route('pengaturan.management-pengguna.index') }}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.users = data.users.data;
                            this.pagination = data.users;

                            // Update table body
                            document.getElementById('users-table-body').innerHTML = data.table_body;
                            this.selectedUsers = [];

                            // Reattach event listeners to new table content
                            this.attachTableEventListeners();
                        }
                    } catch (error) {
                        console.error('Error loading users:', error);
                        this.errorMessage = 'Gagal memuat data pengguna';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                // Selection
                toggleSelectAll(event) {
                    if (event.target.checked) {
                        this.selectedUsers = this.users.map(user => user.id);
                    } else {
                        this.selectedUsers = [];
                    }

                    // Update individual checkboxes
                    document.querySelectorAll('input[name="selected_users[]"]').forEach(checkbox => {
                        checkbox.checked = event.target.checked;
                    });
                },

                toggleUserSelection(userId) {
                    const index = this.selectedUsers.indexOf(userId);
                    if (index > -1) {
                        this.selectedUsers.splice(index, 1);
                    } else {
                        this.selectedUsers.push(userId);
                    }

                    // Update select all checkbox
                    const selectAllCheckbox = document.querySelector('input[type="checkbox"][onchange*="toggleSelectAll"]');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = this.selectedUsers.length === this.users.length;
                        selectAllCheckbox.indeterminate = this.selectedUsers.length > 0 && this.selectedUsers.length < this
                            .users.length;
                    }
                },

                // Submit form
                async submitForm() {
                    // Validate form data
                    if (!this.formData.name || !this.formData.email || !this.formData.roles
                        .length) {
                        this.errorMessage = 'Mohon lengkapi semua field yang wajib diisi';
                        setTimeout(() => this.errorMessage = '', 5000);
                        return;
                    }

                    if (!this.editMode && (!this.formData.password || this.formData.password !== this.formData
                            .password_confirmation)) {
                        this.errorMessage = 'Password dan konfirmasi password harus sama';
                        setTimeout(() => this.errorMessage = '', 5000);
                        return;
                    }

                    this.loading = true;
                    try {
                        const url = this.editMode ?
                            `{{ route('pengaturan.management-pengguna.update', ':id') }}`
                            .replace(':id', this.formData.id) :
                            '{{ route('pengaturan.management-pengguna.store') }}';

                        const formData = new FormData();

                        // Add basic fields
                        formData.append('name', this.formData.name);
                        formData.append('email', this.formData.email);
                        formData.append('is_active', this.formData.is_active ? '1' : '0');

                        // Add password fields only for create or if provided in edit
                        if (!this.editMode) {
                            formData.append('password', this.formData.password);
                            formData.append('password_confirmation', this.formData.password_confirmation);
                        }

                        // Add roles array (backend expects role_ids[])
                        this.formData.roles.forEach(roleId => {
                            formData.append('role_ids[]', roleId);
                        });

                        // Add method and token
                        if (this.editMode) {
                            formData.append('_method', 'PUT');
                        }
                        formData.append('_token', '{{ csrf_token() }}');


                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message || (this.editMode ? 'Pengguna berhasil diupdate' :
                                'Pengguna berhasil ditambahkan');
                            this.closeModal();
                            this.loadUsers(this.pagination.current_page);
                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            // Log the full response for debugging
                            console.error('Validation failed:', {
                                status: response.status,
                                statusText: response.statusText,
                                data: data
                            });

                            if (data.errors) {
                                // Handle validation errors with detailed formatting
                                let errorMessages = [];
                                Object.keys(data.errors).forEach(field => {
                                    const fieldErrors = data.errors[field];
                                    fieldErrors.forEach(error => {
                                        errorMessages.push(`${field}: ${error}`);
                                    });
                                });
                                this.errorMessage = errorMessages.join('; ');
                            } else {
                                this.errorMessage = data.message ||
                                    `Terjadi kesalahan saat menyimpan data (${response.status})`;
                            }
                            setTimeout(() => this.errorMessage = '', 8000); // Longer timeout for validation errors
                        }
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        this.errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                // Modal functions
                openCreateModal() {
                    this.editMode = false;
                    this.modalTitle = 'Tambah Pengguna';
                    this.resetForm();
                    this.showModal = true;

                    // Focus on first input after modal opens
                    this.$nextTick(() => {
                        document.getElementById('name')?.focus();
                    });
                },

                closeModal() {
                    this.showModal = false;
                    this.resetForm();
                    // Clear any error messages
                    this.errorMessage = '';
                },

                resetForm() {
                    this.formData = {
                        id: null,
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                        roles: [],
                        is_active: true
                    };
                },

                // Basic operations
                async bulkDeleteSelected() {
                    if (this.selectedUsers.length === 0) {
                        this.errorMessage = 'Pilih pengguna yang ingin dihapus';
                        setTimeout(() => this.errorMessage = '', 5000);
                        return;
                    }

                    if (!confirm(
                            `Apakah Anda yakin ingin menghapus ${this.selectedUsers.length} pengguna yang dipilih?`)) {
                        return;
                    }

                    this.loading = true;

                    try {
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('ids', JSON.stringify(this.selectedUsers));

                        const response = await fetch('{{ route('pengaturan.management-pengguna.bulk-destroy') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message ||
                                `Berhasil menghapus ${this.selectedUsers.length} pengguna`;
                            this.selectedUsers = [];
                            this.loadUsers(this.pagination.current_page);
                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            this.errorMessage = data.message || 'Terjadi kesalahan saat menghapus pengguna';
                            setTimeout(() => this.errorMessage = '', 5000);
                        }

                    } catch (error) {
                        console.error('Error bulk deleting users:', error);
                        this.errorMessage = 'Terjadi kesalahan saat menghapus pengguna';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                sortBy(field) {
                    if (this.sorting.field === field) {
                        this.sorting.direction = this.sorting.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sorting.field = field;
                        this.sorting.direction = 'asc';
                    }
                    this.loadUsers();
                },

                changePage(page) {
                    if (page >= 1 && page <= this.pagination.last_page) {
                        this.loadUsers(page);
                    }
                },

                openDeleteModal(userId, userName) {
                    this.userToDelete = {
                        id: userId,
                        name: userName
                    };
                    this.deleteMessage =
                        `Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat dibatalkan.`;
                    this.showDeleteModal = true;
                },

                async confirmDelete() {
                    if (!this.userToDelete) return;

                    this.loading = true;
                    try {
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        formData.append('_token', '{{ csrf_token() }}');

                        const response = await fetch(
                            `{{ route('pengaturan.management-pengguna.destroy', ':id') }}`
                            .replace(':id', this.userToDelete.id), {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message || 'Pengguna berhasil dihapus';
                            this.closeDeleteModal();
                            this.loadUsers(this.pagination.current_page);
                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            this.errorMessage = data.message || 'Gagal menghapus pengguna';
                            setTimeout(() => this.errorMessage = '', 5000);
                        }
                    } catch (error) {
                        console.error('Error deleting user:', error);
                        this.errorMessage = 'Terjadi kesalahan saat menghapus pengguna';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                closeDeleteModal() {
                    this.showDeleteModal = false;
                    this.userToDelete = null;
                },

                // View modal
                async openViewModal(userId) {
                    this.loading = true;
                    try {
                        const response = await fetch(`{{ route('pengaturan.management-pengguna.show', ':id') }}`
                            .replace(':id', userId), {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                        if (response.ok) {
                            const data = await response.json();
                            this.viewUser = data.user;
                            this.showViewModal = true;
                        } else {
                            this.errorMessage = 'Gagal memuat detail pengguna';
                            setTimeout(() => this.errorMessage = '', 5000);
                        }
                    } catch (error) {
                        console.error('Error loading user details:', error);
                        this.errorMessage = 'Terjadi kesalahan saat memuat detail pengguna';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                closeViewModal() {
                    this.showViewModal = false;
                    this.viewUser = {};
                },

                editFromView() {
                    this.editMode = true;
                    this.modalTitle = 'Edit Pengguna';
                    this.formData = {
                        id: this.viewUser.id,
                        name: this.viewUser.name,
                        email: this.viewUser.email,
                        password: '',
                        password_confirmation: '',
                        roles: this.viewUser.roles ? this.viewUser.roles.map(role => role.id) : [],
                        is_active: this.viewUser.is_active
                    };
                    this.closeViewModal();
                    this.showModal = true;
                },

                // Edit modal
                openEditModal(userId) {
                    const user = this.users.find(u => u.id === userId);
                    if (user) {
                        this.editMode = true;
                        this.modalTitle = 'Edit Pengguna';
                        this.formData = {
                            id: user.id,
                            name: user.name,
                            email: user.email,
                            password: '',
                            password_confirmation: '',
                            roles: user.roles ? user.roles.map(role => role.id) : [],
                            is_active: user.is_active
                        };
                        this.showModal = true;
                    }
                },

                // Reset password modal
                resetUserPassword(userId) {


                    // Convert to number for comparison
                    const numericUserId = parseInt(userId);
                    const user = this.users.find(u => parseInt(u.id) === numericUserId);


                    if (user) {
                        this.resetPasswordUser = user;
                        this.resetPasswordForm = {
                            new_password: '',
                            password_confirmation: '',
                            force_change: true
                        };
                        this.showResetPasswordModal = true;

                    } else {
                        console.error('User not found for ID:', userId);
                        this.errorMessage = 'User tidak ditemukan untuk reset password';
                        setTimeout(() => this.errorMessage = '', 5000);
                    }
                },

                closeResetPasswordModal() {
                    this.showResetPasswordModal = false;
                    this.resetPasswordUser = {};
                    this.resetPasswordForm = {
                        new_password: '',
                        password_confirmation: '',
                        force_change: true
                    };
                },

                // Toggle user status
                async toggleUserStatus(userId) {
                    try {
                        const response = await fetch(
                            `{{ route('pengaturan.management-pengguna.toggle-status', ':id') }}`
                            .replace(':id', userId), {
                                method: 'PATCH',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message || 'Status pengguna berhasil diubah';
                            this.loadUsers(this.pagination.current_page);
                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            this.errorMessage = data.message || 'Gagal mengubah status pengguna';
                            setTimeout(() => this.errorMessage = '', 5000);
                        }
                    } catch (error) {
                        console.error('Error toggling user status:', error);
                        this.errorMessage = 'Terjadi kesalahan saat mengubah status pengguna';
                        setTimeout(() => this.errorMessage = '', 5000);
                    }
                },

                // Reset password - open modal
                resetUserPassword(userId) {
                    const user = this.users.find(u => u.id === userId);
                    if (user) {
                        this.resetPasswordUser = {
                            id: user.id,
                            name: user.name
                        };
                        this.resetPasswordForm = {
                            new_password: '',
                            password_confirmation: '',
                            force_change: true
                        };
                        this.showNewPassword = false;
                        this.showConfirmPassword = false;
                        this.showResetPasswordModal = true;
                    }
                },

                closeResetPasswordModal() {
                    this.showResetPasswordModal = false;
                    this.resetPasswordUser = {};
                    this.resetPasswordForm = {
                        new_password: '',
                        password_confirmation: '',
                        force_change: true
                    };
                    this.showNewPassword = false;
                    this.showConfirmPassword = false;
                },

                async confirmResetPassword() {
                    if (!this.isResetPasswordFormValid) {
                        this.errorMessage = 'Password tidak valid atau tidak cocok';
                        setTimeout(() => this.errorMessage = '', 5000);
                        return;
                    }

                    if (!this.resetPasswordUser || !this.resetPasswordUser.id) {
                        this.errorMessage = 'Data user tidak valid untuk reset password';
                        setTimeout(() => this.errorMessage = '', 5000);
                        return;
                    }

                    this.loading = true;
                    try {
                        const formData = new FormData();
                        formData.append('_method', 'PATCH'); // Laravel method spoofing
                        formData.append('_token', '{{ csrf_token() }}'); // Laravel CSRF token
                        formData.append('new_password', this.resetPasswordForm.new_password);
                        formData.append('password_confirmation', this.resetPasswordForm.password_confirmation);
                        formData.append('force_change', this.resetPasswordForm.force_change ? '1' : '0');

                        // Debug: Log the reset password data being sent

                        const response = await fetch(
                            `{{ route('pengaturan.management-pengguna.reset-password', ':id') }}`
                            .replace(':id', this.resetPasswordUser.id), {
                                method: 'POST', // Use POST with _method spoofing
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });

                        const data = await response.json();

                        if (response.ok) {

                            this.successMessage = data.message || 'Password berhasil direset';
                            this.closeResetPasswordModal();

                            // Reload users data to verify the change
                            this.loadUsers(this.pagination.current_page);

                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            // Log the full response for debugging
                            console.error('Reset password validation failed:', {
                                status: response.status,
                                statusText: response.statusText,
                                data: data
                            });

                            if (data.errors) {
                                // Handle validation errors with detailed formatting
                                let errorMessages = [];
                                Object.keys(data.errors).forEach(field => {
                                    const fieldErrors = data.errors[field];
                                    fieldErrors.forEach(error => {
                                        errorMessages.push(`${field}: ${error}`);
                                    });
                                });
                                this.errorMessage = errorMessages.join('; ');
                            } else {
                                this.errorMessage = data.message || `Gagal mereset password (${response.status})`;
                            }
                            setTimeout(() => this.errorMessage = '', 8000);
                        }
                    } catch (error) {
                        console.error('Error resetting password:', error);
                        this.errorMessage = 'Terjadi kesalahan saat mereset password';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                // Load roles with filters
                async loadRoles() {
                    this.roleLoading = true;
                    try {
                        const params = new URLSearchParams({
                            search: this.roleFilters.search
                        });

                        const response = await fetch(`{{ route('pengaturan.management-pengguna.roles') }}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.roles = data.data || [];
                        } else {
                            this.errorMessage = data.message || 'Gagal memuat data role';
                            setTimeout(() => this.errorMessage = '', 5000);
                        }
                    } catch (error) {
                        console.error('Error loading roles:', error);
                        this.errorMessage = 'Terjadi kesalahan saat memuat data role';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.roleLoading = false;
                    }
                },

                // Open create role modal
                openCreateRoleModal() {
                    this.roleEditMode = false;
                    this.roleModalTitle = 'Tambah Role';
                    this.roleFormData = {
                        id: null,
                        nama: '',
                        kode: '',
                        deskripsi: ''
                    };
                    this.showRoleModal = true;
                },

                // Open edit role modal
                openEditRoleModal(role) {
                    this.roleEditMode = true;
                    this.roleModalTitle = 'Edit Role';
                    this.roleFormData = {
                        id: role.id,
                        nama: role.nama,
                        kode: role.kode,
                        deskripsi: role.deskripsi || ''
                    };
                    this.showRoleModal = true;
                },

                // Submit role form
                async submitRoleForm() {
                    this.loading = true;
                    try {
                        const formData = new FormData();
                        formData.append('nama', this.roleFormData.nama);
                        formData.append('kode', this.roleFormData.kode);
                        formData.append('deskripsi', this.roleFormData.deskripsi);

                        const url = this.roleEditMode ?
                            `{{ route('pengaturan.management-pengguna.roles') }}/${this.roleFormData.id}` :
                            `{{ route('pengaturan.management-pengguna.roles') }}`;

                        const method = this.roleEditMode ? 'PUT' : 'POST';

                        if (this.roleEditMode) {
                            formData.append('_method', 'PUT');
                        }

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message;
                            this.closeRoleModal();
                            this.loadRoles();
                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            if (data.errors) {
                                // Handle validation errors
                                const errorMessages = Object.values(data.errors).flat();
                                this.errorMessage = errorMessages.join(', ');
                            } else {
                                this.errorMessage = data.message || 'Gagal menyimpan role';
                            }
                            setTimeout(() => this.errorMessage = '', 5000);
                        }
                    } catch (error) {
                        console.error('Error submitting role form:', error);
                        this.errorMessage = 'Terjadi kesalahan saat menyimpan role';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                // Close role modal
                closeRoleModal() {
                    this.showRoleModal = false;
                    this.roleFormData = {
                        id: null,
                        nama: '',
                        kode: '',
                        deskripsi: ''
                    };
                    this.roleEditMode = false;
                    this.roleModalTitle = 'Tambah Role';
                },

                // Open delete role modal
                openDeleteRoleModal(role) {
                    this.roleToDelete = role;

                    // Check if it's a system role
                    const systemRoles = ['admin', 'super_admin', 'superadmin'];
                    const isSystemRole = systemRoles.includes(role.kode.toLowerCase());

                    if (isSystemRole) {
                        this.deleteRoleMessage = `Role "${role.nama}" adalah role sistem dan tidak dapat dihapus.`;
                    } else if (role.users_count > 0) {
                        this.deleteRoleMessage =
                            `Role "${role.nama}" sedang digunakan oleh ${role.users_count} pengguna dan tidak dapat dihapus. Silakan hapus atau ubah role pengguna terlebih dahulu.`;
                    } else {
                        this.deleteRoleMessage =
                            `Apakah Anda yakin ingin menghapus role "${role.nama}"? Tindakan ini tidak dapat dibatalkan.`;
                    }

                    this.showDeleteRoleModal = true;
                },

                // Confirm delete role
                async confirmDeleteRole() {
                    if (!this.roleToDelete) return;

                    // Check if it's a system role or has users
                    const systemRoles = ['admin', 'super_admin', 'superadmin'];
                    const isSystemRole = systemRoles.includes(this.roleToDelete.kode.toLowerCase());

                    if (isSystemRole || this.roleToDelete.users_count > 0) {
                        this.closeDeleteRoleModal();
                        return;
                    }

                    this.loading = true;
                    try {
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');

                        const response = await fetch(
                            `{{ route('pengaturan.management-pengguna.roles') }}/${this.roleToDelete.id}`, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message;
                            this.closeDeleteRoleModal();
                            this.loadRoles();
                            setTimeout(() => this.successMessage = '', 5000);
                        } else {
                            this.errorMessage = data.message || 'Gagal menghapus role';
                            setTimeout(() => this.errorMessage = '', 5000);
                        }
                    } catch (error) {
                        console.error('Error deleting role:', error);
                        this.errorMessage = 'Terjadi kesalahan saat menghapus role';
                        setTimeout(() => this.errorMessage = '', 5000);
                    } finally {
                        this.loading = false;
                    }
                },

                // Close delete role modal
                closeDeleteRoleModal() {
                    this.showDeleteRoleModal = false;
                    this.roleToDelete = null;
                    this.deleteRoleMessage = '';
                }
            }
        }
    </script>
</x-app-layout>

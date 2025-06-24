<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div x-data="{
        // State
        users: @json($users->items() ?? []),
        roles: @json($roles),
        loading: false,
        successMessage: '',
        errorMessage: '',
        selectedUsers: [],
    
        // Modal state
        showModal: false,
        showDeleteModal: false,
        editMode: false,
        modalTitle: 'Tambah Pengguna',
        deleteMessage: '',
        userToDelete: null,
    
        // Form data
        formData: {
            id: null,
            name: '',
            username: '',
            email: '',
            password: '',
            password_confirmation: '',
            roles: [],
            is_active: true
        },
    
        // Filters and sorting
        filters: {
            search: '',
            role: '',
            status: ''
        },
        sorting: {
            field: 'name',
            direction: 'asc'
        },
    
        // Pagination
        pagination: @json($users->toArray()),
    
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
    
        init() {
            @if(session('success'))
            this.successMessage = '{{ session('success') }}';
            setTimeout(() => this.successMessage = '', 5000);
            @endif
    
            @if(session('error'))
            this.errorMessage = '{{ session('error') }}';
            setTimeout(() => this.errorMessage = '', 5000);
            @endif
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
                }
            } catch (error) {
                console.error('Error loading users:', error);
                this.errorMessage = 'Gagal memuat data pengguna';
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
            document.querySelectorAll('input[name=\"selected_users[]\"]').forEach(checkbox=> {
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
        },

        // Submit form
        async submitForm() {
        this.loading = true;
        try {
        const url = this.editMode
        ? `{{ route('pengaturan.management-pengguna.index') }}/${this.formData.id}`
        : '{{ route('pengaturan.management-pengguna.store') }}';

        const formData = new FormData();
        Object.keys(this.formData).forEach(key => {
        if (key === 'roles') {
        this.formData.roles.forEach(roleId => {
        formData.append('roles[]', roleId);
        });
        } else if (this.formData[key] !== null && this.formData[key] !== '') {
        formData.append(key, this.formData[key]);
        }
        });

        if (this.editMode) {
        formData.append('_method', 'PUT');
        }
        formData.append('_token', '{{ csrf_token() }}');

        const response = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
        'X-Requested-With': 'XMLHttpRequest'
        }
        });

        const data = await response.json();

        if (response.ok) {
        this.successMessage = data.message;
        this.closeModal();
        this.loadUsers(this.pagination.current_page);
        setTimeout(() => this.successMessage = '', 5000);
        } else {
        this.errorMessage = data.message || 'Terjadi kesalahan';
        setTimeout(() => this.errorMessage = '', 5000);
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
        },

        closeModal() {
        this.showModal = false;
        this.resetForm();
        },

        resetForm() {
        this.formData = {
        id: null,
        name: '',
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        roles: [],
        is_active: true
        };
        },

        // Basic operations
        bulkDeleteSelected() {
        if (this.selectedUsers.length === 0) {
        alert('Pilih pengguna yang ingin dihapus');
        return;
        }

        if (!confirm(`Apakah Anda yakin ingin menghapus ${this.selectedUsers.length} pengguna yang dipilih?`)) {
        return;
        }

        // Implementation will be added later
        alert('Bulk delete functionality will be implemented');
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
        if (page >= 1 && page <= this.pagination.last_page) { this.loadUsers(page); } }, openDeleteModal(userId,
            userName) { this.userToDelete={ id: userId, name: userName }; this.deleteMessage=`Apakah Anda yakin ingin
            menghapus pengguna \"${userName}\"? Tindakan ini tidak dapat dibatalkan.`; this.showDeleteModal=true; },
            confirmDelete() { if (!this.userToDelete) return; // Implementation will be added later alert(`Delete user:
            ${this.userToDelete.name}`); this.closeDeleteModal(); }, closeDeleteModal() { this.showDeleteModal=false;
            this.userToDelete=null; } }" class="container mx-auto py-6">
            <!-- Header Section -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Management Pengguna</h1>
                            <p class="text-gray-600 dark:text-gray-400">Kelola pengguna sistem dan role mereka.</p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex space-x-2">
                            <button @click="openCreateModal()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Pengguna
                            </button>
                            <button @click="bulkDeleteSelected()" x-show="selectedUsers.length > 0" x-transition
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
            <div
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

            <!-- Users Table -->
            <div
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                    @click="sortBy('email')">
                                    <div class="flex items-center space-x-1">
                                        <span>Email</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan pengguna
                            baru.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div x-show="!loading && pagination.total > 0"
                    class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-600 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="changePage(pagination.current_page - 1)"
                            :disabled="pagination.current_page <= 1"
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
                                <span class="font-medium" x-text="pagination.from"></span>
                                sampai
                                <span class="font-medium" x-text="pagination.to"></span>
                                dari
                                <span class="font-medium" x-text="pagination.total"></span>
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

                                <div>
                                    <label for="username"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" id="username" x-model="formData.username" required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                                </div>

                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email
                                        <span class="text-red-500">*</span></label>
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
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi
                                        Password <span class="text-red-500">*</span></label>
                                    <input type="password" id="password_confirmation"
                                        x-model="formData.password_confirmation" :required="!editMode"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="roles"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role
                                        <span class="text-red-500">*</span></label>
                                    <select id="roles" x-model="formData.roles" multiple required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:text-white">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->nama ?? $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tahan Ctrl (Windows) atau
                                        Cmd (Mac) untuk memilih multiple role</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" x-model="formData.is_active"
                                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pengguna
                                            Aktif</span>
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Hapus Pengguna
                        </h3>
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
    </div>
</x-app-layout>

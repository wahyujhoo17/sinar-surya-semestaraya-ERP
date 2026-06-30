@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout :breadcrumbs="[
    ['label' => 'Pengaturan', 'url' => '#'],
    ['label' => 'Hak Akses', 'url' => route('pengaturan.hak-akses.index')],
]" :currentPage="'Hak Akses'">

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="permissionsManager()">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Hak Akses</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola peran (role) dan hak akses pengguna secara real-time.</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <button @click="openAddModal = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Permission Baru
                </button>
            </div>
        </div>
        
        <!-- Flash Messages via Alpine -->
        <div x-show="successMessage" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-md shadow-sm fixed bottom-4 right-4 z-50 w-72" style="display: none;">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-200" x-text="successMessage"></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="w-full sm:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" placeholder="Cari permission..."
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <select x-model="selectedRole" class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            <option value="all">Semua Role</option>
                            <template x-for="role in roles" :key="role.id">
                                <option :value="role.id" x-text="role.nama || role.name"></option>
                            </template>
                        </select>
                        <select x-model="selectedModul" class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            <option value="all">Semua Modul</option>
                            <template x-for="modul in availableModules" :key="modul">
                                <option :value="modul" x-text="formatModulName(modul)"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-0 sm:p-6" x-show="!isLoading" x-transition>
                <!-- Grouped View Lazy -->
                <div class="space-y-4">
                    <template x-for="(modulPermissions, modulName) in groupedPermissions" :key="modulName">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800 shadow-sm transition-colors hover:border-gray-300">
                            <!-- Accordion Header -->
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 flex justify-between items-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" @click="toggleModule(modulName)">
                                <div class="flex items-center">
                                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide" x-text="formatModulName(modulName)"></h3>
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white border border-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 shadow-sm" x-text="modulPermissions.length + ' item'"></span>
                                </div>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{'rotate-180': openModules[modulName]}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>

                            <!-- Lazy Rendered Permissions Table -->
                            <template x-if="openModules[modulName]">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Permission</th>
                                                <template x-for="role in filteredRoles" :key="role.id">
                                                    <th class="px-2 py-3 text-center">
                                                        <div class="whitespace-nowrap text-sm font-bold text-gray-700 dark:text-gray-300" x-text="role.nama || role.name"></div>
                                                    </th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="permission in modulPermissions" :key="permission.id">
                                                <tr class="border-b last:border-0 border-gray-100 dark:border-gray-700 hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td class="px-4 py-3 border-r border-gray-100 dark:border-gray-700">
                                                        <div class="flex flex-col">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="permission.nama || permission.name"></span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="permission.deskripsi || '-'"></span>
                                                            <span class="text-[10px] font-mono text-gray-400 mt-1" x-text="permission.kode"></span>
                                                        </div>
                                                    </td>
                                                    <template x-for="role in filteredRoles" :key="role.id">
                                                        <td class="px-2 py-3 text-center border-r border-gray-50 last:border-0 dark:border-gray-700">
                                                            <label class="inline-flex relative cursor-pointer p-1">
                                                                <input type="checkbox"
                                                                    :checked="hasPermission(role, permission)"
                                                                    @change="togglePermissionAjax(role, permission, $event.target.checked)"
                                                                    class="rounded w-4 h-4 border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer hover:border-primary-400">
                                                            </label>
                                                        </td>
                                                    </template>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </div>
                    </template>
                    
                    <div x-show="Object.keys(groupedPermissions).length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada permission ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                </div>
            </div>
            
            <div x-show="isLoading" class="p-12 text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                <p class="mt-2 text-sm text-gray-500 font-medium">Memuat dan mengoptimalkan tampilan data...</p>
            </div>
        </div>

        <!-- Add Permission Modal -->
        <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" @click="openAddModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200 dark:border-gray-700">
                    <form @submit.prevent="submitNewPermission" id="form-add-permission">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">Tambah Permission Baru</h3>
                                    <p class="text-sm text-gray-500 mt-1">Daftarkan izin akses baru ke dalam sistem untuk dikontrol.</p>
                                    
                                    <div class="mt-5 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modul</label>
                                            <input type="text" x-model="newPerm.modul" list="modul-list" placeholder="Contoh: Pengaturan" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                            <datalist id="modul-list">
                                                <template x-for="mod in availableModules">
                                                    <option :value="mod"></option>
                                                </template>
                                            </datalist>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Permission</label>
                                            <input type="text" x-model="newPerm.nama" placeholder="Contoh: Lihat Data Laporan" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode / Slug (Unik)</label>
                                            <input type="text" x-model="newPerm.kode" placeholder="Contoh: laporan.view" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm font-mono bg-gray-50">
                                            <p class="text-xs text-gray-400 mt-1">Gunakan huruf kecil, pemisah titik (.) atau underscore (_).</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Tambahan</label>
                                            <textarea x-model="newPerm.deskripsi" rows="2" placeholder="Hak akses untuk melihat data laporan secara umum..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700 gap-3">
                            <button type="submit" :disabled="isSavingPerm" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 transition-colors">
                                <span x-show="!isSavingPerm">Simpan Permission</span>
                                <span x-show="isSavingPerm" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Menyimpan...
                                </span>
                            </button>
                            <button type="button" @click="openAddModal = false" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function permissionsManager() {
                return {
                    roles: {!! json_encode($roles) !!},
                    permissions: {!! json_encode($permissions) !!},
                    rolePermissions: {},
                    searchQuery: '',
                    selectedRole: 'all',
                    selectedModul: 'all',
                    openModules: {},
                    isLoading: true,
                    successMessage: '',
                    
                    openAddModal: false,
                    isSavingPerm: false,
                    newPerm: {
                        nama: '',
                        kode: '',
                        modul: '',
                        deskripsi: ''
                    },

                    init() {
                        this.roles.forEach(role => {
                            this.rolePermissions[role.id] = role.permissions ? role.permissions.map(p => p.id) : [];
                        });
                        
                        // Fake slight delay to let DOM settle, significantly improves first render feel
                        setTimeout(() => {
                            this.isLoading = false;
                        }, 150);
                        
                        // Open the first module by default so it's not completely blank
                        if(this.availableModules.length > 0) {
                             this.openModules[this.availableModules[0]] = true;
                        }
                    },

                    get availableModules() {
                        return [...new Set(this.permissions.map(p => p.modul))].sort();
                    },

                    get filteredRoles() {
                        if (this.selectedRole === 'all') return this.roles;
                        return this.roles.filter(role => role.id == this.selectedRole);
                    },

                    get filteredPermissionsForTableView() {
                        let filtered = this.permissions;
                        if (this.selectedModul !== 'all') {
                            filtered = filtered.filter(p => p.modul === this.selectedModul);
                        }
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
                        const filtered = this.filteredPermissionsForTableView;
                        const grouped = {};
                        filtered.forEach(permission => {
                            const modul = permission.modul || 'other';
                            if (!grouped[modul]) grouped[modul] = [];
                            grouped[modul].push(permission);
                        });
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
                        return modul.split('_').map(word =>
                            word.charAt(0).toUpperCase() + word.slice(1)
                        ).join(' ');
                    },

                    toggleModule(modulName) {
                        if (this.openModules[modulName]) {
                            this.openModules[modulName] = false;
                        } else {
                            this.openModules[modulName] = true;
                        }
                    },

                    hasPermission(role, permission) {
                        return this.rolePermissions[role.id]?.includes(permission.id);
                    },

                    async togglePermissionAjax(role, permission, isChecked) {
                        if (!this.rolePermissions[role.id]) this.rolePermissions[role.id] = [];
                        
                        if (isChecked) {
                            if (!this.rolePermissions[role.id].includes(permission.id)) {
                                this.rolePermissions[role.id].push(permission.id);
                            }
                        } else {
                            this.rolePermissions[role.id] = this.rolePermissions[role.id].filter(id => id !== permission.id);
                        }

                        try {
                            const formData = new FormData();
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('role_id', role.id);
                            formData.append('permission_id', permission.id);
                            formData.append('state', isChecked ? '1' : '0');

                            const response = await fetch('{{ route('pengaturan.hak-akses.toggle') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (response.ok) {
                                this.showToast('Tersimpan: ' + (permission.nama || permission.kode));
                            } else {
                                alert('Gagal menyimpan otomatis.');
                            }
                        } catch (error) {
                            console.error(error);
                            alert('Terjadi kesalahan koneksi saat menyimpan.');
                        }
                    },
                    
                    showToast(msg) {
                        this.successMessage = msg;
                        setTimeout(() => { this.successMessage = ''; }, 2500);
                    },

                    async submitNewPermission() {
                        this.isSavingPerm = true;
                        try {
                            const formData = new FormData();
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('nama', this.newPerm.nama);
                            formData.append('kode', this.newPerm.kode);
                            formData.append('modul', this.newPerm.modul);
                            formData.append('deskripsi', this.newPerm.deskripsi);

                            const response = await fetch('{{ route('pengaturan.hak-akses.permission.store') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (response.ok && data.status === 'success') {
                                this.showToast('Berhasil menambahkan: ' + data.permission.nama);
                                this.permissions.push(data.permission);
                                this.openModules[data.permission.modul] = true;
                                this.openAddModal = false;
                                this.newPerm = { nama: '', kode: '', modul: '', deskripsi: '' };
                            } else {
                                alert(data.message || 'Gagal menambahkan permission. Kode mungkin sudah ada (harus unik).');
                            }
                        } catch (error) {
                            console.error(error);
                            alert('Gagal mengirim data permission.');
                        } finally {
                            this.isSavingPerm = false;
                        }
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>

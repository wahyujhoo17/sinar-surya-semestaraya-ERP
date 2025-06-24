<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Struktur Organisasi'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Struktur Organisasi</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Visualisasi hierarki dan struktur organisasi PT Sinar Surya Semestaraya
            </p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Departments Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18m2.25-18v18m-16.5-10.5h6.75m-6.75 3h6.75m-6.75 3h6.75m10.5-10.5h6.75m-6.75 3h6.75m-6.75 3h6.75M6.75 7.5h3v3h-3v-3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Departemen</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $departmentStats->count() }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">unit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Positions Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3.5">
                            <svg class="h-7 w-7 text-emerald-500 dark:text-emerald-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Jabatan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $jabatans->count() }}
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">posisi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Active Employees Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-purple-100 dark:bg-purple-900/30 p-3.5">
                            <svg class="h-7 w-7 text-purple-500 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Karyawan Aktif</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $departmentStats->sum('karyawan_count') }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div x-data="organizationStructure()" class="space-y-8">
            {{-- View Toggle --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div class="mb-4 sm:mb-0">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Visualisasi Organisasi</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih tampilan struktur organisasi
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <button @click="viewMode = 'chart'"
                                :class="viewMode === 'chart' ? 'bg-primary-600 text-white' :
                                    'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Bagan Organisasi
                            </button>
                            <button @click="viewMode = 'tree'"
                                :class="viewMode === 'tree' ? 'bg-primary-600 text-white' :
                                    'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5v4">
                                    </path>
                                </svg>
                                Hierarki Departemen
                            </button>
                            <button @click="showManagementModal = true"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Kelola Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Organization Chart View --}}
            <div x-show="viewMode === 'chart'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Bagan Struktur Organisasi
                        </h3>
                        <div id="organization-chart" class="min-h-[600px] overflow-x-auto">
                            {{-- Organization chart will be rendered here --}}
                            @include('hr_karyawan.struktur_organisasi._organization_chart')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Department Tree View --}}
            <div x-show="viewMode === 'tree'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Hierarki Departemen</h3>
                        <div class="space-y-4">
                            @foreach ($departments as $department)
                                @include('hr_karyawan.struktur_organisasi._department_tree', [
                                    'department' => $department,
                                    'level' => 0,
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Department Details Modal --}}
            <div x-show="showModal" @click.away="showModal = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                style="display: none;">
                <div
                    class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="mt-3">
                        {{-- Modal Header --}}
                        <div
                            class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="selectedDepartment?.name || 'Detail Departemen'"></h3>
                            <button @click="showModal = false"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Content --}}
                        <div class="mt-4">
                            <template x-if="selectedDepartment">
                                <div>
                                    {{-- Department Info --}}
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode
                                                    Departemen</label>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white"
                                                    x-text="selectedDepartment.code"></p>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah
                                                    Karyawan</label>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white"
                                                    x-text="selectedDepartment.employee_count + ' orang'"></p>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label
                                                    class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                                                <p class="text-sm text-gray-900 dark:text-white"
                                                    x-text="selectedDepartment.description || '-'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Employees List --}}
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Daftar
                                            Karyawan</h4>
                                        <div class="space-y-3 max-h-60 overflow-y-auto">
                                            <template x-for="employee in departmentEmployees" :key="employee.id">
                                                <div
                                                    class="flex items-center space-x-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                                            <template x-if="employee.photo">
                                                                <img :src="employee.photo" :alt="employee.name"
                                                                    class="h-10 w-10 rounded-full object-cover">
                                                            </template>
                                                            <template x-if="!employee.photo">
                                                                <span
                                                                    class="text-primary-700 dark:text-primary-300 font-medium text-sm"
                                                                    x-text="employee.name.charAt(0).toUpperCase()"></span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                                            x-text="employee.name"></p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400"
                                                            x-text="employee.position"></p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400"
                                                            x-text="'NIP: ' + employee.nip"></p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <a :href="'/hr/karyawan/' + employee.id"
                                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Department Modal --}}
            <div x-show="showAddDepartmentModal" @click.away="showAddDepartmentModal = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                style="display: none;">
                <div
                    class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-xl bg-white dark:bg-gray-800">
                    <div class="mt-3">
                        {{-- Modal Header --}}
                        <div
                            class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                <span x-text="editingDepartment ? 'Edit Departemen' : 'Tambah Departemen Baru'"></span>
                            </h3>
                            <button @click="closeAddDepartmentModal()"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Content --}}
                        <form @submit.prevent="saveDepartment()" class="mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                        Departemen</label>
                                    <input type="text" x-model="departmentForm.name" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode
                                        Departemen</label>
                                    <input type="text" x-model="departmentForm.code" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departemen
                                        Induk</label>
                                    <select x-model="departmentForm.parent_id"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">-- Pilih Departemen Induk --</option>
                                        <template x-for="dept in allDepartments" :key="dept.id">
                                            <option :value="dept.id" x-text="dept.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manager</label>
                                    <select x-model="departmentForm.manager_id"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">-- Pilih Manager --</option>
                                        <template x-for="emp in availableEmployees" :key="emp.id">
                                            <option :value="emp.id"
                                                x-text="emp.name + ' (' + emp.position + ')'"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                                    <textarea x-model="departmentForm.description" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                </div>
                            </div>

                            <div
                                class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" @click="closeAddDepartmentModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                    <span x-text="editingDepartment ? 'Update' : 'Simpan'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Add Jabatan Modal --}}
            <div x-show="showAddJabatanModal" @click.away="showAddJabatanModal = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                style="display: none;">
                <div
                    class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-xl bg-white dark:bg-gray-800">
                    <div class="mt-3">
                        {{-- Modal Header --}}
                        <div
                            class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                <span x-text="editingJabatan ? 'Edit Jabatan' : 'Tambah Jabatan Baru'"></span>
                            </h3>
                            <button @click="closeAddJabatanModal()"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Content --}}
                        <form @submit.prevent="saveJabatan()" class="mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                        Jabatan</label>
                                    <input type="text" x-model="jabatanForm.nama_jabatan" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode
                                        Jabatan</label>
                                    <input type="text" x-model="jabatanForm.kode_jabatan" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departemen</label>
                                    <select x-model="jabatanForm.department_id" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">-- Pilih Departemen --</option>
                                        <template x-for="dept in allDepartments" :key="dept.id">
                                            <option :value="dept.id" x-text="dept.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Level
                                        Jabatan</label>
                                    <select x-model="jabatanForm.level" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">-- Pilih Level --</option>
                                        <option value="1">Level 1 (Direktur)</option>
                                        <option value="2">Level 2 (Manager)</option>
                                        <option value="3">Level 3 (Supervisor)</option>
                                        <option value="4">Level 4 (Staff)</option>
                                        <option value="5">Level 5 (Operator)</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi
                                        Jabatan</label>
                                    <textarea x-model="jabatanForm.deskripsi" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white"></textarea>
                                </div>
                            </div>

                            <div
                                class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" @click="closeAddJabatanModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">
                                    <span x-text="editingJabatan ? 'Update' : 'Simpan'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Management Actions Modal --}}
            <div x-show="showManagementModal" @click.away="showManagementModal = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                style="display: none;">
                <div
                    class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-xl bg-white dark:bg-gray-800 max-h-[90vh] overflow-y-auto">
                    <div class="mt-3">
                        {{-- Modal Header --}}
                        <div
                            class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Kelola Departemen & Jabatan
                            </h3>
                            <button @click="showManagementModal = false"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Management Tabs --}}
                        <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700 p-1 rounded-lg mt-4">
                            <button @click="managementTab = 'departments'"
                                :class="managementTab === 'departments' ?
                                    'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400' :
                                    'text-gray-600 dark:text-gray-400'"
                                class="flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors">
                                Departemen
                            </button>
                            <button @click="managementTab = 'jabatan'"
                                :class="managementTab === 'jabatan' ?
                                    'bg-white dark:bg-gray-800 text-emerald-600 dark:text-emerald-400' :
                                    'text-gray-600 dark:text-gray-400'"
                                class="flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors">
                                Jabatan
                            </button>
                        </div>

                        {{-- Departments Tab --}}
                        <div x-show="managementTab === 'departments'" class="mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Departemen</h4>
                                <button @click="openAddDepartmentModal()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Departemen
                                </button>
                            </div>

                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                <template x-if="allDepartments && allDepartments.length > 0">
                                    <template x-for="dept in allDepartments" :key="dept.id">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-900 dark:text-white"
                                                        x-text="dept.name"></h5>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400"
                                                        x-text="dept.code"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1"
                                                        x-text="dept.description || 'Tidak ada deskripsi'"></p>
                                                    <div class="mt-2 flex items-center space-x-4 text-xs">
                                                        <span
                                                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
                                                            <span x-text="dept.employee_count || 0"></span> Karyawan
                                                        </span>
                                                        <template x-if="dept.parent_name">
                                                            <span
                                                                class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-2 py-1 rounded">
                                                                Sub dari: <span x-text="dept.parent_name"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button @click="editDepartment(dept)"
                                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="deleteDepartment(dept.id)"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="!allDepartments || allDepartments.length === 0">
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Belum ada departemen yang dibuat
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Jabatan Tab --}}
                        <div x-show="managementTab === 'jabatan'" class="mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Jabatan</h4>
                                <button @click="openAddJabatanModal()"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Jabatan
                                </button>
                            </div>

                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                <template x-if="jabatanList && jabatanList.length > 0">
                                    <template x-for="jabatan in jabatanList" :key="jabatan.id">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-900 dark:text-white"
                                                        x-text="jabatan.nama_jabatan || jabatan.nama"></h5>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400"
                                                        x-text="jabatan.kode_jabatan || jabatan.kode"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1"
                                                        x-text="jabatan.deskripsi || 'Tidak ada deskripsi'"></p>
                                                    <div class="mt-2 flex items-center space-x-4 text-xs">
                                                        <span
                                                            class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-2 py-1 rounded">
                                                            Level <span x-text="jabatan.level || '-'"></span>
                                                        </span>
                                                        <span
                                                            class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-2 py-1 rounded">
                                                            <span
                                                                x-text="jabatan.department_name || (jabatan.department ? jabatan.department.nama : 'Tidak ada departemen')"></span>
                                                        </span>
                                                        <span
                                                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
                                                            <span x-text="jabatan.karyawan_count || 0"></span> Karyawan
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button @click="editJabatan(jabatan)"
                                                        class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="deleteJabatan(jabatan.id)"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="!jabatanList || jabatanList.length === 0">
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Belum ada jabatan yang dibuat</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function organizationStructure() {
            return {
                viewMode: 'chart',
                showModal: false,
                showAddDepartmentModal: false,
                showAddJabatanModal: false,
                showManagementModal: false,
                managementTab: 'departments',
                selectedDepartment: null,
                departmentEmployees: [],
                organizationData: @json($organizationData),
                allDepartments: @json($allDepartments ?? []),
                jabatanList: @json($jabatans ?? []),
                availableEmployees: [],
                editingDepartment: null,
                editingJabatan: null,

                departmentForm: {
                    name: '',
                    code: '',
                    parent_id: '',
                    manager_id: '',
                    description: ''
                },

                jabatanForm: {
                    nama_jabatan: '',
                    kode_jabatan: '',
                    department_id: '',
                    level: '',
                    deskripsi: ''
                },

                async init() {
                    // Load available employees for manager selection
                    await this.loadAvailableEmployees();
                },

                async loadAvailableEmployees() {
                    try {
                        const response = await fetch('/hr/karyawan/api/employees');
                        const data = await response.json();
                        this.availableEmployees = data.employees || [];
                    } catch (error) {
                        console.error('Error loading employees:', error);
                    }
                },

                async showDepartmentDetails(departmentId) {
                    try {
                        const response = await fetch(`/hr/struktur-organisasi/department/${departmentId}`);
                        const data = await response.json();

                        this.selectedDepartment = data.department;
                        this.departmentEmployees = data.employees;
                        this.showModal = true;
                    } catch (error) {
                        console.error('Error fetching department details:', error);
                        alert('Gagal memuat detail departemen');
                    }
                },

                // Department Management
                openAddDepartmentModal() {
                    this.editingDepartment = null;
                    this.resetDepartmentForm();
                    this.showAddDepartmentModal = true;
                    this.showManagementModal = false;
                },

                editDepartment(department) {
                    this.editingDepartment = department;
                    this.departmentForm = {
                        name: department.name,
                        code: department.code,
                        parent_id: department.parent_id || '',
                        manager_id: department.manager?.id || '',
                        description: department.description || ''
                    };
                    this.showAddDepartmentModal = true;
                    this.showManagementModal = false;
                },

                resetDepartmentForm() {
                    this.departmentForm = {
                        name: '',
                        code: '',
                        parent_id: '',
                        manager_id: '',
                        description: ''
                    };
                },

                closeAddDepartmentModal() {
                    this.showAddDepartmentModal = false;
                    this.editingDepartment = null;
                    this.resetDepartmentForm();
                },

                async saveDepartment() {
                    try {
                        const url = this.editingDepartment ?
                            `/hr/struktur-organisasi/departments/${this.editingDepartment.id}` :
                            '/hr/struktur-organisasi/departments';

                        const method = this.editingDepartment ? 'PUT' : 'POST';

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(this.departmentForm)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.closeAddDepartmentModal();
                            window.location.reload(); // Reload to update organization chart
                        } else {
                            alert(data.message || 'Gagal menyimpan departemen');
                        }
                    } catch (error) {
                        console.error('Error saving department:', error);
                        alert('Gagal menyimpan departemen');
                    }
                },

                async deleteDepartment(departmentId) {
                    if (!confirm('Apakah Anda yakin ingin menghapus departemen ini?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/hr/struktur-organisasi/departments/${departmentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Gagal menghapus departemen');
                        }
                    } catch (error) {
                        console.error('Error deleting department:', error);
                        alert('Gagal menghapus departemen');
                    }
                },

                // Jabatan Management
                openAddJabatanModal() {
                    this.editingJabatan = null;
                    this.resetJabatanForm();
                    this.showAddJabatanModal = true;
                    this.showManagementModal = false;
                },

                editJabatan(jabatan) {
                    this.editingJabatan = jabatan;
                    this.jabatanForm = {
                        nama_jabatan: jabatan.nama_jabatan || jabatan.nama,
                        kode_jabatan: jabatan.kode_jabatan || jabatan.kode,
                        department_id: jabatan.department_id || '',
                        level: jabatan.level || '',
                        deskripsi: jabatan.deskripsi || ''
                    };
                    this.showAddJabatanModal = true;
                    this.showManagementModal = false;
                },

                resetJabatanForm() {
                    this.jabatanForm = {
                        nama_jabatan: '',
                        kode_jabatan: '',
                        department_id: '',
                        level: '',
                        deskripsi: ''
                    };
                },

                closeAddJabatanModal() {
                    this.showAddJabatanModal = false;
                    this.editingJabatan = null;
                    this.resetJabatanForm();
                },

                async saveJabatan() {
                    try {
                        const url = this.editingJabatan ?
                            `/hr/jabatan/${this.editingJabatan.id}` :
                            '/hr/jabatan';

                        const method = this.editingJabatan ? 'PUT' : 'POST';

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(this.jabatanForm)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.closeAddJabatanModal();
                            window.location.reload(); // Reload to update jabatan list
                        } else {
                            alert(data.message || 'Gagal menyimpan jabatan');
                        }
                    } catch (error) {
                        console.error('Error saving jabatan:', error);
                        alert('Gagal menyimpan jabatan');
                    }
                },

                async deleteJabatan(jabatanId) {
                    if (!confirm('Apakah Anda yakin ingin menghapus jabatan ini?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/hr/jabatan/${jabatanId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Gagal menghapus jabatan');
                        }
                    } catch (error) {
                        console.error('Error deleting jabatan:', error);
                        alert('Gagal menghapus jabatan');
                    }
                }
            }
        }
    </script>
</x-app-layout>

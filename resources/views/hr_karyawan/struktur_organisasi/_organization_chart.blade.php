{{-- Simple & Clean Organization Chart --}}
<div class="organization-chart-container">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Struktur Organisasi</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ count($organizationData) }} Departemen • {{ collect($organizationData)->sum('employee_count') }}
                    Karyawan
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button @click="showAddDepartmentModal = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Departemen
                </button>
                <button @click="showAddJabatanModal = true"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Jabatan
                </button>

            </div>
        </div>

        {{-- Organization Chart --}}
        <div class="space-y-6">

            {{-- Executive Level --}}
            <div class="flex justify-center mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg p-6 shadow-lg max-w-md">
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white bg-opacity-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">CEO / Direktur Utama</h3>
                        <p class="text-blue-100 text-sm">Pimpinan Tertinggi Perusahaan</p>
                        <div class="flex justify-center gap-4 mt-4 text-sm">
                            <span>{{ count($organizationData) }} Departemen</span>
                            <span>•</span>
                            <span>{{ collect($organizationData)->sum('employee_count') }} Karyawan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Connection Line --}}
            @if (count($organizationData) > 0)
                <div class="flex justify-center">
                    <div class="w-px h-8 bg-gray-300 dark:bg-gray-600"></div>
                </div>
            @endif

            {{-- Departments Grid --}}
            @if (count($organizationData) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($organizationData as $department)
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            {{-- Department Header --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">
                                        {{ $department['name'] }}</h4>
                                    <div x-data="{ showMenu: false }" class="relative">
                                        <button @click="showMenu = !showMenu"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>
                                        <div x-show="showMenu" @click.outside="showMenu = false" x-transition
                                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-600 z-10">
                                            <div class="py-1">
                                                <button
                                                    @click="showDepartmentDetails({{ $department['id'] }}); showMenu = false"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Lihat Detail
                                                </button>
                                                <button
                                                    @click="editDepartment({{ $department['id'] }}, '{{ $department['name'] }}', '{{ $department['code'] }}', '{{ addslashes($department['description'] ?? '') }}'); showMenu = false"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Edit
                                                </button>
                                                <button
                                                    @click="deleteDepartment({{ $department['id'] }}, '{{ $department['name'] }}'); showMenu = false"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $department['code'] }}</p>
                            </div>

                            {{-- Department Content --}}
                            <div class="p-4 cursor-pointer" @click="showDepartmentDetails({{ $department['id'] }})">
                                {{-- Department Icon --}}
                                <div class="flex justify-center mb-3">
                                    <div
                                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Manager Info --}}
                                @if (isset($department['manager']) && $department['manager'])
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-3 mb-3">
                                        <div class="flex items-center space-x-2">
                                            <div
                                                class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                    {{ substr($department['manager']['name'], 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ $department['manager']['name'] }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $department['manager']['position'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Stats --}}
                                <div class="flex justify-center space-x-4 mb-3">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $department['employee_count'] ?? 0 }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Karyawan</div>
                                    </div>
                                    @if (count($department['children'] ?? []) > 0)
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ count($department['children']) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Sub-Dept</div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Description --}}
                                @if ($department['description'] ?? false)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 text-center">
                                        {{ Str::limit($department['description'], 80) }}
                                    </p>
                                @endif
                            </div>

                            {{-- Sub-departments --}}
                            @if (count($department['children'] ?? []) > 0)
                                <div
                                    class="border-t border-gray-200 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-800">
                                    <div class="text-center mb-2">
                                        <button @click="expandSubDepartments({{ $department['id'] }})"
                                            class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                                            Lihat {{ count($department['children']) }} Sub-Departemen
                                        </button>
                                    </div>

                                    {{-- Preview of first 2 sub-departments --}}
                                    <div class="space-y-1">
                                        @foreach (collect($department['children'])->take(2) as $subDept)
                                            <div class="bg-white dark:bg-gray-700 rounded p-2 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                                @click="showDepartmentDetails({{ $subDept['id'] }})">
                                                <div class="flex justify-between items-center">
                                                    <span
                                                        class="font-medium text-gray-900 dark:text-white">{{ $subDept['name'] }}</span>
                                                    <span
                                                        class="text-gray-500 dark:text-gray-400">{{ $subDept['employee_count'] ?? 0 }}</span>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if (count($department['children']) > 2)
                                            <div class="text-center text-xs text-gray-500 dark:text-gray-400 pt-1">
                                                +{{ count($department['children']) - 2 }} lainnya
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Departemen</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Mulai dengan menambahkan departemen pertama.</p>
                    <button @click="showAddDepartmentModal = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Tambah Departemen
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

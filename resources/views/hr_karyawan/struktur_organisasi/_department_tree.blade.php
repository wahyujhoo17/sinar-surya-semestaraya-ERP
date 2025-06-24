{{-- Department Tree Item --}}
<div class="department-tree-item" style="margin-left: {{ $level * 24 }}px">
    <div
        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-3 hover:shadow-md transition-all duration-200 hover:border-primary-300 dark:hover:border-primary-600">
        <div class="flex items-center justify-between">
            {{-- Department Info --}}
            <div class="flex items-center space-x-4 flex-1">
                {{-- Level Indicator --}}
                @if ($level > 0)
                    <div class="flex items-center space-x-2">
                        @for ($i = 0; $i < $level; $i++)
                            <div class="w-4 h-px bg-gray-300 dark:bg-gray-600"></div>
                        @endfor
                        <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                    </div>
                @endif

                {{-- Department Icon --}}
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>

                {{-- Department Details --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $department->nama }}</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                            {{ $department->kode }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $department->deskripsi ?: 'Tidak ada deskripsi' }}</p>

                    {{-- Employee Statistics --}}
                    <div class="flex items-center space-x-4 mt-2">
                        <div class="flex items-center space-x-1 text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-1L11 4.5-2 21h3M12 9.75l3.5 3.5-3.5 3.5z">
                                </path>
                            </svg>
                            <span>{{ $department->karyawan->where('status', 'aktif')->count() }} Karyawan Aktif</span>
                        </div>
                        @if ($department->children->count() > 0)
                            <div class="flex items-center space-x-1 text-sm text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span>{{ $department->children->count() }} Sub Departemen</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center space-x-2">
                {{-- View Details Button --}}
                <button @click="showDepartmentDetails({{ $department->id }})"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 border border-primary-200 rounded-md hover:bg-primary-100 hover:border-primary-300 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-800 dark:hover:bg-primary-900/30 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    Detail
                </button>

                {{-- Expand/Collapse Button for children --}}
                @if ($department->children->count() > 0)
                    <button x-data="{ expanded: true }" @click="expanded = !expanded"
                        class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                        <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                        <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7">
                            </path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Employees Preview (show 3 employees max) --}}
        @if ($department->karyawan->where('status', 'aktif')->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Karyawan:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach ($department->karyawan->where('status', 'aktif')->take(5) as $employee)
                        <div class="flex items-center space-x-2 bg-gray-50 dark:bg-gray-600 rounded-full px-3 py-1">
                            <div class="flex-shrink-0">
                                @if ($employee->foto)
                                    <img src="{{ asset('storage/' . $employee->foto) }}"
                                        alt="{{ $employee->nama_lengkap }}" class="h-6 w-6 rounded-full object-cover">
                                @else
                                    <div
                                        class="h-6 w-6 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                        <span
                                            class="text-primary-700 dark:text-primary-300 font-medium text-xs">{{ substr($employee->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs text-gray-700 dark:text-gray-300">{{ $employee->nama_lengkap }}</span>
                        </div>
                    @endforeach
                    @if ($department->karyawan->where('status', 'aktif')->count() > 5)
                        <span class="text-xs text-gray-500 dark:text-gray-400 px-2 py-1">
                            +{{ $department->karyawan->where('status', 'aktif')->count() - 5 }} lainnya
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Render Children Departments --}}
    @if ($department->children->count() > 0)
        <div x-data="{ expanded: true }" x-show="expanded" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @foreach ($department->children as $child)
                @include('hr_karyawan.struktur_organisasi._department_tree', [
                    'department' => $child,
                    'level' => $level + 1,
                ])
            @endforeach
        </div>
    @endif
</div>

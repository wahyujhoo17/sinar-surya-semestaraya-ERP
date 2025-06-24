@forelse ($users as $user)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                @change="toggleUserSelection({{ $user->id }})"
                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                    @if ($user->karyawan)
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->karyawan->jabatan->nama ?? 'Karyawan' }}</div>
                    @endif
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
            @if ($user->username && $user->username !== $user->email)
                <div class="text-sm text-gray-500 dark:text-gray-400">Username: {{ $user->username }}</div>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex flex-wrap gap-1">
                @forelse($user->roles as $role)
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $role->kode === 'admin'
                            ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            : ($role->kode === 'manager'
                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                        {{ $role->nama ?? $role->name }}
                    </span>
                @empty
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                        Tidak ada role
                    </span>
                @endforelse
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                @if ($user->is_active)
                    <div class="flex items-center">
                        <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-sm text-green-800 dark:text-green-200">Aktif</span>
                    </div>
                @else
                    <div class="flex items-center">
                        <div class="h-2 w-2 bg-red-400 rounded-full mr-2"></div>
                        <span class="text-sm text-red-800 dark:text-red-200">Tidak Aktif</span>
                    </div>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            <div>{{ $user->created_at->format('d/m/Y') }}</div>
            <div class="text-xs">{{ $user->created_at->format('H:i') }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center space-x-2">
                <!-- View Button -->
                <button @click="openViewModal({{ $user->id }})"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                    title="Lihat Detail">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </button>

                <!-- Edit Button -->
                <button @click="openEditModal({{ $user->id }})"
                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors duration-150"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </button>

                <!-- Status Toggle Button -->
                <button @click="toggleUserStatus({{ $user->id }})"
                    class="{{ $user->is_active ? 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300' : 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' }} transition-colors duration-150"
                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                    @if ($user->is_active)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                            </path>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </button>

                <!-- Reset Password Button -->
                <button @click="resetUserPassword({{ $user->id }})"
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-150"
                    title="Reset Password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </button>

                <!-- Delete Button -->
                @if (!in_array('admin', $user->roles->pluck('kode')->toArray()) || auth()->user()->hasRole('admin'))
                    @if ($user->id !== auth()->id())
                        <button @click="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                            title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    @endif
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
            Tidak ada data pengguna
        </td>
    </tr>
@endforelse

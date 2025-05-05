@if ($karyawan->isEmpty())
    <tr>
        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
            <div class="flex flex-col items-center justify-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-200 dark:text-gray-700 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada Data Karyawan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Tambahkan data karyawan baru untuk memulai.</p>
                <a href="{{ route('hr.karyawan.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Karyawan
                </a>
            </div>
        </td>
    </tr>
@else
    @foreach ($karyawan as $k)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors duration-200">
            <td class="px-3 py-4 whitespace-nowrap">
                <input type="checkbox" name="karyawan_ids[]" value="{{ $k->id }}"
                    @click="updateSelectedKaryawan($event, '{{ $k->id }}')"
                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
            </td>
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        @if ($k->foto)
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $k->foto) }}"
                                alt="{{ $k->nama_lengkap }}">
                        @else
                            <div
                                class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-medium">
                                {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $k->nama_lengkap }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $k->email }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $k->nip }}
                </div>
            </td>
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-8 w-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-medium">
                        {{ strtoupper(substr($k->department->nama ?? 'N/A', 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $k->department->nama ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $k->jabatan->nama ?? 'N/A' }}
                </div>
            </td>
            <td class="px-5 py-4 whitespace-nowrap">
                @php
                    $statusClasses = [
                        'aktif' =>
                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-800/30',
                        'nonaktif' =>
                            'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-800/30',
                        'cuti' =>
                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-800/30',
                        'keluar' =>
                            'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border-gray-300 dark:border-gray-600',
                    ];
                    $statusClass = $statusClasses[$k->status] ?? $statusClasses['aktif'];
                @endphp
                <span
                    class="px-3 py-1 inline-flex text-xs leading-5 font-medium rounded-full border {{ $statusClass }}">
                    {{ ucfirst($k->status) }}
                </span>
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('hr.karyawan.show', $k->id) }}"
                        class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                        <span class="sr-only">Detail</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <a href="{{ route('hr.karyawan.edit', $k->id) }}"
                        class="text-blue-600 hover:text-blue-900 dark:text-blue-500 dark:hover:text-blue-400">
                        <span class="sr-only">Edit</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('hr.karyawan.destroy', $k->id) }}" method="POST"
                        class="inline-block delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="confirmDelete($event, '{{ $k->id }}')"
                            class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400">
                            <span class="sr-only">Delete</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
@endif

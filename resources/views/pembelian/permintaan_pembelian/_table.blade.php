@php
    // Helper function to get status color
    function statusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'diajukan':
                return 'blue';
            case 'disetujui':
                return 'emerald';
            case 'ditolak':
                return 'red';
            case 'selesai':
                return 'purple';
            default:
                return 'primary';
        }
    }
@endphp

<div
    class="overflow-hidden rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm mt-5">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th
                        class="group px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 text-xs">
                            <span>No</span>
                        </div>
                    </th>
                    <th
                        class="group px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 text-xs">
                            <span>Nomor</span>
                        </div>
                    </th>
                    <th
                        class="group px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 text-xs">
                            <span>Tanggal</span>
                        </div>
                    </th>
                    <th
                        class="group px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 text-xs">
                            <span>Departemen</span>
                        </div>
                    </th>
                    <th
                        class="group px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 text-xs">
                            <span>Pemohon</span>
                        </div>
                    </th>
                    <th
                        class="group px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 text-xs">
                            <span>Status</span>
                        </div>
                    </th>
                    <th
                        class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($permintaanPembelian as $pr)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ ($permintaanPembelian->firstItem() ?? 1) + $loop->index }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-{{ statusColor($pr->status) }}-100 dark:bg-{{ statusColor($pr->status) }}-900/20 text-{{ statusColor($pr->status) }}-600 dark:text-{{ statusColor($pr->status) }}-400 flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <a href="{{ route('pembelian.permintaan-pembelian.show', $pr->id) }}">
                                        <div class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                            {{ $pr->nomor }}
                                        </div>
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $pr->catatan ?? 'Tidak ada catatan' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm text-gray-700 dark:text-gray-200">
                                {{ tanggal_indo($pr->tanggal) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($pr->tanggal)->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ $pr->department->nama ?? '-' }}
                            </div>
                            @if ($pr->department && $pr->department->kode)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Kode: {{ $pr->department->kode }}
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                    @if ($pr->user && $pr->user->profile_photo_url)
                                        <img src="{{ $pr->user->profile_photo_url }}" alt="{{ $pr->user->name }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        <svg class="h-full w-full text-gray-400" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ $pr->user->name ?? '-' }}
                                    </div>
                                    @if ($pr->user && $pr->user->email)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $pr->user->email }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $statusColors = [
                                    'draft' => [
                                        'bg' => 'bg-gray-100 dark:bg-gray-700',
                                        'text' => 'text-gray-700 dark:text-gray-300',
                                        'dot' => 'bg-gray-400 dark:bg-gray-500',
                                    ],
                                    'diajukan' => [
                                        'bg' => 'bg-blue-100 dark:bg-blue-900/20',
                                        'text' => 'text-blue-700 dark:text-blue-300',
                                        'dot' => 'bg-blue-500 dark:bg-blue-400',
                                    ],
                                    'disetujui' => [
                                        'bg' => 'bg-emerald-100 dark:bg-emerald-900/20',
                                        'text' => 'text-emerald-700 dark:text-emerald-300',
                                        'dot' => 'bg-emerald-500 dark:bg-emerald-400',
                                    ],
                                    'ditolak' => [
                                        'bg' => 'bg-red-100 dark:bg-red-900/20',
                                        'text' => 'text-red-700 dark:text-red-300',
                                        'dot' => 'bg-red-500 dark:bg-red-400',
                                    ],
                                    'selesai' => [
                                        'bg' => 'bg-purple-100 dark:bg-purple-900/20',
                                        'text' => 'text-purple-700 dark:text-purple-300',
                                        'dot' => 'bg-purple-500 dark:bg-purple-400',
                                    ],
                                ];
                                $status = $pr->status;
                                $colors = $statusColors[$status] ?? $statusColors['draft'];
                            @endphp

                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $colors['dot'] }}"></span>
                                {{ ucfirst($pr->status) }}
                            </span>

                            @if ($pr->updated_at && $pr->updated_at->diffInDays() < 3)
                                <span
                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300 ml-2">
                                    Baru
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex gap-1 justify-end items-center">
                                <!-- View button - always visible -->
                                <a href="{{ route('pembelian.permintaan-pembelian.show', $pr->id) }}"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                    title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-4 h-4">
                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                        <path fill-rule="evenodd"
                                            d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>

                                <!-- Edit button -->
                                @if ($pr->status == 'draft')
                                    <a href="{{ route('pembelian.permintaan-pembelian.edit', $pr->id) }}"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-4 h-4">
                                            <path
                                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path
                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 dark:text-gray-600 dark:bg-gray-800 border border-dashed border-gray-300"
                                        title="Edit tidak tersedia">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-4 h-4">
                                            <path
                                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path
                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0027 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </span>
                                @endif

                                <!-- Delete button with Modern Alert -->
                                @if ($pr->status == 'draft')
                                    <div x-data="{ showConfirmModal: false }">
                                        <!-- Delete Button -->
                                        <button type="button" @click="showConfirmModal = true"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-gray-700 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd"
                                                    d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Modern Confirmation Dialog -->
                                        <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto"
                                            style="display: none;" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                            <!-- Backdrop -->
                                            <div
                                                class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
                                            </div>

                                            <!-- Dialog -->
                                            <div class="flex min-h-screen items-center justify-center p-4">
                                                <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95">

                                                    <div
                                                        class="flex items-center justify-center w-12 h-12 mx-auto rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </div>

                                                    <div class="text-center">
                                                        <h3
                                                            class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                                            Konfirmasi Hapus</h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                Apakah Anda yakin ingin menghapus permintaan pembelian
                                                                {{ $pr->nomor }}? <br>
                                                                Data yang dihapus tidak dapat dikembalikan.
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-5 sm:mt-6 flex justify-center space-x-3">
                                                        <button type="button" @click="showConfirmModal = false"
                                                            class="inline-flex justify-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                            Batal
                                                        </button>
                                                        <form
                                                            action="{{ route('pembelian.permintaan-pembelian.destroy', $pr->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                                Hapus Permintaan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-50 text-gray-400 dark:text-gray-600 dark:bg-gray-800 border border-dashed border-gray-300"
                                        title="Hapus tidak tersedia">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div
                                    class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-700/50">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-base font-medium text-gray-500 dark:text-gray-400">Tidak ada data
                                        permintaan pembelian</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 max-w-md mx-auto">
                                        Belum ada permintaan pembelian dengan status ini.
                                    </p>
                                </div>
                                <a href="{{ route('pembelian.permintaan-pembelian.create') }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Buat Permintaan
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $permintaanPembelian->links('vendor.pagination.tailwind-custom') }}
</div>

@push('scripts')
    <script>
        function statusColor(status) {
            switch (status) {
                case 'draft':
                    return 'gray';
                case 'diajukan':
                    return 'blue';
                case 'disetujui':
                    return 'emerald';
                case 'ditolak':
                    return 'red';
                case 'selesai':
                    return 'purple';
                default:
                    return 'primary';
            }
        }
    </script>
@endpush

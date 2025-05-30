<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Daftar Nota Kredit</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Mengelola data nota kredit untuk pengembalian dana kepada customer
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('penjualan.nota-kredit.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari:</label>
                            <input type="text" name="search" id="search"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                placeholder="Nomor / Customer" value="{{ request('search') }}">
                        </div>
                        <div>
                            <label for="date_start"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari
                                Tanggal:</label>
                            <input type="date" name="date_start" id="date_start"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                value="{{ request('date_start') }}">
                        </div>
                        <div>
                            <label for="date_end"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai
                                Tanggal:</label>
                            <input type="date" name="date_end" id="date_end"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                value="{{ request('date_end') }}">
                        </div>
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status:</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="semua">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                                    Diproses</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('penjualan.nota-kredit.index') }}"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div
                class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-800 rounded-lg overflow-hidden shadow-sm">
                <div class="px-6 py-4 flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 dark:bg-blue-600 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 dark:text-blue-300 font-medium">Total Nota Kredit</p>
                        <p class="text-2xl font-bold text-blue-800 dark:text-blue-100">
                            {{ number_format($statusCounts['semua'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-amber-50 dark:bg-amber-900 border border-amber-200 dark:border-amber-800 rounded-lg overflow-hidden shadow-sm">
                <div class="px-6 py-4 flex items-center">
                    <div class="flex-shrink-0 bg-amber-500 dark:bg-amber-600 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-amber-600 dark:text-amber-300 font-medium">Nota Kredit Draft</p>
                        <p class="text-2xl font-bold text-amber-800 dark:text-amber-100">
                            {{ number_format($statusCounts['draft'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-emerald-50 dark:bg-emerald-900 border border-emerald-200 dark:border-emerald-800 rounded-lg overflow-hidden shadow-sm">
                <div class="px-6 py-4 flex items-center">
                    <div class="flex-shrink-0 bg-emerald-500 dark:bg-emerald-600 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600 dark:text-emerald-300 font-medium">Nota Kredit Selesai</p>
                        <p class="text-2xl font-bold text-emerald-800 dark:text-emerald-100">
                            {{ number_format($statusCounts['selesai'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No.</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nomor</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Customer</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No. Retur</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($notaKredits ?? [] as $index => $notaKredit)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $notaKredit->nomor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notaKredit->tanggal ? \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notaKredit->customer->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notaKredit->returPenjualan->nomor ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($notaKredit->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($notaKredit->status == 'draft')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-amber-500" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Draft
                                        </span>
                                    @elseif($notaKredit->status == 'diproses')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-500" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Diproses
                                        </span>
                                    @elseif($notaKredit->status == 'selesai')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-emerald-500" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $notaKredit->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('penjualan.nota-kredit.show', $notaKredit->id) }}"
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        @if ($notaKredit->status == 'draft')
                                            <a href="{{ route('penjualan.nota-kredit.edit', $notaKredit->id) }}"
                                                class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300"
                                                title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <button type="button"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 btn-delete"
                                                data-modal-target="deleteModal" data-modal-toggle="deleteModal"
                                                data-id="{{ $notaKredit->id }}" data-name="{{ $notaKredit->nomor }}"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif

                                        <a href="{{ route('penjualan.nota-kredit.pdf', $notaKredit->id) }}"
                                            target="_blank"
                                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                            title="Download PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data nota kredit
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($notaKredits) && $notaKredits->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $notaKredits->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="deleteModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-500 w-12 h-12" aria-hidden="true" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin
                        menghapus nota kredit <span id="delete-name" class="font-medium"></span>?</h3>
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Hapus
                        </button>
                        <button type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                            data-modal-hide="deleteModal">
                            Batal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Setup delete modal
                const deleteButtons = document.querySelectorAll('.btn-delete');
                const deleteName = document.getElementById('delete-name');
                const deleteForm = document.getElementById('delete-form');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const name = this.getAttribute('data-name');

                        deleteName.textContent = name;
                        deleteForm.action = `{{ url('penjualan/nota-kredit') }}/${id}`;
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Detail Bill of Material'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Bill of Material</h1>
            <div class="flex space-x-3">
                {{-- Back Button --}}
                <a href="{{ route('produksi.bom.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                {{-- Edit Button --}}
                @if (auth()->user()->hasPermission('bill_of_material.edit'))
                    <a href="{{ route('produksi.bom.edit', $bom->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit BOM
                    </a>
                @else
                    <span
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Tidak Ada Akses
                    </span>
                @endif

                {{-- Delete Button --}}
                @if (auth()->user()->hasPermission('bill_of_material.delete'))
                    <button
                        @click="window.dispatchEvent(new CustomEvent('open-delete-modal', {detail: {id: {{ $bom->id }}}}));"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus BOM
                    </button>
                @else
                    <span
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Tidak Ada Akses
                    </span>
                @endif
            </div>
        </div>

        {{-- BOM Details Card --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informasi BOM</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode BOM</h3>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $bom->kode }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama BOM</h3>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $bom->nama }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Versi</h3>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $bom->versi }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                        <p class="mt-1">
                            @if ($bom->is_active)
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-200/30 dark:text-green-400">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400">
                                    Non Aktif
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</h3>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                            {{ $bom->produk->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</h3>
                        <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $bom->deskripsi ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Biaya Overhead</h3>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                            @if ($bom->overhead_cost > 0)
                                <span class="text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($bom->overhead_cost, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOM Components Card --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Komponen BOM</h2>
                    @if (auth()->user()->hasPermission('bill_of_material.edit'))
                        <a href="{{ route('produksi.bom.edit', $bom->id) }}#components"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Komponen
                        </a>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Tidak Ada Akses
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Kode Komponen
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Komponen
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Catatan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($bom->details as $index => $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detail->komponen->kode ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detail->komponen->nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detail->satuan->nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detail->catatan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada komponen BOM yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Cost Breakdown Card --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden mb-6" x-data="costBreakdown">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Breakdown Harga Pokok</h2>
                    <div class="flex space-x-2">
                        <button @click="loadCostBreakdown()"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </button>
                        @if (auth()->user()->hasPermission('bill_of_material.edit'))
                            <button @click="updateProductCost()" :disabled="updating"
                                :class="updating ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg x-show="!updating" class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <svg x-show="updating" class="-ml-1 mr-2 h-4 w-4 animate-spin"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span x-text="updating ? 'Mengupdate...' : 'Update Harga Beli'"></span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-300">Memuat data breakdown...</span>
                </div>

                <!-- Error State -->
                <div x-show="error"
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4 mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-red-800 dark:text-red-200" x-text="errorMessage"></p>
                        </div>
                    </div>
                </div>

                <!-- Cost Summary -->
                <div x-show="data && !loading" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Harga Beli Saat Ini</h3>
                        <p class="mt-1 text-2xl font-bold text-blue-900 dark:text-blue-100"
                            x-text="formatCurrency(data?.current_cost || 0)"></p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Harga Kalkulasi BOM</h3>
                        <p class="mt-1 text-2xl font-bold text-green-900 dark:text-green-100"
                            x-text="formatCurrency(data?.calculated_cost || 0)"></p>
                    </div>
                    <div class="p-4 rounded-lg border-2" :style="getSimpleDifferenceStyle()">
                        <h3 class="text-sm font-medium" x-text="getDifferenceLabel()"></h3>
                        <div class="mt-1 flex items-center">
                            <p class="text-2xl font-bold" x-text="getDifferenceValue()"></p>
                            <span x-show="data?.cost_difference === 0"
                                style="margin-left: 8px; color: green; font-weight: bold;">
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Cost Breakdown Table -->
                <div x-show="data?.breakdown && data.breakdown.length > 0 && !loading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Komponen
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Satuan
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Total Cost
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="item in data.breakdown" :key="item.komponen_id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <span x-text="item.komponen_nama"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <span x-text="item.quantity"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <span x-text="formatCurrency(item.harga_satuan)"></span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        <span x-text="formatCurrency(item.total_cost)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                    Total Harga Pokok:
                                </td>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                    <span x-text="formatCurrency(data?.calculated_cost || 0)"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- No BOM Message -->
                <div x-show="data && !data.has_bom && !loading" class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak Ada Breakdown</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">BOM ini belum memiliki komponen untuk
                        dihitung.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete Confirmation --}}
    @if (auth()->user()->hasPermission('bill_of_material.delete'))
        <div x-data="{ open: false, performDelete() { deleteItem({{ $bom->id }}); } }" x-init="window.addEventListener('open-delete-modal', event => {
            if (event.detail.id == {{ $bom->id }}) {
                open = true;
            }
        })" @keydown.esc.window="open = false">
            <!-- Modal background -->
            <div x-show="open"
                class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity z-40"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <!-- Modal panel -->
            <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Modal content -->
                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600 dark:text-red-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        Hapus BOM
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Apakah Anda yakin ingin menghapus BOM ini? Tindakan ini tidak dapat
                                            dibatalkan.
                                            Semua data yang terkait dengan BOM ini juga akan dihapus.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="performDelete()">
                                Hapus
                            </button>
                            <button type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="open = false">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function deleteItem(id) {
            // Check if user has delete permission
            @if (!auth()->user()->hasPermission('bill_of_material.delete'))
                alert('Anda tidak memiliki akses untuk menghapus BOM.');
                return;
            @endif

            const url = '{{ route('produksi.bom.index') }}/' + id;
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to index page
                        window.location.href = '{{ route('produksi.bom.index') }}';
                    } else {
                        alert('Gagal menghapus BOM: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
        }

        // Cost Breakdown Alpine.js Component
        function costBreakdown() {
            return {
                loading: false,
                updating: false,
                error: false,
                errorMessage: '',
                data: null,

                init() {
                    this.loadCostBreakdown();
                },

                async loadCostBreakdown() {
                    this.loading = true;
                    this.error = false;
                    this.errorMessage = '';

                    try {
                        const response = await fetch(`{{ route('produksi.bom.cost-breakdown', $bom->id) }}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.data = result.data;
                            console.log('Cost breakdown data received:', this.data);
                            console.log('cost_difference value:', this.data.cost_difference);
                            console.log('cost_difference type:', typeof this.data.cost_difference);
                        } else {
                            this.error = true;
                            this.errorMessage = result.message || 'Gagal memuat data breakdown';
                        }
                    } catch (error) {
                        console.error('Error loading cost breakdown:', error);
                        this.error = true;
                        this.errorMessage = 'Terjadi kesalahan saat memuat data';
                    } finally {
                        this.loading = false;
                    }
                },

                async updateProductCost() {
                    if (this.updating) return;

                    this.updating = true;

                    try {
                        const response = await fetch(`{{ route('produksi.bom.update-cost', $bom->id) }}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Show success notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'success',
                                    title: 'Berhasil',
                                    message: 'Harga beli produk berhasil diupdate berdasarkan BOM',
                                    timeout: 3000
                                }
                            }));

                            // Reload cost breakdown to show updated values
                            await this.loadCostBreakdown();
                        } else {
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    title: 'Error',
                                    message: result.message || 'Gagal mengupdate harga beli produk',
                                    timeout: 5000
                                }
                            }));
                        }
                    } catch (error) {
                        console.error('Error updating product cost:', error);
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'error',
                                title: 'Error',
                                message: 'Terjadi kesalahan saat mengupdate harga beli',
                                timeout: 5000
                            }
                        }));
                    } finally {
                        this.updating = false;
                    }
                },

                formatCurrency(amount) {
                    if (!amount) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                },

                getDifferenceClass() {
                    if (!this.data || this.data.cost_difference === 0) {
                        return 'bg-green-50 dark:bg-green-900/20'; // Hijau jika tidak ada selisih (sesuai)
                    }
                    return this.data.cost_difference > 0 ? 'bg-red-50 dark:bg-red-900/20' :
                        'bg-yellow-50 dark:bg-yellow-900/20';
                },

                getDifferenceTextClass() {
                    if (!this.data || this.data.cost_difference === 0) {
                        return 'text-green-800 dark:text-green-200'; // Hijau jika tidak ada selisih (sesuai)
                    }
                    return this.data.cost_difference > 0 ? 'text-red-800 dark:text-red-200' :
                        'text-yellow-800 dark:text-yellow-200';
                },

                getDifferenceLabel() {
                    if (!this.data || this.data.cost_difference === 0) {
                        return 'Status Harga'; // Sesuai
                    }
                    return this.data.cost_difference > 0 ? 'Selisih (Lebih Tinggi)' : 'Selisih (Lebih Rendah)';
                },

                getDifferenceValue() {
                    if (!this.data || this.data.cost_difference === 0) {
                        return 'Sesuai'; // Tampilkan "Sesuai" jika tidak ada selisih
                    }
                    return this.formatCurrency(this.data.cost_difference);
                },

                getSimpleDifferenceStyle() {
                    if (!this.data || this.data.cost_difference === 0) {
                        return 'background-color: #dcfce7; color: #166534; border-color: #22c55e;'; // Hijau
                    }
                    if (this.data.cost_difference > 0) {
                        return 'background-color: #fef2f2; color: #dc2626; border-color: #ef4444;'; // Merah
                    }
                    return 'background-color: #fefce8; color: #ca8a04; border-color: #eab308;'; // Kuning
                }
            }
        }
    </script>
</x-app-layout>

{{-- Modal Detail Transaksi Project --}}
<div x-data="{ 
    isOpen: false, 
    project: null,
    transaksi: [],
    isLoading: false,
    error: null,
    currentPage: 1,
    totalPages: 1,
    
    async loadTransaksi(projectId, page = 1) {
        this.isLoading = true;
        this.error = null;
        
        try {
            const response = await fetch(`{{ route('keuangan.transaksi-project.index') }}?project_id=${projectId}&page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to load transaksi');
            }
            
            const data = await response.json();
            if (data.success) {
                this.transaksi = data.data.data;
                this.currentPage = data.data.current_page;
                this.totalPages = data.data.last_page;
            } else {
                throw new Error(data.message || 'Failed to load transaksi');
            }
        } catch (error) {
            console.error('Error loading transaksi:', error);
            this.error = error.message;
        } finally {
            this.isLoading = false;
        }
    },
    
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },
    
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    
    getJenisColor(jenis) {
        switch(jenis) {
            case 'alokasi': return 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900/30';
            case 'penggunaan': return 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30';
            case 'pengembalian': return 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/30';
            default: return 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900/30';
        }
    },
    
    getJenisIcon(jenis) {
        switch(jenis) {
            case 'alokasi': return 'M12 4.5v15m7.5-7.5h-15';
            case 'penggunaan': return 'M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z';
            case 'pengembalian': return 'M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3';
            default: return 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z';
        }
    }
}" 
@open-detail-transaksi-project.window="
    project = $event.detail.project;
    isOpen = true;
    loadTransaksi(project.id);
"
@close-detail-transaksi-project.window="isOpen = false"
@keydown.escape.window="isOpen = false"
x-show="isOpen" 
x-cloak
class="fixed inset-0 z-50 overflow-y-auto" 
aria-labelledby="modal-title" 
role="dialog" 
aria-modal="true">

    {{-- Background Overlay --}}
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             aria-hidden="true"
             @click="isOpen = false"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Panel --}}
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">

            {{-- Header --}}
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-title">
                        Detail Transaksi Project
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-show="project">
                        <span x-text="project?.nama"></span> - 
                        <span class="font-medium">Saldo: </span>
                        <span x-text="formatRupiah(project?.saldo || 0)"></span>
                    </p>
                </div>
                <button type="button" 
                        @click="isOpen = false" 
                        class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="mt-6">
                {{-- Loading State --}}
                <div x-show="isLoading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Memuat transaksi...</span>
                </div>

                {{-- Error State --}}
                <div x-show="error && !isLoading" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Terjadi kesalahan
                            </h3>
                            <p class="mt-1 text-sm text-red-700 dark:text-red-300" x-text="error"></p>
                        </div>
                    </div>
                </div>

                {{-- Transaksi List --}}
                <div x-show="!isLoading && !error">
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" x-show="project">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-500 text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Alokasi</dt>
                                    <dd class="text-lg font-semibold text-blue-900 dark:text-blue-100" x-text="formatRupiah(project?.total_alokasi || 0)"></dd>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-red-500 text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-red-600 dark:text-red-400">Total Penggunaan</dt>
                                    <dd class="text-lg font-semibold text-red-900 dark:text-red-100" x-text="formatRupiah(project?.total_penggunaan || 0)"></dd>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-green-500 text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-green-600 dark:text-green-400">Saldo Tersisa</dt>
                                    <dd class="text-lg font-semibold text-green-900 dark:text-green-100" x-text="formatRupiah(project?.saldo || 0)"></dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Transaksi Table --}}
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sumber Dana</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="trx in transaksi" :key="trx.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100" x-text="formatDate(trx.tanggal)"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize" :class="getJenisColor(trx.jenis)">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getJenisIcon(trx.jenis)"></path>
                                                </svg>
                                                <span x-text="trx.jenis"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="trx.keterangan"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span x-show="trx.kas" x-text="trx.kas?.nama"></span>
                                            <span x-show="trx.rekening_bank" x-text="trx.rekening_bank?.nama_bank + ' - ' + trx.rekening_bank?.nomor_rekening"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <span :class="trx.jenis === 'penggunaan' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'" x-text="formatRupiah(trx.jumlah)"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="trx.user?.name"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        {{-- Empty State --}}
                        <div x-show="transaksi.length === 0 && !isLoading" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6m-10 0H4m6 0v4m4-4v4m-4-4a2 2 0 012-2h0a2 2 0 012 2m-6 0a2 2 0 012-2h0a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada transaksi</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Project ini belum memiliki transaksi keuangan</p>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div x-show="totalPages > 1" class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6 mt-4">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <button @click="currentPage > 1 && loadTransaksi(project.id, currentPage - 1)" 
                                    :disabled="currentPage <= 1"
                                    class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Previous
                            </button>
                            <button @click="currentPage < totalPages && loadTransaksi(project.id, currentPage + 1)" 
                                    :disabled="currentPage >= totalPages"
                                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Showing page <span class="font-medium" x-text="currentPage"></span> of <span class="font-medium" x-text="totalPages"></span>
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <button @click="currentPage > 1 && loadTransaksi(project.id, currentPage - 1)" 
                                            :disabled="currentPage <= 1"
                                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button @click="currentPage < totalPages && loadTransaksi(project.id, currentPage + 1)" 
                                            :disabled="currentPage >= totalPages"
                                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-6 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                <button type="button" 
                        @click="isOpen = false" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

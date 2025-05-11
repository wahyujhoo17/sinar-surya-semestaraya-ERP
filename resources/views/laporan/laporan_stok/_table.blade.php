<div class="relative rounded-lg" x-transition>
    {{-- Loading overlay with improved animation --}}
    <div x-show="loading"
        class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-10">
        <div class="flex flex-col items-center">
            <div class="relative">
                <div
                    class="h-16 w-16 rounded-full border-4 border-t-primary-500 border-primary-200 dark:border-primary-800 dark:border-t-primary-500 animate-spin">
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="h-8 w-8 text-primary-500/80 animate-pulse" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M21 12c0 1.2-.26 2.35-.72 3.4a7.97 7.97 0 0 1-2.28 2.78 8.53 8.53 0 0 1-3.4 1.54 8.97 8.97 0 0 1-3.2 0 8.53 8.53 0 0 1-3.4-1.54 8 8 0 0 1-2.28-2.78A8.17 8.17 0 0 1 5 12c0-1.2.26-2.35.72-3.4a7.97 7.97 0 0 1 2.28-2.78c1-.74 2.16-1.26 3.4-1.54a8.97 8.97 0 0 1 3.2 0c1.24.28 2.4.8 3.4 1.54a8 8 0 0 1 2.28 2.78A8.17 8.17 0 0 1 21 12z" />
                    </svg>
                </div>
            </div>
            <span class="mt-4 text-sm text-gray-700 dark:text-gray-300 font-medium">Memuat data...</span>
        </div>
    </div>

    {{-- Table container with improved responsive styling --}}
    <div class="overflow-hidden border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto min-w-full">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800/80">
                        <tr>
                            <th scope="col"
                                class="sticky left-0 z-20 bg-inherit px-4 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">
                                No
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[120px]">
                                <div class="flex items-center">
                                    <span>Kode</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[200px]">
                                <div class="flex items-center">
                                    <span>Nama Barang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                Satuan
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[150px]">
                                <div class="flex items-center">
                                    <span>Gudang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28">
                                Stok Awal
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                <div class="flex items-center justify-end">
                                    <span>Barang Masuk</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                <div class="flex items-center justify-end">
                                    <span>Barang Keluar</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                <div class="flex items-center justify-end">
                                    <span>Stok Akhir</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[140px]">
                                Nilai Barang
                            </th>
                            <th scope="col"
                                class="px-4 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[140px]">
                                Update Terakhir
                            </th>
                            <th scope="col"
                                class="sticky right-0 z-20 bg-inherit px-4 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200/70 dark:divide-gray-700/70">
                        <template x-if="data.length === 0 && !loading">
                            <tr>
                                <td colspan="12"
                                    class="px-4 py-16 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700/50 rounded-full p-4 w-20 h-20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Tidak ada
                                                data yang ditemukan</h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah filter
                                                pencarian atau tambahkan data stok baru</p>
                                        </div>
                                        <button @click="resetFilter()"
                                            class="mt-3 inline-flex items-center px-3 py-1.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-md text-sm font-medium hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Reset Filter
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <template x-for="(item, index) in data" :key="item.produk_id + '-' + (item.gudang_id || '')">
                            <tr :class="item.is_below_minimum ?
                                'bg-red-50/70 hover:bg-red-50/90 dark:bg-red-900/10 dark:hover:bg-red-900/20' :
                                'hover:bg-gray-50/90 dark:hover:bg-gray-700/40'"
                                class="transition-all duration-200">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                    x-text="((page - 1) * perPage) + index + 1"></td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-1.5 h-9 bg-primary-500/70 rounded-full mr-3"></div>
                                        <div class="font-mono text-xs text-primary-600 dark:text-primary-400 font-medium"
                                            x-text="item.kode_barang || '-'"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white"
                                        x-text="item.nama_barang || '-'"></div>
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                        x-text="item.satuan || '-'">
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-sm text-gray-600 dark:text-gray-300"
                                            x-text="item.gudang || '-'"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400"
                                    x-text="Number(item.stok_awal || 0).toLocaleString('id-ID')"></td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1 text-green-500 dark:text-green-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        <span class="text-sm text-green-600 dark:text-green-400 font-medium"
                                            x-text="Number(item.barang_masuk || 0).toLocaleString('id-ID')"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1 text-red-500 dark:text-red-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        <span class="text-sm text-red-600 dark:text-red-400 font-medium"
                                            x-text="Number(item.barang_keluar || 0).toLocaleString('id-ID')"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <template x-if="item.is_below_minimum">
                                            <svg class="h-4.5 w-4.5 text-red-500 dark:text-red-400"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <span class="text-sm font-bold"
                                            :class="item.is_below_minimum ? 'text-red-600 dark:text-red-400' :
                                                'text-gray-700 dark:text-gray-300'"
                                            x-text="Number(item.stok_akhir || 0).toLocaleString('id-ID')"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium"
                                        x-text="formatRupiah(item.nilai_barang)"></span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs text-gray-500 dark:text-gray-400"
                                            x-text="formatDate(item.tanggal_update)"></span>
                                    </div>
                                </td>
                                <td
                                    class="sticky right-0 bg-white dark:bg-gray-800 px-4 py-4 whitespace-nowrap text-center">
                                    <button @click="viewRiwayat(item.produk_id, item.gudang_id)"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-primary-400 shadow-sm text-xs font-medium rounded-md text-primary-700 dark:text-primary-400 bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-primary-900/20 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-400 transition-all duration-200">
                                        <svg class="mr-1.5 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Riwayat
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

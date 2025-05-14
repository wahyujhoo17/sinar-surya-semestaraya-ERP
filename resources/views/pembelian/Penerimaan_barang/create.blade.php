<x-app-layout>
    <div x-data="goodsReceiptForm()" class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8" x-cloak>
        @push('styles')
            <style>
                [x-cloak] {
                    display: none !important;
                }

                .fade-enter {
                    opacity: 0;
                }

                .fade-enter-active {
                    opacity: 1;
                    transition: opacity 150ms ease-out;
                }

                .fade-leave {
                    opacity: 1;
                }

                .fade-leave-active {
                    opacity: 0;
                    transition: opacity 150ms ease-in;
                }

                .form-card {
                    transition: all 0.3s ease;
                }

                .form-card:hover {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
            </style>
        @endpush

        <form id="penerimaanForm" @submit.prevent="openConfirmModal" method="POST">
            @csrf

            {{-- Header Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 mb-6 form-card">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-sky-600 h-10 w-1 rounded-full mr-3">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Penerimaan Barang</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Catat barang yang diterima dari
                                    supplier berdasarkan Purchase Order.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('pembelian.penerimaan-barang.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-sky-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                            :disabled="isSubmitting">
                            <span x-show="!isSubmitting" x-transition>Simpan Penerimaan Barang</span>
                            <span x-show="isSubmitting" x-transition class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Step 1: PO Search --}}
                <div x-show="step === 1" x-transition:enter="fade-enter fade-enter-active"
                    x-transition:leave="fade-leave fade-leave-active" class="px-6 py-5">
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <div
                                class="flex items-center justify-center h-6 w-6 rounded-full bg-sky-100 dark:bg-sky-900 text-sky-800 dark:text-sky-300 text-sm font-bold mr-2">
                                1</div>
                            Pilih Purchase Order
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Cari dan pilih Purchase Order (PO) yang akan diproses untuk penerimaan barang. Hanya PO
                            dengan status selain 'selesai' yang dapat dipilih.
                        </p>

                        {{-- Search Box --}}
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="w-full md:w-1/3">
                                    <label for="po_search"
                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Cari Purchase Order
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" id="po_search" x-model="poSearch"
                                            @input="searchPurchaseOrders" placeholder="Cari nomor PO atau supplier..."
                                            class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                                        <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                            <button type="button" @click="searchPurchaseOrders"
                                                class="inline-flex items-center px-2 py-1 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PO List --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div x-show="loading" x-transition:enter="fade-enter fade-enter-active"
                            x-transition:leave="fade-leave fade-leave-active" class="p-8 text-center">
                            <div
                                class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-sky-50 dark:bg-sky-900/30 mb-3">
                                <svg class="animate-spin h-6 w-6 text-sky-600 dark:text-sky-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Memuat data purchase order...</p>
                        </div>

                        <div x-show="!loading && filteredPurchaseOrders.length === 0"
                            x-transition:enter="fade-enter fade-enter-active"
                            x-transition:leave="fade-leave fade-leave-active" class="p-8 text-center">
                            <div
                                class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Tidak ada purchase order</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <template x-if="poSearch">
                                    Tidak ditemukan purchase order yang sesuai dengan pencarian.
                                </template>
                                <template x-if="!poSearch">
                                    Mulai pencarian untuk menampilkan purchase order yang tersedia.
                                </template>
                            </p>
                        </div>

                        <div x-show="!loading && filteredPurchaseOrders.length > 0"
                            x-transition:enter="fade-enter fade-enter-active"
                            x-transition:leave="fade-leave fade-leave-active" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nomor PO
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Supplier
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status Terima
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="po in filteredPurchaseOrders" :key="po.id">
                                        <tr
                                            class="hover:bg-sky-50 dark:hover:bg-sky-900/30 transition duration-150 group">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white group-hover:text-sky-700 dark:group-hover:text-sky-300 transition">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="h-4 w-4 text-sky-400 group-hover:text-sky-600 dark:text-sky-500 dark:group-hover:text-sky-300"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 17v-2a4 4 0 014-4h4" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 7h4v4" />
                                                    </svg>
                                                    <span x-text="po.nomor"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 group-hover:text-sky-700 dark:group-hover:text-sky-300 transition"
                                                x-text="formatDate(po.tanggal)"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 group-hover:text-sky-700 dark:group-hover:text-sky-300 transition"
                                                x-text="po.supplier.nama"></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-1 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full border border-transparent group-hover:border-sky-400 transition"
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500': po
                                                            .status == 'draft',
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500': po
                                                            .status == 'diproses',
                                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-500': po
                                                            .status == 'dikirim',
                                                        'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500': po
                                                            .status == 'dibatalkan',
                                                        'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-500': po
                                                            .status == 'selesai'
                                                    }">
                                                    <template x-if="po.status == 'draft'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="po.status == 'diproses'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="po.status == 'dikirim'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="po.status == 'dibatalkan'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="po.status == 'selesai'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </template>
                                                    <span
                                                        x-text="{
                                                        'draft': 'Draft',
                                                        'diproses': 'Diproses',
                                                        'dikirim': 'Dikirim',
                                                        'dibatalkan': 'Dibatalkan',
                                                        'selesai': 'Selesai'
                                                    }[po.status] || po.status"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-1 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full border border-transparent group-hover:border-sky-400 transition"
                                                    :class="{
                                                        'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500': po
                                                            .status_penerimaan == 'belum_diterima',
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500': po
                                                            .status_penerimaan == 'sebagian',
                                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500': po
                                                            .status_penerimaan == 'diterima'
                                                    }">
                                                    <template x-if="po.status_penerimaan == 'belum_diterima'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <circle cx="12" cy="12" r="9"
                                                                stroke="currentColor" stroke-width="2"
                                                                fill="none" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8 12h8" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="po.status_penerimaan == 'sebagian'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <circle cx="12" cy="12" r="9"
                                                                stroke="currentColor" stroke-width="2"
                                                                fill="none" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8 12h4" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="po.status_penerimaan == 'diterima'">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <circle cx="12" cy="12" r="9"
                                                                stroke="currentColor" stroke-width="2"
                                                                fill="none" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75" />
                                                        </svg>
                                                    </template>
                                                    <span x-text="po.status_penerimaan.replace('_', ' ')"></span>
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button type="button" @click="selectPurchaseOrder(po)"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 font-semibold text-xs shadow-sm hover:bg-sky-200 dark:hover:bg-sky-800 hover:text-sky-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:focus:ring-offset-gray-800 transition">
                                                    Pilih
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Goods Receipt Entry Form --}}
                <div x-show="step === 2" x-transition:enter="fade-enter fade-enter-active"
                    x-transition:leave="fade-leave fade-leave-active" class="px-6 py-5">
                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1 flex items-center">
                                <div
                                    class="flex items-center justify-center h-6 w-6 rounded-full bg-sky-100 dark:bg-sky-900 text-sky-800 dark:text-sky-300 text-sm font-bold mr-2">
                                    2</div>
                                Detail Penerimaan Barang
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Isi informasi penerimaan barang berdasarkan <span
                                    class="font-medium text-gray-900 dark:text-white"
                                    x-text="selectedPO?.nomor || '-'"></span>
                            </p>
                        </div>
                        <button type="button" @click="backToPoSelection"
                            class="text-sm text-sky-600 hover:text-sky-700 dark:text-sky-400 dark:hover:text-sky-300 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali pilih PO
                        </button>
                    </div>

                    {{-- PO Info Summary --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                    x-text="selectedPO?.supplier?.nama || '-'"></p>
                                <input type="hidden" name="po_id" x-model="selectedPO?.id">
                                <input type="hidden" name="supplier_id" x-model="selectedPO?.supplier_id">
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal PO</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                    x-text="formatDate(selectedPO?.tanggal) || '-'"></p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-500': selectedPO
                                                ?.status == 'draft',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-500': selectedPO
                                                ?.status == 'submit',
                                            'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-500': selectedPO
                                                ?.status == 'approved',
                                            'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-500': selectedPO
                                                ?.status == 'dibatalkan',
                                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-500': selectedPO
                                                ?.status == 'selesai'
                                        }"
                                        x-text="selectedPO?.status || '-'">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Goods Receipt Details --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Penerimaan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="date" name="tanggal" id="tanggal" x-model="receiptDate"
                                        required
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                                </div>
                            </div>

                            {{-- Gudang --}}
                            <div>
                                <label for="gudang_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Gudang <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <select name="gudang_id" id="gudang_id" required
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm rounded-md">
                                        <option value="">Pilih Gudang</option>
                                        @foreach ($gudangs as $gudang)
                                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3z"
                                                clip-rule="evenodd" />
                                            <path fill-rule="evenodd"
                                                d="M10 12.586l-2.293-2.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L10 12.586z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Surat Jalan --}}
                            <div>
                                <label for="nomor_surat_jalan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Surat Jalan
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="nomor_surat_jalan" id="nomor_surat_jalan"
                                        class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                        placeholder="Nomor surat jalan dari supplier">
                                </div>
                            </div>

                            {{-- Tanggal Surat Jalan --}}
                            <div>
                                <label for="tanggal_surat_jalan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Surat Jalan
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="date" name="tanggal_surat_jalan" id="tanggal_surat_jalan"
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                                </div>
                            </div>

                            {{-- Catatan --}}
                            <div class="md:col-span-2">
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan
                                </label>
                                <div>
                                    <textarea id="catatan" name="catatan" rows="2"
                                        class="shadow-sm block w-full focus:ring-sky-500 focus:border-sky-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                        placeholder="Catatan tambahan untuk penerimaan barang ini..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Line Items --}}
                        <div class="mt-8">
                            <h3 class="text-base font-medium text-gray-900 dark:text-white mb-4">
                                Item Penerimaan Barang
                            </h3>

                            <div
                                class="overflow-x-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                No
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Kode - Nama Barang
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Qty PO
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Sudah Diterima
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Qty Terima
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Lokasi Rak
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Catatan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <template x-for="(detail, index) in poDetails" :key="detail.id">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                    x-text="index + 1"></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="ml-4">
                                                            <div
                                                                class="text-sm font-medium text-gray-900 dark:text-white">
                                                                <span
                                                                    x-text="detail.produk ? (detail.produk.kode + ' - ' + detail.produk.nama) : detail.nama_item"></span>
                                                                <input type="hidden" :name="`items[${index}][id]`"
                                                                    :value="detail.id">
                                                                <input type="hidden"
                                                                    :name="`items[${index}][produk_id]`"
                                                                    :value="detail.produk_id">
                                                            </div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="detail.deskripsi || '-'"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                    x-text="detail.quantity"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                    x-text="detail.quantity_diterima || 0"></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number" :name="`items[${index}][qty_diterima]`"
                                                        x-model="detail.qty_diterima" @input="validateItemQty(detail)"
                                                        min="0"
                                                        :max="detail.quantity - (detail.quantity_diterima || 0)"
                                                        class="block w-24 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                        :class="{
                                                            'border-red-300 focus:ring-red-500 focus:border-red-500 dark:border-red-700': detail
                                                                .error
                                                        }">
                                                    <p x-show="detail.error"
                                                        class="mt-1 text-xs text-red-600 dark:text-red-400"
                                                        x-text="detail.error"></p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="text" :name="`items[${index}][lokasi_rak]`"
                                                        class="block w-24 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                        placeholder="A-01-B">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="text" :name="`items[${index}][catatan]`"
                                                        class="block w-40 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                        placeholder="Cacat/rusak, dll">
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="poDetails.length === 0">
                                            <td colspan="7"
                                                class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                Tidak ada item pada PO yang dipilih
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div x-show="hasItemErrors"
                                class="mt-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                            Terdapat kesalahan pada item penerimaan barang
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Kuantitas yang diterima tidak boleh melebihi jumlah yang tersisa
                                                    pada PO</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Confirmation Modal - Improved version --}}
            <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @keydown.escape.window="showConfirmModal = false"
                class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center">

                {{-- Modal backdrop with blur effect --}}
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity"
                    @click="showConfirmModal = false"></div>

                {{-- Modal content --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-xl max-w-md w-full mx-auto shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">

                    {{-- Modal header with color accent --}}
                    <div class="bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Konfirmasi Penerimaan Barang
                        </h3>
                        <button type="button" @click="showConfirmModal = false"
                            class="text-white hover:text-gray-100 focus:outline-none transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal body --}}
                    <div class="px-6 py-6">
                        <div class="text-center mb-6">
                            <div
                                class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-sky-100 dark:bg-sky-900/30 mb-4">
                                <svg class="h-8 w-8 text-sky-600 dark:text-sky-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">Konfirmasi Penerimaan Barang
                            </h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Tindakan ini akan mencatat penerimaan barang, mengupdate stok di gudang, dan mengubah
                                status Purchase Order terkait.
                            </p>
                        </div>

                        <div class="space-y-6">
                            {{-- PO Information --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Detail Purchase Order
                                    </h4>
                                </div>
                                <div class="px-4 py-3 grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor PO
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="selectedPO?.nomor || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal PO
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="formatDate(selectedPO?.tanggal) || '-'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Supplier
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="selectedPO?.supplier?.nama || '-'"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Receipt Information --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Detail Penerimaan
                                    </h4>
                                </div>
                                <div class="px-4 py-3 grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                            Penerimaan</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="receiptDate"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Gudang
                                            Tujuan</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="getGudangName()"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                            Surat Jalan</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <span
                                                x-text="document.getElementById('nomor_surat_jalan').value || '-'"></span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                            Surat Jalan</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <span
                                                x-text="document.getElementById('tanggal_surat_jalan').value || '-'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Item Summary --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        Ringkasan Item
                                    </h4>
                                </div>
                                <div class="px-4 py-3 divide-y divide-gray-200 dark:divide-gray-700">
                                    <div class="py-2">
                                        <div class="flex justify-between">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Item
                                                Diterima</p>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white"
                                                x-text="countReceivedItems() + ' item'"></p>
                                        </div>
                                    </div>
                                    <div class="py-2">
                                        <div class="mb-2">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Detail Item
                                            </p>
                                        </div>
                                        <div class="max-h-36 overflow-y-auto pr-2 space-y-1.5">
                                            <template
                                                x-for="(item, index) in poDetails.filter(i => (parseFloat(i.qty_diterima) || 0) > 0)"
                                                :key="item.id">
                                                <div class="flex justify-between text-xs">
                                                    <div class="flex-1 truncate">
                                                        <span
                                                            x-text="item.produk ? (item.produk.kode + ' - ' + item.produk.nama) : item.nama_item"></span>
                                                    </div>
                                                    <div class="flex-none ml-2 font-medium">
                                                        <span x-text="item.qty_diterima"></span>
                                                        <span x-text="item.satuan?.nama || '-'"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Warning message --}}
                            <div
                                class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800 rounded-md p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                            Pastikan data yang dimasukkan sudah benar. Anda dapat mengedit catatan
                                            setelah disimpan, tetapi jumlah barang yang diterima tidak dapat diubah.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal footer --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="showConfirmModal = false"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:focus:ring-offset-gray-800 transition-colors">
                            Kembali
                        </button>
                        <button type="button" @click="submitForm"
                            class="px-4 py-2 bg-sky-600 border border-transparent rounded-md text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:focus:ring-offset-gray-800 transition-colors"
                            :disabled="isSubmitting">
                            <span x-show="!isSubmitting" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Konfirmasi & Simpan
                            </span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function goodsReceiptForm() {
            return {
                step: 1,
                loading: true, // Start with loading state true
                poSearch: '',
                purchaseOrders: @json($purchaseOrders ?? []),
                filteredPurchaseOrders: [],
                selectedPO: null,
                poDetails: [],
                receiptDate: new Date().toISOString().substr(0, 10),
                errors: [],
                isSubmitting: false,
                showConfirmModal: false,

                init() {
                    // Use setTimeout to simulate loading and prevent initial UI flicker
                    setTimeout(() => {
                        this.filteredPurchaseOrders = [...this.purchaseOrders];
                        this.loading = false;
                    }, 300);
                },

                searchPurchaseOrders() {
                    this.loading = true;

                    // Clear previous search results first
                    this.filteredPurchaseOrders = [];

                    // Delayed search to prevent UI lockup and show loading state
                    setTimeout(() => {
                        if (!this.poSearch) {
                            this.filteredPurchaseOrders = [...this.purchaseOrders].filter(po =>
                                po.status !== 'dibatalkan' && po.status !== 'draft'
                            );
                        } else {
                            const search = this.poSearch.toLowerCase();
                            this.filteredPurchaseOrders = this.purchaseOrders
                                .filter(po => po.status !== 'dibatalkan')
                                .filter(po => po.status !== 'draft')
                                .filter(po =>
                                    po.nomor.toLowerCase().includes(search) ||
                                    po.supplier.nama.toLowerCase().includes(search)
                                );
                        }
                        this.loading = false;
                    }, 300);
                },

                selectPurchaseOrder(po) {
                    this.loading = true;
                    setTimeout(() => {
                        this.selectedPO = po;
                        this.poDetails = po.details.map(detail => ({
                            ...detail,
                            qty_diterima: 0,
                            error: null
                        }));
                        this.step = 2;
                        this.loading = false;
                    }, 200);
                },

                backToPoSelection() {
                    this.step = 1;
                    this.selectedPO = null;
                    this.poDetails = [];
                },

                // Rest of the methods remain unchanged
                validateItemQty(item) {
                    const qtyReceived = parseFloat(item.qty_diterima) || 0;
                    const qtyPo = parseFloat(item.quantity) || 0;
                    const qtyAlreadyReceived = parseFloat(item.quantity_diterima) || 0;
                    const maxQty = qtyPo - qtyAlreadyReceived;

                    if (qtyReceived > maxQty) {
                        item.error = `Maksimal ${maxQty}`;
                        return false;
                    } else if (qtyReceived < 0) {
                        item.error = 'Tidak boleh negatif';
                        return false;
                    } else {
                        item.error = null;
                        return true;
                    }
                },

                get hasItemErrors() {
                    return this.poDetails.some(item => item.error !== null);
                },

                validateForm(e) {
                    if (this.isSubmitting) {
                        e.preventDefault();
                        return false;
                    }

                    this.errors = [];

                    // Validate all items
                    let valid = true;
                    this.poDetails.forEach(item => {
                        if (!this.validateItemQty(item)) {
                            valid = false;
                        }
                    });

                    // Check if at least one item has qty > 0
                    const hasItemsToReceive = this.poDetails.some(item => (parseFloat(item.qty_diterima) || 0) > 0);
                    if (!hasItemsToReceive) {
                        this.errors.push('Minimal harus ada 1 item yang diterima');
                        valid = false;
                    }

                    // Check if gudang is selected
                    const gudangId = document.getElementById('gudang_id').value;
                    if (!gudangId) {
                        this.errors.push('Gudang wajib dipilih');
                        valid = false;
                    }

                    // Check if tanggal is filled
                    const tanggal = document.getElementById('tanggal').value;
                    if (!tanggal) {
                        this.errors.push('Tanggal penerimaan wajib diisi');
                        valid = false;
                    }

                    if (!valid) {
                        e.preventDefault();
                        alert('Terdapat kesalahan:\n- ' + this.errors.join('\n- '));
                        return false;
                    }

                    this.isSubmitting = true;
                    return true;
                },

                openConfirmModal(e) {
                    // Validate form before showing modal
                    if (!this.validateBeforeModal()) {
                        return;
                    }

                    this.showConfirmModal = true;
                },

                validateBeforeModal() {
                    this.errors = [];

                    // Validate all items
                    let valid = true;
                    this.poDetails.forEach(item => {
                        if (!this.validateItemQty(item)) {
                            valid = false;
                        }
                    });

                    // Check if at least one item has qty > 0
                    const hasItemsToReceive = this.poDetails.some(item => (parseFloat(item.qty_diterima) || 0) > 0);
                    if (!hasItemsToReceive) {
                        this.errors.push('Minimal harus ada 1 item yang diterima');
                        valid = false;

                        // Show toast notification when no items are selected
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'error',
                                title: 'Validasi Gagal',
                                message: 'Minimal harus ada 1 item yang diterima',
                                timeout: 5000
                            }
                        }));
                    }

                    // Check if gudang is selected
                    const gudangId = document.getElementById('gudang_id').value;
                    if (!gudangId) {
                        this.errors.push('Gudang wajib dipilih');
                        valid = false;
                    }

                    // Check if tanggal is filled
                    const tanggal = document.getElementById('tanggal').value;
                    if (!tanggal) {
                        this.errors.push('Tanggal penerimaan wajib diisi');
                        valid = false;
                    }

                    if (!valid) {
                        // Only show the comprehensive error toast if there are multiple errors
                        if (this.errors.length > 1) {
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    title: 'Validasi Form Gagal',
                                    message: 'Silakan perbaiki kesalahan berikut:',
                                    list: this.errors,
                                    timeout: 8000
                                }
                            }));
                        }
                        return false;
                    }

                    return true;
                },

                submitForm() {
                    if (this.isSubmitting) {
                        return;
                    }

                    this.isSubmitting = true;

                    // Submit the form to the server
                    const form = document.getElementById('penerimaanForm');
                    const formData = new FormData(form);

                    // Add the CSRF token
                    formData.append('_token', document.querySelector('input[name="_token"]').value);

                    // Send the request to the server
                    fetch('{{ route('pembelian.penerimaan-barang.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json' // Explicitly request JSON response
                            }
                        })
                        .then(response => {
                            // First check if the response is OK
                            if (!response.ok) {
                                // Try to parse as JSON first
                                return response.text().then(text => {
                                    let errorMessage = 'Terjadi kesalahan pada server';
                                    try {
                                        // See if it's valid JSON
                                        const data = JSON.parse(text);
                                        errorMessage = data.message || 'Terjadi kesalahan pada server';
                                    } catch (e) {
                                        // If not JSON, it might be HTML error page
                                        console.error('Server returned non-JSON response:', text.substring(0, 500));
                                        if (text.includes('DOCTYPE') || text.includes('<html')) {
                                            errorMessage = 'Server error: Sesi mungkin kadaluarsa atau kesalahan server';
                                        }
                                    }
                                    throw new Error(errorMessage);
                                });
                            }
                            
                            // If response is OK, try to parse as JSON
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Received invalid JSON response:', text.substring(0, 500));
                                    throw new Error('Format respons tidak valid');
                                }
                            });
                        })
                        .then(data => {
                            // Show success notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'success',
                                    title: 'Berhasil',
                                    message: data.message || 'Penerimaan barang berhasil disimpan',
                                    timeout: 3000
                                }
                            }));
                            
                            // Redirect on success after a brief delay
                            setTimeout(() => {
                                window.location.href = data.redirect || '{{ route('pembelian.penerimaan-barang.index') }}';
                            }, 1000);
                        })
                        .catch(error => {
                            console.error('Error submitting form:', error);
                            
                            // Show error notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    title: 'Gagal',
                                    message: error.message || 'Terjadi kesalahan saat menyimpan data',
                                    timeout: 5000
                                }
                            }));
                            
                            this.isSubmitting = false;
                            this.showConfirmModal = false;
                        });
                },

                getGudangName() {
                    const gudangId = document.getElementById('gudang_id').value;
                    const gudangElement = document.querySelector(`#gudang_id option[value="${gudangId}"]`);
                    return gudangElement ? gudangElement.textContent : '-';
                },

                countReceivedItems() {
                    return this.poDetails.filter(item => (parseFloat(item.qty_diterima) || 0) > 0).length;
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                },

                formatStatusPenerimaan(status) {
                    if (!status) return '';
                    switch (status) {
                        case 'belum_diterima':
                            return 'Belum Diterima';
                        case 'sebagian':
                            return 'Diterima Sebagian';
                        case 'diterima':
                            return 'Selesai Diterima';
                        default:
                            return status;
                    }
                }
            }
        }
    </script>
</x-app-layout>

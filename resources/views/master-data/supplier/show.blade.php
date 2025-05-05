<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Supplier</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informasi lengkap untuk <span
                        class="font-medium">{{ $supplier->nama }}</span>.</p>
            </div>
            <div class="flex space-x-3 flex-shrink-0">
                {{-- Edit Button --}}
                <button
                    @click="window.dispatchEvent(new CustomEvent('open-supplier-modal', {detail: {mode: 'edit', supplier: {{ json_encode($supplier) }} }}))"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </button>
                {{-- Back Button --}}
                <a href="{{ route('master.supplier.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Main Details Card --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            {{-- Card Header --}}
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-100 to-blue-100 dark:from-primary-900/30 dark:to-blue-900/30 p-3 rounded-lg shadow-sm">
                            {{-- Icon Supplier --}}
                            <svg class="h-8 w-8 text-primary-600 dark:text-primary-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $supplier->nama }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $supplier->kode }}</p>
                        </div>
                    </div>
                    {{-- Status Badge --}}
                    <div class="flex-shrink-0 mt-2 sm:mt-0">
                        @if ($supplier->is_active)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30">
                                <svg class="h-4 w-4 mr-1.5 animate-pulse" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/30">
                                <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                    <path
                                        d="M4 0a4 4 0 0 0-4 4 4 4 0 0 0 4 4 4 4 0 0 0 4-4 4 4 0 0 0-4-4zM2.5 2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-.5zm0 3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-.5z" />
                                </svg>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Body - Details --}}
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-8">
                    {{-- Kontak Section --}}
                    <div class="md:col-span-1 space-y-4">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $supplier->telepon ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $supplier->email ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. HP</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $supplier->no_hp ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Kontak</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $supplier->nama_kontak ?: '-' }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Section --}}
                    <div class="md:col-span-1 space-y-4">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $supplier->alamat ?: '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Bisnis Section --}}
                    <div class="md:col-span-1 space-y-4">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Produksi</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    {{ $supplier->type_produksi ?: '-' }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan Section --}}
                    <div class="md:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</dt>
                        <dd
                            class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            {!! nl2br(e($supplier->catatan ?: 'Tidak ada catatan.')) !!}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Riwayat Transaksi Section --}}
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Riwayat Transaksi</h2>
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    <div class="flex justify-center items-center mb-3">
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 dark:text-gray-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium">Belum ada riwayat transaksi</p>
                    <p class="text-xs mt-1">Data transaksi pembelian dari supplier ini akan muncul di sini.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Supplier Component for Edit --}}
    <x-modal-supplier />
</x-app-layout>

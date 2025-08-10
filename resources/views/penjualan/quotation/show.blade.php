<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Quotation', 'url' => route('penjualan.quotation.index')],
    ['label' => 'Detail'],
]" :currentPage="'Detail Quotation'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Quotation</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $quotation->nomor }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('penjualan.quotation.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Kembali
                    </a>

                    {{-- PDF Download Dropdown --}}
                    <div x-data="{ pdfDropdownOpen: false }" class="relative">
                        <button @click="pdfDropdownOpen = !pdfDropdownOpen"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Download PDF
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="pdfDropdownOpen" @click.away="pdfDropdownOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10">

                            <!-- Template Sinar Surya (Default) -->
                            <div class="py-1">
                                <a href="{{ route('penjualan.quotation.pdf', $quotation->id) }}"
                                    class="group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">PT Sinar Surya Semestaraya</div>
                                    </div>
                                </a>
                            </div>

                            <!-- Template Indo Atsaka -->
                            <div class="py-1">
                                <a href="{{ route('penjualan.quotation.pdf.template', [$quotation->id, 'indo-atsaka']) }}"
                                    class="group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-red-600 dark:text-red-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">PT Indo Atsaka Industri</div>
                                    </div>
                                </a>
                            </div>

                            <!-- Template Hidayah Cahaya -->
                            <div class="py-1">
                                <a href="{{ route('penjualan.quotation.pdf.template', [$quotation->id, 'hidayah-cahaya']) }}"
                                    class="group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-green-600 dark:text-green-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">PT Hidayah Cahaya Berkah</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->hasPermission('quotation.edit') && $quotation->status == 'draft')
                        <a href="{{ route('penjualan.quotation.edit', $quotation->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit
                        </a>
                    @endif

                    {{-- Status dropdown --}}
                    @if (auth()->user()->hasPermission('quotation.change_status'))
                        <div x-data="{ statusDropdownOpen: false, confirmModal: false, newStatus: '{{ $quotation->status }}', currentStatus: '{{ $quotation->status }}' }">
                            <div class="relative">
                                <button @click="statusDropdownOpen = !statusDropdownOpen"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                    Ubah Status
                                </button>

                                <div x-show="statusDropdownOpen" @click.away="statusDropdownOpen = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10">

                                    <!-- Draft option - only available if current status is draft -->
                                    <div class="py-1" x-show="currentStatus === 'draft'">
                                        <button
                                            @click="newStatus = 'draft'; confirmModal = true; statusDropdownOpen = false"
                                            class="group flex items-center px-4 py-2 text-sm dark:text-gray-300 w-full text-left cursor-not-allowed opacity-60"
                                            disabled>
                                            <span class="h-2 w-2 bg-gray-500 rounded-full mr-3"></span>
                                            Draft (Status Saat Ini)
                                        </button>
                                    </div>

                                    <!-- Dikirim option - available if current status is draft or dikirim (but disabled if current) -->
                                    <div class="py-1" x-show="['draft', 'dikirim'].includes(currentStatus)">
                                        <button x-show="currentStatus !== 'dikirim'"
                                            @click="newStatus = 'dikirim'; confirmModal = true; statusDropdownOpen = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                            <span class="h-2 w-2 bg-blue-500 rounded-full mr-3"></span>
                                            Dikirim
                                        </button>
                                        <button x-show="currentStatus === 'dikirim'"
                                            class="group flex items-center px-4 py-2 text-sm dark:text-gray-300 w-full text-left cursor-not-allowed opacity-60"
                                            disabled>
                                            <span class="h-2 w-2 bg-blue-500 rounded-full mr-3"></span>
                                            Dikirim (Status Saat Ini)
                                        </button>
                                    </div>

                                    @if (auth()->user()->hasPermission('quotation.approve'))
                                        <div class="py-1"
                                            x-show="['draft', 'dikirim', 'disetujui'].includes(currentStatus)">
                                            <button x-show="currentStatus !== 'disetujui'"
                                                @click="newStatus = 'disetujui'; confirmModal = true; statusDropdownOpen = false"
                                                class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                                <span class="h-2 w-2 bg-green-500 rounded-full mr-3"></span>
                                                Disetujui
                                            </button>
                                            <button x-show="currentStatus === 'disetujui'"
                                                class="group flex items-center px-4 py-2 text-sm dark:text-gray-300 w-full text-left cursor-not-allowed opacity-60"
                                                disabled>
                                                <span class="h-2 w-2 bg-green-500 rounded-full mr-3"></span>
                                                Disetujui (Status Saat Ini)
                                            </button>
                                        </div>


                                        <!-- Ditolak option - available if current status is draft or dikirim (but disabled if current) -->

                                        <div class="py-1"
                                            x-show="['draft', 'dikirim', 'ditolak'].includes(currentStatus)">
                                            <button x-show="currentStatus !== 'ditolak'"
                                                @click="newStatus = 'ditolak'; confirmModal = true; statusDropdownOpen = false"
                                                class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                                <span class="h-2 w-2 bg-red-500 rounded-full mr-3"></span>
                                                Ditolak
                                            </button>
                                            <button x-show="currentStatus === 'ditolak'"
                                                class="group flex items-center px-4 py-2 text-sm dark:text-gray-300 w-full text-left cursor-not-allowed opacity-60"
                                                disabled>
                                                <span class="h-2 w-2 bg-red-500 rounded-full mr-3"></span>
                                                Ditolak (Status Saat Ini)
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Kedaluwarsa option - always available (but disabled if current) -->
                                    <div class="py-1">
                                        <button x-show="currentStatus !== 'kedaluwarsa'"
                                            @click="newStatus = 'kedaluwarsa'; confirmModal = true; statusDropdownOpen = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                            <span class="h-2 w-2 bg-amber-500 rounded-full mr-3"></span>
                                            Kedaluwarsa
                                        </button>
                                        <button x-show="currentStatus === 'kedaluwarsa'"
                                            class="group flex items-center px-4 py-2 text-sm dark:text-gray-300 w-full text-left cursor-not-allowed opacity-60"
                                            disabled>
                                            <span class="h-2 w-2 bg-amber-500 rounded-full mr-3"></span>
                                            Kedaluwarsa (Status Saat Ini)
                                        </button>
                                    </div>

                                    <!-- Message about status constraints -->
                                    <div
                                        class="py-2 px-4 text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700">
                                        <div x-show="['disetujui', 'ditolak', 'kedaluwarsa'].includes(currentStatus)">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 inline-block mr-1 text-amber-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Status yang sudah disetujui atau ditolak tidak bisa dikembalikan ke status
                                            sebelumnya.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Change Confirmation Modal --}}
                            <div x-show="confirmModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="confirmModal" x-transition:enter="ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                                    </div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div x-show="confirmModal" x-transition:enter="ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full"
                                                    :class="{
                                                        'bg-gray-100 dark:bg-gray-700': newStatus == 'draft',
                                                        'bg-blue-100 dark:bg-blue-900/20': newStatus == 'dikirim',
                                                        'bg-emerald-100 dark:bg-emerald-900/20': newStatus ==
                                                            'disetujui',
                                                        'bg-red-100 dark:bg-red-900/20': newStatus == 'ditolak',
                                                        'bg-amber-100 dark:bg-amber-900/20': newStatus == 'kedaluwarsa',
                                                    }"
                                                    sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        :class="{
                                                            'text-gray-600 dark:text-gray-400': newStatus == 'draft',
                                                            'text-blue-600 dark:text-blue-400': newStatus == 'dikirim',
                                                            'text-emerald-600 dark:text-emerald-400': newStatus ==
                                                                'disetujui',
                                                            'text-red-600 dark:text-red-400': newStatus == 'ditolak',
                                                            'text-amber-600 dark:text-amber-400': newStatus ==
                                                                'kedaluwarsa',
                                                        }"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                                        id="modal-title">
                                                        Ubah Status Quotation
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            Apakah Anda yakin ingin mengubah status quotation ini
                                                            menjadi
                                                            <span
                                                                :class="{
                                                                    'text-gray-800 dark:text-gray-200': newStatus ==
                                                                        'draft',
                                                                    'text-blue-600 dark:text-blue-400': newStatus ==
                                                                        'dikirim',
                                                                    'text-emerald-600 dark:text-emerald-400': newStatus ==
                                                                        'disetujui',
                                                                    'text-red-600 dark:text-red-400': newStatus ==
                                                                        'ditolak',
                                                                    'text-amber-600 dark:text-amber-400': newStatus ==
                                                                        'kedaluwarsa',
                                                                }"
                                                                class="font-semibold" x-text="newStatus"></span>?
                                                        </p>

                                                        <div class="mt-4">
                                                            <label for="catatan_status"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan
                                                                (opsional)</label>
                                                            <textarea id="catatan_status" name="catatan_status" rows="2"
                                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                                placeholder="Masukkan catatan perubahan status"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <form method="POST"
                                                action="{{ route('penjualan.quotation.changeStatus', $quotation->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" x-bind:value="newStatus">
                                                <input type="hidden" name="catatan_status"
                                                    x-bind:value="document.getElementById('catatan_status').value">
                                                <button type="submit"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm"
                                                    :class="{
                                                        'bg-gray-600 hover:bg-gray-700': newStatus == 'draft',
                                                        'bg-blue-600 hover:bg-blue-700': newStatus == 'dikirim',
                                                        'bg-emerald-600 hover:bg-emerald-700': newStatus == 'disetujui',
                                                        'bg-red-600 hover:bg-red-700': newStatus == 'ditolak',
                                                        'bg-amber-600 hover:bg-amber-700': newStatus == 'kedaluwarsa',
                                                    }">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                            <button @click="confirmModal = false" type="button"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Notification --}}
    @if (session('success'))
        <div class="mb-6">
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6">
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-300">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left column - Info + Status --}}
        <div>
            {{-- Status Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Status</h2>
                </div>
                <div class="p-5">
                    @php
                        $statusColors = [
                            'draft' => 'gray',
                            'dikirim' => 'blue',
                            'disetujui' => 'emerald',
                            'ditolak' => 'red',
                            'kedaluwarsa' => 'amber',
                        ];
                        $statusColor = $statusColors[$quotation->status] ?? 'gray';
                    @endphp
                    <div class="mb-5 flex items-center">
                        <div class="flex-shrink-0">
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/20 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400">
                                @if ($quotation->status == 'draft')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                @elseif($quotation->status == 'dikirim')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @elseif($quotation->status == 'disetujui')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($quotation->status == 'ditolak')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($quotation->status == 'kedaluwarsa')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white capitalize">
                                {{ $quotation->status }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if ($quotation->status == 'draft')
                                    Quotation masih dalam tahap draft
                                @elseif($quotation->status == 'dikirim')
                                    Quotation telah dikirim ke customer
                                @elseif($quotation->status == 'disetujui')
                                    Quotation telah disetujui oleh customer
                                @elseif($quotation->status == 'ditolak')
                                    Quotation ditolak oleh customer
                                @elseif($quotation->status == 'kedaluwarsa')
                                    Quotation telah kedaluwarsa
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($quotation->tanggal_berlaku && \Carbon\Carbon::parse($quotation->tanggal_berlaku)->isFuture())
                        <div
                            class="p-3 bg-amber-50 dark:bg-amber-900/20 text-xs text-amber-800 dark:text-amber-300 rounded-md">
                            <div class="flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">Masa Berlaku:</span>
                            </div>
                            <div class="ml-5">
                                <div>Sampai:
                                    {{ \Carbon\Carbon::parse($quotation->tanggal_berlaku)->format('d M Y') }}</div>
                                <div class="mt-0.5">
                                    Tersisa:
                                    {{ ceil(now()->floatDiffInDays(\Carbon\Carbon::parse($quotation->tanggal_berlaku))) }}
                                    hari
                                </div>
                            </div>
                        </div>
                    @elseif ($quotation->tanggal_berlaku)
                        <div
                            class="p-3 bg-red-50 dark:bg-red-900/20 text-xs text-red-800 dark:text-red-300 rounded-md">
                            <div class="flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">Kedaluwarsa:</span>
                            </div>
                            <div class="ml-5">
                                <div>Tanggal:
                                    {{ \Carbon\Carbon::parse($quotation->tanggal_berlaku)->format('d M Y') }}</div>
                                <div class="mt-0.5">Quotation sudah kedaluwarsa.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Quotation</h2>
                </div>
                <div class="p-5">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Quotation</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $quotation->nomor }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($quotation->tanggal)->format('d M Y') }}</dd>
                        </div>

                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                            <dd class="col-span-2">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $quotation->customer->company ?? ($quotation->customer->nama ?? '-') }}
                                </div>
                                @if ($quotation->customer)
                                    <div class="mt-2 space-y-1.5">
                                        @if ($quotation->customer->email)
                                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $quotation->customer->email }}
                                            </div>
                                        @endif
                                        @if ($quotation->customer->telepon)
                                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $quotation->customer->telepon }}
                                            </div>
                                        @endif
                                        @if ($quotation->customer->telepon_alternatif)
                                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ $quotation->customer->telepon_alternatif }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </dd>
                        </div>

                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                            <dd class="col-span-2">
                                @if ($quotation->customer)
                                    <div class="text-sm text-gray-900 dark:text-white mb-1">
                                        {{ $quotation->customer->alamat ?? 'Alamat tidak tersedia' }}
                                    </div>
                                    <div class="flex flex-wrap gap-x-2 text-xs text-gray-600 dark:text-gray-400">
                                        @if ($quotation->customer->kota)
                                            <span>{{ $quotation->customer->kota }}</span>
                                        @endif
                                        @if ($quotation->customer->provinsi)
                                            <span>{{ $quotation->customer->provinsi }}</span>
                                        @endif
                                        @if ($quotation->customer->kode_pos)
                                            <span>{{ $quotation->customer->kode_pos }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        Alamat tidak tersedia
                                    </div>
                                @endif
                            </dd>
                        </div>

                        @if ($quotation->customer && $quotation->customer->npwp)
                            <div class="grid grid-cols-3 gap-2 py-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NPWP</dt>
                                <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                    {{ $quotation->customer->npwp }}
                                </dd>
                            </div>
                        @endif

                        @if ($quotation->customer && $quotation->customer->kontak_person)
                            <div class="grid grid-cols-3 gap-2 py-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak Person</dt>
                                <dd class="col-span-2">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $quotation->customer->kontak_person }}

                                    </div>
                                    @if ($quotation->customer->no_hp_kontak || $quotation->customer->pic_email)
                                        <div class="mt-1 space-y-1">

                                            @if ($quotation->customer->no_hp_kontak)
                                                <div
                                                    class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $quotation->customer->no_hp_kontak }}
                                                </div>
                                            @endif
                                            @if ($quotation->customer->email)
                                                <div
                                                    class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $quotation->customer->email }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </dd>
                            </div>
                        @endif

                        @if ($quotation->alamat_pengiriman)
                            <div class="grid grid-cols-3 gap-2 py-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pengiriman
                                </dt>
                                <dd class="col-span-2">
                                    <div class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                        {{ $quotation->alamat_pengiriman }}
                                    </div>
                                </dd>
                            </div>
                        @endif


                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $quotation->user->name ?? 'User tidak ditemukan' }}</dd>
                        </div>

                        @if ($quotation->catatan)
                            <div class="grid grid-cols-3 gap-2 py-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                    {{ $quotation->catatan }}</dd>
                            </div>
                        @endif

                        @if ($quotation->syarat_ketentuan)
                            <div class="grid grid-cols-3 gap-2 py-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Syarat & Ketentuan
                                </dt>
                                <dd class="col-span-2 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                    {{ $quotation->syarat_ketentuan }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column - Items --}}
        <div class="lg:col-span-2">
            <!-- Items -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Item</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Item
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Deskripsi
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Qty
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Diskon
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($quotation->details as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $detail->produk->kode ?? '-' }}</div>
                                        <div>{{ $detail->produk->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detail->deskripsi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detail->satuan->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($detail->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if ($detail->diskon_persen > 0)
                                            <span
                                                class="text-green-600 dark:text-green-400">{{ $detail->diskon_persen }}%</span>
                                            <span
                                                class="block text-xs">({{ number_format($detail->diskon_nominal, 0, ',', '.') }})</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada detail item
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 font-medium text-sm text-gray-900 dark:text-white text-right">
                                    Subtotal:</td>
                                <td colspan="2" class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ number_format($quotation->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if ($quotation->diskon_nominal > 0)
                                <tr>
                                    <td colspan="6"
                                        class="px-6 py-4 font-medium text-sm text-gray-900 dark:text-white text-right">
                                        Diskon ({{ $quotation->diskon_persen }}%):</td>
                                    <td colspan="2"
                                        class="px-6 py-4 text-sm font-medium text-green-600 dark:text-green-400">
                                        - {{ number_format($quotation->diskon_nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($quotation->ppn > 0)
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-4 font-medium text-sm text-gray-900 dark:text-white text-right">
                                        PPN ({{ $quotation->ppn }}%):</td>
                                    <td colspan="2" class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ number_format(($quotation->subtotal - $quotation->diskon_nominal) * ($quotation->ppn / 100), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 font-medium text-lg text-gray-900 dark:text-white text-right">
                                    Total:</td>
                                <td colspan="2" class="px-6 py-4 text-lg font-bold text-gray-900 dark:text-white">
                                    {{ number_format($quotation->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($quotation->salesOrders && $quotation->salesOrders->count() > 0)
                <!-- Related Sales Orders -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Sales Order Terkait</h2>
                    </div>
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Nomor
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($quotation->salesOrders as $salesOrder)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $salesOrder->nomor }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $statusClass = [
                                                        'draft' =>
                                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                        'diproses' =>
                                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                        'dikirim' =>
                                                            'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                                        'selesai' =>
                                                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                        'dibatalkan' =>
                                                            'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                    ];
                                                    $class = $statusClass[$salesOrder->status] ?? $statusClass['draft'];
                                                @endphp
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                                    {{ ucfirst($salesOrder->status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                                Rp {{ number_format($salesOrder->total, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                                <a href="#"
                                                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Log Aktivitas -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Log Aktivitas</h2>
                </div>
                <div class="p-5">
                    @if ($quotation->logAktivitas && $quotation->logAktivitas->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach ($quotation->logAktivitas as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span
                                                    class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex items-start space-x-3">
                                                <div class="relative">
                                                    @php
                                                        $iconClass = 'bg-gray-400';
                                                        $iconSvg =
                                                            '<svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>';

                                                        if ($log->aktivitas == 'create') {
                                                            $iconClass = 'bg-green-500';
                                                            $iconSvg =
                                                                '<svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>';
                                                        } elseif ($log->aktivitas == 'update') {
                                                            $iconClass = 'bg-blue-500';
                                                            $iconSvg =
                                                                '<svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>';
                                                        } elseif ($log->aktivitas == 'delete') {
                                                            $iconClass = 'bg-red-500';
                                                            $iconSvg =
                                                                '<svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>';
                                                        } elseif ($log->aktivitas == 'change_status') {
                                                            $iconClass = 'bg-purple-500';
                                                            $iconSvg =
                                                                '<svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="h-10 w-10 rounded-full flex items-center justify-center {{ $iconClass }}">
                                                        {!! $iconSvg !!}
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div>
                                                        <div class="text-sm">
                                                            <span class="font-medium text-gray-900 dark:text-white">
                                                                {{ $log->user->name ?? 'User tidak diketahui' }}
                                                            </span>
                                                            @php
                                                                $aktifitasText = '';
                                                                switch ($log->aktivitas) {
                                                                    case 'create':
                                                                        $aktifitasText = 'membuat';
                                                                        break;
                                                                    case 'update':
                                                                        $aktifitasText = 'mengubah';
                                                                        break;
                                                                    case 'delete':
                                                                        $aktifitasText = 'menghapus';
                                                                        break;
                                                                    case 'change_status':
                                                                        $aktifitasText = 'mengubah status';
                                                                        break;
                                                                    default:
                                                                        $aktifitasText = $log->aktivitas;
                                                                }
                                                            @endphp
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400">{{ $aktifitasText }}
                                                                quotation</span>
                                                        </div>
                                                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $log->created_at->format('d M Y, H:i') }}
                                                            <span class="text-gray-400 dark:text-gray-600">·</span>
                                                            IP: {{ $log->ip_address }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                        @if ($log->aktivitas == 'create')
                                                            @php $detail = json_decode($log->detail); @endphp
                                                            Membuat quotation baru nomor <span
                                                                class="font-medium">{{ $detail->nomor }}</span>
                                                            untuk customer <span
                                                                class="font-medium">{{ $detail->customer }}</span>
                                                            dengan total <span class="font-medium">Rp.
                                                                {{ number_format($detail->total, 0, ',', '.') }}</span>
                                                        @elseif($log->aktivitas == 'update')
                                                            @php
                                                                $detail = json_decode($log->detail);
                                                                $hasChanges = false;
                                                            @endphp
                                                            Mengubah data quotation
                                                            @if ($detail->before->nomor != $detail->after->nomor)
                                                                @php $hasChanges = true; @endphp
                                                                dari nomor
                                                                <span
                                                                    class="font-medium">{{ $detail->before->nomor }}</span>
                                                                menjadi <span
                                                                    class="font-medium">{{ $detail->after->nomor }}</span>
                                                            @endif
                                                            @if ($detail->before->customer != $detail->after->customer)
                                                                @php $hasChanges = true; @endphp
                                                                {{ $detail->before->nomor != $detail->after->nomor ? ',' : '' }}
                                                                customer dari <span
                                                                    class="font-medium">{{ $detail->before->customer }}</span>
                                                                menjadi <span
                                                                    class="font-medium">{{ $detail->after->customer }}</span>
                                                            @endif
                                                            @if ($detail->before->total != $detail->after->total)
                                                                @php $hasChanges = true; @endphp
                                                                {{ $detail->before->nomor != $detail->after->nomor || $detail->before->customer != $detail->after->customer ? ',' : '' }}
                                                                total dari <span class="font-medium">Rp.
                                                                    {{ number_format($detail->before->total, 0, ',', '.') }}</span>
                                                                menjadi <span class="font-medium">Rp.
                                                                    {{ number_format($detail->after->total, 0, ',', '.') }}</span>
                                                            @endif
                                                            @if (!$hasChanges)
                                                                dengan nomor <span
                                                                    class="font-medium">{{ $detail->after->nomor }}</span>
                                                            @endif
                                                        @elseif($log->aktivitas == 'delete')
                                                            @php $detail = json_decode($log->detail); @endphp
                                                            Menghapus quotation nomor <span
                                                                class="font-medium">{{ $detail->nomor }}</span>
                                                            untuk customer <span
                                                                class="font-medium">{{ $detail->customer }}</span>
                                                            dengan total <span class="font-medium">Rp.
                                                                {{ number_format($detail->total, 0, ',', '.') }}</span>
                                                        @elseif($log->aktivitas == 'change_status')
                                                            @php $detail = json_decode($log->detail); @endphp
                                                            Mengubah status quotation nomor <span
                                                                class="font-medium">{{ $detail->nomor }}</span>
                                                            dari <span
                                                                class="font-medium capitalize">{{ $detail->status_lama }}</span>
                                                            menjadi <span
                                                                class="font-medium capitalize">{{ $detail->status_baru }}</span>
                                                            @if ($detail->catatan != '-')
                                                                <p class="mt-1 text-xs italic">Catatan:
                                                                    "{{ $detail->catatan }}"</p>
                                                            @endif
                                                        @else
                                                            {{ $log->detail }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Belum ada log aktivitas untuk quotation ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }

            .status-history-line {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 19px;
                width: 2px;
                background-color: #e5e7eb;
            }

            .dark .status-history-line {
                background-color: #374151;
            }

            @media print {
                body * {
                    visibility: hidden;
                }

                #section-to-print,
                #section-to-print * {
                    visibility: visible;
                }

                #section-to-print {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                }
            }
        </style>
    @endpush
</x-app-layout>

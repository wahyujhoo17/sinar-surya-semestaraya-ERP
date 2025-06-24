<x-app-layout :breadcrumbs="[['label' => 'CRM', 'url' => route('crm.prospek.index')], ['label' => 'Pipeline Penjualan']]" :currentPage="'Pipeline Penjualan'">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .slide-in-right {
            animation: slideInRight 0.4s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .pipeline-stage {
            min-height: 400px;
            transition: all 0.2s ease-in-out;
        }

        .pipeline-card {
            transition: all 0.3s ease;
            cursor: grab;
        }

        .pipeline-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .pipeline-card.dragging {
            opacity: 0.5;
            transform: scale(0.95);
            cursor: grabbing;
        }

        .pipeline-stage.drag-over {
            background-color: rgba(79, 70, 229, 0.1);
            border: 2px dashed #4f46e5;
            transform: scale(1.02);
        }

        .pipeline-stage.potential-drop-target {
            background-color: rgba(79, 70, 229, 0.05);
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .pipeline-card {
                font-size: 0.875rem;
            }

            .pipeline-stage {
                min-height: 300px;
            }

            .conversion-rate-mobile {
                font-size: 0.75rem;
            }

            /* Make the Kanban board scrollable horizontally on mobile */
            .kanban-container {
                overflow-x: auto;
                padding-bottom: 1rem;
                /* Add space for the scrollbar */
                -webkit-overflow-scrolling: touch;
                /* Smooth scrolling on iOS */
            }

            .kanban-container::-webkit-scrollbar {
                height: 4px;
            }

            .kanban-container::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
                border-radius: 2px;
            }

            .kanban-container::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.2);
                border-radius: 2px;
            }

            /* Adjust pipeline stages on mobile */
            .mobile-pipeline-stages {
                display: flex;
                flex-wrap: nowrap;
                width: max-content;
                min-width: 100%;
            }

            .mobile-pipeline-stages .pipeline-stage {
                min-width: 280px;
                margin-right: 1rem;
            }
        }
    </style>

    <div x-data="pipelineManager()" x-init="init()" class="space-y-6">
        <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
            <div
                class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h1
                                class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Pipeline Penjualan
                            </h1>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Visualisasi dan pengelolaan tahapan proses penjualan dari prospek hingga menjadi pelanggan.
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex-shrink-0 flex">
                        <a href="{{ route('crm.prospek.create') }}"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Prospek Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div x-data="{ filterPanelOpen: true }" class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center mb-2 sm:mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Pipeline
                    </h2>

                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-600 italic dark:text-gray-400 sm:inline-block">
                            <span x-show="search || timeFrame" x-cloak>Filter aktif</span>
                            <span x-show="!search && !timeFrame">Tidak ada filter</span>
                        </span>
                        <button @click="filterPanelOpen = !filterPanelOpen" type="button"
                            class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                            <span x-text="filterPanelOpen ? 'Sembunyikan' : 'Tampilkan'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 ml-1 transition-transform duration-200"
                                :class="filterPanelOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="filterPanelOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 overflow-hidden">
                    <form @submit.prevent="applyFilters" class="space-y-3">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                            <!-- Search -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label
                                        class="text-xs font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
                                    <span x-show="search"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                        Aktif
                                    </span>
                                </div>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" x-model="search" x-on:input="searchDebounced()"
                                        placeholder="Cari nama prospek atau perusahaan..."
                                        class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <div x-show="isSearching" x-cloak class="absolute right-2 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-4 w-4 text-primary-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Frame -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Periode
                                        Waktu</label>
                                    <span x-show="timeFrame"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                        Aktif
                                    </span>
                                </div>
                                <div class="relative mt-1">
                                    <select x-model="timeFrame"
                                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="">Semua Waktu</option>
                                        <option value="today">Hari Ini</option>
                                        <option value="week">Minggu Ini</option>
                                        <option value="month">Bulan Ini</option>
                                        <option value="quarter">Kuartal Ini</option>
                                        <option value="year">Tahun Ini</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Terapkan Filter
                                </button>
                                <button type="button" @click="resetFilters"
                                    class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pipeline Stats -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Prospek</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Prospek Baru</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.baru">0</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900/30 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Negosiasi</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.negosiasi">0
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Converted</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white"
                                x-text="stats.menjadi_customer">0</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 dark:bg-red-900/30 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tertolak</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.menolak">0
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Kanban Board -->
            <div
                class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2 md:mb-0">Pipeline Penjualan</h2>

                    <!-- Export Actions -->
                    <div class="flex flex-wrap gap-2">
                        <button @click="exportData('excel')" type="button"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1.5 text-green-600 dark:text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Excel
                        </button>
                        <button @click="exportData('csv')" type="button"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1.5 text-blue-600 dark:text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>

                <!-- Pipeline Progress Bar -->
                <div class="mb-6 bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conversion Funnel Progress
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">All percentages show proportion of total
                        prospects that reached each stage</p>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 mr-4">
                            <div class="bg-primary-600 h-2.5 rounded-full"
                                :style="`width: ${conversionRates.overall}%`"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap"
                            x-text="`${conversionRates.overall}%`">0%</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                        <div class="bg-white dark:bg-gray-800 rounded p-2 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs text-gray-500 dark:text-gray-400">Tertarik (% of Total)</h4>
                            <div class="flex items-center mt-1">
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mr-2">
                                    <div class="bg-blue-500 h-1.5 rounded-full"
                                        :style="`width: ${conversionRates.prospekToTertarik}%`"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="`${conversionRates.prospekToTertarik}%`">0%</span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-2 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs text-gray-500 dark:text-gray-400">Negosiasi (% of Total)</h4>
                            <div class="flex items-center mt-1">
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mr-2">
                                    <div class="bg-indigo-500 h-1.5 rounded-full"
                                        :style="`width: ${conversionRates.tertarikToNegosiasi}%`"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="`${conversionRates.tertarikToNegosiasi}%`">0%</span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-2 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs text-gray-500 dark:text-gray-400">Customer (% of Total)</h4>
                            <div class="flex items-center mt-1">
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mr-2">
                                    <div class="bg-green-500 h-1.5 rounded-full"
                                        :style="`width: ${conversionRates.negosiasiToCustomer}%`"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="`${conversionRates.negosiasiToCustomer}%`">0%</span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-2 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs text-gray-500 dark:text-gray-400">Overall Conversion Rate</h4>
                            <div class="flex items-center mt-1">
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mr-2">
                                    <div class="bg-green-600 h-1.5 rounded-full"
                                        :style="`width: ${conversionRates.overall}%`"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="`${conversionRates.overall}%`">0%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanban Board -->
                <div class="kanban-container overflow-x-auto pb-4">
                    <div class="mobile-pipeline-stages grid grid-cols-1 lg:grid-cols-5 gap-4 min-w-full lg:min-w-0">
                        <!-- Baru -->
                        <div class="pipeline-stage bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3" data-status="baru"
                            @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                            @dragleave="$event.currentTarget.classList.remove('drag-over')"
                            @drop.prevent="handleDrop($event, 'baru')"
                            @dragend="$event.currentTarget.classList.remove('drag-over')">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                    Baru
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.baru.length">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(prospek, index) in pipelineData.baru" :key="index">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700"
                                        draggable="true" @dragstart="handleDragStart($event, prospek)"
                                        @dragend="$event.currentTarget.classList.remove('dragging')">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="prospek.nama_prospek"></h4>
                                            <span class="text-xs px-1.5 py-0.5"
                                                :class="`bg-${getStatusColor(prospek.status)}-100 text-${getStatusColor(prospek.status)}-800 dark:bg-${getStatusColor(prospek.status)}-900/30 dark:text-${getStatusColor(prospek.status)}-300 rounded-full`"
                                                x-text="getStatusLabel(prospek.status)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="prospek.perusahaan || 'Individu'"></p>
                                        <!-- Sales Penanggung Jawab -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="prospek.user">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="prospek.user ? prospek.user.name : 'Tidak Ada'"></span>
                                        </div>
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(prospek.tanggal_kontak)"></span>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <a :href="'{{ route('crm.prospek.show', ':prospek') }}'.replace(':prospek', prospek
                                                .id)"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="pipelineData.baru.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada prospek
                                </div>
                            </div>
                        </div>

                        <!-- Tertarik -->
                        <div class="pipeline-stage bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="tertarik" @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                            @dragleave="$event.currentTarget.classList.remove('drag-over')"
                            @drop.prevent="handleDrop($event, 'tertarik')"
                            @dragend="$event.currentTarget.classList.remove('drag-over')">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                    Tertarik
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.tertarik.length">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(prospek, index) in pipelineData.tertarik" :key="index">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700"
                                        draggable="true" @dragstart="handleDragStart($event, prospek)"
                                        @dragend="$event.currentTarget.classList.remove('dragging')">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="prospek.nama_prospek"></h4>
                                            <span class="text-xs px-1.5 py-0.5"
                                                :class="`bg-${getStatusColor(prospek.status)}-100 text-${getStatusColor(prospek.status)}-800 dark:bg-${getStatusColor(prospek.status)}-900/30 dark:text-${getStatusColor(prospek.status)}-300 rounded-full`"
                                                x-text="getStatusLabel(prospek.status)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="prospek.perusahaan || 'Individu'"></p>
                                        <!-- Sales Penanggung Jawab -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="prospek.user">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="prospek.user ? prospek.user.name : 'Tidak Ada'"></span>
                                        </div>
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(prospek.tanggal_kontak)"></span>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <a :href="'{{ route('crm.prospek.show', ':prospek') }}'.replace(':prospek', prospek
                                                .id)"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="pipelineData.tertarik.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada prospek
                                </div>
                            </div>
                        </div>

                        <!-- Negosiasi -->
                        <div class="pipeline-stage bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="negosiasi"
                            @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                            @dragleave="$event.currentTarget.classList.remove('drag-over')"
                            @drop.prevent="handleDrop($event, 'negosiasi')"
                            @dragend="$event.currentTarget.classList.remove('drag-over')">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                                    Negosiasi
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.negosiasi.length">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(prospek, index) in pipelineData.negosiasi" :key="index">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700"
                                        draggable="true" @dragstart="handleDragStart($event, prospek)"
                                        @dragend="$event.currentTarget.classList.remove('dragging')">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="prospek.nama_prospek"></h4>
                                            <span class="text-xs px-1.5 py-0.5"
                                                :class="`bg-${getStatusColor(prospek.status)}-100 text-${getStatusColor(prospek.status)}-800 dark:bg-${getStatusColor(prospek.status)}-900/30 dark:text-${getStatusColor(prospek.status)}-300 rounded-full`"
                                                x-text="getStatusLabel(prospek.status)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="prospek.perusahaan || 'Individu'"></p>
                                        <!-- Sales Penanggung Jawab -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="prospek.user">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="prospek.user ? prospek.user.name : 'Tidak Ada'"></span>
                                        </div>
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(prospek.tanggal_kontak)"></span>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <a :href="'{{ route('crm.prospek.show', ':prospek') }}'.replace(':prospek', prospek
                                                .id)"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="pipelineData.negosiasi.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada prospek
                                </div>
                            </div>
                        </div>

                        <!-- Menolak -->
                        <div class="pipeline-stage bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="menolak" @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                            @dragleave="$event.currentTarget.classList.remove('drag-over')"
                            @drop.prevent="handleDrop($event, 'menolak')"
                            @dragend="$event.currentTarget.classList.remove('drag-over')">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                    Menolak
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.menolak.length">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(prospek, index) in pipelineData.menolak" :key="index">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700"
                                        draggable="true" @dragstart="handleDragStart($event, prospek)"
                                        @dragend="$event.currentTarget.classList.remove('dragging')">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="prospek.nama_prospek"></h4>
                                            <span class="text-xs px-1.5 py-0.5"
                                                :class="`bg-${getStatusColor(prospek.status)}-100 text-${getStatusColor(prospek.status)}-800 dark:bg-${getStatusColor(prospek.status)}-900/30 dark:text-${getStatusColor(prospek.status)}-300 rounded-full`"
                                                x-text="getStatusLabel(prospek.status)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="prospek.perusahaan || 'Individu'"></p>
                                        <!-- Sales Penanggung Jawab -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="prospek.user">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="prospek.user ? prospek.user.name : 'Tidak Ada'"></span>
                                        </div>
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(prospek.tanggal_kontak)"></span>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <a :href="'{{ route('crm.prospek.show', ':prospek') }}'.replace(':prospek', prospek
                                                .id)"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="pipelineData.menolak.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada prospek
                                </div>
                            </div>
                        </div>

                        <!-- Menjadi Customer -->
                        <div class="pipeline-stage bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="menjadi_customer"
                            @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                            @dragleave="$event.currentTarget.classList.remove('drag-over')"
                            @drop.prevent="handleDrop($event, 'menjadi_customer')"
                            @dragend="$event.currentTarget.classList.remove('drag-over')">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Menjadi Customer
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.menjadi_customer.length">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(prospek, index) in pipelineData.menjadi_customer"
                                    :key="index">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700"
                                        draggable="true" @dragstart="handleDragStart($event, prospek)"
                                        @dragend="$event.currentTarget.classList.remove('dragging')">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="prospek.nama_prospek"></h4>
                                            <span class="text-xs px-1.5 py-0.5"
                                                :class="`bg-${getStatusColor(prospek.status)}-100 text-${getStatusColor(prospek.status)}-800 dark:bg-${getStatusColor(prospek.status)}-900/30 dark:text-${getStatusColor(prospek.status)}-300 rounded-full`"
                                                x-text="getStatusLabel(prospek.status)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="prospek.perusahaan || 'Individu'"></p>
                                        <!-- Sales Penanggung Jawab -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="prospek.user">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="prospek.user ? prospek.user.name : 'Tidak Ada'"></span>
                                        </div>
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(prospek.tanggal_kontak)"></span>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <a :href="'{{ route('crm.prospek.show', ':prospek') }}'.replace(':prospek', prospek
                                                .id)"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="pipelineData.menjadi_customer.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada prospek
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Performa Pipeline</h2>

                    <div class="flex flex-wrap justify-end items-center mb-4">
                        <div class="flex mt-2 sm:mt-0 space-x-2">
                            <div class="dropdown relative">
                                <button @click="showExportDropdown = !showExportDropdown" type="button"
                                    class="text-sm px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="showExportDropdown" x-cloak @click.away="showExportDropdown = false"
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    <div class="py-1">
                                        <button @click="exportData('excel'); showExportDropdown = false"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-green-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Export to Excel
                                            </div>
                                        </button>
                                        <button @click="exportData('csv'); showExportDropdown = false"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-blue-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Export to CSV
                                            </div>
                                        </button>
                                        <button @click="downloadChart(); showExportDropdown = false"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-purple-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Download Chart as PNG
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <select
                                class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:ring-primary-500 dark:focus:ring-primary-500"
                                x-model="chartType" @change="switchChartType()">
                                <option value="bar">Bar Chart</option>
                                <option value="doughnut">Doughnut Chart</option>
                                <option value="line">Line Chart</option>
                            </select>
                        </div>
                    </div>

                    <!-- Conversion Rate Indicators -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
                            <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Overall Conversion
                                Rate</h3>
                            <div class="flex justify-between items-center">
                                <div
                                    class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-500 to-green-500"
                                        :style="`width: ${conversionRates.overall}%`"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="`${conversionRates.overall}%`">0%</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
                            <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Tertarik (% of Total)
                            </h3>
                            <div class="flex justify-between items-center">
                                <div
                                    class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div class="absolute top-0 left-0 h-full bg-yellow-500"
                                        :style="`width: ${conversionRates.prospekToTertarik}%`"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="`${conversionRates.prospekToTertarik}%`">0%</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
                            <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Negosiasi (% of
                                Total)</h3>
                            <div class="flex justify-between items-center">
                                <div
                                    class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div class="absolute top-0 left-0 h-full bg-blue-500"
                                        :style="`width: ${conversionRates.tertarikToNegosiasi}%`"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="`${conversionRates.tertarikToNegosiasi}%`">0%</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
                            <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Customer (% of Total)
                            </h3>
                            <div class="flex justify-between items-center">
                                <div
                                    class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div class="absolute top-0 left-0 h-full bg-indigo-500"
                                        :style="`width: ${conversionRates.negosiasiToCustomer}%`"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="`${conversionRates.negosiasiToCustomer}%`">0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pipeline Progress Flow -->
                    <div class="mb-6 relative hidden md:block">
                        <div class="absolute top-4 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700"></div>
                        <div class="flex justify-between">
                            <!-- Baru Stage -->
                            <div class="relative text-center w-1/4">
                                <div
                                    class="w-8 h-8 mx-auto bg-yellow-500 rounded-full text-white flex items-center justify-center z-10 relative">
                                    <span class="text-xs font-bold" x-text="stats.baru"></span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Baru</span>
                                </div>
                            </div>

                            <!-- Tertarik Stage -->
                            <div class="relative text-center w-1/4">
                                <div
                                    class="w-8 h-8 mx-auto bg-blue-500 rounded-full text-white flex items-center justify-center z-10 relative">
                                    <span class="text-xs font-bold" x-text="stats.tertarik"></span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Tertarik</span>
                                    <span class="block text-xs text-blue-600 dark:text-blue-400"
                                        x-text="`${conversionRates.prospekToTertarik}% of total`"></span>
                                </div>
                            </div>

                            <!-- Negosiasi Stage -->
                            <div class="relative text-center w-1/4">
                                <div
                                    class="w-8 h-8 mx-auto bg-indigo-500 rounded-full text-white flex items-center justify-center z-10 relative">
                                    <span class="text-xs font-bold" x-text="stats.negosiasi"></span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Negosiasi</span>
                                    <span class="block text-xs text-indigo-600 dark:text-indigo-400"
                                        x-text="`${conversionRates.tertarikToNegosiasi}% of total`"></span>
                                </div>
                            </div>

                            <!-- Customer Stage -->
                            <div class="relative text-center w-1/4">
                                <div
                                    class="w-8 h-8 mx-auto bg-green-500 rounded-full text-white flex items-center justify-center z-10 relative">
                                    <span class="text-xs font-bold" x-text="stats.menjadi_customer"></span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Customer</span>
                                    <span class="block text-xs text-green-600 dark:text-green-400"
                                        x-text="`${conversionRates.negosiasiToCustomer}% of total`"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Canvas -->
                    <div class="h-60 w-full">
                        <canvas id="pipelineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function pipelineManager() {
                return {
                    search: '',
                    timeFrame: '',
                    chartType: 'bar',
                    isSearching: false,
                    searchTimeout: null,
                    showExportDropdown: false,
                    stats: {
                        total: 0,
                        baru: 0,
                        tertarik: 0,
                        negosiasi: 0,
                        menolak: 0,
                        menjadi_customer: 0
                    },
                    conversionRates: {
                        prospekToTertarik: 0,
                        tertarikToNegosiasi: 0,
                        negosiasiToCustomer: 0,
                        overall: 0
                    },
                    pipelineData: {
                        baru: [],
                        tertarik: [],
                        negosiasi: [],
                        menolak: [],
                        menjadi_customer: []
                    },
                    progressData: {
                        baruToTertarik: {
                            count: 0,
                            rate: 0
                        },
                        tertarikToNegosiasi: {
                            count: 0,
                            rate: 0
                        },
                        negosiasiToCustomer: {
                            count: 0,
                            rate: 0
                        }
                    },
                    pipelineChart: null,
                    init() {
                        // Reset chart state and clean up completely
                        if (this.pipelineChart) {
                            try {
                                this.pipelineChart.destroy();
                                this.pipelineChart = null;
                            } catch (e) {
                                console.warn('Error destroying chart during init:', e);
                                this.pipelineChart = null;
                            }
                        }

                        // Destroy ALL Chart instances to be absolutely sure
                        try {
                            // This is a safety measure - gets all charts and destroys them
                            const existingChartInstances = Object.values(Chart.instances || {});
                            if (existingChartInstances && existingChartInstances.length > 0) {
                                // console.log(
                                // `Cleaning up ${existingChartInstances.length} existing chart instances during init`);
                                existingChartInstances.forEach(chart => {
                                    try {
                                        if (chart && typeof chart.destroy === 'function') {
                                            chart.destroy();
                                        }
                                    } catch (e) {
                                        console.warn('Error destroying chart instance:', e);
                                    }
                                });
                            }
                        } catch (e) {
                            console.warn('Error checking for Chart instances:', e);
                        }

                        // Reset the canvas if it exists
                        const canvas = document.getElementById('pipelineChart');
                        if (canvas) {
                            // Create a new canvas element to replace the old one
                            const canvasParent = canvas.parentNode;
                            if (canvasParent) {
                                // Save original canvas attributes
                                const canvasId = canvas.id;
                                const canvasWidth = canvas.width;
                                const canvasHeight = canvas.height;
                                const canvasClasses = canvas.className;
                                const canvasStyle = canvas.style.cssText;

                                // Remove old canvas
                                canvasParent.removeChild(canvas);

                                // Create a new canvas with same attributes
                                const newCanvas = document.createElement('canvas');
                                newCanvas.id = canvasId;
                                newCanvas.width = canvasWidth;
                                newCanvas.height = canvasHeight;
                                newCanvas.className = canvasClasses;
                                newCanvas.style.cssText = canvasStyle;

                                // Insert the new canvas
                                canvasParent.appendChild(newCanvas);
                            }
                        }

                        // Fetch data first - we'll initialize the chart in the callback
                        setTimeout(() => {
                            this.fetchData();
                        }, 150); // Small delay to ensure everything is cleaned up
                    },

                    searchDebounced() {
                        // Clear any previous timeout
                        if (this.searchTimeout) {
                            clearTimeout(this.searchTimeout);
                        }

                        // Show loading indicator
                        this.isSearching = true;

                        // Set a new timeout
                        this.searchTimeout = setTimeout(() => {
                            this.fetchData();
                        }, 500); // 500ms debounce
                    },

                    calculateConversionRates() {
                        // Calculate conversion rates between stages
                        const {
                            baru,
                            tertarik,
                            negosiasi,
                            menjadi_customer
                        } = this.stats;

                        // Calculate total number of prospects
                        const total = this.stats.total || baru + tertarik + negosiasi + menjadi_customer;

                        // Calculate as percentage of total prospects instead of stage-to-stage conversion
                        // This shows what percentage of all prospects have reached each stage
                        this.conversionRates.prospekToTertarik = total > 0 ? Math.min(100, parseInt((tertarik / total) * 100)) :
                            0;
                        this.conversionRates.tertarikToNegosiasi = total > 0 ? Math.min(100, parseInt((negosiasi / total) *
                            100)) : 0;
                        this.conversionRates.negosiasiToCustomer = total > 0 ? Math.min(100, parseInt((menjadi_customer /
                            total) * 100)) : 0;
                        this.conversionRates.overall = total > 0 ? Math.min(100, parseInt((menjadi_customer / total) * 100)) :
                            0;

                        // Calculate progress data
                        this.progressData = {
                            baruToTertarik: {
                                count: tertarik,
                                rate: this.conversionRates.prospekToTertarik
                            },
                            tertarikToNegosiasi: {
                                count: negosiasi,
                                rate: this.conversionRates.tertarikToNegosiasi
                            },
                            negosiasiToCustomer: {
                                count: menjadi_customer,
                                rate: this.conversionRates.negosiasiToCustomer
                            }
                        };
                    },

                    fetchData() {
                        // Show loading state
                        this.pipelineData = {
                            baru: [],
                            tertarik: [],
                            negosiasi: [],
                            menolak: [],
                            menjadi_customer: []
                        };

                        this.stats = {
                            total: 0,
                            baru: 0,
                            tertarik: 0,
                            negosiasi: 0,
                            menolak: 0,
                            menjadi_customer: 0
                        };

                        // Build query parameters
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.timeFrame) params.append('time_frame', this.timeFrame);

                        // Add cache-busting parameter to ensure fresh data
                        params.append('_t', Date.now());

                        // Fetch data from the API
                        const apiUrl = `{{ route('crm.pipeline.data') }}?${params.toString()}`;

                        fetch(apiUrl)
                            .then(response => {
                                // console.log('Response status:', response.status);
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(response => {
                                // console.log('API Response:', response);

                                if (response.success) {
                                    // Use real data from database
                                    this.pipelineData = response.data;
                                    this.stats = response.stats;
                                } else {
                                    // Handle API success=false
                                    throw new Error("API returned success=false");
                                }

                                // Calculate conversion rates after data is fetched
                                this.calculateConversionRates();

                                // Initialize or update the chart
                                this.$nextTick(() => {
                                    if (!this.pipelineChart) {
                                        // Initialize chart if it doesn't exist
                                        setTimeout(() => {
                                            this.initChart();
                                        }, 100); // Slight delay to ensure DOM is ready
                                    } else {
                                        // Just update data if chart already exists
                                        this.updateChartData();
                                    }
                                });

                                // Hide search loading indicator
                                this.isSearching = false;
                            })
                            .catch(error => {
                                console.error('Error fetching pipeline data:', error);

                                // Show specific error details in the console
                                if (error.response) {
                                    console.error('Error response:', error.response);
                                }

                                // Do NOT fallback to dummy data unless absolutely necessary
                                // Only use dummy data for development purposes
                                let shouldUseDummyData = false;

                                // Check if we're in a development environment or if we have no database connection
                                if (error.message && (
                                        error.message.includes('Failed to fetch') ||
                                        error.message.includes('Network error') ||
                                        error.message.includes('database') ||
                                        error.message.includes('connection')
                                    )) {
                                    shouldUseDummyData = true;
                                }

                                if (shouldUseDummyData) {
                                    console.warn("Using dummy data as fallback");
                                    const dummyData = this.getDummyData();

                                    this.pipelineData = {
                                        baru: dummyData.filter(p => p.status === 'baru'),
                                        tertarik: dummyData.filter(p => p.status === 'tertarik'),
                                        negosiasi: dummyData.filter(p => p.status === 'negosiasi'),
                                        menolak: dummyData.filter(p => p.status === 'menolak'),
                                        menjadi_customer: dummyData.filter(p => p.status === 'menjadi_customer')
                                    };

                                    this.stats = {
                                        total: dummyData.length,
                                        baru: this.pipelineData.baru.length,
                                        tertarik: this.pipelineData.tertarik.length,
                                        negosiasi: this.pipelineData.negosiasi.length,
                                        menolak: this.pipelineData.menolak.length,
                                        menjadi_customer: this.pipelineData.menjadi_customer.length
                                    };
                                }

                                // Calculate conversion rates
                                this.calculateConversionRates();

                                // Initialize or update the chart
                                this.$nextTick(() => {
                                    if (!this.pipelineChart) {
                                        // Initialize chart if it doesn't exist
                                        setTimeout(() => {
                                            this.initChart();
                                        }, 100); // Slight delay to ensure DOM is ready
                                    } else {
                                        // Just update data if chart already exists
                                        this.updateChartData();
                                    }
                                });

                                // Hide search loading indicator
                                this.isSearching = false;

                                // Show error notification
                                let event = new CustomEvent('notify', {
                                    detail: {
                                        type: 'error',
                                        message: 'Gagal memuat data pipeline: ' + error.message
                                    }
                                });
                                window.dispatchEvent(event);
                            });
                    },
                    initChart() {
                        // Create a unique ID for this chart instance to avoid conflicts
                        const chartInstanceId = `pipelineChart_${Date.now()}`;

                        try {
                            // First, ensure any existing chart is properly destroyed
                            if (this.pipelineChart) {
                                try {
                                    this.pipelineChart.destroy();
                                    this.pipelineChart = null;
                                } catch (e) {
                                    console.warn('Error destroying existing chart:', e);
                                    this.pipelineChart = null;
                                }
                            }

                            // Find and destroy all existing Chart instances
                            try {
                                const existingChartInstances = Object.values(Chart.instances || {});
                                if (existingChartInstances && existingChartInstances.length > 0) {
                                    // console.log(`Cleaning up ${existingChartInstances.length} existing chart instances`);
                                    existingChartInstances.forEach(chart => {
                                        try {
                                            chart.destroy();
                                        } catch (e) {
                                            console.warn('Error destroying chart instance:', e);
                                        }
                                    });
                                }
                            } catch (e) {
                                console.warn('Error cleaning up Chart instances:', e);
                            }

                            // Check if the chart canvas exists
                            const canvas = document.getElementById('pipelineChart');
                            if (!canvas) {
                                console.error('Chart canvas element not found');
                                return;
                            }

                            // Ensure the canvas is clean by removing and recreating it
                            const canvasParent = canvas.parentNode;
                            if (canvasParent) {
                                // Save original canvas attributes
                                const canvasId = canvas.id;
                                const canvasWidth = canvas.width;
                                const canvasHeight = canvas.height;
                                const canvasClasses = canvas.className;
                                const canvasStyle = canvas.style.cssText;

                                // Remove old canvas
                                canvasParent.removeChild(canvas);

                                // Create a new canvas with same attributes
                                const newCanvas = document.createElement('canvas');
                                newCanvas.id = canvasId;
                                newCanvas.width = canvasWidth;
                                newCanvas.height = canvasHeight;
                                newCanvas.className = canvasClasses;
                                newCanvas.style.cssText = canvasStyle;

                                // Insert the new canvas
                                canvasParent.appendChild(newCanvas);

                                // Wait a short time to ensure canvas is ready
                                setTimeout(() => {
                                    try {
                                        // Get a fresh context from the new canvas
                                        const ctx = newCanvas.getContext('2d', {
                                            willReadFrequently: true
                                        });
                                        if (!ctx) {
                                            console.error('Failed to get canvas 2D context');
                                            return;
                                        }

                                        // Prepare chart data
                                        const colors = {
                                            backgroundColor: [
                                                'rgba(255, 193, 7, 0.6)', // Baru - yellow
                                                'rgba(13, 110, 253, 0.6)', // Tertarik - blue
                                                'rgba(102, 16, 242, 0.6)', // Negosiasi - indigo
                                                'rgba(220, 53, 69, 0.6)', // Menolak - red
                                                'rgba(25, 135, 84, 0.6)' // Menjadi Customer - green
                                            ],
                                            borderColor: [
                                                'rgba(255, 193, 7, 1)',
                                                'rgba(13, 110, 253, 1)',
                                                'rgba(102, 16, 242, 1)',
                                                'rgba(220, 53, 69, 1)',
                                                'rgba(25, 135, 84, 1)'
                                            ]
                                        };

                                        const labels = ['Baru', 'Tertarik', 'Negosiasi', 'Menolak', 'Menjadi Customer'];
                                        const data = [
                                            this.stats.baru || 0,
                                            this.stats.tertarik || 0,
                                            this.stats.negosiasi || 0,
                                            this.stats.menolak || 0,
                                            this.stats.menjadi_customer || 0
                                        ];

                                        // Configure chart options based on chart type
                                        const chartOptions = this.getChartOptions();

                                        // Create the chart with all required properties
                                        this.pipelineChart = new Chart(ctx, {
                                            type: this.chartType,
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Jumlah Prospek',
                                                    data: data,
                                                    backgroundColor: colors.backgroundColor,
                                                    borderColor: colors.borderColor,
                                                    borderWidth: 1,
                                                    // Add these properties to ensure complete dataset configuration
                                                    hoverBackgroundColor: colors.backgroundColor.map(
                                                        color => color.replace('0.6', '0.8')),
                                                    hoverBorderColor: colors.borderColor,
                                                    hoverBorderWidth: 2,
                                                    tension: this.chartType === 'line' ? 0.3 : 0
                                                }]
                                            },
                                            options: chartOptions
                                        });

                                        // console.log('Chart initialized successfully:', this.chartType);
                                    } catch (innerError) {
                                        console.error('Error creating chart:', innerError);
                                    }
                                }, 100);
                            } else {
                                console.error('Cannot find canvas parent element');
                            }
                        } catch (error) {
                            console.error('Error in initChart:', error);
                        }
                    },

                    getChartOptions() {
                        const baseOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 500
                            },
                            plugins: {
                                legend: {
                                    display: this.chartType === 'doughnut',
                                    position: 'right',
                                    labels: {
                                        font: {
                                            size: 12
                                        },
                                        usePointStyle: true,
                                        padding: 15
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                    padding: 10,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b,
                                                0) || 1;
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} prospek (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        };

                        // Add specific options for bar chart
                        if (this.chartType === 'bar') {
                            return {
                                ...baseOptions,
                                indexAxis: 'y', // Horizontal bar
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0 // Ensure whole numbers
                                        },
                                        grid: {
                                            display: true,
                                            color: 'rgba(0, 0, 0, 0.05)'
                                        }
                                    },
                                    y: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            };
                        }

                        // For line chart
                        if (this.chartType === 'line') {
                            return {
                                ...baseOptions,
                                elements: {
                                    line: {
                                        tension: 0.3
                                    },
                                    point: {
                                        radius: 4,
                                        hoverRadius: 6
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            };
                        }

                        // For doughnut chart
                        return {
                            ...baseOptions,
                            cutout: '60%',
                            radius: '90%'
                        };
                    },
                    switchChartType() {
                        // Save the chart type for recreation
                        const newType = this.chartType;

                        // First destroy any existing chart
                        if (this.pipelineChart) {
                            try {
                                this.pipelineChart.destroy();
                                this.pipelineChart = null;
                            } catch (e) {
                                console.warn('Error destroying chart during type switch:', e);
                                this.pipelineChart = null;
                            }
                        }

                        // Destroy ALL Chart instances to be safe
                        try {
                            // This is a safety measure - gets all charts and destroys them
                            const existingChartInstances = Object.values(Chart.instances || {});
                            if (existingChartInstances && existingChartInstances.length > 0) {
                                // console.log(
                                //     `Cleaning up ${existingChartInstances.length} existing chart instances before switching`
                                //     );
                                existingChartInstances.forEach(chart => {
                                    try {
                                        chart.destroy();
                                    } catch (e) {
                                        console.warn('Error destroying chart instance:', e);
                                    }
                                });
                            }
                        } catch (e) {
                            console.warn('Error cleaning up Chart instances:', e);
                        }

                        // Check if the chart canvas exists
                        const canvas = document.getElementById('pipelineChart');
                        if (!canvas) {
                            console.error('Chart canvas element not found during type switch');

                            // Try to wait and re-initialize
                            setTimeout(() => {
                                this.initChart();
                            }, 500);
                            return;
                        }

                        // Re-create the canvas element to ensure a fresh start
                        const canvasParent = canvas.parentNode;
                        if (canvasParent) {
                            // Save original canvas attributes
                            const canvasId = canvas.id;
                            const canvasWidth = canvas.width;
                            const canvasHeight = canvas.height;
                            const canvasClasses = canvas.className;
                            const canvasStyle = canvas.style.cssText;

                            // Remove old canvas
                            canvasParent.removeChild(canvas);

                            // Create a new canvas with same attributes
                            const newCanvas = document.createElement('canvas');
                            newCanvas.id = canvasId;
                            newCanvas.width = canvasWidth;
                            newCanvas.height = canvasHeight;
                            newCanvas.className = canvasClasses;
                            newCanvas.style.cssText = canvasStyle;

                            // Insert the new canvas
                            canvasParent.appendChild(newCanvas);
                        }

                        // Wait longer before recreating to ensure clean state
                        setTimeout(() => {
                            this.initChart();
                        }, 300);
                    },

                    downloadChart() {
                        if (!this.pipelineChart) {
                            // Dispatch error notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    message: 'Chart tidak tersedia untuk diunduh',
                                    duration: 3000
                                }
                            }));
                            return;
                        }

                        try {
                            // Get canvas element
                            const canvas = document.getElementById('pipelineChart');
                            if (!canvas) {
                                throw new Error('Canvas element not found');
                            }

                            // Make sure the canvas has content
                            if (canvas.width === 0 || canvas.height === 0) {
                                throw new Error('Canvas has zero width or height');
                            }

                            // Safety check for canvas context
                            const ctx = canvas.getContext('2d');
                            if (!ctx) {
                                throw new Error('Unable to get canvas context');
                            }

                            // Create a temporary link element
                            const link = document.createElement('a');

                            try {
                                // Try using toDataURL with a try-catch in case it fails
                                link.href = canvas.toDataURL('image/png', 1.0);
                                link.download = `pipeline-penjualan-${new Date().toISOString().split('T')[0]}.png`;

                                // Append to the document, trigger click, and remove
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);

                                // Show success notification
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: {
                                        type: 'success',
                                        message: 'Chart berhasil diunduh',
                                        duration: 3000
                                    }
                                }));
                            } catch (dataUrlError) {
                                console.error('Error creating data URL:', dataUrlError);

                                // Alternative approach if toDataURL fails
                                // Try to render the chart again before export
                                setTimeout(() => {
                                    try {
                                        // Force a chart update
                                        this.pipelineChart.update('none');

                                        // Try again after a slight delay
                                        setTimeout(() => {
                                            try {
                                                link.href = canvas.toDataURL('image/png', 1.0);
                                                link.download =
                                                    `pipeline-penjualan-${new Date().toISOString().split('T')[0]}.png`;
                                                document.body.appendChild(link);
                                                link.click();
                                                document.body.removeChild(link);

                                                window.dispatchEvent(new CustomEvent('notify', {
                                                    detail: {
                                                        type: 'success',
                                                        message: 'Chart berhasil diunduh',
                                                        duration: 3000
                                                    }
                                                }));
                                            } catch (retryError) {
                                                throw retryError;
                                            }
                                        }, 200);
                                    } catch (retryError) {
                                        throw retryError;
                                    }
                                }, 100);
                            }
                        } catch (error) {
                            console.error('Error downloading chart:', error);

                            // Dispatch error notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    message: 'Gagal mengunduh chart: ' + error.message,
                                    duration: 5000
                                }
                            }));
                        }
                    },

                    updateChartData() {
                        try {
                            if (!this.pipelineChart) {
                                console.warn('Cannot update chart data - chart instance does not exist');
                                // Initialize the chart instead
                                this.initChart();
                                return;
                            }

                            // Check if datasets array exists
                            if (!this.pipelineChart.data || !this.pipelineChart.data.datasets ||
                                !this.pipelineChart.data.datasets[0]) {
                                console.warn('Chart data structure is incomplete');
                                // Reinitialize the chart
                                this.pipelineChart.destroy();
                                this.pipelineChart = null;
                                this.initChart();
                                return;
                            }

                            // Safely update the data values without changing options
                            this.pipelineChart.data.datasets[0].data = [
                                this.stats.baru || 0,
                                this.stats.tertarik || 0,
                                this.stats.negosiasi || 0,
                                this.stats.menolak || 0,
                                this.stats.menjadi_customer || 0
                            ];

                            // Update the chart with minimal animation
                            this.pipelineChart.update('none');
                        } catch (error) {
                            console.error('Error updating chart data:', error);

                            // If updating fails, destroy and recreate the chart
                            try {
                                if (this.pipelineChart) {
                                    this.pipelineChart.destroy();
                                    this.pipelineChart = null;
                                }
                            } catch (e) {
                                console.warn('Error cleaning up chart after update failure:', e);
                                this.pipelineChart = null;
                            }

                            // Recreate after a short delay
                            setTimeout(() => {
                                this.initChart();
                            }, 200);
                        }
                    },

                    // This is the original updateChart that we'll keep as a backup but not use directly
                    updateChart() {
                        try {
                            // If chart doesn't exist, initialize it but don't call this function recursively
                            if (!this.pipelineChart) {
                                const existingChart = Chart.getChart('pipelineChart');
                                if (existingChart) {
                                    existingChart.destroy();
                                }
                                this.initChart();
                                return;
                            }

                            // Update data without recreating the entire chart
                            this.pipelineChart.data.datasets[0].data = [
                                this.stats.baru || 0,
                                this.stats.tertarik || 0,
                                this.stats.negosiasi || 0,
                                this.stats.menolak || 0,
                                this.stats.menjadi_customer || 0
                            ];

                            // Update chart options based on chart type
                            const newOptions = this.getChartOptions();

                            // Apply the updated options
                            Object.assign(this.pipelineChart.options, newOptions);

                            // Update the chart
                            this.pipelineChart.update('none'); // Use 'none' to disable animations during update
                        } catch (error) {
                            console.error('Error updating chart:', error);

                            // If updating fails, destroy the chart instance properly before recreating
                            if (this.pipelineChart) {
                                try {
                                    this.pipelineChart.destroy();
                                } catch (e) {
                                    console.warn('Error destroying chart:', e);
                                }
                                this.pipelineChart = null;
                            }

                            // Also check for any Chart instances that might be using our canvas
                            const existingChart = Chart.getChart('pipelineChart');
                            if (existingChart) {
                                try {
                                    existingChart.destroy();
                                } catch (e) {
                                    console.warn('Error destroying existing chart:', e);
                                }
                            }

                            // Recreate after a timeout to avoid immediate recreation
                            setTimeout(() => {
                                this.initChart();
                            }, 200);
                        }
                    },

                    applyFilters() {
                        // This would normally send a request to the server with the filters
                        // For now we'll just refresh the data
                        this.fetchData();
                    },

                    resetFilters() {
                        this.search = '';
                        this.timeFrame = '';
                        this.fetchData();
                    },

                    exportData(type) {
                        // Create the URL with query parameters (for filtering)
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.timeFrame) params.append('time_frame', this.timeFrame);

                        let route;
                        let message;

                        if (type === 'excel') {
                            route = "{{ route('crm.pipeline.export.excel') }}";
                            message = 'Mengekspor data ke Excel...';
                        } else if (type === 'csv') {
                            route = "{{ route('crm.pipeline.export.csv') }}";
                            message = 'Mengekspor data ke CSV...';
                        } else {
                            console.error("Invalid export type: " + type);
                            return;
                        }

                        // Show loading notification
                        const loadingNotification = new CustomEvent('notify', {
                            detail: {
                                type: 'info',
                                message: message
                            }
                        });
                        window.dispatchEvent(loadingNotification);

                        // Download the file directly by setting window.location
                        window.location.href = `${route}?${params.toString()}`;

                        // Show success notification (with a slight delay)
                        setTimeout(() => {
                            const successNotification = new CustomEvent('notify', {
                                detail: {
                                    type: 'success',
                                    message: `Data berhasil diekspor ke ${type.toUpperCase()}`
                                }
                            });
                            window.dispatchEvent(successNotification);
                        }, 1000);
                    },

                    formatDate(dateString) {
                        if (!dateString) return 'N/A';
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                    },

                    getDummyData() {
                        return [{
                                id: 1,
                                nama_prospek: 'PT Maju Jaya',
                                perusahaan: 'PT Maju Jaya',
                                status: 'baru',
                                tanggal_kontak: '2025-06-10',
                                catatan: 'Prospek baru dari website'
                            },
                            {
                                id: 2,
                                nama_prospek: 'CV Abadi Sentosa',
                                perusahaan: 'CV Abadi Sentosa',
                                status: 'baru',
                                tanggal_kontak: '2025-06-09',
                                catatan: 'Referral dari customer lama'
                            },
                            {
                                id: 3,
                                nama_prospek: 'PT Teknologi Prima',
                                perusahaan: 'PT Teknologi Prima',
                                status: 'tertarik',
                                tanggal_kontak: '2025-06-05',
                                catatan: 'Tertarik dengan produk A'
                            },
                            {
                                id: 4,
                                nama_prospek: 'PT Sinar Abadi',
                                perusahaan: 'PT Sinar Abadi',
                                status: 'tertarik',
                                tanggal_kontak: '2025-06-01',
                                catatan: 'Meminta penawaran harga'
                            },
                            {
                                id: 5,
                                nama_prospek: 'PT Makmur Sejahtera',
                                perusahaan: 'PT Makmur Sejahtera',
                                status: 'negosiasi',
                                tanggal_kontak: '2025-05-28',
                                catatan: 'Negosiasi harga produk B'
                            },
                            {
                                id: 6,
                                nama_prospek: 'CV Mitra Utama',
                                perusahaan: 'CV Mitra Utama',
                                status: 'negosiasi',
                                tanggal_kontak: '2025-05-25',
                                catatan: 'Negosiasi syarat pembayaran'
                            },
                            {
                                id: 7,
                                nama_prospek: 'PT Sejahtera Mandiri',
                                perusahaan: 'PT Sejahtera Mandiri',
                                status: 'menolak',
                                tanggal_kontak: '2025-05-20',
                                catatan: 'Harga terlalu tinggi'
                            },
                            {
                                id: 8,
                                nama_prospek: 'Ahmad Sulaiman',
                                perusahaan: null,
                                status: 'menolak',
                                tanggal_kontak: '2025-05-15',
                                catatan: 'Tidak sesuai kebutuhan'
                            },
                            {
                                id: 9,
                                nama_prospek: 'PT Maju Bersama',
                                perusahaan: 'PT Maju Bersama',
                                status: 'menjadi_customer',
                                tanggal_kontak: '2025-05-10',
                                catatan: 'Deal sudah ditandatangani'
                            },
                            {
                                id: 10,
                                nama_prospek: 'PT Sukses Makmur',
                                perusahaan: 'PT Sukses Makmur',
                                status: 'menjadi_customer',
                                tanggal_kontak: '2025-05-05',
                                catatan: 'Customer baru, pembelian pertama'
                            }
                        ];
                    },

                    getStatusLabel(status) {
                        const labels = {
                            'baru': 'Baru',
                            'tertarik': 'Tertarik',
                            'negosiasi': 'Negosiasi',
                            'menolak': 'Menolak',
                            'menjadi_customer': 'Customer'
                        };
                        return labels[status] || status;
                    },

                    getStatusColor(status) {
                        const colors = {
                            'baru': 'yellow',
                            'tertarik': 'blue',
                            'negosiasi': 'indigo',
                            'menolak': 'red',
                            'menjadi_customer': 'green'
                        };
                        return colors[status] || 'gray';
                    },

                    // Drag and Drop Functions
                    draggedProspek: null,

                    handleDragStart(event, prospek) {
                        this.draggedProspek = prospek;
                        event.currentTarget.classList.add('dragging');

                        // Set drag data for HTML5 drag and drop
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', JSON.stringify(prospek));

                        // Add visual feedback for all pipeline stages
                        document.querySelectorAll('.pipeline-stage').forEach(stage => {
                            if (stage.getAttribute('data-status') !== prospek.status) {
                                stage.classList.add('potential-drop-target');
                            }
                        });
                    },

                    handleDrop(event, newStatus) {
                        event.currentTarget.classList.remove('drag-over');

                        // Remove visual feedback from all stages
                        document.querySelectorAll('.pipeline-stage').forEach(stage => {
                            stage.classList.remove('potential-drop-target');
                        });

                        // Exit if no prospek being dragged
                        if (!this.draggedProspek) return;

                        const prospekId = this.draggedProspek.id;
                        const oldStatus = this.draggedProspek.status;

                        // Don't do anything if status hasn't changed
                        if (oldStatus === newStatus) return;

                        // Create a notification toast to show the status is being updated
                        const updateNotification = new CustomEvent('notify', {
                            detail: {
                                type: 'info',
                                message: `Memperbarui status dari "${this.getStatusLabel(oldStatus)}" ke "${this.getStatusLabel(newStatus)}"...`,
                                duration: 2000
                            }
                        });
                        window.dispatchEvent(updateNotification);

                        // We'll skip the optimistic UI update to ensure data integrity
                        // Instead, we'll update the UI only after a successful API response

                        // Call API to update the status on the server
                        const formData = new FormData();
                        formData.append('status', newStatus);
                        formData.append('_method', 'PATCH');


                        // Use the correctly formatted URL that matches the route definition
                        // The route is defined as: crm/pipeline/{prospek}/status
                        const url = "{{ route('crm.pipeline.update-status', ['prospek' => ':prospek']) }}".replace(':prospek',
                            prospekId);

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {

                                if (data.success) {
                                    // Refresh data to ensure we have the latest from the server
                                    this.fetchData();

                                    // Show success notification
                                    let event = new CustomEvent('notify', {
                                        detail: {
                                            type: 'success',
                                            message: `Status prospek berhasil diubah menjadi ${this.getStatusLabel(newStatus)}`
                                        }
                                    });
                                    window.dispatchEvent(event);
                                } else {
                                    // Revert changes if there was an error
                                    this.fetchData();

                                    // Show error notification
                                    let event = new CustomEvent('notify', {
                                        detail: {
                                            type: 'error',
                                            message: data.message || 'Gagal mengubah status prospek'
                                        }
                                    });
                                    window.dispatchEvent(event);
                                }
                            })
                            .catch(error => {
                                console.error('Error updating prospek status:', error);
                                // Revert changes
                                this.fetchData();

                                // Show error notification
                                let event = new CustomEvent('notify', {
                                    detail: {
                                        type: 'error',
                                        message: 'Terjadi kesalahan saat mengubah status prospek'
                                    }
                                });
                                window.dispatchEvent(event);
                            })
                            .finally(() => {
                                this.draggedProspek = null;
                            });
                    },
                };
            }
        </script>
</x-app-layout>

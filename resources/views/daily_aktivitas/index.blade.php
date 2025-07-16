<x-app-layout :breadcrumbs="[['label' => 'Daily Aktivitas']]" :currentPage="'Daily Aktivitas'">

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

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Drag and drop styles */
        .pipeline-card.dragging {
            opacity: 0.5;
            transform: rotate(2deg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.15s ease;
        }

        .pipeline-column.drag-over {
            background-color: rgba(59, 130, 246, 0.1);
            border: 2px dashed #3b82f6;
            transform: scale(1.02);
            transition: all 0.2s ease;
        }

        .dark .pipeline-column.drag-over {
            background-color: rgba(59, 130, 246, 0.2);
        }

        .pipeline-card {
            transition: opacity 0.15s ease, transform 0.15s ease;
        }

        .pipeline-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        /* Smooth transitions */
        .pipeline-stage {
            transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .pipeline-card {
                font-size: 0.875rem;
            }

            .pipeline-stage {
                min-height: 300px;
            }

            .kanban-container {
                overflow-x: auto;
                padding-bottom: 1rem;
                -webkit-overflow-scrolling: touch;
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
        <!-- Loading Overlay - Only show during initial loading or filtering -->
        <div x-show="loading && !draggedItem" x-cloak class="loading-overlay">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg flex items-center space-x-4">
                <div class="spinner"></div>
                <div class="text-gray-700 dark:text-gray-300 font-medium">Memuat data...</div>
            </div>
        </div>

        <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div
                class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1
                            class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Daily Aktivitas
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Kelola dan monitor semua aktivitas harian Anda dalam bentuk pipeline yang intuitif
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex-shrink-0 flex space-x-3">
                        <a href="{{ route('daily-aktivitas.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Aktivitas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-full p-3">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Aktivitas</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-full p-3">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Hari Ini</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.today">0</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-full p-3">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.pending">0</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 dark:bg-red-900/30 rounded-full p-3">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue</h3>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.overdue">0</p>
                        </div>
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
                        Filter Aktifitas
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
                                    <div
                                        class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" x-model="search" @input.debounce.500ms="loadPipelineData()"
                                        class="pl-8 block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Cari aktivitas...">
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
                                <select x-model="timeFrame" @change="loadPipelineData()"
                                    class="mt-1 block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Semua Waktu</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="tomorrow">Besok</option>
                                    <option value="this_week">Minggu Ini</option>
                                    <option value="next_week">Minggu Depan</option>
                                    <option value="this_month">Bulan Ini</option>
                                    <option value="overdue">Terlambat</option>
                                </select>
                            </div>

                            <!-- Reset Button -->
                            <div class="flex items-end">
                                <button @click="clearFilters()" type="button"
                                    class="w-full px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pipeline Kanban Board -->
            <div
                class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2 md:mb-0">Aktivitas</h2>
                </div>

                <!-- Pipeline Stages -->
                <div class="kanban-container overflow-x-auto">
                    <div class="mobile-pipeline-stages grid grid-cols-1 md:grid-cols-4 gap-6 min-w-max md:min-w-0">

                        <!-- Pending -->
                        <div class="pipeline-stage pipeline-column bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="pending" @dragover.prevent="handleDragOver($event)"
                            @dragleave="handleDragLeave($event)" @drop.prevent="handleDrop($event, 'pending')"
                            @dragend="handleDragEnd($event)">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                    Menunggu
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.pending?.length || 0">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(aktivitas, index) in pipelineData.pending || []"
                                    :key="aktivitas.id">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700 cursor-move transition-opacity"
                                        draggable="true" @dragstart="handleDragStart($event, aktivitas)"
                                        @dragend="handleDragEnd($event)">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="aktivitas.judul"></h4>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full"
                                                :class="getPriorityColor(aktivitas.prioritas)"
                                                x-text="getPriorityLabel(aktivitas.prioritas)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"
                                            x-text="aktivitas.deskripsi || 'Tidak ada deskripsi'"></p>

                                        <!-- Type and Location -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                            </svg>
                                            <span x-text="getTypeLabel(aktivitas.tipe_aktivitas)"></span>
                                        </div>

                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="aktivitas.lokasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span x-text="aktivitas.lokasi"></span>
                                        </div>

                                        <!-- Schedule -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(aktivitas.tanggal_mulai)"></span>
                                        </div>

                                        <!-- Assigned Team -->
                                        <div class="mt-2 flex items-start text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 mr-1 mt-0.5 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <template
                                                    x-if="aktivitas.assigned_users && aktivitas.assigned_users.length > 0">
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="assignedUser in aktivitas.assigned_users"
                                                            :key="assignedUser.id">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 font-medium"
                                                                :title="assignedUser.name">
                                                                <span x-text="getInitials(assignedUser.name)"
                                                                    class="text-xs"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template
                                                    x-if="!aktivitas.assigned_users || aktivitas.assigned_users.length === 0">
                                                    <span x-text="aktivitas.assigned_to?.name || 'Tidak ada'"></span>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex justify-end">
                                            <a :href="`/daily-aktivitas/${aktivitas.id}`"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="!pipelineData.pending || pipelineData.pending.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada aktivitas
                                </div>
                            </div>
                        </div>

                        <!-- Dalam Proses -->
                        <div class="pipeline-stage pipeline-column bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="dalam_proses" @dragover.prevent="handleDragOver($event)"
                            @dragleave="handleDragLeave($event)" @drop.prevent="handleDrop($event, 'dalam_proses')"
                            @dragend="handleDragEnd($event)">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                    Dalam Proses
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.dalam_proses?.length || 0">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(aktivitas, index) in pipelineData.dalam_proses || []"
                                    :key="aktivitas.id">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700 cursor-move transition-opacity"
                                        draggable="true" @dragstart="handleDragStart($event, aktivitas)"
                                        @dragend="handleDragEnd($event)">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="aktivitas.judul"></h4>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full"
                                                :class="getPriorityColor(aktivitas.prioritas)"
                                                x-text="getPriorityLabel(aktivitas.prioritas)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"
                                            x-text="aktivitas.deskripsi || 'Tidak ada deskripsi'"></p>

                                        <!-- Type and Location -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                            </svg>
                                            <span x-text="getTypeLabel(aktivitas.tipe_aktivitas)"></span>
                                        </div>

                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="aktivitas.lokasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span x-text="aktivitas.lokasi"></span>
                                        </div>

                                        <!-- Schedule -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(aktivitas.tanggal_mulai)"></span>
                                        </div>

                                        <!-- Assigned Team -->
                                        <div class="mt-2 flex items-start text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 mr-1 mt-0.5 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <template
                                                    x-if="aktivitas.assigned_users && aktivitas.assigned_users.length > 0">
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="assignedUser in aktivitas.assigned_users"
                                                            :key="assignedUser.id">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 font-medium"
                                                                :title="assignedUser.name">
                                                                <span x-text="getInitials(assignedUser.name)"
                                                                    class="text-xs"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template
                                                    x-if="!aktivitas.assigned_users || aktivitas.assigned_users.length === 0">
                                                    <span x-text="aktivitas.assigned_to?.name || 'Tidak ada'"></span>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex justify-end">
                                            <a :href="`/daily-aktivitas/${aktivitas.id}`"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="!pipelineData.dalam_proses || pipelineData.dalam_proses.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada aktivitas
                                </div>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="pipeline-stage pipeline-column bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="selesai" @dragover.prevent="handleDragOver($event)"
                            @dragleave="handleDragLeave($event)" @drop.prevent="handleDrop($event, 'selesai')"
                            @dragend="handleDragEnd($event)">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Selesai
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.selesai?.length || 0">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(aktivitas, index) in pipelineData.selesai || []"
                                    :key="aktivitas.id">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700 cursor-move transition-opacity"
                                        draggable="true" @dragstart="handleDragStart($event, aktivitas)"
                                        @dragend="handleDragEnd($event)">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="aktivitas.judul"></h4>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full"
                                                :class="getPriorityColor(aktivitas.prioritas)"
                                                x-text="getPriorityLabel(aktivitas.prioritas)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"
                                            x-text="aktivitas.deskripsi || 'Tidak ada deskripsi'"></p>

                                        <!-- Type and Location -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                            </svg>
                                            <span x-text="getTypeLabel(aktivitas.tipe_aktivitas)"></span>
                                        </div>

                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="aktivitas.lokasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span x-text="aktivitas.lokasi"></span>
                                        </div>

                                        <!-- Schedule -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(aktivitas.tanggal_mulai)"></span>
                                        </div>

                                        <!-- Assigned Team -->
                                        <div class="mt-2 flex items-start text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 mr-1 mt-0.5 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <template
                                                    x-if="aktivitas.assigned_users && aktivitas.assigned_users.length > 0">
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="assignedUser in aktivitas.assigned_users"
                                                            :key="assignedUser.id">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 font-medium"
                                                                :title="assignedUser.name">
                                                                <span x-text="getInitials(assignedUser.name)"
                                                                    class="text-xs"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template
                                                    x-if="!aktivitas.assigned_users || aktivitas.assigned_users.length === 0">
                                                    <span x-text="aktivitas.assigned_to?.name || 'Tidak ada'"></span>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex justify-end">
                                            <a :href="`/daily-aktivitas/${aktivitas.id}`"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="!pipelineData.selesai || pipelineData.selesai.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada aktivitas
                                </div>
                            </div>
                        </div>

                        <!-- Dibatalkan -->
                        <div class="pipeline-stage pipeline-column bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3"
                            data-status="dibatalkan" @dragover.prevent="handleDragOver($event)"
                            @dragleave="handleDragLeave($event)" @drop.prevent="handleDrop($event, 'dibatalkan')"
                            @dragend="handleDragEnd($event)">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <span class="inline-block w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                    Dibatalkan
                                </h3>
                                <span
                                    class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full"
                                    x-text="pipelineData.dibatalkan?.length || 0">0</span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(aktivitas, index) in pipelineData.dibatalkan || []"
                                    :key="aktivitas.id">
                                    <div class="pipeline-card bg-white dark:bg-gray-800 rounded-lg shadow p-3 border border-gray-200 dark:border-gray-700 cursor-move transition-opacity"
                                        draggable="true" @dragstart="handleDragStart($event, aktivitas)"
                                        @dragend="handleDragEnd($event)">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm"
                                                x-text="aktivitas.judul"></h4>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full"
                                                :class="getPriorityColor(aktivitas.prioritas)"
                                                x-text="getPriorityLabel(aktivitas.prioritas)"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"
                                            x-text="aktivitas.deskripsi || 'Tidak ada deskripsi'"></p>

                                        <!-- Type and Location -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                            </svg>
                                            <span x-text="getTypeLabel(aktivitas.tipe_aktivitas)"></span>
                                        </div>

                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400"
                                            x-show="aktivitas.lokasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span x-text="aktivitas.lokasi"></span>
                                        </div>

                                        <!-- Schedule -->
                                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="formatDate(aktivitas.tanggal_mulai)"></span>
                                        </div>

                                        <!-- Assigned Team -->
                                        <div class="mt-2 flex items-start text-xs text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 mr-1 mt-0.5 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <template
                                                    x-if="aktivitas.assigned_users && aktivitas.assigned_users.length > 0">
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="assignedUser in aktivitas.assigned_users"
                                                            :key="assignedUser.id">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 font-medium"
                                                                :title="assignedUser.name">
                                                                <span x-text="getInitials(assignedUser.name)"
                                                                    class="text-xs"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template
                                                    x-if="!aktivitas.assigned_users || aktivitas.assigned_users.length === 0">
                                                    <span x-text="aktivitas.assigned_to?.name || 'Tidak ada'"></span>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex justify-end">
                                            <a :href="`/daily-aktivitas/${aktivitas.id}`"
                                                class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="!pipelineData.dibatalkan || pipelineData.dibatalkan.length === 0"
                                    class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada aktivitas
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pipelineManager', () => ({
                loading: false,
                search: '',
                timeFrame: '',
                pipelineData: {
                    pending: [],
                    dalam_proses: [],
                    selesai: [],
                    dibatalkan: []
                },
                stats: @json($statistics),
                draggedItem: null,

                init() {
                    this.loadPipelineData();
                },

                async loadPipelineData() {
                    this.loading = true;
                    try {
                        const params = new URLSearchParams({
                            search: this.search,
                            timeFrame: this.timeFrame,
                            pipeline: 'true'
                        });

                        const response = await fetch(
                            `{{ route('daily-aktivitas.data') }}?${params}`);
                        const data = await response.json();

                        if (data.success) {
                            // Group data by status
                            this.pipelineData = {
                                pending: data.data.filter(item => item.status === 'pending'),
                                dalam_proses: data.data.filter(item => item.status ===
                                    'dalam_proses'),
                                selesai: data.data.filter(item => item.status === 'selesai'),
                                dibatalkan: data.data.filter(item => item.status ===
                                    'dibatalkan')
                            };

                            // Update stats
                            this.updateStats();
                        }
                    } catch (error) {
                        console.error('Error loading pipeline data:', error);
                        window.notify('Gagal memuat data pipeline', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                applyFilters() {
                    this.loadPipelineData();
                },

                clearFilters() {
                    this.search = '';
                    this.timeFrame = '';
                    this.loadPipelineData();
                },

                handleDragStart(event, item) {
                    this.draggedItem = item;
                    event.currentTarget.classList.add('dragging');
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/html', event.currentTarget.outerHTML);

                    // Add visual feedback safely
                    setTimeout(() => {
                        if (event.currentTarget && event.currentTarget.style) {
                            event.currentTarget.style.opacity = '0.5';
                        }
                    }, 0);
                },

                handleDragEnd(event) {
                    if (event.currentTarget) {
                        event.currentTarget.classList.remove('dragging');
                        if (event.currentTarget.style) {
                            event.currentTarget.style.opacity = '1';
                        }
                    }

                    // Remove drag over states from all columns safely
                    const columns = document.querySelectorAll('.pipeline-column');
                    columns.forEach(col => {
                        if (col && col.classList) {
                            col.classList.remove('drag-over');
                        }
                    });
                },

                handleDragOver(event) {
                    event.preventDefault();
                    if (event.currentTarget && event.currentTarget.classList) {
                        event.currentTarget.classList.add('drag-over');
                    }
                },

                handleDragLeave(event) {
                    // Only remove drag-over if we're actually leaving the element
                    if (event.currentTarget && event.currentTarget.classList &&
                        (!event.relatedTarget || !event.currentTarget.contains(event.relatedTarget))) {
                        event.currentTarget.classList.remove('drag-over');
                    }
                },

                async handleDrop(event, newStatus) {
                    event.currentTarget.classList.remove('drag-over');

                    if (!this.draggedItem || this.draggedItem.status === newStatus) {
                        return;
                    }

                    const originalStatus = this.draggedItem.status;
                    const draggedItemId = this.draggedItem.id;

                    try {
                        // Update UI optimistically
                        this.draggedItem.status = newStatus;
                        this.moveItemBetweenColumns(draggedItemId, originalStatus, newStatus);
                        this.updateStats();

                        const response = await fetch(
                            `/daily-aktivitas/${draggedItemId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    status: newStatus
                                })
                            });

                        const data = await response.json();
                        if (data.success) {
                            // Show success notification
                            window.notify(
                                `Status berhasil diubah ke ${this.getStatusLabel(newStatus)}`,
                                'success');
                        } else {
                            // Revert UI changes on failure
                            this.revertItemMove(draggedItemId, originalStatus, newStatus);
                            this.updateStats();
                            window.notify(data.message || 'Gagal mengubah status', 'error');
                        }
                    } catch (error) {
                        console.error('Error updating status:', error);
                        // Revert UI changes on error
                        this.revertItemMove(draggedItemId, originalStatus, newStatus);
                        this.updateStats();
                        window.notify('Gagal mengubah status', 'error');
                    } finally {
                        this.draggedItem = null;
                    }
                },

                moveItemBetweenColumns(itemId, fromStatus, toStatus) {
                    // Find and remove item from original column
                    const itemIndex = this.pipelineData[fromStatus].findIndex(item => item.id ===
                        itemId);
                    if (itemIndex !== -1) {
                        const item = this.pipelineData[fromStatus].splice(itemIndex, 1)[0];
                        // Add to new column
                        if (item) {
                            item.status = toStatus;
                            this.pipelineData[toStatus].push(item);
                        }
                    }
                },

                revertItemMove(itemId, originalStatus, newStatus) {
                    // Find and remove item from new column
                    const itemIndex = this.pipelineData[newStatus].findIndex(item => item.id ===
                        itemId);
                    if (itemIndex !== -1) {
                        const item = this.pipelineData[newStatus].splice(itemIndex, 1)[0];
                        // Add back to original column
                        if (item) {
                            item.status = originalStatus;
                            this.pipelineData[originalStatus].push(item);
                        }
                    }
                },

                updateStats() {
                    this.stats = {
                        total: Object.values(this.pipelineData).reduce((sum, arr) => sum + arr
                            .length, 0),
                        pending: this.pipelineData.pending.length,
                        dalam_proses: this.pipelineData.dalam_proses.length,
                        selesai: this.pipelineData.selesai.length,
                        today: this.getTodayCount(),
                        overdue: this.getOverdueCount()
                    };
                },

                getTodayCount() {
                    const today = new Date().toDateString();
                    return Object.values(this.pipelineData).flat().filter(item => {
                        const itemDate = new Date(item.tanggal_mulai).toDateString();
                        return itemDate === today;
                    }).length;
                },

                getOverdueCount() {
                    const now = new Date();
                    return Object.values(this.pipelineData).flat().filter(item => {
                        if (!item.deadline) return false;
                        const deadline = new Date(item.deadline);
                        return deadline < now && item.status !== 'selesai';
                    }).length;
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                getPriorityColor(priority) {
                    const colors = {
                        'rendah': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                        'sedang': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                        'tinggi': 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                        'urgent': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                    };
                    return colors[priority] ||
                        'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                },

                getPriorityLabel(priority) {
                    const labels = {
                        'rendah': 'Rendah',
                        'sedang': 'Sedang',
                        'tinggi': 'Tinggi',
                        'urgent': 'Urgent'
                    };
                    return labels[priority] || 'Tidak diset';
                },

                getTypeLabel(type) {
                    const labels = {
                        'meeting': 'Meeting',
                        'call': 'Panggilan',
                        'email': 'Email',
                        'task': 'Tugas',
                        'follow_up': 'Follow Up',
                        'presentasi': 'Presentasi',
                        'kunjungan': 'Kunjungan',
                        'training': 'Training',
                        'lainnya': 'Lainnya'
                    };
                    return labels[type] || 'Tidak diset';
                },

                getStatusLabel(status) {
                    const labels = {
                        'pending': 'Menunggu',
                        'dalam_proses': 'Dalam Proses',
                        'selesai': 'Selesai',
                        'dibatalkan': 'Dibatalkan'
                    };
                    return labels[status] || 'Tidak diset';
                },

                getInitials(name) {
                    if (!name) return '?';
                    return name.split(' ')
                        .map(word => word.charAt(0).toUpperCase())
                        .slice(0, 2)
                        .join('');
                }
            }));
        });
    </script>
</x-app-layout>

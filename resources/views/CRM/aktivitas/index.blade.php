<x-app-layout :breadcrumbs="[['label' => 'CRM', 'url' => route('crm.prospek.index')], ['label' => 'Aktivitas & Follow-up']]" :currentPage="'Aktivitas & Follow-up'">

    @push('styles')
        <style>
            /* Select2 Dark Mode Support */
            .dark .select2-container--default .select2-selection--single {
                background-color: rgb(55 65 81);
                border-color: rgb(75 85 99);
                color: white;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: white;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: rgb(156 163 175);
            }

            .dark .select2-dropdown {
                background-color: rgb(55 65 81);
                border-color: rgb(75 85 99);
            }

            .dark .select2-container--default .select2-results__option {
                color: white;
            }

            .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: rgb(59 130 246);
            }

            .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: rgb(55 65 81);
                border-color: rgb(75 85 99);
                color: white;
            }

            .select2-user-option {
                padding: 4px 0;
            }
        </style>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
    </style>

    <div x-data="{ loading: false }" class="space-y-6">
        <!-- Loading Overlay -->
        <div x-show="loading" x-cloak class="loading-overlay">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg flex items-center space-x-4">
                <div class="spinner"></div>
                <div class="text-gray-700 dark:text-gray-300 font-medium">Memproses...</div>
            </div>
        </div>

        <!-- Toast Notification -->

        <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="{
            loading: false,
            filterPanelOpen: true,
            activeTab: 'basic',
            datepickerOpen: false,
            dateRange: {
                start: '{{ request('tanggal_dari', '') }}',
                end: '{{ request('tanggal_sampai', '') }}'
            },
            sortField: '{{ request('sort_by', 'tanggal') }}',
            sortDirection: '{{ request('sort_dir', 'desc') }}',
            hasActiveFilters() {
                const prospekEl = document.getElementById('prospek_id');
                const tipeEl = document.getElementById('tipe');
                const statusEl = document.getElementById('status_followup');
                const userEl = document.getElementById('user_id');
        
                return (prospekEl && prospekEl.value !== '') ||
                    (tipeEl && tipeEl.value !== '') ||
                    (statusEl && statusEl.value !== '') ||
                    (userEl && userEl.value !== '') ||
                    this.dateRange.start !== '' ||
                    this.dateRange.end !== '';
            },
            showSuccessMessage(message) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        type: 'success',
                        message: message
                    }
                }));
            },
            toggleSort(field) {
                if (this.sortField === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDirection = 'asc';
                }
        
                // Redirect with sorting parameters
                const url = new URL(window.location.href);
                url.searchParams.set('sort_by', this.sortField);
                url.searchParams.set('sort_dir', this.sortDirection);
                window.location.href = url.toString();
            },
            formatDisplayDate(date) {
                if (!date) return '';
                const dateObj = new Date(date);
                return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            },
            rangeDisplay() {
                if (this.dateRange.start && this.dateRange.end) {
                    return `${this.formatDisplayDate(this.dateRange.start)} - ${this.formatDisplayDate(this.dateRange.end)}`;
                } else if (this.dateRange.start) {
                    return `${this.formatDisplayDate(this.dateRange.start)} - ...`;
                } else if (this.dateRange.end) {
                    return `... - ${this.formatDisplayDate(this.dateRange.end)}`;
                } else {
                    return 'Pilih Rentang Tanggal';
                }
            },
            clearDates() {
                this.dateRange.start = '';
                this.dateRange.end = '';
                const dariEl = document.getElementById('tanggal_dari');
                const sampaiEl = document.getElementById('tanggal_sampai');
                if (dariEl) dariEl.value = '';
                if (sampaiEl) sampaiEl.value = '';
            }
        }">
            <!-- Header -->
            <div
                class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 hover-shadow fade-in w-full">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1
                            class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Aktivitas & Follow-up
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Kelola aktivitas dan pengingat follow-up untuk prospek CRM
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex-shrink-0 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('crm.aktivitas.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 hover:scale-105 transform transition">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Aktivitas
                        </a>
                        <a href="{{ route('crm.aktivitas.followups') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 hover:scale-105 transform transition">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Lihat Follow-up
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-6 w-full">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center mb-2 sm:mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 00-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Aktivitas
                    </h2>
                    <div class="flex items-center space-x-2 ">
                        <span class="text-xs text-gray-500 dark:text-gray-400 italic hidden sm:inline-block">
                            <span x-show="!hasActiveFilters()" x-cloak>Tidak ada filter</span>
                            <span x-show="hasActiveFilters()" x-cloak class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-primary-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Filter aktif
                            </span>
                            <button @click="filterPanelOpen = !filterPanelOpen" type="button"
                                class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                                <span x-text="filterPanelOpen ? 'Sembunyikan' : 'Tampilkan'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 ml-1 transition-transform duration-200"
                                    :class="filterPanelOpen ? 'rotate-180' : ''" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                    </div>
                </div>
            </div>

            <div x-show="filterPanelOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 hover-shadow w-full mb-6">
                <form action="{{ route('crm.aktivitas.index') }}" method="GET" class="space-y-6 fade-in"
                    @submit="loading = true">

                    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                            <li class="mr-2">
                                <button type="button" @click="activeTab = 'basic'"
                                    :class="{
                                        'border-primary-500 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'basic',
                                        'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'basic'
                                    }"
                                    class="inline-block p-2 border-b-2 rounded-t-lg">
                                    Kriteria Umum
                                </button>
                            </li>
                            <li class="mr-2">
                                <button type="button" @click="activeTab = 'date'"
                                    :class="{
                                        'border-primary-500 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'date',
                                        'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'date'
                                    }"
                                    class="inline-block p-2 border-b-2 rounded-t-lg">
                                    Tanggal & Waktu
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Basic Filters Tab -->
                    <div x-show="activeTab === 'basic'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <!-- Filter Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Prospek Filter -->
                            <div class="space-y-2">
                                <label for="prospek_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Prospek
                                </label>
                                <div class="relative rounded-md shadow-sm group">
                                    <select id="prospek_id" name="prospek_id"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-colors duration-200 group-hover:bg-gray-50 dark:group-hover:bg-gray-600 appearance-none pr-10 pl-10">
                                        <option value="">Semua Prospek</option>
                                        @foreach (\App\Models\Prospek::orderBy('nama_prospek')->get() as $prospek)
                                            <option value="{{ $prospek->id }}"
                                                {{ request('prospek_id') == $prospek->id ? 'selected' : '' }}>
                                                {{ $prospek->nama_prospek }}
                                                {{ $prospek->perusahaan ? '- ' . $prospek->perusahaan : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>

                                    <div class="absolute -bottom-1 left-3 right-3 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"
                                        x-show="document.getElementById('prospek_id')?.value !== ''" x-cloak></div>
                                </div>
                            </div>

                            <!-- Tipe Aktivitas Filter -->
                            <div class="space-y-2">
                                <label for="tipe"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    Tipe Aktivitas
                                </label>
                                <div class="relative rounded-md shadow-sm group">
                                    <select id="tipe" name="tipe"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-colors duration-200 group-hover:bg-gray-50 dark:group-hover:bg-gray-600 appearance-none pr-10 pl-10">
                                        <option value="">Semua Tipe</option>
                                        @foreach (\App\Models\ProspekAktivitas::getTipeList() as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('tipe') == $value ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                    </div>

                                    <div class="absolute -bottom-1 left-3 right-3 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"
                                        x-show="document.getElementById('tipe')?.value !== ''" x-cloak></div>
                                </div>
                            </div>

                            <!-- Status Follow-up Filter -->
                            <div class="space-y-2">
                                <label for="status_followup"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Status Follow-up
                                </label>
                                <div class="relative rounded-md shadow-sm group">
                                    <select id="status_followup" name="status_followup"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-colors duration-200 group-hover:bg-gray-50 dark:group-hover:bg-gray-600 appearance-none pr-10 pl-10">
                                        <option value="">Semua Status</option>
                                        @foreach (\App\Models\ProspekAktivitas::getStatusList() as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('status_followup') == $value ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </div>

                                    <div class="absolute -bottom-1 left-3 right-3 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"
                                        x-show="document.getElementById('status_followup')?.value !== ''" x-cloak>
                                    </div>
                                </div>
                            </div>

                            <!-- Pembuat Filter -->
                            <div class="space-y-2">
                                <label for="user_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Dibuat Oleh
                                </label>
                                <div class="relative rounded-md shadow-sm group">
                                    <select id="user_id" name="user_id"
                                        class="select2-user block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-colors duration-200 group-hover:bg-gray-50 dark:group-hover:bg-gray-600">
                                        <option value="">Semua Pembuat</option>
                                        @foreach (\App\Models\User::where('is_active', true)->orderBy('name')->get() as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}
                                                data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                                {{ $user->name }}
                                                @if ($user->email)
                                                    ({{ $user->email }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="absolute -bottom-1 left-3 right-3 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"
                                        x-show="document.getElementById('user_id')?.value !== ''" x-cloak>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Tab -->
                    <div x-show="activeTab === 'date'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <!-- Date Range Picker -->
                        <div class="space-y-2 md:col-span-3">
                            <label for="tanggal_range"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Rentang Tanggal
                            </label>
                            <div class="relative">
                                <button @click="datepickerOpen = !datepickerOpen" type="button"
                                    class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md py-2 px-3 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-200 hover:bg-gray-50 dark:hover:bg-gray-600 flex justify-between items-center pl-10">
                                    <span x-text="rangeDisplay()" class="text-gray-700 dark:text-gray-300"></span>
                                    <div class="flex items-center">
                                        <button x-show="dateRange.start || dateRange.end" @click.stop="clearDates()"
                                            type="button" class="text-gray-400 hover:text-gray-500 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </button>

                                <div x-show="datepickerOpen" @click.away="datepickerOpen = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    class="absolute z-10 mt-1 w-full sm:w-96 right-0 sm:right-auto bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-300 dark:border-gray-700 p-4">

                                    <!-- Quick Date Options -->
                                    <div class="mb-4">
                                        <h4
                                            class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                            Pilihan Cepat</h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button type="button"
                                                @click="dateRange.start = new Date().toISOString().split('T')[0]; dateRange.end = new Date().toISOString().split('T')[0]; document.getElementById('tanggal_dari').value = dateRange.start; document.getElementById('tanggal_sampai').value = dateRange.end; datepickerOpen = false;"
                                                class="flex items-center justify-center text-xs text-gray-700 dark:text-gray-300 px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3.5 w-3.5 mr-1.5 text-primary-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Hari Ini
                                            </button>
                                            <button type="button"
                                                @click="const d = new Date(); dateRange.start = new Date(d.setDate(d.getDate() - 7)).toISOString().split('T')[0]; dateRange.end = new Date().toISOString().split('T')[0]; document.getElementById('tanggal_dari').value = dateRange.start; document.getElementById('tanggal_sampai').value = dateRange.end; datepickerOpen = false;"
                                                class="flex items-center justify-center text-xs text-gray-700 dark:text-gray-300 px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3.5 w-3.5 mr-1.5 text-indigo-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                7 Hari Terakhir
                                            </button>
                                            <button type="button"
                                                @click="const d = new Date(); dateRange.start = new Date(d.setDate(d.getDate() - 30)).toISOString().split('T')[0]; dateRange.end = new Date().toISOString().split('T')[0]; document.getElementById('tanggal_dari').value = dateRange.start; document.getElementById('tanggal_sampai').value = dateRange.end; datepickerOpen = false;"
                                                class="flex items-center justify-center text-xs text-gray-700 dark:text-gray-300 px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3.5 w-3.5 mr-1.5 text-green-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                30 Hari Terakhir
                                            </button>
                                            <button type="button"
                                                @click="const d = new Date(); dateRange.start = new Date(d.setMonth(d.getMonth() - 1, 1)).toISOString().split('T')[0]; dateRange.end = new Date(d.getFullYear(), d.getMonth() + 1, 0).toISOString().split('T')[0]; document.getElementById('tanggal_dari').value = dateRange.start; document.getElementById('tanggal_sampai').value = dateRange.end; datepickerOpen = false;"
                                                class="flex items-center justify-center text-xs text-gray-700 dark:text-gray-300 px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3.5 w-3.5 mr-1.5 text-purple-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Bulan Lalu
                                            </button>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <h4
                                            class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                            Pilih Tanggal Kustom</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Dari
                                                    Tanggal</label>
                                                <input type="date" id="tanggal_dari" name="tanggal_dari"
                                                    x-model="dateRange.start"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai
                                                    Tanggal</label>
                                                <input type="date" id="tanggal_sampai" name="tanggal_sampai"
                                                    x-model="dateRange.end"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" @click="clearDates(); datepickerOpen = false"
                                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium transition-colors duration-200">
                                            Kosongkan
                                        </button>
                                        <button type="button" @click="datepickerOpen = false"
                                            class="bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 px-4 py-1.5 rounded-md text-sm font-medium hover:bg-primary-100 dark:hover:bg-primary-900/50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200">
                                            Terapkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filter Indicators -->
                        <div class="mt-6" x-show="hasActiveFilters()" x-cloak
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            <div
                                class="bg-primary-50 dark:bg-primary-900/10 border border-primary-100 dark:border-primary-800/30 rounded-lg p-3 mb-4">
                                <h3
                                    class="text-xs font-semibold text-primary-800 dark:text-primary-300 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Filter Aktif
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <div x-show="document.getElementById('prospek_id')?.value !== ''"
                                        class="inline-flex items-center text-xs px-2 py-1 rounded-md bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 border border-primary-100 dark:border-primary-800">
                                        <span>Prospek: </span>
                                        <span class="font-medium ml-1"
                                            x-text="(() => {
                                    const select = document.getElementById('prospek_id');
                                    if (!select) return '';
                                    const option = select.options[select.selectedIndex];
                                    return option ? option.text : '';
                                })()"></span>
                                        <button type="button"
                                            @click="const el = document.getElementById('prospek_id'); if(el) el.value = ''"
                                            class="ml-1.5 text-primary-500 hover:text-primary-700 dark:hover:text-primary-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div x-show="document.getElementById('tipe')?.value !== ''"
                                        class="inline-flex items-center text-xs px-2 py-1 rounded-md bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800">
                                        <span>Tipe: </span>
                                        <span class="font-medium ml-1"
                                            x-text="(() => {
                                    const select = document.getElementById('tipe');
                                    if (!select) return '';
                                    const option = select.options[select.selectedIndex];
                                    return option ? option.text : '';
                                })()"></span>
                                        <button type="button"
                                            @click="const el = document.getElementById('tipe'); if(el) el.value = ''"
                                            class="ml-1.5 text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div x-show="document.getElementById('status_followup')?.value !== ''"
                                        class="inline-flex items-center text-xs px-2 py-1 rounded-md bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-100 dark:border-green-800">
                                        <span>Status: </span>
                                        <span class="font-medium ml-1"
                                            x-text="(() => {
                                    const select = document.getElementById('status_followup');
                                    if (!select) return '';
                                    const option = select.options[select.selectedIndex];
                                    return option ? option.text : '';
                                })()"></span>
                                        <button type="button"
                                            @click="const el = document.getElementById('status_followup'); if(el) el.value = ''"
                                            class="ml-1.5 text-green-500 hover:text-green-700 dark:hover:text-green-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div x-show="document.getElementById('user_id')?.value !== ''"
                                        class="inline-flex items-center text-xs px-2 py-1 rounded-md bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-purple-800">
                                        <span>Pembuat: </span>
                                        <span class="font-medium ml-1"
                                            x-text="(() => {
                                    const select = document.getElementById('user_id');
                                    if (!select) return '';
                                    const option = select.options[select.selectedIndex];
                                    return option ? option.getAttribute('data-name') || option.text : '';
                                })()"></span>
                                        <button type="button"
                                            @click="const el = document.getElementById('user_id'); if(el) { if ($(el).data('select2')) { $(el).val('').trigger('change'); } else { el.value = ''; } }"
                                            class="ml-1.5 text-purple-500 hover:text-purple-700 dark:hover:text-purple-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div x-show="dateRange.start || dateRange.end"
                                        class="inline-flex items-center text-xs px-2 py-1 rounded-md bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-800">
                                        <span>Tanggal: </span>
                                        <span class="font-medium ml-1"
                                            x-text="(() => {
                                    if (dateRange.start && dateRange.end) {
                                        return `${formatDisplayDate(dateRange.start)} - ${formatDisplayDate(dateRange.end)}`;
                                    } else if (dateRange.start) {
                                        return `${formatDisplayDate(dateRange.start)} - ...`;
                                    } else if (dateRange.end) {
                                        return `... - ${formatDisplayDate(dateRange.end)}`;
                                    }
                                    return '';
                                })()"></span>
                                        <button type="button" @click="clearDates()"
                                            class="ml-1.5 text-amber-500 hover:text-amber-700 dark:hover:text-amber-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <a href="{{ route('crm.aktivitas.index') }}"
                                        class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium flex items-center transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset Semua Filter
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div
                            class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('crm.aktivitas.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:scale-105 transform order-2 sm:order-1">
                                <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                        clip-rule="evenodd" />
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:scale-105 transform order-1 sm:order-2">
                                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                </form>
            </div>


            <!-- Aktivitas List -->
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 hover-shadow fade-in w-full"
                x-data="{
                    selectedItems: [],
                    selectAll: false,
                    batchActionsOpen: false,
                    toggleSelectAll() {
                        this.selectAll = !this.selectAll;
                        if (this.selectAll) {
                            this.selectedItems = Array.from(document.querySelectorAll('input[name=\'selected_items[]\']')).map(checkbox => checkbox.value);
                        } else {
                            this.selectedItems = [];
                        }
                        this.updateBatchActions();
                    },
                    updateSelection() {
                        const checkboxes = document.querySelectorAll('input[name=\'selected_items[]\']');
                        this.selectAll = checkboxes.length > 0 && this.selectedItems.length === checkboxes.length;
                        this.updateBatchActions();
                    },
                    updateBatchActions() {
                        this.batchActionsOpen = this.selectedItems.length > 0;
                    },
                    getSelectedFollowupItems() {
                        return this.selectedItems.filter(id => {
                            const checkbox = document.querySelector(`input[value='${id}']`);
                            return checkbox && checkbox.dataset.followup === 'true' && checkbox.dataset.status === 'menunggu';
                        });
                    },
                    executeMarkAsCompleted() {
                        if (confirm('Apakah Anda yakin ingin menandai semua follow-up terpilih sebagai selesai?')) {
                            loading = true;
                            fetch('{{ route('crm.aktivitas.followup.batch-update') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
                                    },
                                    body: JSON.stringify({
                                        ids: this.getSelectedFollowupItems(),
                                        status_followup: 'selesai'
                                    })
                                })
                                .then(response => {
                                    loading = false;
                                    if (response.ok) {
                                        return response.json().catch(() => ({}));
                                    } else {
                                        throw new Error('Response not OK');
                                    }
                                })
                                .then(data => {
                                    const count = data.count || this.getSelectedFollowupItems().length;
                                    showSuccessMessage(`${count} follow-up berhasil ditandai selesai`);
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                })
                                .catch(error => {
                                    loading = false;
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan: ' + error.message);
                                });
                        }
                    },
                    executeDelete() {
                        if (confirm('Apakah Anda yakin ingin menghapus semua aktivitas terpilih?')) {
                            loading = true;
                            fetch('{{ route('crm.aktivitas.batch-delete') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
                                    },
                                    body: JSON.stringify({
                                        ids: this.selectedItems
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    loading = false;
                                    showSuccessMessage(`${data.count} aktivitas berhasil dihapus`);
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                })
                                .catch(error => {
                                    loading = false;
                                    console.error('Error:', error);
                                });
                        }
                    }
                }">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 100 4 2 2 0 000-4zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        Daftar Aktivitas
                    </h3>
                    <div class="flex items-center gap-3">
                        <template x-if="batchActionsOpen">
                            <div class="flex items-center space-x-2 animate__animated animate__fadeIn">
                                <template x-if="getSelectedFollowupItems().length > 0">
                                    <button @click="executeMarkAsCompleted()" type="button"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </button>
                                </template>
                                <button @click="executeDelete()" type="button"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </template>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300">
                            {{ $aktivitas->total() }} Aktivitas
                        </span>
                    </div>
                </div>

                @if ($aktivitas->isEmpty())
                    <div class="p-8 text-center fade-in">
                        <div
                            class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-6 pulse">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 text-primary-500 dark:text-primary-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-3">Belum Ada Aktivitas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                            Belum ada aktivitas yang tercatat dengan filter yang dipilih. Tambahkan aktivitas baru atau
                            ubah
                            filter untuk melihat aktivitas lainnya.
                        </p>
                        <a href="{{ route('crm.aktivitas.create') }}"
                            class="mt-4 inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:scale-105 transform">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Aktivitas Pertama
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                class="form-checkbox h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                                @click="toggleSelectAll()" :checked="selectAll">
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <button class="flex items-center focus:outline-none w-full text-left group"
                                            @click="toggleSort('tanggal')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-primary-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Tanggal
                                            <!-- Sort Indicator -->
                                            <span class="ml-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                :class="{ 'opacity-100': sortField === 'tanggal' }">
                                                <svg x-show="sortField === 'tanggal' && sortDirection === 'asc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="sortField === 'tanggal' && sortDirection === 'desc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <button class="flex items-center focus:outline-none w-full text-left group"
                                            @click="toggleSort('prospek_id')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-primary-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Prospek / Customer
                                            <!-- Sort Indicator -->
                                            <span class="ml-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                :class="{ 'opacity-100': sortField === 'prospek_id' }">
                                                <svg x-show="sortField === 'prospek_id' && sortDirection === 'asc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="sortField === 'prospek_id' && sortDirection === 'desc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <button class="flex items-center focus:outline-none w-full text-left group"
                                            @click="toggleSort('tipe')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-primary-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                            </svg>
                                            Tipe
                                            <!-- Sort Indicator -->
                                            <span class="ml-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                :class="{ 'opacity-100': sortField === 'tipe' }">
                                                <svg x-show="sortField === 'tipe' && sortDirection === 'asc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="sortField === 'tipe' && sortDirection === 'desc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <button class="flex items-center focus:outline-none w-full text-left group"
                                            @click="toggleSort('judul')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-primary-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                            Judul
                                            <!-- Sort Indicator -->
                                            <span class="ml-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                :class="{ 'opacity-100': sortField === 'judul' }">
                                                <svg x-show="sortField === 'judul' && sortDirection === 'asc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="sortField === 'judul' && sortDirection === 'desc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <button class="flex items-center focus:outline-none w-full text-left group"
                                            @click="toggleSort('tanggal_followup')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-primary-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            Follow-up
                                            <!-- Sort Indicator -->
                                            <span class="ml-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                :class="{ 'opacity-100': sortField === 'tanggal_followup' }">
                                                <svg x-show="sortField === 'tanggal_followup' && sortDirection === 'asc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="sortField === 'tanggal_followup' && sortDirection === 'desc'"
                                                    class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-primary-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Aksi
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($aktivitas as $item)
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 fade-in">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="selected_items[]"
                                                    class="form-checkbox h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                                    value="{{ $item->id }}"
                                                    data-followup="{{ $item->perlu_followup ? 'true' : 'false' }}"
                                                    data-status="{{ $item->status_followup }}"
                                                    x-model="selectedItems" @change="updateSelection()">
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <div class="font-medium">{{ $item->tanggal->format('d M Y') }}</div>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                                {{ $item->tanggal->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($item->prospek_id && $item->prospek)
                                                <a href="{{ route('crm.prospek.show', $item->prospek_id) }}"
                                                    class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 truncate max-w-[200px] block hover:underline transition-all duration-200">
                                                    {{ $item->prospek->nama_prospek }}
                                                </a>
                                                <div
                                                    class="text-gray-500 dark:text-gray-400 text-xs truncate max-w-[200px]">
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 mr-1">Prospek</span>
                                                    {{ $item->prospek->perusahaan ?: 'Tidak ada perusahaan' }}
                                                </div>
                                            @elseif($item->customer_id && $item->customer)
                                                <a href="{{ route('master.pelanggan.show', $item->customer_id) }}"
                                                    class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 truncate max-w-[200px] block hover:underline transition-all duration-200">
                                                    {{ $item->customer->nama }}
                                                </a>
                                                <div
                                                    class="text-gray-500 dark:text-gray-400 text-xs truncate max-w-[200px]">
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 mr-1">Customer</span>
                                                    {{ $item->customer->company ?: $item->customer->kode }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 italic">Tidak ada
                                                    data</span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all duration-200 hover:scale-105 transform
                                            @if ($item->tipe == 'telepon') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @elseif($item->tipe == 'email') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300
                                            @elseif($item->tipe == 'pertemuan') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($item->tipe == 'presentasi') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @elseif($item->tipe == 'penawaran') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                        ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    @if ($item->tipe == 'telepon')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    @elseif($item->tipe == 'email')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    @elseif($item->tipe == 'pertemuan')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    @elseif($item->tipe == 'presentasi')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                                    @elseif($item->tipe == 'penawaran')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12h6m-6 4h6m-6-8h6M9 20h6M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                    @endif
                                                </svg>
                                                {{ \App\Models\ProspekAktivitas::getTipeList()[$item->tipe] ?? $item->tipe }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <div class="font-medium">{{ $item->judul }}</div>
                                            @if ($item->hasil)
                                                <div class="text-gray-500 dark:text-gray-400 text-xs">
                                                    Hasil: {{ Str::limit($item->hasil, 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            @if ($item->perlu_followup)
                                                <div class="flex flex-col">
                                                    <div
                                                        class="text-xs flex items-center text-primary-600 dark:text-primary-400 mb-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $item->tanggal_followup ? $item->tanggal_followup->format('d M Y H:i') : 'Belum dijadwalkan' }}
                                                    </div>
                                                    <span
                                                        class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium transition-all duration-200 hover:scale-105 transform
                                                    @if ($item->status_followup == 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                    @elseif($item->status_followup == 'selesai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                    @elseif($item->status_followup == 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                                ">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 mr-1" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            @if ($item->status_followup == 'menunggu')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            @elseif($item->status_followup == 'selesai')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            @elseif($item->status_followup == 'dibatalkan')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            @endif
                                                        </svg>
                                                        {{ \App\Models\ProspekAktivitas::getStatusList()[$item->status_followup] ?? 'Menunggu' }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Tidak perlu follow-up
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <a href="{{ route('crm.aktivitas.show', $item->id) }}"
                                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-all duration-200 transform hover:scale-110"
                                                    title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('crm.aktivitas.edit', $item->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-all duration-200 transform hover:scale-110"
                                                    title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                @if ($item->perlu_followup && $item->status_followup === 'menunggu')
                                                    <form
                                                        action="{{ route('crm.aktivitas.followup.update', $item->id) }}"
                                                        method="POST" class="inline"
                                                        @submit.prevent="
                                                    loading = true;
                                                    fetch($el.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
                                                        },
                                                        body: JSON.stringify({
                                                            _method: 'PATCH',
                                                            status_followup: 'selesai'
                                                        })
                                                    })
                                                    .then(response => {
                                                        loading = false;
                                                        if (response.ok) {
                                                            showSuccessMessage('Follow-up berhasil ditandai selesai');
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 1500);
                                                        } else {
                                                            alert('Terjadi kesalahan saat memperbarui status');
                                                        }
                                                    })
                                                    .catch(error => {
                                                        loading = false;
                                                        console.error('Error:', error);
                                                        alert('Terjadi kesalahan: ' + error.message);
                                                    });">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status_followup" value="selesai">
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 transform hover:scale-110 disabled:opacity-50"
                                                            title="Tandai Selesai" :disabled="loading">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('crm.aktivitas.destroy', $item->id) }}"
                                                    method="POST" class="inline"
                                                    @submit.prevent="
                                                    if (confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) {
                                                        loading = true;
                                                        fetch($el.action, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
                                                            },
                                                            body: JSON.stringify({
                                                                _method: 'DELETE'
                                                            })
                                                        })
                                                        .then(response => {
                                                            loading = false;
                                                            showSuccessMessage('Aktivitas berhasil dihapus');
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 1500);
                                                        })
                                                        .catch(error => {
                                                            loading = false;
                                                            console.error('Error:', error);
                                                        });
                                                    }">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 transform hover:scale-110 disabled:opacity-50"
                                                        title="Hapus" :disabled="loading">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 w-full">
                        {{ $aktivitas->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2 for user dropdown
                $('.select2-user').select2({
                    placeholder: 'Semua Pembuat',
                    allowClear: true,
                    width: '100%',
                    theme: 'default',
                    templateResult: formatUser,
                    templateSelection: formatUserSelection
                });

                function formatUser(user) {
                    if (!user.id) {
                        return user.text;
                    }

                    const $user = $(user.element);
                    const name = $user.data('name');
                    const email = $user.data('email');

                    let html = '<div class="select2-user-option">';
                    html += '<div class="font-medium text-gray-900 dark:text-white">' + name + '</div>';
                    if (email) {
                        html += '<div class="text-xs text-gray-500 dark:text-gray-400">' + email + '</div>';
                    }
                    html += '</div>';

                    return $(html);
                }

                function formatUserSelection(user) {
                    if (!user.id) {
                        return user.text;
                    }
                    const $user = $(user.element);
                    const name = $user.data('name');
                    return name || user.text;
                }
            });
        </script>
    @endpush
</x-app-layout>

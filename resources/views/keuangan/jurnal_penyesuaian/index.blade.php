<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Jurnal Penyesuaian'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Jurnal Penyesuaian
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kelola jurnal penyesuaian akhir periode dan koreksi pencatatan akuntansi
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.jurnal-penyesuaian.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Jurnal Penyesuaian
                </a>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('keuangan.jurnal-penyesuaian.index') }}" class="space-y-3"
                id="filterForm">
                <div class="flex flex-wrap items-end gap-3">
                    {{-- Date Inputs --}}
                    <div>
                        <label for="start_date"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Dari</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                    </div>

                    <div>
                        <label for="end_date"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Sampai</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                    </div>

                    {{-- Period Dropdown --}}
                    <div class="flex-1 min-w-48">
                        <label for="periode_id"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Periode
                            Akuntansi</label>
                        <select name="periode_id" id="periode_id"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                            <option value="">Semua Periode</option>
                            @foreach ($periodes as $periode)
                                <option value="{{ $periode->id }}"
                                    {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama }} ({{ $periode->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $periode->tanggal_akhir->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Account Dropdown --}}
                    <div class="flex-1 min-w-64">
                        <label for="akun_id"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Pilih Akun</label>
                        <select name="akun_id" id="akun_id"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                            <option value="">Semua Akun</option>
                            @php
                                $groupedAccounts = $akuns->groupBy('kategori');
                                $categoryLabels = [
                                    'asset' => 'ASET',
                                    'liability' => 'KEWAJIBAN',
                                    'equity' => 'MODAL',
                                    'income' => 'PENDAPATAN',
                                    'expense' => 'BEBAN',
                                    'other' => 'LAINNYA',
                                ];
                            @endphp
                            @foreach (['asset', 'liability', 'equity', 'income', 'expense', 'other'] as $category)
                                @if (isset($groupedAccounts[$category]) && $groupedAccounts[$category]->count() > 0)
                                    <optgroup label="{{ $categoryLabels[$category] }}">
                                        @foreach ($groupedAccounts[$category] as $akun)
                                            <option value="{{ $akun->id }}"
                                                {{ request('akun_id') == $akun->id ? 'selected' : '' }}>
                                                {{ $akun->kode }} - {{ $akun->nama }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Reference Number Search --}}
                    <div class="flex-1 min-w-48">
                        <label for="no_referensi"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">No.
                            Referensi</label>
                        <input type="text" name="no_referensi" id="no_referensi"
                            value="{{ request('no_referensi') }}" placeholder="Cari nomor referensi..."
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                    </div>

                    {{-- Status Posting Filter --}}
                    <div class="flex-1 min-w-48">
                        <label for="status_posting"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status
                            Posting</label>
                        <select name="status_posting" id="status_posting"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status_posting') === 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="posted" {{ request('status_posting') === 'posted' ? 'selected' : '' }}>
                                Posted</option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <button type="submit" title="Filter Data"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md transition-colors text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('keuangan.jurnal-penyesuaian.index') }}" title="Reset Filter"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-md transition-colors text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset
                        </a>

                    </div>
                </div>

                {{-- Quick Period Buttons --}}
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Quick:</span>
                    <button type="button" onclick="setQuickPeriod('today')"
                        class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                        Hari Ini
                    </button>
                    <button type="button" onclick="setQuickPeriod('thisMonth')"
                        class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setQuickPeriod('thisYear')"
                        class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                        Tahun Ini
                    </button>
                </div>
            </form>
        </div>

        {{-- Journal Entries List --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <div
                class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Jurnal Penyesuaian</h3>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span
                        class="bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded-md text-indigo-700 dark:text-indigo-400">
                        <span id="total-count">{{ $jurnals->total() }}</span> transaksi
                    </span>
                </div>
            </div>

            {{-- Table Container --}}
            <div id="table-container">
                @include('keuangan.jurnal_penyesuaian._table')
            </div>

            {{-- Pagination --}}
            <div id="pagination-container">
                @include('keuangan.jurnal_penyesuaian._pagination', ['jurnals' => $jurnals])
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.882 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus jurnal penyesuaian:
                    </p>
                    <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            <span id="modal-no-referensi"></span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Tanggal: <span id="modal-tanggal"></span>
                        </p>
                    </div>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button id="cancelDelete" type="button"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button id="confirmDelete" type="button"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Post/Unpost Confirmation Modal --}}
    <div id="postModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
            </div>

            {{-- Modal panel --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div id="postModalIcon"
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icon will be set dynamically -->
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                id="postModalTitle">
                                <!-- Title will be set dynamically -->
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="postModalMessage">
                                    <!-- Dynamic message will be inserted here -->
                                </p>
                                <div id="postModalWarning" class="mt-3 p-3 rounded-md border">
                                    <!-- Warning content will be set dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmPostBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <!-- Button text will be set dynamically -->
                    </button>
                    <button type="button" id="cancelPostBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                'use strict';

                // Scoped variables for sorting and pagination
                let jurnalCurrentSort = '{{ $sortField }}';
                let jurnalCurrentDirection = '{{ $sortDirection }}';
                let jurnalIsLoading = false;
                let jurnalCurrentPostForm = null;

                // Safe DOM element getter with null check
                function safeGetElement(id) {
                    const element = document.getElementById(id);
                    if (!element) {
                        console.warn(`Element with ID '${id}' not found`);
                    }
                    return element;
                }

                // Safe text content setter with null check
                function safeSetTextContent(elementId, content) {
                    const element = safeGetElement(elementId);
                    if (element) {
                        element.textContent = content;
                    }
                }

                // Safe innerHTML setter with null check
                function safeSetInnerHTML(elementId, content) {
                    const element = safeGetElement(elementId);
                    if (element) {
                        element.innerHTML = content;
                    }
                }

                // Sort function - exposed to global scope
                window.sortTable = function(field) {
                    if (jurnalIsLoading) return;

                    if (jurnalCurrentSort === field) {
                        jurnalCurrentDirection = jurnalCurrentDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        jurnalCurrentSort = field;
                        jurnalCurrentDirection = 'asc';
                    }

                    // Get current filter parameters from the form
                    const filterForm = safeGetElement('filterForm');
                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams();

                    for (let [key, value] of formData) {
                        if (value !== '') {
                            params.append(key, value);
                        }
                    }

                    // Add sort parameters
                    params.set('sort', jurnalCurrentSort);
                    params.set('direction', jurnalCurrentDirection);

                    // Reload the page with new sort parameters
                    window.location.search = params.toString();
                };

                // Apply filters and sorting with AJAX
                function applyFiltersAndSort(page = 1) {
                    if (jurnalIsLoading) return;

                    jurnalIsLoading = true;

                    // Show loading state
                    const tableContainer = safeGetElement('table-container');
                    if (tableContainer) {
                        tableContainer.style.opacity = '0.5';
                    }

                    // Get all form data
                    const filterForm = safeGetElement('filterForm');
                    if (!filterForm) {
                        console.error('Filter form not found');
                        jurnalIsLoading = false;
                        return;
                    }

                    const formData = new FormData(filterForm);

                    // Add sort parameters
                    formData.append('sort', jurnalCurrentSort);
                    formData.append('direction', jurnalCurrentDirection);
                    formData.append('page', page);

                    // Convert FormData to URLSearchParams
                    const params = new URLSearchParams();
                    for (let [key, value] of formData) {
                        if (value !== '') {
                            params.append(key, value);
                        }
                    }

                    // Make AJAX request
                    fetch('{{ route('keuangan.jurnal-penyesuaian.index') }}?' + params.toString(), {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Update table content
                            safeSetInnerHTML('table-container', data.table_html);

                            // Update pagination
                            safeSetInnerHTML('pagination-container', data.pagination_html);

                            // Update counts
                            safeSetTextContent('total-count', data.total);

                            // Update showing-info safely (it should now always exist)
                            const showingInfo = safeGetElement('showing-info');
                            if (showingInfo && data.first_item !== undefined && data.last_item !== undefined && data
                                .total !== undefined) {
                                showingInfo.textContent =
                                    `Menampilkan ${data.first_item} hingga ${data.last_item} dari ${data.total} transaksi`;
                            }

                            // Re-attach delete event listeners
                            attachDeleteEventListeners();

                            // Update URL without page reload
                            const url = new URL(window.location);
                            url.search = params.toString();
                            // Use pushState to create a new history entry
                            window.history.pushState({
                                path: url.toString()
                            }, '', url);

                            // Restore opacity
                            if (tableContainer) {
                                tableContainer.style.opacity = '1';
                            }
                            jurnalIsLoading = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (tableContainer) {
                                tableContainer.style.opacity = '1';
                            }
                            jurnalIsLoading = false;
                        });
                }

                // Handle filter form submission
                const filterForm = safeGetElement('filterForm');
                if (filterForm) {
                    filterForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        applyFiltersAndSort(1);
                    });
                }

                // Quick date filters - exposed to global scope
                window.setQuickPeriod = function(period) {
                    const today = new Date();
                    let startDate, endDate;

                    switch (period) {
                        case 'today':
                            startDate = endDate = today.toISOString().split('T')[0];
                            break;
                        case 'thisMonth':
                            startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[
                                0];
                            break;
                        case 'thisYear':
                            startDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                            endDate = new Date(today.getFullYear(), 11, 31).toISOString().split('T')[0];
                            break;
                    }

                    const startDateInput = safeGetElement('start_date');
                    const endDateInput = safeGetElement('end_date');
                    const periodeSelect = safeGetElement('periode_id');

                    if (startDateInput) startDateInput.value = startDate;
                    if (endDateInput) endDateInput.value = endDate;

                    // Reset other filters when using quick period  
                    if (periodeSelect) periodeSelect.selectedIndex = 0;

                    applyFiltersAndSort(1);
                };

                // Helper function to generate delete route URL
                function getDeleteUrl(noReferensi) {
                    return `{{ url('keuangan/jurnal-penyesuaian') }}/${noReferensi}`;
                }

                // Delete modal functionality
                let jurnalDeleteForm = null;
                const deleteModal = safeGetElement('deleteModal');
                const modalNoReferensi = safeGetElement('modal-no-referensi');
                const modalTanggal = safeGetElement('modal-tanggal');
                const confirmDeleteBtn = safeGetElement('confirmDelete');
                const cancelDeleteBtn = safeGetElement('cancelDelete');

                function attachDeleteEventListeners() {
                    document.querySelectorAll('.delete-button').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();

                            const noReferensi = this.dataset.noReferensi;
                            const tanggal = this.dataset.tanggal;

                            // Create form dynamically
                            jurnalDeleteForm = document.createElement('form');
                            jurnalDeleteForm.method = 'POST';
                            jurnalDeleteForm.action = getDeleteUrl(noReferensi);

                            const csrfField = document.createElement('input');
                            csrfField.type = 'hidden';
                            csrfField.name = '_token';
                            csrfField.value = '{{ csrf_token() }}';

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            jurnalDeleteForm.appendChild(csrfField);
                            jurnalDeleteForm.appendChild(methodField);

                            // Update modal content
                            if (modalNoReferensi) modalNoReferensi.textContent = noReferensi;
                            if (modalTanggal) modalTanggal.textContent = tanggal;

                            // Show modal
                            if (deleteModal) {
                                deleteModal.classList.remove('hidden');
                                // Focus on cancel button for accessibility
                                if (cancelDeleteBtn) cancelDeleteBtn.focus();
                            }
                        });
                    });
                }

                // Confirm delete
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        if (jurnalDeleteForm) {
                            document.body.appendChild(jurnalDeleteForm);
                            jurnalDeleteForm.submit();
                        }
                    });
                }

                // Cancel delete
                function closeModal() {
                    if (deleteModal) {
                        deleteModal.classList.add('hidden');
                    }
                    jurnalDeleteForm = null;
                }

                // Cancel delete event listeners
                if (cancelDeleteBtn) {
                    cancelDeleteBtn.addEventListener('click', closeModal);
                }

                // Close modal when clicking outside
                if (deleteModal) {
                    deleteModal.addEventListener('click', function(e) {
                        if (e.target === deleteModal) {
                            closeModal();
                        }
                    });
                }

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
                        closeModal();
                    }
                });

                // Handle pagination clicks
                document.addEventListener('click', function(e) {
                    if (e.target.matches('.pagination a')) {
                        e.preventDefault();
                        const url = new URL(e.target.href);
                        const page = url.searchParams.get('page') || 1;
                        applyFiltersAndSort(page);
                    }
                });

                // Handle browser back/forward button using pageshow event for bfcache compatibility
                window.addEventListener('pageshow', function(event) {
                    // The pageshow event is fired every time the page is displayed.
                    // The event.persisted property is false on initial load, and true if the page is from the bfcache.
                    // If the page is restored from the back-forward cache, we need to reload it to
                    // ensure we get the correct HTML content instead of a stale JSON response.
                    if (event.persisted) {
                        window.location.reload();
                    }
                });

                // Initial setup
                document.addEventListener('DOMContentLoaded', function() {
                    attachDeleteEventListeners();
                    setupPostModalEventListeners();
                });

                // Post/Unpost modal functionality
                // Use event delegation for post/unpost buttons
                document.addEventListener('click', function(e) {
                    const postButton = e.target.closest('.post-unpost-btn');
                    if (postButton) {
                        e.preventDefault();
                        e.stopPropagation();

                        const action = postButton.getAttribute('data-action');
                        const noReferensi = postButton.getAttribute('data-no-referensi') || 'N/A';
                        const formAction = postButton.getAttribute('data-form-action');


                        // Validate required data
                        if (!action || !formAction) {
                            console.error('Missing required data for post/unpost action:', {
                                action,
                                formAction
                            });
                            return;
                        }

                        // Create a form for submission
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = formAction;

                        // Add CSRF token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Add no_referensi
                        const noRefInput = document.createElement('input');
                        noRefInput.type = 'hidden';
                        noRefInput.name = 'no_referensi';
                        noRefInput.value = noReferensi;
                        form.appendChild(noRefInput);

                        // Add form to body temporarily (required for submission)
                        document.body.appendChild(form);
                        form.style.display = 'none';

                        // console.log('Form created successfully, showing modal'); // Debug log
                        showPostModal(action, form, noReferensi);
                    }
                });

                function setupPostModalEventListeners() {
                    const confirmPostBtn = safeGetElement('confirmPostBtn');
                    const cancelPostBtn = safeGetElement('cancelPostBtn');
                    const postModal = safeGetElement('postModal');

                    if (confirmPostBtn) {
                        confirmPostBtn.addEventListener('click', function() {
                            if (jurnalCurrentPostForm) {
                                // console.log('Submitting post form'); // Debug log

                                // Disable the confirm button to prevent double submission
                                const originalText = confirmPostBtn.textContent;
                                confirmPostBtn.disabled = true;
                                confirmPostBtn.textContent = 'Memproses...';

                                jurnalCurrentPostForm.submit();

                                // Clean up the form after submission
                                setTimeout(() => {
                                    if (jurnalCurrentPostForm && jurnalCurrentPostForm.parentNode) {
                                        jurnalCurrentPostForm.parentNode.removeChild(jurnalCurrentPostForm);
                                    }
                                    closePostModal();
                                }, 100);
                            } else {
                                console.error('No jurnalCurrentPostForm set');
                                closePostModal();
                            }
                        });
                    }

                    if (cancelPostBtn) {
                        cancelPostBtn.addEventListener('click', function() {
                            // console.log('Post/unpost cancelled');
                            closePostModal();
                        });
                    }

                    if (postModal) {
                        postModal.addEventListener('click', function(e) {
                            if (e.target === this) {
                                // console.log('Modal closed by clicking outside');
                                closePostModal();
                            }
                        });
                    }

                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && postModal && !postModal.classList.contains('hidden')) {
                            // console.log('Modal closed with Escape key');
                            closePostModal();
                        }
                    });
                }

                function showPostModal(action, form, noReferensi = 'N/A') {


                    jurnalCurrentPostForm = form;

                    const modal = safeGetElement('postModal');
                    const title = safeGetElement('postModalTitle');
                    const message = safeGetElement('postModalMessage');
                    const warning = safeGetElement('postModalWarning');
                    const icon = safeGetElement('postModalIcon');
                    const confirmBtn = safeGetElement('confirmPostBtn');

                    if (!modal || !title || !message || !warning || !icon || !confirmBtn) {
                        console.error('Modal elements not found:', {
                            modal: !!modal,
                            title: !!title,
                            message: !!message,
                            warning: !!warning,
                            icon: !!icon,
                            confirmBtn: !!confirmBtn
                        });
                        return;
                    }

                    if (action === 'post') {
                        // Post configuration
                        title.textContent = 'Konfirmasi Posting Jurnal Penyesuaian';

                        message.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin memposting jurnal penyesuaian berikut?</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                    <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                </div>
                            </div>
                        `;

                        icon.className =
                            'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10';
                        icon.innerHTML = `
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        `;

                        warning.className =
                            'mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800';
                        warning.innerHTML = `
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 dark:text-green-300">
                                        <strong>Informasi:</strong> Setelah diposting, jurnal penyesuaian ini akan mempengaruhi saldo akun dan tidak dapat diedit lagi. Pastikan semua entri sudah benar sebelum melanjutkan.
                                    </p>
                                </div>
                            </div>
                        `;

                        confirmBtn.className =
                            'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 hover:bg-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                        confirmBtn.textContent = 'Ya, Posting Jurnal';

                    } else {
                        // Unpost configuration
                        title.textContent = 'Konfirmasi Pembatalan Posting';

                        message.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin membatalkan posting jurnal penyesuaian berikut?</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                    <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                </div>
                            </div>
                        `;

                        icon.className =
                            'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/30 sm:mx-0 sm:h-10 sm:w-10';
                        icon.innerHTML = `
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        `;

                        warning.className =
                            'mt-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-md border border-orange-200 dark:border-orange-800';
                        warning.innerHTML = `
                            <div class="flex">
                                <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-orange-700 dark:text-orange-300">
                                        <strong>Perhatian:</strong> Pembatalan posting akan membalik perubahan saldo akun yang telah dilakukan. Jurnal akan kembali ke status draft dan dapat diedit kembali.
                                    </p>
                                </div>
                            </div>
                        `;

                        confirmBtn.className =
                            'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 hover:bg-orange-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                        confirmBtn.textContent = 'Ya, Unpost';
                    }

                    // Show modal
                    // console.log('Showing modal...'); // Debug log
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    confirmBtn.focus();
                    // console.log('Modal should be visible now'); // Debug log
                }

                function closePostModal() {
                    const modal = safeGetElement('postModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = ''; // Restore scrolling
                    }

                    // Clean up the form if it exists
                    if (jurnalCurrentPostForm && jurnalCurrentPostForm.parentNode) {
                        jurnalCurrentPostForm.parentNode.removeChild(jurnalCurrentPostForm);
                    }
                    jurnalCurrentPostForm = null;

                    // Reset confirm button state
                    const confirmBtn = safeGetElement('confirmPostBtn');
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = confirmBtn.textContent.includes('Posting') ? 'Ya, Posting Jurnal' :
                            'Ya, Unpost';
                    }
                }

            })(); // End IIFE
        </script>
    @endpush
</x-app-layout>

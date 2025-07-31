<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Jurnal Penutup'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-red-600 dark:text-red-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Jurnal Penutup
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kelola jurnal penutup akhir periode untuk menutup akun nominal
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.jurnal-penutup.create', ['mode' => 'auto']) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Auto Closing
                </a>
                <a href="{{ route('keuangan.jurnal-penutup.create', ['mode' => 'manual']) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Manual Entry
                </a>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('keuangan.jurnal-penutup.index') }}" id="filterForm"
                class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    {{-- Date Range --}}
                    <div class="lg:col-span-2 xl:col-span-2">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="tanggal_awal"
                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Dari
                                    Tanggal</label>
                                <input type="date" name="tanggal_awal" id="tanggal_awal"
                                    value="{{ request('tanggal_awal', $tanggalAwal) }}"
                                    class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 focus:ring-1">
                            </div>
                            <div>
                                <label for="tanggal_akhir"
                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Sampai
                                    Tanggal</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                    value="{{ request('tanggal_akhir', $tanggalAkhir) }}"
                                    class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 focus:ring-1">
                            </div>
                        </div>
                    </div>

                    {{-- Period Filter --}}
                    <div>
                        <label for="periode_id"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Periode</label>
                        <select name="periode_id" id="periode_id"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 focus:ring-1">
                            <option value="">Semua Periode</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}"
                                    {{ request('periode_id') == $period->id ? 'selected' : '' }}>
                                    {{ $period->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- No Referensi --}}
                    <div>
                        <label for="no_referensi"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">No.
                            Referensi</label>
                        <input type="text" name="no_referensi" id="no_referensi"
                            value="{{ request('no_referensi') }}" placeholder="Cari no. referensi..."
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 focus:ring-1">
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label for="status"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-red-500 focus:ring-red-500 focus:ring-1">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="posted" {{ request('status') === 'posted' ? 'selected' : '' }}>Posted
                            </option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>

                {{-- Quick Filter Buttons --}}
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Quick Filter:</span>
                    <button type="button" onclick="setQuickPeriod('today')"
                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                        Hari Ini
                    </button>
                    <button type="button" onclick="setQuickPeriod('thisMonth')"
                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setQuickPeriod('thisYear')"
                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600 dark:text-red-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Daftar Jurnal Penutup
                    </h3>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span
                        id="total-count">{{ is_object($jurnals) && method_exists($jurnals, 'total') ? $jurnals->total() : $jurnals->count() }}</span>
                    total jurnal
                </div>
            </div>

            {{-- Table Container --}}
            <div id="table-container">
                @include('keuangan.jurnal_penutup._table')
            </div>

            {{-- Pagination --}}
            <div id="pagination-container">
                @include('keuangan.jurnal_penutup._pagination', ['jurnals' => $jurnals])
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="modal-message">
                        Apakah Anda yakin ingin menghapus jurnal penutup ini?
                    </p>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button id="cancelDelete" type="button"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Batal
                    </button>
                    <button id="confirmDelete" type="button"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        Ya, Hapus Jurnal
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
                            <!-- Icon will be set by JavaScript -->
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                id="postModalTitle">
                                <!-- Title will be set by JavaScript -->
                            </h3>
                            <div class="mt-2" id="postModalMessage">
                                <!-- Message will be set by JavaScript -->
                            </div>
                            <div id="postModalWarning">
                                <!-- Warning will be set by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmPostBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <!-- Text will be set by JavaScript -->
                    </button>
                    <button type="button" id="cancelPostBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
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
                let jurnalCurrentSort = '{{ request('sort', 'tanggal') }}';
                let jurnalCurrentDirection = '{{ request('direction', 'desc') }}';
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
                    fetch('{{ route('keuangan.jurnal-penutup.index') }}?' + params.toString(), {
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

                            // Update showing-info safely
                            const showingInfo = safeGetElement('showing-info');
                            if (showingInfo && data.first_item !== undefined && data.last_item !== undefined && data
                                .total !== undefined) {
                                showingInfo.textContent =
                                    `Menampilkan ${data.first_item} hingga ${data.last_item} dari ${data.total} hasil`;
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

                    const startDateInput = safeGetElement('tanggal_awal');
                    const endDateInput = safeGetElement('tanggal_akhir');
                    const periodeSelect = safeGetElement('periode_id');

                    if (startDateInput) startDateInput.value = startDate;
                    if (endDateInput) endDateInput.value = endDate;

                    // Reset other filters when using quick period  
                    if (periodeSelect) periodeSelect.selectedIndex = 0;

                    applyFiltersAndSort(1);
                };

                // Helper function to generate delete route URL
                function getDeleteUrl(noReferensi) {
                    return `{{ url('keuangan/jurnal-penutup') }}/${noReferensi}`;
                }

                // Delete modal functionality
                let jurnalDeleteForm = null;
                const deleteModal = safeGetElement('deleteModal');
                const modalMessage = safeGetElement('modal-message');
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
                            if (modalMessage) {
                                modalMessage.innerHTML = `
                                    <div class="space-y-2">
                                        <p>Apakah Anda yakin ingin menghapus jurnal penutup berikut?</p>
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                            <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                            <div><strong>Tanggal:</strong> ${tanggal}</div>
                                        </div>
                                    </div>
                                `;
                            }

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
                            closePostModal();
                        });
                    }

                    if (postModal) {
                        postModal.addEventListener('click', function(e) {
                            if (e.target === this) {
                                closePostModal();
                            }
                        });
                    }

                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && postModal && !postModal.classList.contains('hidden')) {
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
                        console.error('Modal elements not found');
                        return;
                    }

                    if (action === 'post') {
                        // Post configuration
                        title.textContent = 'Konfirmasi Posting Jurnal Penutup';

                        message.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin memposting jurnal penutup berikut?</p>
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
                                        <strong>Informasi:</strong> Setelah diposting, jurnal penutup ini akan mempengaruhi saldo akun dan tidak dapat diedit lagi.
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
                                <p>Apakah Anda yakin ingin membatalkan posting jurnal penutup berikut?</p>
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
                                        <strong>Perhatian:</strong> Pembatalan posting akan membalik perubahan saldo akun yang telah dilakukan.
                                    </p>
                                </div>
                            </div>
                        `;

                        confirmBtn.className =
                            'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 hover:bg-orange-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                        confirmBtn.textContent = 'Ya, Unpost';
                    }

                    // Show modal
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    confirmBtn.focus();
                }

                function closePostModal() {
                    const modal = safeGetElement('postModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = '';
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

                // Legacy functions for backward compatibility
                window.togglePost = function(noReferensi, isPosted) {
                    const action = isPosted ? 'unpost' : 'post';
                    const route = `{{ url('keuangan/jurnal-penutup') }}/${noReferensi}/toggle-post`;

                    // Create form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = route;

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add to body temporarily
                    document.body.appendChild(form);
                    form.style.display = 'none';

                    showPostModal(action, form, noReferensi);
                };

                window.confirmDelete = function(noReferensi) {
                    // Create delete form
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
                    if (modalMessage) {
                        modalMessage.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin menghapus jurnal penutup berikut?</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                    <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                </div>
                            </div>
                        `;
                    }

                    // Show modal
                    if (deleteModal) {
                        deleteModal.classList.remove('hidden');
                        if (cancelDeleteBtn) cancelDeleteBtn.focus();
                    }
                };

            })(); // End IIFE
        </script>
    @endpush
</x-app-layout>

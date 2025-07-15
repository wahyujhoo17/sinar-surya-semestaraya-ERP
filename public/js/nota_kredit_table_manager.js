function notaKreditTableManager() {
    return {
        isLoading: false,
        search: new URLSearchParams(window.location.search).get('search') || '',
        status: new URLSearchParams(window.location.search).get('status') || 'semua',
        dateStart: new URLSearchParams(window.location.search).get('date_start') || '',
        dateEnd: new URLSearchParams(window.location.search).get('date_end') || '',
        sortField: new URLSearchParams(window.location.search).get('sort') || 'tanggal',
        sortDirection: new URLSearchParams(window.location.search).get('direction') || 'desc',
        tableHtml: '',
        paginationHtml: '',

        init() {
            // Initialize modal functionality for initial content
            this.initializeModals();

            // Listen for popstate (browser back/forward buttons)
            window.addEventListener('popstate', () => {
                const params = new URLSearchParams(window.location.search);
                this.search = params.get('search') || '';
                this.status = params.get('status') || 'semua';
                this.dateStart = params.get('date_start') || '';
                this.dateEnd = params.get('date_end') || '';
                this.sortField = params.get('sort') || 'tanggal';
                this.sortDirection = params.get('direction') || 'desc';
                this.fetchTable();
            });
        },

        // Initialize modal functionality
        initializeModals() {
            this.$nextTick(() => {
                // Handle opening modals
                const deleteButtons = document.querySelectorAll('.btn-delete');
                const deleteName = document.getElementById('delete-name');
                const deleteForm = document.getElementById('delete-form');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        const name = this.getAttribute('data-name');

                        deleteName.textContent = name;
                        deleteForm.action = `/penjualan/nota-kredit/${id}`;
                    });
                });
            });
        },

        resetFilters() {
            this.search = '';
            this.status = 'semua';
            this.dateStart = '';
            this.dateEnd = '';
            this.sortField = 'tanggal';
            this.sortDirection = 'desc';
            this.fetchTable();
        },

        sortBy(field) {
            // If already sorting by this field, toggle direction
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            this.fetchTable();
        },

        handlePaginationClick(event) {
            // Check if the clicked element is a pagination link
            const link = event.target.closest('a[href]');
            if (link && link.getAttribute('href')) {
                event.preventDefault();
                const url = new URL(link.href);
                const page = url.searchParams.get('page');
                if (page) {
                    this.fetchTable(page);
                }
            }
        },

        fetchTable(page = null) {
            this.isLoading = true;

            // Build query parameters
            const params = new URLSearchParams();

            if (this.search) params.set('search', this.search);
            if (this.status !== 'semua') params.set('status', this.status);
            if (this.dateStart) params.set('date_start', this.dateStart);
            if (this.dateEnd) params.set('date_end', this.dateEnd);
            if (this.sortField) params.set('sort', this.sortField);
            if (this.sortDirection) params.set('direction', this.sortDirection);

            // If a specific page is requested
            if (page) params.set('page', page);

            // Update URL
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.pushState({}, '', newUrl);

            fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    this.isLoading = false;
                    document.getElementById('tableBody').innerHTML = data.table;
                    document.getElementById('paginationContainer').innerHTML = data.pagination;

                    // Initialize modals for new content
                    this.initializeModals();

                    // Attach event listeners to pagination links
                    this.attachPaginationListeners();
                })
                .catch(error => {
                    this.isLoading = false;
                    console.error('Error fetching table data:', error);
                    document.getElementById('tableBody').innerHTML = `
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-red-50 dark:bg-red-900/20">
                                        <svg class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-base font-medium text-red-500">Terjadi kesalahan saat memuat data</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan coba muat ulang halaman.</p>
                                    </div>
                                    <button type="button" @click="fetchTable()" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Muat Ulang
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    document.getElementById('paginationContainer').innerHTML = '';
                });
        },

        attachPaginationListeners() {
            this.$nextTick(() => {
                const paginationLinks = document.querySelectorAll('#paginationContainer a[href]');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const url = new URL(link.href);
                        const page = url.searchParams.get('page');
                        this.fetchTable(page);
                    });
                });
            });
        }
    };
}

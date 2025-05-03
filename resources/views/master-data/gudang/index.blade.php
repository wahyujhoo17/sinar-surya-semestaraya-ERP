<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Master Data Gudang</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola data gudang untuk manajemen stok dan persediaan PT Sinar Surya Semestaraya
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {{-- Total Gudang --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v1.5M3 7.5h18m-18 0v10.5A2.25 2.25 0 005.25 20.25h13.5A2.25 2.25 0 0021 18V7.5m-18 0l9 6 9-6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Gudang</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $gudangs->total() }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">gudang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Quick Action Card --}}
            <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-gudang-modal', {detail: {}}))"
                class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                            <p class="mt-1 text-lg font-semibold text-white">Tambah Gudang Baru</p>
                        </div>
                        <div
                            class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/20 group-hover:bg-white/30 text-white transition-all duration-200">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Main Content Area --}}
        <div x-data="gudangTableManager()">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300">
                <div class="p-6 sm:p-7 text-gray-900 dark:text-gray-100">
                    {{-- Header with Quick Search and Action Buttons --}}
                    <div class="mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="mb-4 sm:mb-0">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight">
                                    Daftar Gudang</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua gudang di sini</p>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                                {{-- Bulk Actions --}}
                                <div x-show="selectedGudangs.length > 0" x-transition
                                    class="flex items-center mr-3 bg-white dark:bg-gray-700/60 border border-gray-300 dark:border-gray-600 rounded-lg p-1 shadow-sm">
                                    <span class="px-2 text-sm text-gray-600 dark:text-gray-300"><span
                                            x-text="selectedGudangs.length"></span> item dipilih</span>
                                    <button @click="confirmDeleteSelected"
                                        class="ml-1 px-2 py-1 text-xs text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </span>
                                    </button>
                                </div>
                                {{-- Quick Search --}}
                                <div class="relative w-full sm:w-auto grow sm:grow-0">
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model.debounce.300ms="search"
                                            placeholder="Cari gudang..."
                                            class="pl-10 pr-10 py-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white shadow-sm">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" @click="search = ''; applyFilters()" x-show="search"
                                                x-transition
                                                class="p-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-full">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div id="gudang-table-container" class="relative">
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Menampilkan <span x-text="firstItem"></span> sampai <span x-text="lastItem"></span>
                                    dari <span x-text="totalResults"></span> hasil
                                </div>
                                <div>
                                    <label for="per-page"
                                        class="text-xs text-gray-500 dark:text-gray-400">Tampilkan:</label>
                                    <select id="per-page" x-model="perPage" @change="fetchGudangs()"
                                        class="ml-1 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs border border-gray-300 dark:border-gray-600 rounded py-1 pl-2 pr-6 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                        @foreach ([10, 25, 50, 100] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 w-8">
                                            <input type="checkbox" :checked="allSelected" @click="toggleSelectAll()"
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            <div class="flex items-center">
                                                <span>Kode</span>
                                                <button type="button" @click="sortBy('kode')"
                                                    class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                    {{-- Sort icons logic --}}
                                                </button>
                                            </div>
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            <div class="flex items-center">
                                                <span>Nama</span>
                                                <button type="button" @click="sortBy('nama')"
                                                    class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                    {{-- Sort icons logic --}}
                                                </button>
                                            </div>
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Jenis
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Alamat
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Status
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Jumlah Stok
                                        </th>
                                        <th
                                            class="px-5 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody x-html="tableHtml"
                                    class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800"
                                    x-transition:enter="transition-opacity duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    @include('master-data.gudang._table_body', [
                                        'gudangs' => $gudangs,
                                    ])
                                </tbody>
                            </table>
                        </div>
                        <div id="pagination-container" x-html="paginationHtml"
                            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            {{ $gudangs->links('vendor.pagination.tailwind-custom') }}
                        </div>
                        <div x-show="loading" x-transition
                            class="absolute inset-0 bg-white/70 dark:bg-gray-800/70 flex items-center justify-center z-30">
                            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Delete Form --}}
    <form id="bulk-delete-form" action="{{ route('master.gudang.bulk-destroy') }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <div id="selected-gudangs-container"></div>
    </form>

    {{-- Modal Gudang --}}
    <x-modal-gudang :users="$users ?? []" />

    <script>
        function gudangTableManager() {
            return {
                search: '{{ request('search', '') }}',
                perPage: '{{ request('per_page', 10) }}',
                currentPage: '{{ $gudangs->currentPage() }}',
                sortField: '{{ $sortField ?? 'created_at' }}',
                sortDirection: '{{ $sortDirection ?? 'desc' }}',
                tableHtml: '',
                paginationHtml: '',
                loading: false,
                totalResults: {{ $gudangs->total() }},
                firstItem: {{ $gudangs->firstItem() ?? 0 }},
                lastItem: {{ $gudangs->lastItem() ?? 0 }},
                selectedGudangs: [],
                allSelected: false,

                init() {
                    this.tableHtml = document.querySelector('#gudang-table-container tbody').innerHTML;
                    this.paginationHtml = document.querySelector('#pagination-container').innerHTML;

                    this.$watch('paginationHtml', () => setTimeout(() => this.attachPaginationListener(), 50));
                    this.$watch('search', value => {
                        this.currentPage = 1;
                        this.fetchGudangs();
                    });
                    this.$watch('selectedGudangs', value => {
                        const checkboxes = document.querySelectorAll('input[name="gudang_ids[]"]');
                        this.allSelected = checkboxes.length > 0 && value.length === checkboxes.length;
                    });

                    setTimeout(() => this.attachPaginationListener(), 50);

                    window.addEventListener('refresh-gudang-table', () => this.fetchGudangs());
                    this.fetchGudangs();
                },

                toggleSelectAll() {
                    const checkboxes = document.querySelectorAll('input[name="gudang_ids[]"]');
                    if (this.allSelected) {
                        this.selectedGudangs = [];
                    } else {
                        this.selectedGudangs = Array.from(checkboxes).map(cb => cb.value);
                    }
                    this.allSelected = !this.allSelected;
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.allSelected;
                    });
                },

                confirmDeleteSelected() {
                    if (this.selectedGudangs.length === 0) {
                        notify('Silakan pilih minimal 1 gudang untuk dihapus.', 'warning', 'Peringatan');
                        return;
                    }
                    confirmDelete(
                        `Apakah Anda yakin ingin menghapus <strong>${this.selectedGudangs.length}</strong> gudang yang dipilih?`,
                        () => {
                            const container = document.getElementById('selected-gudangs-container');
                            container.innerHTML = '';
                            this.selectedGudangs.forEach(id => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'ids[]';
                                input.value = id;
                                container.appendChild(input);
                            });
                            document.getElementById('bulk-delete-form').submit();
                        });
                },

                attachPaginationListener() {
                    const container = document.getElementById('pagination-container');
                    if (!container) return;
                    container.querySelectorAll('a').forEach(link => {
                        link.removeEventListener('click', this.handlePaginationClick);
                        link.addEventListener('click', (event) => {
                            event.preventDefault();
                            const url = new URL(event.currentTarget.href);
                            this.currentPage = url.searchParams.get('page') || 1;
                            this.fetchGudangs();
                        });
                    });
                },

                buildQueryString() {
                    const params = new URLSearchParams();
                    params.append('page', this.currentPage);
                    params.append('per_page', this.perPage);
                    if (this.search) params.append('search', this.search);
                    if (this.sortField) params.append('sort', this.sortField);
                    if (this.sortDirection) params.append('direction', this.sortDirection);
                    return params.toString();
                },

                fetchGudangs() {
                    this.loading = true;
                    const queryString = this.buildQueryString();
                    const url = `{{ route('master.gudang.index') }}?${queryString}`;
                    setTimeout(() => {
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error(`Network response error: ${response.status}`);
                                return response.json();
                            })
                            .then(data => {
                                if (!data || !data.success) throw new Error(data?.message ||
                                    'Terjadi kesalahan saat memproses data');
                                this.tableHtml = data.table_html;
                                this.paginationHtml = data.pagination_html;
                                this.totalResults = data.total_results;
                                this.firstItem = data.first_item || 0;
                                this.lastItem = data.last_item || 0;
                                this.loading = false;
                                this.selectedGudangs = [];
                                this.allSelected = false;
                            })
                            .catch(error => {
                                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                                this.loading = false;
                            });
                    }, 50);
                },

                applyFilters() {
                    this.currentPage = 1;
                    this.fetchGudangs();
                },

                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                    this.currentPage = 1;
                    this.fetchGudangs();
                },

                updateSelectedGudangs(event, id) {
                    if (event.target.checked) {
                        if (!this.selectedGudangs.includes(id)) {
                            this.selectedGudangs.push(id);
                        }
                    } else {
                        this.selectedGudangs = this.selectedGudangs.filter(gudangId => gudangId !== id);
                    }
                    const checkboxes = document.querySelectorAll('input[name="gudang_ids[]"]');
                    this.allSelected = checkboxes.length > 0 && this.selectedGudangs.length === checkboxes.length;
                }
            }
        }

        function openEditGudangModal(id) {
            fetch(`/master-data/gudang/${id}/get`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.gudang) {
                        window.dispatchEvent(new CustomEvent('open-gudang-modal', {
                            detail: {
                                mode: 'edit',
                                gudang: data.gudang
                            }
                        }));
                    } else {
                        notify('Gagal memuat data gudang', 'error', 'Error');
                    }
                })
                .catch(error => {
                    notify('Terjadi kesalahan saat memuat data', 'error', 'Error');
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_modal') === 'create') {
                window.dispatchEvent(new CustomEvent('open-gudang-modal', {
                    detail: {}
                }));
                const newUrl = window.location.pathname;
                history.replaceState({}, document.title, newUrl);
            }
        });
    </script>
</x-app-layout>

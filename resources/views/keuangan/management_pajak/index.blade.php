<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        @include('keuangan.management_pajak.partials.alerts')

        {{-- Modern Header with Stats --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Management Pajak
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola laporan pajak, PPN, dan kewajiban perpajakan perusahaan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Generate Auto Report Button --}}
                    <button type="button" onclick="openAutoReportModal()"
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan Otomatis
                    </button>

                    {{-- Add New Tax Record Button --}}
                    <a href="{{ route('keuangan.management-pajak.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Laporan Pajak
                    </a>
                </div>
            </div>

            {{-- Dashboard Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                {{-- PPN Keluaran Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-3.5">
                                <svg class="h-7 w-7 text-green-500 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    PPN Keluaran</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($totalPpnKeluaran ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PPN Masukan Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                                <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    PPN Masukan</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($totalPpnMasukan ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PPN Terutang Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-orange-100 dark:bg-orange-900/30 p-3.5">
                                <svg class="h-7 w-7 text-orange-500 dark:text-orange-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    PPN Terutang</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($ppnTerutang ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current Month Stats Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-purple-100 dark:bg-purple-900/30 p-3.5">
                                <svg class="h-7 w-7 text-purple-500 dark:text-purple-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Laporan Bulan Ini</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $currentMonthStats['laporan_count'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters & Search Section --}}
        <div class="mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50">
                <div class="p-6">
                    <form method="GET" action="{{ route('keuangan.management-pajak.index') }}" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            {{-- Jenis Pajak Filter --}}
                            <div>
                                <label for="jenis"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jenis Pajak
                                </label>
                                <select name="jenis" id="jenis"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Semua Jenis</option>
                                    <option value="ppn_keluaran"
                                        {{ request('jenis') === 'ppn_keluaran' ? 'selected' : '' }}>PPN Keluaran
                                    </option>
                                    <option value="ppn_masukan"
                                        {{ request('jenis') === 'ppn_masukan' ? 'selected' : '' }}>PPN Masukan</option>
                                    <option value="pph21" {{ request('jenis') === 'pph21' ? 'selected' : '' }}>PPh 21
                                    </option>
                                    <option value="pph23" {{ request('jenis') === 'pph23' ? 'selected' : '' }}>PPh 23
                                    </option>
                                    <option value="pph4_ayat2"
                                        {{ request('jenis') === 'pph4_ayat2' ? 'selected' : '' }}>PPh 4 Ayat 2</option>
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status
                                </label>
                                <select name="status" id="status"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="final" {{ request('status') === 'final' ? 'selected' : '' }}>Final
                                    </option>
                                </select>
                            </div>

                            {{-- Start Date Filter --}}
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            {{-- End Date Filter --}}
                            <div>
                                <label for="end_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ request('end_date') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            {{-- Search --}}
                            <div>
                                <label for="search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Cari
                                </label>
                                <div class="relative">
                                    <input type="text" name="search" id="search"
                                        placeholder="Nomor atau keterangan..." value="{{ request('search') }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 pl-10">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('keuangan.management-pajak.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 border border-transparent rounded-lg font-medium text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest focus:bg-gray-400 dark:focus:bg-gray-500 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <div id="table-container">
                    @include('keuangan.management_pajak.partials.table')
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $laporanPajaks->links() }}
        </div>
    </div>

    {{-- Auto Report Modal --}}
    <div id="autoReportModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Generate Laporan Otomatis</h3>
                    <button type="button" onclick="closeAutoReportModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="autoReportForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="auto_jenis"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jenis Pajak
                            </label>
                            <select name="jenis_pajak" id="auto_jenis" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Jenis Pajak</option>
                                <option value="ppn_keluaran">PPN Keluaran</option>
                                <option value="ppn_masukan">PPN Masukan</option>
                            </select>
                        </div>

                        <div>
                            <label for="auto_periode_awal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Periode Awal
                            </label>
                            <input type="date" name="periode_awal" id="auto_periode_awal" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <div>
                            <label for="auto_periode_akhir"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Periode Akhir
                            </label>
                            <input type="date" name="periode_akhir" id="auto_periode_akhir" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAutoReportModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 rounded-lg font-medium text-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm">
                            <span class="auto-report-btn-text">Generate</span>
                            <span class="auto-report-btn-loading hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAutoReportModal() {
            document.getElementById('autoReportModal').classList.remove('hidden');
        }

        function closeAutoReportModal() {
            document.getElementById('autoReportModal').classList.add('hidden');
            document.getElementById('autoReportForm').reset();
        }

        // Auto Report Form Submission
        document.getElementById('autoReportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.auto-report-btn-text');
            const btnLoading = submitBtn.querySelector('.auto-report-btn-loading');

            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            fetch('{{ route('keuangan.management-pajak.auto-report') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('Laporan pajak otomatis berhasil dibuat!');

                        // Redirect to the created report
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            // Refresh the page
                            window.location.reload();
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat membuat laporan pajak.');
                })
                .finally(() => {
                    // Reset loading state
                    submitBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                    closeAutoReportModal();
                });
        });

        // Auto-submit form when filters change
        document.querySelectorAll('#filterForm select, #filterForm input[type="date"]').forEach(element => {
            element.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Close modal when clicking outside
        document.getElementById('autoReportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAutoReportModal();
            }
        });
    </script>
</x-app-layout>

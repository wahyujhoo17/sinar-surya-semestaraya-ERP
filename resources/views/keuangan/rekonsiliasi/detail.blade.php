<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="Detail Rekonsiliasi">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl mb-8 border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Detail Rekonsiliasi Bank
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            {{ $reconciliation->rekeningBank->nama_bank }} -
                            {{ $reconciliation->rekeningBank->nomor_rekening }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $reconciliation->status_badge }}">
                            {{ $reconciliation->status }}
                        </span>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ $reconciliation->periode_formatted }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo ERP</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($reconciliation->erp_balance, 0, ',', '.') }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-3">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Bank Statement</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($reconciliation->bank_balance, 0, ',', '.') }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3 {{ $reconciliation->isBalanced() ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                            <svg class="h-6 w-6 {{ $reconciliation->isBalanced() ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Selisih</dt>
                            <dd
                                class="text-lg font-semibold {{ $reconciliation->isBalanced() ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                Rp {{ number_format(abs($reconciliation->difference), 0, ',', '.') }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Matched Transactions --}}
        @if ($reconciliation->matched_transactions && count($reconciliation->matched_transactions) > 0)
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-xl mb-8 border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Transaksi Cocok ({{ count($reconciliation->matched_transactions) }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach ($reconciliation->matched_transactions as $transaction)
                            <div
                                class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction['description'] ?? 'N/A' }}</span>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $transaction['date'] ?? 'N/A' }} • {{ $transaction['reference'] ?? 'N/A' }}
                                    </div>
                                </div>
                                <span
                                    class="text-sm font-semibold {{ ($transaction['type'] ?? 'debit') === 'debit' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    {{ ($transaction['type'] ?? 'debit') === 'debit' ? '-' : '+' }}Rp
                                    {{ number_format($transaction['amount'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Unmatched Transactions --}}
        @if (
            ($reconciliation->unmatched_erp_transactions && count($reconciliation->unmatched_erp_transactions) > 0) ||
                ($reconciliation->unmatched_bank_transactions && count($reconciliation->unmatched_bank_transactions) > 0))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- Unmatched ERP --}}
                @if ($reconciliation->unmatched_erp_transactions && count($reconciliation->unmatched_erp_transactions) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                ERP Tidak Cocok ({{ count($reconciliation->unmatched_erp_transactions) }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach ($reconciliation->unmatched_erp_transactions as $transaction)
                                    <div
                                        class="flex items-center justify-between p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded">
                                        <span
                                            class="text-sm text-gray-900 dark:text-white">{{ $transaction['description'] ?? 'N/A' }}</span>
                                        <span class="text-sm font-medium text-red-600 dark:text-red-400">
                                            Rp {{ number_format($transaction['amount'] ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Unmatched Bank --}}
                @if ($reconciliation->unmatched_bank_transactions && count($reconciliation->unmatched_bank_transactions) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Bank Tidak Cocok ({{ count($reconciliation->unmatched_bank_transactions) }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach ($reconciliation->unmatched_bank_transactions as $transaction)
                                    <div
                                        class="flex items-center justify-between p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded">
                                        <span
                                            class="text-sm text-gray-900 dark:text-white">{{ $transaction['description'] ?? 'N/A' }}</span>
                                        <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                                            Rp {{ number_format($transaction['amount'] ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Metadata --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Rekonsiliasi</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Tanggal</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $reconciliation->created_at->format('d F Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $reconciliation->user->name ?? 'System' }}</dd>
                    </div>
                    @if ($reconciliation->approved_by)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Disetujui Tanggal</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $reconciliation->approved_at?->format('d F Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Disetujui Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $reconciliation->approver->name ?? 'N/A' }}</dd>
                        </div>
                    @endif
                    @if ($reconciliation->notes)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reconciliation->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex justify-between">
            <a href="{{ route('keuangan.rekonsiliasi.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>

            @if ($reconciliation->status === 'Pending')
                <div class="space-x-3">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                        <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Setujui
                    </button>
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                        <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

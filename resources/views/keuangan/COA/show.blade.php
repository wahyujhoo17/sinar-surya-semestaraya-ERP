<x-app-layout>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Akun</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Informasi lengkap akun {{ $akun->nama }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.coa.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('keuangan.coa.edit', $akun->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <!-- Account Information -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Akun</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Akun</h3>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $akun->kode }}
                            </p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Akun</h3>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $akun->nama }}
                            </p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</h3>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                @switch($akun->kategori)
                                    @case('asset')
                                        Aset
                                    @break

                                    @case('liability')
                                        Kewajiban
                                    @break

                                    @case('equity')
                                        Ekuitas
                                    @break

                                    @case('income')
                                        Pendapatan
                                    @break

                                    @case('expense')
                                        Beban
                                    @break

                                    @default
                                        Lainnya
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</h3>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $akun->tipe === 'header' ? 'Header (Grup)' : 'Detail (Transaksi)' }}
                            </p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Akun Induk</h3>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                @if ($akun->parent)
                                    {{ $akun->parent->kode }} - {{ $akun->parent->nama }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Tidak ada (Akun Level Atas)</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                            <p class="mt-1">
                                @if ($akun->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </p>
                        </div>

                        @if (isset($referenceInfo))
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Referensi</h3>
                                <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $referenceInfo['type'] }}:
                                    @if ($referenceInfo['type'] == 'Kas')
                                        {{ $referenceInfo['data']->nama }}
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            (Saldo: Rp {{ number_format($referenceInfo['data']->saldo, 0, ',', '.') }})
                                        </span>
                                    @else
                                        {{ $referenceInfo['data']->nama_bank }} -
                                        {{ $referenceInfo['data']->nomor_rekening }}
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ $referenceInfo['data']->atas_nama }})
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ringkasan Keuangan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Debit</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kredit</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo</h3>
                        <p
                            class="mt-1 text-lg font-semibold {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            Rp {{ number_format(abs($balance), 0, ',', '.') }}
                            @if (in_array($akun->kategori, ['asset', 'expense']))
                                {{-- Asset/Expense: positive = Debit (normal), negative = Kredit (abnormal) --}}
                                {{ $balance >= 0 ? '(Debit)' : '(Kredit)' }}
                            @else
                                {{-- Liability/Equity/Income: positive = Kredit (normal), negative = Debit (abnormal) --}}
                                {{ $balance >= 0 ? '(Kredit)' : '(Debit)' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sub Accounts -->
            @if ($akun->children->count() > 0)
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Sub Akun</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tipe
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($akun->children as $childAccount)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $childAccount->kode }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $childAccount->nama }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $childAccount->tipe === 'header' ? 'Header (Grup)' : 'Detail (Transaksi)' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            @if ($childAccount->is_active)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('keuangan.coa.show', $childAccount->id) }}"
                                                class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400 mr-3">
                                                Lihat
                                            </a>
                                            <a href="{{ route('keuangan.coa.edit', $childAccount->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-500 dark:hover:text-indigo-400">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Latest Transactions -->
            @if ($akun->jurnalEntries->count() > 0)
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                            Transaksi Terbaru
                            <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                                (Menampilkan 5 dari {{ $akun->jurnalEntries->count() }} transaksi)
                            </span>
                        </h2>
                        @if ($akun->jurnalEntries->count() > 5)
                            <a href="{{ route('keuangan.jurnal-umum.index', ['akun_id' => $akun->id]) }}"
                                class="text-sm text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400 font-medium">
                                Lihat Semua →
                            </a>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Referensi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Debit
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kredit
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($akun->jurnalEntries->take(5) as $entry)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $entry->tanggal->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $entry->referensi_display }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $entry->keterangan ?? '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700 dark:text-gray-300">
                                            @if ($entry->debit > 0)
                                                Rp {{ number_format($entry->debit, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700 dark:text-gray-300">
                                            @if ($entry->kredit > 0)
                                                Rp {{ number_format($entry->kredit, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

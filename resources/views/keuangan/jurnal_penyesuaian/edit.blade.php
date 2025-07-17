@php
    $categoryLabels = [
        'aset' => 'ASET',
        'aset_lancar' => 'ASET LANCAR',
        'aset_tetap' => 'ASET TETAP',
        'hutang' => 'HUTANG',
        'hutang_lancar' => 'HUTANG LANCAR',
        'hutang_jangka_panjang' => 'HUTANG JANGKA PANJANG',
        'modal' => 'MODAL',
        'pendapatan' => 'PENDAPATAN',
        'beban' => 'BEBAN',
        'beban_operasional' => 'BEBAN OPERASIONAL',
        'beban_non_operasional' => 'BEBAN NON OPERASIONAL',
    ];
@endphp

<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Edit Jurnal Penyesuaian'">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Jurnal Penyesuaian
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Edit entri jurnal penyesuaian: {{ $jurnal->no_referensi }}
                </p>
            </div>

            <form action="{{ route('keuangan.jurnal-penyesuaian.update', $jurnal->no_referensi) }}" method="POST"
                x-data="editJournalForm()">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div
                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        Terdapat kesalahan dalam form
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Basic Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" required
                                value="{{ old('tanggal', \Carbon\Carbon::parse($jurnal->tanggal_jurnal)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="no_referensi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                No. Referensi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_referensi" id="no_referensi" required readonly
                                value="{{ old('no_referensi', $jurnal->no_referensi) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="ADJ-202501-0001">
                            @error('no_referensi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status Balance
                            </label>
                            <!-- Status Balance -->
                            <div class="mt-1 p-3 rounded border"
                                style="background-color: #eff6ff; border-color: #93c5fd; color: #1e40af;">
                                <div class="flex items-center justify-between">
                                    <span id="balance-status">○ Checking...</span>
                                    <span class="text-sm" id="balance-totals">Debit: Rp 0,00 | Kredit: Rp 0,00</span>
                                </div>
                                <div id="balance-difference" class="text-sm mt-2 pt-2"
                                    style="border-top: 1px solid rgba(0,0,0,0.2); display: none;">
                                    Selisih: <span class="font-bold" id="difference-amount">Rp 0,00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Deskripsi jurnal penyesuaian...">{{ old('keterangan', $jurnal->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Journal Entries --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Entri Jurnal</h3>
                            <button type="button" @click="addEntry()"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Entri
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Akun
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Debit
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kredit
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(entry, index) in entries" :key="index">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select :name="'entries[' + index + '][akun_id]'" required
                                                    x-model="entry.akun_id"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="">Pilih Akun</option>
                                                    @foreach ($akunList->groupBy('kategori') as $kategori => $akunGroup)
                                                        <optgroup
                                                            label="{{ $categoryLabels[$kategori] ?? strtoupper($kategori) }}">
                                                            @foreach ($akunGroup as $akun)
                                                                <option value="{{ $akun->id }}">
                                                                    {{ $akun->kode }}
                                                                    - {{ $akun->nama }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" :name="'entries[' + index + '][keterangan]'"
                                                    x-model="entry.keterangan"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                    placeholder="Keterangan entri...">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" :name="'entries[' + index + '][debit]'"
                                                    step="0.01" min="0" x-model.number="entry.debit"
                                                    @input="if(entry.debit > 0) entry.kredit = 0; calculateTotals()"
                                                    oninput="recalculateFromInput()"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right"
                                                    placeholder="0.00">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" :name="'entries[' + index + '][kredit]'"
                                                    step="0.01" min="0" x-model.number="entry.kredit"
                                                    @input="if(entry.kredit > 0) entry.debit = 0; calculateTotals()"
                                                    oninput="recalculateFromInput()"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right"
                                                    placeholder="0.00">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button type="button" @click="removeEntry(index)"
                                                    x-show="entries.length > 2"
                                                    class="inline-flex items-center p-1 border border-transparent rounded-full text-red-600 hover:bg-red-100 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th colspan="2"
                                            class="px-6 py-3 text-left text-sm font-medium text-gray-900 dark:text-white">
                                            Total
                                        </th>
                                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="'Rp ' + totalDebit.toLocaleString('id-ID', {minimumFractionDigits: 2})">
                                        </th>
                                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="'Rp ' + totalKredit.toLocaleString('id-ID', {minimumFractionDigits: 2})">
                                        </th>
                                        <th class="px-6 py-3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Balance Info --}}
                    <div class="rounded-md p-4"
                        :class="isBalanced ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' :
                            'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800'">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg x-show="isBalanced" class="h-5 w-5 text-green-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg x-show="!isBalanced" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium"
                                    :class="isBalanced ? 'text-green-800 dark:text-green-200' :
                                        'text-yellow-800 dark:text-yellow-200'">
                                    <span x-text="isBalanced ? 'Jurnal Seimbang' : 'Jurnal Tidak Seimbang'"></span>
                                </h3>
                                <div class="mt-2 text-sm"
                                    :class="isBalanced ? 'text-green-700 dark:text-green-300' :
                                        'text-yellow-700 dark:text-yellow-300'">
                                    <p x-show="isBalanced">
                                        Total debit dan kredit sudah seimbang. Jurnal siap untuk disimpan.
                                    </p>
                                    <p x-show="!isBalanced">
                                        Total debit dan kredit harus seimbang sebelum menyimpan jurnal.
                                        Selisih: <span
                                            x-text="'Rp ' + Math.abs(totalDebit - totalKredit).toLocaleString('id-ID', {minimumFractionDigits: 2})"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                    <a href="{{ route('keuangan.jurnal-penyesuaian.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit" :disabled="!isBalanced || totalDebit === 0"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Update Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Status Balance Management
        let globalTotalDebit = 0;
        let globalTotalKredit = 0;
        let globalIsBalanced = false;

        function recalculateFromInput() {
            setTimeout(() => {
                let totalDebit = 0;
                let totalKredit = 0;

                const debitInputs = document.querySelectorAll('input[name*="[debit]"]');
                debitInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    totalDebit += value;
                });

                const kreditInputs = document.querySelectorAll('input[name*="[kredit]"]');
                kreditInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    totalKredit += value;
                });

                globalTotalDebit = totalDebit;
                globalTotalKredit = totalKredit;
                globalIsBalanced = totalDebit === totalKredit && totalDebit > 0;

                updateStatusBalance();
            }, 100);
        }

        function updateStatusBalance() {
            const statusElement = document.getElementById('balance-status');
            const totalsElement = document.getElementById('balance-totals');
            const differenceElement = document.getElementById('balance-difference');
            const differenceAmountElement = document.getElementById('difference-amount');
            const containerElement = statusElement?.parentElement?.parentElement;

            if (statusElement && totalsElement && containerElement) {
                if (globalIsBalanced) {
                    statusElement.textContent = '✓ Balanced';
                    statusElement.className = 'font-bold';
                    containerElement.style.backgroundColor = '#f0fdf4';
                    containerElement.style.borderColor = '#86efac';
                    containerElement.style.color = '#166534';
                } else if (globalTotalDebit > 0 || globalTotalKredit > 0) {
                    statusElement.textContent = '✗ Not Balanced';
                    statusElement.className = 'font-bold';
                    containerElement.style.backgroundColor = '#fef2f2';
                    containerElement.style.borderColor = '#fca5a5';
                    containerElement.style.color = '#dc2626';
                } else {
                    statusElement.textContent = '○ Menunggu input';
                    statusElement.className = 'font-medium';
                    containerElement.style.backgroundColor = '#eff6ff';
                    containerElement.style.borderColor = '#93c5fd';
                    containerElement.style.color = '#1e40af';
                }

                const formatCurrency = (amount) => {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(amount || 0);
                };

                totalsElement.textContent =
                    `Debit: Rp ${formatCurrency(globalTotalDebit)} | Kredit: Rp ${formatCurrency(globalTotalKredit)}`;

                if (!globalIsBalanced && (globalTotalDebit > 0 || globalTotalKredit > 0)) {
                    const difference = Math.abs(globalTotalDebit - globalTotalKredit);
                    differenceAmountElement.textContent = `Rp ${formatCurrency(difference)}`;
                    differenceElement.style.display = 'block';
                } else {
                    differenceElement.style.display = 'none';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const initialData = {!! json_encode(
                $jurnal->details->map(function ($detail) {
                        return [
                            'debit' => $detail->debit > 0 ? floatval($detail->debit) : 0,
                            'kredit' => $detail->kredit > 0 ? floatval($detail->kredit) : 0,
                        ];
                    })->values(),
            ) !!};

            globalTotalDebit = initialData.reduce((sum, entry) => sum + (entry.debit || 0), 0);
            globalTotalKredit = initialData.reduce((sum, entry) => sum + (entry.kredit || 0), 0);
            globalIsBalanced = globalTotalDebit === globalTotalKredit && globalTotalDebit > 0;

            updateStatusBalance();
        });

        function editJournalForm() {
            return {
                entries: {!! json_encode(
                    $jurnal->details->map(function ($detail) {
                            return [
                                'akun_id' => $detail->akun_id,
                                'keterangan' => $detail->keterangan,
                                'debit' => $detail->debit > 0 ? floatval($detail->debit) : 0,
                                'kredit' => $detail->kredit > 0 ? floatval($detail->kredit) : 0,
                            ];
                        })->values(),
                ) !!},
                totalDebit: 0,
                totalKredit: 0,
                isBalanced: false,

                init() {
                    this.calculateTotals();
                },

                addEntry() {
                    this.entries.push({
                        akun_id: '',
                        keterangan: '',
                        debit: 0,
                        kredit: 0
                    });
                    this.calculateTotals();
                },

                removeEntry(index) {
                    if (this.entries.length > 2) {
                        this.entries.splice(index, 1);
                        this.calculateTotals();
                    }
                },

                calculateTotals() {
                    this.totalDebit = this.entries.reduce((sum, entry) => {
                        const debit = parseFloat(entry.debit) || 0;
                        return sum + debit;
                    }, 0);

                    this.totalKredit = this.entries.reduce((sum, entry) => {
                        const kredit = parseFloat(entry.kredit) || 0;
                        return sum + kredit;
                    }, 0);

                    this.isBalanced = this.totalDebit === this.totalKredit && this.totalDebit > 0;

                    globalTotalDebit = this.totalDebit;
                    globalTotalKredit = this.totalKredit;
                    globalIsBalanced = this.isBalanced;

                    updateStatusBalance();
                }
            }
        }
    </script>
</x-app-layout>

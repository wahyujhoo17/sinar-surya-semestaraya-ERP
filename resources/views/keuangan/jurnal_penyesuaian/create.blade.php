<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Buat Jurnal Penyesuaian'">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Buat Jurnal Penyesuaian Baru
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Buat entri jurnal penyesuaian untuk koreksi atau penyesuaian akhir periode
                </p>
            </div>

            <form action="{{ route('keuangan.jurnal-penyesuaian.store') }}" method="POST" x-data="journalForm()">
                @csrf
                <div class="p-6 space-y-6">
                    {{-- Basic Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" required
                                value="{{ old('tanggal', date('Y-m-d')) }}"
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
                            <input type="text" name="no_referensi" id="no_referensi" required
                                value="{{ old('no_referensi', $nextRefNumber) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                    <span id="balance-status">○ Menunggu input</span>
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
                            placeholder="Deskripsi jurnal penyesuaian...">{{ old('keterangan') }}</textarea>
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
                                                    @foreach ($akuns->groupBy('kategori') as $kategori => $akunGroup)
                                                        <optgroup
                                                            label="{{ $categoryLabels[$kategori] ?? strtoupper($kategori) }}">
                                                            @foreach ($akunGroup as $akun)
                                                                <option value="{{ $akun->id }}">{{ $akun->kode }}
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
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
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
                                            class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                            Total:
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                            <span x-text="'Rp ' + totalDebit.toLocaleString('id-ID')"></span>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                            <span x-text="'Rp ' + totalKredit.toLocaleString('id-ID')"></span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @error('entries')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('balance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('keuangan.jurnal-penyesuaian.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit" :disabled="!isBalanced || entries.length < 2"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Jurnal Penyesuaian
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Wrap in IIFE to avoid global scope conflicts
            (function() {
                'use strict';

                // Status Balance Management for Create Page
                let _jurnalPenyesuaianCreate_totalDebit = 0;
                let _jurnalPenyesuaianCreate_totalKredit = 0;
                let _jurnalPenyesuaianCreate_isBalanced = false;

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

                        _jurnalPenyesuaianCreate_totalDebit = totalDebit;
                        _jurnalPenyesuaianCreate_totalKredit = totalKredit;
                        _jurnalPenyesuaianCreate_isBalanced = totalDebit === totalKredit && totalDebit > 0;

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
                        if (_jurnalPenyesuaianCreate_isBalanced) {
                            statusElement.textContent = '✓ Balanced';
                            statusElement.className = 'font-bold';
                            containerElement.style.backgroundColor = '#f0fdf4';
                            containerElement.style.borderColor = '#86efac';
                            containerElement.style.color = '#166534';
                        } else if (_jurnalPenyesuaianCreate_totalDebit > 0 || _jurnalPenyesuaianCreate_totalKredit > 0) {
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
                            `Debit: Rp ${formatCurrency(_jurnalPenyesuaianCreate_totalDebit)} | Kredit: Rp ${formatCurrency(_jurnalPenyesuaianCreate_totalKredit)}`;

                        if (!_jurnalPenyesuaianCreate_isBalanced && (_jurnalPenyesuaianCreate_totalDebit > 0 ||
                                _jurnalPenyesuaianCreate_totalKredit > 0)) {
                            const difference = Math.abs(_jurnalPenyesuaianCreate_totalDebit -
                                _jurnalPenyesuaianCreate_totalKredit);
                            differenceAmountElement.textContent = `Rp ${formatCurrency(difference)}`;
                            differenceElement.style.display = 'block';
                        } else {
                            differenceElement.style.display = 'none';
                        }
                    }
                }

                // Expose functions to global scope
                window.recalculateFromInput = recalculateFromInput;
                window.updateStatusBalance = updateStatusBalance;

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    updateStatusBalance();
                });

                window.journalForm = function() {
                    return {
                        entries: [{
                                akun_id: '',
                                debit: 0,
                                kredit: 0,
                                keterangan: ''
                            },
                            {
                                akun_id: '',
                                debit: 0,
                                kredit: 0,
                                keterangan: ''
                            }
                        ],
                        totalDebit: 0,
                        totalKredit: 0,
                        isBalanced: false,

                        init() {
                            this.calculateTotals();
                        },

                        addEntry() {
                            this.entries.push({
                                akun_id: '',
                                debit: 0,
                                kredit: 0,
                                keterangan: ''
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

                            _jurnalPenyesuaianCreate_totalDebit = this.totalDebit;
                            _jurnalPenyesuaianCreate_totalKredit = this.totalKredit;
                            _jurnalPenyesuaianCreate_isBalanced = this.isBalanced;

                            updateStatusBalance();
                        }
                    }
                };
            })();
        </script>
    @endpush
</x-app-layout>

<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Edit Jurnal Penutup'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-yellow-600 dark:text-yellow-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Jurnal Penutup
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $jurnalPenutup->no_referensi }} -
                    {{ \Carbon\Carbon::parse($jurnalPenutup->tanggal)->format('d F Y') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 space-x-2">
                <a href="{{ route('keuangan.jurnal-penutup.show', $jurnalPenutup->no_referensi) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('keuangan.jurnal-penutup.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('keuangan.jurnal-penutup.update', $jurnalPenutup->no_referensi) }}" method="POST"
            id="jurnalForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Form --}}
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Jurnal</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Basic Info --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ old('tanggal', \Carbon\Carbon::parse($jurnalPenutup->tanggal)->format('Y-m-d')) }}"
                                        required
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500 @error('tanggal') !border-red-500 @enderror">
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="periode_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Periode Akuntansi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="periode_id" id="periode_id" required
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500 @error('periode_id') !border-red-500 @enderror">
                                        <option value="">Pilih Periode</option>
                                        @foreach ($periods as $periode)
                                            <option value="{{ $periode->id }}"
                                                {{ old('periode_id', $jurnalPenutup->periode_id) == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->nama ?? $periode->tahun }}
                                                ({{ ucfirst($periode->status) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('periode_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Keterangan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="keterangan" id="keterangan" rows="3" required
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500 @error('keterangan') !border-red-500 @enderror"
                                    placeholder="Keterangan jurnal penutup...">{{ old('keterangan', $jurnalPenutup->keterangan) }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Journal Details --}}
                    <div
                        class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Jurnal</h3>
                            <button type="button" onclick="addJournalEntry()"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Baris
                            </button>
                        </div>

                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <th
                                                class="text-left py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Akun</th>
                                            <th
                                                class="text-left py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Keterangan</th>
                                            <th
                                                class="text-right py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Debit</th>
                                            <th
                                                class="text-right py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Kredit</th>
                                            <th
                                                class="text-center py-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="journalEntries">
                                        <!-- Existing journal entries will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            {{-- Balance Summary --}}
                            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Total Debit:</span>
                                        <span id="totalDebit"
                                            class="font-medium text-gray-900 dark:text-gray-100 ml-2">Rp 0</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Total Kredit:</span>
                                        <span id="totalKredit"
                                            class="font-medium text-gray-900 dark:text-gray-100 ml-2">Rp 0</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Balance:</span>
                                        <span id="balance" class="font-medium ml-2">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aksi</h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Jurnal
                            </button>

                            <a href="{{ route('keuangan.jurnal-penutup.show', $jurnalPenutup->no_referensi) }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Batal
                            </a>
                        </div>

                        {{-- Help Text --}}
                        <div class="px-6 pb-6">
                            <div
                                class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100 mb-2">Tips Edit
                                    Jurnal</h4>
                                <ul class="text-xs text-yellow-800 dark:text-yellow-200 space-y-1">
                                    <li>• Hanya jurnal yang belum diposting yang dapat diedit</li>
                                    <li>• Pastikan total debit = total kredit</li>
                                    <li>• Pilih akun yang sesuai dengan jenis jurnal penutup</li>
                                    <li>• Keterangan harus jelas dan deskriptif</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Account Search Modal --}}
    <div id="accountModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pilih Akun</h3>
                    <button onclick="closeAccountModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <input type="text" id="accountSearch" placeholder="Cari akun..."
                    class="w-full mb-4 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500">

                <div id="accountList" class="max-h-96 overflow-y-auto">
                    @foreach ($accounts as $account)
                        <div class="account-item p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700"
                            data-id="{{ $account->id }}" data-code="{{ $account->kode }}"
                            data-name="{{ $account->nama }}">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $account->kode }} -
                                {{ $account->nama }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $account->kategori }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        let entryIndex = 0;
        let currentAccountInput = null;

        // Existing journal entries data
        const existingEntries = [
            @foreach ($jurnalPenutup->details as $detail)
                {
                    'akun_id': {{ $detail->akun_id }},
                    'akun_display': '{{ $detail->akun->kode ?? 'N/A' }} - {{ $detail->akun->nama ?? 'Akun tidak ditemukan' }}',
                    'keterangan': '{{ addslashes($detail->keterangan) }}',
                    'debit': {{ $detail->debit }},
                    'kredit': {{ $detail->kredit }}
                }
                {{ !$loop->last ? ',' : '' }}
            @endforeach
        ];

        // Load existing entries
        document.addEventListener('DOMContentLoaded', function() {
            if (existingEntries.length > 0) {
                existingEntries.forEach(entry => {
                    addJournalEntry(entry);
                });
            } else {
                // Add two empty entries if no existing entries
                addJournalEntry();
                addJournalEntry();
            }
            updateBalance();
        });

        function addJournalEntry(entryData = null) {
            const tbody = document.getElementById('journalEntries');
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-200 dark:border-gray-700';

            const debitValue = entryData ? entryData.debit : '';
            const kreditValue = entryData ? entryData.kredit : '';
            const akunIdValue = entryData ? entryData.akun_id : '';
            const akunDisplayValue = entryData ? entryData.akun_display : '';
            const keteranganValue = entryData ? entryData.keterangan : '';

            row.innerHTML = `
                <td class="py-3">
                    <input type="hidden" name="details[${entryIndex}][akun_id]" class="akun-id-input" value="${akunIdValue}">
                    <input type="text" name="details[${entryIndex}][akun_display]" 
                           class="akun-display-input w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500" 
                           placeholder="Pilih akun..." readonly onclick="openAccountModal(this)" value="${akunDisplayValue}">
                </td>
                <td class="py-3">
                    <input type="text" name="details[${entryIndex}][keterangan]" 
                           class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500" 
                           placeholder="Keterangan..." value="${keteranganValue}">
                </td>
                <td class="py-3">
                    <input type="number" name="details[${entryIndex}][debit]" step="0.01" min="0"
                           class="debit-input w-full text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500" 
                           placeholder="0.00" onchange="updateBalance()" value="${debitValue}">
                </td>
                <td class="py-3">
                    <input type="number" name="details[${entryIndex}][kredit]" step="0.01" min="0"
                           class="kredit-input w-full text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-yellow-500 focus:ring-yellow-500" 
                           placeholder="0.00" onchange="updateBalance()" value="${kreditValue}">
                </td>
                <td class="py-3 text-center">
                    <button type="button" onclick="removeJournalEntry(this)" 
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
            entryIndex++;
            updateRowNumbers();
        }

        function removeJournalEntry(button) {
            const tbody = document.getElementById('journalEntries');
            if (tbody.children.length > 2) {
                button.closest('tr').remove();
                updateRowNumbers();
                updateBalance();
            } else {
                alert('Minimal harus ada 2 baris entry dalam jurnal');
            }
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#journalEntries tr');
            rows.forEach((row, index) => {
                row.querySelectorAll('input[name*="details["]').forEach(input => {
                    const name = input.getAttribute('name');
                    const newName = name.replace(/details\[\d+\]/, `details[${index}]`);
                    input.setAttribute('name', newName);
                });
            });
        }

        function openAccountModal(input) {
            currentAccountInput = input;
            document.getElementById('accountModal').classList.remove('hidden');
            document.getElementById('accountSearch').focus();
        }

        function closeAccountModal() {
            document.getElementById('accountModal').classList.add('hidden');
            currentAccountInput = null;
            document.getElementById('accountSearch').value = '';
            showAllAccounts();
        }

        function updateBalance() {
            let totalDebit = 0;
            let totalKredit = 0;

            document.querySelectorAll('.debit-input').forEach(input => {
                totalDebit += parseFloat(input.value) || 0;
            });

            document.querySelectorAll('.kredit-input').forEach(input => {
                totalKredit += parseFloat(input.value) || 0;
            });

            const balance = totalDebit - totalKredit;

            document.getElementById('totalDebit').textContent = 'Rp ' + totalDebit.toLocaleString('id-ID');
            document.getElementById('totalKredit').textContent = 'Rp ' + totalKredit.toLocaleString('id-ID');

            const balanceEl = document.getElementById('balance');
            balanceEl.textContent = 'Rp ' + Math.abs(balance).toLocaleString('id-ID');

            if (balance === 0) {
                balanceEl.className = 'font-medium ml-2 text-green-600 dark:text-green-400';
                document.getElementById('submitBtn').disabled = false;
            } else {
                balanceEl.className = 'font-medium ml-2 text-red-600 dark:text-red-400';
                document.getElementById('submitBtn').disabled = true;
            }
        }

        // Account selection
        document.querySelectorAll('.account-item').forEach(item => {
            item.addEventListener('click', function() {
                if (currentAccountInput) {
                    const akunId = this.getAttribute('data-id');
                    const akunCode = this.getAttribute('data-code');
                    const akunName = this.getAttribute('data-name');

                    const row = currentAccountInput.closest('tr');
                    row.querySelector('.akun-id-input').value = akunId;
                    currentAccountInput.value = `${akunCode} - ${akunName}`;

                    closeAccountModal();
                }
            });
        });

        // Account search
        document.getElementById('accountSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.account-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        function showAllAccounts() {
            document.querySelectorAll('.account-item').forEach(item => {
                item.style.display = 'block';
            });
        }

        // Close modal when clicking outside
        document.getElementById('accountModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAccountModal();
            }
        });

        // Form validation
        document.getElementById('jurnalForm').addEventListener('submit', function(e) {
            const totalDebit = Array.from(document.querySelectorAll('.debit-input')).reduce((sum, input) => sum + (
                parseFloat(input.value) || 0), 0);
            const totalKredit = Array.from(document.querySelectorAll('.kredit-input')).reduce((sum, input) => sum +
                (parseFloat(input.value) || 0), 0);

            if (Math.abs(totalDebit - totalKredit) > 0.01) {
                e.preventDefault();
                alert('Total debit dan kredit harus seimbang!');
                return;
            }

            // Check if at least one account is selected
            const hasAccounts = Array.from(document.querySelectorAll('.akun-id-input')).some(input => input.value);
            if (!hasAccounts) {
                e.preventDefault();
                alert('Minimal harus ada satu akun yang dipilih!');
                return;
            }
        });
    </script>
</x-app-layout>

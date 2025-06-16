<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Jurnal</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Edit entri jurnal umum untuk PT Sinar Surya Semestaraya
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('keuangan.jurnal-umum.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <form action="{{ route('keuangan.jurnal-umum.update', $jurnal->id) }}" method="POST" id="jurnalUmumForm">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    @if ($errors->any())
                        <div
                            class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 dark:bg-red-900/30 dark:border-red-700">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                        Ada beberapa masalah dengan inputan Anda:
                                    </h3>
                                    <ul class="mt-1 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Informasi Umum --}}
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Umum</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal"
                                    value="{{ old('tanggal', $jurnal->tanggal) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div>
                                <label for="no_referensi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    No. Referensi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="no_referensi" id="no_referensi"
                                    value="{{ old('no_referensi', $jurnal->no_referensi) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div class="md:col-span-3">
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Keterangan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="keterangan" id="keterangan"
                                    value="{{ old('keterangan', $jurnal->keterangan) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Keterangan transaksi" required>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Jurnal --}}
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Jurnal</h3>
                            <button type="button" id="addRow"
                                class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Baris
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="jurnalTable">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Akun <span class="text-red-500">*</span>
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
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach ($jurnalItems as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="hidden" name="jurnal_items[{{ $index }}][id]"
                                                    value="{{ $item->id }}">
                                                <select name="jurnal_items[{{ $index }}][akun_id]"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="">Pilih Akun</option>
                                                    @foreach ($akuns as $akun)
                                                        <option value="{{ $akun->id }}"
                                                            {{ $item->akun_id == $akun->id ? 'selected' : '' }}>
                                                            {{ $akun->kode }} - {{ $akun->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" name="jurnal_items[{{ $index }}][debit]"
                                                    step="0.01" min="0"
                                                    class="debit-input block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm text-right dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    placeholder="0,00" value="{{ $item->debit }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number"
                                                    name="jurnal_items[{{ $index }}][kredit]" step="0.01"
                                                    min="0"
                                                    class="kredit-input block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm text-right dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    placeholder="0,00" value="{{ $item->kredit }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button type="button"
                                                    class="text-red-500 hover:text-red-700 deleteRow"
                                                    {{ $jurnalItems->count() <= 2 && $index < 2 ? 'disabled' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <td class="px-6 py-3 text-right font-medium">Total:</td>
                                        <td class="px-6 py-3 text-right font-medium">
                                            <span id="totalDebit">0,00</span>
                                        </td>
                                        <td class="px-6 py-3 text-right font-medium">
                                            <span id="totalKredit">0,00</span>
                                        </td>
                                        <td class="px-6 py-3"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-3 text-right font-medium">Selisih:</td>
                                        <td colspan="2" class="px-6 py-3 text-right font-medium">
                                            <span id="selisih" class="text-red-500">0,00</span>
                                        </td>
                                        <td class="px-6 py-3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                    <a href="{{ route('keuangan.jurnal-umum.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" id="submitForm"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jurnalTable = document.getElementById('jurnalTable').querySelector('tbody');
            const addRowButton = document.getElementById('addRow');
            const submitButton = document.getElementById('submitForm');
            const totalDebitSpan = document.getElementById('totalDebit');
            const totalKreditSpan = document.getElementById('totalKredit');
            const selisihSpan = document.getElementById('selisih');
            let rowCounter = {{ $jurnalItems->count() }}; // Start from the current count

            // Function to add a new row
            addRowButton.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select name="jurnal_items[${rowCounter}][akun_id]"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Akun</option>
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->kode }} - {{ $akun->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="jurnal_items[${rowCounter}][debit]" step="0.01" min="0"
                            class="debit-input block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm text-right dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="0,00">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="jurnal_items[${rowCounter}][kredit]" step="0.01" min="0"
                            class="kredit-input block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm text-right dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="0,00">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button type="button" class="text-red-500 hover:text-red-700 deleteRow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                `;
                jurnalTable.appendChild(newRow);
                rowCounter++;

                // Add event listeners to the new row
                addInputListeners();
                addDeleteListeners();

                // Update totals after adding a new row
                updateTotals();
            });

            // Function to delete a row
            function addDeleteListeners() {
                const deleteButtons = document.querySelectorAll('.deleteRow');
                deleteButtons.forEach(button => {
                    button.removeEventListener('click',
                    deleteRowHandler); // Remove existing listener to avoid duplicates
                    button.addEventListener('click', deleteRowHandler);
                });
            }

            function deleteRowHandler(e) {
                // Check if button is disabled
                if (e.currentTarget.disabled) return;

                // Check if we have more than 2 rows
                if (jurnalTable.children.length > 2) {
                    e.currentTarget.closest('tr').remove();
                    updateTotals();
                } else {
                    alert('Minimal harus ada 2 baris dalam jurnal.');
                }
            }

            // Function to add listeners to debit and kredit inputs
            function addInputListeners() {
                const inputs = document.querySelectorAll('.debit-input, .kredit-input');
                inputs.forEach(input => {
                    input.removeEventListener('input',
                    inputHandler); // Remove existing listener to avoid duplicates
                    input.addEventListener('input', inputHandler);
                });
            }

            function inputHandler(e) {
                const input = e.currentTarget;
                const row = input.closest('tr');

                // If this is a debit input, clear the kredit input in the same row and vice versa
                if (input.classList.contains('debit-input') && parseFloat(input.value) > 0) {
                    row.querySelector('.kredit-input').value = '';
                } else if (input.classList.contains('kredit-input') && parseFloat(input.value) > 0) {
                    row.querySelector('.debit-input').value = '';
                }

                updateTotals();
            }

            // Function to update totals
            function updateTotals() {
                let totalDebit = 0;
                let totalKredit = 0;

                // Calculate totals
                document.querySelectorAll('.debit-input').forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    totalDebit += value;
                });

                document.querySelectorAll('.kredit-input').forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    totalKredit += value;
                });

                // Update display
                totalDebitSpan.textContent = totalDebit.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                totalKreditSpan.textContent = totalKredit.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Calculate and display difference
                const selisih = Math.abs(totalDebit - totalKredit);
                selisihSpan.textContent = selisih.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Enable/disable submit button based on balance
                if (selisih < 0.01 && totalDebit > 0 && totalKredit > 0) {
                    submitButton.disabled = false;
                    selisihSpan.classList.remove('text-red-500');
                    selisihSpan.classList.add('text-green-500');
                } else {
                    submitButton.disabled = true;
                    selisihSpan.classList.remove('text-green-500');
                    selisihSpan.classList.add('text-red-500');
                }
            }

            // Initialize event listeners
            addInputListeners();
            addDeleteListeners();
            updateTotals();
        });
    </script>
</x-app-layout>

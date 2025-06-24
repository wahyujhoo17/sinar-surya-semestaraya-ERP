<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        @include('keuangan.management_pajak.partials.alerts')

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Tambah Laporan Pajak
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Buat laporan pajak baru untuk periode tertentu.
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('keuangan.management-pajak.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 border border-transparent rounded-lg font-medium text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest focus:bg-gray-400 dark:focus:bg-gray-500 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="mb-6">
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Terdapat beberapa kesalahan:
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
            <form action="{{ route('keuangan.management-pajak.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Jenis Pajak --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="jenis_pajak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Pajak <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_pajak" id="jenis_pajak" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('jenis_pajak') border-red-500 @enderror">
                                <option value="">Pilih Jenis Pajak</option>
                                <option value="ppn_keluaran"
                                    {{ old('jenis_pajak') === 'ppn_keluaran' ? 'selected' : '' }}>PPN
                                    Keluaran</option>
                                <option value="ppn_masukan"
                                    {{ old('jenis_pajak') === 'ppn_masukan' ? 'selected' : '' }}>PPN
                                    Masukan</option>
                                <option value="pph21" {{ old('jenis_pajak') === 'pph21' ? 'selected' : '' }}>PPh 21
                                </option>
                                <option value="pph23" {{ old('jenis_pajak') === 'pph23' ? 'selected' : '' }}>PPh 23
                                </option>
                                <option value="pph4_ayat2" {{ old('jenis_pajak') === 'pph4_ayat2' ? 'selected' : '' }}>
                                    PPh 4
                                    Ayat 2</option>
                            </select>
                            @error('jenis_pajak')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Faktur --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="tanggal_faktur"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Faktur <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_faktur" id="tanggal_faktur" required
                                value="{{ old('tanggal_faktur', date('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('tanggal_faktur') border-red-500 @enderror">
                            @error('tanggal_faktur')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Periode --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="periode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Periode <span class="text-red-500">*</span>
                            </label>
                            <input type="month" name="periode" id="periode" required
                                value="{{ old('periode', date('Y-m')) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('periode') border-red-500 @enderror">
                            @error('periode')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- No Faktur Pajak --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="no_faktur_pajak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                No. Faktur Pajak
                            </label>
                            <input type="text" name="no_faktur_pajak" id="no_faktur_pajak"
                                value="{{ old('no_faktur_pajak') }}" maxlength="50"
                                placeholder="Contoh: 010.000-21.00000001"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('no_faktur_pajak') border-red-500 @enderror">
                            @error('no_faktur_pajak')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NPWP --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="npwp"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                NPWP
                            </label>
                            <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}"
                                maxlength="50" placeholder="Contoh: 01.234.567.8-901.000"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('npwp') border-red-500 @enderror">
                            @error('npwp')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dasar Pengenaan Pajak --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="dasar_pengenaan_pajak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dasar Pengenaan Pajak (DPP) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                                </div>
                                <input type="number" name="dasar_pengenaan_pajak" id="dasar_pengenaan_pajak"
                                    required value="{{ old('dasar_pengenaan_pajak') }}" min="0"
                                    step="1" placeholder="0"
                                    class="w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('dasar_pengenaan_pajak') border-red-500 @enderror">
                            </div>
                            @error('dasar_pengenaan_pajak')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tarif Pajak --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="tarif_pajak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tarif Pajak <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="tarif_pajak" id="tarif_pajak" required
                                    value="{{ old('tarif_pajak', '11') }}" min="0" max="100"
                                    step="0.01" placeholder="11"
                                    class="w-full pr-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('tarif_pajak') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            @error('tarif_pajak')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jumlah Pajak --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="jumlah_pajak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah Pajak <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                                </div>
                                <input type="number" name="jumlah_pajak" id="jumlah_pajak" required
                                    value="{{ old('jumlah_pajak') }}" min="0" step="1" placeholder="0"
                                    readonly
                                    class="w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-100 dark:bg-gray-600 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('jumlah_pajak') border-red-500 @enderror">
                            </div>
                            @error('jumlah_pajak')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Otomatis dihitung: DPP × Tarif Pajak
                            </p>
                        </div>

                        {{-- Tanggal Jatuh Tempo --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="tanggal_jatuh_tempo"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Jatuh Tempo
                            </label>
                            <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo"
                                value="{{ old('tanggal_jatuh_tempo') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('tanggal_jatuh_tempo') border-red-500 @enderror">
                            @error('tanggal_jatuh_tempo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Pembayaran --}}
                        <div class="col-span-2 md:col-span-1">
                            <label for="status_pembayaran"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="status_pembayaran" id="status_pembayaran" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('status_pembayaran') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="belum_bayar"
                                    {{ old('status_pembayaran', 'belum_bayar') === 'belum_bayar' ? 'selected' : '' }}>
                                    Belum Bayar</option>
                                <option value="sudah_bayar"
                                    {{ old('status_pembayaran') === 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar
                                </option>
                                <option value="lebih_bayar"
                                    {{ old('status_pembayaran') === 'lebih_bayar' ? 'selected' : '' }}>Lebih Bayar
                                </option>
                            </select>
                            @error('status_pembayaran')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-span-2">
                            <label for="keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                placeholder="Tambahkan keterangan atau catatan untuk laporan pajak ini..."
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Maksimal 500 karakter.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <a href="{{ route('keuangan.management-pajak.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 border border-transparent rounded-lg font-medium text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest focus:bg-gray-400 dark:focus:bg-gray-500 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Laporan Pajak
                    </button>
                </div>
            </form>
        </div>

        {{-- Help Info --}}
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Informasi Penting
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc space-y-1 pl-5">
                            <li>Laporan pajak akan dibuat dengan status "Draft" yang dapat diedit.</li>
                            <li>Setelah difinalisasi, laporan tidak dapat diubah lagi.</li>
                            <li>Pastikan periode dan nilai pajak sudah benar sebelum memfinalisasi.</li>
                            <li>Anda dapat menggunakan fitur "Laporan Otomatis" untuk PPN berdasarkan transaksi.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate tax amount when DPP or rate changes
        function calculateTaxAmount() {
            const dpp = parseFloat(document.getElementById('dasar_pengenaan_pajak').value) || 0;
            const rate = parseFloat(document.getElementById('tarif_pajak').value) || 0;
            const taxAmount = Math.round(dpp * (rate / 100));

            document.getElementById('jumlah_pajak').value = taxAmount;
        }

        // Event listeners for auto-calculation
        document.getElementById('dasar_pengenaan_pajak').addEventListener('input', calculateTaxAmount);
        document.getElementById('tarif_pajak').addEventListener('input', calculateTaxAmount);

        // Auto-set tarif pajak based on jenis pajak
        document.getElementById('jenis_pajak').addEventListener('change', function() {
            const jenis = this.value;
            const tarifInput = document.getElementById('tarif_pajak');

            switch (jenis) {
                case 'ppn_keluaran':
                case 'ppn_masukan':
                    tarifInput.value = '11';
                    break;
                case 'pph21':
                    tarifInput.value = '5';
                    break;
                case 'pph23':
                    tarifInput.value = '2';
                    break;
                case 'pph4_ayat2':
                    tarifInput.value = '0.5';
                    break;
                default:
                    tarifInput.value = '';
            }

            calculateTaxAmount();
        });

        // Auto-set due date (30 days from invoice date)
        document.getElementById('tanggal_faktur').addEventListener('change', function() {
            const invoiceDate = new Date(this.value);
            if (invoiceDate) {
                const dueDate = new Date(invoiceDate);
                dueDate.setDate(dueDate.getDate() + 30);
                document.getElementById('tanggal_jatuh_tempo').value = dueDate.toISOString().split('T')[0];
            }
        });

        // Format number inputs for better UX
        function formatNumberInput(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function() {
                    // Remove any non-numeric characters
                    let value = this.value.replace(/[^0-9]/g, '');
                    this.value = value;
                });
            }
        }

        formatNumberInput('dasar_pengenaan_pajak');
        formatNumberInput('jumlah_pajak');

        // Form submission handler with debugging
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form is being submitted...');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);

            // Get all form data
            const formData = new FormData(this);
            const formObject = {};
            for (let [key, value] of formData.entries()) {
                formObject[key] = value;
            }
            console.log('Form data:', formObject);

            // Check required fields
            const requiredFields = ['jenis_pajak', 'tanggal_faktur', 'periode', 'dasar_pengenaan_pajak',
                'tarif_pajak', 'jumlah_pajak', 'status_pembayaran'
            ];
            let hasError = false;

            requiredFields.forEach(field => {
                if (!formObject[field]) {
                    console.error('Missing required field:', field);
                    hasError = true;
                }
            });

            if (hasError) {
                e.preventDefault();
                alert('Ada field yang belum diisi!');
                return false;
            }

            console.log('Form validation passed, submitting...');
        });

        // Initialize calculation on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculateTaxAmount();
        });
    </script>
</x-app-layout>

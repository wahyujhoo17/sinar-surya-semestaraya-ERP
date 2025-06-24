@extends('layouts.app')

@section('title', 'Edit Management Pajak')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('keuangan.management_pajak.partials.alerts')

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Management Pajak</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Update data management pajak
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('keuangan.management-pajak.show', $pajak->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        <a href="{{ route('keuangan.management-pajak.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <form action="{{ route('keuangan.management-pajak.update', $pajak->id) }}" method="POST"
                    id="editPajakForm">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Pajak</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update informasi data pajak</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Pajak -->
                            <div>
                                <label for="jenis_pajak"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Pajak <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_pajak" id="jenis_pajak" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    {{ $pajak->status === 'final' ? 'disabled' : '' }}>
                                    <option value="">Pilih Jenis Pajak</option>
                                    <option value="ppn_keluaran"
                                        {{ old('jenis_pajak', $pajak->jenis_pajak) === 'ppn_keluaran' ? 'selected' : '' }}>
                                        PPN Keluaran</option>
                                    <option value="ppn_masukan"
                                        {{ old('jenis_pajak', $pajak->jenis_pajak) === 'ppn_masukan' ? 'selected' : '' }}>
                                        PPN Masukan</option>
                                    <option value="pph21"
                                        {{ old('jenis_pajak', $pajak->jenis_pajak) === 'pph21' ? 'selected' : '' }}>PPh 21
                                    </option>
                                    <option value="pph23"
                                        {{ old('jenis_pajak', $pajak->jenis_pajak) === 'pph23' ? 'selected' : '' }}>PPh 23
                                    </option>
                                    <option value="pph4_ayat2"
                                        {{ old('jenis_pajak', $pajak->jenis_pajak) === 'pph4_ayat2' ? 'selected' : '' }}>PPh
                                        4 Ayat 2</option>
                                </select>
                                @error('jenis_pajak')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Periode -->
                            <div>
                                <label for="periode"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Periode <span class="text-red-500">*</span>
                                </label>
                                <input type="month" name="periode" id="periode" required
                                    value="{{ old('periode', $pajak->periode ? \Carbon\Carbon::parse($pajak->periode)->format('Y-m') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    {{ $pajak->status === 'final' ? 'readonly' : '' }}>
                                @error('periode')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No. Faktur Pajak -->
                            <div>
                                <label for="no_faktur_pajak"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    No. Faktur Pajak
                                </label>
                                <input type="text" name="no_faktur_pajak" id="no_faktur_pajak"
                                    value="{{ old('no_faktur_pajak', $pajak->no_faktur_pajak) }}"
                                    placeholder="Contoh: 010.000-24.00000001"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    {{ $pajak->status === 'final' ? 'readonly' : '' }}>
                                @error('no_faktur_pajak')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NPWP -->
                            <div>
                                <label for="npwp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    NPWP
                                </label>
                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $pajak->npwp) }}"
                                    placeholder="Contoh: 01.234.567.8-901.000"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('npwp')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dasar Pengenaan Pajak -->
                            <div>
                                <label for="dasar_pengenaan_pajak"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dasar Pengenaan Pajak (DPP) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" name="dasar_pengenaan_pajak" id="dasar_pengenaan_pajak" required
                                        value="{{ old('dasar_pengenaan_pajak', number_format($pajak->dasar_pengenaan_pajak, 0, ',', '.')) }}"
                                        placeholder="0"
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white currency-input"
                                        {{ $pajak->status === 'final' ? 'readonly' : '' }}>
                                </div>
                                @error('dasar_pengenaan_pajak')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tarif Pajak -->
                            <div>
                                <label for="tarif_pajak"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tarif Pajak (%) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="tarif_pajak" id="tarif_pajak" required
                                        value="{{ old('tarif_pajak', $pajak->tarif_pajak) }}" step="0.01" min="0"
                                        max="100" placeholder="11.00"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                        {{ $pajak->status === 'final' ? 'readonly' : '' }}>
                                    <span class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">%</span>
                                </div>
                                @error('tarif_pajak')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jumlah Pajak (Auto calculated) -->
                            <div>
                                <label for="jumlah_pajak_display"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jumlah Pajak
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" id="jumlah_pajak_display"
                                        value="{{ number_format($pajak->jumlah_pajak, 0, ',', '.') }}" readonly
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                                    <input type="hidden" name="jumlah_pajak" id="jumlah_pajak"
                                        value="{{ $pajak->jumlah_pajak }}">
                                </div>
                            </div>

                            <!-- Tanggal Faktur -->
                            <div>
                                <label for="tanggal_faktur"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Faktur <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_faktur" id="tanggal_faktur" required
                                    value="{{ old('tanggal_faktur', $pajak->tanggal_faktur ? \Carbon\Carbon::parse($pajak->tanggal_faktur)->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    {{ $pajak->status === 'final' ? 'readonly' : '' }}>
                                @error('tanggal_faktur')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Jatuh Tempo -->
                            <div>
                                <label for="tanggal_jatuh_tempo"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Jatuh Tempo
                                </label>
                                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo"
                                    value="{{ old('tanggal_jatuh_tempo', $pajak->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($pajak->tanggal_jatuh_tempo)->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    {{ $pajak->status === 'final' ? 'readonly' : '' }}>
                                @error('tanggal_jatuh_tempo')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Pembayaran -->
                            <div>
                                <label for="status_pembayaran"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="status_pembayaran" id="status_pembayaran" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Pilih Status</option>
                                    <option value="belum_bayar"
                                        {{ old('status_pembayaran', $pajak->status_pembayaran) === 'belum_bayar' ? 'selected' : '' }}>
                                        Belum Bayar</option>
                                    <option value="sudah_bayar"
                                        {{ old('status_pembayaran', $pajak->status_pembayaran) === 'sudah_bayar' ? 'selected' : '' }}>
                                        Sudah Bayar</option>
                                    <option value="lebih_bayar"
                                        {{ old('status_pembayaran', $pajak->status_pembayaran) === 'lebih_bayar' ? 'selected' : '' }}>
                                        Lebih Bayar</option>
                                </select>
                                @error('status_pembayaran')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Keterangan
                                </label>
                                <textarea name="keterangan" id="keterangan" rows="3" placeholder="Keterangan tambahan..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    {{ $pajak->status === 'final' ? 'readonly' : '' }}>{{ old('keterangan', $pajak->keterangan) }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if ($pajak->status === 'final')
                                    <div class="flex items-center text-green-600 dark:text-green-400">
                                        <i class="fas fa-lock mr-2"></i>
                                        <span class="text-sm font-medium">Data sudah final dan tidak dapat diubah</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-blue-600 dark:text-blue-400">
                                        <i class="fas fa-edit mr-2"></i>
                                        <span class="text-sm font-medium">Data dapat diubah</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3">
                                <a href="{{ route('keuangan.management-pajak.index') }}"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Batal
                                </a>
                                @if ($pajak->status !== 'final')
                                    <button type="submit"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-save mr-2"></i>
                                        Update Data
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Currency formatting
            const currencyInputs = document.querySelectorAll('.currency-input');
            currencyInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^\d]/g, '');
                    e.target.value = formatNumber(value);
                    calculateTax();
                });
            });

            // Tax calculation
            function calculateTax() {
                const dppInput = document.getElementById('dasar_pengenaan_pajak');
                const tarifInput = document.getElementById('tarif_pajak');
                const jumlahPajakDisplay = document.getElementById('jumlah_pajak_display');
                const jumlahPajakHidden = document.getElementById('jumlah_pajak');

                if (dppInput && tarifInput && jumlahPajakDisplay && jumlahPajakHidden) {
                    const dpp = parseFloat(dppInput.value.replace(/[^\d]/g, '')) || 0;
                    const tarif = parseFloat(tarifInput.value) || 0;
                    const jumlahPajak = Math.round((dpp * tarif) / 100);

                    jumlahPajakDisplay.value = formatNumber(jumlahPajak.toString());
                    jumlahPajakHidden.value = jumlahPajak;
                }
            }

            // Listen for tarif pajak changes
            const tarifInput = document.getElementById('tarif_pajak');
            if (tarifInput) {
                tarifInput.addEventListener('input', calculateTax);
            }

            // Auto-fill tarif based on jenis pajak
            const jenisPajakSelect = document.getElementById('jenis_pajak');
            if (jenisPajakSelect) {
                jenisPajakSelect.addEventListener('change', function() {
                    const tarifInput = document.getElementById('tarif_pajak');
                    if (tarifInput && !tarifInput.readOnly) {
                        switch (this.value) {
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
                        calculateTax();
                    }
                });
            }

            // Format number helper
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Form submission handler with debugging
            const form = document.getElementById('editPajakForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Edit form is being submitted...');
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
                    const requiredFields = ['jenis_pajak', 'tanggal_faktur', 'periode',
                        'dasar_pengenaan_pajak', 'tarif_pajak', 'jumlah_pajak', 'status_pembayaran'
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

                    // Convert formatted currency back to number
                    const dppInput = document.getElementById('dasar_pengenaan_pajak');
                    if (dppInput) {
                        dppInput.value = dppInput.value.replace(/[^\d]/g, '');
                    }

                    console.log('Form validation passed, submitting...');
                });
            }

            // Auto-calculate due date (30 days after faktur date for tax)
            const tanggalFakturInput = document.getElementById('tanggal_faktur');
            const tanggalJatuhTempoInput = document.getElementById('tanggal_jatuh_tempo');

            if (tanggalFakturInput && tanggalJatuhTempoInput) {
                tanggalFakturInput.addEventListener('change', function() {
                    if (this.value && !tanggalJatuhTempoInput.readOnly) {
                        const fakturDate = new Date(this.value);
                        fakturDate.setDate(fakturDate.getDate() + 30);
                        tanggalJatuhTempoInput.value = fakturDate.toISOString().split('T')[0];
                    }
                });
            }
        });
    </script>
@endsection

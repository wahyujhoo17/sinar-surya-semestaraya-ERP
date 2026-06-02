<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Edit Penggajian'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Penggajian</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Edit data penggajian karyawan {{ $penggajian->karyawan->nama_lengkap }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row sm:items-center gap-3">
                <a href="{{ route('hr.penggajian.index', request()->query()) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Penggajian
                </a>
                <button type="submit" form="payrollForm"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        {{-- Form Section --}}
        <form id="payrollForm" action="{{ route('hr.penggajian.update', $penggajian->id) }}" method="POST"
            x-data="payrollCalculator()" x-init="init()" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Employee Selection Card (Read-only) --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Karyawan
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Data karyawan dan periode penggajian (tidak
                        dapat diubah)</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Employee Info (Read-only) --}}
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Karyawan
                        </label>
                        <div
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-300">
                            {{ $penggajian->karyawan->nama_lengkap }} - {{ $penggajian->karyawan->nip }}
                        </div>
                    </div>

                    {{-- Month (Read-only) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bulan
                        </label>
                        <div
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-300">
                            {{ Carbon\Carbon::create()->month($penggajian->bulan)->translatedFormat('F') }}
                        </div>
                    </div>

                    {{-- Year (Read-only) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tahun
                        </label>
                        <div
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-300">
                            {{ $penggajian->tahun }}
                        </div>
                    </div>
                </div>

                {{-- Employee Info Display --}}
                <div class="px-6 pb-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Informasi Karyawan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">Nama:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $penggajian->karyawan->nama_lengkap }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">NIP:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $penggajian->karyawan->nip }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">Department:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $penggajian->karyawan->department->nama ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">Jabatan:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $penggajian->karyawan->jabatan->nama ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Salary Components Card --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                                Komponen Gaji
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Komponen pendapatan dan potongan
                                gaji</p>
                        </div>
                        <button type="button" @click="calculateCommission()" :disabled="commissionLoading"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 disabled:opacity-50">
                            <svg x-show="!commissionLoading" class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <svg x-show="commissionLoading" class="animate-spin h-4 w-4 mr-2 text-primary-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="commissionLoading ? 'Memproses...' : 'Hitung Ulang Komisi'"></span>
                        </button>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Basic Salary --}}
                    <div>
                        <label for="gaji_pokok" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gaji Pokok <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="gaji_pokok" :value="salaryComponents.gaji_pokok">
                            <input type="text" id="gaji_pokok" required x-init="$el.value = formatRibu(salaryComponents.gaji_pokok)"
                                @focus="$el.value = salaryComponents.gaji_pokok; $el.select()"
                                @input="salaryComponents.gaji_pokok = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.gaji_pokok)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('gaji_pokok') border-red-500 @enderror">
                        </div>
                        @error('gaji_pokok')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tunjangan Keluarga --}}
                    <div>
                        <label for="tunjangan_keluarga"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan Keluarga
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="tunjangan_keluarga"
                                :value="salaryComponents.tunjangan_keluarga">
                            <input type="text" id="tunjangan_keluarga" x-init="$el.value = formatRibu(salaryComponents.tunjangan_keluarga)"
                                @focus="$el.value = salaryComponents.tunjangan_keluarga; $el.select()"
                                @input="salaryComponents.tunjangan_keluarga = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.tunjangan_keluarga)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Tunjangan Jabatan --}}
                    <div>
                        <label for="tunjangan_jabatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan Jabatan
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="tunjangan_jabatan"
                                :value="salaryComponents.tunjangan_jabatan">
                            <input type="text" id="tunjangan_jabatan" x-init="$el.value = formatRibu(salaryComponents.tunjangan_jabatan)"
                                @focus="$el.value = salaryComponents.tunjangan_jabatan; $el.select()"
                                @input="salaryComponents.tunjangan_jabatan = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.tunjangan_jabatan)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Tunjangan Transport --}}
                    <div>
                        <label for="tunjangan_transport"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan Transport
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="tunjangan_transport"
                                :value="salaryComponents.tunjangan_transport">
                            <input type="text" id="tunjangan_transport" x-init="$el.value = formatRibu(salaryComponents.tunjangan_transport)"
                                @focus="$el.value = salaryComponents.tunjangan_transport; $el.select()"
                                @input="salaryComponents.tunjangan_transport = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.tunjangan_transport)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Tunjangan Makan --}}
                    <div>
                        <label for="tunjangan_makan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan Makan
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="tunjangan_makan" :value="salaryComponents.tunjangan_makan">
                            <input type="text" id="tunjangan_makan" x-init="$el.value = formatRibu(salaryComponents.tunjangan_makan)"
                                @focus="$el.value = salaryComponents.tunjangan_makan; $el.select()"
                                @input="salaryComponents.tunjangan_makan = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.tunjangan_makan)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Tunjangan BTN (Hidden as requested) --}}
                    <input type="hidden" name="tunjangan_btn" :value="salaryComponents.tunjangan_btn">

                    {{-- Tunjangan Pulsa --}}
                    <div>
                        <label for="tunjangan_pulsa"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan Pulsa
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="tunjangan_pulsa" :value="salaryComponents.tunjangan_pulsa">
                            <input type="text" id="tunjangan_pulsa" x-init="$el.value = formatRibu(salaryComponents.tunjangan_pulsa)"
                                @focus="$el.value = salaryComponents.tunjangan_pulsa; $el.select()"
                                @input="salaryComponents.tunjangan_pulsa = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.tunjangan_pulsa)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Allowances (General) --}}
                    <div>
                        <label for="tunjangan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan Lainnya
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="tunjangan" :value="salaryComponents.tunjangan">
                            <input type="text" id="tunjangan" x-init="$el.value = formatRibu(salaryComponents.tunjangan)"
                                @focus="$el.value = salaryComponents.tunjangan; $el.select()"
                                @input="salaryComponents.tunjangan = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.tunjangan)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Bonus --}}
                    <div>
                        <label for="bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bonus
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="bonus" :value="salaryComponents.bonus">
                            <input type="text" id="bonus" x-init="$el.value = formatRibu(salaryComponents.bonus)"
                                @focus="$el.value = salaryComponents.bonus; $el.select()"
                                @input="salaryComponents.bonus = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.bonus)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Overtime --}}
                    <div>
                        <label for="lembur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Lembur
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="lembur" :value="salaryComponents.lembur">
                            <input type="text" id="lembur" x-init="$el.value = formatRibu(salaryComponents.lembur)"
                                @focus="$el.value = salaryComponents.lembur; $el.select()"
                                @input="salaryComponents.lembur = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.lembur)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                {{-- Deduction Section --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                            </path>
                        </svg>
                        Potongan Gaji
                    </h4>
                </div>
                <div class="p-6 pt-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- BPJS --}}
                    <div>
                        <label for="bpjs_karyawan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BPJS
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="bpjs_karyawan" :value="salaryComponents.bpjs_karyawan">
                            <input type="text" id="bpjs_karyawan" x-init="$el.value = formatRibu(salaryComponents.bpjs_karyawan)"
                                @focus="$el.value = salaryComponents.bpjs_karyawan; $el.select()"
                                @input="salaryComponents.bpjs_karyawan = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.bpjs_karyawan)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Cash Bon --}}
                    <div>
                        <label for="cash_bon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cash Bon
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="cash_bon" :value="salaryComponents.cash_bon">
                            <input type="text" id="cash_bon" x-init="$el.value = formatRibu(salaryComponents.cash_bon)"
                                @focus="$el.value = salaryComponents.cash_bon; $el.select()"
                                @input="salaryComponents.cash_bon = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.cash_bon)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Keterlambatan --}}
                    <div>
                        <label for="keterlambatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterlambatan
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="keterlambatan" :value="salaryComponents.keterlambatan">
                            <input type="text" id="keterlambatan" x-init="$el.value = formatRibu(salaryComponents.keterlambatan)"
                                @focus="$el.value = salaryComponents.keterlambatan; $el.select()"
                                @input="salaryComponents.keterlambatan = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.keterlambatan)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Deductions (General) --}}
                    <div>
                        <label for="potongan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Potongan Lainnya
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="hidden" name="potongan" :value="salaryComponents.potongan">
                            <input type="text" id="potongan" x-init="$el.value = formatRibu(salaryComponents.potongan)"
                                @focus="$el.value = salaryComponents.potongan; $el.select()"
                                @input="salaryComponents.potongan = parseFloat(($el.value+'').replace(/\./g,'').replace(',','.')) || 0; calculateTotal()"
                                @blur="$el.value = formatRibu(salaryComponents.potongan)"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label for="tanggal_bayar"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Bayar
                        </label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar"
                            value="{{ old('tanggal_bayar', $penggajian->tanggal_bayar ? \Carbon\Carbon::parse($penggajian->tanggal_bayar)->format('Y-m-d') : '') }}"
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                    </div>
                </div>
            </div>

            @php
                $manualKomponenGaji = $penggajian->komponenGaji->reject(function ($komponen) {
                    return str_starts_with((string) ($komponen->keterangan ?? ''), '__AUTO_TUNJANGAN_');
                });
            @endphp

            {{-- Existing Salary Components --}}
            @if ($manualKomponenGaji->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-gray-800 dark:to-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Komponen Gaji Tambahan
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Komponen gaji seperti komisi dan komponen lainnya (tidak dapat diubah)
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Komponen
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jenis
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nilai
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($manualKomponenGaji as $index => $komponen)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                <input type="hidden" name="komponenGaji[{{ $index }}][id]"
                                                    value="{{ $komponen->id }}">
                                                <input type="hidden"
                                                    name="komponenGaji[{{ $index }}][nama_komponen]"
                                                    value="{{ $komponen->nama_komponen }}">
                                                <input type="hidden" name="komponenGaji[{{ $index }}][jenis]"
                                                    value="{{ $komponen->jenis }}">
                                                <input type="hidden" name="komponenGaji[{{ $index }}][nilai]"
                                                    value="{{ $komponen->nilai }}">
                                                <input type="hidden"
                                                    name="komponenGaji[{{ $index }}][keterangan]"
                                                    value="{{ $komponen->keterangan }}">
                                                {{ $komponen->nama_komponen }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($komponen->jenis === 'pendapatan')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-500">
                                                        Pendapatan
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-500">
                                                        Potongan
                                                    </span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">
                                                Rp {{ number_format($komponen->nilai, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $komponen->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Commission Calculation Card (Dynamic for Edit) --}}
            <div x-show="commissionData.orders.length > 0" x-transition
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden mb-6">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-gray-800 dark:to-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Perhitungan Komisi Penjualan
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Komisi dihitung berdasarkan margin penjualan dari sales order yang sudah lunas
                    </p>
                </div>
                <div class="p-6">
                    {{-- Commission Rules Info --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Informasi Sistem Komisi:
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-blue-800 dark:text-blue-200">
                            <div>
                                <p class="font-medium mb-2">Basis Perhitungan:</p>
                                <ul class="space-y-1 ml-4">
                                    <li>• Komisi dihitung berdasarkan tanggal pembayaran invoice</li>
                                    <li>• Menggunakan margin % = (Harga Jual - Harga Beli) / Harga Beli × 100</li>
                                    <li>• Rate komisi mengikuti tier system (1% - 30%)</li>
                                </ul>
                            </div>
                            <div>
                                <p class="font-medium mb-2">Contoh Tier:</p>
                                <div class="space-y-1">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                                        <span>Margin 18-20%: 1% komisi</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                                        <span>Margin 45.5-50%: 2.5% komisi</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                                        <span>Margin 451-500%: 10% komisi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Penyesuaian Komisi Per Sales Order --}}
                    <div
                        class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-orange-900 dark:text-orange-100 mb-4">
                            Penyesuaian Komisi Per Sales Order:
                        </h4>
                        <div class="space-y-4">
                            <template x-for="order in commissionData.orders" :key="order.id">
                                <div
                                    class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                                    <input type="hidden"
                                        :name="'sales_order_adjustments[' + order.id + '][sales_order_id]'"
                                        :value="order.id">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h5 class="font-medium text-gray-900 dark:text-white"
                                                x-text="'SO: ' + order.nomor"></h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400"
                                                x-text="'Netto Penjualan: ' + formatRupiah(order.netto_penjualan)"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-green-600 dark:text-green-400 font-medium"
                                                x-text="'Komisi: ' + formatRupiah(order.komisi)"></p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cashback
                                                (Nominal)</label>
                                            <input type="text" x-init="$el.value = formatRibu(salesOrderAdjustments[order.id] ? salesOrderAdjustments[order.id].cashback_nominal : 0)"
                                                @focus="$el.value = (salesOrderAdjustments[order.id] ? salesOrderAdjustments[order.id].cashback_nominal : 0); $el.select()"
                                                @input="setSalesOrderAdjustment(order.id, 'cashback_nominal', parseFloat(($event.target.value+'').replace(/\./g,'').replace(',','.')) || 0)"
                                                @blur="$el.value = formatRibu(salesOrderAdjustments[order.id] ? salesOrderAdjustments[order.id].cashback_nominal : 0)"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white text-sm">
                                            <input type="hidden"
                                                :name="'sales_order_adjustments[' + order.id + '][cashback_nominal]'"
                                                :value="salesOrderAdjustments[order.id] ? salesOrderAdjustments[order.id]
                                                    .cashback_nominal : 0">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Overhead
                                                (%)</label>
                                            <input type="number" step="0.01"
                                                :name="'sales_order_adjustments[' + order.id + '][overhead_persen]'"
                                                x-model="salesOrderAdjustments[order.id] ? salesOrderAdjustments[order.id].overhead_persen : 0"
                                                @input="setSalesOrderAdjustment(order.id, 'overhead_persen', $event.target.value)"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white text-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="mt-4">
                            <button type="button" @click="calculateCommission()"
                                class="px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors duration-200">
                                Hitung Ulang Komisi dengan Penyesuaian
                            </button>
                        </div>
                    </div>

                    {{-- Loading State --}}
                    <div x-show="commissionLoading" class="text-center py-8">
                        <div
                            class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-gray-500 bg-white dark:bg-gray-800 transition ease-in-out duration-150">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menghitung komisi...
                        </div>
                    </div>

                    {{-- Commission Details Table --}}
                    <div x-show="!commissionLoading && commissionData.orders.length > 0" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div
                                class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Total Sales
                                    Order</span>
                                <p class="text-xl font-bold text-gray-900 dark:text-white"
                                    x-text="commissionData.orders.length"></p>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Total Netto
                                    Penjualan</span>
                                <p class="text-xl font-bold text-primary-600"
                                    x-text="formatRupiah(commissionData.total_sales)"></p>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Rata-rata
                                    Margin</span>
                                <p class="text-xl font-bold text-purple-600"
                                    x-text="(parseFloat(commissionData.average_margin || 0)).toFixed(2) + '%'"></p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr
                                        class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <th class="px-2 py-3">Produk</th>
                                        <th class="px-2 py-3 text-right">Harga Jual</th>
                                        <th class="px-2 py-3 text-right">Harga Beli</th>
                                        <th class="px-2 py-3 text-right">Margin %</th>
                                        <th class="px-2 py-3 text-right">Rate</th>
                                        <th class="px-2 py-3 text-right">Komisi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <template x-for="order in commissionData.orders" :key="'details-' + order.id">
                                        <template x-for="item in order.product_details" :key="item.produk">
                                            <tr class="text-sm">
                                                <td class="px-2 py-3 dark:text-gray-300" x-text="item.produk"></td>
                                                <td class="px-2 py-3 text-right dark:text-gray-300"
                                                    x-text="formatRupiah(item.harga_jual)"></td>
                                                <td class="px-2 py-3 text-right dark:text-gray-300"
                                                    x-text="formatRupiah(item.harga_beli)"></td>
                                                <td class="px-2 py-3 text-right">
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                                        :class="item.margin_persen > 40 ? 'bg-green-100 text-green-800' :
                                                            'bg-yellow-100 text-yellow-800'"
                                                        x-text="(parseFloat(item.margin_persen)).toFixed(2) + '%'"></span>
                                                </td>
                                                <td class="px-2 py-3 text-right font-medium text-blue-600"
                                                    x-text="item.commission_rate + '%'"></td>
                                                <td class="px-2 py-3 text-right font-semibold text-green-600"
                                                    x-text="formatRupiah(item.komisi)"></td>
                                            </tr>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Total Calculation Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Ringkasan Perhitungan
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Total gaji yang akan diterima karyawan
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Income Summary --}}
                            <div class="space-y-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Pendapatan</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Gaji Pokok:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.gaji_pokok)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.tunjangan_keluarga) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan Keluarga:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan_keluarga)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.tunjangan_jabatan) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan Jabatan:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan_jabatan)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.tunjangan_transport) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan Transport:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan_transport)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.tunjangan_makan) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan Makan:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan_makan)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.tunjangan_btn) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan BTN:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan_btn)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.tunjangan_pulsa) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Tunjangan Pulsa:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.tunjangan_pulsa)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Bonus:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.bonus)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Lembur:</span>
                                        <span class="font-medium"
                                            x-text="formatRupiah(salaryComponents.lembur)"></span>
                                    </div>

                                    {{-- Dynamic Commission from Alpine --}}
                                    <div class="flex justify-between border-t pt-2"
                                        x-show="commissionData.total_commission > 0">
                                        <span class="text-gray-600 dark:text-gray-400 font-medium italic">Komisi
                                            Penjualan:</span>
                                        <span class="font-bold text-green-600 dark:text-green-400"
                                            x-text="formatRupiah(commissionData.total_commission)"></span>
                                    </div>

                                    {{-- Manual Components --}}
                                    <template x-for="comp in existingComponents.filter(c => c.jenis === 'pendapatan')"
                                        :key="comp.id || comp.nama_komponen">
                                        <div class="flex justify-between border-t pt-2">
                                            <span class="text-gray-600 dark:text-gray-400"
                                                x-text="comp.nama_komponen + ':'"></span>
                                            <span class="font-medium text-green-600 dark:text-green-400"
                                                x-text="formatRupiah(comp.nilai)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Deduction Summary --}}
                            <div class="space-y-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Potongan</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.bpjs_karyawan) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">BPJS:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400"
                                            x-text="formatRupiah(salaryComponents.bpjs_karyawan)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.cash_bon) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Cash Bon:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400"
                                            x-text="formatRupiah(salaryComponents.cash_bon)"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="parseFloat(salaryComponents.keterlambatan) > 0">
                                        <span class="text-gray-600 dark:text-gray-400">Keterlambatan:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400"
                                            x-text="formatRupiah(salaryComponents.keterlambatan)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Potongan Lainnya:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400"
                                            x-text="formatRupiah(salaryComponents.potongan)"></span>
                                    </div>

                                    {{-- Manual Deduction Components --}}
                                    <template x-for="comp in existingComponents.filter(c => c.jenis === 'potongan')"
                                        :key="comp.id || comp.nama_komponen">
                                        <div class="flex justify-between border-t pt-2">
                                            <span class="text-gray-600 dark:text-gray-400"
                                                x-text="comp.nama_komponen + ':'"></span>
                                            <span class="font-medium text-red-600 dark:text-red-400"
                                                x-text="formatRupiah(comp.nilai)"></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Total --}}
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total
                                            Gaji:</span>
                                        <span class="text-2xl font-bold text-green-600 dark:text-green-400"
                                            x-text="formatRupiah(totalSalary)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Settings Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan Tambahan
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Status --}}
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('status') border-red-500 @enderror">
                                <option value="draft"
                                    {{ old('status', $penggajian->status) == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="disetujui"
                                    {{ old('status', $penggajian->status) == 'disetujui' ? 'selected' : '' }}>Disetujui
                                </option>
                                <option value="dibayar"
                                    {{ old('status', $penggajian->status) == 'dibayar' ? 'selected' : '' }}>Dibayar
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200"
                                placeholder="Catatan tambahan (opsional)">{{ old('catatan', $penggajian->catatan) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-between items-center pt-6">
                    <a href="{{ route('hr.penggajian.index', request()->query()) }}"
                        class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 font-medium">
                        Batal
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('hr.penggajian.show', array_merge(request()->query(), ['penggajian' => $penggajian->id])) }}"
                            class="px-6 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 font-medium">
                            Detail
                        </a>
                        <button type="submit"
                            class="px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Update Penggajian
                        </button>
                    </div>
                </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function payrollCalculator() {
                return {
                    salaryComponents: {
                        gaji_pokok: {{ old('gaji_pokok', $penggajian->gaji_pokok ?? 0) }},
                        tunjangan_keluarga: {{ old('tunjangan_keluarga', optional($penggajian->komponenGaji->firstWhere('keterangan', '__AUTO_TUNJANGAN_KELUARGA__'))->nilai ?? ($penggajian->karyawan->tunjangan_keluarga ?? 0)) }},
                        tunjangan_jabatan: {{ old('tunjangan_jabatan', optional($penggajian->komponenGaji->firstWhere('keterangan', '__AUTO_TUNJANGAN_JABATAN__'))->nilai ?? ($penggajian->karyawan->tunjangan_jabatan ?? 0)) }},
                        tunjangan_transport: {{ old('tunjangan_transport', optional($penggajian->komponenGaji->firstWhere('keterangan', '__AUTO_TUNJANGAN_TRANSPORT__'))->nilai ?? ($penggajian->karyawan->tunjangan_transport ?? 0)) }},
                        tunjangan_makan: {{ old('tunjangan_makan', optional($penggajian->komponenGaji->firstWhere('keterangan', '__AUTO_TUNJANGAN_MAKAN__'))->nilai ?? ($penggajian->karyawan->tunjangan_makan ?? 0)) }},
                        tunjangan_btn: {{ old('tunjangan_btn', optional($penggajian->komponenGaji->firstWhere('keterangan', '__AUTO_TUNJANGAN_BTN__'))->nilai ?? ($penggajian->karyawan->tunjangan_btn ?? 0)) }},
                        tunjangan_pulsa: {{ old('tunjangan_pulsa', optional($penggajian->komponenGaji->firstWhere('keterangan', '__AUTO_TUNJANGAN_PULSA__'))->nilai ?? ($penggajian->karyawan->tunjangan_pulsa ?? 0)) }},
                        tunjangan: {{ old('tunjangan', $penggajian->tunjangan ?? 0) }},
                        bonus: {{ old('bonus', $penggajian->bonus ?? 0) }},
                        lembur: {{ old('lembur', $penggajian->lembur ?? 0) }},
                        bpjs_karyawan: {{ old('bpjs_karyawan', $penggajian->bpjs_karyawan ?? 0) }},
                        cash_bon: {{ old('cash_bon', $penggajian->cash_bon ?? 0) }},
                        keterlambatan: {{ old('keterlambatan', $penggajian->keterlambatan ?? 0) }},
                        potongan: {{ old('potongan', $penggajian->potongan ?? 0) }}
                    },

                    totalSalary: 0,
                    totalGaji: 0,
                    existingComponents: @json($manualKomponenGaji->values()),

                    @php
                        $ordersData = $penggajian->komponenGaji
                            ->whereNotNull('sales_order_id')
                            ->map(function ($c) {
                                return [
                                    'id' => $c->sales_order_id,
                                    'nomor' => $c->salesOrder->nomor ?? 'N/A',
                                    'netto_penjualan' => $c->netto_penjualan_adjusted ?? $c->netto_penjualan_original,
                                    'komisi' => $c->nilai,
                                    'product_details' => $c->product_details ?? [],
                                ];
                            })
                            ->values();

                        $adjustmentsData = $penggajian->komponenGaji->whereNotNull('sales_order_id')->mapWithKeys(function ($c) {
                            return [
                                $c->sales_order_id => [
                                    'sales_order_id' => $c->sales_order_id,
                                    'cashback_nominal' => $c->cashback_nominal ?? 0,
                                    'overhead_persen' => $c->overhead_persen ?? 0,
                                ],
                            ];
                        });
                    @endphp

                    // Commission Data
                    selectedEmployee: '{{ $penggajian->karyawan_id }}',
                    selectedMonth: {{ $penggajian->bulan }},
                    selectedYear: {{ $penggajian->tahun }},
                    commissionData: {
                        total_commission: {{ $penggajian->komisi ?? 0 }},
                        total_sales: 0,
                        average_margin: 0,
                        orders: @json($ordersData)
                    },
                    commissionLoading: false,
                    salesOrderAdjustments: @json($adjustmentsData),

                    init() {
                        // 1. Calculate total
                        this.calculateTotal();

                        // 2. Auto-trigger commission to ensure data is synced
                        if (this.selectedEmployee && this.selectedMonth && this.selectedYear) {
                            this.calculateCommission();
                        }

                        // 3. Formatting
                        this.$nextTick(() => {
                            const fields = ['gaji_pokok', 'tunjangan_keluarga', 'tunjangan_jabatan',
                                'tunjangan_transport', 'tunjangan_makan', 'tunjangan_btn', 'tunjangan_pulsa',
                                'tunjangan', 'bonus', 'lembur',
                                'bpjs_karyawan', 'cash_bon', 'keterlambatan', 'potongan'
                            ];
                            fields.forEach(f => {
                                const el = document.getElementById(f);
                                if (el) el.value = this.formatRibu(this.salaryComponents[f] || 0);
                            });
                        });
                    },

                    async calculateCommission() {
                        this.commissionLoading = true;
                        try {
                            const response = await fetch('{{ route('hr.penggajian.get-komisi') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    karyawan_id: this.selectedEmployee,
                                    bulan: this.selectedMonth,
                                    tahun: this.selectedYear,
                                    sales_order_adjustments: this.salesOrderAdjustments
                                })
                            });

                            const result = await response.json();
                            if (result.success) {
                                this.commissionData = result.data;
                                this.calculateTotal();
                            }
                        } catch (error) {
                            console.error('Error calculating commission:', error);
                        } finally {
                            this.commissionLoading = false;
                        }
                    },

                    setSalesOrderAdjustment(orderId, field, value) {
                        if (!this.salesOrderAdjustments[orderId]) {
                            this.salesOrderAdjustments[orderId] = {
                                sales_order_id: orderId,
                                cashback_nominal: 0,
                                overhead_persen: 0
                            };
                        }
                        this.salesOrderAdjustments[orderId][field] = parseFloat(value) || 0;
                    },

                    calculateTotal() {
                        // Calculate income from form inputs
                        const income = parseFloat(this.salaryComponents.gaji_pokok || 0) +
                            parseFloat(this.salaryComponents.tunjangan_keluarga || 0) +
                            parseFloat(this.salaryComponents.tunjangan_jabatan || 0) +
                            parseFloat(this.salaryComponents.tunjangan_transport || 0) +
                            parseFloat(this.salaryComponents.tunjangan_makan || 0) +
                            parseFloat(this.salaryComponents.tunjangan_btn || 0) +
                            parseFloat(this.salaryComponents.tunjangan_pulsa || 0) +
                            parseFloat(this.salaryComponents.tunjangan || 0) +
                            parseFloat(this.salaryComponents.bonus || 0) +
                            parseFloat(this.salaryComponents.lembur || 0);

                        // Add commission from Alpine
                        const commission = parseFloat(this.commissionData.total_commission || 0);

                        // Add existing income components (filter out automated ones that are already in income above)
                        const existingIncome = this.existingComponents
                            .filter(comp => comp.jenis === 'pendapatan' && !comp.nama_komponen.includes('Komisi Penjualan'))
                            .reduce((sum, comp) => sum + parseFloat(comp.nilai || 0), 0);

                        // Calculate deductions from form inputs
                        const deductions = parseFloat(this.salaryComponents.bpjs_karyawan || 0) +
                            parseFloat(this.salaryComponents.cash_bon || 0) +
                            parseFloat(this.salaryComponents.keterlambatan || 0) +
                            parseFloat(this.salaryComponents.potongan || 0);

                        // Add existing deduction components
                        const existingDeductions = this.existingComponents
                            .filter(comp => comp.jenis === 'potongan')
                            .reduce((sum, comp) => sum + parseFloat(comp.nilai || 0), 0);

                        this.totalGaji = income + commission + existingIncome;
                        this.totalSalary = Math.max(0, this.totalGaji - deductions - existingDeductions);
                    },

                    formatRupiah(amount) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(amount || 0);
                    },

                    formatRibu(value) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(parseFloat(value) || 0);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>

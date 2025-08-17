<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Tambah Penggajian'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Buat Penggajian Baru</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Sistem perhitungan gaji dengan komisi otomatis berdasarkan margin penjualan
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('hr.penggajian.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Penggajian
                </a>
            </div>
        </div>

        {{-- Form Section --}}
        <form id="payrollForm" action="{{ route('hr.penggajian.store') }}" method="POST" x-data="payrollCalculator()"
            x-init="init()" class="space-y-6">
            @csrf

            {{-- Hidden inputs for disabled fields --}}
            <input type="hidden" name="bulan" x-model="selectedMonth">
            <input type="hidden" name="tahun" x-model="selectedYear">

            {{-- Employee Selection Card --}}
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
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Data karyawan dan periode penggajian</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Employee Selection --}}
                    <div class="lg:col-span-2">
                        <label for="karyawan_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Karyawan <span class="text-red-500">*</span>
                        </label>
                        <select name="karyawan_id" id="karyawan_id" required x-model="selectedEmployee"
                            @change="onEmployeeChange()"
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('karyawan_id') border-red-500 @enderror">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('karyawan_id', $selectedKaryawan?->id) == $k->id ? 'selected' : '' }}
                                    data-gaji-pokok="{{ $k->gaji_pokok }}" data-nama="{{ $k->nama_lengkap }}"
                                    data-nip="{{ $k->nip }}" data-department="{{ $k->department->nama ?? 'N/A' }}"
                                    data-jabatan="{{ $k->jabatan->nama ?? 'N/A' }}"
                                    data-tunjangan-keluarga="{{ $k->tunjangan_keluarga ?? 0 }}"
                                    data-tunjangan-jabatan="{{ $k->tunjangan_jabatan ?? 0 }}"
                                    data-tunjangan-transport="{{ $k->tunjangan_transport ?? 0 }}"
                                    data-tunjangan-makan="{{ $k->tunjangan_makan ?? 0 }}"
                                    data-tunjangan-pulsa="{{ $k->tunjangan_pulsa ?? 0 }}"
                                    data-default-tunjangan="{{ $k->default_tunjangan ?? 0 }}"
                                    data-default-bonus="{{ $k->default_bonus ?? 0 }}" data-bpjs="{{ $k->bpjs ?? 0 }}"
                                    data-default-potongan="{{ $k->default_potongan ?? 0 }}">
                                    {{ $k->nama_lengkap }} - {{ $k->nip }}
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Month Selection --}}
                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bulan <span class="text-red-500">*</span>
                        </label>
                        <select name="bulan" id="bulan" required x-model="selectedMonth" readonly
                            @change="calculateCommission()"
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 bg-gray-100 dark:bg-gray-700 cursor-not-allowed @error('bulan') border-red-500 @enderror"
                            disabled>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ old('bulan', $bulanSekarang) == $i ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        @error('bulan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Year Selection --}}
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <select name="tahun" id="tahun" required x-model="selectedYear" readonly
                            @change="calculateCommission()"
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 bg-gray-100 dark:bg-gray-700 cursor-not-allowed @error('tahun') border-red-500 @enderror"
                            disabled>
                            @for ($i = Carbon\Carbon::now()->year - 2; $i <= Carbon\Carbon::now()->year + 1; $i++)
                                <option value="{{ $i }}"
                                    {{ old('tahun', $tahunSekarang) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Employee Info Display --}}
                <div x-show="selectedEmployee" x-transition class="px-6 pb-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Informasi Karyawan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">Nama:</span>
                                <span x-text="employeeInfo.nama"
                                    class="font-medium text-gray-900 dark:text-white"></span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">NIP:</span>
                                <span x-text="employeeInfo.nip"
                                    class="font-medium text-gray-900 dark:text-white"></span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">Department:</span>
                                <span x-text="employeeInfo.department"
                                    class="font-medium text-gray-900 dark:text-white"></span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block">Jabatan:</span>
                                <span x-text="employeeInfo.jabatan"
                                    class="font-medium text-gray-900 dark:text-white"></span>
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                        Komponen Gaji
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Komponen pendapatan dan potongan gaji</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Basic Salary --}}
                    <div>
                        <label for="gaji_pokok"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gaji Pokok <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="gaji_pokok" id="gaji_pokok" required min="0"
                                step="1000" x-model="salaryComponents.gaji_pokok" @input="calculateTotal()"
                                value="{{ old('gaji_pokok', $gajiPokok) }}"
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
                            <input type="number" name="tunjangan_keluarga" id="tunjangan_keluarga" min="0"
                                step="1000" x-model="salaryComponents.tunjangan_keluarga"
                                @input="calculateTotal()"
                                value="{{ old('tunjangan_keluarga', $salaryComponents['tunjangan_keluarga'] ?? 0) }}"
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
                            <input type="number" name="tunjangan_jabatan" id="tunjangan_jabatan" min="0"
                                step="1000" x-model="salaryComponents.tunjangan_jabatan" @input="calculateTotal()"
                                value="{{ old('tunjangan_jabatan', $salaryComponents['tunjangan_jabatan'] ?? 0) }}"
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
                            <input type="number" name="tunjangan_transport" id="tunjangan_transport" min="0"
                                step="1000" x-model="salaryComponents.tunjangan_transport"
                                @input="calculateTotal()"
                                value="{{ old('tunjangan_transport', $salaryComponents['tunjangan_transport'] ?? 0) }}"
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
                            <input type="number" name="tunjangan_makan" id="tunjangan_makan" min="0"
                                step="1000" x-model="salaryComponents.tunjangan_makan" @input="calculateTotal()"
                                value="{{ old('tunjangan_makan', $salaryComponents['tunjangan_makan'] ?? 0) }}"
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
                            <input type="number" name="tunjangan" id="tunjangan" min="0" step="1000"
                                x-model="salaryComponents.tunjangan" @input="calculateTotal()"
                                value="{{ old('tunjangan', $salaryComponents['default_tunjangan'] ?? 0) }}"
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
                            <input type="number" name="bonus" id="bonus" min="0" step="1000"
                                x-model="salaryComponents.bonus" @input="calculateTotal()"
                                value="{{ old('bonus', $salaryComponents['default_bonus'] ?? 0) }}"
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
                            <input type="number" name="lembur" id="lembur" min="0" step="1000"
                                x-model="salaryComponents.lembur" @input="calculateTotal()"
                                value="{{ old('lembur', 0) }}"
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
                            <input type="number" name="bpjs_karyawan" id="bpjs_karyawan" min="0"
                                step="1000" x-model="salaryComponents.bpjs_karyawan" @input="calculateTotal()"
                                value="{{ old('bpjs_karyawan', $salaryComponents['bpjs'] ?? 0) }}"
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
                            <input type="number" name="cash_bon" id="cash_bon" min="0" step="1000"
                                x-model="salaryComponents.cash_bon" @input="calculateTotal()"
                                value="{{ old('cash_bon', 0) }}"
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
                            <input type="number" name="keterlambatan" id="keterlambatan" min="0"
                                step="1000" x-model="salaryComponents.keterlambatan" @input="calculateTotal()"
                                value="{{ old('keterlambatan', 0) }}"
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
                            <input type="number" name="potongan" id="potongan" min="0" step="1000"
                                x-model="salaryComponents.potongan" @input="calculateTotal()"
                                value="{{ old('potongan', $salaryComponents['default_potongan'] ?? 0) }}"
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
                            value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                    </div>
                </div>
            </div>

            {{-- Commission Calculation Card --}}
            <div x-show="commissionData.orders.length > 0" x-transition
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
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

                    {{-- Commission Details --}}
                    <div x-show="!commissionLoading && commissionData.orders.length > 0" class="space-y-6">
                        {{-- Commission Summary Card --}}
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                            <h4
                                class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 00-2 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Ringkasan Perhitungan Komisi
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                {{-- Total Sales Orders --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Sales Order</div>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400"
                                        x-text="commissionData.orders.length"></div>
                                </div>

                                {{-- Total Revenue --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Penjualan</div>
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400"
                                        x-text="formatRupiah(commissionData.total_sales || 0)"></div>
                                </div>

                                {{-- Average Margin --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Margin</div>
                                    <div class="text-lg font-bold"
                                        :class="{
                                            'text-red-600 dark:text-red-400': (commissionData.average_margin || 0) <=
                                                30,
                                            'text-yellow-600 dark:text-yellow-400': (commissionData.average_margin ||
                                                0) > 30 && (commissionData.average_margin || 0) <= 50,
                                            'text-green-600 dark:text-green-400': (commissionData.average_margin || 0) >
                                                50
                                        }"
                                        x-text="(commissionData.average_margin || 0).toFixed(2) + '%'"></div>
                                </div>

                                {{-- Total Commission --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Komisi</div>
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400"
                                        x-text="formatRupiah(commissionData.total_commission)"></div>
                                </div>
                            </div>

                            {{-- Commission Rate Breakdown --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Breakdown Rate
                                    Komisi</h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                                    <template x-for="tier in commissionData.commission_tiers" :key="tier.range">
                                        <div
                                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                            <span class="text-gray-600 dark:text-gray-400" x-text="tier.range"></span>
                                            <span class="font-medium" x-text="tier.rate + '%'"></span>
                                            <span class="text-green-600 dark:text-green-400 font-medium"
                                                x-text="formatRupiah(tier.amount || 0)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Detailed Commission Table --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sales Order
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nilai Jual
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nilai Beli
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Margin %
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Rate Komisi
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Komisi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="order in commissionData.orders" :key="order.id">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
                                                x-text="order.nomor">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"
                                                x-text="order.customer">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right"
                                                x-text="formatRupiah(order.harga_jual)">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right"
                                                x-text="formatRupiah(order.harga_beli)">
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">
                                                <span x-text="order.margin_persen + '%'"
                                                    :class="{
                                                        'text-red-600 dark:text-red-400': order.margin_persen <= 30,
                                                        'text-yellow-600 dark:text-yellow-400': order.margin_persen >
                                                            30 && order.margin_persen <= 50,
                                                        'text-green-600 dark:text-green-400': order.margin_persen > 50
                                                    }"></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right"
                                                x-text="(order.commission_rate || 0) + '%'">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right"
                                                x-text="formatRupiah(order.komisi)">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white text-right">
                                            Total Komisi:
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-green-600 dark:text-green-400 text-right"
                                            x-text="formatRupiah(commissionData.total_commission)">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Hidden commission input --}}
                    <input type="hidden" name="komisi" x-model="commissionData.total_commission">
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
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Total gaji yang akan diterima karyawan</p>
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
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tunjangan Keluarga:</span>
                                    <span class="font-medium"
                                        x-text="formatRupiah(salaryComponents.tunjangan_keluarga)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tunjangan Jabatan:</span>
                                    <span class="font-medium"
                                        x-text="formatRupiah(salaryComponents.tunjangan_jabatan)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tunjangan Transport:</span>
                                    <span class="font-medium"
                                        x-text="formatRupiah(salaryComponents.tunjangan_transport)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tunjangan Makan:</span>
                                    <span class="font-medium"
                                        x-text="formatRupiah(salaryComponents.tunjangan_makan)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tunjangan Lainnya:</span>
                                    <span class="font-medium"
                                        x-text="formatRupiah(salaryComponents.tunjangan)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Bonus:</span>
                                    <span class="font-medium" x-text="formatRupiah(salaryComponents.bonus)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Lembur:</span>
                                    <span class="font-medium" x-text="formatRupiah(salaryComponents.lembur)"></span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-gray-600 dark:text-gray-400">Komisi Penjualan:</span>
                                    <span class="font-medium text-green-600 dark:text-green-400"
                                        x-text="formatRupiah(commissionData.total_commission)"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Deduction Summary --}}
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Potongan</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">BPJS:</span>
                                    <span class="font-medium text-red-600 dark:text-red-400"
                                        x-text="formatRupiah(salaryComponents.bpjs_karyawan)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Cash Bon:</span>
                                    <span class="font-medium text-red-600 dark:text-red-400"
                                        x-text="formatRupiah(salaryComponents.cash_bon)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Keterlambatan:</span>
                                    <span class="font-medium text-red-600 dark:text-red-400"
                                        x-text="formatRupiah(salaryComponents.keterlambatan)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Potongan Lainnya:</span>
                                    <span class="font-medium text-red-600 dark:text-red-400"
                                        x-text="formatRupiah(salaryComponents.potongan)"></span>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-base font-semibold text-gray-900 dark:text-white">Total Gaji
                                        (Bruto):</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400"
                                        x-text="formatRupiah(totalGaji)"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">THP (Take Home
                                        Pay):</span>
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
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
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
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('status') border-red-500 @enderror">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="disetujui" {{ old('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="dibayar" {{ old('status') == 'dibayar' ? 'selected' : '' }}>Dibayar
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan
                        </label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200"
                            placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-between items-center pt-6">
                <a href="{{ route('hr.penggajian.index') }}"
                    class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 font-medium">
                    Batal
                </a>
                <div class="flex space-x-3">
                    <button type="button" @click="resetForm()"
                        class="px-6 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 font-medium">
                        Reset
                    </button>
                    <button type="submit"
                        class="px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-medium transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Penggajian
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function payrollCalculator() {
                return {
                    selectedEmployee: '{{ old('karyawan_id', $selectedKaryawan?->id ?? '') }}',
                    selectedMonth: {{ old('bulan', $bulanSekarang) }},
                    selectedYear: {{ old('tahun', $tahunSekarang) }},
                    commissionLoading: false,

                    employeeInfo: {
                        nama: '',
                        nip: '',
                        department: '',
                        jabatan: ''
                    },

                    salaryComponents: {
                        gaji_pokok: {{ old('gaji_pokok', $gajiPokok) }},
                        tunjangan_keluarga: {{ old('tunjangan_keluarga', $salaryComponents['tunjangan_keluarga'] ?? 0) }},
                        tunjangan_jabatan: {{ old('tunjangan_jabatan', $salaryComponents['tunjangan_jabatan'] ?? 0) }},
                        tunjangan_transport: {{ old('tunjangan_transport', $salaryComponents['tunjangan_transport'] ?? 0) }},
                        tunjangan_makan: {{ old('tunjangan_makan', $salaryComponents['tunjangan_makan'] ?? 0) }},
                        tunjangan: {{ old('tunjangan', $salaryComponents['default_tunjangan'] ?? 0) }},
                        bonus: {{ old('bonus', $salaryComponents['default_bonus'] ?? 0) }},
                        lembur: {{ old('lembur', 0) }},
                        bpjs_karyawan: {{ old('bpjs_karyawan', $salaryComponents['bpjs'] ?? 0) }},
                        cash_bon: {{ old('cash_bon', 0) }},
                        keterlambatan: {{ old('keterlambatan', 0) }},
                        potongan: {{ old('potongan', $salaryComponents['default_potongan'] ?? 0) }}
                    },

                    commissionData: {
                        total_commission: 0,
                        total_sales: 0,
                        average_margin: 0,
                        commission_tiers: [],
                        orders: []
                    },

                    totalSalary: 0,
                    totalGaji: 0, // Total bruto sebelum potongan

                    init() {
                        this.calculateTotal();
                        if (this.selectedEmployee) {
                            this.onEmployeeChange();
                            this.calculateCommission();
                        }
                    },

                    onEmployeeChange() {
                        const select = document.getElementById('karyawan_id');
                        const selectedOption = select.options[select.selectedIndex];

                        if (selectedOption && selectedOption.value) {
                            this.salaryComponents.gaji_pokok = parseFloat(selectedOption.dataset.gajiPokok) || 0;
                            this.salaryComponents.tunjangan_keluarga = parseFloat(selectedOption.dataset.tunjanganKeluarga) ||
                                0;
                            this.salaryComponents.tunjangan_jabatan = parseFloat(selectedOption.dataset.tunjanganJabatan) || 0;
                            this.salaryComponents.tunjangan_transport = parseFloat(selectedOption.dataset.tunjanganTransport) ||
                                0;
                            this.salaryComponents.tunjangan_makan = parseFloat(selectedOption.dataset.tunjanganMakan) || 0;
                            this.salaryComponents.tunjangan = parseFloat(selectedOption.dataset.defaultTunjangan) || 0;
                            this.salaryComponents.bonus = parseFloat(selectedOption.dataset.defaultBonus) || 0;
                            this.salaryComponents.bpjs_karyawan = parseFloat(selectedOption.dataset.bpjs) || 0;
                            this.salaryComponents.potongan = parseFloat(selectedOption.dataset.defaultPotongan) || 0;

                            this.employeeInfo = {
                                nama: selectedOption.dataset.nama || '',
                                nip: selectedOption.dataset.nip || '',
                                department: selectedOption.dataset.department || '',
                                jabatan: selectedOption.dataset.jabatan || ''
                            };

                            // Update form inputs
                            document.getElementById('gaji_pokok').value = this.salaryComponents.gaji_pokok;
                            document.getElementById('tunjangan_keluarga').value = this.salaryComponents.tunjangan_keluarga;
                            document.getElementById('tunjangan_jabatan').value = this.salaryComponents.tunjangan_jabatan;
                            document.getElementById('tunjangan_transport').value = this.salaryComponents.tunjangan_transport;
                            document.getElementById('tunjangan_makan').value = this.salaryComponents.tunjangan_makan;
                            document.getElementById('tunjangan').value = this.salaryComponents.tunjangan;
                            document.getElementById('bonus').value = this.salaryComponents.bonus;
                            document.getElementById('bpjs_karyawan').value = this.salaryComponents.bpjs_karyawan;
                            document.getElementById('potongan').value = this.salaryComponents.potongan;

                            this.calculateTotal();
                            this.calculateCommission();
                        }
                    },

                    async calculateCommission() {
                        if (!this.selectedEmployee || !this.selectedMonth || !this.selectedYear) {
                            this.commissionData = {
                                total_commission: 0,
                                total_sales: 0,
                                average_margin: 0,
                                commission_tiers: [],
                                orders: []
                            };
                            this.calculateTotal();
                            return;
                        }

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
                                    tahun: this.selectedYear
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                const salesDetails = data.sales_details || [];

                                // Calculate total sales and average margin
                                let totalSales = 0;
                                let totalMargin = 0;
                                let commissionTiers = {};

                                salesDetails.forEach(order => {
                                    totalSales += parseFloat(order.harga_jual || 0);
                                    totalMargin += parseFloat(order.margin_persen || 0);

                                    // Group by commission rate
                                    const rate = parseFloat(order.commission_rate || 0);
                                    if (!commissionTiers[rate]) {
                                        commissionTiers[rate] = {
                                            range: this.getMarginRangeForRate(rate),
                                            rate: rate,
                                            amount: 0
                                        };
                                    }
                                    commissionTiers[rate].amount += parseFloat(order.komisi || 0);
                                });

                                const averageMargin = salesDetails.length > 0 ? totalMargin / salesDetails.length : 0;

                                this.commissionData = {
                                    total_commission: data.komisi || 0,
                                    total_sales: totalSales,
                                    average_margin: averageMargin,
                                    commission_tiers: Object.values(commissionTiers),
                                    orders: salesDetails
                                };
                                console.log(data);
                            } else {
                                this.commissionData = {
                                    total_commission: 0,
                                    total_sales: 0,
                                    average_margin: 0,
                                    commission_tiers: [],
                                    orders: []
                                };
                                console.error('Commission calculation failed:', data.message);
                            }
                        } catch (error) {
                            console.error('Error calculating commission:', error);
                            this.commissionData = {
                                total_commission: 0,
                                total_sales: 0,
                                average_margin: 0,
                                commission_tiers: [],
                                orders: []
                            };
                        } finally {
                            this.commissionLoading = false;
                            this.calculateTotal();
                        }
                    },

                    getMarginRangeForRate(rate) {
                        // Map commission rates to margin ranges based on the tier system
                        const tierMap = {
                            1.00: '18-20%',
                            1.25: '20.5-25%',
                            1.50: '25.5-30%',
                            1.75: '30.5-35%',
                            2.00: '35.5-40%',
                            2.25: '40.5-45%',
                            2.50: '45.5-50%',
                            2.75: '50.5-55%',
                            3.00: '55.5-60%',
                            3.25: '60.5-65%',
                            3.50: '65.5-70%',
                            3.75: '70.5-75%',
                            4.00: '75.5-80%',
                            4.25: '80.5-85%',
                            4.50: '85.5-90%',
                            4.75: '90.5-95%',
                            5.00: '95.5-100%',
                            5.25: '101-110%',
                            5.50: '111-125%',
                            5.75: '126-140%',
                            6.00: '141-160%',
                            6.25: '161-180%',
                            6.50: '181-200%',
                            7.00: '201-225%',
                            7.25: '226-250%',
                            7.50: '251-275%',
                            8.00: '276-300%',
                            8.25: '301-325%',
                            8.50: '326-350%',
                            9.00: '351-400%',
                            9.50: '401-450%',
                            10.00: '451-500%'
                        };

                        return tierMap[rate] || `${rate}%`;
                    },

                    calculateTotal() {
                        // Hitung total pendapatan
                        const income = parseFloat(this.salaryComponents.gaji_pokok || 0) +
                            parseFloat(this.salaryComponents.tunjangan_keluarga || 0) +
                            parseFloat(this.salaryComponents.tunjangan_jabatan || 0) +
                            parseFloat(this.salaryComponents.tunjangan_transport || 0) +
                            parseFloat(this.salaryComponents.tunjangan_makan || 0) +
                            parseFloat(this.salaryComponents.tunjangan || 0) +
                            parseFloat(this.salaryComponents.bonus || 0) +
                            parseFloat(this.salaryComponents.lembur || 0) +
                            parseFloat(this.commissionData.total_commission || 0);

                        // Hitung total potongan
                        const deductions = parseFloat(this.salaryComponents.bpjs_karyawan || 0) +
                            parseFloat(this.salaryComponents.cash_bon || 0) +
                            parseFloat(this.salaryComponents.keterlambatan || 0) +
                            parseFloat(this.salaryComponents.potongan || 0);

                        // Total gaji bruto (sebelum potongan)
                        this.totalGaji = income;

                        // THP (Take Home Pay) = Total - Potongan
                        this.totalSalary = Math.max(0, income - deductions);
                    },

                    formatRupiah(amount) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(amount || 0);
                    },

                    resetForm() {
                        if (confirm('Apakah Anda yakin ingin mereset form ini?')) {
                            document.getElementById('payrollForm').reset();
                            this.selectedEmployee = '';
                            this.salaryComponents = {
                                gaji_pokok: 0,
                                tunjangan_keluarga: 0,
                                tunjangan_jabatan: 0,
                                tunjangan_transport: 0,
                                tunjangan_makan: 0,
                                tunjangan: 0,
                                bonus: 0,
                                lembur: 0,
                                bpjs_karyawan: 0,
                                cash_bon: 0,
                                keterlambatan: 0,
                                potongan: 0
                            };
                            this.commissionData = {
                                total_commission: 0,
                                orders: []
                            };
                            this.employeeInfo = {
                                nama: '',
                                nip: '',
                                department: '',
                                jabatan: ''
                            };
                            this.calculateTotal();
                        }
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>

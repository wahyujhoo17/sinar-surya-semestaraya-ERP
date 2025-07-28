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
                        <label for="gaji_pokok" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gaji Pokok <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="gaji_pokok" id="gaji_pokok" required min="0"
                                step="1000" x-model="salaryComponents.gaji_pokok" @input="calculateTotal()"
                                value="{{ old('gaji_pokok', $penggajian->gaji_pokok) }}"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('gaji_pokok') border-red-500 @enderror">
                        </div>
                        @error('gaji_pokok')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tunjangan BTN --}}
                    <div>
                        <label for="tunjangan_btn"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tunjangan BTN
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="tunjangan_btn" id="tunjangan_btn" min="0" step="1000"
                                x-model="salaryComponents.tunjangan_btn" @input="calculateTotal()"
                                value="{{ old('tunjangan_btn', $penggajian->karyawan->tunjangan_btn ?? 0) }}"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                        </div>
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
                                value="{{ old('tunjangan_keluarga', $penggajian->karyawan->tunjangan_keluarga ?? 0) }}"
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
                                value="{{ old('tunjangan_jabatan', $penggajian->karyawan->tunjangan_jabatan ?? 0) }}"
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
                                value="{{ old('tunjangan_transport', $penggajian->karyawan->tunjangan_transport ?? 0) }}"
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
                                value="{{ old('tunjangan_makan', $penggajian->karyawan->tunjangan_makan ?? 0) }}"
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
                                value="{{ old('tunjangan', $penggajian->tunjangan ?? 0) }}"
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
                                value="{{ old('bonus', $penggajian->bonus ?? 0) }}"
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
                                value="{{ old('lembur', $penggajian->lembur ?? 0) }}"
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
                                value="{{ old('bpjs_karyawan', $penggajian->bpjs_karyawan ?? 0) }}"
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
                                value="{{ old('cash_bon', $penggajian->cash_bon ?? 0) }}"
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
                                value="{{ old('keterlambatan', $penggajian->keterlambatan ?? 0) }}"
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
                                value="{{ old('potongan', $penggajian->potongan ?? 0) }}"
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

            {{-- Existing Salary Components --}}
            @if ($penggajian->komponenGaji->count() > 0)
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
                                    @foreach ($penggajian->komponenGaji as $komponen)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
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
                                    <span class="text-gray-600 dark:text-gray-400">Tunjangan:</span>
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
                                @foreach ($penggajian->komponenGaji->where('jenis', 'pendapatan') as $komponen)
                                    <div class="flex justify-between border-t pt-2">
                                        <span
                                            class="text-gray-600 dark:text-gray-400">{{ $komponen->nama_komponen }}:</span>
                                        <span class="font-medium text-green-600 dark:text-green-400">
                                            Rp {{ number_format($komponen->nilai, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Deduction Summary --}}
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Potongan</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Potongan:</span>
                                    <span class="font-medium text-red-600 dark:text-red-400"
                                        x-text="formatRupiah(salaryComponents.potongan)"></span>
                                </div>
                                @foreach ($penggajian->komponenGaji->where('jenis', 'potongan') as $komponen)
                                    <div class="flex justify-between">
                                        <span
                                            class="text-gray-600 dark:text-gray-400">{{ $komponen->nama_komponen }}:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400">
                                            Rp {{ number_format($komponen->nilai, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
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
                            <option value="draft"
                                {{ old('status', $penggajian->status) == 'draft' ? 'selected' : '' }}>Draft</option>
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
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                <a href="{{ route('hr.penggajian.index') }}"
                    class="px-6 py-3 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 font-medium">
                    Batal
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('hr.penggajian.show', $penggajian->id) }}"
                        class="px-6 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 font-medium">
                        Detail
                    </a>
                    <button type="submit"
                        class="px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white font-medium transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
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
                        gaji_pokok: {{ old('gaji_pokok', $penggajian->gaji_pokok) }},
                        tunjangan_btn: {{ old('tunjangan_btn', $penggajian->karyawan->tunjangan_btn ?? 0) }},
                        tunjangan_keluarga: {{ old('tunjangan_keluarga', $penggajian->karyawan->tunjangan_keluarga ?? 0) }},
                        tunjangan_jabatan: {{ old('tunjangan_jabatan', $penggajian->karyawan->tunjangan_jabatan ?? 0) }},
                        tunjangan_transport: {{ old('tunjangan_transport', $penggajian->karyawan->tunjangan_transport ?? 0) }},
                        tunjangan_makan: {{ old('tunjangan_makan', $penggajian->karyawan->tunjangan_makan ?? 0) }},
                        tunjangan: {{ old('tunjangan', $penggajian->tunjangan ?? 0) }},
                        bonus: {{ old('bonus', $penggajian->bonus ?? 0) }},
                        lembur: {{ old('lembur', $penggajian->lembur ?? 0) }},
                        bpjs_karyawan: {{ old('bpjs_karyawan', $penggajian->bpjs_karyawan ?? 0) }},
                        cash_bon: {{ old('cash_bon', $penggajian->cash_bon ?? 0) }},
                        keterlambatan: {{ old('keterlambatan', $penggajian->keterlambatan ?? 0) }},
                        potongan: {{ old('potongan', $penggajian->potongan ?? 0) }}
                    },

                    totalSalary: 0,
                    totalGaji: 0, // Total bruto sebelum potongan
                    existingComponents: @json($penggajian->komponenGaji),

                    init() {
                        this.calculateTotal();
                    },

                    calculateTotal() {
                        // Calculate income from form inputs
                        const income = parseFloat(this.salaryComponents.gaji_pokok || 0) +
                            parseFloat(this.salaryComponents.tunjangan_btn || 0) +
                            parseFloat(this.salaryComponents.tunjangan_keluarga || 0) +
                            parseFloat(this.salaryComponents.tunjangan_jabatan || 0) +
                            parseFloat(this.salaryComponents.tunjangan_transport || 0) +
                            parseFloat(this.salaryComponents.tunjangan_makan || 0) +
                            parseFloat(this.salaryComponents.tunjangan || 0) +
                            parseFloat(this.salaryComponents.bonus || 0) +
                            parseFloat(this.salaryComponents.lembur || 0);

                        // Add existing income components (like commission)
                        const existingIncome = this.existingComponents
                            .filter(comp => comp.jenis === 'pendapatan')
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

                        this.totalGaji = income + existingIncome;
                        this.totalSalary = Math.max(0, this.totalGaji - deductions - existingDeductions);
                    },

                    formatRupiah(amount) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(amount || 0);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>

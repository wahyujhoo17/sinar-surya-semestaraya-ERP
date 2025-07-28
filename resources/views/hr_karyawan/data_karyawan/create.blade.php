<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Karyawan Baru</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Tambahkan data karyawan baru ke sistem
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('hr.karyawan.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    Kembali ke Daftar Karyawan
                </a>
            </div>
        </div>

        {{-- Form Section --}}
        <form action="{{ route('hr.karyawan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Personal Information Card --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/80">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Pribadi</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Data pribadi karyawan</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIP --}}
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('nip') border-red-500 dark:border-red-500 @enderror">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('nama_lengkap') border-red-500 dark:border-red-500 @enderror">
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('email') border-red-500 dark:border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('telepon') border-red-500 dark:border-red-500 @enderror">
                        @error('telepon')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label for="tempat_lahir"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
                            required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('tempat_lahir') border-red-500 dark:border-red-500 @enderror">
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="tanggal_lahir"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                            value="{{ old('tanggal_lahir') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('tanggal_lahir') border-red-500 dark:border-red-500 @enderror">
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="jenis_kelamin"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('jenis_kelamin') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" id="alamat" rows="3" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('alamat') border-red-500 dark:border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
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
                        Komponen Gaji & Tunjangan
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Konfigurasi default untuk komponen gaji
                        karyawan
                    </p>
                </div>
                <div class="p-6">
                    {{-- Tunjangan Section --}}
                    <div class="mb-6">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tunjangan (Pendapatan)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            {{-- Tunjangan BTN --}}
                            <div>
                                <label for="tunjangan_btn"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan BTN
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="tunjangan_btn" id="tunjangan_btn"
                                        value="{{ old('tunjangan_btn', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Tunjangan Keluarga --}}
                            <div>
                                <label for="tunjangan_keluarga"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan Keluarga
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="tunjangan_keluarga" id="tunjangan_keluarga"
                                        value="{{ old('tunjangan_keluarga', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Tunjangan Jabatan --}}
                            <div>
                                <label for="tunjangan_jabatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan Jabatan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="tunjangan_jabatan" id="tunjangan_jabatan"
                                        value="{{ old('tunjangan_jabatan', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Tunjangan Transport --}}
                            <div>
                                <label for="tunjangan_transport"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan Transport
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="tunjangan_transport" id="tunjangan_transport"
                                        value="{{ old('tunjangan_transport', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Tunjangan Makan --}}
                            <div>
                                <label for="tunjangan_makan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan Makan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="tunjangan_makan" id="tunjangan_makan"
                                        value="{{ old('tunjangan_makan', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Tunjangan Pulsa --}}
                            <div>
                                <label for="tunjangan_pulsa"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan Pulsa
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="tunjangan_pulsa" id="tunjangan_pulsa"
                                        value="{{ old('tunjangan_pulsa', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Default Tunjangan --}}
                            <div>
                                <label for="default_tunjangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tunjangan Lainnya
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="default_tunjangan" id="default_tunjangan"
                                        value="{{ old('default_tunjangan', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Default Bonus --}}
                            <div>
                                <label for="default_bonus"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Bonus Default
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="default_bonus" id="default_bonus"
                                        value="{{ old('default_bonus', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Default Lembur Rate --}}
                            <div>
                                <label for="default_lembur_rate"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Rate Lembur (per jam)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="default_lembur_rate" id="default_lembur_rate"
                                        value="{{ old('default_lembur_rate', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Potongan Section --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                </path>
                            </svg>
                            Potongan (Pengurangan)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- BPJS --}}
                            <div>
                                <label for="bpjs"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    BPJS
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="bpjs" id="bpjs"
                                        value="{{ old('bpjs', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>

                            {{-- Default Potongan --}}
                            <div>
                                <label for="default_potongan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Potongan Lainnya
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="default_potongan" id="default_potongan"
                                        value="{{ old('default_potongan', 0) }}" min="0" step="1000"
                                        class="pl-10 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employment Information Card --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-gray-800 dark:to-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6">
                            </path>
                        </svg>
                        Informasi Kepegawaian
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Data jabatan dan status kepegawaian</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Department --}}
                    <div>
                        <label for="department_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" id="department_id" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('department_id') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label for="jabatan_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jabatan_id" id="jabatan_id" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('jabatan_id') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}"
                                    {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                    {{ $jabatan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gaji Pokok --}}
                    <div>
                        <label for="gaji_pokok"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Gaji Pokok <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}"
                                min="0" step="1000" required
                                class="pl-10 mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('gaji_pokok') border-red-500 dark:border-red-500 @enderror">
                        </div>
                        @error('gaji_pokok')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Tanggal Masuk --}}
                    <div>
                        <label for="tanggal_masuk"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            value="{{ old('tanggal_masuk') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('tanggal_masuk') border-red-500 dark:border-red-500 @enderror">
                        @error('tanggal_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('status') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                            <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- User Role --}}
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            User Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role_id" id="role_id" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('role_id') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto Karyawan --}}
                    <div class="md:col-span-2">
                        <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Foto Karyawan
                        </label>
                        <div class="mt-2 flex items-center">
                            <div class="flex-shrink-0 h-24 w-24 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center overflow-hidden"
                                id="preview-container">
                                <div id="preview-placeholder"
                                    class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <span class="text-xs text-center">Preview Foto</span>
                                </div>
                                <img src="#" alt="Preview" class="h-full w-full object-cover hidden"
                                    id="preview-image">
                            </div>
                            <label for="foto"
                                class="ml-5 bg-white dark:bg-gray-800 py-2.5 px-4 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 cursor-pointer">
                                Pilih Foto
                                <input id="foto" name="foto" type="file" class="sr-only"
                                    accept="image/*" onchange="previewImage()">
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            PNG, JPG, atau JPEG. Maksimal ukuran 2MB.
                        </p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-between items-center pt-4">
                <button type="reset"
                    class="px-5 py-3 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    Reset Form
                </button>
                <button type="submit"
                    class="px-5 py-3 rounded-md bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white transition-colors duration-200">
                    Simpan Karyawan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewImage() {
                const input = document.getElementById('foto');
                const image = document.getElementById('preview-image');
                const placeholder = document.getElementById('preview-placeholder');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        placeholder.classList.add('hidden');
                        image.classList.remove('hidden');
                        image.src = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
</x-app-layout>

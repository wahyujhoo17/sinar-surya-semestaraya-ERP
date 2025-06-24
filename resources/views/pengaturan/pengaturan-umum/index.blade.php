<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="container mx-auto py-6">
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 mb-6">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Pengaturan Umum</h1>
                <p class="text-gray-600 dark:text-gray-400">Kelola pengaturan umum sistem ERP Sinar Surya</p>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('pengaturan.umum.update') }}" enctype="multipart/form-data"
            x-data="pengaturanUmumForm()" @submit="loading = true">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Tab Navigation -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Kategori Pengaturan
                            </h3>
                            <nav class="space-y-2">
                                <button type="button" @click="activeTab = 'company'"
                                    :class="activeTab === 'company' ?
                                        'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-300' :
                                        'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white border-transparent'"
                                    class="w-full text-left px-3 py-2 text-sm border-l-4 rounded-md transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Informasi Perusahaan
                                    </div>
                                </button>

                                <button type="button" @click="activeTab = 'application'"
                                    :class="activeTab === 'application' ?
                                        'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-300' :
                                        'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white border-transparent'"
                                    class="w-full text-left px-3 py-2 text-sm border-l-4 rounded-md transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Pengaturan Aplikasi
                                    </div>
                                </button>

                                <button type="button" @click="activeTab = 'document'"
                                    :class="activeTab === 'document' ?
                                        'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-300' :
                                        'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white border-transparent'"
                                    class="w-full text-left px-3 py-2 text-sm border-l-4 rounded-md transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Pengaturan Dokumen
                                    </div>
                                </button>

                                <button type="button" @click="activeTab = 'system'"
                                    :class="activeTab === 'system' ?
                                        'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-300' :
                                        'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white border-transparent'"
                                    class="w-full text-left px-3 py-2 text-sm border-l-4 rounded-md transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                            </path>
                                        </svg>
                                        Pengaturan Sistem
                                    </div>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="lg:col-span-2">
                    <!-- Company Settings -->
                    <div x-show="activeTab === 'company'" x-transition
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Informasi Perusahaan
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Company Name -->
                                <div class="md:col-span-2">
                                    <label for="company_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nama Perusahaan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="company_name" name="company_name"
                                        value="{{ old('company_name', $companySettings['company_name']) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Address -->
                                <div class="md:col-span-2">
                                    <label for="company_address"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Alamat Perusahaan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="company_address" name="company_address" rows="3"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>{{ old('company_address', $companySettings['company_address']) }}</textarea>
                                    @error('company_address')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="company_phone"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="company_phone" name="company_phone"
                                        value="{{ old('company_phone', $companySettings['company_phone']) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('company_phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="company_email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Utama <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="company_email" name="company_email"
                                        value="{{ old('company_email', $companySettings['company_email']) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('company_email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="company_city"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kota <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="company_city" name="company_city"
                                        value="{{ old('company_city', $companySettings['company_city']) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('company_city')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label for="company_postal_code"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kode Pos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="company_postal_code" name="company_postal_code"
                                        value="{{ old('company_postal_code', $companySettings['company_postal_code']) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('company_postal_code')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email 2 -->
                                <div>
                                    <label for="company_email_2"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Kedua
                                    </label>
                                    <input type="email" id="company_email_2" name="company_email_2"
                                        value="{{ old('company_email_2', $companySettings['company_email_2'] ?? '') }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('company_email_2')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email 3 -->
                                <div>
                                    <label for="company_email_3"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Ketiga
                                    </label>
                                    <input type="email" id="company_email_3" name="company_email_3"
                                        value="{{ old('company_email_3', $companySettings['company_email_3'] ?? '') }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('company_email_3')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NPWP -->
                                <div>
                                    <label for="company_npwp"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        NPWP
                                    </label>
                                    <input type="text" id="company_npwp" name="company_npwp"
                                        value="{{ old('company_npwp', $companySettings['company_npwp']) }}"
                                        placeholder="00.000.000.0-000.000"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('company_npwp')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Website -->
                                <div>
                                    <label for="company_website"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Website
                                    </label>
                                    <input type="url" id="company_website" name="company_website"
                                        value="{{ old('company_website', $companySettings['company_website']) }}"
                                        placeholder="https://example.com"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('company_website')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Logo -->
                                <div class="md:col-span-2">
                                    <label for="company_logo"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Logo Perusahaan
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        @if ($companySettings['company_logo'] && Storage::disk('public')->exists($companySettings['company_logo']))
                                            <img src="{{ asset('storage/' . $companySettings['company_logo']) }}"
                                                alt="Logo"
                                                class="h-16 w-16 object-contain rounded-lg border border-gray-200 dark:border-gray-600">
                                        @else
                                            <div
                                                class="h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <input type="file" id="company_logo" name="company_logo"
                                                accept="image/*"
                                                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-300">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF
                                                hingga 2MB</p>
                                        </div>
                                    </div>
                                    @error('company_logo')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Settings -->
                    <div x-show="activeTab === 'application'" x-transition
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Pengaturan Aplikasi
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tax Percentage -->
                                <div>
                                    <label for="tax_percentage"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Persentase Pajak (PPN) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" id="tax_percentage" name="tax_percentage"
                                            value="{{ old('tax_percentage', $applicationSettings['tax_percentage']) }}"
                                            step="0.01" min="0" max="100"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 pr-8"
                                            required>
                                        <span class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">%</span>
                                    </div>
                                    @error('tax_percentage')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Currency -->
                                <div>
                                    <label for="default_currency"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mata Uang Default <span class="text-red-500">*</span>
                                    </label>
                                    <select id="default_currency" name="default_currency"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="IDR"
                                            {{ old('default_currency', $applicationSettings['default_currency']) == 'IDR' ? 'selected' : '' }}>
                                            IDR - Rupiah Indonesia</option>
                                        <option value="USD"
                                            {{ old('default_currency', $applicationSettings['default_currency']) == 'USD' ? 'selected' : '' }}>
                                            USD - US Dollar</option>
                                        <option value="EUR"
                                            {{ old('default_currency', $applicationSettings['default_currency']) == 'EUR' ? 'selected' : '' }}>
                                            EUR - Euro</option>
                                    </select>
                                    @error('default_currency')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date Format -->
                                <div>
                                    <label for="date_format"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Format Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <select id="date_format" name="date_format"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="d/m/Y"
                                            {{ old('date_format', $applicationSettings['date_format']) == 'd/m/Y' ? 'selected' : '' }}>
                                            DD/MM/YYYY (31/12/2024)</option>
                                        <option value="m/d/Y"
                                            {{ old('date_format', $applicationSettings['date_format']) == 'm/d/Y' ? 'selected' : '' }}>
                                            MM/DD/YYYY (12/31/2024)</option>
                                        <option value="Y-m-d"
                                            {{ old('date_format', $applicationSettings['date_format']) == 'Y-m-d' ? 'selected' : '' }}>
                                            YYYY-MM-DD (2024-12-31)</option>
                                        <option value="d-m-Y"
                                            {{ old('date_format', $applicationSettings['date_format']) == 'd-m-Y' ? 'selected' : '' }}>
                                            DD-MM-YYYY (31-12-2024)</option>
                                    </select>
                                    @error('date_format')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Timezone -->
                                <div>
                                    <label for="timezone"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Zona Waktu <span class="text-red-500">*</span>
                                    </label>
                                    <select id="timezone" name="timezone"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="Asia/Jakarta"
                                            {{ old('timezone', $applicationSettings['timezone']) == 'Asia/Jakarta' ? 'selected' : '' }}>
                                            Asia/Jakarta (WIB)</option>
                                        <option value="Asia/Makassar"
                                            {{ old('timezone', $applicationSettings['timezone']) == 'Asia/Makassar' ? 'selected' : '' }}>
                                            Asia/Makassar (WITA)</option>
                                        <option value="Asia/Jayapura"
                                            {{ old('timezone', $applicationSettings['timezone']) == 'Asia/Jayapura' ? 'selected' : '' }}>
                                            Asia/Jayapura (WIT)</option>
                                    </select>
                                    @error('timezone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Decimal Separator -->
                                <div>
                                    <label for="decimal_separator"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Pemisah Desimal <span class="text-red-500">*</span>
                                    </label>
                                    <select id="decimal_separator" name="decimal_separator"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value=","
                                            {{ old('decimal_separator', $applicationSettings['decimal_separator']) == ',' ? 'selected' : '' }}>
                                            Koma (,)</option>
                                        <option value="."
                                            {{ old('decimal_separator', $applicationSettings['decimal_separator']) == '.' ? 'selected' : '' }}>
                                            Titik (.)</option>
                                    </select>
                                    @error('decimal_separator')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Thousand Separator -->
                                <div>
                                    <label for="thousand_separator"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Pemisah Ribuan <span class="text-red-500">*</span>
                                    </label>
                                    <select id="thousand_separator" name="thousand_separator"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="."
                                            {{ old('thousand_separator', $applicationSettings['thousand_separator']) == '.' ? 'selected' : '' }}>
                                            Titik (.)</option>
                                        <option value=","
                                            {{ old('thousand_separator', $applicationSettings['thousand_separator']) == ',' ? 'selected' : '' }}>
                                            Koma (,)</option>
                                        <option value=" "
                                            {{ old('thousand_separator', $applicationSettings['thousand_separator']) == ' ' ? 'selected' : '' }}>
                                            Spasi ( )</option>
                                    </select>
                                    @error('thousand_separator')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Language -->
                                <div>
                                    <label for="language"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Bahasa Sistem <span class="text-red-500">*</span>
                                    </label>
                                    <select id="language" name="language"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="id"
                                            {{ old('language', $applicationSettings['language']) == 'id' ? 'selected' : '' }}>
                                            Bahasa Indonesia</option>
                                        <option value="en"
                                            {{ old('language', $applicationSettings['language']) == 'en' ? 'selected' : '' }}>
                                            English</option>
                                    </select>
                                    @error('language')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Items Per Page -->
                                <div>
                                    <label for="items_per_page"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Item per Halaman <span class="text-red-500">*</span>
                                    </label>
                                    <select id="items_per_page" name="items_per_page"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="10"
                                            {{ old('items_per_page', $applicationSettings['items_per_page']) == '10' ? 'selected' : '' }}>
                                            10</option>
                                        <option value="15"
                                            {{ old('items_per_page', $applicationSettings['items_per_page']) == '15' ? 'selected' : '' }}>
                                            15</option>
                                        <option value="25"
                                            {{ old('items_per_page', $applicationSettings['items_per_page']) == '25' ? 'selected' : '' }}>
                                            25</option>
                                        <option value="50"
                                            {{ old('items_per_page', $applicationSettings['items_per_page']) == '50' ? 'selected' : '' }}>
                                            50</option>
                                        <option value="100"
                                            {{ old('items_per_page', $applicationSettings['items_per_page']) == '100' ? 'selected' : '' }}>
                                            100</option>
                                    </select>
                                    @error('items_per_page')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Settings -->
                    <div x-show="activeTab === 'document'" x-transition
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Pengaturan Dokumen
                            </h3>

                            <div class="space-y-6">
                                <!-- Document Prefixes -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Prefix
                                        Nomor Dokumen</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="quotation_prefix"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Quotation
                                            </label>
                                            <input type="text" id="quotation_prefix" name="quotation_prefix"
                                                value="{{ old('quotation_prefix', $documentSettings['quotation_prefix']) }}"
                                                maxlength="10"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div>
                                            <label for="sales_order_prefix"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sales Order
                                            </label>
                                            <input type="text" id="sales_order_prefix" name="sales_order_prefix"
                                                value="{{ old('sales_order_prefix', $documentSettings['sales_order_prefix']) }}"
                                                maxlength="10"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div>
                                            <label for="purchase_request_prefix"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Purchase Request
                                            </label>
                                            <input type="text" id="purchase_request_prefix"
                                                name="purchase_request_prefix"
                                                value="{{ old('purchase_request_prefix', $documentSettings['purchase_request_prefix']) }}"
                                                maxlength="10"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div>
                                            <label for="purchase_order_prefix"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Purchase Order
                                            </label>
                                            <input type="text" id="purchase_order_prefix"
                                                name="purchase_order_prefix"
                                                value="{{ old('purchase_order_prefix', $documentSettings['purchase_order_prefix']) }}"
                                                maxlength="10"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div>
                                            <label for="delivery_order_prefix"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Delivery Order
                                            </label>
                                            <input type="text" id="delivery_order_prefix"
                                                name="delivery_order_prefix"
                                                value="{{ old('delivery_order_prefix', $documentSettings['delivery_order_prefix']) }}"
                                                maxlength="10"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div>
                                            <label for="invoice_prefix"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Invoice
                                            </label>
                                            <input type="text" id="invoice_prefix" name="invoice_prefix"
                                                value="{{ old('invoice_prefix', $documentSettings['invoice_prefix']) }}"
                                                maxlength="10"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Document Terms -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Syarat dan
                                        Ketentuan</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="quotation_terms"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Syarat dan Ketentuan Quotation
                                            </label>
                                            <textarea id="quotation_terms" name="quotation_terms" rows="4"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('quotation_terms', $documentSettings['quotation_terms']) }}</textarea>
                                        </div>

                                        <div>
                                            <label for="invoice_terms"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Syarat dan Ketentuan Invoice
                                            </label>
                                            <textarea id="invoice_terms" name="invoice_terms" rows="4"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('invoice_terms', $documentSettings['invoice_terms']) }}</textarea>
                                        </div>

                                        <div>
                                            <label for="invoice_footer"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Footer Invoice
                                            </label>
                                            <textarea id="invoice_footer" name="invoice_footer" rows="2"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('invoice_footer', $documentSettings['invoice_footer']) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div x-show="activeTab === 'system'" x-transition
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Pengaturan Sistem</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Session Lifetime -->
                                <div>
                                    <label for="session_lifetime"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Waktu Sesi (menit) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="session_lifetime" name="session_lifetime"
                                        value="{{ old('session_lifetime', $systemSettings['session_lifetime']) }}"
                                        min="5" max="1440"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('session_lifetime')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Max Login Attempts -->
                                <div>
                                    <label for="max_login_attempts"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Maksimal Percobaan Login <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="max_login_attempts" name="max_login_attempts"
                                        value="{{ old('max_login_attempts', $systemSettings['max_login_attempts']) }}"
                                        min="3" max="10"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                    @error('max_login_attempts')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Backup Frequency -->
                                <div>
                                    <label for="backup_frequency"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Frekuensi Backup <span class="text-red-500">*</span>
                                    </label>
                                    <select id="backup_frequency" name="backup_frequency"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required>
                                        <option value="daily"
                                            {{ old('backup_frequency', $systemSettings['backup_frequency']) == 'daily' ? 'selected' : '' }}>
                                            Harian</option>
                                        <option value="weekly"
                                            {{ old('backup_frequency', $systemSettings['backup_frequency']) == 'weekly' ? 'selected' : '' }}>
                                            Mingguan</option>
                                        <option value="monthly"
                                            {{ old('backup_frequency', $systemSettings['backup_frequency']) == 'monthly' ? 'selected' : '' }}>
                                            Bulanan</option>
                                    </select>
                                    @error('backup_frequency')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- System Toggles -->
                                <div class="md:col-span-2">
                                    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Fitur
                                        Sistem</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="enable_notifications"
                                                name="enable_notifications"
                                                {{ old('enable_notifications', $systemSettings['enable_notifications']) == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="enable_notifications"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Aktifkan Notifikasi Sistem
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="enable_email_notifications"
                                                name="enable_email_notifications"
                                                {{ old('enable_email_notifications', $systemSettings['enable_email_notifications'] ?? '0') == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="enable_email_notifications"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Aktifkan Notifikasi Email
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="enable_multi_currency"
                                                name="enable_multi_currency"
                                                {{ old('enable_multi_currency', $systemSettings['enable_multi_currency'] ?? '0') == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="enable_multi_currency"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Aktifkan Multi Mata Uang
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="enable_barcode" name="enable_barcode"
                                                {{ old('enable_barcode', $systemSettings['enable_barcode'] ?? '0') == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="enable_barcode"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Aktifkan Barcode/QR Code
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="enable_auto_backup" name="enable_auto_backup"
                                                {{ old('enable_auto_backup', $systemSettings['enable_auto_backup'] ?? '0') == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="enable_auto_backup"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Aktifkan Auto Backup
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="debug_mode" name="debug_mode"
                                                {{ old('debug_mode', $systemSettings['debug_mode'] ?? '0') == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="debug_mode"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Mode Debug (Untuk Development)
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="enable_audit_log" name="enable_audit_log"
                                                {{ old('enable_audit_log', $systemSettings['enable_audit_log'] ?? '0') == '1' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="enable_audit_log"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Aktifkan Audit Log
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <div
                            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Pastikan semua pengaturan sudah benar sebelum menyimpan.
                                    </div>
                                    <button type="submit" :disabled="loading"
                                        class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 focus:ring-offset-primary-200 text-white rounded-lg transition ease-in-out duration-150 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Pengaturan'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function pengaturanUmumForm() {
                return {
                    activeTab: 'company',
                    loading: false,

                    init() {
                        // Set active tab based on URL hash or default to company
                        const hash = window.location.hash.replace('#', '');
                        if (['company', 'application', 'document', 'system'].includes(hash)) {
                            this.activeTab = hash;
                        }

                        // Update URL hash when tab changes
                        this.$watch('activeTab', (value) => {
                            window.location.hash = value;
                        });
                    }
                }
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Custom file input styling */
            input[type="file"]::-webkit-file-upload-button {
                visibility: hidden;
            }

            input[type="file"]::before {
                content: 'Pilih File';
                display: inline-block;
                background: linear-gradient(top, #f9f9f9, #e3e3e3);
                border: 1px solid #999;
                border-radius: 3px;
                padding: 5px 8px;
                outline: none;
                white-space: nowrap;
                -webkit-user-select: none;
                cursor: pointer;
                text-shadow: 1px 1px #fff;
                font-weight: 700;
                font-size: 10pt;
            }

            input[type="file"]:hover::before {
                border-color: black;
            }

            input[type="file"]:active::before {
                background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
            }
        </style>
    @endpush
</x-app-layout>

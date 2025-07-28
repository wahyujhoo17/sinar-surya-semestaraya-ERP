<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Detail Karyawan'">
    <div class="py-8 px-4 mx-auto max-w-6xl lg:py-10">
        <!-- Hero Banner with Action Bar -->
        <div
            class="relative mb-8 bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
            <div
                class="px-6 py-6 sm:px-10 sm:py-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <h1 class="text-2xl font-bold text-white sm:text-3xl mr-4">
                        Detail Karyawan
                    </h1>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2 sm:mt-0 {{ $karyawan->status === 'aktif'
                            ? 'bg-green-100 text-green-800'
                            : ($karyawan->status === 'nonaktif'
                                ? 'bg-red-100 text-red-800'
                                : ($karyawan->status === 'cuti'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst($karyawan->status) }}
                    </span>
                </div>

                <div class="flex gap-3 w-full sm:w-auto justify-end mt-4 sm:mt-0">
                    <a href="{{ route('hr.karyawan.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium  text-primary-800 bg-white backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('hr.karyawan.edit', $karyawan->id) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-900/40 backdrop-blur-sm border border-dashed border-white/30 rounded-lg hover:bg-primary-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </a>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-8">
            <!-- Left side (Profile Card) -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <div class="relative h-32 bg-gradient-to-r from-primary-500/80 to-primary-700/80">
                        <div class="absolute inset-0 bg-pattern-wave opacity-10"></div>
                    </div>
                    <div class="relative px-6 pb-6 -mt-16 flex flex-col items-center">
                        @if ($karyawan->foto)
                            <img src="{{ asset('storage/' . $karyawan->foto) }}"
                                alt="Foto {{ $karyawan->nama_lengkap }}"
                                class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                        @else
                            <div
                                class="h-32 w-32 rounded-full bg-primary-100 dark:bg-primary-900/30 border-4 border-white dark:border-gray-800 shadow-md flex items-center justify-center text-4xl text-primary-700 dark:text-primary-300 font-bold">
                                {{ strtoupper(substr($karyawan->nama_lengkap, 0, 1)) }}
                            </div>
                        @endif

                        <div class="mt-4 text-center">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $karyawan->nama_lengkap }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $karyawan->email }}</p>

                            @if ($karyawan->user && $karyawan->user->roles->isNotEmpty())
                                <div class="mt-2 flex justify-center space-x-1">
                                    @foreach ($karyawan->user->roles as $role)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ $role->nama }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-5 grid grid-cols-1 gap-3">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                        </path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $karyawan->nip }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-gray-700 dark:text-gray-300">{{ $karyawan->jabatan->nama ?? '-' }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-gray-700 dark:text-gray-300">{{ $karyawan->department->nama ?? '-' }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $karyawan->telepon }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side (Content) -->
            <div class="lg:col-span-3">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <!-- Tabs -->
                    <div x-data="{ activeTab: 'personal' }">
                        <!-- Tab Navigation -->
                        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                            <nav class="flex whitespace-nowrap">
                                <button @click="activeTab = 'personal'"
                                    :class="activeTab === 'personal' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/10' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="flex items-center py-4 px-6 font-medium text-sm border-b-2 whitespace-nowrap transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2"
                                        :class="activeTab === 'personal' ? 'text-primary-500' : 'text-gray-400'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Data Pribadi
                                </button>
                                <button @click="activeTab = 'employment'"
                                    :class="activeTab === 'employment' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/10' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="flex items-center py-4 px-6 font-medium text-sm border-b-2 whitespace-nowrap transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2"
                                        :class="activeTab === 'employment' ? 'text-primary-500' : 'text-gray-400'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Kepegawaian
                                </button>
                                <button @click="activeTab = 'salary'"
                                    :class="activeTab === 'salary' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/10' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="flex items-center py-4 px-6 font-medium text-sm border-b-2 whitespace-nowrap transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2"
                                        :class="activeTab === 'salary' ? 'text-primary-500' : 'text-gray-400'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    Komponen Gaji
                                </button>
                                <button @click="activeTab = 'account'"
                                    :class="activeTab === 'account' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/10' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="flex items-center py-4 px-6 font-medium text-sm border-b-2 whitespace-nowrap transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2"
                                        :class="activeTab === 'account' ? 'text-primary-500' : 'text-gray-400'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    Akun User
                                </button>
                            </nav>
                        </div>
                        <!-- Tab Content -->
                        <div class="p-4 sm:p-6">
                            <!-- Personal Tab -->
                            <div x-show="activeTab === 'personal'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                                    </path>
                                                </svg>
                                                Identitas
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">NIP</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->nip }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Nama
                                                        Lengkap</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->nama_lengkap }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Jenis
                                                        Kelamin</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Kontak
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->email }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400">Telepon</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->telepon }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Tanggal Lahir
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tempat
                                                        Lahir</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->tempat_lahir }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal
                                                        Lahir</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->tanggal_lahir ? date('d-m-Y', strtotime($karyawan->tanggal_lahir)) : '-' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Alamat
                                            </h3>
                                            <div class="space-y-3">
                                                <div>
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Alamat
                                                        Lengkap</span>
                                                    <p class="text-sm text-gray-900 dark:text-white">
                                                        {{ $karyawan->alamat }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employment Tab -->
                            <div x-show="activeTab === 'employment'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Posisi
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400">Department</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->department->nama ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400">Jabatan</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->jabatan->nama ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Masa Kerja
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal
                                                        Masuk</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->tanggal_masuk ? date('d-m-Y', strtotime($karyawan->tanggal_masuk)) : '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal
                                                        Keluar</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->tanggal_keluar ? date('d-m-Y', strtotime($karyawan->tanggal_keluar)) : '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $karyawan->status === 'aktif'
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                            : ($karyawan->status === 'nonaktif'
                                                                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                                                : ($karyawan->status === 'cuti'
                                                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300')) }}">
                                                        {{ ucfirst($karyawan->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Salary Components Tab -->
                            <div x-show="activeTab === 'salary'" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Left Column - Allowances (Tunjangan) -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                                            <h3
                                                class="text-lg font-semibold text-green-800 dark:text-green-200 flex items-center mb-4">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tunjangan & Pendapatan
                                            </h3>
                                            <div class="space-y-4">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Gaji
                                                        Pokok</span>
                                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        Rp {{ number_format($karyawan->gaji_pokok ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                @if (($karyawan->tunjangan_btn ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            BTN</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->tunjangan_btn, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->tunjangan_keluarga ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            Keluarga</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->tunjangan_keluarga, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->tunjangan_jabatan ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            Jabatan</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->tunjangan_jabatan, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->tunjangan_transport ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            Transport</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->tunjangan_transport, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->tunjangan_makan ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            Makan</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->tunjangan_makan, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->tunjangan_pulsa ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            Pulsa</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->tunjangan_pulsa, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->default_tunjangan ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">Tunjangan
                                                            Lainnya</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->default_tunjangan, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->default_bonus ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">Bonus
                                                            Default</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->default_bonus, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->default_lembur_rate ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">Rate
                                                            Lembur (per jam)</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->default_lembur_rate, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (
                                                    ($karyawan->tunjangan_btn ?? 0) == 0 &&
                                                        ($karyawan->tunjangan_keluarga ?? 0) == 0 &&
                                                        ($karyawan->tunjangan_jabatan ?? 0) == 0 &&
                                                        ($karyawan->tunjangan_transport ?? 0) == 0 &&
                                                        ($karyawan->tunjangan_makan ?? 0) == 0 &&
                                                        ($karyawan->tunjangan_pulsa ?? 0) == 0 &&
                                                        ($karyawan->default_tunjangan ?? 0) == 0 &&
                                                        ($karyawan->default_bonus ?? 0) == 0 &&
                                                        ($karyawan->default_lembur_rate ?? 0) == 0)
                                                    <div class="text-center py-4">
                                                        <span
                                                            class="text-sm text-gray-500 dark:text-gray-400 italic">Hanya
                                                            gaji pokok yang dikonfigurasi</span>
                                                    </div>
                                                @endif
                                                <hr class="border-green-200 dark:border-green-700">
                                                <div class="flex justify-between items-center font-semibold">
                                                    <span class="text-base text-green-800 dark:text-green-200">Total
                                                        Tunjangan</span>
                                                    <span class="text-base text-green-800 dark:text-green-200">
                                                        Rp
                                                        {{ number_format(
                                                            ($karyawan->tunjangan_btn ?? 0) +
                                                                ($karyawan->tunjangan_keluarga ?? 0) +
                                                                ($karyawan->tunjangan_jabatan ?? 0) +
                                                                ($karyawan->tunjangan_transport ?? 0) +
                                                                ($karyawan->tunjangan_makan ?? 0) +
                                                                ($karyawan->tunjangan_pulsa ?? 0) +
                                                                ($karyawan->default_tunjangan ?? 0) +
                                                                ($karyawan->default_bonus ?? 0),
                                                            0,
                                                            ',',
                                                            '.',
                                                        ) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column - Deductions (Potongan) -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                                            <h3
                                                class="text-lg font-semibold text-red-800 dark:text-red-200 flex items-center mb-4">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                                Potongan & Pengurangan
                                            </h3>
                                            <div class="space-y-4">
                                                @if (($karyawan->bpjs ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="text-sm text-gray-600 dark:text-gray-400">BPJS</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp {{ number_format($karyawan->bpjs, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->default_potongan ?? 0) > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">Potongan
                                                            Lainnya</span>
                                                        <span
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format($karyawan->default_potongan, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (($karyawan->bpjs ?? 0) == 0 && ($karyawan->default_potongan ?? 0) == 0)
                                                    <div class="text-center py-4">
                                                        <span
                                                            class="text-sm text-gray-500 dark:text-gray-400 italic">Tidak
                                                            ada potongan yang dikonfigurasi</span>
                                                    </div>
                                                @endif
                                                <hr class="border-red-200 dark:border-red-700">
                                                <div class="flex justify-between items-center font-semibold">
                                                    <span class="text-base text-red-800 dark:text-red-200">Total
                                                        Potongan</span>
                                                    <span class="text-base text-red-800 dark:text-red-200">
                                                        Rp
                                                        {{ number_format(($karyawan->bpjs ?? 0) + ($karyawan->default_potongan ?? 0), 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Summary Card -->
                                        <div
                                            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                                            <h3
                                                class="text-lg font-semibold text-blue-800 dark:text-blue-200 flex items-center mb-4">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Ringkasan Gaji
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Gaji
                                                        Pokok</span>
                                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        Rp {{ number_format($karyawan->gaji_pokok ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total
                                                        Tunjangan</span>
                                                    <span
                                                        class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                        + Rp
                                                        {{ number_format(
                                                            ($karyawan->tunjangan_btn ?? 0) +
                                                                ($karyawan->tunjangan_keluarga ?? 0) +
                                                                ($karyawan->tunjangan_jabatan ?? 0) +
                                                                ($karyawan->tunjangan_transport ?? 0) +
                                                                ($karyawan->tunjangan_makan ?? 0) +
                                                                ($karyawan->tunjangan_pulsa ?? 0) +
                                                                ($karyawan->default_tunjangan ?? 0) +
                                                                ($karyawan->default_bonus ?? 0),
                                                            0,
                                                            ',',
                                                            '.',
                                                        ) }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total
                                                        Potongan</span>
                                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                                        - Rp
                                                        {{ number_format(($karyawan->bpjs ?? 0) + ($karyawan->default_potongan ?? 0), 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <hr class="border-blue-200 dark:border-blue-700">
                                                <div class="flex justify-between items-center font-bold text-lg">
                                                    <span class="text-blue-800 dark:text-blue-200">Total Gaji
                                                        (estimasi)</span>
                                                    <span class="text-blue-800 dark:text-blue-200">
                                                        Rp
                                                        {{ number_format(
                                                            ($karyawan->gaji_pokok ?? 0) +
                                                                (($karyawan->tunjangan_btn ?? 0) +
                                                                    ($karyawan->tunjangan_keluarga ?? 0) +
                                                                    ($karyawan->tunjangan_jabatan ?? 0) +
                                                                    ($karyawan->tunjangan_transport ?? 0) +
                                                                    ($karyawan->tunjangan_makan ?? 0) +
                                                                    ($karyawan->tunjangan_pulsa ?? 0) +
                                                                    ($karyawan->default_tunjangan ?? 0) +
                                                                    ($karyawan->default_bonus ?? 0)) -
                                                                (($karyawan->bpjs ?? 0) + ($karyawan->default_potongan ?? 0)),
                                                            0,
                                                            ',',
                                                            '.',
                                                        ) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                    * Belum termasuk komisi, lembur, cash bon, keterlambatan, dan
                                                    komponen variabel lainnya
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Tab -->
                            <div x-show="activeTab === 'account'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                Akun
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">User
                                                        Login</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->user->email ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Nama
                                                        User</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->user->name ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status
                                                        User</span>
                                                    <span>
                                                        @if ($karyawan->user && $karyawan->user->is_active)
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-6">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                            <h3
                                                class="text-sm font-medium text-gray-900 dark:text-white flex items-center mb-3">
                                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                    </path>
                                                </svg>
                                                Akses
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Terakhir
                                                        Login</span>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $karyawan->user->last_login_at ? date('d-m-Y H:i', strtotime($karyawan->user->last_login_at)) : '-' }}</span>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400 block mb-2">User
                                                        Role</span>
                                                    <div class="flex flex-wrap gap-2">
                                                        @if ($karyawan->user && $karyawan->user->roles->isNotEmpty())
                                                            @foreach ($karyawan->user->roles as $role)
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                                    {{ $role->nama }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span
                                                                class="text-sm text-gray-500 dark:text-gray-400">Tidak
                                                                ada role</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

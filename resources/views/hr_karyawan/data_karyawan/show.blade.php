<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Detail Karyawan'">
    <div class="py-8 px-4 mx-auto max-w-5xl lg:py-10">
        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                Detail Karyawan
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('hr.karyawan.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('hr.karyawan.edit', $karyawan->id) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <!-- Card -->
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Left side (Image) -->
                <div
                    class="p-6 flex flex-col items-center justify-center md:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    @if ($karyawan->foto)
                        <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="Foto {{ $karyawan->nama_lengkap }}"
                            class="h-32 w-32 rounded-full object-cover border-4 border-primary-200 mb-2">
                    @else
                        <div
                            class="h-32 w-32 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-4xl text-primary-700 dark:text-primary-300 font-bold mb-2">
                            {{ strtoupper(substr($karyawan->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-2">
                        {{ $karyawan->nama_lengkap }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $karyawan->email }}</div>
                    <span
                        class="mt-2 px-3 py-1 rounded-full text-xs font-medium {{ $karyawan->status === 'aktif'
                            ? 'bg-green-100 text-green-800'
                            : ($karyawan->status === 'nonaktif'
                                ? 'bg-red-100 text-red-800'
                                : ($karyawan->status === 'cuti'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst($karyawan->status) }}
                    </span>
                </div>
                <!-- Right side (Content) -->
                <div class="md:col-span-2 p-6">
                    <!-- Tabs -->
                    <div x-data="{ activeTab: 'personal' }">
                        <!-- Tab Navigation -->
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="flex space-x-8">
                                <button @click="activeTab = 'personal'"
                                    :class="activeTab === 'personal' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Data Pribadi
                                </button>
                                <button @click="activeTab = 'employment'"
                                    :class="activeTab === 'employment' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Kepegawaian
                                </button>
                                <button @click="activeTab = 'account'"
                                    :class="activeTab === 'account' ?
                                        'border-primary-500 text-primary-600 dark:text-primary-400' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Akun User
                                </button>
                            </nav>
                        </div>
                        <!-- Tab Content -->
                        <div class="mt-6">
                            <!-- Personal Tab -->
                            <div x-show="activeTab === 'personal'">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NIP</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->nip }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap
                                        </dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->nama_lengkap }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempat, Tanggal
                                            Lahir</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->tempat_lahir }},
                                            {{ $karyawan->tanggal_lahir ? date('d-m-Y', strtotime($karyawan->tanggal_lahir)) : '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin
                                        </dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $karyawan->alamat }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->telepon }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->email }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <!-- Employment Tab -->
                            <div x-show="activeTab === 'employment'">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->department->nama ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->jabatan->nama ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Masuk
                                        </dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->tanggal_masuk ? date('d-m-Y', strtotime($karyawan->tanggal_masuk)) : '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Keluar
                                        </dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->tanggal_keluar ? date('d-m-Y', strtotime($karyawan->tanggal_keluar)) : '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                        <dd class="mt-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $karyawan->status === 'aktif'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($karyawan->status === 'nonaktif'
                                                        ? 'bg-red-100 text-red-800'
                                                        : ($karyawan->status === 'cuti'
                                                            ? 'bg-blue-100 text-blue-800'
                                                            : 'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst($karyawan->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            <!-- Account Tab -->
                            <div x-show="activeTab === 'account'">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User Login</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->user->email ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama User</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->user->name ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status User
                                        </dt>
                                        <dd class="mt-1">
                                            @if ($karyawan->user && $karyawan->user->is_active)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Login
                                        </dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $karyawan->user->last_login_at ? date('d-m-Y H:i', strtotime($karyawan->user->last_login_at)) : '-' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

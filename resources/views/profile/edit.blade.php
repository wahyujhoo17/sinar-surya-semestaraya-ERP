<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profil Pengguna</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Kelola informasi profil dan pengaturan akun Anda
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <!-- Profile Header -->
                        <div class="relative h-32 bg-gradient-to-r from-primary-500/80 to-primary-700/80">
                            <div class="absolute inset-0 bg-pattern-wave opacity-10"></div>
                        </div>

                        <!-- Profile Photo -->
                        <div class="relative px-6 pb-6 -mt-16 flex flex-col items-center">
                            @if ($user->photo_url)
                                <img src="{{ $user->photo_url }}" alt="Foto {{ $user->display_name }}"
                                    class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                            @else
                                <div
                                    class="h-32 w-32 rounded-full bg-primary-100 dark:bg-primary-900/30 border-4 border-white dark:border-gray-800 shadow-md flex items-center justify-center text-4xl text-primary-700 dark:text-primary-300 font-bold">
                                    {{ $user->initials }}
                                </div>
                            @endif

                            <div class="mt-4 text-center">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->display_name }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->display_email }}</p>

                                @if ($user->karyawan && $user->karyawan->user && $user->karyawan->user->roles->isNotEmpty())
                                    <div class="mt-2 flex justify-center space-x-1">
                                        @foreach ($user->karyawan->user->roles as $role)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ $role->nama }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Photo Upload Section for Karyawan -->
                                @if ($user->karyawan)
                                    <div class="mt-4" x-data="photoUpload()">
                                        <!-- Photo Upload Button -->
                                        <div class="flex justify-center space-x-2">
                                            <button @click="$refs.photoInput.click()"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-300 dark:border-primary-700 dark:hover:bg-primary-900/50 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $user->photo_url ? 'Ubah Foto' : 'Upload Foto' }}
                                            </button>

                                            @if ($user->photo_url)
                                                <button @click="deletePhoto()"
                                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700 dark:hover:bg-red-900/50 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Hapus Foto
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Hidden File Input -->
                                        <input type="file" x-ref="photoInput" @change="handleFileSelect($event)"
                                            accept="image/jpeg,image/jpg,image/png,image/gif" class="hidden">

                                        <!-- Preview Modal -->
                                        <div x-show="showPreview" x-cloak
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95">

                                                <!-- Modal Header -->
                                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        Preview Foto Profil</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pastikan
                                                        foto terlihat jelas dan sesuai</p>
                                                </div>

                                                <!-- Preview Image -->
                                                <div class="p-6">
                                                    <div class="flex justify-center mb-4">
                                                        <img x-show="previewUrl" :src="previewUrl" alt="Preview"
                                                            class="h-40 w-40 rounded-full object-cover border-4 border-primary-200 dark:border-primary-700 shadow-lg">
                                                    </div>

                                                    <!-- File Info -->
                                                    <div x-show="selectedFile"
                                                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-600 dark:text-gray-300">Nama
                                                                file:</span>
                                                            <span class="font-medium text-gray-900 dark:text-white"
                                                                x-text="selectedFile ? selectedFile.name : ''"></span>
                                                        </div>
                                                        <div class="flex items-center justify-between text-sm mt-1">
                                                            <span
                                                                class="text-gray-600 dark:text-gray-300">Ukuran:</span>
                                                            <span class="font-medium text-gray-900 dark:text-white"
                                                                x-text="formatFileSize(selectedFile ? selectedFile.size : 0)"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Actions -->
                                                <div
                                                    class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                                                    <button @click="cancelUpload()"
                                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                                                        Batal
                                                    </button>
                                                    <button @click="uploadPhoto()" :disabled="uploading"
                                                        :class="uploading ? 'opacity-50 cursor-not-allowed' :
                                                            'hover:bg-primary-700'"
                                                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg transition-colors duration-200 flex items-center">
                                                        <svg x-show="uploading"
                                                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                        <span
                                                            x-text="uploading ? 'Mengunggah...' : 'Simpan Foto'"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upload Form (Hidden) -->
                                        <form x-ref="uploadForm" action="{{ route('profile.photo.update') }}"
                                            method="POST" enctype="multipart/form-data" class="hidden">
                                            @csrf
                                            <input type="file" name="photo" x-ref="hiddenFileInput">
                                        </form>

                                        <!-- Delete Form (Hidden) -->
                                        <form x-ref="deleteForm" action="{{ route('profile.photo.delete') }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <!-- Quick Info -->
                            @if ($user->karyawan)
                                <div class="mt-5 w-full space-y-3">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                            </path>
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300">NIP:
                                            {{ $user->karyawan->nip }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300">{{ $user->karyawan->department->nama ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300">{{ $user->karyawan->jabatan->nama ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Information -->
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Profil</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Perbarui informasi akun Anda.</p>
                        </div>

                        <div class="p-6">
                            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                                @csrf
                                @method('patch')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Nama <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $user->name) }}" required
                                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('name') border-red-500 dark:border-red-500 @enderror">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email', $user->email) }}" required
                                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('email') border-red-500 dark:border-red-500 @enderror">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4">
                                    <button type="submit"
                                        class="px-6 py-3 rounded-md bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white transition-colors duration-200">
                                        Simpan Perubahan
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 3000)"
                                            class="text-sm text-green-600 dark:text-green-400">
                                            Profil berhasil diperbarui!
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Employee Personal Information Section -->
                    @if ($user->karyawan)
                        <div
                            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Personal
                                    Karyawan</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Perbarui informasi personal Anda sebagai karyawan.
                                </p>
                            </div>

                            <div class="p-6">
                                <form method="post" action="{{ route('profile.employee.update') }}"
                                    class="space-y-6">
                                    @csrf
                                    @method('patch')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="nama_lengkap"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Nama Lengkap
                                            </label>
                                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                                value="{{ old('nama_lengkap', $user->karyawan->nama_lengkap) }}"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('nama_lengkap', 'updateEmployee') border-red-500 dark:border-red-500 @enderror">
                                            @error('nama_lengkap', 'updateEmployee')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="telepon"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Nomor Telepon
                                            </label>
                                            <input type="text" name="telepon" id="telepon"
                                                value="{{ old('telepon', $user->karyawan->telepon) }}"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('telepon', 'updateEmployee') border-red-500 dark:border-red-500 @enderror"
                                                placeholder="Contoh: 081234567890">
                                            @error('telepon', 'updateEmployee')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="tempat_lahir"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Tempat Lahir
                                            </label>
                                            <input type="text" name="tempat_lahir" id="tempat_lahir"
                                                value="{{ old('tempat_lahir', $user->karyawan->tempat_lahir) }}"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('tempat_lahir', 'updateEmployee') border-red-500 dark:border-red-500 @enderror"
                                                placeholder="Contoh: Jakarta">
                                            @error('tempat_lahir', 'updateEmployee')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="tanggal_lahir"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Tanggal Lahir
                                            </label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                value="{{ old('tanggal_lahir', $user->karyawan->tanggal_lahir) }}"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('tanggal_lahir', 'updateEmployee') border-red-500 dark:border-red-500 @enderror">
                                            @error('tanggal_lahir', 'updateEmployee')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="jenis_kelamin"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Jenis Kelamin
                                            </label>
                                            <select name="jenis_kelamin" id="jenis_kelamin"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('jenis_kelamin', 'updateEmployee') border-red-500 dark:border-red-500 @enderror">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L"
                                                    {{ old('jenis_kelamin', $user->karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="P"
                                                    {{ old('jenis_kelamin', $user->karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin', 'updateEmployee')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="alamat"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Alamat Lengkap
                                        </label>
                                        <textarea name="alamat" id="alamat" rows="3"
                                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('alamat', 'updateEmployee') border-red-500 dark:border-red-500 @enderror"
                                            placeholder="Masukkan alamat lengkap Anda">{{ old('alamat', $user->karyawan->alamat) }}</textarea>
                                        @error('alamat', 'updateEmployee')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-center justify-between pt-4">
                                        <button type="submit"
                                            class="px-6 py-3 rounded-md bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white transition-colors duration-200">
                                            Simpan Informasi Personal
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Update Password Section -->
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ubah Password</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
                            </p>
                        </div>

                        <div class="p-6">
                            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                                @csrf
                                @method('put')

                                <div>
                                    <label for="current_password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Password Saat Ini <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('current_password', 'updatePassword') border-red-500 dark:border-red-500 @enderror"
                                        autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Password Baru <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('password', 'updatePassword') border-red-500 dark:border-red-500 @enderror"
                                        autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('password_confirmation', 'updatePassword') border-red-500 dark:border-red-500 @enderror"
                                        autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between pt-4">
                                    <button type="submit"
                                        class="px-6 py-3 rounded-md bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white transition-colors duration-200">
                                        Ubah Password
                                    </button>

                                    @if (session('status') === 'password-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 3000)"
                                            class="text-sm text-green-600 dark:text-green-400">
                                            Password berhasil diubah!
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @error('photo')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    @push('scripts')
        <script>
            function photoUpload() {
                return {
                    showPreview: false,
                    previewUrl: null,
                    selectedFile: null,
                    uploading: false,

                    init() {
                        // Initialization if needed
                    },

                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Hanya file gambar (JPEG, PNG, GIF) yang diperbolehkan.');
                            return;
                        }

                        // Validate file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ukuran file maksimal 5MB.');
                            return;
                        }

                        this.selectedFile = file;

                        // Create preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewUrl = e.target.result;
                            this.showPreview = true;
                        };
                        reader.readAsDataURL(file);
                    },

                    cancelUpload() {
                        this.showPreview = false;
                        this.previewUrl = null;
                        this.selectedFile = null;
                        this.$refs.photoInput.value = '';
                    },

                    async uploadPhoto() {
                        if (!this.selectedFile) return;

                        this.uploading = true;

                        try {
                            const formData = new FormData();
                            formData.append('photo', this.selectedFile);
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'));

                            const response = await fetch('{{ route('profile.photo.update') }}', {
                                method: 'POST',
                                body: formData,
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                // Success - reload page to show new photo
                                window.location.reload();
                            } else {
                                throw new Error(result.message || 'Gagal mengunggah foto');
                            }
                        } catch (error) {
                            console.error('Upload error:', error);
                            alert(error.message || 'Terjadi kesalahan saat mengunggah foto');
                        } finally {
                            this.uploading = false;
                            this.showPreview = false;
                            this.previewUrl = null;
                            this.selectedFile = null;
                        }
                    },

                    async deletePhoto() {
                        if (!confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                            return;
                        }

                        try {
                            const response = await fetch('{{ route('profile.photo.delete') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                },
                                body: JSON.stringify({
                                    _method: 'DELETE'
                                })
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                // Success - reload page to remove photo
                                window.location.reload();
                            } else {
                                throw new Error(result.message || 'Gagal menghapus foto');
                            }
                        } catch (error) {
                            console.error('Delete error:', error);
                            alert(error.message || 'Terjadi kesalahan saat menghapus foto');
                        }
                    },

                    formatFileSize(bytes) {
                        if (bytes === 0) return '0 Bytes';
                        const k = 1024;
                        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>

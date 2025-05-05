<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Data Karyawan</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $karyawan->nama_lengkap }} - {{ $karyawan->nip }}
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
        <form action="{{ route('hr.karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

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
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $karyawan->nip) }}"
                            required
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
                        <input type="text" name="nama_lengkap" id="nama_lengkap"
                            value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required
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
                        <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email) }}"
                            required
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
                        <input type="text" name="telepon" id="telepon"
                            value="{{ old('telepon', $karyawan->telepon) }}" required
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
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                            value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" required
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
                            value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}" required
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
                            <option value="L"
                                {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P"
                                {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
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
                            class="mt-1 block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-md text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 transition-colors duration-200 @error('alamat') border-red-500 dark:border-red-500 @enderror">{{ old('alamat', $karyawan->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Employment Information Card --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/70 dark:border-gray-700/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/80">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Kepegawaian</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Data kepegawaian karyawan</p>
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
                                    {{ old('department_id', $karyawan->department_id) == $department->id ? 'selected' : '' }}>
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
                                    {{ old('jabatan_id', $karyawan->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                                    {{ $jabatan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
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
                            value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk) }}" required
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
                            <option value="aktif"
                                {{ old('status', $karyawan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif"
                                {{ old('status', $karyawan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                            <option value="cuti" {{ old('status', $karyawan->status) == 'cuti' ? 'selected' : '' }}>
                                Cuti</option>
                        </select>
                        @error('status')
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
                                @if ($karyawan->foto)
                                    <img src="{{ asset('storage/' . $karyawan->foto) }}"
                                        alt="{{ $karyawan->nama_lengkap }}" class="h-full w-full object-cover"
                                        id="preview-image">
                                @else
                                    <div id="preview-placeholder"
                                        class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <span class="text-xs text-center">Preview Foto</span>
                                    </div>
                                    <img src="#" alt="Preview" class="h-full w-full object-cover hidden"
                                        id="preview-image">
                                @endif
                            </div>
                            <label for="foto"
                                class="ml-5 bg-white dark:bg-gray-800 py-2.5 px-4 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 cursor-pointer">
                                Ganti Foto
                                <input id="foto" name="foto" type="file" class="sr-only"
                                    accept="image/*" onchange="previewImage()">
                            </label>
                            @if ($karyawan->foto)
                                <button type="button" onclick="removeFoto()"
                                    class="ml-2 text-sm text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-400">
                                    Hapus Foto
                                </button>
                                <input type="hidden" name="remove_foto" value="0" id="remove_foto">
                            @endif
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
                <a href="{{ route('hr.karyawan.index') }}"
                    class="px-5 py-3 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-3 rounded-md bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-white transition-colors duration-200">
                    Simpan Perubahan
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
                        if (placeholder) placeholder.classList.add('hidden');
                        image.classList.remove('hidden');
                        image.src = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeFoto() {
                const container = document.getElementById('preview-container');
                const image = document.getElementById('preview-image');
                const input = document.getElementById('foto');
                const removeInput = document.getElementById('remove_foto');

                // Clear the file input
                input.value = '';

                // Hide the current image
                image.classList.add('hidden');
                image.src = '#';

                // Show placeholder
                if (!document.getElementById('preview-placeholder')) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'preview-placeholder';
                    placeholder.className = 'flex flex-col items-center justify-center text-gray-400 dark:text-gray-500';
                    placeholder.innerHTML = '<span class="text-xs text-center">Preview Foto</span>';
                    container.appendChild(placeholder);
                } else {
                    document.getElementById('preview-placeholder').classList.remove('hidden');
                }

                // Set remove_foto input to true
                removeInput.value = '1';
            }
        </script>
    @endpush
</x-app-layout>

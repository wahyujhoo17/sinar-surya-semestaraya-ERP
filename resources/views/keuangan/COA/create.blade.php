<x-app-layout>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Tambah Akun Baru</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Tambahkan akun baru ke dalam Chart of Accounts (COA)
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('keuangan.coa.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 border-gray-300">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <form action="{{ route('keuangan.coa.store') }}" method="POST" id="coaForm">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Akun -->
                        <div class="col-span-1">
                            <label for="kode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" name="kode" id="kode" required
                                    class="flex-1 focus:ring-primary-500 focus:border-primary-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('kode') border-red-500 @enderror"
                                    value="{{ old('kode') }}" placeholder="Contoh: 1000">
                                <button type="button" id="generateCodeBtn"
                                    class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span class="ml-1">Generate</span>
                                </button>
                            </div>
                            @error('kode')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode akun harus unik dan sesuai
                                dengan standar akuntansi</p>
                        </div>

                        <!-- Nama Akun -->
                        <div class="col-span-1">
                            <label for="nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <input type="text" name="nama" id="nama" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nama') border-red-500 @enderror"
                                    value="{{ old('nama') }}" placeholder="Contoh: Kas Kecil">
                            </div>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Akun -->
                        <div class="col-span-1">
                            <label for="kategori"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <select name="kategori" id="kategori" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('kategori') border-red-500 @enderror">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($kategoriAkun as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Akun -->
                        <div class="col-span-1">
                            <label for="tipe"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Akun <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <select name="tipe" id="tipe" required
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('tipe') border-red-500 @enderror">
                                    <option value="" disabled selected>Pilih Tipe</option>
                                    @foreach ($tipeAkun as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('tipe') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('tipe')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Header untuk kelompok akun, Detail
                                untuk akun transaksi</p>
                        </div>

                        <!-- Parent Akun -->
                        <div class="col-span-1">
                            <label for="parent_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Akun Induk</label>
                            <div class="mt-1">
                                <select name="parent_id" id="parent_id"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('parent_id') border-red-500 @enderror">
                                    <option value="">Tidak Ada (Akun Level Atas)</option>
                                    @foreach ($allAccounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ old('parent_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->kode }} - {{ $account->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih akun induk jika ini adalah
                                sub-akun</p>
                        </div>

                        <!-- Reference Type (Kas/Rekening) -->
                        <div class="col-span-1">
                            <label for="reference_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referensi Ke</label>
                            <div class="mt-1">
                                <select name="reference_type" id="reference_type"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    onchange="toggleReferenceOptions()">
                                    <option value="">Tidak Ada</option>
                                    <option value="kas" {{ old('reference_type') == 'kas' ? 'selected' : '' }}>Kas
                                    </option>
                                    <option value="rekening"
                                        {{ old('reference_type') == 'rekening' ? 'selected' : '' }}>
                                        Rekening Bank</option>
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hubungkan akun dengan kas atau
                                rekening bank yang sudah ada</p>
                        </div>

                        <!-- Kas Options -->
                        <div class="col-span-2" id="kas_options"
                            style="display: {{ old('reference_type') == 'kas' ? 'block' : 'none' }}">
                            <label for="reference_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Kas</label>
                            <div class="mt-1">
                                <select name="reference_id" id="kas_select"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Pilih Kas</option>
                                    @foreach ($kasOptions as $kas)
                                        <option value="{{ $kas->id }}"
                                            {{ old('reference_id') == $kas->id && old('reference_type') == 'kas' ? 'selected' : '' }}>
                                            {{ $kas->nama }} (Saldo: Rp
                                            {{ number_format($kas->saldo, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Rekening Options -->
                        <div class="col-span-2" id="rekening_options"
                            style="display: {{ old('reference_type') == 'rekening' ? 'block' : 'none' }}">
                            <label for="reference_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Rekening
                                Bank</label>
                            <div class="mt-1">
                                <select name="reference_id" id="rekening_select"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Pilih Rekening Bank</option>
                                    @foreach ($rekeningOptions as $rekening)
                                        <option value="{{ $rekening->id }}"
                                            {{ old('reference_id') == $rekening->id && old('reference_type') == 'rekening' ? 'selected' : '' }}>
                                            {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }}
                                            ({{ $rekening->atas_nama }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-span-1">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Akun Aktif
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nonaktifkan jika akun tidak
                                digunakan</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right flex justify-end space-x-3">
                    <button type="button" onclick="window.location.href='{{ route('keuangan.coa.index') }}'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        Simpan Akun Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const generateCodeBtn = document.getElementById('generateCodeBtn');
                const parentIdField = document.getElementById('parent_id');
                const kategoriField = document.getElementById('kategori');
                const kodeField = document.getElementById('kode');

                generateCodeBtn.addEventListener('click', function() {
                    const parentId = parentIdField.value;
                    const kategori = kategoriField.value;

                    if (!kategori) {
                        alert('Silakan pilih kategori akun terlebih dahulu');
                        return;
                    }

                    fetch(`/keuangan/coa/generate-code?parent_id=${parentId}&kategori=${kategori}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                kodeField.value = data.code;
                            } else {
                                alert('Gagal menghasilkan kode akun');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghasilkan kode akun');
                        });
                });

                // Initialize reference options
                toggleReferenceOptions();
            });

            function toggleReferenceOptions() {
                const referenceType = document.getElementById('reference_type').value;
                const kasOptions = document.getElementById('kas_options');
                const rekeningOptions = document.getElementById('rekening_options');
                const kasSelect = document.getElementById('kas_select');
                const rekeningSelect = document.getElementById('rekening_select');

                if (referenceType === 'kas') {
                    kasOptions.style.display = 'block';
                    rekeningOptions.style.display = 'none';
                    kasSelect.name = 'reference_id';
                    rekeningSelect.name = 'rekening_id_disabled';
                } else if (referenceType === 'rekening') {
                    kasOptions.style.display = 'none';
                    rekeningOptions.style.display = 'block';
                    kasSelect.name = 'kas_id_disabled';
                    rekeningSelect.name = 'reference_id';
                } else {
                    kasOptions.style.display = 'none';
                    rekeningOptions.style.display = 'none';
                    kasSelect.name = 'kas_id_disabled';
                    rekeningSelect.name = 'rekening_id_disabled';
                }
            }
        </script>
    @endpush
</x-app-layout>

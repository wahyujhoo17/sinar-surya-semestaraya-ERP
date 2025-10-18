<x-app-layout :breadcrumbs="[
    ['label' => 'CRM', 'url' => route('crm.prospek.index')],
    ['label' => 'Prospek & Lead', 'url' => route('crm.prospek.index')],
    ['label' => 'Edit Prospek'],
]" :currentPage="'Edit Prospek'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Prospek
                        </h1>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Edit informasi prospek untuk mengupdate data penjualan.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex-shrink-0 flex">
                    <a href="{{ route('crm.prospek.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('crm.prospek.update', $prospek->id) }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                            Informasi Dasar
                        </h3>

                        <div>
                            <label for="nama_prospek"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nama Prospek <span class="text-red-600">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" name="nama_prospek" id="nama_prospek" required
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="Masukkan nama lengkap prospek"
                                    value="{{ old('nama_prospek', $prospek->nama_prospek) }}">
                            </div>
                            @error('nama_prospek')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="perusahaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Perusahaan
                            </label>
                            <div class="mt-1">
                                <input type="text" name="perusahaan" id="perusahaan"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="Nama perusahaan (opsional)"
                                    value="{{ old('perusahaan', $prospek->perusahaan) }}">
                            </div>
                            @error('perusahaan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email
                            </label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="Email kontak" value="{{ old('email', $prospek->email) }}">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nomor Telepon
                            </label>
                            <div class="mt-1">
                                <input type="text" name="telepon" id="telepon"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="Nomor telepon kontak" value="{{ old('telepon', $prospek->telepon) }}">
                            </div>
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Alamat
                            </label>
                            <div class="mt-1">
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="Alamat lengkap">{{ old('alamat', $prospek->alamat) }}</textarea>
                            </div>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                            Detail Prospek
                        </h3>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status <span class="text-red-600">*</span>
                            </label>
                            <div class="mt-1">
                                <select name="status" id="status" required
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    <option value="baru"
                                        {{ old('status', $prospek->status) == 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="tertarik"
                                        {{ old('status', $prospek->status) == 'tertarik' ? 'selected' : '' }}>Tertarik
                                    </option>
                                    <option value="negosiasi"
                                        {{ old('status', $prospek->status) == 'negosiasi' ? 'selected' : '' }}>
                                        Negosiasi</option>
                                    <option value="menolak"
                                        {{ old('status', $prospek->status) == 'menolak' ? 'selected' : '' }}>Menolak
                                    </option>
                                    <option value="menjadi_customer"
                                        {{ old('status', $prospek->status) == 'menjadi_customer' ? 'selected' : '' }}>
                                        Menjadi Customer</option>
                                </select>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Sumber <span class="text-red-600">*</span>
                            </label>
                            <div class="mt-1">
                                <select name="sumber" id="sumber" required
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    <option value="" disabled
                                        {{ old('sumber', $prospek->sumber) ? '' : 'selected' }}>Pilih sumber prospek
                                    </option>
                                    <option value="website"
                                        {{ old('sumber', $prospek->sumber) == 'website' ? 'selected' : '' }}>Website
                                    </option>
                                    <option value="referral"
                                        {{ old('sumber', $prospek->sumber) == 'referral' ? 'selected' : '' }}>Referral
                                    </option>
                                    <option value="pameran"
                                        {{ old('sumber', $prospek->sumber) == 'pameran' ? 'selected' : '' }}>Pameran
                                    </option>
                                    <option value="media_sosial"
                                        {{ old('sumber', $prospek->sumber) == 'media_sosial' ? 'selected' : '' }}>Media
                                        Sosial</option>
                                    <option value="cold_call"
                                        {{ old('sumber', $prospek->sumber) == 'cold_call' ? 'selected' : '' }}>Cold
                                        Call</option>
                                    <option value="lainnya"
                                        {{ old('sumber', $prospek->sumber) == 'lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                            </div>
                            @error('sumber')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nilai_potensi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nilai Potensi (Rp)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="nilai_potensi" id="nilai_potensi" min="0"
                                    class="pl-12 focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="0" value="{{ old('nilai_potensi', $prospek->nilai_potensi) }}">
                            </div>
                            @error('nilai_potensi')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_kontak"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Kontak Pertama
                            </label>
                            <div class="mt-1">
                                <input type="date" name="tanggal_kontak" id="tanggal_kontak"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    value="{{ old('tanggal_kontak', $prospek->tanggal_kontak ? date('Y-m-d', strtotime($prospek->tanggal_kontak)) : '') }}">
                            </div>
                            @error('tanggal_kontak')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_followup"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Follow-up Berikutnya
                            </label>
                            <div class="mt-1">
                                <input type="date" name="tanggal_followup" id="tanggal_followup"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    value="{{ old('tanggal_followup', $prospek->tanggal_followup ? date('Y-m-d', strtotime($prospek->tanggal_followup)) : '') }}">
                            </div>
                            @error('tanggal_followup')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catatan Tambahan
                            </label>
                            <div class="mt-1">
                                <textarea name="catatan" id="catatan" rows="4"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    placeholder="Catatan penting tentang prospek ini">{{ old('catatan', $prospek->catatan) }}</textarea>
                            </div>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Attachments Section -->
                        <div>
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                                Lampiran File
                            </h3>
                            <x-crm-file-attachments :existingAttachments="$prospek->attachments ?? []" model-type="prospek" :model-id="$prospek->id" />
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-5">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('crm.prospek.index') }}"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Update Prospek
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout :breadcrumbs="[
    ['label' => 'CRM', 'url' => route('crm.prospek.index')],
    ['label' => 'Aktivitas & Follow-up', 'url' => route('crm.aktivitas.index')],
    ['label' => 'Tambah Aktivitas'],
]" :currentPage="'Tambah Aktivitas'">

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-primary-100 dark:bg-primary-900 p-2 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Tambah Aktivitas Baru
                    </h1>
                </div>

                <form action="{{ route('crm.aktivitas.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Prospek -->
                        <div class="col-span-1">
                            <label for="prospek_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Prospek <span class="text-red-600">*</span>
                            </label>
                            <select id="prospek_id" name="prospek_id"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('prospek_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Prospek --</option>
                                @foreach ($prospekList as $prospek)
                                    <option value="{{ $prospek->id }}"
                                        {{ old('prospek_id', request('prospek_id')) == $prospek->id ? 'selected' : '' }}>
                                        {{ $prospek->nama_prospek }}
                                        {{ $prospek->customer ? '(' . $prospek->customer->nama . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prospek_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Aktivitas -->
                        <div class="col-span-1">
                            <label for="tipe"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tipe Aktivitas <span class="text-red-600">*</span>
                            </label>
                            <select id="tipe" name="tipe"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('tipe') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                @foreach ($tipeList as $key => $label)
                                    <option value="{{ $key }}" {{ old('tipe') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Judul -->
                        <div class="col-span-1">
                            <label for="judul"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Judul <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('judul') border-red-500 @enderror"
                                placeholder="Contoh: Penawaran Produk A" required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> <!-- Tanggal -->
                        <div class="col-span-1">
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tanggal <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="datetime-local" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}"
                                    class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('tanggal') border-red-500 @enderror"
                                    required>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-span-full">
                            <label for="deskripsi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('deskripsi') border-red-500 @enderror"
                                placeholder="Detail aktivitas...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hasil -->
                        <div class="col-span-full">
                            <label for="hasil"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Hasil
                            </label>
                            <textarea id="hasil" name="hasil" rows="3"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('hasil') border-red-500 @enderror"
                                placeholder="Hasil dari aktivitas...">{{ old('hasil') }}</textarea>
                            @error('hasil')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Follow-up Section -->
                        <div class="col-span-full mt-4">
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="perlu_followup" name="perlu_followup" type="checkbox"
                                            value="1"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                            {{ old('perlu_followup') ? 'checked' : '' }}
                                            onchange="toggleFollowupFields(this.checked)">
                                    </div>
                                    <div class="ml-3">
                                        <label for="perlu_followup"
                                            class="font-medium text-gray-700 dark:text-gray-300">
                                            Perlu Follow-up
                                        </label>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                                            Centang jika aktivitas ini memerlukan tindak lanjut
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Follow-up Fields (initially hidden) -->
                        <div id="followup-fields"
                            class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6 {{ old('perlu_followup') ? '' : 'hidden' }}">
                            <!-- Tanggal Follow-up -->
                            <div>
                                <label for="tanggal_followup"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-indigo-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tanggal Follow-up <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <input type="datetime-local" id="tanggal_followup" name="tanggal_followup"
                                        value="{{ old('tanggal_followup', now()->addDays(3)->format('Y-m-d\TH:i')) }}"
                                        class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('tanggal_followup') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('tanggal_followup')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Follow-up -->
                            <div>
                                <label for="status_followup"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status Follow-up <span class="text-red-600">*</span>
                                </label>
                                <select id="status_followup" name="status_followup"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status_followup') border-red-500 @enderror">
                                    @foreach ($statusList as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status_followup', \App\Models\ProspekAktivitas::STATUS_MENUNGGU) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_followup')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catatan Follow-up -->
                            <div class="col-span-full">
                                <label for="catatan_followup"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan Follow-up
                                </label>
                                <textarea id="catatan_followup" name="catatan_followup" rows="3"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('catatan_followup') border-red-500 @enderror"
                                    placeholder="Catatan untuk follow-up...">{{ old('catatan_followup') }}</textarea>
                                @error('catatan_followup')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ url()->previous() }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFollowupFields(isChecked) {
            const followupFields = document.getElementById('followup-fields');
            const tanggalFollowup = document.getElementById('tanggal_followup');
            const statusFollowup = document.getElementById('status_followup');

            if (isChecked) {
                followupFields.classList.remove('hidden');
                // Make fields required when checkbox is checked
                tanggalFollowup.required = true;
                statusFollowup.required = true;
                // Ensure the fields are enabled for submission
                tanggalFollowup.disabled = false;
                statusFollowup.disabled = false;
            } else {
                followupFields.classList.add('hidden');
                // Remove required attribute when checkbox is unchecked
                tanggalFollowup.required = false;
                statusFollowup.required = false;
                // Don't disable fields as this prevents them from being submitted
                // Just make them not required
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFollowupFields(document.getElementById('perlu_followup').checked);
        });
    </script>
</x-app-layout>

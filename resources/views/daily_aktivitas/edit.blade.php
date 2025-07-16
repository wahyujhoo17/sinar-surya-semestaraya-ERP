<x-app-layout :breadcrumbs="[
    ['label' => 'Daily Aktivitas', 'url' => route('daily-aktivitas.index')],
    ['label' => 'Detail Aktivitas', 'url' => route('daily-aktivitas.show', $dailyAktivitas)],
    ['label' => 'Edit Aktivitas'],
]" :currentPage="'Edit Aktivitas'">

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
                            Edit Aktivitas
                        </h1>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Update informasi aktivitas untuk memperbarui jadwal dan detail.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex-shrink-0 flex space-x-3">
                    <a href="{{ route('daily-aktivitas.show', $dailyAktivitas) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('daily-aktivitas.update', $dailyAktivitas) }}" method="POST"
                enctype="multipart/form-data" x-data="aktivitasForm()" @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="lg:col-span-2">
                            <label for="judul"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Aktivitas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul"
                                value="{{ old('judul', $dailyAktivitas->judul) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Masukkan judul aktivitas">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label for="deskripsi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Masukkan deskripsi aktivitas">{{ old('deskripsi', $dailyAktivitas->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tipe_aktivitas"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipe Aktivitas <span class="text-red-500">*</span>
                            </label>
                            <select id="tipe_aktivitas" name="tipe_aktivitas" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Tipe Aktivitas</option>
                                @foreach (\App\Models\DailyAktivitas::getTipeAktivitasList() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('tipe_aktivitas', $dailyAktivitas->tipe_aktivitas) == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tipe_aktivitas')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prioritas"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Prioritas <span class="text-red-500">*</span>
                            </label>
                            <select id="prioritas" name="prioritas" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Prioritas</option>
                                @foreach (\App\Models\DailyAktivitas::getPrioritasList() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('prioritas', $dailyAktivitas->prioritas) == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('prioritas')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                @foreach (\App\Models\DailyAktivitas::getStatusList() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status', $dailyAktivitas->status) == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="assigned_to"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Assign Kepada (Utama)
                            </label>
                            <select id="assigned_to" name="assigned_to"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih User Utama (Opsional)</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('assigned_to', $dailyAktivitas->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Team Assignment -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Penugasan Tim</h3>

                        <div>
                            <label for="assigned_users"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tim Anggota (Multi-Select)
                                <span class="text-xs text-gray-500 dark:text-gray-400">(Ctrl+Click untuk memilih
                                    beberapa)</span>
                            </label>
                            <select id="assigned_users" name="assigned_users[]" multiple
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                style="min-height: 120px;">
                                @foreach ($users as $user)
                                    @php
                                        $isAssigned = $dailyAktivitas
                                            ->assignedUsers()
                                            ->where('user_id', $user->id)
                                            ->exists();
                                        $inOld = in_array($user->id, old('assigned_users', []));
                                    @endphp
                                    <option value="{{ $user->id }}"
                                        {{ (old('assigned_users') ? $inOld : $isAssigned) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Pilih beberapa user yang akan ditugaskan pada aktivitas ini. User yang dipilih akan
                                mendapat notifikasi.
                            </p>
                            @error('assigned_users')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Jadwal & Waktu</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_mulai"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal & Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', $dailyAktivitas->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal & Waktu Selesai
                                </label>
                                <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai"
                                    value="{{ old('tanggal_selesai', $dailyAktivitas->tanggal_selesai ? $dailyAktivitas->tanggal_selesai->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="deadline"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deadline
                                </label>
                                <input type="datetime-local" id="deadline" name="deadline"
                                    value="{{ old('deadline', $dailyAktivitas->deadline ? $dailyAktivitas->deadline->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                @error('deadline')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="estimasi_durasi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Estimasi Durasi (Jam)
                                </label>
                                <input type="number" id="estimasi_durasi" name="estimasi_durasi"
                                    value="{{ old('estimasi_durasi', $dailyAktivitas->estimasi_durasi) }}"
                                    step="0.5" min="0" max="999.99"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Contoh: 2.5">
                                @error('estimasi_durasi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="durasi_aktual"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Durasi Aktual (Jam)
                                </label>
                                <input type="number" id="durasi_aktual" name="durasi_aktual"
                                    value="{{ old('durasi_aktual', $dailyAktivitas->durasi_aktual) }}" step="0.5"
                                    min="0" max="999.99"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Contoh: 3.0">
                                @error('durasi_aktual')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reminder_at"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reminder
                                </label>
                                <input type="datetime-local" id="reminder_at" name="reminder_at"
                                    value="{{ old('reminder_at', $dailyAktivitas->reminder_at ? $dailyAktivitas->reminder_at->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                @error('reminder_at')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="lokasi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Lokasi
                                </label>
                                <input type="text" id="lokasi" name="lokasi"
                                    value="{{ old('lokasi', $dailyAktivitas->lokasi) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Masukkan lokasi aktivitas">
                                @error('lokasi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Tambahan</h3>

                        <div class="space-y-6">
                            <div>
                                <label for="peserta"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Peserta
                                </label>
                                <textarea id="peserta" name="peserta" rows="2"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Masukkan nama peserta, email, atau daftar yang terlibat">{{ old('peserta', $dailyAktivitas->peserta) }}</textarea>
                                @error('peserta')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Catatan
                                </label>
                                <textarea id="catatan" name="catatan" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Masukkan catatan atau informasi tambahan">{{ old('catatan', $dailyAktivitas->catatan) }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hasil"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hasil / Outcome
                                </label>
                                <textarea id="hasil" name="hasil" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Masukkan hasil atau outcome dari aktivitas">{{ old('hasil', $dailyAktivitas->hasil) }}</textarea>
                                @error('hasil')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Existing Attachments -->
                            @if ($dailyAktivitas->attachments && count($dailyAktivitas->attachments) > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        File Lampiran Saat Ini
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        @foreach ($dailyAktivitas->attachments as $index => $attachment)
                                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $attachment['filename'] ?? 'File' }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ isset($attachment['size']) ? number_format($attachment['size'] / 1024, 1) . ' KB' : '' }}
                                                    </p>
                                                </div>
                                                @if (isset($attachment['path']))
                                                    <div class="ml-3">
                                                        <a href="{{ Storage::url($attachment['path']) }}"
                                                            target="_blank"
                                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label for="new_attachments"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tambah File Lampiran Baru
                                </label>
                                <input type="file" id="new_attachments" name="new_attachments[]" multiple
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.xlsx,.xls,.ppt,.pptx">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Upload file pendukung tambahan (PDF, DOC, Image, Excel, PowerPoint). Maksimal 10MB
                                    per file.
                                </p>
                                @error('new_attachments')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <a href="{{ route('daily-aktivitas.show', $dailyAktivitas) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" :disabled="loading"
                        class="inline-flex items-center px-6 py-2 bg-primary-600 hover:bg-primary-700 disabled:bg-primary-400 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="loading ? 'Menyimpan...' : 'Update Aktivitas'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function aktivitasForm() {
            return {
                loading: false
            }
        }
    </script>
</x-app-layout>

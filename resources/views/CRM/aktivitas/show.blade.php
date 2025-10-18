@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout :breadcrumbs="[
    ['label' => 'CRM', 'url' => route('crm.prospek.index')],
    ['label' => 'Aktivitas & Follow-up', 'url' => route('crm.aktivitas.index')],
    ['label' => 'Detail Aktivitas'],
]" :currentPage="'Detail Aktivitas'">

    <!-- Actions Section -->
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="flex justify-between">
            <a href="{{ route('crm.aktivitas.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar
            </a>
            <a href="{{ route('crm.prospek.show', $aktivitas->prospek_id) }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                Lihat Prospek
                <svg class="ml-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $aktivitas->judul }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Ditambahkan oleh {{ $aktivitas->user->name }}
                            <span class="mx-2">•</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $aktivitas->created_at->timezone($userTimezone ?? 'Asia/Jakarta')->format('d M Y H:i') }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="{{ route('crm.aktivitas.edit', $aktivitas->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </a>

                        <form action="{{ route('crm.aktivitas.destroy', $aktivitas->id) }}" method="POST"
                            class="inline-block"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6 shadow-inner">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 005 10a1 1 0 01-2 0C3 7.239 4.239 6 5.5 6S8 7.239 8 8c0 .146-.015.289-.042.428A7.939 7.939 0 0110 8c4.136 0 7.5 3.364 7.5 7.5v1a.5.5 0 01-.5.5H3a.5.5 0 01-.5-.5v-1c0-1.152.261-2.243.726-3.224A5.989 5.989 0 013 10a1 1 0 01-2 0c0-2.761 2.239-5 5-5 .962 0 1.865.278 2.622.75A5.495 5.495 0 0114 10a1 1 0 01-2 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Prospek</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <a href="{{ route('crm.prospek.show', $aktivitas->prospek_id) }}"
                                        class="text-primary-600 hover:text-primary-700 hover:underline">
                                        {{ $aktivitas->prospek->nama_prospek }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Aktivitas</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $tipeList[$aktivitas->tipe] ?? $aktivitas->tipe }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-0.5"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $aktivitas->tanggal->timezone($userTimezone ?? 'Asia/Jakarta')->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Deskripsi</h2>
                    <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">
                            {{ $aktivitas->deskripsi ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Hasil</h2>
                    <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">
                            {{ $aktivitas->hasil ?? 'Belum ada hasil' }}
                        </p>
                    </div>
                </div>

                @if ($aktivitas->perlu_followup)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary-600" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Informasi Follow-up
                        </h2>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Follow-up
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $aktivitas->tanggal_followup->timezone($userTimezone ?? 'Asia/Jakarta')->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                    <p class="mt-1">
                                        @if ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_MENUNGGU)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                {{ $statusList[$aktivitas->status_followup] ?? $aktivitas->status_followup }}
                                            </span>
                                        @elseif($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_SELESAI)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                {{ $statusList[$aktivitas->status_followup] ?? $aktivitas->status_followup }}
                                            </span>
                                        @elseif($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_DIBATALKAN)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                {{ $statusList[$aktivitas->status_followup] ?? $aktivitas->status_followup }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan Follow-up
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">
                                    {{ $aktivitas->catatan_followup ?? 'Tidak ada catatan' }}
                                </p>
                            </div>

                            @if (
                                $aktivitas->status_followup != \App\Models\ProspekAktivitas::STATUS_SELESAI &&
                                    $aktivitas->status_followup != \App\Models\ProspekAktivitas::STATUS_DIBATALKAN)
                                <div class="mt-4 border-t border-gray-200 dark:border-gray-600 pt-4">
                                    <form action="{{ route('crm.aktivitas.followup.update', $aktivitas->id) }}"
                                        method="POST" class="flex flex-col md:flex-row gap-4 items-start">
                                        @csrf
                                        @method('PATCH')

                                        <div class="flex-grow">
                                            <label for="catatan_followup"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Update
                                                Catatan (Opsional)</label>
                                            <textarea id="catatan_followup" name="catatan_followup" rows="2"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                                        </div>

                                        <div class="flex-shrink-0 flex gap-2 self-end">
                                            <button type="submit" name="status_followup"
                                                value="{{ \App\Models\ProspekAktivitas::STATUS_SELESAI }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Selesai
                                            </button>

                                            <button type="submit" name="status_followup"
                                                value="{{ \App\Models\ProspekAktivitas::STATUS_DIBATALKAN }}"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Batalkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>



        <!-- Lampiran File -->
        @if ($aktivitas->attachments && count($aktivitas->attachments) > 0)
            <div class="mt-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header -->
                    <div
                        class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/10 dark:to-blue-900/10">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 bg-green-100 dark:bg-green-900/30 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Lampiran File</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                        {{ count($aktivitas->attachments) }} file terlampir</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                            @foreach ($aktivitas->attachments as $index => $attachment)
                                <div
                                    class="group relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-700/30 rounded-xl border-2 border-gray-200 dark:border-gray-600 p-5 hover:shadow-lg hover:border-green-400 dark:hover:border-green-500 hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                                    <div class="flex flex-col space-y-3">
                                        <!-- File Icon -->
                                        <div class="flex items-center justify-center">
                                            @if (isset($attachment['mime_type']) && str_starts_with($attachment['mime_type'], 'image/'))
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/30 rounded-2xl flex items-center justify-center shadow-md">
                                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @elseif(isset($attachment['mime_type']) && $attachment['mime_type'] === 'application/pdf')
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/40 dark:to-red-800/30 rounded-2xl flex items-center justify-center shadow-md">
                                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-2xl flex items-center justify-center shadow-md">
                                                    <svg class="w-8 h-8 text-gray-600 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- File Info -->
                                        <div class="flex-1 min-w-0 text-center">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate px-2"
                                                title="{{ $attachment['original_name'] ?? 'File' }}">
                                                {{ $attachment['original_name'] ?? 'File' }}
                                            </h4>
                                            <div
                                                class="mt-2 flex items-center justify-center space-x-2 text-xs text-gray-600 dark:text-gray-400">
                                                <span
                                                    class="font-medium">{{ number_format(($attachment['size'] ?? 0) / 1024, 1) }}
                                                    KB</span>
                                                @if (isset($attachment['mime_type']))
                                                    <span>•</span>
                                                    <span
                                                        class="uppercase font-medium">{{ explode('/', $attachment['mime_type'])[1] ?? 'file' }}</span>
                                                @endif
                                            </div>

                                            <!-- Download Button -->
                                            <a href="{{ route('crm.aktivitas.attachment.download', ['id' => $aktivitas->id, 'index' => $index]) }}"
                                                class="mt-3 inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-500 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 w-full group-hover:scale-105">
                                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

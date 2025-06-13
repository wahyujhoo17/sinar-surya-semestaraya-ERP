<div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Aktivitas & Follow-up
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Riwayat aktivitas dan pengingat follow-up untuk prospek ini
                </p>
            </div>

            <div class="mt-4 md:mt-0">
                <a href="{{ route('crm.aktivitas.create', ['prospek_id' => $prospek->id]) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Aktivitas
                </a>
            </div>
        </div>

        <!-- Upcoming Follow-up -->
        @php
            $upcomingFollowup = $prospek
                ->aktivitas()
                ->where('perlu_followup', 1)
                ->whereIn('status_followup', [null, \App\Models\ProspekAktivitas::STATUS_MENUNGGU])
                ->orderBy('tanggal_followup')
                ->first();

            $userTimezone = auth()->user()->timezone ?? 'Asia/Jakarta';
        @endphp

        @if ($upcomingFollowup)
            <div
                class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Follow-up Berikutnya
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                                <p class="font-medium">{{ $upcomingFollowup->judul }}</p>
                                <p class="mt-1">
                                    <span class="font-semibold">Tanggal:</span>
                                    {{ $upcomingFollowup->tanggal_followup->timezone($userTimezone)->format('d M Y H:i') }}
                                    ({{ $upcomingFollowup->tanggal_followup->diffForHumans() }})
                                </p>
                                @if ($upcomingFollowup->catatan_followup)
                                    <p class="mt-1">
                                        <span class="font-semibold">Catatan:</span>
                                        {{ $upcomingFollowup->catatan_followup }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('crm.aktivitas.show', $upcomingFollowup->id) }}"
                                class="whitespace-nowrap ml-3 text-sm font-medium text-yellow-700 dark:text-yellow-300 hover:text-yellow-600 dark:hover:text-yellow-200">
                                Lihat detail
                                <span aria-hidden="true"> &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Activity List -->
        @if ($prospek->aktivitas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Judul
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Hasil
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Follow-up
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($prospek->aktivitas->take(5) as $aktivitas)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $aktivitas->tanggal->timezone($userTimezone)->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @php
                                        $tipeList = \App\Models\ProspekAktivitas::getTipeList();
                                    @endphp
                                    {{ $tipeList[$aktivitas->tipe] ?? $aktivitas->tipe }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $aktivitas->judul }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($aktivitas->hasil, 50) ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($aktivitas->perlu_followup)
                                        @if ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_MENUNGGU)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                {{ $aktivitas->tanggal_followup->timezone($userTimezone)->format('d M Y') }}
                                            </span>
                                        @elseif ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_SELESAI)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Selesai
                                            </span>
                                        @elseif ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_DIBATALKAN)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('crm.aktivitas.show', $aktivitas->id) }}"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($prospek->aktivitas->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('crm.aktivitas.index', ['prospek_id' => $prospek->id]) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Lihat Semua Aktivitas
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            @endif
        @else
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada aktivitas</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Belum ada riwayat aktivitas untuk prospek ini
                </p>
                <div class="mt-6">
                    <a href="{{ route('crm.aktivitas.create', ['prospek_id' => $prospek->id]) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Aktivitas Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<x-app-layout :breadcrumbs="[
    ['label' => 'CRM', 'url' => route('crm.prospek.index')],
    ['label' => 'Aktivitas & Follow-up', 'url' => route('crm.aktivitas.index')],
    ['label' => 'Daftar Follow-up'],
]" :currentPage="'Daftar Follow-up'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Daftar Follow-up
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Kelola dan monitor semua aktivitas yang memerlukan follow-up
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex-shrink-0 flex space-x-3">
                        <a href="{{ route('crm.aktivitas.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kembali ke Aktivitas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Filter Follow-up</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('crm.aktivitas.followups') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="prospek_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prospek</label>
                        <select id="prospek_id" name="prospek_id"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Prospek</option>
                            @foreach (\App\Models\Prospek::orderBy('nama_prospek')->get() as $prospek)
                                <option value="{{ $prospek->id }}"
                                    {{ request('prospek_id') == $prospek->id ? 'selected' : '' }}>
                                    {{ $prospek->nama_prospek }}
                                    {{ $prospek->perusahaan ? '- ' . $prospek->perusahaan : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status_followup"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Follow-up</label>
                        <select id="status_followup" name="status_followup"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Status</option>
                            @foreach (\App\Models\ProspekAktivitas::getStatusList() as $value => $label)
                                <option value="{{ $value }}"
                                    {{ request('status_followup') == $value ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_range"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Follow-up</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="date" name="tanggal_dari" id="tanggal_dari"
                                value="{{ request('tanggal_dari') }}"
                                class="flex-1 min-w-0 block w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            <span
                                class="inline-flex items-center px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-gray-500 text-sm">
                                s/d
                            </span>
                            <input type="date" name="tanggal_sampai" id="tanggal_sampai"
                                value="{{ request('tanggal_sampai') }}"
                                class="flex-1 min-w-0 block w-full rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('crm.aktivitas.followups') }}"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Follow-up List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Follow-up yang Memerlukan Tindakan</h3>

                <!-- Summary stats -->
                <div class="flex space-x-2">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                        {{ $menunggu_count }} Menunggu
                    </span>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                        {{ $selesai_count }} Selesai
                    </span>
                </div>
            </div>

            @if ($followups->isEmpty())
                <div class="p-6 text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Follow-up</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Tidak ada follow-up yang perlu ditindaklanjuti saat ini dengan filter yang dipilih.
                    </p>
                    <a href="{{ route('crm.aktivitas.create') }}"
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Aktivitas Baru
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal Follow-up</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Prospek</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tipe</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aktivitas</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($followups as $item)
                                <tr
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150
                                    @if ($item->tanggal_followup && $item->tanggal_followup->isPast() && $item->status_followup === 'menunggu') bg-yellow-50 dark:bg-yellow-900/10 @endif
                                ">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        <div
                                            class="font-medium @if ($item->tanggal_followup && $item->tanggal_followup->isPast() && $item->status_followup === 'menunggu') text-red-600 dark:text-red-400 @endif">
                                            {{ $item->tanggal_followup ? $item->tanggal_followup->format('d M Y') : 'Belum dijadwalkan' }}
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            {{ $item->tanggal_followup ? $item->tanggal_followup->format('H:i') : '' }}
                                            @if ($item->tanggal_followup && $item->tanggal_followup->isPast() && $item->status_followup === 'menunggu')
                                                <span
                                                    class="text-red-600 dark:text-red-400 font-medium">(Terlambat)</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('crm.prospek.show', $item->prospek_id) }}"
                                            class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 truncate max-w-[200px] block">
                                            {{ $item->prospek->nama_prospek }}
                                        </a>
                                        <div class="text-gray-500 dark:text-gray-400 text-xs truncate max-w-[200px]">
                                            {{ $item->prospek->perusahaan ?: 'Tidak ada perusahaan' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if ($item->tipe == 'telepon') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @elseif($item->tipe == 'email') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300
                                            @elseif($item->tipe == 'pertemuan') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($item->tipe == 'presentasi') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @elseif($item->tipe == 'penawaran') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                        ">
                                            {{ \App\Models\ProspekAktivitas::getTipeList()[$item->tipe] ?? $item->tipe }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        <div class="font-medium">{{ $item->judul }}</div>
                                        <div class="text-gray-500 dark:text-gray-400 text-xs">
                                            {{ $item->tanggal->format('d M Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($item->status_followup == 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @elseif($item->status_followup == 'selesai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($item->status_followup == 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                        ">
                                            {{ \App\Models\ProspekAktivitas::getStatusList()[$item->status_followup] ?? 'Menunggu' }}
                                        </span>
                                        @if ($item->catatan_followup)
                                            <div class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                                {{ Str::limit($item->catatan_followup, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            @if ($item->status_followup === 'menunggu')
                                                <form action="{{ route('crm.aktivitas.followup.update', $item->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_followup" value="selesai">
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                        title="Tandai Selesai">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('crm.aktivitas.followup.update', $item->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_followup" value="dibatalkan">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                        title="Batalkan Follow-up">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('crm.aktivitas.show', $item->id) }}"
                                                class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('crm.aktivitas.edit', $item->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $followups->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

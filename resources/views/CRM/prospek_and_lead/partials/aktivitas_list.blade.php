<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Aktivitas & Follow-up</h3>
            </div>
        </div>
        <a href="{{ route('crm.aktivitas.create', ['prospek_id' => $prospek->id]) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-primary-600 hover:bg-primary-700 transition-colors">
            <svg class="-ml-0.5 mr-1.5 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Aktivitas
        </a>
    </div>

    <div class="p-0">
        @php
            $upcomingFollowup = $prospek->aktivitas()->where('perlu_followup', 1)->whereIn('status_followup', [null, \App\Models\ProspekAktivitas::STATUS_MENUNGGU])->orderBy('tanggal_followup')->first();
            $userTimezone = auth()->user()->timezone ?? 'Asia/Jakarta';
        @endphp

        @if ($upcomingFollowup)
            <div class="m-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200 flex items-start sm:items-center flex-col sm:flex-row justify-between gap-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h4 class="text-sm font-semibold text-yellow-800">Follow-up Terdekat: {{ $upcomingFollowup->judul }}</h4>
                        <p class="text-xs text-yellow-700 mt-1">Tanggal: {{ $upcomingFollowup->tanggal_followup->timezone($userTimezone)->format('d M Y H:i') }} ({{ $upcomingFollowup->tanggal_followup->diffForHumans() }})</p>
                    </div>
                </div>
                <a href="{{ route('crm.aktivitas.show', $upcomingFollowup->id) }}" class="text-sm font-medium text-yellow-700 hover:text-yellow-800 whitespace-nowrap">Lihat detail &rarr;</a>
            </div>
        @endif

        @if ($prospek->aktivitas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Follow-up</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($prospek->aktivitas->take(5) as $aktivitas)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $aktivitas->tanggal->timezone($userTimezone)->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php $tipeList = \App\Models\ProspekAktivitas::getTipeList(); @endphp
                                    {{ $tipeList[$aktivitas->tipe] ?? $aktivitas->tipe }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $aktivitas->judul }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($aktivitas->perlu_followup)
                                        @if ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_MENUNGGU)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                {{ $aktivitas->tanggal_followup->timezone($userTimezone)->format('d M Y') }}
                                            </span>
                                        @elseif ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_SELESAI)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 border border-green-200">Selesai</span>
                                        @elseif ($aktivitas->status_followup == \App\Models\ProspekAktivitas::STATUS_DIBATALKAN)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 border border-red-200">Batal</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('crm.aktivitas.show', $aktivitas->id) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if ($prospek->aktivitas->count() > 5)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-center">
                    <a href="{{ route('crm.aktivitas.index', ['prospek_id' => $prospek->id]) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Lihat Semua Aktivitas &rarr;</a>
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-sm font-medium text-gray-900">Belum ada aktivitas</h3>
                <p class="mt-1 text-sm text-gray-500 mb-4">Catat telepon, meeting, atau email pertama dengan prospek ini.</p>
                <a href="{{ route('crm.aktivitas.create', ['prospek_id' => $prospek->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    Tambah Aktivitas
                </a>
            </div>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        <h3 class="text-sm font-semibold text-gray-900">Riwayat Perubahan Status</h3>
    </div>
    <div class="p-6">
        @if(isset($logStatus) && $logStatus->count() > 0)
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($logStatus as $index => $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $log->aktivitas == 'create' ? 'bg-green-500' : 'bg-blue-500' }}">
                                            @if($log->aktivitas == 'create')
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            @else
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                @if($log->aktivitas == 'create')
                                                    Prospek ditambahkan oleh <span class="font-medium text-gray-900">{{ $log->user->name ?? 'Sistem' }}</span>
                                                @else
                                                    @php
                                                        $detail = json_decode($log->detail, true);
                                                        $statusBaru = $detail['status_baru'] ?? '-';
                                                    @endphp
                                                    Status diubah ke <span class="font-medium text-gray-900 uppercase text-xs mx-1 px-2 py-0.5 bg-gray-100 rounded-full border border-gray-200">{{ str_replace('_', ' ', $statusBaru) }}</span> oleh <span class="font-medium text-gray-900">{{ $log->user->name ?? 'Sistem' }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $log->created_at->format('Y-m-d') }}">{{ $log->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="py-6 text-center text-sm text-gray-500">
                Belum ada riwayat status
            </div>
        @endif
    </div>
</div>

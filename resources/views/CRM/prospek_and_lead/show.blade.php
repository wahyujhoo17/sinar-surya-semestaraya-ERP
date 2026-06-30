@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout :breadcrumbs="[
    ['label' => 'CRM', 'url' => route('crm.prospek.index')],
    ['label' => 'Prospek & Lead', 'url' => route('crm.prospek.index')],
    ['label' => 'Detail Prospek'],
]" :currentPage="'Detail Prospek'">

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Top Navigation & Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('crm.prospek.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">Detail Prospek</h1>
                    <p class="text-sm text-gray-500">Melihat informasi lengkap prospek dan riwayat aktivitas.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('crm.prospek.edit', $prospek->id) }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm">
                    <svg class="-ml-1 mr-2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit Prospek
                </a>
                

            </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN (Main Content) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Hero Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $prospek->nama_prospek }}</h2>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-gray-100 text-gray-800': '{{ $prospek->status }}' === 'baru',
                                            'bg-blue-100 text-blue-800': '{{ $prospek->status }}' === 'tertarik',
                                            'bg-yellow-100 text-yellow-800': '{{ $prospek->status }}' === 'negosiasi',
                                            'bg-red-100 text-red-800': '{{ $prospek->status }}' === 'menolak',
                                            'bg-green-100 text-green-800': '{{ $prospek->status }}' === 'menjadi_customer',
                                        }">
                                        @if ($prospek->status == 'baru') Baru
                                        @elseif($prospek->status == 'tertarik') Tertarik
                                        @elseif($prospek->status == 'negosiasi') Negosiasi
                                        @elseif($prospek->status == 'menolak') Menolak
                                        @elseif($prospek->status == 'menjadi_customer') Customer
                                        @endif
                                    </span>
                                </div>
                                
                                @if ($prospek->perusahaan)
                                <div class="flex items-center text-gray-600 text-base">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    {{ $prospek->perusahaan }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Stats Grid inside Hero -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Nilai Potensi</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($prospek->nilai_potensi, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Kontak Pertama</p>
                                <p class="text-base font-medium text-gray-900">{{ $prospek->tanggal_kontak ? date('d M Y', strtotime($prospek->tanggal_kontak)) : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Sumber</p>
                                <p class="text-base font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $prospek->sumber) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Follow-up</p>
                                <p class="text-base font-medium {{ $prospek->tanggal_followup ? 'text-yellow-600' : 'text-gray-900' }}">
                                    {{ $prospek->tanggal_followup ? date('d M Y', strtotime($prospek->tanggal_followup)) : 'Belum Ada' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($prospek->catatan)
                <!-- Catatan -->
                <div class="bg-yellow-50 rounded-xl border border-yellow-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-yellow-100 flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <h3 class="text-sm font-semibold text-yellow-800">Catatan Internal</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-yellow-900 whitespace-pre-wrap leading-relaxed">{{ $prospek->catatan }}</p>
                    </div>
                </div>
                @endif

                <!-- Lampiran File -->
                @if ($prospek->attachments && count($prospek->attachments) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                            <h3 class="text-sm font-semibold text-gray-900">Lampiran File</h3>
                        </div>
                        <span class="bg-gray-200 text-gray-700 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ count($prospek->attachments) }}</span>
                    </div>
                    <div class="p-6">
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($prospek->attachments as $index => $attachment)
                            @php
                                $isImage = isset($attachment['mime_type']) && str_starts_with($attachment['mime_type'], 'image/');
                                $isPdf = isset($attachment['mime_type']) && $attachment['mime_type'] === 'application/pdf';
                                $canViewInline = $isImage || $isPdf;
                                $inlineUrl = route('crm.prospek.attachment.download', ['id' => $prospek->id, 'index' => $index, 'inline' => 1]);
                                $downloadUrl = route('crm.prospek.attachment.download', ['id' => $prospek->id, 'index' => $index]);
                            @endphp
                            <li class="col-span-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-primary-300 transition-colors flex items-center p-3 relative group">
                                <div class="w-12 h-12 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($isImage)
                                        <img src="{{ $inlineUrl }}" alt="Thumbnail" class="w-full h-full object-cover" />
                                    @elseif($isPdf)
                                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1 min-w-0 pr-2">
                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $attachment['original_name'] ?? 'File' }}">{{ $attachment['original_name'] ?? 'File' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ number_format(($attachment['size'] ?? 0) / 1024, 1) }} KB</p>
                                </div>
                                <div class="flex-shrink-0 flex items-center space-x-1">
                                    @if($canViewInline)
                                    <a href="{{ $inlineUrl }}" target="_blank" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-md transition-colors" title="Lihat/Preview">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    @endif
                                    <a href="{{ $downloadUrl }}" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-md transition-colors" title="Download File">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Aktivitas List Partial -->
                @include('CRM.prospek_and_lead.partials.aktivitas_list')
                
                <!-- Timeline Partial -->
                @include('CRM.prospek_and_lead.partials.timeline')

            </div>

            <!-- RIGHT COLUMN (Sidebar) -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Kontak & Informasi -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                            Informasi Kontak
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                            @if ($prospek->email) 
                                <a href="mailto:{{ $prospek->email }}" class="text-sm font-medium text-primary-600 hover:text-primary-800 break-all">{{ $prospek->email }}</a> 
                            @else 
                                <span class="text-sm text-gray-400">-</span> 
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Telepon / WhatsApp</p>
                            @if ($prospek->telepon) 
                                <a href="tel:{{ $prospek->telepon }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">{{ $prospek->telepon }}</a> 
                            @else 
                                <span class="text-sm text-gray-400">-</span> 
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat</p>
                            <p class="text-sm text-gray-900 leading-relaxed">{{ $prospek->alamat ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sales PIC -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Sales Penanggung Jawab
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($prospek->user)
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-lg border border-primary-200 flex-shrink-0">
                                {{ strtoupper(substr($prospek->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">{{ $prospek->user->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $prospek->user->email }}</p>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-500">Belum Ada Sales PIC</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Delete Action -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Hapus Prospek</h3>
                        <p class="text-xs text-gray-500 mb-4">Tindakan ini akan menghapus semua data prospek, termasuk catatan dan lampiran. Tidak dapat dibatalkan.</p>
                        <form action="{{ route('crm.prospek.destroy', $prospek->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-red-300 text-red-700 rounded-lg text-sm font-medium hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('Hapus prospek ini secara permanen?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Hapus Prospek
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Meta -->
                <div class="text-center px-4 pt-2">
                    <p class="text-xs text-gray-400">
                        Dibuat: {{ $prospek->created_at->format('d M Y, H:i') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Diupdate: {{ $prospek->updated_at->diffForHumans() }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

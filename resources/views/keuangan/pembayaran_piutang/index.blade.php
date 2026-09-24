<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Piutang Usaha', 'url' => route('keuangan.piutang-usaha.index')],
    ['name' => 'Riwayat Pembayaran Piutang', 'url' => '#'],
]" :currentPage="'Riwayat Pembayaran Piutang'">

    <div class="w-full max-w-none py-6 px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 h-12 w-1.5 rounded-full mr-2"></div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Riwayat Pembayaran Piutang</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Daftar bukti pembayaran piutang dan penerimaan kas/bank dari pelanggan.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('keuangan.piutang-usaha.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition-colors">
                    <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Lihat Piutang Usaha
                </a>
                <a href="{{ route('keuangan.pembayaran-piutang.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white transition-colors">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pembayaran Piutang
                </a>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-750 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left">Nomor Pembayaran</th>
                            <th scope="col" class="px-6 py-3.5 text-left">Tanggal</th>
                            <th scope="col" class="px-6 py-3.5 text-left">Customer</th>
                            <th scope="col" class="px-6 py-3.5 text-left">Invoice Terkait</th>
                            <th scope="col" class="px-6 py-3.5 text-left">Metode / Akun</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Jumlah Pembayaran</th>
                            <th scope="col" class="px-6 py-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse ($pembayaranPiutangs as $pembayaran)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-semibold text-primary-600 dark:text-primary-400">
                                    <a href="{{ route('keuangan.pembayaran-piutang.show', $pembayaran->id) }}" class="hover:underline">
                                        {{ $pembayaran->nomor }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white font-medium">
                                    {{ $pembayaran->customer->nama ?? '-' }}
                                    @if(isset($pembayaran->customer->company) && $pembayaran->customer->company)
                                        <div class="text-xs text-gray-400">{{ $pembayaran->customer->company }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    @if ($pembayaran->details->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($pembayaran->details as $d)
                                                @if ($d->invoice)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800"
                                                        title="Alokasi: Rp {{ number_format($d->jumlah, 0, ',', '.') }}">
                                                        {{ $d->invoice->nomor }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif ($pembayaran->invoice)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            {{ $pembayaran->invoice->nomor }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Umum</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $pembayaran->metode_pembayaran }}</span>
                                    <div class="text-xs text-gray-400">
                                        @if (in_array(strtolower($pembayaran->metode_pembayaran), ['kas', 'tunai']) && $pembayaran->kas)
                                            Kas: {{ $pembayaran->kas->nama }}
                                        @elseif ($pembayaran->rekeningBank)
                                            {{ $pembayaran->rekeningBank->nama_bank }} - {{ $pembayaran->rekeningBank->nomor_rekening }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-mono font-bold text-green-600 dark:text-green-400">
                                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="{{ route('keuangan.pembayaran-piutang.show', $pembayaran->id) }}"
                                            class="p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                                            title="Lihat Detail Pembayaran">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('keuangan.pembayaran-piutang.print', $pembayaran->id) }}" target="_blank"
                                            class="p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                            title="Cetak Bukti Pembayaran">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                        @if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('direktur_utama') || auth()->user()->hasRole('administrator') || auth()->user()->hasRole('admin'))
                                            <a href="{{ route('keuangan.pembayaran-piutang.edit', $pembayaran->id) }}"
                                                class="p-1.5 text-amber-600 hover:text-amber-900 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors"
                                                title="Edit Pembayaran">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('keuangan.pembayaran-piutang.destroy', $pembayaran->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus pembayaran {{ $pembayaran->nomor }}?\n\nJurnal otomatis, mutasi kas/bank, dan sisa piutang invoice terkait akan dipulihkan.');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-red-600 hover:text-red-900 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                    title="Batalkan / Hapus Pembayaran">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium">Belum ada data pembayaran piutang</p>
                                    <p class="text-xs text-gray-400 mt-1">Pembayaran piutang yang baru dicatat akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pembayaranPiutangs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
                    {{ $pembayaranPiutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

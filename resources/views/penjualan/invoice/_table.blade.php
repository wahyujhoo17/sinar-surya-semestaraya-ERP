@if ($invoices->isEmpty())
    <tr>
        <td colspan="9" class="px-5 py-10 text-center">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div
                    class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-300">Data tidak ditemukan</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Belum ada data invoice yang tersedia atau
                        data tidak sesuai dengan filter.</p>
                    @if (request()->filled('nota_kredit_id'))
                        <p class="text-sm text-purple-500 dark:text-purple-400 mt-1">
                            Mode Aplikasi Kredit aktif (ID: {{ request()->nota_kredit_id }})
                        </p>
                    @endif
                </div>
            </div>
            <a href="{{ route('penjualan.invoice.create') }}"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Invoice Baru
            </a>
            </div>
        </td>
    </tr>
@else
    @php $no = ($invoices->currentPage() - 1) * $invoices->perPage(); @endphp
    @foreach ($invoices as $invoice)
        @php $no++; @endphp
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                {{ $no }}
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ $invoice->nomor }}
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ date('d/m/Y', strtotime($invoice->tanggal)) }}
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                <div class="text-sm">
                    <div class="font-medium text-gray-900 dark:text-white">
                        {{ $invoice->customer->company ?? ($invoice->customer->nama ?? 'Unknown') }}
                    </div>
                    <div class="text-gray-500 dark:text-gray-400">
                        {{ $invoice->customer->kode ?? '' }}
                    </div>
                </div>
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                @if ($invoice->salesOrder)
                    <a href="{{ route('penjualan.sales-order.show', $invoice->salesOrder->id) }}"
                        class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 hover:underline">
                        {{ $invoice->salesOrder->nomor }}
                    </a>
                @else
                    <span class="text-gray-400 dark:text-gray-500">-</span>
                @endif
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm">
                @if ($invoice->status === 'belum_bayar')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-600 dark:text-red-400" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Belum Bayar
                    </span>
                @elseif ($invoice->status === 'sebagian')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-800/30 dark:text-orange-400">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-orange-600 dark:text-orange-400" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Bayar Sebagian
                    </span>
                @elseif ($invoice->status === 'lunas')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-600 dark:text-green-400" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Lunas
                    </span>
                @endif
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-300">
                <span class="font-medium">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
            </td>
            <td class="px-5 py-4 whitespace-nowrap text-sm text-right font-medium">
                <div class="flex justify-end space-x-2">
                    @if (request()->filled('nota_kredit_id') && $invoice->status !== 'lunas')
                        <button type="button" data-modal-target="creditModal-{{ $invoice->id }}"
                            data-modal-toggle="creditModal-{{ $invoice->id }}"
                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white shadow-sm hover:shadow-md transition-all duration-200"
                            title="Aplikasi Kredit - ID: {{ request()->nota_kredit_id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4 mr-1">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            Aplikasi Kredit
                        </button>

                        <!-- Credit Application Modal -->
                        <div id="creditModal-{{ $invoice->id }}" tabindex="-1" aria-hidden="true"
                            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-md max-h-full">
                                <div
                                    class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <!-- Modal header with gradient background -->
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-white">
                                                        Aplikasi Kredit
                                                    </h3>
                                                    <p class="text-sm text-blue-100">Invoice #{{ $invoice->nomor }}</p>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="text-white hover:text-blue-200 focus:outline-none transition-colors rounded-full p-2 hover:bg-white hover:bg-opacity-10"
                                                data-modal-hide="creditModal-{{ $invoice->id }}">
                                                <svg class="w-5 h-5" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                    </div>

                                    <form
                                        action="{{ route('penjualan.nota-kredit.apply-to-invoice', [request()->nota_kredit_id, $invoice->id]) }}"
                                        method="POST">
                                        @csrf

                                        <!-- Modal content with invoice and credit information -->
                                        <div class="p-6">
                                            <!-- Credit note details -->
                                            <div
                                                class="mb-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-100 dark:border-blue-800">
                                                <h4
                                                    class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">
                                                    Informasi Nota Kredit</h4>
                                                <div class="grid grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <p class="text-gray-500 dark:text-gray-400">Total Kredit:</p>
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            Rp
                                                            {{ number_format(App\Models\NotaKredit::find(request()->nota_kredit_id)->total, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-500 dark:text-gray-400">Sisa Kredit:</p>
                                                        <p class="font-medium text-emerald-600 dark:text-emerald-400">
                                                            Rp
                                                            {{ number_format(App\Models\NotaKredit::find(request()->nota_kredit_id)->total - App\Models\NotaKredit::find(request()->nota_kredit_id)->invoices()->sum('nota_kredit_invoice.applied_amount'), 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Invoice details -->
                                            <div
                                                class="mb-6 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                                                <h4
                                                    class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                                    Informasi Invoice</h4>
                                                <div class="grid grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <p class="text-gray-500 dark:text-gray-400">Total Invoice:</p>
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-500 dark:text-gray-400">Sisa Piutang:</p>
                                                        <p class="font-medium text-red-600 dark:text-red-400">
                                                            Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Amount input -->
                                            <div class="mb-2">
                                                <label for="amount-{{ $invoice->id }}"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Jumlah Kredit yang Akan Diterapkan
                                                </label>

                                                <div class="relative">
                                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                                    </div>
                                                    <input type="number" id="amount-{{ $invoice->id }}" name="amount" readonly
                                                        class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-not-allowed"
                                                        placeholder="Masukkan jumlah"
                                                        value="{{ min($invoice->sisa_piutang, App\Models\NotaKredit::find(request()->nota_kredit_id)->total - App\Models\NotaKredit::find(request()->nota_kredit_id)->invoices()->sum('nota_kredit_invoice.applied_amount')) }}">
                                                </div>

                                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    Jumlah maksimal yang dapat diterapkan adalah nilai terendah antara
                                                    sisa kredit dan sisa piutang.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Action buttons -->
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                                            <button type="button" data-modal-hide="creditModal-{{ $invoice->id }}"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors dark:focus:ring-gray-700">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-lg shadow-sm hover:shadow focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 focus:outline-none transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Terapkan Kredit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <a href="{{ route('penjualan.invoice.show', $invoice->id) }}"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                        title="Lihat Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    @if ($invoice->status !== 'lunas')
                        <a href="{{ route('penjualan.invoice.edit', $invoice->id) }}"
                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                            title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4">
                                <path
                                    d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                <path
                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                            </svg>
                        </a>
                    @endif
                    <a href="{{ route('penjualan.invoice.print', $invoice->id) }}" target="_blank"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-indigo-100 text-gray-700 dark:text-white dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 transition-colors border border-dashed border-indigo-300"
                        title="Cetak">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-4 h-4">
                            <path fill-rule="evenodd"
                                d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.126.153A2.212 2.212 0 0118 8.653v4.097A2.25 2.25 0 0115.75 15h-.241l.305 1.984A1.75 1.75 0 0114.084 19H5.915a1.75 1.75 0 01-1.73-2.016L4.5 15H4.25A2.25 2.25 0 012 12.75V8.653c0-1.082.775-2.034 1.874-2.198.374-.056.75-.107 1.127-.153L5 6.25v-3.5zm8.5 3.397v-.147c0-.292-.256-.55-.546-.55h-6.91c-.29 0-.546.258-.546.55v.549c1.805-.222 3.534-.222 5.34 0 .9.111 1.8.222 2.662.222v-.074zM4.15 15.799A.25.25 0 004.4 16h11.2a.25.25 0 00.25-.201l.534-3.47H3.616l.534 3.47zM13.5 9.75a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
@endif

<script>
    function openKreditModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContent = document.getElementById(modalId + 'Content');

        if (modal && modalContent) {
            // Display the modal container first with opacity 0
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Prevent scrolling
            document.body.style.overflow = 'hidden';

            // Trigger animations after a tiny delay to ensure the display change takes effect
            setTimeout(() => {
                modal.classList.add('bg-opacity-50');
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    function closeKreditModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContent = document.getElementById(modalId + 'Content');

        if (modal && modalContent) {
            // Start animations
            modal.classList.remove('bg-opacity-50');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            // Hide the modal after animations complete
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                // Restore scrolling
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Close modal when clicking outside of it
    document.addEventListener('DOMContentLoaded', function() {
        const kreditModals = document.querySelectorAll('[id^="creditModal-"]');

        kreditModals.forEach(modal => {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    const modalId = modal.getAttribute('id');
                    closeKreditModal(modalId);
                }
            });
        });
    });
</script>

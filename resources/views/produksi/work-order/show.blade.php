<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Detail'],
]" :currentPage="'Detail Perintah Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Detail Perintah Produksi: {{ $workOrder->nomor }}
                </h1>
                <div class="flex space-x-3">
                    <a href="{{ route('produksi.work-order.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>

                    @if ($workOrder->status === 'direncanakan')
                        @if (auth()->user()->hasPermission('work_order.edit'))
                            <a href="{{ route('produksi.work-order.edit', $workOrder->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Alert untuk status rework --}}
        @if ($workOrder->status === 'berjalan' && $workOrder->qualityControl && $workOrder->qualityControl->jumlah_gagal > 0)
            <div
                class="mb-6 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-orange-800 dark:text-orange-200">
                            Work Order dalam Status Rework
                        </h3>
                        <div class="mt-2 text-sm text-orange-700 dark:text-orange-300">
                            <p>
                                Work Order ini dikembalikan ke produksi untuk rework karena
                                {{ $workOrder->qualityControl->jumlah_gagal }} unit tidak memenuhi standar QC.
                                Silakan lakukan perbaikan dan QC ulang setelah selesai.
                            </p>
                        </div>
                        <div class="mt-3">
                            <div class="bg-orange-100 dark:bg-orange-800/30 rounded-md p-3">
                                <h4
                                    class="text-xs font-medium text-orange-800 dark:text-orange-200 uppercase tracking-wide mb-2">
                                    💡 Tips Proses Rework:
                                </h4>
                                <ul class="text-xs text-orange-700 dark:text-orange-300 space-y-1">
                                    <li>• Analisis penyebab kegagalan QC dari detail kriteria</li>
                                    <li>• Lakukan perbaikan sesuai standar operasional</li>
                                    <li>• Setelah rework selesai, ubah status ke "Selesai Produksi"</li>
                                    <li>• Lakukan QC ulang hingga semua unit memenuhi standar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action buttons based on status --}}
        @if ($workOrder->status != 'selesai')

            <div
                class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tindakan</h2>
                <div class="flex flex-wrap gap-3">
                    @if ($workOrder->status === 'direncanakan')
                        @if (auth()->user()->hasPermission('work_order.change_status'))
                            <a href="{{ route('produksi.work-order.create-pengambilan', $workOrder->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                Buat Pengambilan Bahan Baku
                            </a>
                        @endif

                        @if ($workOrder->pengambilanBahanBaku()->exists())
                            @if (auth()->user()->hasPermission('work_order.change_status'))
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                                    onclick="showConfirmModal('Mulai Produksi', 'Apakah Anda yakin ingin memulai proses produksi?', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'berjalan']) }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Mulai Produksi
                                </button>
                            @endif
                        @endif
                    @endif

                    @if ($workOrder->status === 'berjalan')
                        @if (auth()->user()->hasPermission('work_order.change_status'))
                            <a href="{{ route('produksi.work-order.create-pengambilan', $workOrder->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                Tambah Pengambilan Bahan Baku
                            </a>

                            <button type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                onclick="showConfirmModal('Selesai Produksi', 'Apakah produksi sudah selesai dan siap untuk Quality Control?', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'selesai_produksi']) }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Selesai Produksi
                            </button>
                        @endif
                    @endif

                    @if ($workOrder->status === 'selesai_produksi')
                        @if (auth()->user()->hasPermission('quality_control.create'))
                            {{-- Button untuk membuat Quality Control --}}
                            <a href="{{ route('produksi.work-order.create-qc', $workOrder->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Buat Quality Control
                            </a>

                            {{-- Button Rework hanya ditampilkan jika QC terakhir ada produk yang gagal --}}
                            @if ($workOrder->qualityControl && $workOrder->qualityControl->jumlah_gagal > 0)
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                                    onclick="showConfirmModal('Rework Produk', 'Apakah ada produk yang perlu diperbaiki/rework? Work Order akan dikembalikan ke status produksi.', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'berjalan']) }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Rework Produk
                                </button>
                            @endif

                            <button type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                onclick="showConfirmModal('QC Passed', 'Apakah Quality Control telah dilakukan dan produk lulus QC?', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'qc_passed']) }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                QC Passed
                            </button>
                        @endif
                    @endif

                    @if ($workOrder->status === 'qc_passed')
                        @if (auth()->user()->hasPermission('work_order.create'))
                            <a href="{{ route('produksi.work-order.create-pengembalian', $workOrder->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                </svg>
                                Buat Pengembalian Material
                            </a>

                            {{-- Button untuk rework jika ditemukan masalah setelah QC atau QC terakhir ada yang gagal --}}
                            @if ($workOrder->qualityControl && $workOrder->qualityControl->jumlah_gagal > 0)
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                                    onclick="showConfirmModal('Rework Produk', 'Apakah ditemukan masalah dan perlu rework produk? Work Order akan dikembalikan ke status produksi.', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'berjalan']) }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Rework Produk
                                </button>
                            @endif

                            <button type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                onclick="showConfirmModal('Pengembalian Material', 'Apakah pengembalian material sisa produksi telah dilakukan?', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'pengembalian_material']) }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Konfirmasi Pengembalian Material
                            </button>
                        @endif
                    @endif

                    @if ($workOrder->status === 'pengembalian_material')
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            onclick="showConfirmModal('Selesai Work Order', 'Apakah work order ini telah selesai dan produk siap disimpan di gudang hasil?', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'selesai']) }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Selesai Work Order
                        </button>
                    @endif

                    @if ($workOrder->status === 'qc_reject')
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="showConfirmModal('Kembali ke Produksi', 'Apakah Anda yakin akan mengembalikan work order ini ke tahap produksi?', '{{ route('produksi.work-order.change-status', ['id' => $workOrder->id, 'status' => 'berjalan']) }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Kembali ke Produksi
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Informasi Utama --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Utama</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Work Order</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->nomor }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->tanggal ? date('d/m/Y', strtotime($workOrder->tanggal)) : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->tanggal_mulai ? date('d/m/Y', strtotime($workOrder->tanggal_mulai)) : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deadline</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->deadline ? date('d/m/Y', strtotime($workOrder->deadline)) : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->tanggal_selesai ? date('d/m/Y', strtotime($workOrder->tanggal_selesai)) : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                @php
                                    $isRework =
                                        $workOrder->status === 'berjalan' &&
                                        $workOrder->qualityControl &&
                                        $workOrder->qualityControl->jumlah_gagal > 0;
                                @endphp

                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $workOrder->status == 'direncanakan' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                    {{ $workOrder->status == 'berjalan' && !$isRework ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300' : '' }}
                                    {{ $isRework ? 'bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-300' : '' }}
                                    {{ $workOrder->status == 'selesai_produksi' ? 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300' : '' }}
                                    {{ $workOrder->status == 'qc_passed' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-700 dark:text-indigo-300' : '' }}
                                    {{ $workOrder->status == 'pengembalian_material' ? 'bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-300' : '' }}
                                    {{ $workOrder->status == 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : '' }}
                                    {{ $workOrder->status == 'dibatalkan' ? 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' : '' }}">

                                    @if ($isRework)
                                        Rework ({{ $workOrder->qualityControl->jumlah_gagal }} unit)
                                    @else
                                        {{ ucwords(str_replace('_', ' ', $workOrder->status)) }}
                                    @endif
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Informasi Produk & Gudang --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Produk & Gudang</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->produk->nama ?? 'Produk tidak ditemukan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Produk</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->produk->kode ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kuantitas</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->quantity }} {{ $workOrder->satuan->nama ?? '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bill of Material</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->bom->nama ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->gudangProduksi->nama ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang Hasil</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->gudangHasil->nama ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Informasi Sales Order --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Sales Order</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Sales Order</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                @if ($workOrder->salesOrder)
                                    <a href="{{ route('penjualan.sales-order.show', $workOrder->salesOrder->id) }}"
                                        class="text-primary-600 hover:text-primary-800 hover:underline">
                                        {{ $workOrder->salesOrder->nomor }}
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Sales Order</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->salesOrder && $workOrder->salesOrder->tanggal ? date('d/m/Y', strtotime($workOrder->salesOrder->tanggal)) : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                @if ($workOrder->salesOrder && $workOrder->salesOrder->customer)
                                    <a href="" class="text-primary-600 hover:text-primary-800 hover:underline">
                                        {{ $workOrder->salesOrder->customer->nama ?? $workOrder->salesOrder->customer->company }}
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perencanaan Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                @if ($workOrder->perencanaanProduksi)
                                    <a href="{{ route('produksi.perencanaan-produksi.show', $workOrder->perencanaanProduksi->id) }}"
                                        class="text-primary-600 hover:text-primary-800 hover:underline">
                                        {{ $workOrder->perencanaanProduksi->nomor }}
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Material yang Dibutuhkan --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6 transition-all duration-300 hover:shadow-xl">
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-white dark:from-gray-700 dark:to-gray-800">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Material yang Dibutuhkan
                </h2>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Nama Material
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Kode
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Satuan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($workOrder->materials as $material)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $material->produk->nama ?? 'Produk tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <span
                                        class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300 font-mono text-xs">
                                        {{ $material->produk->kode ?? '-' }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-300 font-medium">
                                    {{ number_format($material->quantity, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $material->satuan->nama ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    Tidak ada data material
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat Pengambilan Bahan Baku --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6 transition-all duration-300 hover:shadow-xl">
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-white dark:from-gray-700 dark:to-gray-800">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Riwayat Pengambilan Bahan Baku
                </h2>
            </div>
            <div class="p-6">
                @if ($workOrder->pengambilanBahanBaku && $workOrder->pengambilanBahanBaku->count() > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach ($workOrder->pengambilanBahanBaku as $pengambilan)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 px-5 py-3 flex flex-wrap justify-between items-center">
                                    <div class="flex items-center">
                                        <div
                                            class="w-2 h-2 rounded-full mr-2 {{ $pengambilan->status == 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                        </div>
                                        <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $pengambilan->nomor }}</span>
                                        <span class="ml-3 text-sm text-gray-500 dark:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $pengambilan->tanggal ? date('d/m/Y', strtotime($pengambilan->tanggal)) : '-' }}
                                        </span>
                                    </div>
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $pengambilan->status == 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300' : '' }}
                                        {{ $pengambilan->status == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : '' }}
                                    ">
                                        {{ ucfirst($pengambilan->status == 'completed' ? 'selesai' : $pengambilan->status) }}
                                    </span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col"
                                                    class="px-5 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Material
                                                </th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Jumlah
                                                </th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Satuan
                                                </th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Keterangan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @forelse ($pengambilan->detail as $detail)
                                                <tr
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                                    <td
                                                        class="px-5 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                                    </td>
                                                    <td
                                                        class="px-5 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-700 dark:text-gray-300">
                                                        {{ number_format($detail->jumlah_diambil, 2, ',', '.') }}
                                                    </td>
                                                    <td
                                                        class="px-5 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $detail->satuan->nama ?? '-' }}
                                                    </td>
                                                    <td
                                                        class="px-5 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $detail->keterangan ?? '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4"
                                                        class="px-5 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6 mx-auto mb-1 text-gray-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Tidak ada detail pengambilan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="text-center text-gray-500 dark:text-gray-400 py-12 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada pengambilan bahan baku</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quality Control --}}
        @if ($workOrder->qualityControl)
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6 transition-all duration-300 hover:shadow-xl">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-white dark:from-gray-700 dark:to-gray-800">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Quality Control
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- QC Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 mr-2 text-gray-600 dark:text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Inspeksi
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Inspeksi
                                    </dt>
                                    <dd class="text-sm text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $workOrder->qualityControl && $workOrder->qualityControl->tanggal_inspeksi ? date('d/m/Y', strtotime($workOrder->qualityControl->tanggal_inspeksi)) : '-' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Inspector</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $workOrder->qualityControl && $workOrder->qualityControl->inspector ? $workOrder->qualityControl->inspector->name : '-' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd>
                                        @if ($workOrder->qualityControl)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $workOrder->qualityControl->status == 'semua_lolos' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : '' }}
                                                {{ $workOrder->qualityControl->status == 'sebagian_gagal' ? 'bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-300' : '' }}
                                                {{ $workOrder->qualityControl->status == 'semua_gagal' ? 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' : '' }}
                                            ">
                                                {{ ucwords(str_replace('_', ' ', $workOrder->qualityControl->status)) }}
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                -
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- QC Results -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 mr-2 text-gray-600 dark:text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Hasil Inspeksi
                            </h3>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Lolos</span>
                                        <span class="text-xl font-bold text-green-600 dark:text-green-400">
                                            {{ $workOrder->qualityControl ? $workOrder->qualityControl->jumlah_lolos : '-' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $workOrder->satuan->nama ?? '' }}</div>
                                    <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                        @php
                                            $totalQC = 0;
                                            $percentagePass = 0;

                                            if ($workOrder->qualityControl) {
                                                $totalQC =
                                                    $workOrder->qualityControl->jumlah_lolos +
                                                    $workOrder->qualityControl->jumlah_gagal;
                                                $percentagePass =
                                                    $totalQC > 0
                                                        ? ($workOrder->qualityControl->jumlah_lolos / $totalQC) * 100
                                                        : 0;
                                            }
                                        @endphp
                                        <div class="h-full bg-green-500 rounded-full"
                                            style="width: {{ $percentagePass }}%"></div>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Gagal</span>
                                        <span class="text-xl font-bold text-red-600 dark:text-red-400">
                                            {{ $workOrder->qualityControl ? $workOrder->qualityControl->jumlah_gagal : '-' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $workOrder->satuan->nama ?? '' }}</div>
                                    <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                        @php
                                            $percentageFail = 0;
                                            if ($workOrder->qualityControl && $totalQC > 0) {
                                                $percentageFail =
                                                    ($workOrder->qualityControl->jumlah_gagal / $totalQC) * 100;
                                            }
                                        @endphp
                                        <div class="h-full bg-red-500 rounded-full"
                                            style="width: {{ $percentageFail }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                            Produksi</span>
                                        <span class="text-lg font-bold text-gray-700 dark:text-gray-300">
                                            {{ $workOrder->qualityControl ? $workOrder->qualityControl->jumlah_lolos + $workOrder->qualityControl->jumlah_gagal : '-' }}
                                            <span
                                                class="text-sm font-normal">{{ $workOrder->satuan->nama ?? '' }}</span>
                                        </span>
                                    </div>
                                    <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-500 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Kriteria Pengujian
                        </h3>
                        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Kriteria
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Hasil
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @if ($workOrder->qualityControl && $workOrder->qualityControl->detail)
                                        @forelse ($workOrder->qualityControl->detail as $detail)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $detail->parameter }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $detail->status == 'pass' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : '' }}
                                                        {{ $detail->status == 'fail' ? 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' : '' }}
                                                    ">
                                                        {{ $detail->status == 'pass' ? 'Lolos' : 'Gagal' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $detail->keterangan ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3"
                                                    class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-8 w-8 mx-auto mb-2 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                    </svg>
                                                    Tidak ada detail kriteria
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($workOrder->qualityControl && $workOrder->qualityControl->catatan)
                        <div class="mt-6">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 mr-2 text-gray-600 dark:text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Catatan
                            </h3>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                    {{ $workOrder->qualityControl->catatan }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Riwayat QC dan Rework --}}
        @if ($workOrder->qualityControls && $workOrder->qualityControls->count() > 0)
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Riwayat Quality Control
                        <span
                            class="ml-2 text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 px-2 py-1 rounded-full">
                            {{ $workOrder->qualityControls->count() }} QC
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($workOrder->qualityControls as $index => $qc)
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 {{ $index === 0 ? 'ring-2 ring-blue-200 dark:ring-blue-800' : '' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                            QC #{{ $qc->nomor }}
                                        </h3>
                                        @if ($index === 0)
                                            <span
                                                class="ml-2 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 px-2 py-1 rounded-full">
                                                Terbaru
                                            </span>
                                        @endif
                                        @if ($index > 0)
                                            <span
                                                class="ml-2 text-xs bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300 px-2 py-1 rounded-full">
                                                QC ke-{{ $workOrder->qualityControls->count() - $index }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ date('d/m/Y H:i', strtotime($qc->created_at)) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-3 gap-4 mb-3">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                            {{ $qc->jumlah_lolos }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Unit Lolos</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-red-600 dark:text-red-400">
                                            {{ $qc->jumlah_gagal }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Unit Gagal</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-gray-700 dark:text-gray-300">
                                            {{ $qc->jumlah_lolos + $qc->jumlah_gagal }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Diperiksa</div>
                                    </div>
                                </div>

                                @if ($qc->jumlah_gagal > 0)
                                    <div
                                        class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md p-3 mb-3">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-orange-400 mr-2" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-orange-800 dark:text-orange-200 font-medium">
                                                {{ $qc->jumlah_gagal }} unit memerlukan rework
                                            </span>
                                        </div>
                                        @if ($index === 0 && $workOrder->status === 'berjalan')
                                            <div class="mt-2 text-xs text-orange-700 dark:text-orange-300">
                                                Status: Sedang dalam proses rework
                                            </div>
                                        @elseif($index > 0)
                                            <div class="mt-2 text-xs text-orange-700 dark:text-orange-300">
                                                Status: Telah dirework
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div
                                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-3 mb-3">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-green-400 mr-2" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-green-800 dark:text-green-200 font-medium">
                                                Semua unit lulus Quality Control
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Detail Kriteria QC --}}
                                @if ($qc->detail && $qc->detail->count() > 0)
                                    <div class="mt-3">
                                        <details class="group">
                                            <summary
                                                class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 flex items-center">
                                                <svg class="w-3 h-3 mr-1 transition-transform group-open:rotate-90"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Lihat Detail Kriteria QC ({{ $qc->detail->count() }} kriteria)
                                            </summary>
                                            <div
                                                class="mt-2 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-600 overflow-hidden">
                                                <div class="max-h-60 overflow-y-auto">
                                                    <table
                                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                                            <tr>
                                                                <th
                                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                                    Kriteria</th>
                                                                <th
                                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                                    Hasil</th>
                                                                <th
                                                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                                    Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                                            @foreach ($qc->detail as $detail)
                                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                    <td
                                                                        class="px-3 py-2 text-xs text-gray-900 dark:text-white">
                                                                        {{ $detail->parameter }}</td>
                                                                    <td class="px-3 py-2 text-xs">
                                                                        <span
                                                                            class="px-2 py-1 rounded-full text-xs font-medium {{ $detail->hasil === 'lolos' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                                            {{ ucwords($detail->hasil) }}
                                                                        </span>
                                                                    </td>
                                                                    <td
                                                                        class="px-3 py-2 text-xs text-gray-600 dark:text-gray-400">
                                                                        {{ $detail->keterangan ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </details>
                                    </div>
                                @endif

                                <div
                                    class="mt-3 text-xs text-gray-600 dark:text-gray-400 flex items-center justify-between">
                                    <span>Inspector: {{ $qc->inspector->name ?? '-' }}</span>
                                    @if ($qc->catatan)
                                        <span class="text-blue-600 dark:text-blue-400">
                                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Ada catatan
                                        </span>
                                    @endif
                                </div>

                                @if ($qc->catatan)
                                    <div
                                        class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
                                        <p class="text-xs text-blue-800 dark:text-blue-200">
                                            <strong>Catatan:</strong> {{ $qc->catatan }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Catatan --}}
        @if ($workOrder->catatan)
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-white dark:from-gray-700 dark:to-gray-800">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Catatan
                    </h2>
                </div>
                <div class="p-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                            {{ $workOrder->catatan }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modern Confirmation Modal --}}
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay, show/hide based on modal state -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-90"
                aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel, show/hide based on modal state -->
            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 animate-modal-appear"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div>
                    <div
                        class="flex items-center justify-center w-12 h-12 mx-auto bg-yellow-100 rounded-full dark:bg-yellow-900">
                        <!-- Warning icon -->
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modalTitle">
                            <!-- Title will be inserted dynamically -->
                        </h3>
                        <div class="mt-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400" id="modalMessage">
                                <!-- Message will be inserted dynamically -->
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 sm:flex sm:gap-3 sm:flex-row-reverse">
                    <form id="confirmForm" method="POST" action="" class="sm:w-1/2">
                        @csrf
                        <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white border border-transparent rounded-md shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm transition-colors duration-200">
                            Ya, Lanjutkan
                        </button>
                    </form>
                    <button type="button" onclick="closeConfirmModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-1/2 sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalAppear {
            0% {
                opacity: 0;
                transform: translate(0, 50px) scale(0.95);
            }

            100% {
                opacity: 1;
                transform: translate(0, 0) scale(1);
            }
        }

        .animate-modal-appear {
            animation: modalAppear 0.3s ease-out forwards;
        }
    </style>

    <script>
        function showConfirmModal(title, message, confirmUrl) {
            // Set modal content
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerText = message;
            document.getElementById('confirmForm').action = confirmUrl;

            // Show modal
            document.getElementById('confirmationModal').classList.remove('hidden');

            // Add body class to prevent scrolling
            document.body.classList.add('overflow-hidden');
        }

        function closeConfirmModal() {
            // Hide modal
            document.getElementById('confirmationModal').classList.add('hidden');

            // Remove body class to enable scrolling
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when pressing escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('confirmationModal').classList.contains(
                    'hidden')) {
                closeConfirmModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('confirmationModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeConfirmModal();
            }
        });
    </script>
</x-app-layout>

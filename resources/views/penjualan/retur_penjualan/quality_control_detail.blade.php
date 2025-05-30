<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1
                                class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                                Detail Quality Control - Retur Penjualan
                                <span class="text-sm font-normal px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                    {{ $returPenjualan->nomor }}
                                </span>
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Hasil pemeriksaan kualitas produk yang dikembalikan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm rounded-lg transition-colors duration-200 border border-gray-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Main QC Info Card -->
            <div
                class="md:col-span-2 bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Informasi QC
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status QC</h4>
                                <div class="flex items-center mt-1">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                                        {{ $returPenjualan->qc_passed ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-1.5 {{ $returPenjualan->qc_passed ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $returPenjualan->qc_passed ? 'Lulus Quality Control' : 'Tidak Lulus Quality Control' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal QC</h4>
                                <p class="text-base text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($returPenjualan->qc_at)->format('d F Y H:i') }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dilakukan Oleh
                                </h4>
                                <p class="text-base text-gray-900 dark:text-white">
                                    {{ $returPenjualan->qcByUser->name ?? 'Unknown' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Retur Penjualan
                                </h4>
                                <p class="text-base text-gray-900 dark:text-white">
                                    {{ $returPenjualan->nomor }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer</h4>
                                <p class="text-base text-gray-900 dark:text-white">
                                    {{ $returPenjualan->customer->nama ?? '-' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Retur</h4>
                                <p class="text-base text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($returPenjualan->tanggal)->format('d F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($returPenjualan->qc_notes)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan QC:</h4>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $returPenjualan->qc_notes }}</p>
                        </div>
                    @endif

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ringkasan Hasil QC:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/30">
                                <h5 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Total Item</h5>
                                <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">
                                    {{ $returPenjualan->details->count() }}
                                </p>
                            </div>
                            <div
                                class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800/30">
                                <h5 class="text-sm font-medium text-green-700 dark:text-green-300 mb-1">Lulus QC</h5>
                                <p class="text-2xl font-bold text-green-800 dark:text-green-200">
                                    {{ $returPenjualan->details->filter(function ($detail) {
                                            return isset($detail->qc_checked) && $detail->qc_checked && $detail->qc_passed;
                                        })->count() }}
                                </p>
                            </div>
                            <div
                                class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800/30">
                                <h5 class="text-sm font-medium text-red-700 dark:text-red-300 mb-1">Tidak Lulus QC</h5>
                                <p class="text-2xl font-bold text-red-800 dark:text-red-200">
                                    {{ $returPenjualan->details->filter(function ($detail) {
                                            return isset($detail->qc_checked) && $detail->qc_checked && !$detail->qc_passed;
                                        })->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Quick Links -->
            <div class="md:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tindakan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Lihat Detail Retur
                        </a>

                        <a href="{{ route('penjualan.retur.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm rounded-lg transition-colors duration-200 border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Daftar Retur Penjualan
                        </a>

                        @if ($returPenjualan->status === 'diproses')
                            <a href="#"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Selesaikan Retur
                            </a>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Waktu</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">QC Pada:</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($returPenjualan->qc_at)->format('d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Retur Dibuat:</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($returPenjualan->created_at)->format('d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diupdate:</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($returPenjualan->updated_at)->format('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QC Detail Table -->
        <div
            class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Item Quality Control</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Produk
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Alasan Retur
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status QC
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jenis Cacat
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($returPenjualan->details as $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->produk->kode }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ number_format($detail->quantity, 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $detail->satuan->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                        {{ $detail->alasan }}
                                    </span>
                                    @if ($detail->keterangan)
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $detail->keterangan }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (isset($detail->qc_checked) && $detail->qc_checked)
                                        @if ($detail->qc_passed)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                                Lulus QC
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                                Tidak Lulus QC
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Belum Diperiksa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($detail->defect_type && isset($defectTypes[$detail->defect_type]))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                            {{ $defectTypes[$detail->defect_type] }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detail->qc_notes ?? '-' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- QC Images Gallery -->
        <script>
            // Define these functions in the page context to ensure they're available
            if (typeof window.openImageModalDirect !== 'function') {
                window.openImageModalDirect = function(imageSrc, imageCaption, imageIndex) {

                    const modal = document.getElementById('imageModal');
                    const modalImage = document.getElementById('modalImage');
                    const modalCaption = document.getElementById('modalCaption');
                    const imageCounter = document.getElementById('imageCounter');

                    if (!modal || !modalImage) {
                        console.error('Modal elements not found for direct open');
                        alert('Error: Modal elements not found. Please try again.');
                        return;
                    }

                    // Show modal
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';

                    // Clear previous image first
                    modalImage.src = '';
                    modalImage.style.opacity = '0';

                    // Set new image
                    setTimeout(() => {
                        modalImage.src = imageSrc;
                        if (modalCaption) modalCaption.textContent = imageCaption || 'Image';

                        // Handle counter if available
                        const galleryImages = document.querySelectorAll('.qc-gallery-item');
                        if (imageCounter && galleryImages.length > 0) {
                            imageCounter.textContent = `${imageIndex + 1} / ${galleryImages.length}`;
                        }
                    }, 50);

                    // When image loads, make it visible
                    modalImage.onload = function() {

                        this.style.transition = 'opacity 0.3s ease-in-out';
                        this.style.opacity = '1';
                    };

                    return false; // Prevent default action
                };
            }

            if (typeof window.closeImageModalDirect !== 'function') {
                window.closeImageModalDirect = function() {
                    console.log('DIRECT CLOSE MODAL CALLED');
                    const modal = document.getElementById('imageModal');
                    const modalImage = document.getElementById('modalImage');

                    if (!modal) {
                        console.error('Modal element not found for direct close');
                        return;
                    }

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = 'auto';

                    if (modalImage) {
                        modalImage.src = '';
                        modalImage.style.opacity = '0';
                    }

                    return false; // Prevent default action
                };
            }
        </script>
        <div
            class="mt-6 bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd" />
                    </svg>
                    Foto Bukti QC
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-7">Klik foto untuk melihat ukuran penuh</p>
            </div>
            <div class="p-6" id="qcGalleryItemsContainer">
                @php
                    $hasImages = false;
                    foreach ($returPenjualan->details as $detail) {
                        if (!empty($detail->qc_images)) {
                            $hasImages = true;
                            break;
                        }
                    }
                @endphp

                @if ($hasImages)
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Klik pada foto untuk melihat ukuran penuh
                        dan navigasi antar foto.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($returPenjualan->details as $detail)
                            @if (!empty($detail->qc_images))
                                @php
                                    $images = is_string($detail->qc_images)
                                        ? json_decode($detail->qc_images)
                                        : $detail->qc_images;
                                    if (!is_array($images) && !is_object($images)) {
                                        $images = [$detail->qc_images];
                                    }
                                @endphp

                                @foreach ($images as $imageIndex => $image)
                                    <div class="aspect-w-1 aspect-h-1 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-700 shadow-md hover:shadow-xl transition-all duration-300 group qc-gallery-item cursor-pointer relative"
                                        data-fullsize="{{ asset('storage/' . $image) }}"
                                        data-caption="{{ $detail->produk->nama }} ({{ $detail->produk->kode }})"
                                        data-index="{{ $imageIndex }}"
                                        onclick="window.openImageModalDirect('{{ asset('storage/' . $image) }}', '{{ $detail->produk->nama }} ({{ $detail->produk->kode }})', {{ $imageIndex }})"
                                        style="position: relative; cursor: pointer;">
                                        <img src="{{ asset('storage/' . $image) }}"
                                            alt="QC Image untuk {{ $detail->produk->nama }}"
                                            class="object-cover w-full h-full group-hover:scale-110 transition-all duration-500 ease-in-out"
                                            onclick="event.stopPropagation(); window.openImageModalDirect('{{ asset('storage/' . $image) }}', '{{ $detail->produk->nama }} ({{ $detail->produk->kode }})', {{ $imageIndex }})">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300 flex flex-col justify-end p-3">
                                            <div
                                                class="mb-1 bg-black/50 text-white text-xs px-2 py-1 rounded-md w-fit flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-1">Klik untuk memperbesar</span>
                                            </div>
                                            <span class="text-white text-sm font-medium truncate">
                                                {{ $detail->produk->nama }}
                                            </span>
                                            <span class="text-white/80 text-xs truncate">
                                                {{ $detail->produk->kode }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada foto bukti QC</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Foto bukti QC akan ditampilkan di sini
                            jika tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal/Lightbox -->
    <div class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm" id="imageModal">
        <div class="absolute inset-0" id="modalBackdrop" onclick="window.closeImageModalDirect()"></div>
        <div class="relative z-10 w-full h-full flex flex-col items-center justify-center">
            <!-- Close Button -->
            <button
                class="absolute top-4 right-4 z-30 p-2 text-white bg-black/30 hover:bg-black/50 rounded-full transition-colors shadow-lg"
                id="modalClose" aria-label="Tutup" onclick="window.closeImageModalDirect()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Image Container with Navigation Controls -->
            <div class="relative flex items-center justify-center h-full w-full p-4">
                <!-- Previous Button -->
                <button
                    class="absolute left-6 z-30 p-2 text-white bg-black/30 hover:bg-black/50 rounded-full transition-colors shadow-lg hidden md:flex items-center justify-center"
                    id="prevImage" aria-label="Gambar Sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Main Image -->
                <div class="max-w-full max-h-full flex items-center justify-center">
                    <img src="" alt="Full Size QC Image"
                        class="rounded-lg shadow-2xl transition-all duration-500 opacity-0 max-h-[85vh] max-w-[95vw]"
                        id="modalImage"
                        style="object-fit: contain !important; display: block !important; margin: 0 auto !important; transform: none !important;">
                </div>

                <!-- Next Button -->
                <button
                    class="absolute right-6 z-30 p-2 text-white bg-black/30 hover:bg-black/50 rounded-full transition-colors shadow-lg hidden md:flex items-center justify-center"
                    id="nextImage" aria-label="Gambar Selanjutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Caption and Image Counter -->
            <div class="mt-4 text-white text-center">
                <h4 class="font-medium text-lg" id="modalCaption"></h4>
                <p class="text-sm text-white/70 mt-1" id="imageCounter"></p>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script src="{{ asset('js/quality_control_detail_lightbox.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('imageModal');
                    const modalImage = document.getElementById('modalImage');
                    const modalCaption = document.getElementById('modalCaption');
                    const imageCounter = document.getElementById('imageCounter');

                    if (!modal || !modalImage) {
                        console.error('Modal elements not found for direct open');
                        return;
                    }

                    // Show modal
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';

                    // Clear previous image first
                    modalImage.src = '';
                    modalImage.style.opacity = '0';

                    // Set new image
                    setTimeout(() => {
                        modalImage.src = imageSrc;
                        if (modalCaption) modalCaption.textContent = imageCaption || 'Image';

                        // Handle counter if available
                        const galleryImages = document.querySelectorAll('.qc-gallery-item');
                        if (imageCounter && galleryImages.length > 0) {
                            imageCounter.textContent = `${imageIndex + 1} / ${galleryImages.length}`;
                        }
                    }, 50);

                    // When image loads, make it visible
                    modalImage.onload = function() {
                        this.style.transition = 'opacity 0.3s ease-in-out';
                        this.style.opacity = '1';

                        // Apply proper sizing
                        this.style.maxWidth = '95vw';
                        this.style.maxHeight = '85vh';
                        this.style.objectFit = 'contain';
                        this.style.margin = '0 auto';
                    };

                    return false; // Prevent default action
                };

                // Direct modal close function
                window.closeImageModalDirect = function() {
                    const modal = document.getElementById('imageModal');
                    const modalImage = document.getElementById('modalImage');

                    if (!modal) {
                        console.error('Modal element not found for direct close');
                        return;
                    }

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = 'auto';

                    if (modalImage) {
                        modalImage.src = '';
                        modalImage.style.opacity = '0';
                    }

                    return false; // Prevent default action
                };

                // Add keyboard support for direct modal approach
                document.addEventListener('keydown', function(event) {
                    const modal = document.getElementById('imageModal');

                    if (modal && !modal.classList.contains('hidden')) {
                        if (event.key === 'Escape') {
                            window.closeImageModalDirect();
                        }
                    }
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('imageModal');
                    const modalImage = document.getElementById('modalImage');
                    const modalCaption = document.getElementById('modalCaption');
                    const imageCounter = document.getElementById('imageCounter');
                    const prevButton = document.getElementById('prevImage');
                    const nextButton = document.getElementById('nextImage');
                    const modalCloseButtons = document.querySelectorAll('#modalClose'); // Get all close buttons
                    const modalBackdrop = document.getElementById('modalBackdrop');
                    const galleryContainer = document.getElementById('qcGalleryItemsContainer');

                    if (!modal || !modalImage) {
                        console.error(
                            'Essential modal elements (imageModal or modalImage) not found. Gallery will not function.'
                        );
                        return;
                    }

                    const galleryImages = (galleryContainer || document).querySelectorAll('.qc-gallery-item');

                    if (galleryImages.length === 0) {
                        console.warn(
                            'No .qc-gallery-item elements found. Modal functionality will not be initialized for gallery items.'
                        );
                    }

                    const galleryImageSources = [];
                    galleryImages.forEach(item => {
                        if (item.dataset.fullsize) {
                            galleryImageSources.push({
                                src: item.dataset.fullsize,
                                caption: item.dataset.caption || `Image from ${item.dataset.fullsize}`
                            });
                        } else {
                            console.warn('Gallery item found without data-fullsize attribute:', item);
                        }
                    });

                    console.log('Processed gallery image sources:', galleryImageSources);

                    if (galleryImageSources.length === 0 && galleryImages.length > 0) {
                        console.warn(
                            'Gallery items were found, but no valid sources (data-fullsize) could be extracted. Modal will not open from gallery clicks.'
                        );
                    }

                    let currentImageIndex = 0;
                    let isModalImageLoading = false;

                    function updateImageDimensions() {
                        if (!modalImage || !modalImage.naturalWidth) {

                            if (modalImage) modalImage.style.opacity = '1';
                            return;
                        }

                        const naturalWidth = modalImage.naturalWidth;
                        const naturalHeight = modalImage.naturalHeight;

                        modalImage.style.width = 'auto';
                        modalImage.style.height = 'auto';
                        modalImage.style.maxWidth = '95vw';
                        modalImage.style.maxHeight = '85vh';
                        modalImage.style.objectFit = 'contain';
                        modalImage.style.display = 'block';
                        modalImage.style.margin = '0 auto';

                    }

                    modalImage.addEventListener('load', function() {

                        isModalImageLoading = false;
                        // Ensure opacity transition happens correctly
                        this.style.transition = 'none'; // Remove transition temporarily
                        this.classList.remove('opacity-0'); // Tailwind class for opacity
                        this.style.opacity = '0'; // Set initial opacity for fade-in

                        updateImageDimensions(); // Adjust size

                        // Force reflow
                        void this.offsetWidth;

                        // Re-apply transition and fade in
                        this.style.transition = 'opacity 0.3s ease-in-out';
                        this.style.opacity = '1';
                        // console.log('Modal image opacity set to 1 after load and dimensions update.');
                    });

                    modalImage.addEventListener('error', function() {
                        console.error(`Error loading modal image: ${this.src}`);
                        isModalImageLoading = false;
                        modalCaption.textContent = 'Error loading image.';
                        this.classList.remove('opacity-0');
                        this.style.opacity = '1'; // Show potential alt text or broken image icon
                    });

                    function openImageModal(index) {


                        if (!modal) {
                            console.error('CRITICAL: Modal element is null in openImageModal.');
                            return;
                        }
                        if (index < 0 || index >= galleryImageSources.length) {
                            console.error(
                                `Invalid index ${index} for galleryImageSources (length ${galleryImageSources.length}).`
                            );
                            return;
                        }

                        console.log('Modal current classes (before open):', modal.className);
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        document.body.style.overflow = 'hidden';

                        currentImageIndex = index;
                        const source = galleryImageSources[currentImageIndex];

                        if (!source || !source.src) {
                            console.error(`Source or source.src is undefined for index ${index}. Source:`, source);
                            if (modalCaption) modalCaption.textContent = 'Image source error.';
                            modalImage.src = '';
                            return;
                        }

                        console.log(`Loading image for modal: ${source.src}`);
                        isModalImageLoading = true;
                        modalImage.style.opacity = '0';
                        modalImage.classList.add('opacity-0');
                        // Clearing src before setting it again helps if the new src is the same as the old one, ensuring 'load' event fires.
                        modalImage.src = '';


                        // Preload image before setting to modalImage.src
                        const tempImg = new Image();
                        tempImg.onload = () => {

                            modalImage.src = tempImg.src; // This will trigger modalImage's 'load' event
                        };
                        tempImg.onerror = () => {
                            console.error(`Error preloading image: ${source.src}`);
                            if (modalCaption) modalCaption.textContent = 'Error loading image.';
                            modalImage.src = '';
                            isModalImageLoading = false;
                            modalImage.style.opacity = '1';
                        };
                        tempImg.src = source.src;

                        if (modalCaption) modalCaption.textContent = source.caption || 'Image';
                        if (imageCounter) imageCounter.textContent =
                            `${currentImageIndex + 1} / ${galleryImageSources.length}`;

                        if (prevButton) prevButton.classList.toggle('hidden', galleryImageSources.length <= 1 || !
                            prevButton.classList.contains('md:flex'));
                        if (nextButton) nextButton.classList.toggle('hidden', galleryImageSources.length <= 1 || !
                            nextButton.classList.contains('md:flex'));
                    }

                    function closeModal() {
                        console.log('--- closeModal called ---');
                        if (!modal) {
                            console.error('CRITICAL: Modal element is null in closeModal.');
                            return;
                        }
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = 'auto';
                        if (modalImage) {
                            modalImage.src = '';
                            modalImage.style.opacity = '0';
                            modalImage.classList.add('opacity-0');
                        }
                        // console.log('Modal closed. Classes:', modal.className);
                    }

                    function navigateImages(direction) {
                        if (isModalImageLoading) {
                            // console.log('Navigation attempt ignored, modal image is currently loading.');
                            return;
                        }
                        // console.log(`--- navigateImages called with direction: ${direction} ---`);
                        const newIndex = currentImageIndex + direction;

                        if (newIndex >= 0 && newIndex < galleryImageSources.length) {
                            currentImageIndex = newIndex;
                            const source = galleryImageSources[currentImageIndex];

                            if (!source || !source.src) {
                                console.error(
                                    `Navigation: Source or source.src is undefined for index ${currentImageIndex}. Source:`,
                                    source);
                                if (modalCaption) modalCaption.textContent = 'Image source error.';
                                modalImage.src = '';
                                return;
                            }

                            console.log(`Navigating to image: ${source.src}`);
                            isModalImageLoading = true;
                            modalImage.style.opacity = '0';
                            modalImage.classList.add('opacity-0');
                            modalImage.src = ''; // Clear previous image

                            const tempImg = new Image();
                            tempImg.onload = () => {
                                // console.log(`Temporary preloader image loaded for navigation: ${tempImg.src}`);
                                modalImage.src = tempImg.src;
                            };
                            tempImg.onerror = () => {
                                console.error(`Error preloading image for navigation: ${source.src}`);
                                if (modalCaption) modalCaption.textContent = 'Error loading image.';
                                modalImage.src = '';
                                isModalImageLoading = false;
                                modalImage.style.opacity = '1';
                            };
                            tempImg.src = source.src;

                            if (modalCaption) modalCaption.textContent = source.caption;
                            if (imageCounter) imageCounter.textContent =
                                `${currentImageIndex + 1} / ${galleryImageSources.length}`;
                        } else {}
                    }

                    // MODIFIED: Event delegation for gallery item clicks
                    if (galleryContainer) {
                        console.log('Attaching click listener to galleryContainer for .qc-gallery-item children.');
                        galleryContainer.addEventListener('click', function(event) {
                            const galleryItem = event.target.closest('.qc-gallery-item');
                            if (galleryItem) {

                                const fullsizeUrl = galleryItem.dataset.fullsize;
                                const caption = galleryItem.dataset.caption;

                                if (fullsizeUrl) {
                                    // console.log(`Fullsize URL: ${fullsizeUrl}, Caption: ${caption}`);
                                    if (galleryImageSources.length > 0) {
                                        const sourceIndex = galleryImageSources.findIndex(s => s.src ===
                                            fullsizeUrl);
                                        if (sourceIndex !== -1) {
                                            openImageModal(sourceIndex);
                                        } else {
                                            console.error(
                                                'Clicked image source not found in pre-processed list. Cannot open modal.',
                                                fullsizeUrl);
                                        }
                                    } else {
                                        console.error(
                                            'Gallery image sources array is empty. Cannot open modal.');
                                    }
                                } else {
                                    console.error('Clicked gallery item is missing data-fullsize attribute:',
                                        galleryItem);
                                }
                            }
                        });
                    } else {
                        // Fallback if galleryContainer was not found - this part remains similar to your previous individual listeners
                        // but it's less efficient and might still have issues if items are dynamically added.
                        galleryImages.forEach((item) => {
                            // Find the index based on the item's fullsize URL in the galleryImageSources array
                            // This is more robust than relying on the forEach index if items were filtered out
                            const fullsizeUrl = item.dataset.fullsize;
                            const sourceIndex = galleryImageSources.findIndex(s => s.src === fullsizeUrl);

                            console.log(
                                `Attaching direct click listener to .qc-gallery-item with fullsize: ${fullsizeUrl} (resolved sourceIndex: ${sourceIndex}):`,
                                item);

                            item.addEventListener('click', function() {
                                console.log(`--- .qc-gallery-item CLICKED (direct) --- Element:`, this);
                                if (this.dataset.fullsize) {
                                    console.log(
                                        `Fullsize URL: ${this.dataset.fullsize}, Caption: ${this.dataset.caption}`
                                    );
                                    if (galleryImageSources.length > 0) {
                                        // Re-find index in case it was not found initially or for safety
                                        const currentSourceIndex = galleryImageSources.findIndex(s => s
                                            .src === this.dataset.fullsize);
                                        if (currentSourceIndex !== -1) {
                                            openImageModal(currentSourceIndex);
                                        } else {
                                            console.error(
                                                'Clicked image source not found in pre-processed list (direct listener). Cannot open modal.',
                                                this.dataset.fullsize);
                                        }
                                    } else {
                                        console.error(
                                            'Gallery image sources array is empty (direct listener). Cannot open modal.'
                                        );
                                    }
                                } else {
                                    console.error(
                                        'Clicked gallery item is missing data-fullsize attribute (direct listener):',
                                        this);
                                }
                            });
                        });
                    }

                    modalCloseButtons.forEach(button => {
                        button.addEventListener('click', closeModal);
                    });

                    if (modalBackdrop) {
                        modalBackdrop.addEventListener('click', closeModal);
                    } else {
                        console.warn('Modal backdrop element (modalBackdrop) not found.');
                    }

                    if (prevButton) {
                        prevButton.addEventListener('click', () => navigateImages(-1));
                    } else {
                        console.warn('Previous image button (prevImage) not found.');
                    }

                    if (nextButton) {
                        nextButton.addEventListener('click', () => navigateImages(1));
                    } else {
                        console.warn('Next image button (nextImage) not found.');
                    }

                    document.addEventListener('keydown', function(event) {
                        if (modal && !modal.classList.contains('hidden')) {
                            if (event.key === 'Escape') {
                                console.log('Escape key pressed, closing modal.');
                                closeModal();
                            } else if (event.key === 'ArrowLeft') {
                                if (prevButton && !prevButton.classList.contains(
                                        'hidden')) { // Check if button is visible
                                    console.log('ArrowLeft key pressed, navigating to previous image.');
                                    navigateImages(-1);
                                }
                            } else if (event.key === 'ArrowRight') {
                                if (nextButton && !nextButton.classList.contains(
                                        'hidden')) { // Check if button is visible
                                    console.log('ArrowRight key pressed, navigating to next image.');
                                    navigateImages(1);
                                }
                            }
                        }
                    });
                    // console.log('QC Image Gallery script initialization complete. Event listeners attached.');
                });
    </script>
@endpush

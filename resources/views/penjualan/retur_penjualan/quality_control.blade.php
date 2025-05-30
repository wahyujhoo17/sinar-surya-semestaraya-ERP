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
                                Quality Control - Retur Penjualan
                                <span class="text-sm font-normal px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                    {{ $returPenjualan->nomor }}
                                </span>
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Pemeriksaan kualitas produk yang dikembalikan
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

        <div class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-100">
            <form action="{{ route('penjualan.retur.process-quality-control', $returPenjualan->id) }}" method="POST"
                enctype="multipart/form-data" id="qc-form">
                @csrf

                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Formulir Quality Control
                        </h3>
                        <div class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($returPenjualan->tanggal)->format('d F Y') }}
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                        <!-- Information Section -->
                        <div
                            class="bg-blue-50 dark:bg-gray-700 p-5 rounded-lg border border-blue-100 dark:border-gray-600 col-span-1">
                            <h4 class="text-base font-medium text-blue-700 dark:text-blue-300 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Retur
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between border-b border-blue-100 dark:border-gray-600 pb-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Nomor
                                        Retur</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $returPenjualan->nomor }}</span>
                                </div>
                                <div class="flex justify-between border-b border-blue-100 dark:border-gray-600 pb-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($returPenjualan->tanggal)->format('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between border-b border-blue-100 dark:border-gray-600 pb-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Customer</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $returPenjualan->customer->company ?? $returPenjualan->customer->nama }}</span>
                                </div>
                                <div class="flex justify-between pb-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sales
                                        Order</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $returPenjualan->salesOrder->nomor }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- QC Status Section -->
                        <div
                            class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm col-span-2">
                            <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Hasil Quality Control
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="qc_passed"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status QC Keseluruhan
                                    </label>
                                    <div class="relative">
                                        <select id="qc_passed" name="qc_passed"
                                            class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg shadow-sm">
                                            <option value="1">✅ Lulus QC</option>
                                            <option value="0">❌ Tidak Lulus QC</option>
                                        </select>
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Status QC keseluruhan dari barang retur
                                    </p>
                                </div>
                                <div>
                                    <label for="qc_notes"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Catatan QC Keseluruhan
                                    </label>
                                    <textarea id="qc_notes" name="qc_notes" rows="4"
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg shadow-sm"
                                        placeholder="Masukkan catatan atau observasi umum untuk QC..."></textarea>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Catatan tambahan terkait QC secara keseluruhan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Detail Produk dan Pemeriksaan
                        </h4>

                        <div
                            class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Qty
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Alasan Retur
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status QC
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tipe Cacat
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Catatan & Foto
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($returPenjualan->details as $detail)
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <td class="px-6 py-4">
                                                <input type="hidden" name="details[{{ $detail->id }}][id]"
                                                    value="{{ $detail->id }}">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
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
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $detail->quantity }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $detail->satuan->nama }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    {{ $detail->alasan }}
                                                </div>
                                                @if ($detail->keterangan)
                                                    <div
                                                        class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                                        {{ $detail->keterangan }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-3">
                                                    <div>
                                                        <label for="qc_checked_{{ $detail->id }}"
                                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                            Status Pemeriksaan
                                                        </label>
                                                        <select id="qc_checked_{{ $detail->id }}"
                                                            name="details[{{ $detail->id }}][qc_checked]"
                                                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                                            <option value="1">✓ Diperiksa</option>
                                                            <option value="0">○ Belum Diperiksa</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="qc_passed_{{ $detail->id }}"
                                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                            Hasil QC
                                                        </label>
                                                        <select id="qc_passed_{{ $detail->id }}"
                                                            name="details[{{ $detail->id }}][qc_passed]"
                                                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                                            <option value="1">✅ Lulus</option>
                                                            <option value="0">❌ Tidak Lulus</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <label for="defect_type_{{ $detail->id }}"
                                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                    Jenis Cacat Produk
                                                </label>
                                                <select id="defect_type_{{ $detail->id }}"
                                                    name="details[{{ $detail->id }}][defect_type]"
                                                    class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                                    <option value="">-- Pilih Tipe Cacat --</option>
                                                    @foreach ($defectTypes as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4">
                                                <label for="qc_notes_{{ $detail->id }}"
                                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                    Catatan Inspeksi
                                                </label>
                                                <textarea id="qc_notes_{{ $detail->id }}" name="details[{{ $detail->id }}][qc_notes]" rows="2"
                                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm rounded-md"
                                                    placeholder="Catatan hasil pemeriksaan..."></textarea>

                                                <div class="mt-3">
                                                    <label for="qc_images_{{ $detail->id }}"
                                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 mr-1 text-indigo-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Foto Bukti Inspeksi
                                                    </label>
                                                    <div
                                                        class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                                        <input type="file" id="qc_images_{{ $detail->id }}"
                                                            name="details[{{ $detail->id }}][qc_images][]" multiple
                                                            accept="image/*"
                                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                            onchange="updateFileCounter(this, 'file-counter-{{ $detail->id }}')">
                                                        <div class="text-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="mx-auto h-8 w-8 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                            </svg>
                                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                                <span id="file-counter-{{ $detail->id }}">Belum ada
                                                                    file</span><br>
                                                                Klik/tarik file gambar ke sini
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Hasil Quality Control
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileCounter(input, counterId) {
            const fileCount = input.files.length;
            const counterElement = document.getElementById(counterId);

            if (fileCount > 0) {
                counterElement.textContent = fileCount + ' file dipilih';
                counterElement.classList.add('font-medium', 'text-indigo-600');
            } else {
                counterElement.textContent = 'Belum ada file';
                counterElement.classList.remove('font-medium', 'text-indigo-600');
            }
        }
    </script>
</x-app-layout>

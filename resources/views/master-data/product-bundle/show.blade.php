<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('master.product-bundle.index') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
                                Product Bundle
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Detail Bundle</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    {{ $productBundle->nama }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    ID: <span class="font-mono font-bold text-gray-700 dark:text-gray-300">{{ $productBundle->kode }}</span> • Terakhir diperbarui {{ $productBundle->updated_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('master.product-bundle.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                @hasPermission('product_bundle.edit')
                    <a href="{{ route('master.product-bundle.edit', $productBundle->id) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Bundle
                    </a>
                @endhasPermission
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Column: Main Info --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Basic Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                            Informasi Produk
                        </h2>
                        @if ($productBundle->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 mr-2 rounded-full bg-green-500 animate-pulse"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-1.5 h-1.5 mr-2 rounded-full bg-red-500"></span>
                                NON-AKTIF
                            </span>
                        @endif
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Bundle</label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $productBundle->nama }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kode Bundle</label>
                                    <p class="text-lg font-mono font-bold text-blue-600 dark:text-blue-400">{{ $productBundle->kode }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kategori</label>
                                    <div class="mt-1">
                                        @if ($productBundle->kategori)
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">
                                                {{ $productBundle->kategori->nama }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic text-sm font-light">Tidak ada kategori</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Dibuat</label>
                                    <p class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $productBundle->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Terakhir Diupdate</label>
                                    <p class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $productBundle->updated_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed italic">
                                        {{ $productBundle->deskripsi ?: 'Tidak ada deskripsi tambahan untuk bundle ini.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products List Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Daftar Produk dalam Bundle
                        </h2>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-lg uppercase">
                            {{ $productBundle->items->count() }} Items
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Qty</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Harga Satuan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Subtotal</th>
                                    <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Stok Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($productBundle->items as $item)
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                    @if($item->produk->gambar && Storage::disk('public')->exists($item->produk->gambar))
                                                        <img src="{{ Storage::url($item->produk->gambar) }}" class="w-8 h-8 object-cover rounded shadow-sm">
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->produk->nama }}</div>
                                                    <div class="text-xs font-mono text-gray-500">{{ $item->produk->kode }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-md">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-5 text-right text-sm font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            @php
                                                $stokTersedia = $item->produk->stokTersedia();
                                                $isLow = $stokTersedia < $item->quantity;
                                            @endphp
                                            <div class="flex flex-col items-end">
                                                <span class="text-sm font-bold {{ $isLow ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $stokTersedia }} Unit
                                                </span>
                                                @if($isLow)
                                                    <span class="text-[10px] font-bold text-red-500 uppercase">Stok Kurang</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Column: Price Summary & Image --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Price Breakdown Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-8">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Harga</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Harga Normal Total</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300 decoration-red-400 line-through">
                                    Rp {{ number_format($productBundle->items->sum(function($item){return $item->quantity * $item->harga_satuan;}), 0, ',', '.') }}
                                </span>
                            </div>
                            
                            @php
                                $totalNormal = $productBundle->items->sum(function($item){return $item->quantity * $item->harga_satuan;});
                                $hargaBundle = $productBundle->harga_bundle;
                                $hemat = $totalNormal - $hargaBundle;
                                $persentase = $totalNormal > 0 ? ($hemat / $totalNormal) * 100 : 0;
                            @endphp

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Penghematan</span>
                                <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-bold rounded text-xs">
                                    {{ number_format($persentase, 1) }}% Hemat
                                </span>
                            </div>
                            
                            <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                                <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 text-center">Harga Bundle Spesial</span>
                                <div class="text-3xl font-black text-center text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($hargaBundle, 0, ',', '.') }}
                                </div>
                                <p class="text-[10px] text-center text-gray-400 mt-1 uppercase tracking-tighter">Sudah termasuk PPN jika berlaku</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Image Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-4">
                        <div class="relative group rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 aspect-square flex items-center justify-center">
                            @if ($productBundle->gambar && Storage::disk('public')->exists($productBundle->gambar))
                                <img src="{{ Storage::url($productBundle->gambar) }}" 
                                     alt="{{ $productBundle->nama }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 cursor-zoom-in"
                                     onclick="openImageModal('{{ Storage::url($productBundle->gambar) }}', '{{ $productBundle->nama }}')">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                    <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">No Image Preview</p>
                                </div>
                            @endif
                        </div>
                        @hasPermission('product_bundle.edit')
                        <div class="mt-4">
                            <a href="{{ route('master.product-bundle.edit', $productBundle->id) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Ganti Gambar Bundle
                            </a>
                        </div>
                        @endhasPermission
                    </div>
                </div>

                {{-- Action Card --}}
                <div class="bg-gray-900 rounded-2xl p-8 text-white shadow-2xl overflow-hidden relative">
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-blue-600 rounded-full blur-3xl opacity-20"></div>
                    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 bg-purple-600 rounded-full blur-3xl opacity-20"></div>
                    
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 relative z-10">Tindakan</h3>
                    <div class="space-y-4 relative z-10">
                        @hasPermission('product_bundle.create')
                        <a href="{{ route('master.product-bundle.create') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-lg shadow-blue-900/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Bundle Baru
                        </a>
                        @endhasPermission

                        @hasPermission('product_bundle.delete')
                        <button type="button" onclick="confirmDelete({{ $productBundle->id }})" class="w-full flex items-center justify-center gap-2 py-3 bg-transparent border border-red-500/50 hover:bg-red-500/10 text-red-500 text-sm font-bold rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Bundle
                        </button>
                        @endhasPermission
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Image Preview --}}
    <div id="imageModal" class="fixed inset-0 z-[60] hidden backdrop-blur-md bg-black/80 p-4 transition-all duration-300" onclick="closeImageModal()">
        <div class="relative w-full h-full flex items-center justify-center">
            <button class="absolute top-4 right-4 p-2 bg-white/10 hover:bg-white/20 rounded-full text-white transition-all duration-200" onclick="closeImageModal(event)">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl scale-95 transition-transform duration-300" onclick="event.stopPropagation()">
        </div>
    </div>

    <script>
        function openImageModal(src, alt) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = src;
            img.alt = alt;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                img.classList.remove('scale-95');
                img.classList.add('scale-100');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal(e) {
            if (e) e.stopPropagation();
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.classList.add('scale-95');
            img.classList.remove('scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeImageModal();
        });

        function confirmDelete(bundleId) {
            Swal.fire({
                title: 'Hapus Bundle?',
                text: "Tindakan ini tidak dapat dibatalkan dan akan menghapus permanen data bundle ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Sekarang',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/master/product-bundle/${bundleId}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>


<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="py-8 px-4 mx-auto max-w-7xl lg:py-10">
        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                Detail Produk
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('master.produk.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <button @click="window.dispatchEvent(new CustomEvent('open-produk-modal', {detail: {mode: 'edit', product: {{ json_encode($produk) }} }}))"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Produk
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Left side (Image) -->
                <div class="p-6 flex items-center justify-center md:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    @if($produk->gambar)
                        <div class="relative aspect-square w-full max-w-md rounded-lg overflow-hidden shadow-md bg-white dark:bg-gray-700">
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"
                                 class="w-full h-full object-contain"
                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}'; this.classList.add('object-contain', 'p-4')">
                            
                            @if(!$produk->is_active)
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                        Nonaktif
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="relative aspect-square w-full max-w-md rounded-lg overflow-hidden shadow-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-32 h-32 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            
                            @if(!$produk->is_active)
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                        Nonaktif
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Right side (Content) -->
                <div class="md:col-span-2 p-6">
                    <!-- Header information -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $produk->nama }}</h2>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <div class="font-mono text-sm px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-gray-700 dark:text-gray-300">
                                {{ $produk->kode }}
                            </div>
                            
                            @if($produk->product_sku)
                                <div class="font-mono text-sm px-3 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-md text-blue-700 dark:text-blue-300">
                                    SKU: {{ $produk->product_sku }}
                                </div>
                            @endif
                            
                            @if($produk->kategori)
                                <div class="px-3 py-1 rounded-md text-xs font-medium
                                    {{ 'bg-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][($produk->kategori->id % 7)] . '-100 dark:bg-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][($produk->kategori->id % 7)] . '-900/30 text-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][($produk->kategori->id % 7)] . '-800 dark:text-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][($produk->kategori->id % 7)] . '-300' }}">
                                    {{ $produk->kategori->nama }}
                                </div>
                            @endif
                            
                            @php
                                // Hitung margin keuntungan
                                $margin = $produk->harga_jual > 0 && $produk->harga_beli > 0 
                                        ? (($produk->harga_jual - $produk->harga_beli) / $produk->harga_beli) * 100 
                                        : 0;
                                
                                // Status stok berdasarkan stok_minimum
                                $stokStatus = 'normal';
                                if ($produk->total_stok <= 0) {
                                    $stokStatus = 'habis';
                                } elseif ($produk->total_stok <= $produk->stok_minimum) {
                                    $stokStatus = 'warning';
                                }
                            @endphp
                            
                            @if($stokStatus === 'habis')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Stok Habis
                                </span>
                            @elseif($stokStatus === 'warning')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Stok Minimum
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div x-data="{ activeTab: 'details' }">
                        <!-- Tab Navigation -->
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="flex space-x-8">
                                <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Informasi Dasar
                                </button>
                                <button @click="activeTab = 'specifications'" :class="activeTab === 'specifications' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Spesifikasi
                                </button>
                                <button @click="activeTab = 'pricing'" :class="activeTab === 'pricing' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Harga & Margin
                                </button>
                                <button @click="activeTab = 'stock'" :class="activeTab === 'stock' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="py-4 px-1 font-medium text-sm border-b-2 whitespace-nowrap">
                                    Stok & Gudang
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Content -->
                        <div class="mt-6">
                            <!-- Details Tab -->
                            <div x-show="activeTab === 'details'">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Produk</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $produk->kode }}</dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">SKU</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->product_sku ?: 'Tidak ada SKU' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Produk</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $produk->nama }}</dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Merek</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->merek ?: 'Tidak ada merek' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->kategori->nama ?? 'Tidak ada kategori' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sub Kategori</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->sub_kategori ?: 'Tidak ada sub kategori' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Produk</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->jenis->nama ?? 'Tidak ada jenis produk' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                        <dd class="mt-1">
                                            @if($produk->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            @if($produk->deskripsi)
                                                <p class="whitespace-pre-line">{{ $produk->deskripsi }}</p>
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Tidak ada deskripsi</p>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Specifications Tab -->
                            <div x-show="activeTab === 'specifications'">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Satuan</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->satuan->nama ?? 'Tidak ada satuan' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ukuran</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->ukuran ?: 'Tidak ada ukuran' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Material</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->type_material ?: 'Tidak ada tipe material' }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kualitas</dt>
                                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $produk->kualitas ?: 'Tidak ada informasi kualitas' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Pricing Tab -->
                            <div x-show="activeTab === 'pricing'">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Price Card -->
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="flex flex-col">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Informasi Harga</h3>
                                                
                                                <div class="mt-5 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Beli</p>
                                                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                                            {{ 'Rp ' . number_format($produk->harga_beli, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</p>
                                                        <p class="mt-1 text-2xl font-semibold text-primary-600 dark:text-primary-400">
                                                            {{ 'Rp ' . number_format($produk->harga_jual, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Margin Card -->
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="flex flex-col">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Margin Keuntungan</h3>
                                                
                                                <div class="mt-5 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Margin (%)</p>
                                                        <p class="mt-1 flex items-center">
                                                            <span class="text-2xl font-semibold 
                                                                {{ $margin >= 30 ? 'text-green-600 dark:text-green-400' : 
                                                                ($margin >= 15 ? 'text-blue-600 dark:text-blue-400' : 
                                                                'text-yellow-600 dark:text-yellow-400') }}">
                                                                {{ number_format($margin, 1) }}%
                                                            </span>
                                                        </p>
                                                    </div>
                                                    
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Keuntungan</p>
                                                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                                            {{ 'Rp ' . number_format($produk->harga_jual - $produk->harga_beli, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Profit Analysis -->
                                    <div class="sm:col-span-2 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div class="px-4 py-5 sm:p-6">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Analisis Harga</h3>
                                            
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 mb-4">
                                                <div class="bg-primary-600 h-4 rounded-full" style="width: {{ min(100, $margin) }}%"></div>
                                            </div>
                                            
                                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                                <div>Risiko Kerugian</div>
                                                <div>Margin Rendah</div>
                                                <div>Margin Ideal</div>
                                                <div>Margin Tinggi</div>
                                            </div>
                                            
                                            <div class="mt-4 flex items-center justify-between text-sm">
                                                <div class="text-gray-600 dark:text-gray-300">
                                                    <span class="font-medium">Evaluasi: </span>
                                                    @if($margin <= 0)
                                                        <span class="text-red-600 dark:text-red-400 font-medium">Kerugian</span>
                                                    @elseif($margin < 15)
                                                        <span class="text-yellow-600 dark:text-yellow-400 font-medium">Margin Rendah</span>
                                                    @elseif($margin < 30)
                                                        <span class="text-blue-600 dark:text-blue-400 font-medium">Margin Normal</span>
                                                    @else
                                                        <span class="text-green-600 dark:text-green-400 font-medium">Margin Tinggi</span>
                                                    @endif
                                                </div>
                                                <div class="text-gray-700 dark:text-gray-300">
                                                    <span class="font-medium">Rekomendasi: </span>
                                                    @if($margin <= 0)
                                                        <span class="text-red-600 dark:text-red-400">Naikkan harga jual atau turunkan harga beli</span>
                                                    @elseif($margin < 15)
                                                        <span class="text-yellow-600 dark:text-yellow-400">Pertimbangkan untuk menaikkan harga jual</span>
                                                    @elseif($margin < 30)
                                                        <span class="text-blue-600 dark:text-blue-400">Harga sudah cukup baik</span>
                                                    @else
                                                        <span class="text-green-600 dark:text-green-400">Margin sangat baik, pertahankan</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Tab -->
                            <div x-show="activeTab === 'stock'">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Stock Overview Card -->
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="flex flex-col">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Ringkasan Stok</h3>
                                                
                                                <div class="mt-5 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stok</p>
                                                        <p class="mt-1 flex items-center">
                                                            <span class="text-2xl font-semibold 
                                                                {{ $stokStatus === 'habis' ? 'text-red-600 dark:text-red-400' : 
                                                                ($stokStatus === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 
                                                                'text-green-600 dark:text-green-400') }}">
                                                                {{ $produk->total_stok }}
                                                            </span>
                                                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $produk->satuan->nama ?? 'unit' }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                    
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Minimum</p>
                                                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                                            {{ $produk->stok_minimum ?? 0 }}
                                                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                                {{ $produk->satuan->nama ?? 'unit' }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-5">
                                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Stok</p>
                                                    <div class="mt-2">
                                                        @if($stokStatus === 'habis')
                                                            <div class="flex items-center text-red-600 dark:text-red-400">
                                                                <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="font-medium">Stok Habis - Perlu segera restock!</span>
                                                            </div>
                                                        @elseif($stokStatus === 'warning')
                                                            <div class="flex items-center text-yellow-600 dark:text-yellow-400">
                                                                <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="font-medium">Stok Mendekati Minimum - Segera pesan tambahan</span>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center text-green-600 dark:text-green-400">
                                                                <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="font-medium">Stok Tersedia - Status normal</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Stock Visualization -->
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div class="px-4 py-5 sm:p-6">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Visualisasi Stok</h3>
                                            
                                            <div class="flex flex-col items-center">
                                                <!-- Circular Progress -->
                                                <div class="relative w-40 h-40">
                                                    <svg class="w-full h-full" viewBox="0 0 100 100">
                                                        <!-- Background Circle -->
                                                        <circle class="text-gray-200 dark:text-gray-700" stroke-width="10" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50"/>
                                                        
                                                        <!-- Progress Circle -->
                                                        @if($produk->stok_minimum > 0)
                                                            @php
                                                                $percentage = min(100, ($produk->total_stok / max(1, $produk->stok_minimum * 2)) * 100);
                                                                $strokeDasharray = 251.2; // Circumference of circle with r=40 (2πr)
                                                                $strokeDashoffset = $strokeDasharray - ($strokeDasharray * $percentage / 100);
                                                                $strokeColor = $stokStatus === 'habis' ? 'text-red-500 dark:text-red-400' : 
                                                                             ($stokStatus === 'warning' ? 'text-yellow-500 dark:text-yellow-400' : 
                                                                             'text-green-500 dark:text-green-400');
                                                            @endphp
                                                            <circle class="{{ $strokeColor }}"
                                                                stroke-width="10"
                                                                stroke-dasharray="{{ $strokeDasharray }}"
                                                                stroke-dashoffset="{{ $strokeDashoffset }}"
                                                                stroke-linecap="round"
                                                                stroke="currentColor"
                                                                fill="transparent"
                                                                r="40"
                                                                cx="50"
                                                                cy="50"
                                                                transform="rotate(-90 50 50)"
                                                            />
                                                        @else
                                                            @php
                                                                $strokeColor = $produk->total_stok <= 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400';
                                                            @endphp
                                                            <circle class="{{ $strokeColor }}"
                                                                stroke-width="10"
                                                                stroke-dasharray="251.2"
                                                                stroke-dashoffset="0"
                                                                stroke-linecap="round"
                                                                stroke="currentColor"
                                                                fill="transparent"
                                                                r="40"
                                                                cx="50"
                                                                cy="50"
                                                                transform="rotate(-90 50 50)"
                                                            />
                                                        @endif
                                                        
                                                        <!-- Center Text -->
                                                        <text x="50" y="50" font-size="20" text-anchor="middle" alignment-baseline="middle" class="fill-current text-gray-700 dark:text-gray-300 font-bold">
                                                            {{ $produk->total_stok }}
                                                        </text>
                                                        <text x="50" y="65" font-size="10" text-anchor="middle" alignment-baseline="middle" class="fill-current text-gray-500 dark:text-gray-400">
                                                            {{ $produk->satuan->nama ?? 'unit' }}
                                                        </text>
                                                    </svg>
                                                </div>
                                                
                                                <!-- Min & Available Legend -->
                                                <div class="flex justify-center gap-4 mt-4">
                                                    <div class="flex items-center">
                                                        <span class="inline-block w-3 h-3 mr-1.5 bg-yellow-500 dark:bg-yellow-400 rounded-full"></span>
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">Min: {{ $produk->stok_minimum ?? 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="inline-block w-3 h-3 mr-1.5 bg-green-500 dark:bg-green-400 rounded-full"></span>
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">Tersedia: {{ $produk->total_stok }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Warehouse Distribution (Empty State) -->
                                    <div class="sm:col-span-2 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Distribusi Stok per Gudang</h3>
                                                <a href="#" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                                    Lihat Semua
                                                </a>
                                            </div>
                                            
                                            @if(count($produk->stok) > 0)
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                            <tr>
                                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                    Gudang
                                                                </th>
                                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                    Jumlah
                                                                </th>
                                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                    Persentase
                                                                </th>
                                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                    Terakhir Update
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                            @foreach($produk->stok as $stok)
                                                                <tr>
                                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                                        {{ $stok->gudang->nama ?? 'Gudang #' . $stok->gudang_id }}
                                                                    </td>
                                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                                        {{ $stok->jumlah }} {{ $produk->satuan->nama ?? 'unit' }}
                                                                    </td>
                                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                                        @if($produk->total_stok > 0)
                                                                            {{ number_format(($stok->jumlah / $produk->total_stok) * 100, 1) }}%
                                                                        @else
                                                                            0%
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                                        {{ $stok->updated_at ? $stok->updated_at->format('d M Y H:i') : 'N/A' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="py-6 flex flex-col items-center justify-center text-center">
                                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                                    </svg>
                                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">Belum ada data stok</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mt-1">
                                                        Belum ada data stok untuk produk ini di gudang manapun.
                                                    </p>
                                                    <a href="#" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                        </svg>
                                                        Tambah Stok Baru
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
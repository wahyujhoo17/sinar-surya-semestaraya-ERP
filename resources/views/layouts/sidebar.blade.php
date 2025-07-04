<!-- Sidebar -->
<div x-cloak
    class="flex h-full flex-col border-r border-gray-200 dark:border-gray-700 bg-[#fdfdfd] dark:bg-gray-800 sidebar-shadow dark:sidebar-glow w-64">
    <!-- Logo -->
    <div
        class="flex items-center justify-start h-[77px] flex-shrink-0 px-4 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <div class="flex items-center">
                <img src="{{ asset('img/Logo_nama.png') }}" alt="Logo PT Sinar Surya Semestaraya" class="h-auto w-auto">
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <nav class="flex-1 py-4 px-2 space-y-1" x-data="{
            activeDropdown: localStorage.getItem('activeDropdown') || null,
            toggleDropdown(dropdown) {
                this.activeDropdown = this.activeDropdown === dropdown ? null : dropdown;
                localStorage.setItem('activeDropdown', this.activeDropdown);
            }
        }">
            <!-- Dashboard -->
            @if (auth()->user()->hasPermission('dashboard.view'))
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 menu-active-item' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
                    <div
                        class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                        <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span>Dashboard</span>
                </a>
            @endif

            <!-- Master Data -->
            @if (auth()->user()->hasPermission('produk.view') ||
                    auth()->user()->hasPermission('kategori_produk.view') ||
                    auth()->user()->hasPermission('pelanggan.view') ||
                    auth()->user()->hasPermission('supplier.view') ||
                    auth()->user()->hasPermission('gudang.view') ||
                    auth()->user()->hasPermission('satuan.view'))
                <div>
                    <button @click="toggleDropdown('masterData')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'masterData' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('master.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <span>Master Data</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'masterData' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'masterData'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('produk.view'))
                            <a href="{{ route('master.produk.index') }}"
                                class="{{ request()->routeIs('master.produk.index') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Produk</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('kategori_produk.view'))
                            <a href="{{ route('master.kategori-produk.index') }}"
                                class="{{ request()->routeIs('master.kategori-produk.index') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Kategori
                                    Produk</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('pelanggan.view'))
                            <a href="{{ route('master.pelanggan.index') }}"
                                class="{{ request()->routeIs('master.pelanggan.index') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Data
                                    Pelanggan</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('supplier.view'))
                            <a href="{{ route('master.supplier.index') }}"
                                class="{{ request()->routeIs('master.supplier.index') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Data
                                    Supplier</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('gudang.view'))
                            <a href="{{ route('master.gudang.index') }}"
                                class="{{ request()->routeIs('master.gudang.index') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Data
                                    Gudang</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('satuan.view'))
                            <a href="{{ route('master.satuan.index') }}"
                                class="{{ request()->routeIs('master.satuan.index') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Satuan</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Inventaris -->
            @if (auth()->user()->hasPermission('stok_barang.view') ||
                    auth()->user()->hasPermission('penyesuaian_stok.view') ||
                    auth()->user()->hasPermission('transfer_gudang.view') ||
                    auth()->user()->hasPermission('permintaan_barang.view'))
                <div>
                    <button @click="toggleDropdown('inventaris')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'inventaris' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('inventaris.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <span>Inventaris</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'inventaris' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'inventaris'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('stok_barang.view'))
                            <a href="/inventaris/stok"
                                class="{{ request()->is('inventaris/stok') || request()->is('inventaris/stok/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Stok
                                    Barang</span></a>
                        @endif
                        {{-- @if (auth()->user()->hasPermission('stok_barang.view'))
                            <a href="#"
                                class="{{ request()->is('inventaris/tracking') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Tracking
                                    Serial Number</span></a>
                        @endif --}}
                        @if (auth()->user()->hasPermission('penyesuaian_stok.view'))
                            <a href="/inventaris/penyesuaian-stok"
                                class="{{ request()->is('inventaris/penyesuaian-stok') || request()->is('inventaris/penyesuaian-stok/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Penyesuaian
                                    Stok</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('transfer_gudang.view'))
                            <a href="/inventaris/transfer-gudang"
                                class="{{ request()->is('inventaris/transfer-gudang') || request()->is('inventaris/transfer-gudang/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Transfer
                                    Gudang</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('permintaan_barang.view'))
                            <a href="/inventaris/permintaan-barang"
                                class="{{ request()->is('inventaris/permintaan-barang') || request()->is('inventaris/permintaan-barang/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>
                                    Permintaan Barang dari Sales Order</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Produksi -->
            @if (auth()->user()->hasPermission('work_order.view') ||
                    auth()->user()->hasPermission('bill_of_material.view') ||
                    auth()->user()->hasPermission('perencanaan_produksi.view'))
                <div>
                    <button @click="toggleDropdown('produksi')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'produksi' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('produksi.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.657 7.343A8 8 0 0118 18a8 8 0 01-.343.657z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.879 16.121A3 3 0 1012.014 14 3 3 0 009.879 16.121z" />
                                </svg>
                            </div>
                            <span>Produksi</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'produksi' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'produksi'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('work_order.view'))
                            <a href="/produksi/work-order"
                                class="{{ request()->is('produksi/work-order') || request()->is('produksi/work-order/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Perintah
                                    Produksi (WO)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('bill_of_material.view'))
                            <a href="/produksi/bom"
                                class="{{ request()->is('produksi/bom') || request()->is('produksi/bom/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Bill
                                    of Materials (BOM)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('perencanaan_produksi.view'))
                            <a href="/produksi/perencanaan-produksi"
                                class="{{ request()->is('produksi/perencanaan-produksi') || request()->is('produksi/perencanaan-produksi/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Perencanaan
                                    Produksi</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Penjualan -->
            @if (auth()->user()->hasPermission('quotation.view') ||
                    auth()->user()->hasPermission('sales_order.view') ||
                    auth()->user()->hasPermission('delivery_order.view') ||
                    auth()->user()->hasPermission('invoice.view') ||
                    auth()->user()->hasPermission('retur_penjualan.view') ||
                    auth()->user()->hasPermission('nota_kredit.view'))
                <div>
                    <button @click="toggleDropdown('penjualan')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'penjualan' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('penjualan.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span>Penjualan</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'penjualan' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'penjualan'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('quotation.view'))
                            <a href="/penjualan/quotation"
                                class="{{ request()->is('penjualan/quotation') || request()->is('penjualan/quotation/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Quotation</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('sales_order.view'))
                            <a href="/penjualan/sales-order"
                                class="{{ request()->is('penjualan/sales-order') || request()->is('penjualan/sales-order/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Sales
                                    Order (SO)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('delivery_order.view'))
                            <a href="/penjualan/delivery-order"
                                class="{{ request()->is('penjualan/delivery-order') || request()->is('penjualan/delivery-order/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Delivery
                                    Order (DO)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('invoice.view'))
                            <a href="/penjualan/invoice"
                                class="{{ request()->is('penjualan/invoice') || request()->is('penjualan/invoice/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Invoice
                                    Penjualan</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('retur_penjualan.view'))
                            <a href="/penjualan/retur"
                                class="{{ request()->is('penjualan/retur') || request()->is('penjualan/retur/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Retur
                                    Penjualan</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('nota_kredit.view'))
                            <a href="/penjualan/nota-kredit"
                                class="{{ request()->is('penjualan/nota-kredit') || request()->is('penjualan/nota-kredit/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Nota
                                    Kredit</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('sales_order.view') || auth()->user()->hasPermission('invoice.view'))
                            <a href="/penjualan/riwayat-transaksi"
                                class="{{ request()->is('penjualan/riwayat-transaksi') || request()->is('penjualan/riwayat-transaksi/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Riwayat
                                    Transaksi</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Pembelian -->
            @if (auth()->user()->hasPermission('purchase_request.view') ||
                    auth()->user()->hasPermission('purchase_order.view') ||
                    auth()->user()->hasPermission('penerimaan_barang.view') ||
                    auth()->user()->hasPermission('retur_pembelian.view'))
                <div>
                    <button @click="toggleDropdown('pembelian')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'pembelian' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('pembelian.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span>Pembelian</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'pembelian' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'pembelian'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('purchase_request.view'))
                            <a href="/pembelian/permintaan-pembelian"
                                class="{{ request()->is('pembelian/permintaan-pembelian') || request()->is('pembelian/permintaan-pembelian/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Permintaan
                                    Pembelian (PR)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('purchase_order.view'))
                            <a href="/pembelian/purchasing-order"
                                class="{{ request()->is('pembelian/purchasing-order') || request()->is('pembelian/purchasing-order/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Purchase
                                    Order (PO)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('penerimaan_barang.view'))
                            <a href="/pembelian/penerimaan-barang"
                                class="{{ request()->is('pembelian/penerimaan-barang') || request()->is('pembelian/penerimaan-barang/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Penerimaan
                                    Barang (GRN)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('retur_pembelian.view'))
                            <a href="/pembelian/retur-pembelian"
                                class="{{ request()->is('pembelian/retur-pembelian') || request()->is('pembelian/retur-pembelian/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Retur
                                    Pembelian</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('purchase_order.view') || auth()->user()->hasPermission('penerimaan_barang.view'))
                            <a href="/pembelian/riwayat-transaksi"
                                class="{{ request()->is('pembelian/riwayat-transaksi') || request()->is('pembelian/riwayat-transaksi/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Riwayat
                                    Transaksi</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- HR & Karyawan -->
            @if (auth()->user()->hasPermission('karyawan.view') ||
                    auth()->user()->hasPermission('absensi.view') ||
                    auth()->user()->hasPermission('penggajian.view') ||
                    auth()->user()->hasPermission('cuti.view') ||
                    auth()->user()->hasPermission('struktur_organisasi.view'))
                <div>
                    <button @click="toggleDropdown('hr')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'hr' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('hr.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span>HR & Karyawan</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'hr' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'hr'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('karyawan.view'))
                            <a href="/hr/karyawan"
                                class="{{ request()->is('hr/karyawan') || request()->is('hr/karyawan/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Data
                                    Karyawan</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('absensi.view'))
                            <a href="/hr/absensi"
                                class="{{ request()->is('hr/absensi') || request()->is('hr/absensi/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Absensi
                                    & Kehadiran</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('penggajian.view'))
                            <a href="/hr/penggajian"
                                class="{{ request()->is('hr/penggajian') || request()->is('hr/penggajian/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Penggajian
                                    & Tunjangan</span></a>
                        @endif
                        {{-- @if (auth()->user()->hasPermission('cuti.view'))
                            <a href="#"
                                class="{{ request()->is('hr/cuti') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Cuti
                                    & Izin</span></a>
                        @endif --}}
                        @if (auth()->user()->hasPermission('struktur_organisasi.view'))
                            <a href="/hr/struktur-organisasi"
                                class="{{ request()->is('hr/struktur-organisasi') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Struktur
                                    Organisasi</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- CRM -->
            @if (auth()->user()->hasPermission('prospek_lead.view') ||
                    auth()->user()->hasPermission('aktivitas_prospek.view') ||
                    auth()->user()->hasPermission('pipeline_penjualan.view'))
                <div>
                    <button @click="toggleDropdown('crm')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'crm' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('crm.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span>CRM</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'crm' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'crm'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('prospek_lead.view'))
                            <a href="{{ route('crm.prospek.index') }}"
                                class="{{ request()->is('crm/prospek') || request()->is('crm/prospek/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Prospek
                                    & Lead</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('aktivitas_prospek.view'))
                            <a href="{{ route('crm.aktivitas.index') }}"
                                class="{{ request()->is('crm/aktivitas') || request()->is('crm/aktivitas/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Aktivitas
                                    & Follow-up</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('pipeline_penjualan.view'))
                            <a href="{{ route('crm.pipeline.index') }}"
                                class="{{ request()->is('crm/pipeline') || request()->is('crm/pipeline/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Pipeline
                                    Penjualan</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Keuangan -->
            @if (auth()->user()->hasPermission('chart_of_accounts.view') ||
                    auth()->user()->hasPermission('jurnal_umum.view') ||
                    auth()->user()->hasPermission('piutang_usaha.view') ||
                    auth()->user()->hasPermission('hutang_usaha.view') ||
                    auth()->user()->hasPermission('kas_dan_bank.view') ||
                    auth()->user()->hasPermission('management_pajak.view') ||
                    auth()->user()->hasPermission('rekonsiliasi_bank.view'))
                <div>
                    <button @click="toggleDropdown('keuangan')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'keuangan' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('keuangan.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2-1.343-2-3-2m0 8c1.11 0 2.08-.402 2.599-1M12 16v1m0-1v-8m0 0c-1.657 0-3 .895-3 2s1.343 2 3 2m0 0c1.657 0 3 .895 3 2s-1.343 2-3 2m0 0c-1.11 0-2.08.402-2.599 1M9.401 15C9.126 14.283 9 13.474 9 12.6c0-1.79.684-3.418 1.808-4.632M14.599 9c.275.717.401 1.526.401 2.4 0 1.79-.684 3.418-1.808 4.632" />
                                </svg>
                            </div>
                            <span>Keuangan</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'keuangan' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'keuangan'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('chart_of_accounts.view'))
                            <a href="{{ route('keuangan.coa.index') }}"
                                class="{{ request()->is('keuangan/coa') || request()->is('keuangan/coa/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Chart
                                    of Accounts (COA)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('jurnal_umum.view'))
                            <a href="{{ route('keuangan.jurnal-umum.index') }}"
                                class="{{ request()->is('keuangan/jurnal-umum') || request()->is('keuangan/jurnal-umum/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Jurnal
                                    Umum</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('jurnal_umum.view'))
                            <a href="{{ route('keuangan.buku-besar.index') }}"
                                class="{{ request()->is('keuangan/buku-besar') || request()->is('keuangan/buku-besar/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Buku
                                    Besar (General Ledger)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('piutang_usaha.view'))
                            <a href="/keuangan/piutang-usaha"
                                class="{{ request()->is('keuangan/piutang-usaha') || request()->is('keuangan/piutang-usaha/*') || request()->is('keuangan/pembayaran-piutang/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Piutang
                                    Usaha (AR)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('hutang_usaha.view'))
                            <a href="/keuangan/hutang-usaha"
                                class="{{ request()->is('keuangan/hutang-usaha') || request()->is('keuangan/hutang-usaha/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Hutang
                                    Usaha (AP)</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('kas_dan_bank.view'))
                            <a href="/keuangan/kas-dan-bank"
                                class="{{ request()->is('keuangan/kas-dan-bank') || request()->is('keuangan/kas-dan-bank/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Kas
                                    & Bank</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('management_pajak.view'))
                            <a href="{{ route('keuangan.management-pajak.index') }}"
                                class="{{ request()->is('keuangan/management-pajak') || request()->is('keuangan/management-pajak/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Management
                                    Pajak</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('rekonsiliasi_bank.view'))
                            <a href="{{ route('keuangan.rekonsiliasi.index') }}"
                                class="{{ request()->is('keuangan/rekonsiliasi') || request()->is('keuangan/rekonsiliasi/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Rekonsiliasi</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('kas_dan_bank.view'))
                            <a href="/keuangan/pengembalian-dana"
                                class="{{ request()->is('keuangan/pengembalian-dana') || request()->is('keuangan/pengembalian-dana/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Pengembalian
                                    Dana</span></a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Laporan -->
            @if (auth()->user()->hasPermission('laporan_penjualan.view') ||
                    auth()->user()->hasPermission('laporan_pembelian.view') ||
                    auth()->user()->hasPermission('laporan_stok.view') ||
                    auth()->user()->hasPermission('laporan_produksi.view') ||
                    auth()->user()->hasPermission('laporan_keuangan.view'))
                <div>
                    <button @click="toggleDropdown('laporan')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'laporan' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('laporan.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span>Laporan</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'laporan' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'laporan'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('laporan_penjualan.view'))
                            <a href="/laporan/penjualan"
                                class="{{ request()->is('laporan/penjualan') || request()->is('laporan/penjualan/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Laporan
                                    Penjualan</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('laporan_pembelian.view'))
                            <a href="/laporan/pembelian"
                                class="{{ request()->is('laporan/pembelian') || request()->is('laporan/pembelian/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Laporan
                                    Pembelian</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('laporan_stok.view'))
                            <a href="/laporan/stok"
                                class="{{ request()->is('laporan/stok') || request()->is('laporan/stok/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Laporan
                                    Stok</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('laporan_produksi.view'))
                            <a href="/laporan/produksi"
                                class="{{ request()->is('laporan/produksi') || request()->is('laporan/produksi/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Laporan
                                    Produksi</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('laporan_keuangan.view'))
                            <a href="/laporan/keuangan"
                                class="{{ request()->is('laporan/keuangan') || request()->is('laporan/keuangan/*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Laporan
                                    Keuangan</span></a>
                        @endif
                    </div>
                </div>
            @endif



            <!-- Pengaturan -->
            @if (auth()->user()->hasPermission('pengaturan_umum.view') ||
                    auth()->user()->hasPermission('management_pengguna.view') ||
                    auth()->user()->hasPermission('hak_akses.view') ||
                    auth()->user()->hasPermission('log_aktivitas.view'))
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button @click="toggleDropdown('pengaturan')"
                        class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none transition-colors"
                        :class="{ 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': activeDropdown === 'pengaturan' }">
                        <div class="flex items-center">
                            <div
                                class="mr-3 flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-lg bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-300 shadow-sm sidebar-card-icon">
                                <svg class="h-5 w-5 {{ request()->routeIs('pengaturan.*') ? 'text-blue-600' : '' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span>Pengaturan</span>
                        </div>
                        <svg class="h-5 w-5"
                            :class="{ 'transform rotate-180 text-blue-600': activeDropdown === 'pengaturan' }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeDropdown === 'pengaturan'" x-cloak class="mt-1 space-y-1 pl-11">
                        @if (auth()->user()->hasPermission('pengaturan_umum.view'))
                            <a href="/pengaturan/umum"
                                class="{{ request()->is('pengaturan/umum') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Pengaturan
                                    Umum</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('management_pengguna.view'))
                            <a href="{{ route('pengaturan.management-pengguna.index') }}"
                                class="{{ request()->is('pengaturan/management-pengguna*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Management
                                    Pengguna</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('hak_akses.view'))
                            <a href="/pengaturan/hak-akses"
                                class="{{ request()->is('pengaturan/hak-akses') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Peran
                                    & Hak Akses</span></a>
                        @endif
                        @if (auth()->user()->hasPermission('log_aktivitas.view'))
                            <a href="{{ route('pengaturan.log-aktivitas.index') }}"
                                class="{{ request()->routeIs('pengaturan.log-aktivitas.*') ? 'text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} group flex items-center px-3 py-2 text-sm rounded-md transition-colors"><span>Log
                                    Aktivitas</span></a>
                        @endif
                    </div>
                </div>
            @endif
        </nav>
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-200 dark:border-gray-700 p-4 flex flex-col items-center justify-center">
        <div class="text-xs text-gray-500 text-center">
            {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}<br>
            <span class="font-semibold">Versi {{ setting('app_version', '1.0.0') }}</span>
        </div>
    </div>
</div>

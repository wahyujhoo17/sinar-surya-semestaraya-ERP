<x-app-layout>
    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('pembelian.purchasing-order.index') }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Purchase Order
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit Purchase
                                Order</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <form id="purchaseOrderForm" action="{{ route('pembelian.purchasing-order.update', $purchaseOrder->id) }}"
            method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6 flex flex-wrap items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Purchase Order</h1>

                <div class="flex items-center gap-x-2">
                    <span
                        class="px-3 py-1 text-xs font-medium rounded-lg
                        @if ($purchaseOrder->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                        @if ($purchaseOrder->status == 'sent') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 @endif
                        @if ($purchaseOrder->status == 'partial') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 @endif
                        @if ($purchaseOrder->status == 'received') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif
                        @if ($purchaseOrder->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif
                    ">
                        {{ ucfirst($purchaseOrder->status) }}
                    </span>
                </div>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6">
                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/30 dark:text-red-300">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column: Purchase Order Details -->
                <div class="md:col-span-2 space-y-6">

                    <!-- PO Info Card -->
                    <div x-data="purchaseOrderForm()"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Purchase
                                Order</h2>

                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                                <!-- Nomor PO -->
                                <div>
                                    <label for="nomor"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nomor PO <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nomor" id="nomor"
                                        value="{{ $purchaseOrder->nomor }}" readonly
                                        class="bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                </div>

                                <!-- Tanggal -->
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ $purchaseOrder->tanggal->format('Y-m-d') }}" required
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                        {{ $purchaseOrder->status !== 'draft' ? 'readonly' : '' }}>
                                </div>

                                <!-- Supplier -->
                                <div>
                                    <label for="supplier_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Supplier <span class="text-red-500">*</span>
                                    </label>
                                    <select id="supplier_id" name="supplier_id" required
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                        {{ $purchaseOrder->status !== 'draft' ? 'disabled' : '' }}>
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Referensi Permintaan Pembelian -->
                                <div>
                                    <label for="pr_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Permintaan Pembelian
                                    </label>
                                    <select id="pr_id" name="pr_id" @change="loadPurchaseRequest"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                        {{ $purchaseOrder->status !== 'draft' ? 'disabled' : '' }}>
                                        <option value="">Pilih PR (Opsional)</option>
                                        @foreach ($purchaseRequests as $pr)
                                            <option value="{{ $pr->id }}"
                                                {{ $purchaseOrder->pr_id == $pr->id ? 'selected' : '' }}>
                                                {{ $pr->nomor }} - {{ $pr->department->nama ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tanggal Pengiriman -->
                                <div>
                                    <label for="tanggal_pengiriman"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal Pengiriman
                                    </label>
                                    <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman"
                                        value="{{ $purchaseOrder->tanggal_pengiriman ? $purchaseOrder->tanggal_pengiriman->format('Y-m-d') : '' }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                </div>

                                <!-- Alamat Pengiriman -->
                                <div>
                                    <label for="alamat_pengiriman"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Alamat Pengiriman
                                    </label>
                                    <input type="text" name="alamat_pengiriman" id="alamat_pengiriman"
                                        value="{{ $purchaseOrder->alamat_pengiriman }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                        placeholder="Masukkan alamat pengiriman">
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" id="catatan" rows="3"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    placeholder="Tambahkan catatan jika diperlukan...">{{ $purchaseOrder->catatan }}</textarea>
                            </div>

                            <!-- Terms -->
                            <div>
                                <label for="syarat_ketentuan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Syarat & Ketentuan
                                </label>
                                <textarea name="syarat_ketentuan" id="syarat_ketentuan" rows="3"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    placeholder="Tambahkan syarat & ketentuan jika diperlukan...">{{ $purchaseOrder->syarat_ketentuan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Items Card -->
                    <div x-data="itemsTable()"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex flex-wrap items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Item Purchase Order
                                </h2>
                                @if ($purchaseOrder->status === 'draft')
                                    <button type="button" @click="addRow"
                                        class="inline-flex items-center gap-x-1 text-sm font-medium rounded-lg px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Item
                                    </button>
                                @endif
                            </div>

                            <!-- Item Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Produk</th>
                                            <th scope="col" class="px-4 py-3">Deskripsi</th>
                                            <th scope="col" class="px-4 py-3">Qty</th>
                                            <th scope="col" class="px-4 py-3">Satuan</th>
                                            <th scope="col" class="px-4 py-3">Harga</th>
                                            <th scope="col" class="px-4 py-3">Diskon</th>
                                            <th scope="col" class="px-4 py-3">Subtotal</th>
                                            @if ($purchaseOrder->status === 'draft')
                                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-if="items.length === 0">
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <td colspan="8"
                                                    class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                                    Belum ada item. Silahkan tambahkan item.
                                                </td>
                                            </tr>
                                        </template>

                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <!-- Produk -->
                                                <td class="px-4 py-3">
                                                    <input type="hidden" :name="`items[${index}][id]`"
                                                        x-model="item.id">
                                                    <select :name="`items[${index}][produk_id]`"
                                                        x-model="item.produk_id" @change="updateProductInfo(index)"
                                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2"
                                                        :disabled="item.locked || '{{ $purchaseOrder->status }}'
                                                        !== 'draft'">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($produks as $produk)
                                                            <option value="{{ $produk->id }}">{{ $produk->kode }} -
                                                                {{ $produk->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <!-- Deskripsi -->
                                                <td class="px-4 py-3">
                                                    <input type="text" :name="`items[${index}][deskripsi]`"
                                                        x-model="item.deskripsi"
                                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2"
                                                        placeholder="Deskripsi"
                                                        :readonly="item.locked || '{{ $purchaseOrder->status }}'
                                                        !== 'draft'">
                                                </td>

                                                <!-- Quantity -->
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="`items[${index}][quantity]`"
                                                        x-model="item.quantity" @input="calculateSubtotal(index)"
                                                        step="0.01" min="0.01"
                                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2"
                                                        placeholder="Qty"
                                                        :readonly="item.locked || '{{ $purchaseOrder->status }}'
                                                        !== 'draft'">
                                                </td>

                                                <!-- Satuan -->
                                                <td class="px-4 py-3">
                                                    <select :name="`items[${index}][satuan_id]`"
                                                        x-model="item.satuan_id"
                                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2"
                                                        :disabled="item.locked || '{{ $purchaseOrder->status }}'
                                                        !== 'draft'">
                                                        <option value="">Pilih Satuan</option>
                                                        @foreach ($satuans as $satuan)
                                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <!-- Harga -->
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="`items[${index}][harga]`"
                                                        x-model="item.harga" @input="calculateSubtotal(index)"
                                                        step="0.01" min="0"
                                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2"
                                                        placeholder="Harga"
                                                        :readonly="item.locked || '{{ $purchaseOrder->status }}'
                                                        !== 'draft'">
                                                </td>

                                                <!-- Diskon -->
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="`items[${index}][diskon]`"
                                                        x-model="item.diskon" @input="calculateSubtotal(index)"
                                                        step="0.01" min="0"
                                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2"
                                                        placeholder="Diskon"
                                                        :readonly="item.locked || '{{ $purchaseOrder->status }}'
                                                        !== 'draft'">
                                                </td>

                                                <!-- Subtotal -->
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="`items[${index}][subtotal]`"
                                                        x-model="item.subtotal" readonly
                                                        class="bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                                                </td>

                                                <!-- Actions -->
                                                @if ($purchaseOrder->status === 'draft')
                                                    <td class="px-4 py-3 text-right">
                                                        <button type="button" @click="removeRow(index)"
                                                            x-show="!item.locked"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary & Actions -->
                <div>
                    <div x-data="summaryCalculator()"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan</h2>

                            <div class="space-y-4">
                                <!-- Subtotal -->
                                <div>
                                    <label for="subtotal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Subtotal
                                    </label>
                                    <input type="number" name="subtotal" id="subtotal" x-model="subtotal" readonly
                                        class="bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                </div>

                                <!-- Diskon -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label for="diskon_persen"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Diskon (%)
                                        </label>
                                        <input type="number" name="diskon_persen" id="diskon_persen"
                                            x-model="diskonPersen" @input="calculateTotal" min="0"
                                            max="100"
                                            class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                            {{ $purchaseOrder->status !== 'draft' ? 'readonly' : '' }}>
                                    </div>

                                    <div>
                                        <label for="diskon_nominal"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Diskon (Nominal)
                                        </label>
                                        <input type="number" name="diskon_nominal" id="diskon_nominal"
                                            x-model="diskonNominal" @input="calculateTotal" min="0"
                                            class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                            {{ $purchaseOrder->status !== 'draft' ? 'readonly' : '' }}>
                                    </div>
                                </div>

                                <!-- PPN -->
                                <div>
                                    <label for="ppn"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        PPN (%)
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="ppn" id="ppn" x-model="ppn"
                                            @input="calculateTotal" min="0" max="100"
                                            class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                            {{ $purchaseOrder->status !== 'draft' ? 'readonly' : '' }}>
                                        @if ($purchaseOrder->status === 'draft')
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5">
                                                <button type="button" @click="togglePPN"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 focus:outline-none">
                                                    <template x-if="ppn === 11">
                                                        <span>Reset</span>
                                                    </template>
                                                    <template x-if="ppn !== 11">
                                                        <span>11%</span>
                                                    </template>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <label for="total"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Total
                                    </label>
                                    <input type="number" name="total" id="total" x-model="total" readonly
                                        class="bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 font-bold">
                                </div>

                                <div class="pt-4 space-y-3">
                                    @if ($purchaseOrder->status === 'draft')
                                        <button type="submit"
                                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-primary-600 border border-transparent rounded-lg text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Simpan Perubahan
                                        </button>

                                        <button type="submit" name="action" value="send"
                                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Kirim ke Supplier
                                        </button>
                                    @else
                                        <a href="{{ route('pembelian.purchasing-order.print', $purchaseOrder->id) }}"
                                            target="_blank"
                                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Cetak PO
                                        </a>

                                        @if ($purchaseOrder->status === 'sent' || $purchaseOrder->status === 'partial')
                                            <a href="{{ route('pembelian.purchasing-order.receive', $purchaseOrder->id) }}"
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-lg text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Terima Barang
                                            </a>
                                        @endif

                                        @if ($purchaseOrder->status !== 'cancelled' && $purchaseOrder->status !== 'received')
                                            <form
                                                action="{{ route('pembelian.purchasing-order.change-status', $purchaseOrder->id) }}"
                                                method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan PO ini?')"
                                                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 border border-transparent rounded-lg text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Batalkan PO
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <a href="{{ route('pembelian.purchasing-order.index') }}"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                        </svg>
                                        Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function purchaseOrderForm() {
                return {
                    loadPurchaseRequest() {
                        const prId = document.getElementById('pr_id').value;
                        if (!prId) return;

                        // AJAX call to get PR details
                        fetch(`/pembelian/permintaan-pembelian/${prId}/details`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Get Alpine items instance
                                    const itemsTable = Alpine.evaluate(document.querySelector('[x-data="itemsTable()"]'),
                                        'items');

                                    // Clear existing items
                                    itemsTable.splice(0, itemsTable.length);

                                    // Add PR items
                                    data.details.forEach(detail => {
                                        itemsTable.push({
                                            produk_id: detail.produk_id,
                                            deskripsi: detail.deskripsi || '',
                                            quantity: detail.quantity,
                                            satuan_id: detail.satuan_id,
                                            harga: detail.harga_estimasi || 0,
                                            diskon: 0,
                                            subtotal: (detail.quantity * (detail.harga_estimasi || 0))
                                        });
                                    });

                                    // Update totals
                                    const summary = Alpine.evaluate(document.querySelector(
                                        '[x-data="summaryCalculator()"]'), 'calculateTotal');
                                    summary();
                                }
                            })
                            .catch(error => {
                                console.error('Error loading purchase request details:', error);
                            });
                    }
                }
            }

            function itemsTable() {
                return {
                    items: @json(
                        $purchaseOrder->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'produk_id' => $item->produk_id,
                                'deskripsi' => $item->deskripsi,
                                'quantity' => $item->quantity,
                                'satuan_id' => $item->satuan_id,
                                'harga' => $item->harga,
                                'diskon' => $item->diskon,
                                'subtotal' => $item->subtotal,
                                'locked' => $purchaseOrder->status !== 'draft',
                            ];
                        })),

                    addRow() {
                        this.items.push({
                            id: null,
                            produk_id: '',
                            deskripsi: '',
                            quantity: 1,
                            satuan_id: '',
                            harga: 0,
                            diskon: 0,
                            subtotal: 0,
                            locked: false
                        });
                    },

                    removeRow(index) {
                        this.items.splice(index, 1);
                        this.$nextTick(() => {
                            const summary = Alpine.evaluate(document.querySelector('[x-data="summaryCalculator()"]'),
                                'calculateTotal');
                            summary();
                        });
                    },

                    calculateSubtotal(index) {
                        const item = this.items[index];
                        const price = parseFloat(item.harga) || 0;
                        const qty = parseFloat(item.quantity) || 0;
                        const discount = parseFloat(item.diskon) || 0;

                        item.subtotal = (price * qty) - discount;

                        this.$nextTick(() => {
                            const summary = Alpine.evaluate(document.querySelector('[x-data="summaryCalculator()"]'),
                                'calculateTotal');
                            summary();
                        });
                    },

                    updateProductInfo(index) {
                        const produkId = this.items[index].produk_id;
                        if (!produkId) return;

                        // AJAX call to get product details
                        fetch(`/master/produk/${produkId}/info`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.items[index].harga = data.produk.harga_beli || 0;
                                    this.items[index].satuan_id = data.produk.satuan_id || '';
                                    this.calculateSubtotal(index);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching product info:', error);
                            });
                    }
                };
            }

            function summaryCalculator() {
                return {
                    subtotal: {{ $purchaseOrder->subtotal }},
                    diskonPersen: {{ $purchaseOrder->diskon_persen }},
                    diskonNominal: {{ $purchaseOrder->diskon_nominal }},
                    ppn: {{ $purchaseOrder->ppn }},
                    total: {{ $purchaseOrder->total }},

                    calculateTotal() {
                        // Get all items from the items table
                        const itemsTable = Alpine.evaluate(document.querySelector('[x-data="itemsTable()"]'), 'items');

                        // Calculate subtotal from all items
                        this.subtotal = itemsTable.reduce((sum, item) => sum + (parseFloat(item.subtotal) || 0), 0);

                        // Calculate discounts
                        const discountPercentValue = (parseFloat(this.diskonPersen) || 0) * this.subtotal / 100;
                        const discountNominalValue = parseFloat(this.diskonNominal) || 0;

                        // Calculate PPN
                        const totalAfterDiscount = this.subtotal - discountPercentValue - discountNominalValue;
                        const ppnValue = (parseFloat(this.ppn) || 0) * totalAfterDiscount / 100;

                        // Calculate final total
                        this.total = totalAfterDiscount + ppnValue;
                    },

                    togglePPN() {
                        this.ppn = this.ppn === 11 ? 0 : 11;
                        this.calculateTotal();
                    },

                    init() {
                        this.calculateTotal();

                        // Watch for changes in items and recalculate total
                        this.$watch('subtotal', () => this.calculateTotal());
                        this.$watch('diskonPersen', () => this.calculateTotal());
                        this.$watch('diskonNominal', () => this.calculateTotal());
                        this.$watch('ppn', () => this.calculateTotal());
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>

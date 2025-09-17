<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Select dropdown styling */
        .dropdown-search {
            position: relative;
        }

        .dropdown-search .dropdown-content {
            position: absolute;
            z-index: 9999;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
        }

        /* Alpine.js Dropdown Styling with Dark Mode Support */
        .dropdown-search input[type="text"] {
            @apply w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm;
            height: 38px;
            padding-top: 4px;
        }

        .dropdown-search .dropdown-toggle {
            height: 36px;
        }

        .dropdown-search input[type="text"]::placeholder {
            @apply text-gray-700 dark:text-white;
            line-height: 30px;
        }

        .dropdown-search .dropdown-content {
            @apply bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-lg rounded-md;
        }

        .dropdown-search .search-input {
            @apply border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white;
        }

        .dropdown-search li button {
            @apply px-3 py-2 text-gray-700 dark:text-gray-300;
        }

        .dropdown-search li button:hover {
            @apply bg-primary-500 dark:bg-primary-600 text-white;
        }

        .dropdown-search li button:focus {
            @apply bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300;
        }

        .dropdown-search input[type="text"]:focus {
            @apply border-primary-500 dark:border-primary-500 dark:ring-primary-500 dark:shadow-none;
        }
    </style>
    <!-- Alpine.js dropdown styles -->
    <style>
        /* Dropdown styling */
        .dropdown-content {
            z-index: 9999 !important;
            border-radius: 0.375rem !important;
        }

        .dropdown-search ul li button {
            padding: 8px 12px !important;
        }

        .dark .dropdown-content {
            background-color: #374151 !important;
            color: #fff !important;
            border-color: #4B5563 !important;
        }

        .dark .dropdown-search input[type="text"] {
            background-color: #1F2937 !important;
            color: #fff !important;
            border-color: #4B5563 !important;
        }

        .dark .dropdown-search li {
            color: #D1D5DB !important;
        }

        .dark .dropdown-search li button:hover {
            background-color: rgba(59, 130, 246, 0.5) !important;
            color: #fff !important;
        }

        .dark .dropdown-search li button:focus {
            background-color: #3B82F6 !important;
            color: #fff !important;
        }

        /* Fix the dropdown appearance in all modes */
        .dropdown-search input[type="text"] {
            height: 38px !important;
            padding-top: 4px !important;
        }

        /* Fix for dropdown container visibility */
        .dropdown-search.open .dropdown-content {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="bomEditForm()">
        {{-- Header section --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <span class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Edit Bill of Material
                            </span>
                        </h1>
                        <div class="hidden md:block">
                            <a href="{{ route('produksi.bom.show', $bom->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Edit informasi Bill of Material dan tambahkan atau ubah komponen yang diperlukan.
                    </p>
                </div>
                <div class="mt-5 md:hidden">
                    <span class="block">
                        <a href="{{ route('produksi.bom.show', $bom->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </span>
                </div>
            </div>
        </div>

        {{-- BOM Information Form --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- BOM Details Form --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi BOM</h2>

                    <form id="bomForm" @submit.prevent="updateBOM">
                        <div class="space-y-4">
                            {{-- Kode BOM --}}
                            <div>
                                <label for="kode"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kode BOM <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kode" id="kode" x-model="formData.kode"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <div x-show="errors.kode" class="text-red-500 text-sm mt-1" x-text="errors.kode"></div>
                            </div>

                            {{-- Nama BOM --}}
                            <div>
                                <label for="nama"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nama BOM <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" id="nama" x-model="formData.nama"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <div x-show="errors.nama" class="text-red-500 text-sm mt-1" x-text="errors.nama"></div>
                            </div>

                            {{-- Versi --}}
                            <div>
                                <label for="versi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Versi
                                </label>
                                <input type="text" name="versi" id="versi" x-model="formData.versi"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <div x-show="errors.versi" class="text-red-500 text-sm mt-1" x-text="errors.versi">
                                </div>
                            </div>

                            {{-- Produk Jadi (Searchable Dropdown) --}}
                            <div x-data="dropdownSearch('produk_id',
                                {{ Js::from(
                                    $produks->map(function ($produk) {
                                        return [
                                            'value' => strval($produk->id),
                                            'label' => $produk->nama . ' (' . $produk->kode . ')',
                                            'kode' => $produk->kode,
                                            'nama' => $produk->nama,
                                        ];
                                    }),
                                ) }}, { 'value': '{{ $bom->produk_id }}', 'label': '{{ $bom->produk ? $bom->produk->nama . ' (' . $bom->produk->kode . ')' : '' }}' })" @click.away="open = false" class="relative">
                                <label for="produk_search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Produk Jadi <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="produk_id" :value="selectedOption.value"
                                    x-model="formData.produk_id">
                                <div class="relative">
                                    <input type="text" id="produk_search"
                                        :placeholder="!selectedOption.value ? '-- Pilih Produk --' : ''"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                        @click="open = true" :value="displayValue()"
                                        :readonly="selectedOption.value !== ''" autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="open" x-transition
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                    style="display: none;">
                                    <div class="p-2">
                                        <input type="text"
                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                            placeholder="Cari produk..." x-model="search" @click.stop>
                                    </div>
                                    <ul>
                                        <template x-for="option in filteredOptions()" :key="option.value">
                                            <li>
                                                <button type="button"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                    @click="select(option)" x-text="option.label">
                                                </button>
                                            </li>
                                        </template>
                                        <li x-show="filteredOptions().length === 0"
                                            class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada data
                                        </li>
                                    </ul>
                                </div>

                                <div x-show="selectedOption.value" class="mt-1.5 flex items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                                    <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                                        x-text="selectedOption.label"></span>
                                </div>

                                <div x-show="errors.produk_id" class="text-red-500 text-sm mt-1"
                                    x-text="errors.produk_id"></div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox"
                                        x-model="formData.is_active"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                    <label for="is_active"
                                        class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Aktif
                                    </label>
                                </div>
                                <div x-show="errors.is_active" class="text-red-500 text-sm mt-1"
                                    x-text="errors.is_active"></div>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label for="deskripsi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Deskripsi
                                </label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" x-model="formData.deskripsi"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                                <div x-show="errors.deskripsi" class="text-red-500 text-sm mt-1"
                                    x-text="errors.deskripsi"></div>
                            </div>

                            {{-- Biaya Overhead --}}
                            <div>
                                <label for="overhead_cost"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Biaya Overhead
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="overhead_cost" id="overhead_cost"
                                        x-model="formData.overhead_cost" step="0.01" min="0"
                                        class="w-full pl-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        placeholder="0.00">
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Biaya overhead yang akan ditambahkan ke total biaya produk (opsional)
                                </p>
                                <div x-show="errors.overhead_cost" class="text-red-500 text-sm mt-1"
                                    x-text="errors.overhead_cost"></div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" :disabled="isSubmitting"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <span x-show="isSubmitting" class="inline-block mr-2">
                                        <svg class="animate-spin h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- BOM Components Section --}}
            <div class="lg:col-span-2 space-y-6" id="components">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h6a2 2 0 012 2v2M7 15h10" />
                            </svg>
                            Komponen BOM
                        </h2>
                        <button @click="openAddComponentModal" type="button"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Komponen
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kode Komponen
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Komponen
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-if="components.length === 0">
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada komponen BOM yang ditambahkan.
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="(component, index) in components" :key="index">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                            x-text="index + 1"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                            x-text="component.komponen_kode"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                            x-text="component.komponen_nama"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                            x-text="component.quantity"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                            x-text="component.satuan_nama"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                            x-text="component.catatan || '-'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <button @click="editComponent(index)"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </button>
                                                <button @click="deleteComponent(index)"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Component Modal --}}
        <div x-show="showComponentModal" x-cloak class="fixed inset-0 overflow-y-auto z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                    x-show="showComponentModal" @click="showComponentModal = false"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    @click.away="showComponentModal = false">
                    <form @submit.prevent="saveComponent">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    <span x-text="isEditing ? 'Edit Komponen' : 'Tambah Komponen'"></span>
                                </h3>
                            </div>
                            <div class="space-y-4">
                                {{-- Komponen (Searchable Dropdown) --}}
                                <div x-data="dropdownSearch('komponen_id',
                                    {{ Js::from(
                                        $komponens->map(function ($komponen) {
                                            return [
                                                'value' => strval($komponen->id),
                                                'label' => $komponen->nama . ' (' . $komponen->kode . ')',
                                                'kode' => $komponen->kode,
                                                'nama' => $komponen->nama,
                                            ];
                                        }),
                                    ) }}, { 'value': '', 'label': '' })" @click.away="open = false" class="relative">
                                    <label for="komponen_search"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Komponen <span class="text-red-500">*</span>
                                    </label>
                                    <input type="hidden" name="komponen_id" :value="selectedOption.value"
                                        x-model="componentForm.komponen_id"
                                        x-effect="if(selectedOption.value) getComponentUnitInfo()">
                                    <div class="relative">
                                        <input type="text" id="komponen_search"
                                            :placeholder="!selectedOption.value ? '-- Pilih Komponen --' : ''"
                                            class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                            @click="open = true" :value="displayValue()"
                                            :readonly="selectedOption.value !== ''" autocomplete="off">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div x-show="open" x-transition
                                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                        style="display: none;">
                                        <div class="p-2">
                                            <input type="text"
                                                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                                placeholder="Cari komponen..." x-model="search" @click.stop>
                                        </div>
                                        <ul>
                                            <template x-for="option in filteredOptions()" :key="option.value">
                                                <li>
                                                    <button type="button"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                        @click="select(option)" x-text="option.label">
                                                    </button>
                                                </li>
                                            </template>
                                            <li x-show="filteredOptions().length === 0"
                                                class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada
                                                data
                                            </li>
                                        </ul>
                                    </div>

                                    <div x-show="selectedOption.value" class="mt-1.5 flex items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                                        <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                                            x-text="selectedOption.label"></span>
                                    </div>

                                    <div x-show="componentErrors.komponen_id" class="text-red-500 text-sm mt-1"
                                        x-text="componentErrors.komponen_id"></div>
                                </div>

                                {{-- Quantity --}}
                                <div>
                                    <label for="quantity"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Jumlah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="0.01" name="quantity" id="quantity"
                                        x-model="componentForm.quantity"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required min="0.01">
                                    <div x-show="componentErrors.quantity" class="text-red-500 text-sm mt-1"
                                        x-text="componentErrors.quantity"></div>
                                </div>

                                {{-- Satuan --}}
                                <div x-data="dropdownSearch('satuan_id',
                                    {{ Js::from([
                                        ['value' => '1', 'label' => 'PCS'],
                                        ['value' => '2', 'label' => 'KG'],
                                        ['value' => '3', 'label' => 'LTR'],
                                        ['value' => '4', 'label' => 'BOX'],
                                        ['value' => '5', 'label' => 'ROLL'],
                                        ['value' => '6', 'label' => 'UNIT'],
                                    ]) }}, { 'value': componentForm.satuan_id, 'label': '' })" @click.away="open = false" class="relative">
                                    <label for="satuan_search"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Satuan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="hidden" name="satuan_id" :value="selectedOption.value"
                                        x-model="componentForm.satuan_id">
                                    <div class="relative">
                                        <input type="text" id="satuan_search"
                                            :placeholder="!selectedOption.value ? '-- Pilih Satuan --' : ''"
                                            class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                            @click="open = true" :value="displayValue()"
                                            :readonly="selectedOption.value !== ''" autocomplete="off">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div x-show="open" x-transition
                                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                        style="display: none;">
                                        <div class="p-2">
                                            <input type="text"
                                                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                                placeholder="Cari satuan..." x-model="search" @click.stop>
                                        </div>
                                        <ul>
                                            <template x-for="option in filteredOptions()" :key="option.value">
                                                <li>
                                                    <button type="button"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                        @click="select(option)" x-text="option.label">
                                                    </button>
                                                </li>
                                            </template>
                                            <li x-show="filteredOptions().length === 0"
                                                class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada
                                                data
                                            </li>
                                        </ul>
                                    </div>

                                    <div x-show="selectedOption.value" class="mt-1.5 flex items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                                        <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                                            x-text="selectedOption.label"></span>
                                    </div>

                                    <div x-show="componentErrors.satuan_id" class="text-red-500 text-sm mt-1"
                                        x-text="componentErrors.satuan_id"></div>
                                </div>

                                {{-- Catatan --}}
                                <div>
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Catatan
                                    </label>
                                    <textarea name="catatan" id="catatan" rows="2" x-model="componentForm.catatan"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                                    <div x-show="componentErrors.catatan" class="text-red-500 text-sm mt-1"
                                        x-text="componentErrors.catatan"></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="showComponentModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 overflow-y-auto z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                    x-show="showDeleteModal" @click="showDeleteModal = false"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    @click.away="showDeleteModal = false">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Hapus Komponen
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Apakah Anda yakin ingin menghapus komponen ini? Tindakan ini tidak dapat
                                        dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="confirmDeleteComponent()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                        <button type="button" @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function bomEditForm() {
                return {
                    // Existing BOM data
                    bomId: {{ $bom->id }},

                    // Form for BOM details
                    formData: {
                        kode: '{{ $bom->kode }}',
                        nama: '{{ $bom->nama }}',
                        produk_id: '{{ $bom->produk_id }}',
                        versi: '{{ $bom->versi }}',
                        deskripsi: '{{ $bom->deskripsi }}',
                        is_active: {{ $bom->is_active ? 'true' : 'false' }},
                        overhead_cost: {{ $bom->overhead_cost ?? 0 }}
                    },
                    errors: {},
                    isSubmitting: false,

                    // Helper function for notifications
                    showNotification(type, title, message, timeout = 5000) {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: type,
                                title: title,
                                message: message,
                                timeout: timeout
                            }
                        }));
                    },

                    // Components array
                    components: {!! json_encode(
                        $bom->details->map(function ($detail) {
                            return [
                                'id' => $detail->id,
                                'komponen_id' => $detail->komponen_id,
                                'komponen_kode' => $detail->komponen->kode ?? '',
                                'komponen_nama' => $detail->komponen->nama ?? '',
                                'quantity' => $detail->quantity,
                                'satuan_id' => $detail->satuan_id,
                                'satuan_nama' => $detail->satuan->nama ?? '',
                                'catatan' => $detail->catatan,
                            ];
                        }),
                    ) !!},

                    // Component form
                    showComponentModal: false,
                    componentForm: {
                        id: null,
                        komponen_id: '',
                        quantity: '',
                        satuan_id: '',
                        catatan: ''
                    },
                    componentErrors: {},
                    isEditing: false,
                    editIndex: null,

                    // Delete modal
                    showDeleteModal: false,
                    deleteIndex: null,

                    // Select2 instances
                    productSelect: null,
                    componentSelect: null,
                    satuanSelect: null,

                    init() {
                        // Initialize Alpine.js handlers for our custom dropdowns
                        this.$nextTick(() => {
                            // Initialize component select when modal opens
                            this.$watch('showComponentModal', (isOpen) => {
                                if (isOpen) {
                                    // Initialize after modal is fully visible
                                    setTimeout(() => {
                                        this.initializeComponentSelects();
                                    }, 300);
                                }
                            });

                            // Listen for option-selected events
                            document.addEventListener('option-selected', (event) => {
                                if (!event.detail) return;

                                const {
                                    fieldName,
                                    value
                                } = event.detail;

                                console.log(`Option selected: ${fieldName} = ${value}`);

                                if (fieldName === 'komponen_id') {
                                    this.componentForm.komponen_id = value;
                                    // This will trigger the effect directive to call getComponentUnitInfo
                                } else if (fieldName === 'satuan_id') {
                                    this.componentForm.satuan_id = value;
                                } else if (fieldName === 'produk_id') {
                                    this.formData.produk_id = value;
                                }

                                // Hide all "Terpilih:" labels whenever any option is selected
                                setTimeout(() => {
                                    document.querySelectorAll('[x-show="selectedOption.value"]')
                                        .forEach(el => {
                                            el.style.display = 'none';
                                        });

                                    // Update search input values
                                    if (typeof window.updateDropdowns === 'function') {
                                        window.updateDropdowns();
                                    }
                                }, 100);
                            });
                        });

                        // Initialize the function to hide "Terpilih:" labels
                        window.updateDropdowns = function() {
                            const terpilihLabels = document.querySelectorAll('.mt-1\\.5.flex.items-center');
                            terpilihLabels.forEach(function(label) {
                                if (label.textContent.includes('Terpilih:')) {
                                    label.style.display = 'none';

                                    const container = label.closest('[x-data^="dropdownSearch"]');
                                    if (container) {
                                        const selectedText = label.querySelector('span:last-child')?.innerText;
                                        const input = container.querySelector('input[id$="_search"]');
                                        if (input && selectedText?.trim()) {
                                            input.value = selectedText;
                                        }
                                    }
                                }
                            });
                        };

                        // Call once initially
                        setTimeout(window.updateDropdowns, 100);
                    },

                    // Helper method to initialize the component and unit selects with Alpine.js
                    initializeComponentSelects() {
                        console.log('Initializing component and unit selects');

                        const komponenOptions =
                            {{ Js::from(
                                $komponens->map(function ($komponen) {
                                    return [
                                        'value' => strval($komponen->id),
                                        'label' => $komponen->nama . ' (' . $komponen->kode . ')',
                                        'kode' => $komponen->kode,
                                        'nama' => $komponen->nama,
                                    ];
                                }),
                            ) }};

                        const satuanOptions =
                            {{ Js::from(
                                $satuans->map(function ($satuan) {
                                    return [
                                        'value' => (string) $satuan->id,
                                        'label' => $satuan->nama,
                                    ];
                                }),
                            ) }};

                        // Update komponen dropdown if we have a value
                        if (this.componentForm.komponen_id) {
                            const selectedKomponen = komponenOptions.find(
                                option => option.value === String(this.componentForm.komponen_id)
                            );

                            if (selectedKomponen) {
                                // Update the input field directly
                                const komponenSearch = document.getElementById('komponen_search');
                                if (komponenSearch) {
                                    komponenSearch.value = selectedKomponen.label;
                                }

                                // Dispatch event to tell other components
                                this.$dispatch('option-selected', {
                                    fieldName: 'komponen_id',
                                    value: selectedKomponen.value
                                });
                            }
                        }

                        // Update satuan dropdown if we have a value
                        if (this.componentForm.satuan_id) {
                            const selectedSatuan = satuanOptions.find(
                                option => option.value === String(this.componentForm.satuan_id)
                            );

                            if (selectedSatuan) {
                                // Update the input field directly
                                const satuanSearch = document.getElementById('satuan_search');
                                if (satuanSearch) {
                                    satuanSearch.value = selectedSatuan.label;
                                }

                                // Dispatch event to tell other components
                                this.$dispatch('option-selected', {
                                    fieldName: 'satuan_id',
                                    value: selectedSatuan.value
                                });
                            }
                        }

                        console.log('Dropdowns initialized with Alpine.js');
                    },

                    // Methods for BOM form
                    updateBOM() {
                        this.isSubmitting = true;
                        this.errors = {};

                        // Prepare form data with proper boolean conversion
                        const submitData = {
                            ...this.formData,
                            is_active: this.formData.is_active ? 1 : 0
                        };

                        console.log('Submitting data:', submitData);

                        fetch(`{{ route('produksi.bom.update', $bom->id) }}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(submitData)
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                // Check if response is ok
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Response data:', data);
                                if (data.success) {
                                    // Show success notification using the correct event
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: {
                                            type: 'success',
                                            title: 'Berhasil',
                                            message: data.message || 'BOM berhasil diperbarui',
                                            timeout: 3000
                                        }
                                    }));

                                    // Redirect to show page after a short delay
                                    setTimeout(() => {
                                        window.location.href = `{{ route('produksi.bom.show', $bom->id) }}`;
                                    }, 1000);
                                } else {
                                    // Handle validation errors
                                    if (data.errors) {
                                        this.errors = data.errors;
                                        // Show validation error notification
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                type: 'error',
                                                title: 'Validation Error',
                                                message: 'Silakan periksa form dan perbaiki kesalahan yang ada.',
                                                timeout: 5000
                                            }
                                        }));
                                    } else if (data.message) {
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                type: 'error',
                                                title: 'Error',
                                                message: data.message,
                                                timeout: 5000
                                            }
                                        }));
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: {
                                        type: 'error',
                                        title: 'Error',
                                        message: 'Terjadi kesalahan saat menyimpan data: ' + error.message,
                                        timeout: 5000
                                    }
                                }));
                            })
                            .finally(() => {
                                this.isSubmitting = false;
                            });
                    },

                    // Get unit information for component
                    getComponentUnitInfo() {
                        const componentId = this.componentForm.komponen_id;
                        if (!componentId) return;

                        console.log('Fetching unit info for component:', componentId);

                        fetch(`{{ url('produksi/bom-component-unit') }}/${componentId}`)
                            .then(response => response.json())
                            .then(data => {
                                console.log('Unit info received:', data);

                                if (data.success) {
                                    // Auto set the unit based on component
                                    this.componentForm.satuan_id = data.data.satuan_id;
                                    console.log('Set satuan_id to:', data.data.satuan_id);

                                    // Update the satuan dropdown - find the option label first
                                    const satuanOptions =
                                        {{ Js::from(
                                            $satuans->map(function ($satuan) {
                                                return [
                                                    'value' => (string) $satuan->id,
                                                    'label' => $satuan->nama,
                                                ];
                                            }),
                                        ) }};

                                    const selectedSatuan = satuanOptions.find(
                                        option => option.value === String(data.data.satuan_id)
                                    );

                                    if (selectedSatuan) {
                                        // Dispatch a custom event to update the satuan dropdown
                                        this.$dispatch('option-selected', {
                                            fieldName: 'satuan_id',
                                            value: selectedSatuan.value
                                        });

                                        // Update the input field directly
                                        const satuanSearch = document.getElementById('satuan_search');
                                        if (satuanSearch) {
                                            satuanSearch.value = selectedSatuan.label;
                                        }
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching component unit:', error);
                            });
                    },

                    // Methods for component management
                    openAddComponentModal() {
                        this.isEditing = false;
                        this.componentForm = {
                            id: null,
                            komponen_id: '',
                            quantity: '',
                            satuan_id: '',
                            catatan: ''
                        };
                        this.componentErrors = {};
                        this.showComponentModal = true;
                    },

                    editComponent(index) {
                        this.isEditing = true;
                        this.editIndex = index;
                        const component = this.components[index];

                        this.componentForm = {
                            id: component.id,
                            komponen_id: component.komponen_id,
                            quantity: component.quantity,
                            satuan_id: component.satuan_id,
                            catatan: component.catatan
                        };

                        this.componentErrors = {};
                        this.showComponentModal = true;

                        // Need to update the dropdown values after modal is open
                        this.$nextTick(() => {
                            // Use a longer timeout to make sure the modal is fully open
                            setTimeout(() => {
                                console.log('Initializing component selects in edit mode');

                                // Find selected component data
                                const komponenOptions =
                                    {{ Js::from(
                                        $komponens->map(function ($komponen) {
                                            return [
                                                'value' => strval($komponen->id),
                                                'label' => $komponen->nama . ' (' . $komponen->kode . ')',
                                                'kode' => $komponen->kode,
                                                'nama' => $komponen->nama,
                                            ];
                                        }),
                                    ) }};

                                const satuanOptions =
                                    {{ Js::from(
                                        $satuans->map(function ($satuan) {
                                            return [
                                                'value' => (string) $satuan->id,
                                                'label' => $satuan->nama,
                                            ];
                                        }),
                                    ) }};

                                const selectedKomponen = komponenOptions.find(
                                    opt => opt.value === String(component.komponen_id)
                                );

                                const selectedSatuan = satuanOptions.find(
                                    opt => opt.value === String(component.satuan_id)
                                );

                                // Update component input field
                                if (selectedKomponen) {
                                    const komponenSearch = document.getElementById('komponen_search');
                                    if (komponenSearch) {
                                        komponenSearch.value = selectedKomponen.label;
                                    }
                                }

                                // Update satuan input field
                                if (selectedSatuan) {
                                    const satuanSearch = document.getElementById('satuan_search');
                                    if (satuanSearch) {
                                        satuanSearch.value = selectedSatuan.label;
                                    }
                                }

                                // Make sure any "Terpilih:" labels are hidden
                                if (typeof window.updateDropdowns === 'function') {
                                    window.updateDropdowns();
                                }
                            }, 300);
                        });
                    },

                    saveComponent() {
                        // Validate form
                        this.componentErrors = {};
                        let hasErrors = false;

                        if (!this.componentForm.komponen_id) {
                            this.componentErrors.komponen_id = 'Komponen harus dipilih';
                            hasErrors = true;
                        }

                        if (!this.componentForm.quantity || this.componentForm.quantity <= 0) {
                            this.componentErrors.quantity = 'Jumlah harus lebih dari 0';
                            hasErrors = true;
                        }

                        if (!this.componentForm.satuan_id) {
                            this.componentErrors.satuan_id = 'Satuan harus dipilih';
                            hasErrors = true;
                        }

                        if (hasErrors) {
                            return;
                        }

                        // Get the selected component details from the options
                        const komponenOptions =
                            {{ Js::from(
                                $komponens->map(function ($komponen) {
                                    return [
                                        'value' => strval($komponen->id),
                                        'label' => $komponen->nama . ' (' . $komponen->kode . ')',
                                        'kode' => $komponen->kode,
                                        'nama' => $komponen->nama,
                                    ];
                                }),
                            ) }};

                        const satuanOptions =
                            {{ Js::from(
                                $satuans->map(function ($satuan) {
                                    return [
                                        'value' => (string) $satuan->id,
                                        'label' => $satuan->nama,
                                    ];
                                }),
                            ) }};

                        const selectedKomponen = komponenOptions.find(
                            option => option.value === String(this.componentForm.komponen_id)
                        );

                        const selectedSatuan = satuanOptions.find(
                            option => option.value === String(this.componentForm.satuan_id)
                        );

                        if (!selectedKomponen || !selectedSatuan) {
                            console.error('Selected component or unit not found');
                            return;
                        }

                        // Prepare component object
                        const component = {
                            id: this.componentForm.id,
                            komponen_id: this.componentForm.komponen_id,
                            komponen_kode: selectedKomponen.kode,
                            komponen_nama: selectedKomponen.nama,
                            quantity: this.componentForm.quantity,
                            satuan_id: this.componentForm.satuan_id,
                            satuan_nama: selectedSatuan.label,
                            catatan: this.componentForm.catatan
                        };

                        // Add/Update component
                        if (this.isEditing) {
                            this.components[this.editIndex] = component;
                        } else {
                            this.components.push(component);
                        }

                        // Save to server if it's an existing component
                        if (component.id) {
                            this.updateComponentOnServer(component);
                        } else if (this.bomId) {
                            this.addComponentToServer(component);
                        }

                        this.showComponentModal = false;
                    },

                    deleteComponent(index) {
                        this.deleteIndex = index;
                        this.showDeleteModal = true;
                    },

                    confirmDeleteComponent() {
                        const component = this.components[this.deleteIndex];

                        // If it's an existing component, delete from server
                        if (component.id) {
                            fetch(`{{ url('produksi/bom-component') }}/${component.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.components.splice(this.deleteIndex, 1);
                                        this.showNotification('success', 'Berhasil', 'Komponen berhasil dihapus', 3000);
                                    } else {
                                        this.showNotification('error', 'Error', data.message || 'Gagal menghapus komponen');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    this.showNotification('error', 'Error', 'Terjadi kesalahan saat menghapus komponen');
                                });
                        } else {
                            // Just remove from local array if not saved to server yet
                            this.components.splice(this.deleteIndex, 1);
                        }

                        this.showDeleteModal = false;
                    },

                    updateComponentOnServer(component) {
                        fetch(`{{ url('produksi/bom-component') }}/${component.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    komponen_id: component.komponen_id,
                                    quantity: component.quantity,
                                    satuan_id: component.satuan_id,
                                    catatan: component.catatan
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.showNotification('success', 'Berhasil', 'Komponen berhasil diperbarui', 3000);
                                } else {
                                    this.showNotification('error', 'Error', data.message || 'Gagal memperbarui komponen');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                this.showNotification('error', 'Error', 'Terjadi kesalahan saat memperbarui komponen');
                            });
                    },

                    addComponentToServer(component) {
                        fetch(`{{ url('produksi/bom') }}/${this.bomId}/components`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    komponen_id: component.komponen_id,
                                    quantity: component.quantity,
                                    satuan_id: component.satuan_id,
                                    catatan: component.catatan
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the component with the ID from server
                                    const index = this.components.findIndex(c =>
                                        c.komponen_id === component.komponen_id &&
                                        c.quantity === component.quantity &&
                                        !c.id
                                    );

                                    if (index !== -1) {
                                        this.components[index].id = data.data.id;
                                    }

                                    this.showNotification('success', 'Berhasil', 'Komponen berhasil ditambahkan', 3000);
                                } else {
                                    this.showNotification('error', 'Error', data.message || 'Gagal menambahkan komponen');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                this.showNotification('error', 'Error', 'Terjadi kesalahan saat menambahkan komponen');
                            });
                    }
                }
            }

            // Hide all "Terpilih:" labels and update input fields when the page loads
            document.addEventListener('DOMContentLoaded', function() {
                // Function to handle dropdowns when Alpine initializes
                window.updateDropdowns = function() {
                    // Find all elements with class that matches the Terpilih labels
                    const terpilihLabels = document.querySelectorAll('.mt-1\\.5.flex.items-center');
                    terpilihLabels.forEach(function(label) {
                        // Check if it contains "Terpilih:" text
                        if (label.textContent.includes('Terpilih:')) {
                            label.style.display = 'none';

                            // Find the dropdown container
                            const container = label.closest('[x-data^="dropdownSearch"]');
                            if (container) {
                                // Get the selected value from the label
                                const selectedText = label.querySelector('span:last-child').innerText;

                                // Find the input field and set its value
                                const input = container.querySelector('input[id$="_search"]');
                                if (input && selectedText.trim()) {
                                    input.value = selectedText;
                                }
                            }
                        }
                    });
                };

                // Call once immediately and again after a slight delay to ensure Alpine has initialized
                setTimeout(window.updateDropdowns, 100);
            });

            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdownSearch', function(fieldName, options, selected) {
                    return {
                        search: '',
                        open: false,
                        options: options || [],
                        selectedOption: selected || {
                            value: '',
                            label: ''
                        },

                        filteredOptions() {
                            if (!this.search) return this.options;
                            return this.options.filter(option =>
                                option.label.toLowerCase().includes(this.search.toLowerCase())
                            );
                        },

                        select(option) {
                            this.selectedOption = option;
                            this.open = false;
                            this.search = '';

                            // Hide the "Terpilih:" label for this dropdown and update input
                            const selectedDisplay = this.$el.querySelector('[x-show="selectedOption.value"]');
                            if (selectedDisplay) {
                                selectedDisplay.style.display = 'none';
                            }

                            // Update the input to show the selected value
                            const input = this.$el.querySelector('input[id$="_search"]');
                            if (input) {
                                input.value = option.label;
                            }

                            // Call our global update function if it exists
                            if (typeof window.updateDropdowns === 'function') {
                                setTimeout(window.updateDropdowns, 10);
                            }

                            this.$dispatch('option-selected', {
                                fieldName: fieldName,
                                value: option.value
                            });
                        },

                        init() {
                            // If we have a selected value but no full object, find it in options
                            if (typeof this.selectedOption === 'string' && this.selectedOption !== '') {
                                const foundOption = this.options.find(opt => opt.value === this.selectedOption);
                                if (foundOption) {
                                    this.selectedOption = foundOption;
                                }
                            }

                            // If selectedOption is a primitive value, convert it to object
                            if (this.selectedOption && typeof this.selectedOption.value === 'undefined') {
                                this.selectedOption = {
                                    value: this.selectedOption,
                                    label: ''
                                };
                            }
                        },

                        // Display selected value in the input field
                        displayValue() {
                            return this.selectedOption && this.selectedOption.value ? this.selectedOption
                                .label : '';
                        },

                        // Hide the "Terpilih:" label
                        hideSelectedLabel: true
                    };
                });
            });
        </script>
    @endpush
</x-app-layout>

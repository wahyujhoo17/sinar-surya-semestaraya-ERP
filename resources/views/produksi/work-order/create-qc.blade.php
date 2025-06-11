<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Detail', 'url' => route('produksi.work-order.show', $workOrder->id)],
    ['label' => 'Buat Quality Control'],
]" :currentPage="'Buat Quality Control'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Buat Quality Control
                </h1>
                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Informasi WO --}}
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Work Order</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Work Order</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $workOrder->nomor }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->produk->nama ?? 'Produk tidak ditemukan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->quantity }} {{ $workOrder->satuan->nama ?? '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai Produksi
                            </dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->tanggal_selesai ? date('d/m/Y', strtotime($workOrder->tanggal_selesai)) : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Form QC --}}
            <div class="lg:col-span-2">
                <form action="{{ route('produksi.work-order.store-qc', $workOrder->id) }}" method="POST"
                    id="form-qc">
                    @csrf
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Form Quality Control</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="tanggal_inspeksi"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal Inspeksi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" name="tanggal_inspeksi" id="tanggal_inspeksi"
                                            value="{{ old('tanggal_inspeksi', date('d/m/Y')) }}"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md datepicker"
                                            placeholder="Pilih tanggal inspeksi">
                                    </div>
                                    @error('tanggal_inspeksi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="jumlah_lolos"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Jumlah Lolos <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" min="0" max="{{ $workOrder->quantity }}"
                                            step="0.01" name="jumlah_lolos" id="jumlah_lolos"
                                            value="{{ old('jumlah_lolos', $workOrder->quantity) }}"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                            onchange="updateJumlahGagal()">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span
                                                class="text-gray-500 sm:text-sm">{{ $workOrder->satuan->nama ?? '' }}</span>
                                        </div>
                                    </div>
                                    @error('jumlah_lolos')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jumlah_gagal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Jumlah Gagal <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" min="0" max="{{ $workOrder->quantity }}"
                                            step="0.01" name="jumlah_gagal" id="jumlah_gagal"
                                            value="{{ old('jumlah_gagal', 0) }}"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                            onchange="updateJumlahLolos()">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span
                                                class="text-gray-500 sm:text-sm">{{ $workOrder->satuan->nama ?? '' }}</span>
                                        </div>
                                    </div>
                                    @error('jumlah_gagal')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Total: <span id="total_quantity">{{ $workOrder->quantity }}</span>
                                    {{ $workOrder->satuan->nama ?? '' }}
                                </p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-green-600 h-2.5 rounded-full" id="progress-lolos"
                                        style="width: 100%"></div>
                                </div>
                                <div class="flex justify-between text-xs mt-1">
                                    <span class="text-green-600 dark:text-green-400">Lolos: <span
                                            id="persen-lolos">100</span>%</span>
                                    <span class="text-red-600 dark:text-red-400">Gagal: <span
                                            id="persen-gagal">0</span>%</span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" id="catatan" rows="3"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Kriteria Pengujian</h2>
                            <button type="button" id="add-kriteria"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                        <div class="p-6">
                            <div id="kriteria-container">
                                <div class="kriteria-item mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Nama Kriteria <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="kriteria[0][nama]" required
                                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                placeholder="Masukkan nama kriteria">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Hasil <span class="text-red-500">*</span>
                                            </label>
                                            <select name="kriteria[0][hasil]" required
                                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                <option value="lolos">Lolos</option>
                                                <option value="gagal">Gagal</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Keterangan
                                            </label>
                                            <div class="flex">
                                                <input type="text" name="kriteria[0][keterangan]"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md rounded-r-none"
                                                    placeholder="Masukkan keterangan (opsional)">
                                                <button type="button"
                                                    class="first-remove-kriteria px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-r-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="empty-kriteria" class="text-center text-gray-500 dark:text-gray-400 py-4 hidden">
                                Belum ada kriteria pengujian. Klik 'Tambah' untuk menambahkan kriteria.
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Simpan Quality Control
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // To prevent double initialization, only run once
                if (window.qcFormInitialized) return;
                window.qcFormInitialized = true;

                // Datepicker
                flatpickr('.datepicker', {
                    dateFormat: 'd/m/Y',
                    locale: 'id'
                });

                // Add kriteria
                let kriteriaCount = 1;

                // Remove existing click listener if any to prevent duplicate listeners
                const addKriteriaBtn = document.getElementById('add-kriteria');
                const oldAddKriteria = addKriteriaBtn.cloneNode(true);
                addKriteriaBtn.parentNode.replaceChild(oldAddKriteria, addKriteriaBtn);

                // Add fresh click listener
                document.getElementById('add-kriteria').addEventListener('click', function() {
                    const container = document.getElementById('kriteria-container');
                    const template = `
                        <div class="kriteria-item mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nama Kriteria <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="kriteria[${kriteriaCount}][nama]" required
                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                        placeholder="Masukkan nama kriteria">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Hasil <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kriteria[${kriteriaCount}][hasil]" required
                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <option value="lolos">Lolos</option>
                                        <option value="gagal">Gagal</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Keterangan
                                    </label>
                                    <div class="flex">
                                        <input type="text" name="kriteria[${kriteriaCount}][keterangan]"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md rounded-r-none"
                                            placeholder="Masukkan keterangan (opsional)">
                                        <button type="button" class="remove-kriteria px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-r-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Create a temporary div to hold our HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = template;

                    // Add event listener to remove button
                    tempDiv.querySelector('.remove-kriteria').addEventListener('click', function() {
                        this.closest('.kriteria-item').remove();
                        updateEmptyKriteriaMessage();
                    });

                    // Add to container
                    container.appendChild(tempDiv.firstElementChild);
                    kriteriaCount++;
                    updateEmptyKriteriaMessage();
                });

                // Update empty message
                function updateEmptyKriteriaMessage() {
                    const items = document.querySelectorAll('.kriteria-item');
                    const emptyMessage = document.getElementById('empty-kriteria');

                    if (items.length === 0) {
                        emptyMessage.classList.remove('hidden');
                    } else {
                        emptyMessage.classList.add('hidden');
                    }
                }

                // Set up the first remove button
                const firstRemoveBtn = document.querySelector('.first-remove-kriteria');
                if (firstRemoveBtn) {
                    firstRemoveBtn.addEventListener('click', function() {
                        this.closest('.kriteria-item').remove();
                        updateEmptyKriteriaMessage();
                    });
                }

                // Form validation
                document.getElementById('form-qc').addEventListener('submit', function(e) {
                    const jumlahLolos = parseFloat(document.getElementById('jumlah_lolos').value) || 0;
                    const jumlahGagal = parseFloat(document.getElementById('jumlah_gagal').value) || 0;
                    const totalQuantity = parseFloat('{{ $workOrder->quantity }}');

                    if (jumlahLolos + jumlahGagal !== totalQuantity) {
                        e.preventDefault();
                        alert(
                            `Total jumlah lolos dan gagal (${jumlahLolos + jumlahGagal}) harus sama dengan jumlah work order (${totalQuantity}).`
                        );
                        return false;
                    }

                    const kriteria = document.querySelectorAll('.kriteria-item');
                    if (kriteria.length === 0) {
                        e.preventDefault();
                        alert('Setidaknya harus ada satu kriteria pengujian.');
                        return false;
                    }
                });
            });

            // Update jumlah gagal when jumlah lolos changes
            function updateJumlahGagal() {
                const jumlahLolos = parseFloat(document.getElementById('jumlah_lolos').value) || 0;
                const totalQuantity = parseFloat('{{ $workOrder->quantity }}');
                const jumlahGagal = Math.max(0, totalQuantity - jumlahLolos).toFixed(2);

                document.getElementById('jumlah_gagal').value = jumlahGagal;
                updateProgressBar();
            }

            // Update jumlah lolos when jumlah gagal changes
            function updateJumlahLolos() {
                const jumlahGagal = parseFloat(document.getElementById('jumlah_gagal').value) || 0;
                const totalQuantity = parseFloat('{{ $workOrder->quantity }}');
                const jumlahLolos = Math.max(0, totalQuantity - jumlahGagal).toFixed(2);

                document.getElementById('jumlah_lolos').value = jumlahLolos;
                updateProgressBar();
            }

            // Update progress bar
            function updateProgressBar() {
                const jumlahLolos = parseFloat(document.getElementById('jumlah_lolos').value) || 0;
                const jumlahGagal = parseFloat(document.getElementById('jumlah_gagal').value) || 0;
                const totalQuantity = parseFloat('{{ $workOrder->quantity }}');

                const persenLolos = Math.round((jumlahLolos / totalQuantity) * 100);
                const persenGagal = Math.round((jumlahGagal / totalQuantity) * 100);

                document.getElementById('progress-lolos').style.width = persenLolos + '%';
                document.getElementById('persen-lolos').textContent = persenLolos;
                document.getElementById('persen-gagal').textContent = persenGagal;
            }
        </script>
    @endpush
</x-app-layout>

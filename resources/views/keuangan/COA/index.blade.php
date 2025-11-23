<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    {{-- Inline script to ensure toggleChildren is globally available --}}
    <script>
        // Define the toggleChildren function directly in the page scope
        function toggleChildren(button) {
            // console.log('toggleChildren called');

            // Get parent row
            const row = button.closest('tr');
            const parentId = row.getAttribute('data-id');
            const childRows = document.querySelectorAll(`tr[data-parent="${parentId}"]`);
            const icon = button.querySelector('svg');

            let isExpanded = false;

            // Check if children are currently visible
            if (childRows.length > 0) {
                isExpanded = !childRows[0].classList.contains('hidden');
            }

            // console.log('Toggle children for parent ID:', parentId, 'Currently expanded:', isExpanded);

            // Toggle visibility of child rows
            childRows.forEach(childRow => {
                if (isExpanded) {
                    // Hide this child and all its descendants
                    childRow.classList.add('hidden');

                    // Also hide any grandchildren
                    const childId = childRow.getAttribute('data-id');
                    const grandchildRows = document.querySelectorAll(`tr[data-parent="${childId}"]`);
                    grandchildRows.forEach(grandchildRow => {
                        grandchildRow.classList.add('hidden');
                    });

                    // Reset child toggle icons
                    const childToggle = childRow.querySelector('.toggle-children svg');
                    if (childToggle) {
                        childToggle.classList.remove('rotate-90');
                        childToggle.style.transform = '';
                    }
                } else {
                    // Show only direct children
                    childRow.classList.remove('hidden');
                }

                // console.log('Toggling child with ID:', childRow.getAttribute('data-id'),
                //     'New state:', isExpanded ? 'hidden' : 'visible');
            });

            // Rotate the icon based on expanded state
            if (icon) {
                if (isExpanded) {
                    icon.style.transform = '';
                    icon.classList.remove('rotate-90');
                } else {
                    icon.style.transform = 'rotate(90deg)';
                    icon.classList.add('rotate-90');
                }
            }

            return false; // Prevent default button behavior
        }
    </script>

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }

        .child-row {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Chart of Accounts (COA)</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Kelola struktur akun akuntansi untuk pencatatan keuangan PT Sinar Surya Semestaraya
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.accounting-configuration.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Kalibrasi Akun
                </a>
                <a href="{{ route('keuangan.coa.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Akun Baru
                </a>
            </div>
        </div>

        {{-- Filter and Search Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput"
                            class="pl-10 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                            placeholder="Cari kode atau nama akun...">
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <select id="filterKategori"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        <option value="">Semua Kategori</option>
                        <option value="asset">Aset</option>
                        <option value="liability">Kewajiban</option>
                        <option value="equity">Ekuitas</option>
                        <option value="income">Pendapatan</option>
                        <option value="expense">Beban</option>
                        <option value="purchase">Pembelian</option>
                        <option value="other_income">Pendapatan di Luar Usaha</option>
                        <option value="other_expense">Biaya di Luar Usaha</option>
                        <option value="other">Lainnya</option>
                    </select>
                    <select id="filterStatus"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Accounts List --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kode
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Referensi
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                        id="accountsTable">
                        @forelse ($akunRootLevel as $akun)
                            <x-coa-row :akun="$akun" :level="0" />
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    Tidak ada data akun yang tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Debug code only
        document.addEventListener('DOMContentLoaded', function() {
            // console.log('DOM loaded - debug child rows');

            // Debug info for all table rows
            var allParentRows = document.querySelectorAll('tr.parent-row');
            var allChildRows = document.querySelectorAll('tr.child-row');

            // console.log('Parent rows:', allParentRows.length);
            // console.log('Child rows:', allChildRows.length);

            // Log details about each child row
            allChildRows.forEach(function(row, index) {
                // console.log('Child row ' + index + ':',
                //     'Parent ID:', row.getAttribute('data-parent'),
                //     'ID:', row.getAttribute('data-id'),
                //     'Level:', row.getAttribute('data-level'),
                //     'Hidden:', row.classList.contains('hidden')
                // );
            });

            // Add rotate-90 CSS
            var style = document.createElement('style');
            style.textContent = `
                        .rotate-90 { 
                            transform: rotate(90deg); 
                        }
                        .child-row {
                            background-color: rgba(0, 0, 0, 0.02);
                        }
                    `;
            document.head.appendChild(style);
        });

        // Define the toggleChildren function in the global scope
        function toggleChildren(button) {
            // console.log('toggleChildren called');

            // Get parent row
            const row = button.closest('tr');
            const parentId = row.getAttribute('data-id');
            const childRows = document.querySelectorAll(`tr[data-parent="${parentId}"]`);
            const icon = button.querySelector('svg');

            let isExpanded = false;

            // Check if children are currently visible
            if (childRows.length > 0) {
                isExpanded = !childRows[0].classList.contains('hidden');
            }

            // console.log('Toggle children for parent ID:', parentId, 'Currently expanded:', isExpanded);

            // Toggle visibility of child rows
            childRows.forEach(childRow => {
                if (isExpanded) {
                    // Hide this child and all its descendants
                    childRow.classList.add('hidden');

                    // Also hide any grandchildren
                    const childId = childRow.getAttribute('data-id');
                    const grandchildRows = document.querySelectorAll(`tr[data-parent="${childId}"]`);
                    grandchildRows.forEach(grandchildRow => {
                        grandchildRow.classList.add('hidden');
                    });

                    // Reset child toggle icons
                    const childToggle = childRow.querySelector('.toggle-children svg');
                    if (childToggle) {
                        childToggle.classList.remove('rotate-90');
                        childToggle.style.transform = '';
                    }
                } else {
                    // Show only direct children
                    childRow.classList.remove('hidden');
                }

                // console.log('Toggling child with ID:', childRow.getAttribute('data-id'),
                //     'New state:', isExpanded ? 'hidden' : 'visible');
            });

            // Rotate the icon based on expanded state
            if (icon) {
                if (isExpanded) {
                    icon.style.transform = '';
                    icon.classList.remove('rotate-90');
                } else {
                    icon.style.transform = 'rotate(90deg)';
                    icon.classList.add('rotate-90');
                }
            }

            return false; // Prevent default button behavior
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const filterKategori = document.getElementById('filterKategori');
        const filterStatus = document.getElementById('filterStatus');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const kategoriFilter = filterKategori.value;
            const statusFilter = filterStatus.value;

            document.querySelectorAll('#accountsTable > tr.parent-row').forEach(row => {
                const kode = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const kategori = row.getAttribute('data-kategori');
                const status = row.getAttribute('data-status');

                const matchesSearch = kode.includes(searchTerm) || nama.includes(searchTerm);
                const matchesKategori = !kategoriFilter || kategori === kategoriFilter;
                const matchesStatus = !statusFilter || status === statusFilter;

                const shouldShow = matchesSearch && matchesKategori && matchesStatus;
                row.classList.toggle('hidden', !shouldShow);

                // Handle child rows
                if (shouldShow) {
                    // Expand parent if there's a search term to show matched children
                    const arrow = row.querySelector('.toggle-children svg');
                    if (arrow && searchTerm) {
                        arrow.classList.add('rotate-90');

                        // Show child rows that match search
                        const currentLevel = parseInt(row.getAttribute('data-level') || '0');
                        const nextLevel = currentLevel + 1;

                        let childRow = row.nextElementSibling;
                        while (childRow &&
                            childRow.classList.contains('child-row') &&
                            parseInt(childRow.getAttribute('data-level')) === nextLevel) {

                            const childKode = childRow.querySelector('td:nth-child(1)').textContent.toLowerCase();
                            const childNama = childRow.querySelector('td:nth-child(2)').textContent.toLowerCase();

                            if (searchTerm && (childKode.includes(searchTerm) || childNama.includes(searchTerm))) {
                                childRow.classList.remove('hidden');
                            } else if (!searchTerm) {
                                childRow.classList.add('hidden');
                            }

                            childRow = childRow.nextElementSibling;
                        }
                    }
                } else {
                    // Hide all children of hidden parent
                    let childRow = row.nextElementSibling;
                    while (childRow && childRow.classList.contains('child-row')) {
                        childRow.classList.add('hidden');
                        childRow = childRow.nextElementSibling;
                    }
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterKategori.addEventListener('change', filterTable);
        filterStatus.addEventListener('change', filterTable);

        // Debugging - Periksa apakah child accounts ada di DOM
        window.onload = function() {
            // console.log('=== DEBUG INFO ===');
            // console.log('Total rows in table:', document.querySelectorAll('#accountsTable tr').length);
            // console.log('Parent rows:', document.querySelectorAll('tr.parent-row').length);
            // console.log('Child rows:', document.querySelectorAll('tr.child-row').length);

            // Jika tidak ada child rows, kita cek apakah data sudah dimuat
            if (document.querySelectorAll('tr.child-row').length === 0) {
                // console.log('Tidak ada child rows - cek HTML comments untuk debug info');

                // Cek comments dalam HTML
                var html = document.body.innerHTML;
                var childCountPattern = /<!-- Has (\d+) children -->/g;
                var match;
                var total = 0;

                while ((match = childCountPattern.exec(html)) !== null) {
                    // console.log('Found child count in HTML:', match[1]);
                    total += parseInt(match[1]);
                }

                // console.log('Total children based on HTML comments:', total);
            }

            // Tampilkan parent row dan data-id mereka
            document.querySelectorAll('tr.parent-row').forEach(function(row, index) {
                // console.log('Parent ' + index + ' - ID:', row.getAttribute('data-id'),
                //     'Kode:', row.querySelector('td:nth-child(1)').textContent.trim());
            });
        };
    </script>
</x-app-layout>

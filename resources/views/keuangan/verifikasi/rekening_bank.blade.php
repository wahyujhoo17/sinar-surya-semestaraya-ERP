<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-primary-600 dark:text-primary-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Verifikasi Rekening Bank
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi konfigurasi akun rekening bank pada sistem
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.verifikasi.koreksi-rekening') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Koreksi Kategori
                </a>
            </div>
        </div>

        {{-- Rekening Bank List --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <div
                class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Akun Rekening Bank</h3>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span
                        class="bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded-md text-primary-700 dark:text-primary-400">
                        {{ count($results) }} akun rekening
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead
                        class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Kode
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Nama Akun
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Ref ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Rekening
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Saldo
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($results as $result)
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 
                                       {{ $result['akun_kategori'] !== 'Aset' ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $result['akun_id'] }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $result['akun_kode'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $result['akun_nama'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    @if ($result['akun_kategori'] === 'Aset')
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-md">
                                            {{ $result['akun_kategori'] }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-md font-medium">
                                            {{ $result['akun_kategori'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $result['akun_tipe'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $result['ref_id'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $result['rekening_nama'] }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                                    {{ is_numeric($result['rekening_saldo']) ? number_format($result['rekening_saldo'], 2, ',', '.') : $result['rekening_saldo'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            data akun rekening bank</span>
                                        <p class="text-gray-400 dark:text-gray-500 mt-1 max-w-md text-center">
                                            Tidak ada akun rekening bank yang ditemukan dalam sistem.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

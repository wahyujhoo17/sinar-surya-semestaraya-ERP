<x-app-layout :breadcrumbs="[
    ['label' => 'CRM', 'url' => route('crm.prospek.index')],
    ['label' => 'Prospek & Lead', 'url' => route('crm.prospek.index')],
    ['label' => 'Detail Prospek'],
]" :currentPage="'Detail Prospek'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Header with prospect information -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Left colored status bar -->
                <div class="w-full md:w-1.5 md:h-auto"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ $prospek->status }}'
                        === 'baru',
                        'bg-blue-500 dark:bg-blue-600': '{{ $prospek->status }}'
                        === 'tertarik',
                        'bg-yellow-500 dark:bg-yellow-600': '{{ $prospek->status }}'
                        === 'negosiasi',
                        'bg-red-500 dark:bg-red-600': '{{ $prospek->status }}'
                        === 'menolak',
                        'bg-green-500 dark:bg-green-600': '{{ $prospek->status }}'
                        === 'menjadi_customer',
                    }">
                </div>

                <!-- Header content -->
                <div class="flex-1 p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                                <h1
                                    class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $prospek->nama_prospek }}
                                </h1>

                                <span class="mt-2 md:mt-0 px-3 py-1 rounded-full text-sm font-medium"
                                    :class="{
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': '{{ $prospek->status }}'
                                        === 'baru',
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': '{{ $prospek->status }}'
                                        === 'tertarik',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300': '{{ $prospek->status }}'
                                        === 'negosiasi',
                                        'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300': '{{ $prospek->status }}'
                                        === 'menolak',
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300': '{{ $prospek->status }}'
                                        === 'menjadi_customer',
                                    }">
                                    @if ($prospek->status == 'baru')
                                        Baru
                                    @elseif($prospek->status == 'tertarik')
                                        Tertarik
                                    @elseif($prospek->status == 'negosiasi')
                                        Negosiasi
                                    @elseif($prospek->status == 'menolak')
                                        Menolak
                                    @elseif($prospek->status == 'menjadi_customer')
                                        Menjadi Customer
                                    @endif
                                </span>
                            </div>
                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                @if ($prospek->perusahaan)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $prospek->perusahaan }}
                                    </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $prospek->tanggal_kontak ? date('d F Y', strtotime($prospek->tanggal_kontak)) : 'Belum ada kontak' }}
                                </div>
                                @if ($prospek->user)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Sales: {{ $prospek->user->name }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex-shrink-0 flex space-x-3">
                            <a href="{{ route('crm.prospek.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                Kembali
                            </a>
                            <a href="{{ route('crm.prospek.edit', $prospek->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Prospek
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Source Information -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Sumber</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                            :class="{
                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': '{{ $prospek->sumber }}'
                                === 'lainnya',
                                'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300': '{{ $prospek->sumber }}'
                                === 'website',
                                'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300': '{{ $prospek->sumber }}'
                                === 'referral',
                                'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300': '{{ $prospek->sumber }}'
                                === 'pameran',
                                'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300': '{{ $prospek->sumber }}'
                                === 'media_sosial',
                                'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300': '{{ $prospek->sumber }}'
                                === 'cold_call'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                @if ($prospek->sumber == 'website')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @elseif($prospek->sumber == 'referral')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                @elseif($prospek->sumber == 'pameran')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                @elseif($prospek->sumber == 'media_sosial')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                @elseif($prospek->sumber == 'cold_call')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sumber</h4>
                            <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                @if ($prospek->sumber == 'website')
                                    Website
                                @elseif($prospek->sumber == 'referral')
                                    Referral
                                @elseif($prospek->sumber == 'pameran')
                                    Pameran
                                @elseif($prospek->sumber == 'media_sosial')
                                    Media Sosial
                                @elseif($prospek->sumber == 'cold_call')
                                    Cold Call
                                @elseif($prospek->sumber == 'lainnya')
                                    Lainnya
                                @else
                                    {{ $prospek->sumber }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai Potensi</h4>
                            <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                {{ number_format($prospek->nilai_potensi, 0, ',', '.') ? 'Rp ' . number_format($prospek->nilai_potensi, 0, ',', '.') : 'Rp 0' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Sales Penanggung Jawab -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sales Penanggung Jawab</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="p-6">
                    @if ($prospek->user)
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300 flex items-center justify-center font-semibold text-lg">
                                {{ strtoupper(substr($prospek->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ $prospek->user->name }}</h4>
                                @if ($prospek->user->email)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $prospek->user->email }}
                                    </p>
                                @endif
                                <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                    Bertanggung jawab
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div
                                class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada sales yang ditugaskan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Kontak -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Kontak</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Prospek</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $prospek->nama_prospek }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1v1h-3v-1H8v1H5v-1a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Perusahaan</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $prospek->perusahaan ?: '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">
                                @if ($prospek->email)
                                    <a href="mailto:{{ $prospek->email }}"
                                        class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ $prospek->email }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">
                                @if ($prospek->telepon)
                                    <a href="tel:{{ $prospek->telepon }}"
                                        class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ $prospek->telepon }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $prospek->alamat ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Prospek -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Prospek</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai Potensi</h4>
                            <p class="mt-1 text-base font-medium"
                                :class="{
                                    'text-gray-900 dark:text-white': {{ $prospek->nilai_potensi }} < 10000000,
                                    'text-blue-600 dark:text-blue-400': {{ $prospek->nilai_potensi }} >= 10000000 &&
                                        {{ $prospek->nilai_potensi }} < 50000000,
                                    'text-green-600 dark:text-green-400': {{ $prospek->nilai_potensi }} >= 50000000
                                }">
                                {{ number_format($prospek->nilai_potensi, 0, ',', '.') ? 'Rp ' . number_format($prospek->nilai_potensi, 0, ',', '.') : 'Rp 0' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Kontak Pertama
                            </h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $prospek->tanggal_kontak ? date('d F Y', strtotime($prospek->tanggal_kontak)) : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Follow-up
                                Berikutnya</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $prospek->tanggal_followup ? date('d F Y', strtotime($prospek->tanggal_followup)) : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ date('d F Y, H:i', strtotime($prospek->created_at)) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diupdate</h4>
                            <p class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ date('d F Y, H:i', strtotime($prospek->updated_at)) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan & Tindakan -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Catatan & Tindakan</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Tambahan</h4>
                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-base text-gray-900 dark:text-white">
                                {{ $prospek->catatan ?: 'Tidak ada catatan tambahan.' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 space-y-3">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tindakan</h4>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('crm.prospek.edit', $prospek->id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Prospek
                            </a>

                            <form action="{{ route('crm.prospek.destroy', $prospek->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus prospek ini?')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Hapus Prospek
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline & Aktivitas -->
        @include('CRM.prospek_and_lead.partials.aktivitas_list')
    </div>
</x-app-layout>

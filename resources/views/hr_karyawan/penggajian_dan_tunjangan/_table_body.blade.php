@if ($penggajian->count() > 0 || (isset($unpaidEmployees) && $unpaidEmployees->count() > 0))
    {{-- Display employees who already have payments --}}
    @foreach ($penggajian as $index => $item)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
            <td class="px-3 py-4 whitespace-nowrap">{{ $penggajian->firstItem() + $index }}</td>
            <td class="px-3 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        @if ($item->karyawan->foto)
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="{{ asset('storage/' . $item->karyawan->foto) }}"
                                alt="{{ $item->karyawan->nama_lengkap }}">
                        @else
                            <div
                                class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-medium">
                                {{ strtoupper(substr($item->karyawan->nama_lengkap, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->karyawan->nama_lengkap }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->karyawan->nip }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                @php
                    $bulanLabels = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];

                    // Ensure we have numeric keys for the month
                    $itemBulan = (int) $item->bulan;
                @endphp
                {{ $bulanLabels[$itemBulan] ?? 'Bulan ' . $itemBulan }}
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->tahun }}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Rp
                {{ number_format($item->total_gaji, 0, ',', '.') }}</td>
            <td class="px-3 py-4 whitespace-nowrap">
                @if ($item->status == 'draft')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-500">
                        Draft
                    </span>
                @elseif($item->status == 'disetujui')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-500">
                        Disetujui
                    </span>
                @elseif($item->status == 'dibayar')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-500">
                        Dibayar
                    </span>
                @endif
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $item->tanggal_bayar ? date('d/m/Y', strtotime($item->tanggal_bayar)) : '-' }}
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('hr.penggajian.show', $item->id) }}"
                        class="inline-flex items-center px-2 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        title="Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>

                    {{-- Hide Edit and Delete buttons if status is 'disetujui' or 'dibayar' --}}
                    @if ($item->status === 'draft')
                        <a href="{{ route('hr.penggajian.edit', $item->id) }}"
                            class="inline-flex items-center px-2 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                            title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('hr.penggajian.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-2 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 delete-btn"
                                title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Display employees who don't have payments --}}
    @isset($unpaidEmployees)
        @foreach ($unpaidEmployees as $employee)
            <tr
                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 bg-gray-50 dark:bg-gray-800/50">
                <td class="px-3 py-4 whitespace-nowrap">
                    @if ($penggajian->count() > 0)
                        {{ $penggajian->firstItem() + $penggajian->count() + $loop->index }}
                    @else
                        {{ $loop->iteration }}
                    @endif
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if ($employee->foto)
                                <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ asset('storage/' . $employee->foto) }}" alt="{{ $employee->nama_lengkap }}">
                            @else
                                <div
                                    class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-medium">
                                    {{ strtoupper(substr($employee->nama_lengkap, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $employee->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $employee->nip }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                    @php
                        $bulanLabels = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ];

                        // Ensure we have numeric keys for the month
                        $month = isset($selectedMonth) ? (int) $selectedMonth : (int) $currentMonth;
                    @endphp
                    {{ $bulanLabels[$month] ?? 'Bulan ' . $month }}
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                    {{ isset($selectedYear) ? $selectedYear : $currentYear }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                    @if ($employee->gaji_pokok)
                        Rp {{ number_format($employee->gaji_pokok, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        Belum Dibayar
                    </span>
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">-</td>
                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end">
                        <a href="{{ route('hr.penggajian.create', [
                            'karyawan_id' => $employee->id,
                            'bulan' => isset($selectedMonth) ? $selectedMonth : $currentMonth,
                            'tahun' => isset($selectedYear) ? $selectedYear : $currentYear,
                        ]) }}"
                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Proses Pembayaran
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    @endisset
@else
    <tr>
        <td colspan="8" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
            Tidak ada data penggajian
        </td>
    </tr>
@endif

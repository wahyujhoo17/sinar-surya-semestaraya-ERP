@php
    $statusColors = [
        'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'final' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'belum_bayar' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'sudah_bayar' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'lebih_bayar' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'ppn_keluaran' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'ppn_masukan' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        'pph21' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
        'pph23' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
        'pph4_ayat2' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    ];

    $statusLabels = [
        'draft' => 'Draft',
        'final' => 'Final',
        'belum_bayar' => 'Belum Bayar',
        'sudah_bayar' => 'Sudah Bayar',
        'lebih_bayar' => 'Lebih Bayar',
        'ppn_keluaran' => 'PPN Keluaran',
        'ppn_masukan' => 'PPN Masukan',
        'pph21' => 'PPh 21',
        'pph23' => 'PPh 23',
        'pph4_ayat2' => 'PPh 4 Ayat 2',
    ];

    $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
    $label = $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
    {{ $label }}
</span>

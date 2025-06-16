<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Akun Default untuk Jurnal Otomatis
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini menentukan akun-akun default yang akan digunakan
    | saat membuat jurnal secara otomatis untuk berbagai jenis transaksi.
    |
    */

    // Penjualan
    'penjualan' => [
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID'),          // Piutang Usaha
        'pendapatan_penjualan' => env('AKUN_PENDAPATAN_PENJUALAN_ID'),  // Pendapatan Penjualan
        'ppn_keluaran' => env('AKUN_PPN_KELUARAN_ID'),          // PPN Keluaran
        'hpp' => env('AKUN_HPP_ID'),                   // Harga Pokok Penjualan
        'persediaan' => env('AKUN_PERSEDIAAN_ID'),          // Persediaan Barang
    ],

    // Pembelian
    'pembelian' => [
        'hutang_usaha' => env('AKUN_HUTANG_USAHA_ID'),          // Hutang Usaha
        'persediaan' => env('AKUN_PERSEDIAAN_ID'),          // Persediaan Barang
        'ppn_masukan' => env('AKUN_PPN_MASUKAN_ID'),          // PPN Masukan
    ],

    // Penerimaan Pembayaran
    'pembayaran_piutang' => [
        'kas' => env('AKUN_KAS_ID'),                   // Kas
        'bank' => env('AKUN_BANK_ID'),                  // Bank
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID'),          // Piutang Usaha
    ],

    // Pembayaran Hutang
    'pembayaran_hutang' => [
        'kas' => env('AKUN_KAS_ID'),                   // Kas
        'bank' => env('AKUN_BANK_ID'),                  // Bank
        'hutang_usaha' => env('AKUN_HUTANG_USAHA_ID'),          // Hutang Usaha
    ],

    // Retur Penjualan
    'retur_penjualan' => [
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID'),          // Piutang Usaha
        'pendapatan_penjualan' => env('AKUN_PENDAPATAN_PENJUALAN_ID'),  // Pendapatan Penjualan
        'ppn_keluaran' => env('AKUN_PPN_KELUARAN_ID'),          // PPN Keluaran
        'persediaan' => env('AKUN_PERSEDIAAN_ID'),          // Persediaan Barang
        'hpp' => env('AKUN_HPP_ID'),                   // Harga Pokok Penjualan
    ],

    // Retur Pembelian
    'retur_pembelian' => [
        'hutang_usaha' => env('AKUN_HUTANG_USAHA_ID'),          // Hutang Usaha
        'persediaan' => env('AKUN_PERSEDIAAN_ID'),          // Persediaan Barang
        'ppn_masukan' => env('AKUN_PPN_MASUKAN_ID'),          // PPN Masukan
    ],

    // Beban Operasional
    'beban_operasional' => [
        'kas' => env('AKUN_KAS_ID'),                   // Kas
        'bank' => env('AKUN_BANK_ID'),                  // Bank
        'beban_gaji' => env('AKUN_BEBAN_GAJI_ID'),          // Beban Gaji
        'beban_sewa' => env('AKUN_BEBAN_SEWA_ID'),          // Beban Sewa
        'beban_utilitas' => env('AKUN_BEBAN_UTILITAS_ID'),       // Beban Utilitas (Listrik, Air, dll)
        'beban_administrasi' => env('AKUN_BEBAN_ADMINISTRASI_ID'),    // Beban Administrasi
        'beban_transportasi' => env('AKUN_BEBAN_TRANSPORTASI_ID'),    // Beban Transportasi
        'beban_lainnya' => env('AKUN_BEBAN_LAINNYA_ID'),        // Beban Lainnya
    ],

    // Penyesuaian Persediaan
    'penyesuaian_stok' => [
        'persediaan' => env('AKUN_PERSEDIAAN_ID'),          // Persediaan Barang
        'penyesuaian_persediaan' => env('AKUN_PENYESUAIAN_PERSEDIAAN_ID'), // Penyesuaian Persediaan
    ],
];

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

    // Akun-akun utama untuk testing dan referensi
    'accounts' => [
        'kas' => env('AKUN_KAS_ID', 19),                        // Kas (KAS 1)
        'bank' => env('AKUN_BANK_ID', 22),                      // Bank (Rekening Mandiri)
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID', 24),   // Piutang Usaha  
        'persediaan' => env('AKUN_PERSEDIAAN_ID', 25),         // Persediaan Barang Dagang
        'hutang_usaha' => env('AKUN_HUTANG_USAHA_ID', 9),      // Hutang Usaha
        'pendapatan_penjualan' => env('AKUN_PENDAPATAN_PENJUALAN_ID', 13), // Pendapatan Penjualan
        'hpp' => env('AKUN_HPP_ID', 14),                       // Beban (untuk HPP)
        'beban_operasional' => env('AKUN_BEBAN_OPERASIONAL_ID', 15), // Beban Operasional
    ],

    // Penjualan
    'penjualan' => [
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID', 24),          // Piutang Usaha
        'pendapatan_penjualan' => env('AKUN_PENDAPATAN_PENJUALAN_ID', 13),  // Pendapatan Penjualan
        'ppn_keluaran' => env('AKUN_PPN_KELUARAN_ID', 26),          // PPN Masukan (temporary)
        'hpp' => env('AKUN_HPP_ID', 14),                   // Beban (untuk HPP)
        'persediaan' => env('AKUN_PERSEDIAAN_ID', 25),          // Persediaan Barang Dagang
    ],

    // Pembelian
    'pembelian' => [
        'hutang_usaha' => env('AKUN_HUTANG_USAHA_ID', 9),          // Hutang Usaha
        'persediaan' => env('AKUN_PERSEDIAAN_ID', 25),          // Persediaan Barang Dagang
        'ppn_masukan' => env('AKUN_PPN_MASUKAN_ID', 26),          // PPN Masukan
    ],

    // Penerimaan Pembayaran
    'pembayaran_piutang' => [
        'kas' => env('AKUN_KAS_ID', 19),                   // Kas
        'bank' => env('AKUN_BANK_ID', 22),                  // Bank
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID', 24),          // Piutang Usaha
    ],

    // Pembayaran Hutang
    'pembayaran_hutang' => [
        'kas' => env('AKUN_KAS_ID', 19),                   // Kas
        'bank' => env('AKUN_BANK_ID', 22),                  // Bank
        'hutang_usaha' => env('AKUN_HUTANG_USAHA_ID', 9),          // Hutang Usaha
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

    // Penggajian
    'penggajian' => [
        'kas' => env('AKUN_KAS_ID', 19),                   // Kas
        'bank' => env('AKUN_BANK_ID', 22),                  // Bank
        'beban_gaji' => env('AKUN_BEBAN_GAJI_ID', 15),      // Beban Gaji (menggunakan beban operasional sebagai default)
    ],
];

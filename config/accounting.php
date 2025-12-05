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
        'hpp' => env('AKUN_HPP_ID', 28),                       // Harga Pokok Penjualan
        'beban_operasional' => env('AKUN_BEBAN_OPERASIONAL_ID', 15), // Beban Operasional
        'ppn_masukan' => env('AKUN_PPN_MASUKAN_ID', 26),       // PPN Masukan
        'ppn_keluaran' => env('AKUN_PPN_KELUARAN_ID', 27),     // PPN Keluaran
        'beban_gaji' => env('AKUN_BEBAN_GAJI_ID', 162),        // BIAYA GAJI BAG. KANTOR (60301)
    ],

    // Peng jualan
    'penjualan' => [
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID', 24),          // Piutang Usaha
        'penjualan' => env('AKUN_PENDAPATAN_PENJUALAN_ID', 13),  // Pendapatan Penjualan (alias)
        'pendapatan_penjualan' => env('AKUN_PENDAPATAN_PENJUALAN_ID', 13),  // Pendapatan Penjualan
        'ppn_keluaran' => env('AKUN_PPN_KELUARAN_ID', 27),          // PPN Keluaran
        'hpp' => env('AKUN_HPP_ID', 28),                   // Harga Pokok Penjualan
        'persediaan' => env('AKUN_PERSEDIAAN_ID', 25),          // Persediaan Barang Dagang
    ],

    // Pembelian
    'pembelian' => [
        'pembelian' => env('AKUN_PEMBELIAN_ID', null),          // Pembelian (50001) - untuk laporan HPP
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

    // Uang Muka Penjualan
    'uang_muka_penjualan' => [
        'kas' => env('AKUN_KAS_ID', 19),                        // Kas
        'bank' => env('AKUN_BANK_ID', 22),                       // Bank
        'hutang_uang_muka_penjualan' => env('AKUN_HUTANG_UANG_MUKA_PENJUALAN_ID', null), // Hutang Uang Muka Penjualan (2201)
        'piutang_usaha' => env('AKUN_PIUTANG_USAHA_ID', 24),    // Piutang Usaha (untuk aplikasi ke invoice)
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
        'beban_gaji' => env('AKUN_BEBAN_GAJI_ID', 162),     // BIAYA GAJI BAG. KANTOR (60301)
        'beban_sewa' => env('AKUN_BEBAN_SEWA_ID'),          // Beban Sewa
        'beban_utilitas' => env('AKUN_BEBAN_UTILITAS_ID', 173), // BIAYA LISTRIK, TELP/INTERNET & PAM (60312)
        'beban_administrasi' => env('AKUN_BEBAN_ADMINISTRASI_ID'),    // Beban Administrasi
        'beban_transportasi' => env('AKUN_BEBAN_TRANSPORTASI_ID'),    // Beban Transportasi
        'beban_atk' => env('AKUN_BEBAN_ATK_ID', 69),        // Biaya ATK (61014)
        'beban_jasa' => env('AKUN_BEBAN_JASA_ID', 72),      // Biaya Jasa (61019)
        'beban_promosi' => env('AKUN_BEBAN_PROMOSI_ID', 73), // Biaya Promosi & Iklan (61020)
        'beban_entertainment' => env('AKUN_BEBAN_ENTERTAINMENT_ID', 170), // BIAYA ENTERTAINT ADM (60309)
        'beban_konsumsi' => env('AKUN_BEBAN_KONSUMSI_ID', 67), // Biaya Konsumsi (61012)
        'beban_legalitas' => env('AKUN_BEBAN_LEGALITAS_ID', 174), // BIAYA LEGALITAS & PERIZINAN (60313)
        'beban_training' => env('AKUN_BEBAN_TRAINING_ID', 75), // Biaya Training (61022)
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
        'beban_gaji' => env('AKUN_BEBAN_GAJI_ID', 162),     // BIAYA GAJI BAG. KANTOR (60301)
    ],

    // Saldo Awal / Opening Balance
    'saldo_awal' => [
        'modal_pemilik' => env('AKUN_MODAL_PEMILIK_ID', null),  // Modal Pemilik / Ekuitas
    ],

    // Laporan Keuangan - Konfigurasi akun untuk Income Statement
    'laporan_keuangan' => [
        'persediaan' => env('AKUN_PERSEDIAAN_ID', 25),          // Persediaan Barang Dagang untuk HPP
        'pembelian' => env('AKUN_PEMBELIAN_ID', null),          // Pembelian untuk HPP (50001)
    ],

    // Header/Parent untuk Auto-Create COA
    'headers' => [
        'kas_bank' => env('AKUN_HEADER_KAS_BANK_ID', null),  // Header untuk Kas & Bank (default: 11050 - KAS DI BANK)
    ],
];

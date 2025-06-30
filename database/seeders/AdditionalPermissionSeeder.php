<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class AdditionalPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== MENAMBAHKAN PERMISSION TAMBAHAN ===" . PHP_EOL;

        // Array of additional permissions that might be missing
        $additionalPermissions = [
            // ===== MASTER DATA - ACTIONS =====
            // Produk
            ['nama' => 'Produk - Create', 'kode' => 'produk.create', 'modul' => 'produk', 'deskripsi' => 'Dapat menambah produk baru'],
            ['nama' => 'Produk - Edit', 'kode' => 'produk.edit', 'modul' => 'produk', 'deskripsi' => 'Dapat mengedit produk'],
            ['nama' => 'Produk - Delete', 'kode' => 'produk.delete', 'modul' => 'produk', 'deskripsi' => 'Dapat menghapus produk'],
            ['nama' => 'Produk - Export', 'kode' => 'produk.export', 'modul' => 'produk', 'deskripsi' => 'Dapat export data produk'],
            ['nama' => 'Produk - Import', 'kode' => 'produk.import', 'modul' => 'produk', 'deskripsi' => 'Dapat import data produk'],

            // Kategori Produk
            ['nama' => 'Kategori Produk - Create', 'kode' => 'kategori_produk.create', 'modul' => 'kategori_produk', 'deskripsi' => 'Dapat menambah kategori produk'],
            ['nama' => 'Kategori Produk - Edit', 'kode' => 'kategori_produk.edit', 'modul' => 'kategori_produk', 'deskripsi' => 'Dapat mengedit kategori produk'],
            ['nama' => 'Kategori Produk - Delete', 'kode' => 'kategori_produk.delete', 'modul' => 'kategori_produk', 'deskripsi' => 'Dapat menghapus kategori produk'],

            // Pelanggan
            ['nama' => 'Pelanggan - Create', 'kode' => 'pelanggan.create', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat menambah pelanggan'],
            ['nama' => 'Pelanggan - Edit', 'kode' => 'pelanggan.edit', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat mengedit pelanggan'],
            ['nama' => 'Pelanggan - Delete', 'kode' => 'pelanggan.delete', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat menghapus pelanggan'],
            ['nama' => 'Pelanggan - Export', 'kode' => 'pelanggan.export', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat export data pelanggan'],

            // Supplier
            ['nama' => 'Supplier - Create', 'kode' => 'supplier.create', 'modul' => 'supplier', 'deskripsi' => 'Dapat menambah supplier'],
            ['nama' => 'Supplier - Edit', 'kode' => 'supplier.edit', 'modul' => 'supplier', 'deskripsi' => 'Dapat mengedit supplier'],
            ['nama' => 'Supplier - Delete', 'kode' => 'supplier.delete', 'modul' => 'supplier', 'deskripsi' => 'Dapat menghapus supplier'],
            ['nama' => 'Supplier - Export', 'kode' => 'supplier.export', 'modul' => 'supplier', 'deskripsi' => 'Dapat export data supplier'],

            // Gudang
            ['nama' => 'Gudang - Create', 'kode' => 'gudang.create', 'modul' => 'gudang', 'deskripsi' => 'Dapat menambah gudang'],
            ['nama' => 'Gudang - Edit', 'kode' => 'gudang.edit', 'modul' => 'gudang', 'deskripsi' => 'Dapat mengedit gudang'],
            ['nama' => 'Gudang - Delete', 'kode' => 'gudang.delete', 'modul' => 'gudang', 'deskripsi' => 'Dapat menghapus gudang'],

            // Satuan
            ['nama' => 'Satuan - Create', 'kode' => 'satuan.create', 'modul' => 'satuan', 'deskripsi' => 'Dapat menambah satuan'],
            ['nama' => 'Satuan - Edit', 'kode' => 'satuan.edit', 'modul' => 'satuan', 'deskripsi' => 'Dapat mengedit satuan'],
            ['nama' => 'Satuan - Delete', 'kode' => 'satuan.delete', 'modul' => 'satuan', 'deskripsi' => 'Dapat menghapus satuan'],

            // ===== TRANSAKSI PENJUALAN - ACTIONS =====
            // Quotation
            ['nama' => 'Quotation - Create', 'kode' => 'quotation.create', 'modul' => 'quotation', 'deskripsi' => 'Dapat membuat quotation'],
            ['nama' => 'Quotation - Edit', 'kode' => 'quotation.edit', 'modul' => 'quotation', 'deskripsi' => 'Dapat mengedit quotation'],
            ['nama' => 'Quotation - Delete', 'kode' => 'quotation.delete', 'modul' => 'quotation', 'deskripsi' => 'Dapat menghapus quotation'],
            ['nama' => 'Quotation - Approve', 'kode' => 'quotation.approve', 'modul' => 'quotation', 'deskripsi' => 'Dapat approve quotation'],
            ['nama' => 'Quotation - Print', 'kode' => 'quotation.print', 'modul' => 'quotation', 'deskripsi' => 'Dapat print quotation'],

            // Sales Order
            ['nama' => 'Sales Order - Create', 'kode' => 'sales_order.create', 'modul' => 'sales_order', 'deskripsi' => 'Dapat membuat sales order'],
            ['nama' => 'Sales Order - Edit', 'kode' => 'sales_order.edit', 'modul' => 'sales_order', 'deskripsi' => 'Dapat mengedit sales order'],
            ['nama' => 'Sales Order - Delete', 'kode' => 'sales_order.delete', 'modul' => 'sales_order', 'deskripsi' => 'Dapat menghapus sales order'],
            ['nama' => 'Sales Order - Approve', 'kode' => 'sales_order.approve', 'modul' => 'sales_order', 'deskripsi' => 'Dapat approve sales order'],
            ['nama' => 'Sales Order - Print', 'kode' => 'sales_order.print', 'modul' => 'sales_order', 'deskripsi' => 'Dapat print sales order'],

            // Delivery Order
            ['nama' => 'Delivery Order - Create', 'kode' => 'delivery_order.create', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat membuat delivery order'],
            ['nama' => 'Delivery Order - Edit', 'kode' => 'delivery_order.edit', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat mengedit delivery order'],
            ['nama' => 'Delivery Order - Delete', 'kode' => 'delivery_order.delete', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat menghapus delivery order'],
            ['nama' => 'Delivery Order - Print', 'kode' => 'delivery_order.print', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat print delivery order'],

            // Invoice
            ['nama' => 'Invoice - Create', 'kode' => 'invoice.create', 'modul' => 'invoice', 'deskripsi' => 'Dapat membuat invoice'],
            ['nama' => 'Invoice - Edit', 'kode' => 'invoice.edit', 'modul' => 'invoice', 'deskripsi' => 'Dapat mengedit invoice'],
            ['nama' => 'Invoice - Delete', 'kode' => 'invoice.delete', 'modul' => 'invoice', 'deskripsi' => 'Dapat menghapus invoice'],
            ['nama' => 'Invoice - Print', 'kode' => 'invoice.print', 'modul' => 'invoice', 'deskripsi' => 'Dapat print invoice'],
            ['nama' => 'Invoice - Payment', 'kode' => 'invoice.payment', 'modul' => 'invoice', 'deskripsi' => 'Dapat input pembayaran invoice'],

            // ===== TRANSAKSI PEMBELIAN - ACTIONS =====
            // Purchase Request
            ['nama' => 'Purchase Request - Create', 'kode' => 'purchase_request.create', 'modul' => 'purchase_request', 'deskripsi' => 'Dapat membuat purchase request'],
            ['nama' => 'Purchase Request - Edit', 'kode' => 'purchase_request.edit', 'modul' => 'purchase_request', 'deskripsi' => 'Dapat mengedit purchase request'],
            ['nama' => 'Purchase Request - Delete', 'kode' => 'purchase_request.delete', 'modul' => 'purchase_request', 'deskripsi' => 'Dapat menghapus purchase request'],
            ['nama' => 'Purchase Request - Approve', 'kode' => 'purchase_request.approve', 'modul' => 'purchase_request', 'deskripsi' => 'Dapat approve purchase request'],

            // Purchase Order
            ['nama' => 'Purchase Order - Create', 'kode' => 'purchase_order.create', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat membuat purchase order'],
            ['nama' => 'Purchase Order - Edit', 'kode' => 'purchase_order.edit', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat mengedit purchase order'],
            ['nama' => 'Purchase Order - Delete', 'kode' => 'purchase_order.delete', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat menghapus purchase order'],
            ['nama' => 'Purchase Order - Approve', 'kode' => 'purchase_order.approve', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat approve purchase order'],
            ['nama' => 'Purchase Order - Print', 'kode' => 'purchase_order.print', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat print purchase order'],

            // Penerimaan Barang
            ['nama' => 'Penerimaan Barang - Create', 'kode' => 'penerimaan_barang.create', 'modul' => 'penerimaan_barang', 'deskripsi' => 'Dapat input penerimaan barang'],
            ['nama' => 'Penerimaan Barang - Edit', 'kode' => 'penerimaan_barang.edit', 'modul' => 'penerimaan_barang', 'deskripsi' => 'Dapat mengedit penerimaan barang'],
            ['nama' => 'Penerimaan Barang - Delete', 'kode' => 'penerimaan_barang.delete', 'modul' => 'penerimaan_barang', 'deskripsi' => 'Dapat menghapus penerimaan barang'],

            // ===== KEUANGAN - ACTIONS =====
            // Chart of Accounts
            ['nama' => 'Chart of Accounts - Create', 'kode' => 'chart_of_accounts.create', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat menambah akun'],
            ['nama' => 'Chart of Accounts - Edit', 'kode' => 'chart_of_accounts.edit', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat mengedit akun'],
            ['nama' => 'Chart of Accounts - Delete', 'kode' => 'chart_of_accounts.delete', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat menghapus akun'],

            // Jurnal Umum
            ['nama' => 'Jurnal Umum - Create', 'kode' => 'jurnal_umum.create', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat membuat jurnal'],
            ['nama' => 'Jurnal Umum - Edit', 'kode' => 'jurnal_umum.edit', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat mengedit jurnal'],
            ['nama' => 'Jurnal Umum - Delete', 'kode' => 'jurnal_umum.delete', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat menghapus jurnal'],
            ['nama' => 'Jurnal Umum - Export', 'kode' => 'jurnal_umum.export', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat export jurnal'],

            // Kas dan Bank
            ['nama' => 'Kas dan Bank - Create', 'kode' => 'kas_dan_bank.create', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat input transaksi kas/bank'],
            ['nama' => 'Kas dan Bank - Edit', 'kode' => 'kas_dan_bank.edit', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat mengedit transaksi kas/bank'],
            ['nama' => 'Kas dan Bank - Delete', 'kode' => 'kas_dan_bank.delete', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat menghapus transaksi kas/bank'],

            // Piutang Usaha
            ['nama' => 'Piutang Usaha - Create', 'kode' => 'piutang_usaha.create', 'modul' => 'piutang_usaha', 'deskripsi' => 'Dapat input piutang'],
            ['nama' => 'Piutang Usaha - Edit', 'kode' => 'piutang_usaha.edit', 'modul' => 'piutang_usaha', 'deskripsi' => 'Dapat mengedit piutang'],
            ['nama' => 'Piutang Usaha - Delete', 'kode' => 'piutang_usaha.delete', 'modul' => 'piutang_usaha', 'deskripsi' => 'Dapat menghapus piutang'],

            // Hutang Usaha
            ['nama' => 'Hutang Usaha - Create', 'kode' => 'hutang_usaha.create', 'modul' => 'hutang_usaha', 'deskripsi' => 'Dapat input hutang'],
            ['nama' => 'Hutang Usaha - Edit', 'kode' => 'hutang_usaha.edit', 'modul' => 'hutang_usaha', 'deskripsi' => 'Dapat mengedit hutang'],
            ['nama' => 'Hutang Usaha - Delete', 'kode' => 'hutang_usaha.delete', 'modul' => 'hutang_usaha', 'deskripsi' => 'Dapat menghapus hutang'],

            // ===== PRODUKSI - ACTIONS =====
            // Work Order
            ['nama' => 'Work Order - Create', 'kode' => 'work_order.create', 'modul' => 'work_order', 'deskripsi' => 'Dapat membuat work order'],
            ['nama' => 'Work Order - Edit', 'kode' => 'work_order.edit', 'modul' => 'work_order', 'deskripsi' => 'Dapat mengedit work order'],
            ['nama' => 'Work Order - Delete', 'kode' => 'work_order.delete', 'modul' => 'work_order', 'deskripsi' => 'Dapat menghapus work order'],
            ['nama' => 'Work Order - Start', 'kode' => 'work_order.start', 'modul' => 'work_order', 'deskripsi' => 'Dapat memulai produksi'],
            ['nama' => 'Work Order - Complete', 'kode' => 'work_order.complete', 'modul' => 'work_order', 'deskripsi' => 'Dapat menyelesaikan produksi'],

            // Bill of Material
            ['nama' => 'Bill of Material - Create', 'kode' => 'bill_of_material.create', 'modul' => 'bill_of_material', 'deskripsi' => 'Dapat membuat BOM'],
            ['nama' => 'Bill of Material - Edit', 'kode' => 'bill_of_material.edit', 'modul' => 'bill_of_material', 'deskripsi' => 'Dapat mengedit BOM'],
            ['nama' => 'Bill of Material - Delete', 'kode' => 'bill_of_material.delete', 'modul' => 'bill_of_material', 'deskripsi' => 'Dapat menghapus BOM'],

            // Perencanaan Produksi
            ['nama' => 'Perencanaan Produksi - Create', 'kode' => 'perencanaan_produksi.create', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat membuat rencana produksi'],
            ['nama' => 'Perencanaan Produksi - Edit', 'kode' => 'perencanaan_produksi.edit', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat mengedit rencana produksi'],
            ['nama' => 'Perencanaan Produksi - Delete', 'kode' => 'perencanaan_produksi.delete', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat menghapus rencana produksi'],

            // Quality Control
            ['nama' => 'Quality Control - Create', 'kode' => 'quality_control.create', 'modul' => 'quality_control', 'deskripsi' => 'Dapat input quality control'],
            ['nama' => 'Quality Control - Edit', 'kode' => 'quality_control.edit', 'modul' => 'quality_control', 'deskripsi' => 'Dapat mengedit quality control'],
            ['nama' => 'Quality Control - Approve', 'kode' => 'quality_control.approve', 'modul' => 'quality_control', 'deskripsi' => 'Dapat approve quality control'],
            ['nama' => 'Quality Control - Reject', 'kode' => 'quality_control.reject', 'modul' => 'quality_control', 'deskripsi' => 'Dapat reject quality control'],

            // ===== HR - ACTIONS =====
            // Karyawan
            ['nama' => 'Karyawan - Create', 'kode' => 'karyawan.create', 'modul' => 'karyawan', 'deskripsi' => 'Dapat menambah karyawan'],
            ['nama' => 'Karyawan - Edit', 'kode' => 'karyawan.edit', 'modul' => 'karyawan', 'deskripsi' => 'Dapat mengedit data karyawan'],
            ['nama' => 'Karyawan - Delete', 'kode' => 'karyawan.delete', 'modul' => 'karyawan', 'deskripsi' => 'Dapat menghapus karyawan'],
            ['nama' => 'Karyawan - Export', 'kode' => 'karyawan.export', 'modul' => 'karyawan', 'deskripsi' => 'Dapat export data karyawan'],

            // Absensi
            ['nama' => 'Absensi - Create', 'kode' => 'absensi.create', 'modul' => 'absensi', 'deskripsi' => 'Dapat input absensi'],
            ['nama' => 'Absensi - Edit', 'kode' => 'absensi.edit', 'modul' => 'absensi', 'deskripsi' => 'Dapat mengedit absensi'],
            ['nama' => 'Absensi - Delete', 'kode' => 'absensi.delete', 'modul' => 'absensi', 'deskripsi' => 'Dapat menghapus absensi'],
            ['nama' => 'Absensi - Export', 'kode' => 'absensi.export', 'modul' => 'absensi', 'deskripsi' => 'Dapat export absensi'],

            // Cuti
            ['nama' => 'Cuti - Create', 'kode' => 'cuti.create', 'modul' => 'cuti', 'deskripsi' => 'Dapat mengajukan cuti'],
            ['nama' => 'Cuti - Edit', 'kode' => 'cuti.edit', 'modul' => 'cuti', 'deskripsi' => 'Dapat mengedit pengajuan cuti'],
            ['nama' => 'Cuti - Delete', 'kode' => 'cuti.delete', 'modul' => 'cuti', 'deskripsi' => 'Dapat menghapus pengajuan cuti'],
            ['nama' => 'Cuti - Approve', 'kode' => 'cuti.approve', 'modul' => 'cuti', 'deskripsi' => 'Dapat approve cuti'],

            // Penggajian
            ['nama' => 'Penggajian - Create', 'kode' => 'penggajian.create', 'modul' => 'penggajian', 'deskripsi' => 'Dapat input penggajian'],
            ['nama' => 'Penggajian - Edit', 'kode' => 'penggajian.edit', 'modul' => 'penggajian', 'deskripsi' => 'Dapat mengedit penggajian'],
            ['nama' => 'Penggajian - Delete', 'kode' => 'penggajian.delete', 'modul' => 'penggajian', 'deskripsi' => 'Dapat menghapus penggajian'],
            ['nama' => 'Penggajian - Print', 'kode' => 'penggajian.print', 'modul' => 'penggajian', 'deskripsi' => 'Dapat print slip gaji'],

            // ===== INVENTARIS - ACTIONS =====
            // Stok Barang
            ['nama' => 'Stok Barang - Create', 'kode' => 'stok_barang.create', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat input stok'],
            ['nama' => 'Stok Barang - Edit', 'kode' => 'stok_barang.edit', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat mengedit stok'],
            ['nama' => 'Stok Barang - Export', 'kode' => 'stok_barang.export', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat export laporan stok'],

            // Penyesuaian Stok
            ['nama' => 'Penyesuaian Stok - Create', 'kode' => 'penyesuaian_stok.create', 'modul' => 'penyesuaian_stok', 'deskripsi' => 'Dapat input penyesuaian stok'],
            ['nama' => 'Penyesuaian Stok - Edit', 'kode' => 'penyesuaian_stok.edit', 'modul' => 'penyesuaian_stok', 'deskripsi' => 'Dapat mengedit penyesuaian stok'],
            ['nama' => 'Penyesuaian Stok - Delete', 'kode' => 'penyesuaian_stok.delete', 'modul' => 'penyesuaian_stok', 'deskripsi' => 'Dapat menghapus penyesuaian stok'],
            ['nama' => 'Penyesuaian Stok - Approve', 'kode' => 'penyesuaian_stok.approve', 'modul' => 'penyesuaian_stok', 'deskripsi' => 'Dapat approve penyesuaian stok'],

            // Transfer Gudang
            ['nama' => 'Transfer Gudang - Create', 'kode' => 'transfer_gudang.create', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat input transfer gudang'],
            ['nama' => 'Transfer Gudang - Edit', 'kode' => 'transfer_gudang.edit', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat mengedit transfer gudang'],
            ['nama' => 'Transfer Gudang - Delete', 'kode' => 'transfer_gudang.delete', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat menghapus transfer gudang'],
            ['nama' => 'Transfer Gudang - Approve', 'kode' => 'transfer_gudang.approve', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat approve transfer gudang'],

            // ===== PENGATURAN - ACTIONS =====
            // Management Pengguna
            ['nama' => 'Management Pengguna - Create', 'kode' => 'management_pengguna.create', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat menambah pengguna'],
            ['nama' => 'Management Pengguna - Edit', 'kode' => 'management_pengguna.edit', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat mengedit pengguna'],
            ['nama' => 'Management Pengguna - Delete', 'kode' => 'management_pengguna.delete', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat menghapus pengguna'],
            ['nama' => 'Management Pengguna - Reset Password', 'kode' => 'management_pengguna.reset_password', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat reset password pengguna'],

            // Hak Akses
            ['nama' => 'Hak Akses - Edit', 'kode' => 'hak_akses.edit', 'modul' => 'hak_akses', 'deskripsi' => 'Dapat mengedit hak akses'],

            // Pengaturan Umum
            ['nama' => 'Pengaturan Umum - Edit', 'kode' => 'pengaturan_umum.edit', 'modul' => 'pengaturan_umum', 'deskripsi' => 'Dapat mengedit pengaturan umum'],

            // ===== LAPORAN - EXPORT =====
            ['nama' => 'Laporan Penjualan - Export', 'kode' => 'laporan_penjualan.export', 'modul' => 'laporan_penjualan', 'deskripsi' => 'Dapat export laporan penjualan'],
            ['nama' => 'Laporan Pembelian - Export', 'kode' => 'laporan_pembelian.export', 'modul' => 'laporan_pembelian', 'deskripsi' => 'Dapat export laporan pembelian'],
            ['nama' => 'Laporan Stok - Export', 'kode' => 'laporan_stok.export', 'modul' => 'laporan_stok', 'deskripsi' => 'Dapat export laporan stok'],
            ['nama' => 'Laporan Produksi - Export', 'kode' => 'laporan_produksi.export', 'modul' => 'laporan_produksi', 'deskripsi' => 'Dapat export laporan produksi'],
            ['nama' => 'Laporan Keuangan - Export', 'kode' => 'laporan_keuangan.export', 'modul' => 'laporan_keuangan', 'deskripsi' => 'Dapat export laporan keuangan'],

            // ===== CRM - ACTIONS =====
            ['nama' => 'Prospek Lead - Create', 'kode' => 'prospek_lead.create', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat menambah prospek'],
            ['nama' => 'Prospek Lead - Edit', 'kode' => 'prospek_lead.edit', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat mengedit prospek'],
            ['nama' => 'Prospek Lead - Delete', 'kode' => 'prospek_lead.delete', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat menghapus prospek'],

            ['nama' => 'Aktivitas Prospek - Create', 'kode' => 'aktivitas_prospek.create', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat menambah aktivitas'],
            ['nama' => 'Aktivitas Prospek - Edit', 'kode' => 'aktivitas_prospek.edit', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat mengedit aktivitas'],
            ['nama' => 'Aktivitas Prospek - Delete', 'kode' => 'aktivitas_prospek.delete', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat menghapus aktivitas'],

            // ===== ADDITIONAL MISSING PERMISSIONS =====
            // Retur
            ['nama' => 'Retur Penjualan - Create', 'kode' => 'retur_penjualan.create', 'modul' => 'retur_penjualan', 'deskripsi' => 'Dapat membuat retur penjualan'],
            ['nama' => 'Retur Penjualan - Edit', 'kode' => 'retur_penjualan.edit', 'modul' => 'retur_penjualan', 'deskripsi' => 'Dapat mengedit retur penjualan'],
            ['nama' => 'Retur Penjualan - Delete', 'kode' => 'retur_penjualan.delete', 'modul' => 'retur_penjualan', 'deskripsi' => 'Dapat menghapus retur penjualan'],

            ['nama' => 'Retur Pembelian - Create', 'kode' => 'retur_pembelian.create', 'modul' => 'retur_pembelian', 'deskripsi' => 'Dapat membuat retur pembelian'],
            ['nama' => 'Retur Pembelian - Edit', 'kode' => 'retur_pembelian.edit', 'modul' => 'retur_pembelian', 'deskripsi' => 'Dapat mengedit retur pembelian'],
            ['nama' => 'Retur Pembelian - Delete', 'kode' => 'retur_pembelian.delete', 'modul' => 'retur_pembelian', 'deskripsi' => 'Dapat menghapus retur pembelian'],

            // Nota Kredit
            ['nama' => 'Nota Kredit - Create', 'kode' => 'nota_kredit.create', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat membuat nota kredit'],
            ['nama' => 'Nota Kredit - Edit', 'kode' => 'nota_kredit.edit', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat mengedit nota kredit'],
            ['nama' => 'Nota Kredit - Delete', 'kode' => 'nota_kredit.delete', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat menghapus nota kredit'],

            // Department
            ['nama' => 'Department - View', 'kode' => 'department.view', 'modul' => 'department', 'deskripsi' => 'Dapat melihat department'],
            ['nama' => 'Department - Create', 'kode' => 'department.create', 'modul' => 'department', 'deskripsi' => 'Dapat menambah department'],
            ['nama' => 'Department - Edit', 'kode' => 'department.edit', 'modul' => 'department', 'deskripsi' => 'Dapat mengedit department'],
            ['nama' => 'Department - Delete', 'kode' => 'department.delete', 'modul' => 'department', 'deskripsi' => 'Dapat menghapus department'],

            // Struktur Organisasi
            ['nama' => 'Struktur Organisasi - Create', 'kode' => 'struktur_organisasi.create', 'modul' => 'struktur_organisasi', 'deskripsi' => 'Dapat menambah struktur organisasi'],
            ['nama' => 'Struktur Organisasi - Edit', 'kode' => 'struktur_organisasi.edit', 'modul' => 'struktur_organisasi', 'deskripsi' => 'Dapat mengedit struktur organisasi'],
            ['nama' => 'Struktur Organisasi - Delete', 'kode' => 'struktur_organisasi.delete', 'modul' => 'struktur_organisasi', 'deskripsi' => 'Dapat menghapus struktur organisasi'],

            // Management Pajak
            ['nama' => 'Management Pajak - Create', 'kode' => 'management_pajak.create', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat input pajak'],
            ['nama' => 'Management Pajak - Edit', 'kode' => 'management_pajak.edit', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat mengedit pajak'],
            ['nama' => 'Management Pajak - Delete', 'kode' => 'management_pajak.delete', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat menghapus pajak'],

            // Rekonsiliasi Bank
            ['nama' => 'Rekonsiliasi Bank - Create', 'kode' => 'rekonsiliasi_bank.create', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat input rekonsiliasi'],
            ['nama' => 'Rekonsiliasi Bank - Edit', 'kode' => 'rekonsiliasi_bank.edit', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat mengedit rekonsiliasi'],
            ['nama' => 'Rekonsiliasi Bank - Delete', 'kode' => 'rekonsiliasi_bank.delete', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat menghapus rekonsiliasi'],

            // Pipeline Penjualan
            ['nama' => 'Pipeline Penjualan - Create', 'kode' => 'pipeline_penjualan.create', 'modul' => 'pipeline_penjualan', 'deskripsi' => 'Dapat menambah pipeline'],
            ['nama' => 'Pipeline Penjualan - Edit', 'kode' => 'pipeline_penjualan.edit', 'modul' => 'pipeline_penjualan', 'deskripsi' => 'Dapat mengedit pipeline'],
            ['nama' => 'Pipeline Penjualan - Delete', 'kode' => 'pipeline_penjualan.delete', 'modul' => 'pipeline_penjualan', 'deskripsi' => 'Dapat menghapus pipeline'],

            // Permintaan Barang
            ['nama' => 'Permintaan Barang - Create', 'kode' => 'permintaan_barang.create', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat membuat permintaan barang'],
            ['nama' => 'Permintaan Barang - Edit', 'kode' => 'permintaan_barang.edit', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat mengedit permintaan barang'],
            ['nama' => 'Permintaan Barang - Delete', 'kode' => 'permintaan_barang.delete', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat menghapus permintaan barang'],
            ['nama' => 'Permintaan Barang - Approve', 'kode' => 'permintaan_barang.approve', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat approve permintaan barang'],
        ];

        $addedCount = 0;
        foreach ($additionalPermissions as $permissionData) {
            // Check if permission already exists
            $existingPermission = Permission::where('kode', $permissionData['kode'])->first();

            if (!$existingPermission) {
                Permission::create($permissionData);
                $addedCount++;
                echo "✓ Menambahkan permission: {$permissionData['nama']}" . PHP_EOL;
            } else {
                echo "- Skip (sudah ada): {$permissionData['nama']}" . PHP_EOL;
            }
        }

        echo PHP_EOL . "=== SELESAI ===" . PHP_EOL;
        echo "Total permission baru yang ditambahkan: {$addedCount}" . PHP_EOL;
        echo "Total permission di database sekarang: " . Permission::count() . PHP_EOL;
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class ComprehensivePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Menambahkan permission baru yang belum ada..." . PHP_EOL;

        // Daftar permission baru yang akan ditambahkan (yang belum ada)
        $newPermissions = [
            // ===== MASTER DATA - Tambahan =====
            ['nama' => 'Produk - Import', 'kode' => 'produk.import', 'modul' => 'produk', 'deskripsi' => 'Dapat import data produk'],
            ['nama' => 'Produk - Export', 'kode' => 'produk.export', 'modul' => 'produk', 'deskripsi' => 'Dapat export data produk'],
            ['nama' => 'Produk - Bulk Delete', 'kode' => 'produk.bulk_delete', 'modul' => 'produk', 'deskripsi' => 'Dapat bulk delete produk'],
            ['nama' => 'Produk - Generate Code', 'kode' => 'produk.generate_code', 'modul' => 'produk', 'deskripsi' => 'Dapat generate kode produk'],
            ['nama' => 'Produk - Template', 'kode' => 'produk.template', 'modul' => 'produk', 'deskripsi' => 'Dapat download template produk'],

            ['nama' => 'Kategori Produk - Bulk Delete', 'kode' => 'kategori_produk.bulk_delete', 'modul' => 'kategori_produk', 'deskripsi' => 'Dapat bulk delete kategori produk'],

            ['nama' => 'Pelanggan - View', 'kode' => 'pelanggan.view', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat melihat data pelanggan'],
            ['nama' => 'Pelanggan - Create', 'kode' => 'pelanggan.create', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat membuat data pelanggan'],
            ['nama' => 'Pelanggan - Edit', 'kode' => 'pelanggan.edit', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat edit data pelanggan'],
            ['nama' => 'Pelanggan - Delete', 'kode' => 'pelanggan.delete', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat hapus data pelanggan'],
            ['nama' => 'Pelanggan - Import', 'kode' => 'pelanggan.import', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat import data pelanggan'],
            ['nama' => 'Pelanggan - Export', 'kode' => 'pelanggan.export', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat export data pelanggan'],
            ['nama' => 'Pelanggan - Bulk Delete', 'kode' => 'pelanggan.bulk_delete', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat bulk delete pelanggan'],
            ['nama' => 'Pelanggan - Generate Code', 'kode' => 'pelanggan.generate_code', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat generate kode pelanggan'],
            ['nama' => 'Pelanggan - Get Sales Users', 'kode' => 'pelanggan.get_sales_users', 'modul' => 'pelanggan', 'deskripsi' => 'Dapat get sales users'],

            ['nama' => 'Supplier - Import', 'kode' => 'supplier.import', 'modul' => 'supplier', 'deskripsi' => 'Dapat import data supplier'],
            ['nama' => 'Supplier - Export', 'kode' => 'supplier.export', 'modul' => 'supplier', 'deskripsi' => 'Dapat export data supplier'],
            ['nama' => 'Supplier - Bulk Delete', 'kode' => 'supplier.bulk_delete', 'modul' => 'supplier', 'deskripsi' => 'Dapat bulk delete supplier'],
            ['nama' => 'Supplier - Generate Code', 'kode' => 'supplier.generate_code', 'modul' => 'supplier', 'deskripsi' => 'Dapat generate kode supplier'],

            ['nama' => 'Gudang - Bulk Delete', 'kode' => 'gudang.bulk_delete', 'modul' => 'gudang', 'deskripsi' => 'Dapat bulk delete gudang'],

            ['nama' => 'Satuan - View', 'kode' => 'satuan.view', 'modul' => 'satuan', 'deskripsi' => 'Dapat melihat data satuan'],
            ['nama' => 'Satuan - Create', 'kode' => 'satuan.create', 'modul' => 'satuan', 'deskripsi' => 'Dapat membuat data satuan'],
            ['nama' => 'Satuan - Edit', 'kode' => 'satuan.edit', 'modul' => 'satuan', 'deskripsi' => 'Dapat edit data satuan'],
            ['nama' => 'Satuan - Delete', 'kode' => 'satuan.delete', 'modul' => 'satuan', 'deskripsi' => 'Dapat hapus data satuan'],
            ['nama' => 'Satuan - Bulk Delete', 'kode' => 'satuan.bulk_delete', 'modul' => 'satuan', 'deskripsi' => 'Dapat bulk delete satuan'],

            // ===== PENJUALAN - Tambahan =====
            ['nama' => 'Quotation - Print', 'kode' => 'quotation.print', 'modul' => 'quotation', 'deskripsi' => 'Dapat print quotation'],
            ['nama' => 'Quotation - Export PDF', 'kode' => 'quotation.export_pdf', 'modul' => 'quotation', 'deskripsi' => 'Dapat export PDF quotation'],
            ['nama' => 'Quotation - Change Status', 'kode' => 'quotation.change_status', 'modul' => 'quotation', 'deskripsi' => 'Dapat ubah status quotation'],

            ['nama' => 'Sales Order - Print', 'kode' => 'sales_order.print', 'modul' => 'sales_order', 'deskripsi' => 'Dapat print sales order'],
            ['nama' => 'Sales Order - Export PDF', 'kode' => 'sales_order.export_pdf', 'modul' => 'sales_order', 'deskripsi' => 'Dapat export PDF sales order'],
            ['nama' => 'Sales Order - Change Status', 'kode' => 'sales_order.change_status', 'modul' => 'sales_order', 'deskripsi' => 'Dapat ubah status sales order'],
            ['nama' => 'Sales Order - Generate Invoice', 'kode' => 'sales_order.generate_invoice', 'modul' => 'sales_order', 'deskripsi' => 'Dapat generate invoice dari sales order'],

            ['nama' => 'Delivery Order - Print', 'kode' => 'delivery_order.print', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat print delivery order'],
            ['nama' => 'Delivery Order - Process', 'kode' => 'delivery_order.process', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat proses delivery order'],
            ['nama' => 'Delivery Order - Complete', 'kode' => 'delivery_order.complete', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat complete delivery order'],
            ['nama' => 'Delivery Order - Cancel', 'kode' => 'delivery_order.cancel', 'modul' => 'delivery_order', 'deskripsi' => 'Dapat cancel delivery order'],

            ['nama' => 'Invoice - Print', 'kode' => 'invoice.print', 'modul' => 'invoice', 'deskripsi' => 'Dapat print invoice'],
            ['nama' => 'Invoice - Send', 'kode' => 'invoice.send', 'modul' => 'invoice', 'deskripsi' => 'Dapat send invoice'],

            ['nama' => 'Retur Penjualan - Process', 'kode' => 'retur_penjualan.process', 'modul' => 'retur_penjualan', 'deskripsi' => 'Dapat proses retur penjualan'],
            ['nama' => 'Retur Penjualan - Approve', 'kode' => 'retur_penjualan.approve', 'modul' => 'retur_penjualan', 'deskripsi' => 'Dapat approve retur penjualan'],
            ['nama' => 'Retur Penjualan - Print', 'kode' => 'retur_penjualan.print', 'modul' => 'retur_penjualan', 'deskripsi' => 'Dapat print retur penjualan'],

            ['nama' => 'Nota Kredit - View', 'kode' => 'nota_kredit.view', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat melihat nota kredit'],
            ['nama' => 'Nota Kredit - Create', 'kode' => 'nota_kredit.create', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat membuat nota kredit'],
            ['nama' => 'Nota Kredit - Edit', 'kode' => 'nota_kredit.edit', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat edit nota kredit'],
            ['nama' => 'Nota Kredit - Delete', 'kode' => 'nota_kredit.delete', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat hapus nota kredit'],
            ['nama' => 'Nota Kredit - Print', 'kode' => 'nota_kredit.print', 'modul' => 'nota_kredit', 'deskripsi' => 'Dapat print nota kredit'],

            // ===== PEMBELIAN - Tambahan =====
            ['nama' => 'Purchase Order - Print', 'kode' => 'purchase_order.print', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat print purchase order'],
            ['nama' => 'Purchase Order - Change Status', 'kode' => 'purchase_order.change_status', 'modul' => 'purchase_order', 'deskripsi' => 'Dapat ubah status purchase order'],

            ['nama' => 'Purchase Request - Print', 'kode' => 'purchase_request.print', 'modul' => 'purchase_request', 'deskripsi' => 'Dapat print purchase request'],
            ['nama' => 'Purchase Request - Change Status', 'kode' => 'purchase_request.change_status', 'modul' => 'purchase_request', 'deskripsi' => 'Dapat ubah status purchase request'],

            ['nama' => 'Penerimaan Barang - Print', 'kode' => 'penerimaan_barang.print', 'modul' => 'penerimaan_barang', 'deskripsi' => 'Dapat print penerimaan barang'],

            ['nama' => 'Retur Pembelian - Process', 'kode' => 'retur_pembelian.process', 'modul' => 'retur_pembelian', 'deskripsi' => 'Dapat proses retur pembelian'],
            ['nama' => 'Retur Pembelian - Print', 'kode' => 'retur_pembelian.print', 'modul' => 'retur_pembelian', 'deskripsi' => 'Dapat print retur pembelian'],

            // ===== INVENTARIS - Baru =====
            ['nama' => 'Stok Barang - View', 'kode' => 'stok_barang.view', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat melihat stok barang'],
            ['nama' => 'Stok Barang - Create', 'kode' => 'stok_barang.create', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat membuat stok barang'],
            ['nama' => 'Stok Barang - Edit', 'kode' => 'stok_barang.edit', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat edit stok barang'],
            ['nama' => 'Stok Barang - Delete', 'kode' => 'stok_barang.delete', 'modul' => 'stok_barang', 'deskripsi' => 'Dapat hapus stok barang'],

            ['nama' => 'Transfer Gudang - View', 'kode' => 'transfer_gudang.view', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat melihat transfer gudang'],
            ['nama' => 'Transfer Gudang - Create', 'kode' => 'transfer_gudang.create', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat membuat transfer gudang'],
            ['nama' => 'Transfer Gudang - Edit', 'kode' => 'transfer_gudang.edit', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat edit transfer gudang'],
            ['nama' => 'Transfer Gudang - Delete', 'kode' => 'transfer_gudang.delete', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat hapus transfer gudang'],
            ['nama' => 'Transfer Gudang - Process', 'kode' => 'transfer_gudang.process', 'modul' => 'transfer_gudang', 'deskripsi' => 'Dapat proses transfer gudang'],

            ['nama' => 'Penyesuaian Stok - Process', 'kode' => 'penyesuaian_stok.process', 'modul' => 'penyesuaian_stok', 'deskripsi' => 'Dapat proses penyesuaian stok'],
            ['nama' => 'Penyesuaian Stok - Print', 'kode' => 'penyesuaian_stok.print', 'modul' => 'penyesuaian_stok', 'deskripsi' => 'Dapat print penyesuaian stok'],

            ['nama' => 'Permintaan Barang - View', 'kode' => 'permintaan_barang.view', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat melihat permintaan barang'],
            ['nama' => 'Permintaan Barang - Create', 'kode' => 'permintaan_barang.create', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat membuat permintaan barang'],
            ['nama' => 'Permintaan Barang - Edit', 'kode' => 'permintaan_barang.edit', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat edit permintaan barang'],
            ['nama' => 'Permintaan Barang - Delete', 'kode' => 'permintaan_barang.delete', 'modul' => 'permintaan_barang', 'deskripsi' => 'Dapat hapus permintaan barang'],

            // ===== PRODUKSI - Tambahan =====
            ['nama' => 'Bill Of Material - Add Component', 'kode' => 'bill_of_material.add_component', 'modul' => 'bill_of_material', 'deskripsi' => 'Dapat menambah komponen BOM'],
            ['nama' => 'Bill Of Material - Edit Component', 'kode' => 'bill_of_material.edit_component', 'modul' => 'bill_of_material', 'deskripsi' => 'Dapat edit komponen BOM'],
            ['nama' => 'Bill Of Material - Delete Component', 'kode' => 'bill_of_material.delete_component', 'modul' => 'bill_of_material', 'deskripsi' => 'Dapat hapus komponen BOM'],

            ['nama' => 'Perencanaan Produksi - View', 'kode' => 'perencanaan_produksi.view', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat melihat perencanaan produksi'],
            ['nama' => 'Perencanaan Produksi - Create', 'kode' => 'perencanaan_produksi.create', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat membuat perencanaan produksi'],
            ['nama' => 'Perencanaan Produksi - Edit', 'kode' => 'perencanaan_produksi.edit', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat edit perencanaan produksi'],
            ['nama' => 'Perencanaan Produksi - Delete', 'kode' => 'perencanaan_produksi.delete', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat hapus perencanaan produksi'],
            ['nama' => 'Perencanaan Produksi - Approve', 'kode' => 'perencanaan_produksi.approve', 'modul' => 'perencanaan_produksi', 'deskripsi' => 'Dapat approve perencanaan produksi'],

            ['nama' => 'Work Order - Change Status', 'kode' => 'work_order.change_status', 'modul' => 'work_order', 'deskripsi' => 'Dapat ubah status work order'],

            ['nama' => 'Quality Control - View', 'kode' => 'quality_control.view', 'modul' => 'quality_control', 'deskripsi' => 'Dapat melihat quality control'],
            ['nama' => 'Quality Control - Create', 'kode' => 'quality_control.create', 'modul' => 'quality_control', 'deskripsi' => 'Dapat membuat quality control'],
            ['nama' => 'Quality Control - Edit', 'kode' => 'quality_control.edit', 'modul' => 'quality_control', 'deskripsi' => 'Dapat edit quality control'],
            ['nama' => 'Quality Control - Delete', 'kode' => 'quality_control.delete', 'modul' => 'quality_control', 'deskripsi' => 'Dapat hapus quality control'],
            ['nama' => 'Quality Control - Approve', 'kode' => 'quality_control.approve', 'modul' => 'quality_control', 'deskripsi' => 'Dapat approve quality control'],
            ['nama' => 'Quality Control - Print', 'kode' => 'quality_control.print', 'modul' => 'quality_control', 'deskripsi' => 'Dapat print quality control'],

            // ===== KEUANGAN - Baru =====
            ['nama' => 'Chart Of Accounts - View', 'kode' => 'chart_of_accounts.view', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat melihat chart of accounts'],
            ['nama' => 'Chart Of Accounts - Create', 'kode' => 'chart_of_accounts.create', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat membuat chart of accounts'],
            ['nama' => 'Chart Of Accounts - Edit', 'kode' => 'chart_of_accounts.edit', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat edit chart of accounts'],
            ['nama' => 'Chart Of Accounts - Delete', 'kode' => 'chart_of_accounts.delete', 'modul' => 'chart_of_accounts', 'deskripsi' => 'Dapat hapus chart of accounts'],

            ['nama' => 'Jurnal Umum - View', 'kode' => 'jurnal_umum.view', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat melihat jurnal umum'],
            ['nama' => 'Jurnal Umum - Create', 'kode' => 'jurnal_umum.create', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat membuat jurnal umum'],
            ['nama' => 'Jurnal Umum - Edit', 'kode' => 'jurnal_umum.edit', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat edit jurnal umum'],
            ['nama' => 'Jurnal Umum - Delete', 'kode' => 'jurnal_umum.delete', 'modul' => 'jurnal_umum', 'deskripsi' => 'Dapat hapus jurnal umum'],

            ['nama' => 'Kas Dan Bank - View', 'kode' => 'kas_dan_bank.view', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat melihat kas dan bank'],
            ['nama' => 'Kas Dan Bank - Create', 'kode' => 'kas_dan_bank.create', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat membuat kas dan bank'],
            ['nama' => 'Kas Dan Bank - Edit', 'kode' => 'kas_dan_bank.edit', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat edit kas dan bank'],
            ['nama' => 'Kas Dan Bank - Delete', 'kode' => 'kas_dan_bank.delete', 'modul' => 'kas_dan_bank', 'deskripsi' => 'Dapat hapus kas dan bank'],

            ['nama' => 'Piutang Usaha - View', 'kode' => 'piutang_usaha.view', 'modul' => 'piutang_usaha', 'deskripsi' => 'Dapat melihat piutang usaha'],
            ['nama' => 'Piutang Usaha - Print', 'kode' => 'piutang_usaha.print', 'modul' => 'piutang_usaha', 'deskripsi' => 'Dapat print piutang usaha'],
            ['nama' => 'Piutang Usaha - Export', 'kode' => 'piutang_usaha.export', 'modul' => 'piutang_usaha', 'deskripsi' => 'Dapat export piutang usaha'],

            ['nama' => 'Hutang Usaha - View', 'kode' => 'hutang_usaha.view', 'modul' => 'hutang_usaha', 'deskripsi' => 'Dapat melihat hutang usaha'],
            ['nama' => 'Hutang Usaha - Print', 'kode' => 'hutang_usaha.print', 'modul' => 'hutang_usaha', 'deskripsi' => 'Dapat print hutang usaha'],
            ['nama' => 'Hutang Usaha - Export', 'kode' => 'hutang_usaha.export', 'modul' => 'hutang_usaha', 'deskripsi' => 'Dapat export hutang usaha'],

            ['nama' => 'Pembayaran Piutang - Print', 'kode' => 'pembayaran_piutang.print', 'modul' => 'pembayaran_piutang', 'deskripsi' => 'Dapat print pembayaran piutang'],
            ['nama' => 'Pembayaran Hutang - Print', 'kode' => 'pembayaran_hutang.print', 'modul' => 'pembayaran_hutang', 'deskripsi' => 'Dapat print pembayaran hutang'],

            ['nama' => 'Management Pajak - View', 'kode' => 'management_pajak.view', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat melihat management pajak'],
            ['nama' => 'Management Pajak - Create', 'kode' => 'management_pajak.create', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat membuat management pajak'],
            ['nama' => 'Management Pajak - Edit', 'kode' => 'management_pajak.edit', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat edit management pajak'],
            ['nama' => 'Management Pajak - Delete', 'kode' => 'management_pajak.delete', 'modul' => 'management_pajak', 'deskripsi' => 'Dapat hapus management pajak'],

            ['nama' => 'Rekonsiliasi Bank - View', 'kode' => 'rekonsiliasi_bank.view', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat melihat rekonsiliasi bank'],
            ['nama' => 'Rekonsiliasi Bank - Create', 'kode' => 'rekonsiliasi_bank.create', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat membuat rekonsiliasi bank'],
            ['nama' => 'Rekonsiliasi Bank - Edit', 'kode' => 'rekonsiliasi_bank.edit', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat edit rekonsiliasi bank'],
            ['nama' => 'Rekonsiliasi Bank - Delete', 'kode' => 'rekonsiliasi_bank.delete', 'modul' => 'rekonsiliasi_bank', 'deskripsi' => 'Dapat hapus rekonsiliasi bank'],

            // ===== HR & KARYAWAN - Tambahan =====
            ['nama' => 'Karyawan - Bulk Delete', 'kode' => 'karyawan.bulk_delete', 'modul' => 'karyawan', 'deskripsi' => 'Dapat bulk delete karyawan'],

            ['nama' => 'Struktur Organisasi - View', 'kode' => 'struktur_organisasi.view', 'modul' => 'struktur_organisasi', 'deskripsi' => 'Dapat melihat struktur organisasi'],
            ['nama' => 'Struktur Organisasi - Edit', 'kode' => 'struktur_organisasi.edit', 'modul' => 'struktur_organisasi', 'deskripsi' => 'Dapat edit struktur organisasi'],

            ['nama' => 'Absensi - Import', 'kode' => 'absensi.import', 'modul' => 'absensi', 'deskripsi' => 'Dapat import absensi'],
            ['nama' => 'Absensi - Export', 'kode' => 'absensi.export', 'modul' => 'absensi', 'deskripsi' => 'Dapat export absensi'],

            ['nama' => 'Cuti - View', 'kode' => 'cuti.view', 'modul' => 'cuti', 'deskripsi' => 'Dapat melihat cuti'],
            ['nama' => 'Cuti - Create', 'kode' => 'cuti.create', 'modul' => 'cuti', 'deskripsi' => 'Dapat membuat cuti'],
            ['nama' => 'Cuti - Edit', 'kode' => 'cuti.edit', 'modul' => 'cuti', 'deskripsi' => 'Dapat edit cuti'],
            ['nama' => 'Cuti - Delete', 'kode' => 'cuti.delete', 'modul' => 'cuti', 'deskripsi' => 'Dapat hapus cuti'],

            // ===== CRM - Baru =====
            ['nama' => 'Prospek Lead - View', 'kode' => 'prospek_lead.view', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat melihat prospek lead'],
            ['nama' => 'Prospek Lead - Create', 'kode' => 'prospek_lead.create', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat membuat prospek lead'],
            ['nama' => 'Prospek Lead - Edit', 'kode' => 'prospek_lead.edit', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat edit prospek lead'],
            ['nama' => 'Prospek Lead - Delete', 'kode' => 'prospek_lead.delete', 'modul' => 'prospek_lead', 'deskripsi' => 'Dapat hapus prospek lead'],

            ['nama' => 'Aktivitas Prospek - View', 'kode' => 'aktivitas_prospek.view', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat melihat aktivitas prospek'],
            ['nama' => 'Aktivitas Prospek - Create', 'kode' => 'aktivitas_prospek.create', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat membuat aktivitas prospek'],
            ['nama' => 'Aktivitas Prospek - Edit', 'kode' => 'aktivitas_prospek.edit', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat edit aktivitas prospek'],
            ['nama' => 'Aktivitas Prospek - Delete', 'kode' => 'aktivitas_prospek.delete', 'modul' => 'aktivitas_prospek', 'deskripsi' => 'Dapat hapus aktivitas prospek'],

            ['nama' => 'Pipeline Penjualan - View', 'kode' => 'pipeline_penjualan.view', 'modul' => 'pipeline_penjualan', 'deskripsi' => 'Dapat melihat pipeline penjualan'],
            ['nama' => 'Pipeline Penjualan - Export', 'kode' => 'pipeline_penjualan.export', 'modul' => 'pipeline_penjualan', 'deskripsi' => 'Dapat export pipeline penjualan'],

            // ===== LAPORAN - Baru =====
            ['nama' => 'Laporan Stok - View', 'kode' => 'laporan_stok.view', 'modul' => 'laporan_stok', 'deskripsi' => 'Dapat melihat laporan stok'],
            ['nama' => 'Laporan Stok - Export', 'kode' => 'laporan_stok.export', 'modul' => 'laporan_stok', 'deskripsi' => 'Dapat export laporan stok'],

            ['nama' => 'Laporan Penjualan - View', 'kode' => 'laporan_penjualan.view', 'modul' => 'laporan_penjualan', 'deskripsi' => 'Dapat melihat laporan penjualan'],
            ['nama' => 'Laporan Penjualan - Export', 'kode' => 'laporan_penjualan.export', 'modul' => 'laporan_penjualan', 'deskripsi' => 'Dapat export laporan penjualan'],

            ['nama' => 'Laporan Pembelian - View', 'kode' => 'laporan_pembelian.view', 'modul' => 'laporan_pembelian', 'deskripsi' => 'Dapat melihat laporan pembelian'],
            ['nama' => 'Laporan Pembelian - Export', 'kode' => 'laporan_pembelian.export', 'modul' => 'laporan_pembelian', 'deskripsi' => 'Dapat export laporan pembelian'],

            ['nama' => 'Laporan Produksi - View', 'kode' => 'laporan_produksi.view', 'modul' => 'laporan_produksi', 'deskripsi' => 'Dapat melihat laporan produksi'],
            ['nama' => 'Laporan Produksi - Export', 'kode' => 'laporan_produksi.export', 'modul' => 'laporan_produksi', 'deskripsi' => 'Dapat export laporan produksi'],

            ['nama' => 'Laporan Keuangan - View', 'kode' => 'laporan_keuangan.view', 'modul' => 'laporan_keuangan', 'deskripsi' => 'Dapat melihat laporan keuangan'],
            ['nama' => 'Laporan Keuangan - Export', 'kode' => 'laporan_keuangan.export', 'modul' => 'laporan_keuangan', 'deskripsi' => 'Dapat export laporan keuangan'],

            // ===== PENGATURAN - Baru =====
            ['nama' => 'Management Pengguna - View', 'kode' => 'management_pengguna.view', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat melihat management pengguna'],
            ['nama' => 'Management Pengguna - Create', 'kode' => 'management_pengguna.create', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat membuat management pengguna'],
            ['nama' => 'Management Pengguna - Edit', 'kode' => 'management_pengguna.edit', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat edit management pengguna'],
            ['nama' => 'Management Pengguna - Delete', 'kode' => 'management_pengguna.delete', 'modul' => 'management_pengguna', 'deskripsi' => 'Dapat hapus management pengguna'],

            ['nama' => 'Hak Akses - View', 'kode' => 'hak_akses.view', 'modul' => 'hak_akses', 'deskripsi' => 'Dapat melihat hak akses'],
            ['nama' => 'Hak Akses - Update', 'kode' => 'hak_akses.update', 'modul' => 'hak_akses', 'deskripsi' => 'Dapat update hak akses'],

            ['nama' => 'Pengaturan Umum - View', 'kode' => 'pengaturan_umum.view', 'modul' => 'pengaturan_umum', 'deskripsi' => 'Dapat melihat pengaturan umum'],
            ['nama' => 'Pengaturan Umum - Update', 'kode' => 'pengaturan_umum.update', 'modul' => 'pengaturan_umum', 'deskripsi' => 'Dapat update pengaturan umum'],

            ['nama' => 'Log Aktivitas - View', 'kode' => 'log_aktivitas.view', 'modul' => 'log_aktivitas', 'deskripsi' => 'Dapat melihat log aktivitas'],
            ['nama' => 'Log Aktivitas - Export', 'kode' => 'log_aktivitas.export', 'modul' => 'log_aktivitas', 'deskripsi' => 'Dapat export log aktivitas'],

            // ===== DASHBOARD & SISTEM =====
            ['nama' => 'Dashboard - View', 'kode' => 'dashboard.view', 'modul' => 'dashboard', 'deskripsi' => 'Dapat melihat dashboard'],
            ['nama' => 'Notifikasi - View', 'kode' => 'notifikasi.view', 'modul' => 'notifikasi', 'deskripsi' => 'Dapat melihat notifikasi'],
            ['nama' => 'Profile - View', 'kode' => 'profile.view', 'modul' => 'profile', 'deskripsi' => 'Dapat melihat profile'],
            ['nama' => 'Profile - Edit', 'kode' => 'profile.edit', 'modul' => 'profile', 'deskripsi' => 'Dapat edit profile'],
        ];

        $addedCount = 0;
        foreach ($newPermissions as $permissionData) {
            // Cek apakah permission sudah ada
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

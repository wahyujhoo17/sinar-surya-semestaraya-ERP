<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProdukExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Produk::with(['kategori', 'satuan'])->get();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Jenis',
            'SKU',
            'Kategori',
            'Merek',
            'Sub Kategori',
            'Satuan',
            'Ukuran',
            'Tipe Material',
            'Kualitas',
            'Harga Beli',
            'Harga Jual',
            'Stok Minimum',
            'Status'
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->kode,
            $produk->nama,
            $produk->jenis->nama ?? '',
            $produk->product_sku,
            $produk->kategori->nama ?? '',
            $produk->merek,
            $produk->sub_kategori,
            $produk->satuan->nama ?? '',
            $produk->ukuran,
            $produk->type_material,
            $produk->kualitas,
            $produk->harga_beli,
            $produk->harga_jual,
            $produk->stok_minimum,
            $produk->is_active ? 'Aktif' : 'Nonaktif'
        ];
    }
}

<?php

namespace App\Services;

use App\Models\DeliveryOrder;
use App\Models\Invoice;
use App\Models\PembayaranHutang;
use App\Models\PembayaranPiutang;
use App\Models\PenerimaanBarang;
use App\Models\TransaksiBank;
use App\Models\TransaksiKas;
use Illuminate\Support\Collection;

/**
 * Melengkapi baris jurnal dengan nama customer/supplier dan dokumen terkait
 * (Invoice, Sales Order, Purchase Order) berdasarkan ref_type/ref_id.
 */
class JurnalReferensiResolver
{
    /**
     * Relasi yang di-eager load per tipe referensi agar tidak terjadi N+1 query.
     */
    protected const RELATIONS = [
        Invoice::class => ['customer', 'salesOrder'],
        PembayaranPiutang::class => ['customer', 'invoice.salesOrder', 'invoices.salesOrder'],
        PembayaranHutang::class => ['supplier', 'purchaseOrder', 'purchaseOrders'],
        PenerimaanBarang::class => ['supplier', 'purchaseOrder'],
        DeliveryOrder::class => ['customer', 'salesOrder'],
        TransaksiKas::class => [],
        TransaksiBank::class => [],
    ];

    /** @var array<string, \Illuminate\Database\Eloquent\Model> */
    protected array $models = [];

    /**
     * @param iterable $transaksi koleksi JurnalUmum
     */
    public function __construct(iterable $transaksi)
    {
        $ids = [];
        foreach ($transaksi as $trx) {
            if ($trx->ref_type && $trx->ref_id && array_key_exists($trx->ref_type, self::RELATIONS)) {
                $ids[$trx->ref_type][$trx->ref_id] = true;
            }
        }

        foreach ($ids as $type => $typeIds) {
            $type::with(self::RELATIONS[$type])
                ->whereIn('id', array_keys($typeIds))
                ->get()
                ->each(function ($model) use ($type) {
                    $this->models[$type . '#' . $model->id] = $model;
                });
        }
    }

    /**
     * @return array{pihak: string, dokumen: string}
     */
    public function resolve($trx): array
    {
        $model = $this->models[$trx->ref_type . '#' . $trx->ref_id] ?? null;

        if (!$model) {
            return ['pihak' => '-', 'dokumen' => '-'];
        }

        // Transaksi kas/bank manual: pakai nama penerima jika ada,
        // atau dokumen sumbernya jika berasal dari pembayaran piutang/hutang
        if ($model instanceof TransaksiKas || $model instanceof TransaksiBank) {
            $related = $model->related_type && array_key_exists($model->related_type, self::RELATIONS)
                ? $model->related_type::with(self::RELATIONS[$model->related_type])->find($model->related_id)
                : null;

            if ($related && !($related instanceof TransaksiKas || $related instanceof TransaksiBank)) {
                return $this->describe($related);
            }

            return ['pihak' => $model->nama_penerima ?: '-', 'dokumen' => '-'];
        }

        return $this->describe($model);
    }

    protected function describe($model): array
    {
        $pihak = null;
        $dokumen = [];

        if ($model instanceof Invoice) {
            $pihak = $this->customerName($model->customer);
            $dokumen[] = $this->label('SO', $model->salesOrder?->nomor);
        } elseif ($model instanceof PembayaranPiutang) {
            $pihak = $this->customerName($model->customer);
            $invoices = $model->invoices->isNotEmpty() ? $model->invoices : collect([$model->invoice])->filter();
            $dokumen[] = $this->label('INV', $invoices->pluck('nomor')->filter()->unique()->implode(', '));
            $dokumen[] = $this->label('SO', $invoices->map(fn($inv) => $inv->salesOrder?->nomor)->filter()->unique()->implode(', '));
        } elseif ($model instanceof PembayaranHutang) {
            $pihak = $model->supplier?->nama;
            $pos = $model->purchaseOrders->isNotEmpty() ? $model->purchaseOrders : collect([$model->purchaseOrder])->filter();
            $dokumen[] = $this->label('PO', $pos->pluck('nomor')->filter()->unique()->implode(', '));
        } elseif ($model instanceof PenerimaanBarang) {
            $pihak = $model->supplier?->nama;
            $dokumen[] = $this->label('PO', $model->purchaseOrder?->nomor);
        } elseif ($model instanceof DeliveryOrder) {
            $pihak = $this->customerName($model->customer);
            $dokumen[] = $this->label('SO', $model->salesOrder?->nomor);
        }

        $dokumen = array_filter($dokumen);

        return [
            'pihak' => $pihak ?: '-',
            'dokumen' => $dokumen ? implode(' | ', $dokumen) : '-',
        ];
    }

    protected function customerName($customer): ?string
    {
        if (!$customer) {
            return null;
        }

        // Customer perusahaan sering hanya mengisi kolom company, nama (PIC) kosong
        if ($customer->company && $customer->nama) {
            return "{$customer->company} ({$customer->nama})";
        }

        return $customer->company ?: $customer->nama;
    }

    protected function label(string $prefix, ?string $value): ?string
    {
        return $value ? "{$prefix}: {$value}" : null;
    }
}

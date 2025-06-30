<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\PembayaranPiutang;
use App\Models\Pembelian;
use App\Models\PembayaranHutang;
use App\Models\ReturPenjualan;
use App\Models\ReturPembelian;
use App\Models\PenyesuaianStok;
use App\Models\BiayaOperasional;
use App\Models\Penggajian;
use App\Observers\InvoiceObserver;
use App\Observers\PembayaranPiutangObserver;
use App\Observers\PembelianObserver;
use App\Observers\PembayaranHutangObserver;
use App\Observers\ReturPenjualanObserver;
use App\Observers\ReturPembelianObserver;
use App\Observers\PenyesuaianStokObserver;
use App\Observers\BiayaOperasionalObserver;
use App\Observers\PenggajianObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register observers for automatic journal entries
        Invoice::observe(InvoiceObserver::class);
        PembayaranPiutang::observe(PembayaranPiutangObserver::class);
        Pembelian::observe(PembelianObserver::class);
        PembayaranHutang::observe(PembayaranHutangObserver::class);
        ReturPenjualan::observe(ReturPenjualanObserver::class);
        ReturPembelian::observe(ReturPembelianObserver::class);
        PenyesuaianStok::observe(PenyesuaianStokObserver::class);
        BiayaOperasional::observe(BiayaOperasionalObserver::class);
        Penggajian::observe(PenggajianObserver::class);
    }
}

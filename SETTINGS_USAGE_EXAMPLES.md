# Contoh Penggunaan Settings Helper

## 1. Di Controller (Contoh: Invoice Controller)

```php
<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function generatePDF($id)
    {
        $invoice = Invoice::findOrFail($id);

        $data = [
            'invoice' => $invoice,
            'company' => [
                'name' => setting('company_name'),
                'address' => setting('company_address'),
                'phone' => setting('company_phone'),
                'email' => setting('company_email'),
                'logo_url' => get_company_logo_url(),
                'npwp' => setting('company_npwp'),
                'website' => setting('company_website'),
            ],
            'terms' => setting('invoice_terms'),
            'footer' => setting('invoice_footer'),
        ];

        // Generate PDF dengan data setting
        $pdf = PDF::loadView('invoices.pdf', $data);
        return $pdf->download("invoice-{$invoice->nomor}.pdf");
    }

    public function create()
    {
        return view('invoices.create', [
            'tax_percentage' => get_tax_percentage(),
            'currency' => setting('default_currency'),
            'prefix' => get_document_prefix('invoice'),
        ]);
    }
}
```

## 2. Di Blade Template (Contoh: Dashboard)

```blade
{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} - {{ setting('company_name') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Company Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        @if(setting('company_logo'))
                            <img src="{{ get_company_logo_url() }}"
                                 alt="{{ setting('company_name') }}"
                                 class="h-16 w-16 object-contain mr-4">
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold">{{ setting('company_name') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ setting('company_address') }}</p>
                            <p class="text-sm text-gray-500">{{ setting('company_phone') }} | {{ setting('company_email') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold">{{ format_currency($totalRevenue) }}</div>
                        <div class="text-gray-600 dark:text-gray-400">Total Revenue</div>
                    </div>
                </div>
                <!-- More stats... -->
            </div>
        </div>
    </div>
</x-app-layout>
```

## 3. Di Model (Contoh: Sales Order)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = ['nomor', 'customer_id', 'subtotal', 'tax_amount', 'total'];

    protected static function booted()
    {
        static::creating(function ($salesOrder) {
            if (empty($salesOrder->nomor)) {
                $prefix = get_document_prefix('sales_order');
                $lastNumber = static::where('nomor', 'like', $prefix . '%')
                    ->orderBy('nomor', 'desc')
                    ->first();

                $nextNumber = $lastNumber ?
                    intval(substr($lastNumber->nomor, strlen($prefix))) + 1 : 1;

                $salesOrder->nomor = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Auto calculate tax if not set
            if (empty($salesOrder->tax_amount) && !empty($salesOrder->subtotal)) {
                $salesOrder->tax_amount = calculate_tax($salesOrder->subtotal);
                $salesOrder->total = $salesOrder->subtotal + $salesOrder->tax_amount;
            }
        });
    }

    public function getFormattedTotalAttribute()
    {
        return format_currency($this->total);
    }

    public function getFormattedDateAttribute()
    {
        return format_date_setting($this->created_at);
    }
}
```

## 4. Di Service Class

```php
<?php

namespace App\Services;

class DocumentService
{
    public function generateDocumentNumber($type)
    {
        $prefix = get_document_prefix($type);
        $modelClass = $this->getModelClass($type);

        $lastDocument = $modelClass::where('nomor', 'like', $prefix . '%')
            ->orderBy('nomor', 'desc')
            ->first();

        $nextNumber = $lastDocument ?
            intval(substr($lastDocument->nomor, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getCompanyData()
    {
        return [
            'name' => setting('company_name'),
            'address' => setting('company_address'),
            'phone' => setting('company_phone'),
            'email' => setting('company_email'),
            'npwp' => setting('company_npwp'),
            'website' => setting('company_website'),
            'logo_url' => get_company_logo_url(),
        ];
    }

    public function calculateTotal($subtotal)
    {
        $tax = calculate_tax($subtotal);
        return $subtotal + $tax;
    }
}
```

## 5. Di API Resource

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomor' => $this->nomor,
            'formatted_total' => format_currency($this->total),
            'formatted_date' => format_date_setting($this->created_at),
            'tax_percentage' => get_tax_percentage(),
            'company' => [
                'name' => setting('company_name'),
                'logo_url' => get_company_logo_url(),
            ],
        ];
    }
}
```

## 6. Di Middleware (Contoh: Setting Timezone)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetTimezone
{
    public function handle(Request $request, Closure $next)
    {
        $timezone = get_timezone();

        if ($timezone) {
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
        }

        return $next($request);
    }
}
```

## 7. Di Email Template

```blade
{{-- resources/views/emails/invoice.blade.php --}}
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="text-align: center; padding: 20px;">
        @if(setting('company_logo'))
            <img src="{{ get_company_logo_url() }}"
                 alt="{{ setting('company_name') }}"
                 style="height: 60px;">
        @endif
        <h2>{{ setting('company_name') }}</h2>
    </div>

    <div style="padding: 20px;">
        <h3>Invoice #{{ $invoice->nomor }}</h3>

        <p>Dear {{ $invoice->customer->nama }},</p>

        <p>Terlampir adalah invoice dengan rincian sebagai berikut:</p>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td><strong>Total Amount:</strong></td>
                <td>{{ format_currency($invoice->total) }}</td>
            </tr>
            <tr>
                <td><strong>Due Date:</strong></td>
                <td>{{ format_date_setting($invoice->due_date) }}</td>
            </tr>
        </table>

        <div style="margin-top: 30px; font-size: 12px; color: #666;">
            <p>{{ setting('invoice_footer') }}</p>
        </div>
    </div>

    <div style="background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px;">
        {{ setting('company_name') }}<br>
        {{ setting('company_address') }}<br>
        {{ setting('company_phone') }} | {{ setting('company_email') }}
    </div>
</div>
```

## 8. Di JavaScript (Via Alpine.js)

```blade
<div x-data="{
    companyName: @json(setting('company_name')),
    currency: @json(setting('default_currency')),
    taxPercentage: @json(get_tax_percentage()),
    dateFormat: @json(setting('date_format')),

    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: this.currency
        }).format(amount);
    },

    calculateTax(subtotal) {
        return (subtotal * this.taxPercentage) / 100;
    }
}">
    <h1 x-text="companyName"></h1>
    <p x-text="formatCurrency(1000000)"></p>
</div>
```

## 9. Di Artisan Command

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateReports extends Command
{
    protected $signature = 'reports:generate';

    public function handle()
    {
        $this->info('Generating reports for ' . setting('company_name'));

        // Logic untuk generate reports
        $companyData = [
            'name' => setting('company_name'),
            'address' => setting('company_address'),
            'timezone' => get_timezone(),
        ];

        // Generate reports dengan data company
    }
}
```

Contoh-contoh di atas menunjukkan bagaimana helper functions dapat digunakan di berbagai bagian aplikasi untuk memastikan konsistensi data dan memudahkan maintenance.

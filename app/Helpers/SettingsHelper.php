<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get multiple settings or all settings grouped by group
     *
     * @param array|string|null $keys
     * @return \Illuminate\Support\Collection|array
     */
    function settings($keys = null)
    {
        if (is_null($keys)) {
            return Setting::getAllGrouped();
        }

        if (is_string($keys)) {
            return Setting::getByGroup($keys);
        }

        if (is_array($keys)) {
            $result = [];
            foreach ($keys as $key) {
                $result[$key] = Setting::getValue($key);
            }
            return $result;
        }

        return collect();
    }
}

if (!function_exists('company_setting')) {
    /**
     * Get company setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function company_setting($key, $default = null)
    {
        return Setting::getValue("company_{$key}", $default);
    }
}

if (!function_exists('app_setting')) {
    /**
     * Get application setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function app_setting($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format number to currency based on application settings
     *
     * @param float $amount
     * @param bool $showSymbol
     * @return string
     */
    function format_currency($amount, $showSymbol = true)
    {
        $currency = setting('default_currency', 'IDR');
        $decimalSeparator = setting('decimal_separator', ',');
        $thousandSeparator = setting('thousand_separator', '.');

        $formatted = number_format($amount, 2, $decimalSeparator, $thousandSeparator);

        if ($showSymbol) {
            switch ($currency) {
                case 'IDR':
                    return 'Rp ' . $formatted;
                case 'USD':
                    return '$' . $formatted;
                case 'EUR':
                    return '€' . $formatted;
                default:
                    return $currency . ' ' . $formatted;
            }
        }

        return $formatted;
    }
}

if (!function_exists('format_date_setting')) {
    /**
     * Format date based on application settings
     *
     * @param \Carbon\Carbon|string $date
     * @param string|null $format
     * @return string
     */
    function format_date_setting($date, $format = null)
    {
        if (empty($date)) {
            return '';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        $dateFormat = $format ?: setting('date_format', 'd/m/Y');

        return $date->format($dateFormat);
    }
}

if (!function_exists('get_timezone')) {
    /**
     * Get application timezone
     *
     * @return string
     */
    function get_timezone()
    {
        return setting('timezone', 'Asia/Jakarta');
    }
}

if (!function_exists('get_tax_percentage')) {
    /**
     * Get tax percentage from settings
     *
     * @return float
     */
    function get_tax_percentage()
    {
        return (float) setting('tax_percentage', 11);
    }
}

if (!function_exists('calculate_tax')) {
    /**
     * Calculate tax amount based on subtotal
     *
     * @param float $subtotal
     * @return float
     */
    function calculate_tax($subtotal)
    {
        $taxPercentage = get_tax_percentage();
        return ($subtotal * $taxPercentage) / 100;
    }
}

if (!function_exists('get_document_prefix')) {
    /**
     * Get document prefix for given document type
     *
     * @param string $type
     * @return string
     */
    function get_document_prefix($type)
    {
        $prefixes = [
            'quotation' => setting('quotation_prefix', 'QT'),
            'sales_order' => setting('sales_order_prefix', 'SO'),
            'purchase_request' => setting('purchase_request_prefix', 'PR'),
            'purchase_order' => setting('purchase_order_prefix', 'PO'),
            'delivery_order' => setting('delivery_order_prefix', 'DO'),
            'invoice' => setting('invoice_prefix', 'INV'),
        ];

        return $prefixes[$type] ?? 'DOC';
    }
}

if (!function_exists('get_company_logo_url')) {
    /**
     * Get company logo URL
     *
     * @return string
     */
    function get_company_logo_url()
    {
        $logo = setting('company_logo', 'logo.png');

        if (file_exists(public_path('storage/' . $logo))) {
            return asset('storage/' . $logo);
        }

        // Return default logo or placeholder
        return asset('img/logo-placeholder.png');
    }
}

if (!function_exists('is_feature_enabled')) {
    /**
     * Check if a system feature is enabled
     *
     * @param string $feature
     * @return bool
     */
    function is_feature_enabled($feature)
    {
        $enabledFeatures = [
            'notifications' => setting('enable_notifications', '1'),
            'email_notifications' => setting('enable_email_notifications', '1'),
            'audit_log' => setting('enable_audit_log', '1'),
        ];

        return isset($enabledFeatures[$feature]) && $enabledFeatures[$feature] === '1';
    }
}

if (!function_exists('company_info')) {
    /**
     * Get complete company information for PDF templates
     *
     * @return array
     */
    function company_info()
    {
        return [
            'name' => setting('company_name', 'PT. SINAR SURYA SEMESTARAYA'),
            'address' => setting('company_address', 'Jl. Condet Raya No. 6 Balekambang'),
            'city' => setting('company_city', 'Jakarta Timur'),
            'postal_code' => setting('company_postal_code', '13530'),
            'phone' => setting('company_phone', '(021) 80876624 - 80876642'),
            'email' => setting('company_email', 'admin@kliksinarsurya.com'),
            'email_2' => setting('company_email_2', 'sinar.surya@hotmail.com'),
            'email_3' => setting('company_email_3', 'sinarsurya.sr@gmail.com'),
            'website' => setting('company_website', 'https://sinar-surya.com'),
            'logo_path' => setting('company_logo') ? 'storage/' . setting('company_logo') : 'img/logo_nama3.png',
        ];
    }
}

if (!function_exists('company_address_line')) {
    /**
     * Get formatted company address line
     *
     * @return string
     */
    function company_address_line()
    {
        $address = setting('company_address', 'Jl. Condet Raya No. 6 Balekambang');
        $city = setting('company_city', 'Jakarta Timur');
        $postal = setting('company_postal_code', '13530');

        return $address . ', ' . $city . ' ' . $postal;
    }
}

if (!function_exists('company_contact_info')) {
    /**
     * Get formatted company contact information
     *
     * @return array
     */
    function company_contact_info()
    {
        $emails = array_filter([
            setting('company_email', 'admin@kliksinarsurya.com'),
            setting('company_email_2'),
            setting('company_email_3')
        ]);

        return [
            'phone' => setting('company_phone', '(021) 80876624 - 80876642'),
            'emails' => $emails,
            'website' => setting('company_website')
        ];
    }
}

if (!function_exists('get_enabled_bank_accounts')) {
    /**
     * Get enabled bank accounts for invoice
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_enabled_bank_accounts()
    {
        $enabledIds = json_decode(setting('enabled_bank_accounts', '[]'), true);
        
        if (empty($enabledIds)) {
            return collect();
        }

        return \App\Models\RekeningBank::whereIn('id', $enabledIds)
            ->where('is_aktif', true)
            ->where('is_perusahaan', true)
            ->orderBy('nama_bank')
            ->get();
    }
}

if (!function_exists('get_primary_bank_account')) {
    /**
     * Get primary bank account for invoice
     *
     * @return \App\Models\RekeningBank|null
     */
    function get_primary_bank_account()
    {
        $primaryId = setting('primary_bank_account', '');
        
        if (empty($primaryId)) {
            return null;
        }

        return \App\Models\RekeningBank::where('id', $primaryId)
            ->where('is_aktif', true)
            ->where('is_perusahaan', true)
            ->first();
    }
}

if (!function_exists('get_bank_accounts_for_invoice')) {
    /**
     * Get bank accounts available for invoice selection
     * Returns enabled accounts, or primary account if none enabled
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_bank_accounts_for_invoice()
    {
        $enabledAccounts = get_enabled_bank_accounts();
        
        if ($enabledAccounts->isNotEmpty()) {
            return $enabledAccounts;
        }

        // Fallback to primary account if no accounts are specifically enabled
        $primaryAccount = get_primary_bank_account();
        if ($primaryAccount) {
            return collect([$primaryAccount]);
        }

        // Last fallback to all active company accounts
        return \App\Models\RekeningBank::where('is_aktif', true)
            ->where('is_perusahaan', true)
            ->orderBy('nama_bank')
            ->get();
    }
}
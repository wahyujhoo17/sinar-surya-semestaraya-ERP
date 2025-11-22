<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingConfiguration extends Model
{
    protected $fillable = [
        'transaction_type',
        'account_key',
        'account_name',
        'akun_id',
        'is_required',
        'description'
    ];

    /**
     * Relasi ke akun akuntansi
     */
    public function akun()
    {
        return $this->belongsTo(AkunAkuntansi::class, 'akun_id');
    }

    /**
     * Get konfigurasi berdasarkan transaction type dan account key
     */
    public static function getAccountId($transactionType, $accountKey)
    {
        $config = self::where('transaction_type', $transactionType)
            ->where('account_key', $accountKey)
            ->first();

        return $config ? $config->akun_id : null;
    }

    /**
     * Helper function untuk menggantikan config('accounting.*')
     * Usage: accounting('pembayaran_hutang.hutang_usaha')
     */
    public static function get($key)
    {
        // Parse key seperti 'pembayaran_hutang.hutang_usaha'
        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return null;
        }

        list($transactionType, $accountKey) = $parts;

        return self::getAccountId($transactionType, $accountKey);
    }

    /**
     * Get semua konfigurasi accounting dalam format array seperti config lama
     */
    public static function getAllConfig()
    {
        $configs = self::all();
        $result = [];

        foreach ($configs as $config) {
            $result[$config->transaction_type][$config->account_key] = $config->akun_id;
        }

        return $result;
    }
}

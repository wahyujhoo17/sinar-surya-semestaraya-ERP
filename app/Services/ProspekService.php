<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Prospek;
use Illuminate\Support\Facades\Log;

class ProspekService
{
    /**
     * Create customer automatically from prospek data.
     * Used when prospek status changes to "tertarik".
     *
     * @param Prospek $prospek
     * @return array|null
     */
    public static function createCustomerFromProspek(Prospek $prospek): ?array
    {
        try {
            // Check if customer already exists for this prospek
            if ($prospek->customer_id) {
                Log::info('Customer already exists for prospek', [
                    'prospek_id' => $prospek->id,
                    'customer_id' => $prospek->customer_id
                ]);
                return Customer::find($prospek->customer_id)?->toArray();
            }

            // Check if customer with the same email already exists
            if ($prospek->email) {
                $existingCustomer = Customer::where('email', $prospek->email)->first();
                if ($existingCustomer) {
                    Log::info('Customer with same email already exists', [
                        'prospek_id' => $prospek->id,
                        'existing_customer_id' => $existingCustomer->id,
                        'email' => $prospek->email
                    ]);
                    return $existingCustomer->toArray();
                }
            }

            // Generate customer code
            $customerCode = self::generateCustomerCode();

            // Determine customer type based on whether it's individual or company
            $customerType = $prospek->perusahaan ? 'company' : 'individual';

            // Create customer data mapping from prospek
            $customerData = [
                'kode' => $customerCode,
                'nama' => $prospek->nama_prospek,
                'tipe' => $customerType,
                'company' => $prospek->perusahaan ?: $prospek->nama_prospek,
                'alamat' => $prospek->alamat,
                'alamat_pengiriman' => $prospek->alamat,
                'telepon' => $prospek->telepon,
                'email' => $prospek->email,
                'sales_id' => $prospek->user_id,
                'sales_name' => $prospek->user ? $prospek->user->name : null,
                'is_active' => true,
                'catatan' => 'Customer dibuat otomatis dari prospek: ' . $prospek->nama_prospek
            ];

            // Create the customer
            $customer = Customer::create($customerData);

            Log::info('Customer created successfully from prospek', [
                'prospek_id' => $prospek->id,
                'customer_id' => $customer->id,
                'customer_kode' => $customer->kode,
                'customer_data' => $customerData
            ]);

            return $customer->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to create customer from prospek', [
                'prospek_id' => $prospek->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Handle automatic customer creation when status becomes "tertarik".
     * Returns [customerCreated, customerId] tuple.
     *
     * @param Prospek $prospek
     * @param string $newStatus
     * @param string $oldStatus
     * @return array{bool, int|null}
     */
    public static function handleStatusChangeCustomerCreation(Prospek $prospek, string $newStatus, string $oldStatus): array
    {
        $customerCreated = false;
        $customerId = null;

        if ($newStatus === 'tertarik' && $oldStatus !== 'tertarik') {
            $customerData = self::createCustomerFromProspek($prospek);
            if ($customerData) {
                $customerCreated = true;
                $customerId = $customerData['id'];

                // Update prospek with customer_id
                $prospek->customer_id = $customerId;
                $prospek->save();

                Log::info('Customer created automatically from prospek:', [
                    'prospek_id' => $prospek->id,
                    'customer_id' => $customerId,
                    'customer_kode' => $customerData['kode']
                ]);
            }
        }

        return [$customerCreated, $customerId];
    }

    /**
     * Generate customer code following the same pattern as CustomerController
     *
     * @return string
     */
    public static function generateCustomerCode(): string
    {
        $prefix = 'CUST';
        $last = Customer::orderByDesc('id')->first();
        $lastNumber = 0;

        if ($last && preg_match('/^CUST(\d+)$/', $last->kode, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $newNumber = $lastNumber + 1;
        $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Ensure the code is unique
        while (Customer::where('kode', $code)->exists()) {
            $newNumber++;
            $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}

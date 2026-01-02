<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Prospek;
use App\Models\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class PipelinePenjualanController extends Controller
{
    /**
     * Check if current user can access all prospects (admin/manager_penjualan)
     */
    private function canAccessAllProspects()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan');
    }

    /**
     * Check if current user can edit/delete a specific prospect
     */
    private function canEditProspect($prospek)
    {
        // Admin and manager can edit all prospects
        if ($this->canAccessAllProspects()) {
            return true;
        }

        // Sales can only edit their own prospects
        return $prospek->user_id === Auth::id();
    }

    public function index()
    {
        return view('CRM.pipeline_penjualan.index');
    }

    public function data(Request $request)
    {
        // Inisialisasi array kosong untuk setiap stage
        $pipelineData = [
            'baru' => [],
            'tertarik' => [],
            'negosiasi' => [],
            'menolak' => [],
            'menjadi_customer' => []
        ];

        // Inisialisasi statistik
        $stats = [
            'total' => 0,
            'baru' => 0,
            'tertarik' => 0,
            'negosiasi' => 0,
            'menolak' => 0,
            'menjadi_customer' => 0
        ];

        try {
            // For viewing data, all sales can see all prospects
            // But only admin/manager or prospect owner can modify
            $query = Prospek::with('user');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_prospek', 'like', "%{$search}%")
                        ->orWhere('perusahaan', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telepon', 'like', "%{$search}%");
                });
            }

            // Apply time_frame filter
            if ($request->filled('time_frame')) {
                $timeFrame = $request->time_frame;
                $today = now()->format('Y-m-d');

                switch ($timeFrame) {
                    case 'today':
                        $query->whereDate('tanggal_kontak', $today);
                        break;
                    case 'week':
                        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                        $query->whereDate('tanggal_kontak', '>=', $startOfWeek)
                            ->whereDate('tanggal_kontak', '<=', $today);
                        break;
                    case 'month':
                        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                        $query->whereDate('tanggal_kontak', '>=', $startOfMonth)
                            ->whereDate('tanggal_kontak', '<=', $today);
                        break;
                    case 'quarter':
                        $startOfQuarter = now()->startOfQuarter()->format('Y-m-d');
                        $query->whereDate('tanggal_kontak', '>=', $startOfQuarter)
                            ->whereDate('tanggal_kontak', '<=', $today);
                        break;
                    case 'year':
                        $startOfYear = now()->startOfYear()->format('Y-m-d');
                        $query->whereDate('tanggal_kontak', '>=', $startOfYear)
                            ->whereDate('tanggal_kontak', '<=', $today);
                        break;
                }
            }

            // Get prospeks
            $prospeks = $query->get();

            // Set total count
            $stats['total'] = $prospeks->count();

            // Debug output
            Log::info('Pipeline data query result:', [
                'count' => $prospeks->count(),
                'filter_search' => $request->search,
                'filter_timeframe' => $request->time_frame,
                'first_few_records' => $prospeks->take(3)->toArray()
            ]);

            // Group prospeks by status
            foreach ($prospeks as $prospek) {
                $status = $prospek->status;

                // Skip if status is not one of the defined stages
                if (!array_key_exists($status, $pipelineData)) {
                    continue;
                }

                // Add to pipeline data
                $pipelineData[$status][] = $prospek;

                // Increment status count
                $stats[$status]++;
            }

            return response()->json([
                'success' => true,
                'data' => $pipelineData,
                'stats' => $stats,
                'debug' => [
                    'total_records' => $prospeks->count(),
                    'sample' => $prospeks->take(3)->toArray()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in pipeline data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage(),
                'data' => $pipelineData,
                'stats' => $stats
            ], 500);
        }
    }

    public function updateStatus(Request $request, Prospek $prospek)
    {
        try {
            // Check if user can edit this specific prospect
            if (!$this->canEditProspect($prospek)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah status prospek ini.'
                ], 403);
            }

            // Log the request for debugging
            Log::info('Update status request:', [
                'id' => $prospek->id,
                'current_status' => $prospek->status,
                'new_status' => $request->status,
                'request_data' => $request->all()
            ]);

            // Validate request
            $request->validate([
                'status' => 'required|in:baru,tertarik,negosiasi,menolak,menjadi_customer',
            ]);

            // Update prospek status
            $oldStatus = $prospek->status;
            $prospek->status = $request->status;
            $saved = $prospek->save();

            // Automatic customer creation when status becomes "tertarik"
            $customerCreated = false;
            $customerId = null;
            if ($request->status === 'tertarik' && $oldStatus !== 'tertarik') {
                $customerData = $this->createCustomerFromProspek($prospek);
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

            // Log the result for debugging
            Log::info('Status update result:', [
                'id' => $prospek->id,
                'old_status' => $oldStatus,
                'new_status' => $prospek->status,
                'saved' => $saved,
                'customer_created' => $customerCreated,
                'customer_id' => $customerId,
                'fresh_data' => $prospek->fresh()->toArray()
            ]);

            // Log aktivitas using the parent controller method
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'ubah_status',
                'modul' => 'crm_pipeline',
                'data_id' => $prospek->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'status_lama' => $oldStatus,
                    'status_baru' => $request->status,
                    'nama_prospek' => $prospek->nama_prospek,
                    'perusahaan' => $prospek->perusahaan,
                    'customer_created' => $customerCreated,
                    'customer_id' => $customerId
                ])
            ]);

            $responseMessage = 'Status prospek berhasil diperbarui';
            if ($customerCreated) {
                $responseMessage .= ' dan data customer baru telah dibuat otomatis';
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'data' => [
                    'id' => $prospek->id,
                    'old_status' => $oldStatus,
                    'new_status' => $prospek->status,
                    'customer_created' => $customerCreated,
                    'customer_id' => $customerId
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating prospek status: ' . $e->getMessage(), [
                'prospek_id' => $prospek->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status prospek: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nama Prospek');
        $sheet->setCellValue('C1', 'Perusahaan');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Telepon');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Tanggal Kontak');
        $sheet->setCellValue('H1', 'Sales Penanggung Jawab');
        $sheet->setCellValue('I1', 'Catatan');

        // Style the header row
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        // Query the data (apply filters from request) - all sales can view all prospects for export
        $query = Prospek::with('user');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_prospek', 'like', "%{$search}%")
                    ->orWhere('perusahaan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        // Apply time_frame filter
        if ($request->filled('time_frame')) {
            $timeFrame = $request->time_frame;
            $today = now()->format('Y-m-d');

            switch ($timeFrame) {
                case 'today':
                    $query->whereDate('tanggal_kontak', $today);
                    break;
                case 'week':
                    $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfWeek)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
                case 'month':
                    $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfMonth)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
                case 'quarter':
                    $startOfQuarter = now()->startOfQuarter()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfQuarter)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
                case 'year':
                    $startOfYear = now()->startOfYear()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfYear)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
            }
        }

        // Get the data
        $prospeks = $query->orderBy('status')->orderBy('tanggal_kontak', 'desc')->get();

        // Populate the data
        $row = 2;
        foreach ($prospeks as $prospek) {
            $sheet->setCellValue('A' . $row, $prospek->id);
            $sheet->setCellValue('B' . $row, $prospek->nama_prospek);
            $sheet->setCellValue('C' . $row, $prospek->perusahaan ?? 'Individu');
            $sheet->setCellValue('D' . $row, $prospek->email);
            $sheet->setCellValue('E' . $row, $prospek->telepon);
            $sheet->setCellValue('F' . $row, $this->getStatusLabel($prospek->status));
            $sheet->setCellValue('G' . $row, $prospek->tanggal_kontak ? $prospek->tanggal_kontak->format('d-m-Y') : '');
            $sheet->setCellValue('H' . $row, $prospek->user ? $prospek->user->name : 'Tidak Ada');
            $sheet->setCellValue('I' . $row, $prospek->catatan);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create filename with timestamp
        $filename = 'pipeline_penjualan_' . now()->format('Y-m-d_His') . '.xlsx';

        // Log export activity
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'export_excel',
            'modul' => 'crm_pipeline',
            'data_id' => null,
            'ip_address' => request()->ip(),
            'detail' => json_encode([
                'filters' => [
                    'search' => $request->search ?? null,
                    'time_frame' => $request->time_frame ?? null
                ],
                'filename' => $filename,
                'record_count' => $prospeks->count()
            ])
        ]);

        // Create the writer
        $writer = new Xlsx($spreadsheet);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'pipeline');
        $writer->save($tempFile);

        // Return the file as a download
        return Response::download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function exportCsv(Request $request)
    {
        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nama Prospek');
        $sheet->setCellValue('C1', 'Perusahaan');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Telepon');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Tanggal Kontak');
        $sheet->setCellValue('H1', 'Sales Penanggung Jawab');
        $sheet->setCellValue('I1', 'Catatan');

        // Query the data (apply filters from request) - all sales can view all prospects for CSV export
        $query = Prospek::with('user');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_prospek', 'like', "%{$search}%")
                    ->orWhere('perusahaan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        // Apply time_frame filter
        if ($request->filled('time_frame')) {
            $timeFrame = $request->time_frame;
            $today = now()->format('Y-m-d');

            switch ($timeFrame) {
                case 'today':
                    $query->whereDate('tanggal_kontak', $today);
                    break;
                case 'week':
                    $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfWeek)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
                case 'month':
                    $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfMonth)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
                case 'quarter':
                    $startOfQuarter = now()->startOfQuarter()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfQuarter)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
                case 'year':
                    $startOfYear = now()->startOfYear()->format('Y-m-d');
                    $query->whereDate('tanggal_kontak', '>=', $startOfYear)
                        ->whereDate('tanggal_kontak', '<=', $today);
                    break;
            }
        }

        // Get the data
        $prospeks = $query->orderBy('status')->orderBy('tanggal_kontak', 'desc')->get();

        // Populate the data
        $row = 2;
        foreach ($prospeks as $prospek) {
            $sheet->setCellValue('A' . $row, $prospek->id);
            $sheet->setCellValue('B' . $row, $prospek->nama_prospek);
            $sheet->setCellValue('C' . $row, $prospek->perusahaan ?? 'Individu');
            $sheet->setCellValue('D' . $row, $prospek->email);
            $sheet->setCellValue('E' . $row, $prospek->telepon);
            $sheet->setCellValue('F' . $row, $this->getStatusLabel($prospek->status));
            $sheet->setCellValue('G' . $row, $prospek->tanggal_kontak ? $prospek->tanggal_kontak->format('d-m-Y') : '');
            $sheet->setCellValue('H' . $row, $prospek->user ? $prospek->user->name : 'Tidak Ada');
            $sheet->setCellValue('I' . $row, $prospek->catatan);
            $row++;
        }

        // Create filename with timestamp
        $filename = 'pipeline_penjualan_' . now()->format('Y-m-d_His') . '.csv';

        // Log export activity
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'export_csv',
            'modul' => 'crm_pipeline',
            'data_id' => null,
            'ip_address' => request()->ip(),
            'detail' => json_encode([
                'filters' => [
                    'search' => $request->search ?? null,
                    'time_frame' => $request->time_frame ?? null
                ],
                'filename' => $filename,
                'record_count' => $prospeks->count()
            ])
        ]);

        // Create the writer
        $writer = new Csv($spreadsheet);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'pipeline');
        $writer->save($tempFile);

        // Return the file as a download
        return Response::download($tempFile, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    // Helper method for status labels
    private function getStatusLabel($status)
    {
        $labels = [
            'baru' => 'Baru',
            'tertarik' => 'Tertarik',
            'negosiasi' => 'Negosiasi',
            'menolak' => 'Menolak',
            'menjadi_customer' => 'Menjadi Customer',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Create customer automatically from prospek data
     * 
     * @param Prospek $prospek
     * @return array|null
     */
    private function createCustomerFromProspek(Prospek $prospek)
    {
        try {
            // Check if customer already exists for this prospek
            if ($prospek->customer_id) {
                Log::info('Customer already exists for prospek', [
                    'prospek_id' => $prospek->id,
                    'customer_id' => $prospek->customer_id
                ]);
                return Customer::find($prospek->customer_id)->toArray();
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
            $customerCode = $this->generateCustomerCode();

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
     * Generate customer code following the same pattern as CustomerController
     * 
     * @return string
     */
    private function generateCustomerCode()
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

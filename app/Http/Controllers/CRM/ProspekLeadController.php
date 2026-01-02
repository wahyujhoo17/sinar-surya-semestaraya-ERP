<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospek;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProspekLeadController extends Controller
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
        // All sales can view all prospects for listing
        $prospeks = Prospek::with('user')->orderBy('created_at', 'desc')->get();

        // Get customer groups for filter
        $customerGroups = Customer::whereNotNull('group')
            ->where('group', '!=', '')
            ->distinct()
            ->pluck('group')
            ->sort()
            ->values();

        return view('CRM.prospek_and_lead.index', compact('prospeks', 'customerGroups'));
    }

    public function data(Request $request)
    {
        // All sales can view all prospects data, but filtering will be handled in frontend/UI
        $query = Prospek::with('user');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_prospek', 'like', "%{$search}%")
                    ->orWhere('perusahaan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply sumber filter
        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        // Apply customer group filter
        if ($request->filled('customer_group')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('group', $request->customer_group);
            });
        }

        // Apply periode filter
        if ($request->filled('periode')) {
            $periode = $request->periode;
            $today = now()->format('Y-m-d');

            switch ($periode) {
                case 'today':
                    $query->whereDate('tanggal_kontak', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('tanggal_kontak', now()->subDay()->format('Y-m-d'));
                    break;
                case 'last_7_days':
                    $query->whereDate('tanggal_kontak', '>=', now()->subDays(7)->format('Y-m-d'));
                    break;
                case 'last_30_days':
                    $query->whereDate('tanggal_kontak', '>=', now()->subDays(30)->format('Y-m-d'));
                    break;
                case 'this_month':
                    $query->whereMonth('tanggal_kontak', now()->month)
                        ->whereYear('tanggal_kontak', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('tanggal_kontak', now()->subMonth()->month)
                        ->whereYear('tanggal_kontak', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('tanggal_kontak', now()->year);
                    break;
            }
        }

        // Apply sorting
        $sortColumn = $request->input('sort_column', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        // Paginate results
        $perPage = $request->input('per_page', 10);
        $prospeks = $query->paginate($perPage);

        return response()->json($prospeks);
    }

    public function create()
    {
        $customers = Customer::all();
        return view('CRM.prospek_and_lead.create', compact('customers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_prospek' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|string|in:baru,tertarik,negosiasi,menolak,menjadi_customer',
            'sumber' => 'required|string|in:website,referral,pameran,media_sosial,cold_call,lainnya',
            'nilai_potensi' => 'nullable|numeric|min:0',
            'tanggal_kontak' => 'nullable|date',
            'tanggal_followup' => 'nullable|date',
            'catatan' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // Max 10MB per file
        ]);

        // Tambahkan user_id dari user yang sedang login
        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('prospek_attachments', $filename, 'public');

                $attachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
            $data['attachments'] = $attachments;
        }

        // Simpan data prospek
        $prospek = Prospek::create($data);

        // Customer akan dibuat otomatis saat status diubah menjadi "tertarik"

        return redirect()->route('crm.prospek.index')
            ->with('success', 'Prospek berhasil ditambahkan');
    }

    public function show($id)
    {
        // All sales can view any prospect details
        $prospek = Prospek::with('user')->findOrFail($id);

        return view('CRM.prospek_and_lead.show', compact('prospek'));
    }

    public function edit($id)
    {
        $prospek = Prospek::findOrFail($id);

        // Check if user can edit this specific prospect
        if (!$this->canEditProspect($prospek)) {
            return redirect()->route('crm.prospek.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit prospek ini.');
        }

        $customers = Customer::all();
        return view('CRM.prospek_and_lead.edit', compact('prospek', 'customers'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_prospek' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|string|in:baru,tertarik,negosiasi,menolak,menjadi_customer',
            'sumber' => 'required|string|in:website,referral,pameran,media_sosial,cold_call,lainnya',
            'nilai_potensi' => 'nullable|numeric|min:0',
            'tanggal_kontak' => 'nullable|date',
            'tanggal_followup' => 'nullable|date',
            'catatan' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // Max 10MB per file
        ]);

        $prospek = Prospek::findOrFail($id);

        // Check if user can edit this specific prospect
        if (!$this->canEditProspect($prospek)) {
            return redirect()->route('crm.prospek.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit prospek ini.');
        }

        // Store old status for customer creation check
        $oldStatus = $prospek->status;

        $data = $request->all();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $existingAttachments = $prospek->attachments ?? [];
            $newAttachments = [];

            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('prospek_attachments', $filename, 'public');

                $newAttachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }

            $data['attachments'] = array_merge($existingAttachments, $newAttachments);
        }

        $prospek->update($data);

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

        $message = 'Prospek berhasil diperbarui';
        if ($customerCreated) {
            $message .= ' dan data customer baru telah dibuat otomatis';
        }

        return redirect()->route('crm.prospek.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        try {
            $prospek = Prospek::findOrFail($id);

            // Check if user can delete this specific prospect
            if (!$this->canEditProspect($prospek)) {
                // If it's an AJAX request, return JSON error response
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk menghapus prospek ini.'
                    ], 403);
                }

                return redirect()->route('crm.prospek.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus prospek ini.');
            }

            $prospek->delete();

            // If it's an AJAX request, return JSON response
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prospek berhasil dihapus'
                ]);
            }

            // Regular form submission response with redirect
            return redirect()->route('crm.prospek.index')
                ->with('success', 'Prospek berhasil dihapus');
        } catch (\Exception $e) {
            // If it's an AJAX request, return JSON error response
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus prospek: ' . $e->getMessage()
                ], 500);
            }

            // Regular form submission error response
            return back()->with('error', 'Gagal menghapus prospek: ' . $e->getMessage());
        }
    }

    /**
     * Download attachment file
     */
    public function downloadAttachment($id, $attachmentIndex)
    {
        $prospek = Prospek::findOrFail($id);

        // Check if user can view this prospect
        if (!$this->canAccessAllProspects() && $prospek->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }

        $attachments = $prospek->attachments ?? [];

        if (!isset($attachments[$attachmentIndex])) {
            abort(404, 'File tidak ditemukan.');
        }

        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->download($filePath, $attachment['original_name']);
    }

    /**
     * Delete specific attachment
     */
    public function deleteAttachment($id, $attachmentIndex)
    {
        $prospek = Prospek::findOrFail($id);

        // Check if user can edit this prospect
        if (!$this->canEditProspect($prospek)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file ini.'
            ], 403);
        }

        $attachments = $prospek->attachments ?? [];

        if (!isset($attachments[$attachmentIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.'
            ], 404);
        }

        // Delete file from storage
        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove from array and update
        unset($attachments[$attachmentIndex]);
        $prospek->attachments = array_values($attachments); // Re-index array
        $prospek->save();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus.'
        ]);
    }

    /**
     * Create customer from prospek
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

    public function getCustomerGroups()
    {
        $groups = Customer::whereNotNull('group')
            ->where('group', '!=', '')
            ->distinct()
            ->pluck('group')
            ->sort()
            ->values();

        return response()->json(['success' => true, 'groups' => $groups]);
    }
}

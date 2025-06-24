<?php

namespace App\Http\Controllers\hr_karyawan;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class StrukturOrganisasiController extends Controller
{
    /**
     * Display organizational structure page
     */
    public function index()
    {
        // Get all departments with their parent-child relationships
        $departments = Department::with(['children', 'karyawan.jabatan', 'parent'])
            ->whereNull('parent_id')
            ->orderBy('nama')
            ->get();

        // Get all positions/roles
        $jabatans = Jabatan::with('department')
            ->withCount('karyawan')
            ->orderBy('nama')
            ->get()
            ->map(function ($jabatan) {
                return [
                    'id' => $jabatan->id,
                    'nama_jabatan' => $jabatan->nama,
                    'kode_jabatan' => $jabatan->kode,
                    'deskripsi' => $jabatan->deskripsi,
                    'level' => $jabatan->level,
                    'department_id' => $jabatan->department_id,
                    'department_name' => $jabatan->department->nama ?? null,
                    'karyawan_count' => $jabatan->karyawan_count
                ];
            });

        // Get department statistics
        $departmentStats = Department::withCount(['karyawan' => function ($query) {
            $query->where('status', 'aktif');
        }])->get();

        // Get organizational structure data for chart
        $organizationData = $this->buildOrganizationChart();

        // Get all departments in flat structure for management modal
        $allDepartments = $this->getAllDepartmentsFlat();

        return view('hr_karyawan.struktur_organisasi.index', [
            'departments' => $departments,
            'jabatans' => $jabatans,
            'departmentStats' => $departmentStats,
            'organizationData' => $organizationData,
            'allDepartments' => $allDepartments,
            'currentPage' => 'Struktur Organisasi',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'HR', 'url' => '#'],
                ['name' => 'Struktur Organisasi']
            ]
        ]);
    }

    /**
     * Build organization chart data
     */
    private function buildOrganizationChart()
    {
        $departments = Department::with(['children.children', 'karyawan.jabatan'])
            ->get();

        // Create hierarchical structure with levels
        return $this->buildHierarchicalData($departments);
    }

    /**
     * Build hierarchical data structure with levels
     */
    private function buildHierarchicalData($departments, $parentId = null, $level = 0)
    {
        $result = [];

        $filteredDepartments = $departments->where('parent_id', $parentId);

        foreach ($filteredDepartments as $department) {
            $departmentData = $this->mapDepartmentForChart($department, $level);
            $departmentData['children'] = $this->buildHierarchicalData($departments, $department->id, $level + 1);
            $result[] = $departmentData;
        }

        return collect($result);
    }

    /**
     * Map department data for organizational chart
     */
    private function mapDepartmentForChart($department, $level = 0)
    {
        $employees = $department->karyawan->where('status', 'aktif');

        return [
            'id' => $department->id,
            'name' => $department->nama,
            'code' => $department->kode,
            'description' => $department->deskripsi,
            'parent_id' => $department->parent_id,
            'level' => $level,
            'employee_count' => $employees->count(),
            'manager' => $this->getDepartmentManager($department),
            'employees' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->nama_lengkap,
                    'position' => $employee->jabatan->nama ?? '-',
                    'nip' => $employee->nip,
                    'photo' => $employee->foto ? asset('storage/' . $employee->foto) : null,
                ];
            }),
            'children' => []
        ];
    }

    /**
     * Get department manager (assuming manager is the employee with specific position)
     */
    private function getDepartmentManager($department)
    {
        // Look for manager positions (customize based on your position naming)
        $managerPositions = ['Manager', 'Kepala', 'Supervisor', 'Koordinator'];

        $manager = $department->karyawan
            ->where('status', 'aktif')
            ->filter(function ($employee) use ($managerPositions) {
                $position = $employee->jabatan->nama ?? '';
                return collect($managerPositions)->some(function ($managerPos) use ($position) {
                    return str_contains(strtolower($position), strtolower($managerPos));
                });
            })
            ->first();

        if ($manager) {
            return [
                'id' => $manager->id,
                'name' => $manager->nama_lengkap,
                'position' => $manager->jabatan->nama ?? '-',
                'nip' => $manager->nip,
                'photo' => $manager->foto ? asset('storage/' . $manager->foto) : null,
            ];
        }

        return null;
    }

    /**
     * Get all departments in flat structure for management
     */
    private function getAllDepartmentsFlat()
    {
        $departments = Department::with(['karyawan.jabatan', 'parent'])
            ->orderBy('nama')
            ->get();

        return $departments->map(function ($department) {
            $employees = $department->karyawan->where('status', 'aktif');

            return [
                'id' => $department->id,
                'name' => $department->nama,
                'code' => $department->kode,
                'description' => $department->deskripsi,
                'parent_id' => $department->parent_id,
                'parent_name' => $department->parent ? $department->parent->nama : null,
                'level' => $this->calculateDepartmentLevel($department),
                'employee_count' => $employees->count(),
                'manager' => $this->getDepartmentManager($department),
                'children' => [], // Empty for flat structure
            ];
        });
    }

    /**
     * Calculate department level in hierarchy
     */
    private function calculateDepartmentLevel($department, $level = 0)
    {
        if ($department->parent_id === null) {
            return $level;
        }

        $parent = Department::find($department->parent_id);
        if ($parent) {
            return $this->calculateDepartmentLevel($parent, $level + 1);
        }

        return $level;
    }

    /**
     * Get department details via AJAX
     */
    public function getDepartmentDetails($id)
    {
        try {
            $department = Department::with(['karyawan.jabatan', 'children', 'parent'])
                ->findOrFail($id);

            $employees = $department->karyawan->where('status', 'aktif');

            return response()->json([
                'department' => [
                    'id' => $department->id,
                    'name' => $department->nama,
                    'code' => $department->kode,
                    'description' => $department->deskripsi,
                    'parent' => $department->parent ? $department->parent->nama : null,
                    'employee_count' => $employees->count(),
                ],
                'employees' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->nama_lengkap,
                        'position' => $employee->jabatan->nama ?? '-',
                        'nip' => $employee->nip,
                        'email' => $employee->email,
                        'phone' => $employee->telepon,
                        'photo' => $employee->foto ? asset('storage/' . $employee->foto) : null,
                        'join_date' => $employee->tanggal_masuk ? date('d M Y', strtotime($employee->tanggal_masuk)) : '-',
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Department not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a new department
     */
    public function storeDepartment(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10|unique:department,kode',
                'parent_id' => 'nullable|exists:department,id',
                'manager_id' => 'nullable|exists:karyawan,id',
                'description' => 'nullable|string'
            ]);

            $department = Department::create([
                'nama' => $validatedData['name'],
                'kode' => $validatedData['code'],
                'parent_id' => $validatedData['parent_id'] ?: null,
                'deskripsi' => $validatedData['description']
            ]);

            // Update manager if provided
            if (!empty($validatedData['manager_id'])) {
                $manager = Karyawan::find($validatedData['manager_id']);
                if ($manager) {
                    $manager->update(['department_id' => $department->id]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil ditambahkan',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan departemen: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update department
     */
    public function updateDepartment(Request $request, $id)
    {
        try {
            $department = Department::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10|unique:department,kode,' . $id,
                'parent_id' => 'nullable|exists:department,id',
                'manager_id' => 'nullable|exists:karyawan,id',
                'description' => 'nullable|string'
            ]);

            $department->update([
                'nama' => $validatedData['name'],
                'kode' => $validatedData['code'],
                'parent_id' => $validatedData['parent_id'] ?: null,
                'deskripsi' => $validatedData['description']
            ]);

            // Update manager if provided
            if (!empty($validatedData['manager_id'])) {
                // Remove old manager from this department
                Karyawan::where('department_id', $department->id)->update(['department_id' => null]);

                // Set new manager
                $manager = Karyawan::find($validatedData['manager_id']);
                if ($manager) {
                    $manager->update(['department_id' => $department->id]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil diperbarui',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui departemen: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete department
     */
    public function deleteDepartment($id)
    {
        try {
            $department = Department::findOrFail($id);

            // Check if department has children
            if ($department->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus departemen yang memiliki sub-departemen'
                ], 422);
            }

            // Check if department has employees
            if ($department->karyawan()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus departemen yang memiliki karyawan'
                ], 422);
            }

            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus departemen: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Store a new jabatan
     */
    public function storeJabatan(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_jabatan' => 'required|string|max:255',
                'kode_jabatan' => 'required|string|max:10|unique:jabatan,kode',
                'department_id' => 'nullable|exists:department,id',
                'level' => 'required|integer|min:1|max:5',
                'deskripsi' => 'nullable|string'
            ]);

            $jabatan = Jabatan::create([
                'nama' => $validatedData['nama_jabatan'],
                'kode' => $validatedData['kode_jabatan'],
                'department_id' => $validatedData['department_id'] ?: null,
                'level' => $validatedData['level'],
                'deskripsi' => $validatedData['deskripsi']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil ditambahkan',
                'jabatan' => $jabatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jabatan: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update jabatan
     */
    public function updateJabatan(Request $request, $id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);

            $validatedData = $request->validate([
                'nama_jabatan' => 'required|string|max:255',
                'kode_jabatan' => 'required|string|max:10|unique:jabatan,kode,' . $id,
                'department_id' => 'nullable|exists:department,id',
                'level' => 'required|integer|min:1|max:5',
                'deskripsi' => 'nullable|string'
            ]);

            $jabatan->update([
                'nama' => $validatedData['nama_jabatan'],
                'kode' => $validatedData['kode_jabatan'],
                'department_id' => $validatedData['department_id'] ?: null,
                'level' => $validatedData['level'],
                'deskripsi' => $validatedData['deskripsi']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil diperbarui',
                'jabatan' => $jabatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jabatan: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete jabatan
     */
    public function deleteJabatan($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);

            // Check if jabatan has employees
            if ($jabatan->karyawan()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus jabatan yang sedang digunakan oleh karyawan'
                ], 422);
            }

            $jabatan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jabatan: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get available employees for manager selection
     */
    public function getAvailableEmployees()
    {
        $employees = Karyawan::with('jabatan')
            ->where('status', 'aktif')
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->nama_lengkap,
                    'position' => $employee->jabatan->nama ?? '-',
                    'nip' => $employee->nip
                ];
            });

        return response()->json([
            'success' => true,
            'employees' => $employees
        ]);
    }
}

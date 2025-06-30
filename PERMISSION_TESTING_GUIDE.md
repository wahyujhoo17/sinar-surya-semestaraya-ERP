# Permission Implementation Testing Guide

## Testing Checklist untuk Master Data Permission Implementation

### Prerequisites

1. Pastikan user testing memiliki dan tidak memiliki permission yang berbeda
2. Buat user dengan role yang berbeda untuk testing
3. Akses aplikasi dengan user yang berbeda

### 1. Controller-Level Testing

#### Test dengan user TANPA permission:

```bash
# Test accessing endpoints without permission
# Should return 403 Forbidden or redirect to unauthorized page

# Produk
GET /master-data/produk (without produk.view)
POST /master-data/produk (without produk.create)
PUT /master-data/produk/{id} (without produk.edit)
DELETE /master-data/produk/{id} (without produk.delete)

# Kategori Produk
GET /master-data/kategori-produk (without kategori_produk.view)
POST /master-data/kategori-produk (without kategori_produk.create)
PUT /master-data/kategori-produk/{id} (without kategori_produk.edit)
DELETE /master-data/kategori-produk/{id} (without kategori_produk.delete)

# Customer/Pelanggan
GET /master-data/pelanggan (without pelanggan.view)
POST /master-data/pelanggan (without pelanggan.create)
PUT /master-data/pelanggan/{id} (without pelanggan.edit)
DELETE /master-data/pelanggan/{id} (without pelanggan.delete)

# Supplier
GET /master-data/supplier (without supplier.view)
POST /master-data/supplier (without supplier.create)
PUT /master-data/supplier/{id} (without supplier.edit)
DELETE /master-data/supplier/{id} (without supplier.delete)

# Gudang
GET /master-data/gudang (without gudang.view)
POST /master-data/gudang (without gudang.create)
PUT /master-data/gudang/{id} (without gudang.edit)
DELETE /master-data/gudang/{id} (without gudang.delete)

# Satuan
GET /master-data/satuan (without satuan.view)
POST /master-data/satuan (without satuan.create)
PUT /master-data/satuan/{id} (without satuan.edit)
DELETE /master-data/satuan/{id} (without satuan.delete)
```

### 2. View-Level Testing

#### Test UI Elements Visibility:

**A. Quick Action Cards:**

-   ✅ Visible untuk user dengan CREATE permission
-   ✅ Disabled state untuk user tanpa CREATE permission
-   ✅ Menampilkan lock icon dan "Tidak Ada Akses"

**B. Bulk Actions:**

-   ✅ Visible untuk user dengan DELETE permission
-   ✅ Hidden untuk user tanpa DELETE permission

**C. Export/Import Buttons:**

-   ✅ Export button visible untuk user dengan EXPORT permission
-   ✅ Import button visible untuk user dengan IMPORT permission
-   ✅ Hidden untuk user tanpa permission

**D. Table Checkboxes:**

-   ✅ Visible untuk user dengan DELETE permission
-   ✅ Hidden untuk user tanpa DELETE permission

**E. Action Menu Items:**

-   ✅ Edit button visible untuk user dengan EDIT permission
-   ✅ Delete button visible untuk user dengan DELETE permission
-   ✅ View button visible untuk user dengan VIEW permission
-   ✅ Lock icon untuk user tanpa permission

**F. Empty State Buttons:**

-   ✅ "Tambah" button visible untuk user dengan CREATE permission
-   ✅ Hidden untuk user tanpa CREATE permission

### 3. Route-Level Testing

#### Test Route Protection:

```bash
# Special routes should be protected
GET /master-data/produk/export (needs produk.export)
POST /master-data/produk/import (needs produk.import)
DELETE /master-data/produk/bulk-destroy (needs produk.delete)
GET /master-data/produk/{id}/get (needs produk.view)
GET /master-data/produk/generate-code (needs produk.create)

# Similar for other modules...
```

### 4. Permission Matrix Testing

| User Role   | Modules | View | Create | Edit | Delete | Export | Import |
| ----------- | ------- | ---- | ------ | ---- | ------ | ------ | ------ |
| **Admin**   | All     | ✅   | ✅     | ✅   | ✅     | ✅     | ✅     |
| **Manager** | All     | ✅   | ✅     | ✅   | ✅     | ✅     | ✅     |
| **Staff**   | Limited | ✅   | ✅     | ✅   | ❌     | ✅     | ❌     |
| **Viewer**  | All     | ✅   | ❌     | ❌   | ❌     | ❌     | ❌     |

### 5. Error Handling Testing

#### Expected Behaviors:

-   **403 Forbidden**: Akses controller method tanpa permission
-   **Hidden UI**: Element tidak tampil tanpa permission
-   **Disabled State**: Button disabled dengan visual feedback
-   **Graceful Degradation**: UI tetap berfungsi tanpa error

### 6. Test Scenarios

#### Scenario 1: Full Access User (Admin)

-   Dapat melihat semua button dan menu
-   Dapat mengakses semua fitur
-   Tidak ada element yang disabled

#### Scenario 2: Limited Access User (Staff)

-   Tidak dapat melihat delete buttons
-   Tidak dapat mengakses import functionality
-   Dapat melihat view, create, edit functionality

#### Scenario 3: Read-Only User (Viewer)

-   Hanya dapat melihat data
-   Tidak ada action buttons yang visible
-   Quick action card menampilkan "Tidak Ada Akses"

### 7. Browser Testing

#### Test di berbagai browser:

-   Chrome/Chromium
-   Firefox
-   Safari
-   Edge

#### Test di berbagai device:

-   Desktop
-   Tablet
-   Mobile

### 8. Performance Testing

#### Check impact on performance:

-   Page load time dengan permission checks
-   Database query optimization
-   Caching middleware permission

### 9. Security Testing

#### Test bypassing permission:

-   Direct URL access
-   API endpoint testing
-   Form manipulation
-   Browser console manipulation

### 10. Automated Testing Scripts

```php
// Example PHPUnit test
public function test_user_without_create_permission_cannot_access_create_page()
{
    $user = User::factory()->create();
    // Don't assign create permission

    $response = $this->actingAs($user)
                     ->get('/master-data/produk/create');

    $response->assertStatus(403);
}

public function test_quick_action_card_hidden_without_create_permission()
{
    $user = User::factory()->create();
    // Don't assign create permission

    $response = $this->actingAs($user)
                     ->get('/master-data/produk');

    $response->assertDontSee('Tambah Produk Baru');
    $response->assertSee('Tidak Ada Akses');
}
```

## Expected Results

### ✅ Success Indicators:

-   Unauthorized users cannot access restricted functions
-   UI gracefully degrades for limited users
-   No errors or broken layouts
-   Clear visual feedback for disabled states
-   Performance remains acceptable

### ❌ Failure Indicators:

-   Users can bypass permission checks
-   Broken UI layouts
-   Error messages displayed
-   Missing visual feedback
-   Performance degradation

## Reporting

### Document hasil testing:

1. Screenshot before/after untuk setiap user role
2. List any issues found
3. Performance metrics
4. Security vulnerabilities (if any)
5. Recommendations for improvement

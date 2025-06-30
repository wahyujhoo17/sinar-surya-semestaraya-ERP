# Production Module Permission Testing Guide

## Overview

This guide provides step-by-step instructions to test the newly implemented permission system for the Production (Produksi) module to ensure proper access control is working.

#### Completion Checklist

-   [x] All 10 Production module files have permission checks
-   [x] 40 total permission checks implemented
-   [x] Zero remaining `@can`/`@endcan` directives
-   [x] All files compile without syntax errors
-   [x] **BOM Edit file syntax errors resolved (4 instances fixed)**
-   [ ] Permission workflow tested with different user roles
-   [ ] Fallback UI verified for unauthorized users
-   [ ] Documentation updated with implementation details

---

**Implementation Date:** June 30, 2025  
**Total Files Modified:** 10  
**Total Permission Checks:** 40  
**Syntax Errors Fixed:** 4 (in BOM edit.blade.php)  
**Status:** Ready for Production Testing ✅

### 1. Perencanaan Produksi Testing

#### Test User with `perencanaan_produksi.view` only:

**Expected Behavior:**

-   ✅ Can view list of production plans
-   ✅ Can view production plan details
-   ❌ Cannot see Create button
-   ❌ Cannot see Edit button in detail view
-   ❌ Cannot see Submit button in detail view
-   ❌ Cannot see Approve/Reject buttons

#### Test User with `perencanaan_produksi.edit`:

**Expected Behavior:**

-   ✅ Can view and edit production plans
-   ✅ Can submit draft plans for approval
-   ✅ Can see Edit button for draft status plans
-   ✅ Can see Submit button for draft status plans
-   ❌ Cannot see Approve/Reject buttons (even for pending plans)

#### Test User with `perencanaan_produksi.approve`:

**Expected Behavior:**

-   ✅ Can view all production plans
-   ✅ Can see Approve/Reject buttons for pending approval plans
-   ✅ Can approve or reject production plans
-   ❌ Cannot edit draft plans (unless also has edit permission)

### 2. Work Order Testing

#### Test User with `work_order.view` only:

**Expected Behavior:**

-   ✅ Can view work order list and details
-   ✅ Can view work orders linked to production plans
-   ❌ Cannot see Create Work Order button
-   ❌ Cannot see Edit/Change Status buttons

#### Test User with `work_order.create`:

**Expected Behavior:**

-   ✅ Can create work orders from approved production plans
-   ✅ Can see "Buat Work Order" button on approved production plans
-   ✅ Can see Create button in work order index

#### Test User with `work_order.change_status`:

**Expected Behavior:**

-   ✅ Can change work order status (Start, Complete, Cancel)
-   ✅ Can see all status change buttons in work order table

### 3. Bill of Material (BOM) Testing

#### Test User with `bill_of_material.view` only:

**Expected Behavior:**

-   ✅ Can view BOM list
-   ✅ Can see View button in table
-   ❌ Cannot see Create button
-   ❌ Cannot see Edit/Delete buttons

#### Test User with `bill_of_material.create`:

**Expected Behavior:**

-   ✅ Can see Create BOM button
-   ✅ Can create new BOMs

#### Test User with `bill_of_material.edit`:

**Expected Behavior:**

-   ✅ Can see Edit button in BOM table
-   ✅ Can modify existing BOMs

#### Test User with `bill_of_material.delete`:

**Expected Behavior:**

-   ✅ Can see Delete button in BOM table
-   ✅ Can delete BOMs

### 4. Quality Control Testing

#### Test User with `quality_control.view` only:

**Expected Behavior:**

-   ✅ Can view quality control list
-   ✅ Can see View button in table
-   ❌ Cannot see Print/Approve buttons

#### Test User with `quality_control.print`:

**Expected Behavior:**

-   ✅ Can see Print button in header and table
-   ✅ Can export quality control reports

#### Test User with `quality_control.approve`:

**Expected Behavior:**

-   ✅ Can see Approve button
-   ✅ Can approve quality control records

## Testing Steps

### Step 1: Create Test Users

Create test users with different permission combinations:

```sql
-- User with view-only permissions
INSERT INTO permissions_user (user_id, permission_id) VALUES
(user_id, (SELECT id FROM permissions WHERE kode = 'perencanaan_produksi.view'));

-- User with edit permissions
INSERT INTO permissions_user (user_id, permission_id) VALUES
(user_id, (SELECT id FROM permissions WHERE kode = 'perencanaan_produksi.edit'));

-- User with approval permissions
INSERT INTO permissions_user (user_id, permission_id) VALUES
(user_id, (SELECT id FROM permissions WHERE kode = 'perencanaan_produksi.approve'));
```

### Step 2: Test UI Elements

For each test user:

1. **Login** with the test user
2. **Navigate** to Production module
3. **Check visibility** of buttons and actions according to expected behavior
4. **Attempt actions** to verify permission enforcement
5. **Verify error messages** for unauthorized actions

### Step 3: Test Workflow Integration

Test the complete production workflow:

1. **Create Production Plan** (requires `perencanaan_produksi.edit`)
2. **Submit for Approval** (requires `perencanaan_produksi.edit`)
3. **Approve Plan** (requires `perencanaan_produksi.approve`)
4. **Create Work Order** (requires `work_order.create`)
5. **Process Work Order** (requires `work_order.change_status`)

### Step 4: Verify Fallback UI

Check that users without permissions see appropriate fallback UI:

-   Lock icons instead of action buttons
-   "Access denied" messages
-   Disabled buttons with tooltips

## Permission Matrix

| Action                   | Required Permission            | File Location          |
| ------------------------ | ------------------------------ | ---------------------- |
| View Production Plans    | `perencanaan_produksi.view`    | index.blade.php        |
| Create Production Plan   | `perencanaan_produksi.create`  | index.blade.php        |
| Edit Production Plan     | `perencanaan_produksi.edit`    | show.blade.php         |
| Submit for Approval      | `perencanaan_produksi.edit`    | show.blade.php         |
| Approve/Reject Plan      | `perencanaan_produksi.approve` | show.blade.php         |
| Create Work Order        | `work_order.create`            | show.blade.php         |
| View Work Order          | `work_order.view`              | show.blade.php         |
| Change Work Order Status | `work_order.change_status`     | index.blade.php        |
| View BOM                 | `bill_of_material.view`        | \_table_body.blade.php |
| Create BOM               | `bill_of_material.create`      | index.blade.php        |
| Edit BOM                 | `bill_of_material.edit`        | \_table_body.blade.php |
| Delete BOM               | `bill_of_material.delete`      | \_table_body.blade.php |
| View Quality Control     | `quality_control.view`         | index.blade.php        |
| Print Quality Control    | `quality_control.print`        | index.blade.php        |
| Approve Quality Control  | `quality_control.approve`      | show.blade.php         |

## Expected Results

✅ **Success Indicators:**

-   Buttons appear/disappear based on user permissions
-   Unauthorized actions show appropriate error messages
-   Workflow progression follows permission requirements
-   UI provides clear feedback for permission states

❌ **Failure Indicators:**

-   Users can access actions without proper permissions
-   Missing fallback UI for unauthorized users
-   Server errors when checking permissions
-   Inconsistent permission enforcement across modules

## Troubleshooting

### Common Issues:

1. **Buttons still visible without permissions:**

    - Check that `hasPermission()` calls are properly formatted
    - Verify permission names match the seeder exactly

2. **Server errors on permission checks:**

    - Check that user is authenticated before checking permissions
    - Verify `hasPermission()` method exists on User model

3. **Inconsistent behavior:**
    - Clear cache: `php artisan config:clear && php artisan view:clear`
    - Check that all `@can` directives have been converted

### Debug Commands:

```bash
# Check current user permissions
User::find(1)->permissions->pluck('kode');

# Verify permission exists
Permission::where('kode', 'perencanaan_produksi.view')->first();

# Check user has specific permission
auth()->user()->hasPermission('perencanaan_produksi.view');
```

## Completion Checklist

-   [ ] All 10 Production module files have permission checks
-   [ ] 40 total permission checks implemented
-   [ ] Zero remaining `@can`/`@endcan` directives
-   [ ] All files compile without syntax errors
-   [ ] Permission workflow tested with different user roles
-   [ ] Fallback UI verified for unauthorized users
-   [ ] Documentation updated with implementation details

---

**Implementation Date:** June 30, 2025  
**Total Files Modified:** 10  
**Total Permission Checks:** 40  
**Status:** Ready for Production Testing ✅

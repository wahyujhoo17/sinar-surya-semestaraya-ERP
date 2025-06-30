# Role-Based Dashboard Implementation

## Overview

This implementation creates different dashboard pages for each role group and provides a default dashboard for roles that don't fit into defined groups or newly added roles.

## Features Implemented

### 1. Role-Based Dashboard Routing

-   **DashboardController** now includes intelligent role detection based on user permissions
-   Automatically routes users to appropriate dashboards based on their access levels
-   Fallback to default dashboard for users who don't fit predefined role groups

### 2. Role Groups Defined

#### Management Dashboard (`/dashboards/management.blade.php`)

-   **Target Users**: Users with `laporan.view` + access to multiple modules
-   **Features**:
    -   High-level KPIs (Total Products, Customers, Suppliers, Employees)
    -   Financial overview (Revenue, Receivables, Payables)
    -   Operational metrics (Active Work Orders, Pending Deliveries, etc.)
    -   Charts for sales trends and product categories
    -   Recent system activities

#### Finance Dashboard (`/dashboards/finance.blade.php`)

-   **Target Users**: Users with `jurnal_umum.view`, `kas.view`, `invoice.view`, or `laporan_pajak.view`
-   **Features**:
    -   Cash & bank balance overview
    -   Accounts receivable aging analysis
    -   Invoices due within 7 days
    -   Recent cash transactions
    -   Monthly revenue vs expense charts

#### Sales Dashboard (`/dashboards/sales.blade.php`)

-   **Target Users**: Users with `quotation.view`, `sales_order.view`, `delivery_order.view`, or `pelanggan.view`
-   **Features**:
    -   Monthly quotations and sales orders
    -   Conversion rate tracking
    -   Delivery status overview
    -   Top customers of the month
    -   Recent quotations and sales orders
    -   Quick action buttons for creating new records

#### Production Dashboard (`/dashboards/production.blade.php`)

-   **Target Users**: Users with `work_order.view`, `bill_of_material.view`, or `bahan_baku.view`
-   **Features**:
    -   Work order status tracking (Planned, Running, Completed, Cancelled)
    -   Active work orders list
    -   Material usage tracking
    -   Production capacity (Active BOMs)
    -   Production warehouse stock levels

#### HR Dashboard (`/dashboards/hr.blade.php`)

-   **Target Users**: Users with `karyawan.view`, `absensi.view`, `cuti.view`, or `department.view`
-   **Features**:
    -   Employee count and attendance metrics
    -   Today's attendance list
    -   Department breakdown
    -   Pending leave requests
    -   Current employees on leave
    -   30-day attendance chart

#### Inventory Dashboard (`/dashboards/inventory.blade.php`)

-   **Target Users**: Users with `stok_barang.view`, `transfer_gudang.view`, `gudang.view`, or `penyesuaian_stok.view`
-   **Features**:
    -   Product and stock overview
    -   Low stock alerts (with visual indicators)
    -   Warehouse overview with capacity
    -   Recent stock transfers
    -   Stock distribution by category
    -   Monthly stock movements tracking

#### Purchasing Dashboard (`/dashboards/purchasing.blade.php`)

-   **Target Users**: Users with `purchase_order.view`, `purchase_request.view`, `penerimaan_barang.view`, or `supplier.view`
-   **Features**:
    -   Purchase request status tracking
    -   Monthly PO statistics
    -   Top suppliers of the month
    -   Recent purchase orders and goods receipts
    -   Outstanding purchase orders table

#### Default Dashboard (`/dashboards/default.blade.php`)

-   **Target Users**: Users who don't fit into specific role groups or have limited access
-   **Features**:
    -   Welcome message with user name
    -   Basic statistics (if user has access)
    -   Available modules based on permissions
    -   Recent activities (if any)
    -   Help & support section

### 3. Smart Permission-Based Role Detection

The `determineRoleGroup()` method in DashboardController uses a hierarchical approach:

1. **Management** - Users with reporting access + multiple module access
2. **Finance** - Users with accounting/financial permissions
3. **Sales** - Users with sales-related permissions
4. **Production** - Users with manufacturing permissions
5. **HR** - Users with human resources permissions
6. **Inventory** - Users with stock/warehouse permissions
7. **Purchasing** - Users with procurement permissions
8. **Default** - All other users

### 4. Dashboard Features

#### Common Features Across All Dashboards:

-   Responsive design with Tailwind CSS
-   Dark mode support
-   Hover animations and transitions
-   Chart.js integration for data visualization
-   Quick action buttons based on user permissions
-   Recent activities where applicable

#### Security Features:

-   Permission-based content rendering
-   Quick action buttons only appear if user has required permissions
-   Role-based routing prevents unauthorized access to specific dashboard data

### 5. Controller Structure

```php
DashboardController@index() {
    1. Get authenticated user
    2. Determine role group based on permissions
    3. Route to appropriate dashboard method
    4. Return view with role-specific data
}
```

Each role-specific method:

-   Gathers relevant data for that role
-   Applies necessary business logic
-   Returns optimized view with focused metrics

### 6. File Structure

```
resources/views/dashboards/
├── management.blade.php    # Executive/management dashboard
├── finance.blade.php       # Finance team dashboard
├── sales.blade.php         # Sales team dashboard
├── production.blade.php    # Production team dashboard
├── hr.blade.php           # HR team dashboard
├── inventory.blade.php     # Inventory/warehouse dashboard
├── purchasing.blade.php    # Purchasing team dashboard
└── default.blade.php       # Default dashboard for unclassified users
```

### 7. Benefits

1. **Improved User Experience**: Users see only relevant information for their role
2. **Better Performance**: Reduced data loading for irrelevant modules
3. **Enhanced Security**: Role-based access control at dashboard level
4. **Scalability**: Easy to add new role groups or modify existing ones
5. **Maintainability**: Organized structure with dedicated views per role

### 8. Testing the Implementation

To test the role-based dashboards:

1. **Create test users** with different permission combinations
2. **Login as different users** to see appropriate dashboards
3. **Verify permission-based features** appear/disappear correctly
4. **Test quick action buttons** based on user permissions
5. **Verify data filtering** shows only relevant information

### 9. Future Enhancements

1. **User Preferences**: Allow users to customize their dashboard layout
2. **Dashboard Widgets**: Modular widget system for customizable dashboards
3. **Real-time Updates**: WebSocket integration for live data updates
4. **Export Features**: Dashboard data export functionality
5. **Mobile Optimization**: Enhanced mobile dashboard experience

## Installation Notes

1. All dashboard views are created in `/resources/views/dashboards/`
2. The main DashboardController has been updated with role-based routing
3. No database migrations required - uses existing permission system
4. Compatible with existing sidebar permission enhancements
5. Maintains backward compatibility with original dashboard functionality

This implementation provides a comprehensive role-based dashboard system that enhances user experience by showing relevant information based on user roles and permissions.

# Automatic Product Request Workflow

This document describes the workflow for automatically generating product requests (permintaan barang) from sales orders and the subsequent delivery order creation process.

## Overview

The workflow automates the process of creating product requests when sales orders are created, eliminating the need for warehouse staff to manually input data. It allows warehouse staff to immediately prepare products for delivery orders based on these automated requests.

## Features Implemented

1. **Automatic Product Request Generation**

    - Sales order form includes a checkbox to generate product requests automatically
    - Warehouse selection dropdown appears when the checkbox is selected
    - Product requests are created with status "menunggu" (waiting)
    - **NEW: Detection of pending sales orders that require product requests**
    - **NEW: Instant processing of sales orders directly from the product request list**
    - **IMPROVED: Proper handling of sales orders with partial shipments**

2. **Integration with Delivery Orders**

    - Product requests can be used to create delivery orders directly
    - Delivery orders created from product requests maintain a reference to the source request
    - Product request status is automatically updated based on delivery order status changes

3. **Status Synchronization**
    - When a delivery order is created from a product request, the request status changes to "diproses" (processing)
    - When a delivery order is processed, the product request status changes to "selesai" (completed)
    - When a delivery order is canceled, the product request status reverts to "menunggu" (waiting)

## Workflow Steps

### Creating a Sales Order with Automatic Product Request

1. User creates a new sales order
2. User checks the "Buat Permintaan Barang Otomatis" checkbox
3. User selects a warehouse from the dropdown
4. User completes and submits the sales order form
5. System automatically creates a product request with:
    - Reference to the sales order
    - Items from the sales order
    - Status "menunggu"
    - Selected warehouse

### Auto-Detecting Pending Sales Orders

1. When a user opens the product request page, the system automatically checks for sales orders without product requests
2. These pending sales orders are displayed at the top of the page with an "Auto Process" button
3. Both unshipped sales orders and partially shipped sales orders are included, showing their status
4. The user can:
    - Process each sales order individually by clicking "Proses Otomatis"
    - Hide the notification section if not needed

### Auto-Processing Pending Sales Orders

1. When the user clicks "Proses Otomatis" for a sales order:
    - A modal appears asking to select a warehouse, showing the sales order's shipping status
    - After selecting a warehouse and confirming, the system creates a product request automatically
    - For partially shipped orders, only the remaining quantities that need to be shipped are included
    - The system checks stock availability for each product
    - If any products have insufficient stock, the user is notified with details
2. The user is redirected to the newly created product request details page

### Handling Partially Shipped Sales Orders

1. For sales orders with status "sebagian" (partially shipped):
    - The system calculates the remaining quantity for each product (quantity - quantity_terkirim)
    - Only products with remaining quantities greater than zero are included in the product request
    - The product request notes indicate that it's for remaining quantities from a partially shipped order
2. This ensures that only products that still need to be shipped are included in the product request

### Processing the Product Request

1. Warehouse staff views the list of product requests
2. Warehouse staff clicks on a product request to view details
3. Staff can:
    - Update the status manually via the "Update Status" button
    - Create a delivery order directly via the "Buat Delivery Order" button

### Creating a Delivery Order from a Product Request

1. When "Buat Delivery Order" is clicked, the system redirects to the delivery order creation form
2. The form is pre-filled with data from the product request:
    - Sales order reference
    - Warehouse
    - Products and quantities
3. The delivery order maintains a reference to the source product request
4. When the delivery order is created, the product request status is automatically updated to "diproses"

### Completing the Process

1. When the delivery order is processed (status changes to "dikirim"), the product request status is updated to "selesai"
2. When the delivery order is completed (status changes to "diterima"), the product request status remains "selesai"
3. If the delivery order is canceled, the product request status reverts to "menunggu"

## Database Relationships

-   `SalesOrder` has a one-to-many relationship with `PermintaanBarang`
-   `PermintaanBarang` has a one-to-many relationship with `DeliveryOrder`
-   `PermintaanBarang` has a one-to-many relationship with `PermintaanBarangDetail`
-   `PermintaanBarangDetail` references products and their quantities

## Status Flow

Product Request (`PermintaanBarang`) status transitions:

-   "menunggu" → "diproses" (when delivery order is created)
-   "diproses" → "selesai" (when delivery order is processed)
-   "selesai" or "diproses" → "menunggu" (when delivery order is canceled)
-   Status can also be manually updated via the UI

## Best Practices for Users

1. Always check stock availability before creating automatic product requests
2. Monitor the status of product requests in the "Permintaan Barang" section
3. Use the "Buat Delivery Order" button for efficient workflow
4. Update product request status manually when necessary
5. Regularly check the "Sales Order Menunggu Diproses" section at the top of the product request page
6. Process pending sales orders promptly to ensure timely order fulfillment
7. Pay attention to stock shortage warnings when auto-processing sales orders
8. When working with partially shipped orders, verify the remaining quantities in the product request
9. Check the status indicator on pending sales orders to understand their current shipping state
10. Remember that for partially shipped orders, only the remaining unshipped quantities will be included in new product requests
11. Use the information in the confirmation dialog to understand what will be processed when auto-generating product requests

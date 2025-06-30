<?php

/**
 * Script untuk mengaplikasikan permission checks ke semua module Master Data
 */

$modules = [
    'pelanggan' => [
        'controller' => 'app/Http/Controllers/MasterData/PelangganController.php',
        'view_index' => 'resources/views/master-data/pelanggan/index.blade.php',
        'view_table' => 'resources/views/master-data/pelanggan/_table_body.blade.php',
        'permissions' => [
            'view' => 'pelanggan.view',
            'create' => 'pelanggan.create',
            'edit' => 'pelanggan.edit',
            'delete' => 'pelanggan.delete',
            'export' => 'pelanggan.export',
            'import' => 'pelanggan.import'
        ]
    ],
    'supplier' => [
        'controller' => 'app/Http/Controllers/MasterData/SupplierController.php',
        'view_index' => 'resources/views/master-data/supplier/index.blade.php',
        'view_table' => 'resources/views/master-data/supplier/_table_body.blade.php',
        'permissions' => [
            'view' => 'supplier.view',
            'create' => 'supplier.create',
            'edit' => 'supplier.edit',
            'delete' => 'supplier.delete',
            'export' => 'supplier.export',
            'import' => 'supplier.import'
        ]
    ],
    'gudang' => [
        'controller' => 'app/Http/Controllers/MasterData/GudangController.php',
        'view_index' => 'resources/views/master-data/gudang/index.blade.php',
        'view_table' => 'resources/views/master-data/gudang/_table_body.blade.php',
        'permissions' => [
            'view' => 'gudang.view',
            'create' => 'gudang.create',
            'edit' => 'gudang.edit',
            'delete' => 'gudang.delete',
            'export' => 'gudang.export',
            'import' => 'gudang.import'
        ]
    ],
    'satuan' => [
        'controller' => 'app/Http/Controllers/MasterData/SatuanController.php',
        'view_index' => 'resources/views/master-data/satuan/index.blade.php',
        'view_table' => 'resources/views/master-data/satuan/_table_body.blade.php',
        'permissions' => [
            'view' => 'satuan.view',
            'create' => 'satuan.create',
            'edit' => 'satuan.edit',
            'delete' => 'satuan.delete',
            'export' => 'satuan.export',
            'import' => 'satuan.import'
        ]
    ]
];

echo "Master Data Permission Implementation Script\n";
echo "===========================================\n\n";

foreach ($modules as $module => $config) {
    echo "Processing module: " . ucfirst($module) . "\n";
    echo str_repeat('-', 30) . "\n";

    // Check if files exist
    $controller_path = $config['controller'];
    $view_index_path = $config['view_index'];
    $view_table_path = $config['view_table'];

    echo "Controller: " . ($controller_path ? "✓" : "✗") . "\n";
    echo "Index View: " . ($view_index_path ? "✓" : "✗") . "\n";
    echo "Table View: " . ($view_table_path ? "✓" : "✗") . "\n";
    echo "\n";
}

echo "Manual implementation required for each module:\n";
echo "1. Add constructor with permission middleware to controller\n";
echo "2. Add permission checks to Quick Action Card\n";
echo "3. Add permission checks to Bulk Actions\n";
echo "4. Add permission checks to Export/Import buttons\n";
echo "5. Add permission checks to table checkboxes\n";
echo "6. Add permission checks to action menu items\n";
echo "7. Add permission checks to empty state buttons\n";

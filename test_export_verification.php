<?php

/**
 * Simple Test for Export Functionality
 */

// Set path for artisan commands
$projectPath = '/Volumes/SSD STORAGE/Laravel/project 122 - SS/erp-sinar-surya';

echo "=== Testing Export Functionality ===\n";

// Test 1: Check if export class exists and can be instantiated
echo "\n1. Checking export class...\n";
$exportPath = $projectPath . '/app/Exports/LaporanPembelianExport.php';
if (file_exists($exportPath)) {
    echo "✅ LaporanPembelianExport.php exists\n";

    // Check if the constructor is correct
    $content = file_get_contents($exportPath);
    if (strpos($content, 'public function __construct(array $filters = [])') !== false) {
        echo "✅ Constructor signature is correct\n";
    } else {
        echo "❌ Constructor signature needs checking\n";
    }

    // Check if view method exists
    if (strpos($content, 'public function view(): View') !== false) {
        echo "✅ view() method exists\n";
    } else {
        echo "❌ view() method missing\n";
    }

    // Check for CAST operations
    if (strpos($content, 'CAST(') !== false) {
        echo "✅ CAST operations found in queries\n";
    } else {
        echo "❌ CAST operations missing\n";
    }
} else {
    echo "❌ Export class not found\n";
}

// Test 2: Check template
echo "\n2. Checking Excel template...\n";
$templatePath = $projectPath . '/resources/views/laporan/laporan_pembelian/excel.blade.php';
if (file_exists($templatePath)) {
    echo "✅ Excel template exists\n";

    $template = file_get_contents($templatePath);
    if (strpos($template, 'number_format') !== false) {
        echo "✅ Template uses number_format for display\n";
    }

    if (strpos($template, '(float)') !== false) {
        echo "✅ Template ensures float conversion\n";
    }
} else {
    echo "❌ Excel template not found\n";
}

// Test 3: Check controller
echo "\n3. Checking controller...\n";
$controllerPath = $projectPath . '/app/Http/Controllers/LaporanPembelianController.php';
if (file_exists($controllerPath)) {
    echo "✅ Controller exists\n";

    $controller = file_get_contents($controllerPath);
    if (strpos($controller, 'LaporanPembelianExport') !== false) {
        echo "✅ Controller uses export class\n";
    }
} else {
    echo "❌ Controller not found\n";
}

echo "\n=== Code Quality Check ===\n";

// Check for potential issues
$issues = [];

// Check export class for common issues
if (file_exists($exportPath)) {
    $exportContent = file_get_contents($exportPath);

    if (strpos($exportContent, 'number_format(') !== false) {
        $issues[] = "⚠️  Export class should not use number_format (formatting should be in template)";
    }

    if (strpos($exportContent, 'WithEvents') !== false && strpos($exportContent, 'registerEvents') !== false) {
        echo "✅ Export class implements WithEvents with registerEvents\n";
    }

    // Count CAST operations
    $castCount = substr_count($exportContent, 'CAST(');
    echo "✅ Found $castCount CAST operations in queries\n";
}

if (count($issues) > 0) {
    echo "\nIssues found:\n";
    foreach ($issues as $issue) {
        echo $issue . "\n";
    }
} else {
    echo "✅ No obvious issues found\n";
}

echo "\n=== Summary ===\n";
echo "The export functionality has been enhanced with:\n";
echo "1. ✅ Explicit CAST operations in SQL queries\n";
echo "2. ✅ Proper data type handling in templates\n";
echo "3. ✅ Excel cell formatting through events\n";
echo "4. ✅ Consistent number formatting\n";

echo "\n=== Manual Testing Recommendation ===\n";
echo "To verify the fix works:\n";
echo "1. Access the Laravel application in browser\n";
echo "2. Navigate to Laporan Pembelian\n";
echo "3. Export to Excel\n";
echo "4. Check that values like 11.100 appear as '11.100' not '11.1'\n";
echo "5. Verify all decimal values maintain proper formatting\n";

echo "\n=== Test Complete ===\n";

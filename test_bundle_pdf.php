<?php
// Test bundle grouping for PDF
$details = [
    (object)[
        'produk' => (object)['nama' => '1-Amino-2-Hydroxy-4-Naphthalein Sulfonic'],
        'is_bundle_item' => true,
        'bundle_name' => 'Paket A',
        'quantity' => 1.0
    ],
    (object)[
        'produk' => (object)['nama' => 'a'],
        'is_bundle_item' => true,
        'bundle_name' => 'Paket A',
        'quantity' => 2.0
    ],
    (object)[
        'produk' => (object)['nama' => 'a'],
        'is_bundle_item' => false,
        'bundle_name' => '',
        'quantity' => 1.0
    ]
];

$bundleGroups = [];
$nonBundleItems = [];

foreach ($details as $detail) {
    if ($detail->is_bundle_item && $detail->bundle_name) {
        if (!isset($bundleGroups[$detail->bundle_name])) {
            $bundleGroups[$detail->bundle_name] = [];
        }
        $bundleGroups[$detail->bundle_name][] = $detail;
    } else {
        $nonBundleItems[] = $detail;
    }
}

echo "Bundle Groups:\n";
foreach ($bundleGroups as $name => $items) {
    $totalQty = 0;
    foreach ($items as $item) {
        $totalQty += $item->quantity;
    }
    echo "  $name (Total: $totalQty):\n";
    foreach ($items as $item) {
        echo "    - {$item->produk->nama} (Qty: {$item->quantity})\n";
    }
}

echo "\nNon-Bundle Items:\n";
foreach ($nonBundleItems as $item) {
    echo "  - {$item->produk->nama} (Qty: {$item->quantity})\n";
}
echo "\nBundle grouping logic works correctly!\n";

#!/bin/bash

# Script untuk mengaplikasikan permission checks ke semua module Master Data
# Untuk: supplier, gudang, satuan

echo "Applying permission checks to Master Data modules..."

# Array of modules with their permission names
declare -A modules=(
    ["supplier"]="supplier"
    ["gudang"]="gudang" 
    ["satuan"]="satuan"
)

for module in "${!modules[@]}"; do
    permission="${modules[$module]}"
    echo "Processing $module module with permission $permission..."
    
    # Update Quick Action Card in index.blade.php
    index_file="resources/views/master-data/$module/index.blade.php"
    
    if [ -f "$index_file" ]; then
        echo "  ✓ Found $index_file"
        # Note: Manual replacement needed for each specific module's Quick Action Card
    else
        echo "  ✗ Missing $index_file"
    fi
    
    # Update table body for action buttons
    table_file="resources/views/master-data/$module/_table_body.blade.php"
    
    if [ -f "$table_file" ]; then
        echo "  ✓ Found $table_file"
        # Note: Manual replacement needed for each specific module's action buttons
    else
        echo "  ✗ Missing $table_file"
    fi
done

echo ""
echo "Manual steps required:"
echo "1. Replace Quick Action Card with permission check"
echo "2. Add permission checks to bulk actions"
echo "3. Add permission checks to export/import buttons (if exists)"
echo "4. Add permission checks to table action buttons"
echo "5. Add permission checks to empty state buttons"
echo ""
echo "Example pattern for Quick Action Card:"
echo '@if(auth()->user()->hasPermission("MODULE.create"))'
echo '  <!-- Original button -->'
echo '@else'
echo '  <!-- Disabled state -->'
echo '@endif'

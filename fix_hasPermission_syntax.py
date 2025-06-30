#!/usr/bin/env python3
import os
import re
import glob

def fix_permission_syntax(file_path):
    """Fix malformed hasPermission calls in a blade file"""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    original_content = content
    
    # Pattern 1: hasPermission(permission_name)') -> hasPermission('permission_name')
    content = re.sub(r"hasPermission\(([a-zA-Z_\.]+)\)'\)", r"hasPermission('\1')", content)
    
    # Pattern 2: hasPermission(permission_name)')) -> hasPermission('permission_name')
    content = re.sub(r"hasPermission\(([a-zA-Z_\.]+)\)'\)\)", r"hasPermission('\1')", content)
    
    # Pattern 3: hasPermission(permission_name)' -> hasPermission('permission_name')
    content = re.sub(r"hasPermission\(([a-zA-Z_\.]+)\)'", r"hasPermission('\1')", content)
    
    # Pattern 4: hasPermission(permission_name) -> hasPermission('permission_name')
    content = re.sub(r"hasPermission\(([a-zA-Z_\.]+)\)(?!\))", r"hasPermission('\1')", content)
    
    if content != original_content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed: {file_path}")
        return True
    return False

def main():
    # Find all blade files in the produksi directory
    pattern = "/Volumes/SSD STORAGE/Laravel/project 122 - SS/erp-sinar-surya/resources/views/produksi/**/*.blade.php"
    blade_files = glob.glob(pattern, recursive=True)
    
    fixed_count = 0
    for file_path in blade_files:
        if fix_permission_syntax(file_path):
            fixed_count += 1
    
    print(f"\nFixed {fixed_count} files")

if __name__ == "__main__":
    main()

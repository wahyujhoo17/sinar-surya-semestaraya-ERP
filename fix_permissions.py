#!/usr/bin/env python3
import os
import re
import glob

def fix_permission_calls(content):
    # Pattern to match malformed hasPermission calls
    patterns = [
        # Pattern 1: hasPermission(permission_name)')
        (r"hasPermission\(([a-z_\.]+)\)'\)", r"hasPermission('\1')"),
        # Pattern 2: hasPermission(permission_name)'')
        (r"hasPermission\(([a-z_\.]+)\)''\)", r"hasPermission('\1')"),
        # Pattern 3: hasPermission(permission_name')
        (r"hasPermission\(([a-z_\.]+)'\)", r"hasPermission('\1')"),
        # Pattern 4: hasPermission(permission_name))
        (r"hasPermission\(([a-z_\.]+)\)\)", r"hasPermission('\1')"),
        # Pattern 5: ensure no double quotes inside
        (r"hasPermission\('([a-z_\.]+)'\)", r"hasPermission('\1')"),
    ]
    
    for pattern, replacement in patterns:
        content = re.sub(pattern, replacement, content)
    
    return content

def main():
    base_path = "/Volumes/SSD STORAGE/Laravel/project 122 - SS/erp-sinar-surya"
    pattern = os.path.join(base_path, "resources/views/produksi/**/*.blade.php")
    
    files = glob.glob(pattern, recursive=True)
    
    for file_path in files:
        print(f"Processing: {file_path}")
        
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            fixed_content = fix_permission_calls(content)
            
            if content != fixed_content:
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(fixed_content)
                print(f"  - Fixed permission calls in {file_path}")
            else:
                print(f"  - No changes needed in {file_path}")
                
        except Exception as e:
            print(f"Error processing {file_path}: {e}")

if __name__ == "__main__":
    main()

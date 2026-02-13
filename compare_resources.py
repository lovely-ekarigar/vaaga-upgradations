#!/usr/bin/env python3
"""
Resources Directory Comparison Script
Compares resources between current and old Laravel projects
"""

import os
from pathlib import Path
from collections import defaultdict

def get_all_files(directory, extensions=None):
    """Get all files in directory with optional extension filtering"""
    files = []
    if not os.path.exists(directory):
        return files
    
    for root, dirs, filenames in os.walk(directory):
        for filename in filenames:
            if extensions:
                if any(filename.endswith(ext) for ext in extensions):
                    full_path = os.path.join(root, filename)
                    rel_path = os.path.relpath(full_path, directory)
                    files.append(rel_path.replace('\\', '/'))
            else:
                full_path = os.path.join(root, filename)
                rel_path = os.path.relpath(full_path, directory)
                files.append(rel_path.replace('\\', '/'))
    
    return sorted(files)

def get_directory_structure(directory):
    """Get directory structure with file counts"""
    structure = {}
    if not os.path.exists(directory):
        return structure
    
    for root, dirs, files in os.walk(directory):
        rel_path = os.path.relpath(root, directory)
        if rel_path == '.':
            rel_path = ''
        structure[rel_path.replace('\\', '/')] = len(files)
    
    return structure

def main():
    current_project = r'c:\Projects\vaagaacademy'
    old_project = r'C:\Users\malik\Downloads\_public_html_vaaga_mock'
    
    print("=" * 60)
    print("RESOURCES DIRECTORY COMPARISON")
    print("=" * 60)
    
    # Check if directories exist
    print("\nChecking project paths...")
    print(f"Current project: {current_project} - {'EXISTS' if os.path.exists(current_project) else 'NOT FOUND'}")
    print(f"Old project: {old_project} - {'EXISTS' if os.path.exists(old_project) else 'NOT FOUND'}")
    
    if not os.path.exists(current_project):
        print("ERROR: Current project directory not found!")
        return
    
    if not os.path.exists(old_project):
        print("ERROR: Old project directory not found!")
        return
    
    # 1. Blade View Files Comparison
    print("\n" + "=" * 60)
    print("1. BLADE VIEW FILES COMPARISON")
    print("=" * 60)
    
    current_blade = get_all_files(os.path.join(current_project, 'resources', 'views'), ['.blade.php'])
    old_blade = get_all_files(os.path.join(old_project, 'resources', 'views'), ['.blade.php'])
    
    print(f"\nCurrent Project: {len(current_blade)} blade files")
    print(f"Old Project: {len(old_blade)} blade files")
    
    missing_blade = sorted(set(old_blade) - set(current_blade))
    new_blade = sorted(set(current_blade) - set(old_blade))
    
    print(f"\n--- MISSING IN CURRENT PROJECT ({len(missing_blade)} files) ---")
    for f in missing_blade:
        print(f"  - {f}")
    
    print(f"\n--- NEW IN CURRENT PROJECT ({len(new_blade)} files) ---")
    for f in new_blade[:20]:  # Limit output
        print(f"  - {f}")
    if len(new_blade) > 20:
        print(f"  ... and {len(new_blade) - 20} more files")
    
    # 2. JavaScript Assets Comparison
    print("\n" + "=" * 60)
    print("2. JAVASCRIPT ASSETS COMPARISON")
    print("=" * 60)
    
    current_js = get_all_files(os.path.join(current_project, 'resources'), ['.js'])
    old_js = get_all_files(os.path.join(old_project, 'resources'), ['.js'])
    
    print(f"\nCurrent Project: {len(current_js)} JS files")
    print(f"Old Project: {len(old_js)} JS files")
    
    missing_js = sorted(set(old_js) - set(current_js))
    new_js = sorted(set(current_js) - set(old_js))
    
    print(f"\n--- MISSING IN CURRENT PROJECT ({len(missing_js)} files) ---")
    for f in missing_js:
        print(f"  - {f}")
    
    print(f"\n--- NEW IN CURRENT PROJECT ({len(new_js)} files) ---")
    for f in new_js:
        print(f"  - {f}")
    
    # 3. CSS/SASS Assets Comparison
    print("\n" + "=" * 60)
    print("3. CSS/SASS ASSETS COMPARISON")
    print("=" * 60)
    
    current_css = get_all_files(os.path.join(current_project, 'resources'), ['.css', '.scss', '.sass'])
    old_css = get_all_files(os.path.join(old_project, 'resources'), ['.css', '.scss', '.sass'])
    
    print(f"\nCurrent Project: {len(current_css)} CSS/SCSS/SASS files")
    print(f"Old Project: {len(old_css)} CSS/SCSS/SASS files")
    
    missing_css = sorted(set(old_css) - set(current_css))
    new_css = sorted(set(current_css) - set(old_css))
    
    print(f"\n--- MISSING IN CURRENT PROJECT ({len(missing_css)} files) ---")
    for f in missing_css:
        print(f"  - {f}")
    
    print(f"\n--- NEW IN CURRENT PROJECT ({len(new_css)} files) ---")
    for f in new_css:
        print(f"  - {f}")
    
    # 4. Translation Files Comparison
    print("\n" + "=" * 60)
    print("4. TRANSLATION FILES COMPARISON")
    print("=" * 60)
    
    current_lang = get_all_files(os.path.join(current_project, 'resources', 'lang'))
    old_lang = get_all_files(os.path.join(old_project, 'resources', 'lang'))
    
    print(f"\nCurrent Project: {len(current_lang)} translation files")
    print(f"Old Project: {len(old_lang)} translation files")
    
    missing_lang = sorted(set(old_lang) - set(current_lang))
    new_lang = sorted(set(current_lang) - set(old_lang))
    
    print(f"\n--- MISSING IN CURRENT PROJECT ({len(missing_lang)} files) ---")
    for f in missing_lang[:30]:  # Limit output
        print(f"  - {f}")
    if len(missing_lang) > 30:
        print(f"  ... and {len(missing_lang) - 30} more files")
    
    print(f"\n--- NEW IN CURRENT PROJECT ({len(new_lang)} files) ---")
    for f in new_lang[:10]:  # Limit output
        print(f"  - {f}")
    if len(new_lang) > 10:
        print(f"  ... and {len(new_lang) - 10} more files")
    
    # 5. Directory Structure Comparison
    print("\n" + "=" * 60)
    print("5. RESOURCES DIRECTORY STRUCTURE")
    print("=" * 60)
    
    current_structure = get_directory_structure(os.path.join(current_project, 'resources'))
    old_structure = get_directory_structure(os.path.join(old_project, 'resources'))
    
    print("\n--- Current Project Structure ---")
    for dir_path in sorted(current_structure.keys())[:30]:
        if dir_path:
            print(f"  {dir_path}: {current_structure[dir_path]} files")
    
    print("\n--- Old Project Structure ---")
    for dir_path in sorted(old_structure.keys())[:30]:
        if dir_path:
            print(f"  {dir_path}: {old_structure[dir_path]} files")
    
    # Summary
    print("\n" + "=" * 60)
    print("SUMMARY")
    print("=" * 60)
    print(f"\nMissing Blade Views: {len(missing_blade)}")
    print(f"Missing JS Assets: {len(missing_js)}")
    print(f"Missing CSS/SASS Assets: {len(missing_css)}")
    print(f"Missing Translation Files: {len(missing_lang)}")
    
    # Save detailed results to file
    output_file = os.path.join(current_project, 'resources_comparison_report.txt')
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("=" * 80 + "\n")
        f.write("RESOURCES DIRECTORY COMPARISON REPORT\n")
        f.write("=" * 80 + "\n\n")
        
        f.write("MISSING BLADE VIEW FILES:\n")
        f.write("-" * 80 + "\n")
        for item in missing_blade:
            f.write(f"  {item}\n")
        f.write(f"\nTotal: {len(missing_blade)} files\n\n")
        
        f.write("MISSING JS ASSETS:\n")
        f.write("-" * 80 + "\n")
        for item in missing_js:
            f.write(f"  {item}\n")
        f.write(f"\nTotal: {len(missing_js)} files\n\n")
        
        f.write("MISSING CSS/SASS ASSETS:\n")
        f.write("-" * 80 + "\n")
        for item in missing_css:
            f.write(f"  {item}\n")
        f.write(f"\nTotal: {len(missing_css)} files\n\n")
        
        f.write("MISSING TRANSLATION FILES:\n")
        f.write("-" * 80 + "\n")
        for item in missing_lang:
            f.write(f"  {item}\n")
        f.write(f"\nTotal: {len(missing_lang)} files\n\n")
        
        f.write("NEW IN CURRENT PROJECT:\n")
        f.write("-" * 80 + "\n")
        f.write(f"Blade Views: {len(new_blade)} files\n")
        f.write(f"JS Assets: {len(new_js)} files\n")
        f.write(f"CSS/SASS Assets: {len(new_css)} files\n")
        f.write(f"Translation Files: {len(new_lang)} files\n")
    
    print(f"\nDetailed report saved to: {output_file}")

if __name__ == '__main__':
    main()

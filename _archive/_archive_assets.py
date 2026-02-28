#!/usr/bin/env python3
"""Script to move image/documentation assets to _archive/assets/"""

import os
import shutil

# Project root
project_root = r"C:\Projects\vaagaacademy"
archive_dir = os.path.join(project_root, "_archive", "assets")

# Create archive directory if it doesn't exist
os.makedirs(archive_dir, exist_ok=True)
print(f"Archive directory: {archive_dir}")

# Files to move
files_to_move = [
    "batch-progress-fixed.png",
    "contact_page_current.png",
    "contact_page_detailed.md",
    "contact_page_full.png",
    "contact_page_new_design.png",
    "contact_page_with_header.png",
    "homepage_fixed.png",
    "homepage_working.png",
    "lessons_page_debug.png"
]

moved_files = []
not_found_files = []

for filename in files_to_move:
    source_path = os.path.join(project_root, filename)
    dest_path = os.path.join(archive_dir, filename)
    
    if os.path.exists(source_path):
        shutil.move(source_path, dest_path)
        moved_files.append(filename)
        print(f"✓ Moved: {filename}")
    else:
        not_found_files.append(filename)
        print(f"✗ Not found: {filename}")

# Summary
print("\n" + "="*50)
print("SUMMARY")
print("="*50)
print(f"\nFiles moved: {len(moved_files)}")
for f in moved_files:
    print(f"  - {f}")

if not_found_files:
    print(f"\nFiles not found: {len(not_found_files)}")
    for f in not_found_files:
        print(f"  - {f}")

# List archived files
print(f"\nContents of _archive/assets/:")
for f in os.listdir(archive_dir):
    print(f"  - {f}")

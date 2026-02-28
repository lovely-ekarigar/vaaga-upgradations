#!/usr/bin/env python3
"""
Vaaga Academy File Organization Script
Organizes documentation, SQL, and asset files into _archive folder
"""

import os
import shutil
from pathlib import Path

# Configuration
PROJECT_ROOT = Path("C:/Projects/vaagaacademy")
ARCHIVE_DIR = PROJECT_ROOT / "_archive"

# File categories
FILE_CATEGORIES = {
    "reports": {
        "extensions": [".md"],
        "exclude": ["README.md", "ORGANIZATION_INSTRUCTIONS.md"],  # Keep these in root
        "description": "Documentation and audit reports"
    },
    "sql": {
        "extensions": [".sql"],
        "exclude": [],
        "description": "SQL migration and fix scripts"
    },
    "assets": {
        "extensions": [".png", ".jpg", ".jpeg", ".gif", ".svg"],
        "exclude": [],
        "description": "Screenshots and visual documentation"
    },
    "scripts": {
        "extensions": [".sh"],
        "exclude": [],
        "description": "Shell scripts for maintenance"
    }
}

def create_directories():
    """Create archive directory structure"""
    print("=" * 50)
    print("Creating archive directories...")
    print("=" * 50)
    
    for category in FILE_CATEGORIES.keys():
        dir_path = ARCHIVE_DIR / category
        dir_path.mkdir(parents=True, exist_ok=True)
        print(f"  ✓ _archive/{category}/")
    print()

def get_files_to_move():
    """Get all files organized by category"""
    files_to_move = {category: [] for category in FILE_CATEGORIES.keys()}
    
    for item in PROJECT_ROOT.iterdir():
        if not item.is_file():
            continue
            
        file_name = item.name
        file_ext = item.suffix.lower()
        
        for category, config in FILE_CATEGORIES.items():
            if file_ext in config["extensions"] and file_name not in config["exclude"]:
                files_to_move[category].append(item)
                break
    
    return files_to_move

def move_files(files_to_move):
    """Move files to their respective directories"""
    total_moved = 0
    
    for category, files in files_to_move.items():
        if not files:
            continue
            
        print(f"\nMoving {FILE_CATEGORIES[category]['description']}...")
        dest_dir = ARCHIVE_DIR / category
        
        for file_path in files:
            try:
                dest_path = dest_dir / file_path.name
                shutil.move(str(file_path), str(dest_path))
                print(f"  ✓ {file_path.name}")
                total_moved += 1
            except Exception as e:
                print(f"  ✗ {file_path.name} - Error: {e}")
    
    return total_moved

def create_readme():
    """Create README.md in archive folder"""
    readme_content = """# Project Documentation Archive

## Description

This folder contains historical documentation, SQL scripts, and related files for the Vaaga Academy project.

---

## Directory Structure

### reports/
**Purpose:** Documentation and audit reports (.md files)

### sql/
**Purpose:** SQL migration and fix scripts (.sql files)

### assets/
**Purpose:** Screenshots and visual documentation (.png, .jpg files)

### scripts/
**Purpose:** Shell scripts for maintenance (.sh files)

---

## Usage Guidelines

- **Reports:** Reference when understanding past decisions or troubleshooting
- **SQL Scripts:** Use as templates; verify before production use
- **Assets:** Reference for UI/UX comparisons
- **Scripts:** Test in safe environment before executing

---

*Organized by Python script*
"""
    
    readme_path = ARCHIVE_DIR / "README.md"
    with open(readme_path, 'w', encoding='utf-8') as f:
        f.write(readme_content)
    print(f"\n  ✓ Created _archive/README.md")

def print_summary(files_to_move, total_moved):
    """Print organization summary"""
    print("\n" + "=" * 50)
    print("Organization Complete!")
    print("=" * 50)
    
    for category, files in files_to_move.items():
        count = len(files)
        if count > 0:
            print(f"  {category:12s}: {count:3d} files")
    
    print(f"\n  Total moved: {total_moved} files")
    print("\nArchive structure:")
    print("  _archive/")
    print("    ├── reports/  - Documentation and audit reports")
    print("    ├── sql/      - SQL migration and fix scripts")
    print("    ├── assets/   - Screenshots and visual documentation")
    print("    └── scripts/  - Shell scripts for maintenance")

def main():
    """Main function"""
    print("\n" + "=" * 50)
    print("Vaaga Academy File Organization")
    print("=" * 50 + "\n")
    
    # Check if project root exists
    if not PROJECT_ROOT.exists():
        print(f"Error: Project root not found: {PROJECT_ROOT}")
        return
    
    # Create directories
    create_directories()
    
    # Get files to move
    files_to_move = get_files_to_move()
    
    # Check if any files found
    total_files = sum(len(files) for files in files_to_move.values())
    if total_files == 0:
        print("No files found to organize!")
        return
    
    print(f"Found {total_files} files to organize\n")
    
    # Move files
    total_moved = move_files(files_to_move)
    
    # Create README
    create_readme()
    
    # Print summary
    print_summary(files_to_move, total_moved)
    
    print("\n" + "=" * 50)
    print("Done! You can now delete organize.py")
    print("=" * 50 + "\n")

if __name__ == "__main__":
    main()

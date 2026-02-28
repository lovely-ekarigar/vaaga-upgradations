#!/usr/bin/env python3
"""
Laravel App Directory Comparison Script
Compares app directories between current project and old project
"""

import os
from pathlib import Path

current_project = Path('c:/Projects/vaagaacademy')
old_project = Path('C:/Users/malik/Downloads/_public_html_vaaga_mock')

def get_php_files(directory, relative_to=None):
    """Get all PHP files recursively from a directory."""
    files = {}
    if not directory.exists():
        return files
    
    base = relative_to or directory
    for root, dirs, filenames in os.walk(directory):
        # Skip hidden directories
        dirs[:] = [d for d in dirs if not d.startswith('.')]
        
        for filename in filenames:
            if filename.endswith('.php'):
                full_path = Path(root) / filename
                rel_path = full_path.relative_to(base)
                files[str(rel_path).replace('\\', '/')] = full_path
    
    return dict(sorted(files.items()))

def find_missing(old_files, current_files):
    """Find files that exist in old but not in current."""
    missing = {}
    for key in old_files:
        if key not in current_files:
            missing[key] = old_files[key]
    return missing

def print_section(title, files):
    """Print a section with files."""
    print(f"\n{'='*70}")
    print(f"{title}")
    print(f"{'='*70}")
    if not files:
        print("  (none)")
    else:
        for f in files:
            print(f"  • {f}")
    print(f"  Total: {len(files)} files")

def print_missing(title, files):
    """Print missing files."""
    print(f"\n{'='*70}")
    print(f"⚠️  {title}")
    print(f"{'='*70}")
    if not files:
        print("  ✅ No missing files")
    else:
        for f in files:
            print(f"  ❌ {f}")
    print(f"  Total Missing: {len(files)} files")

# Main comparison
print("\n" + "="*70)
print("LARAVEL APP DIRECTORY COMPARISON")
print("="*70)
print(f"\nCurrent Project: {current_project}")
print(f"Old Project: {old_project}")

# 1. Controllers
print("\n" + "-"*70)
print("1. CONTROLLERS COMPARISON")
print("-"*70)

current_controllers = get_php_files(current_project / 'app/Http/Controllers', current_project / 'app/Http/Controllers')
old_controllers = get_php_files(old_project / 'app/Http/Controllers', old_project / 'app/Http/Controllers')

print_section("Current Controllers", list(current_controllers.keys()))
print_section("Old Controllers", list(old_controllers.keys()))
missing_controllers = find_missing(old_controllers, current_controllers)
print_missing("MISSING CONTROLLERS", list(missing_controllers.keys()))

# 2. Models
print("\n" + "-"*70)
print("2. MODELS COMPARISON")
print("-"*70)

current_models = get_php_files(current_project / 'app/Models', current_project / 'app/Models')
old_models = get_php_files(old_project / 'app/Models', old_project / 'app/Models')

print_section("Current Models", list(current_models.keys()))
print_section("Old Models", list(old_models.keys()))
missing_models = find_missing(old_models, current_models)
print_missing("MISSING MODELS", list(missing_models.keys()))

# 3. Providers
print("\n" + "-"*70)
print("3. PROVIDERS COMPARISON")
print("-"*70)

current_providers = get_php_files(current_project / 'app/Providers', current_project / 'app/Providers')
old_providers = get_php_files(old_project / 'app/Providers', old_project / 'app/Providers')

print_section("Current Providers", list(current_providers.keys()))
print_section("Old Providers", list(old_providers.keys()))
missing_providers = find_missing(old_providers, current_providers)
print_missing("MISSING PROVIDERS", list(missing_providers.keys()))

# 4. Middleware
print("\n" + "-"*70)
print("4. MIDDLEWARE COMPARISON")
print("-"*70)

current_middleware = get_php_files(current_project / 'app/Http/Middleware', current_project / 'app/Http/Middleware')
old_middleware = get_php_files(old_project / 'app/Http/Middleware', old_project / 'app/Http/Middleware')

print_section("Current Middleware", list(current_middleware.keys()))
print_section("Old Middleware", list(old_middleware.keys()))
missing_middleware = find_missing(old_middleware, current_middleware)
print_missing("MISSING MIDDLEWARE", list(missing_middleware.keys()))

# 5. Full App Directory
print("\n" + "-"*70)
print("5. FULL APP DIRECTORY COMPARISON")
print("-"*70)

current_app = get_php_files(current_project / 'app', current_project / 'app')
old_app = get_php_files(old_project / 'app', old_project / 'app')

print_section("Current App Files (all)", list(current_app.keys()))
print_section("Old App Files (all)", list(old_app.keys()))
missing_app = find_missing(old_app, current_app)
print_missing("ALL MISSING FILES IN APP", list(missing_app.keys()))

# Summary
print("\n" + "="*70)
print("SUMMARY")
print("="*70)
print(f"""
📊 STATISTICS:
  Controllers    - Current: {len(current_controllers):3d} | Old: {len(old_controllers):3d} | Missing: {len(missing_controllers):3d}
  Models         - Current: {len(current_models):3d} | Old: {len(old_models):3d} | Missing: {len(missing_models):3d}
  Providers      - Current: {len(current_providers):3d} | Old: {len(old_providers):3d} | Missing: {len(missing_providers):3d}
  Middleware     - Current: {len(current_middleware):3d} | Old: {len(old_middleware):3d} | Missing: {len(missing_middleware):3d}
  Total App      - Current: {len(current_app):3d} | Old: {len(old_app):3d} | Missing: {len(missing_app):3d}

✅ Comparison Complete!
""")

# Save results to file
output_file = current_project / 'app_comparison_results.txt'
with open(output_file, 'w', encoding='utf-8') as f:
    f.write("LARAVEL APP DIRECTORY COMPARISON RESULTS\n")
    f.write(f"Generated: {__import__('datetime').datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n\n")
    
    f.write("="*70 + "\n")
    f.write("MISSING CONTROLLERS\n")
    f.write("="*70 + "\n")
    for fc in missing_controllers:
        f.write(f"  - {fc}\n")
    
    f.write("\n" + "="*70 + "\n")
    f.write("MISSING MODELS\n")
    f.write("="*70 + "\n")
    for fm in missing_models:
        f.write(f"  - {fm}\n")
    
    f.write("\n" + "="*70 + "\n")
    f.write("MISSING PROVIDERS\n")
    f.write("="*70 + "\n")
    for fp in missing_providers:
        f.write(f"  - {fp}\n")
    
    f.write("\n" + "="*70 + "\n")
    f.write("MISSING MIDDLEWARE\n")
    f.write("="*70 + "\n")
    for fm in missing_middleware:
        f.write(f"  - {fm}\n")
    
    f.write("\n\n" + "="*70 + "\n")
    f.write("ALL MISSING FILES\n")
    f.write("="*70 + "\n")
    for fa in missing_app:
        f.write(f"  - {fa}\n")

print(f"📝 Results saved to: {output_file}")

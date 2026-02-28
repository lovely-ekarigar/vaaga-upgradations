#!/usr/bin/env python3
import re

# Read the route references file
with open('route_references.txt', 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Find all route names - match route('name') or route("name")
pattern = r"route\(['\"]([^'\"]+)['\"]"
matches = re.findall(pattern, content)
route_names = sorted(set(matches))

# Write to file
with open('view_route_names.txt', 'w') as f:
    for name in route_names:
        f.write(name + '\n')

print('\n'.join(route_names[:100]))
print(f'\n... total {len(route_names)} routes')

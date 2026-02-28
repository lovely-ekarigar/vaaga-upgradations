#!/usr/bin/env node
/**
 * Vaaga Academy File Organization Script
 * Organizes documentation, SQL, and asset files into _archive folder
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = 'C:/Projects/vaagaacademy';
const ARCHIVE_DIR = path.join(PROJECT_ROOT, '_archive');

const categories = {
    reports: {
        extensions: ['.md'],
        exclude: ['README.md', 'ORGANIZATION_INSTRUCTIONS.md', 'organize.js'],
        desc: 'Documentation and audit reports'
    },
    sql: {
        extensions: ['.sql'],
        exclude: [],
        desc: 'SQL migration and fix scripts'
    },
    assets: {
        extensions: ['.png', '.jpg', '.jpeg', '.gif', '.svg'],
        exclude: [],
        desc: 'Screenshots and visual documentation'
    },
    scripts: {
        extensions: ['.sh'],
        exclude: [],
        desc: 'Shell scripts for maintenance'
    }
};

console.log('\n' + '='.repeat(50));
console.log('Vaaga Academy File Organization');
console.log('='.repeat(50) + '\n');

// Create directories
console.log('Creating archive directories...');
Object.keys(categories).forEach(cat => {
    const dir = path.join(ARCHIVE_DIR, cat);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
    console.log(`  ✓ _archive/${cat}/`);
});
console.log();

// Get files
const files = fs.readdirSync(PROJECT_ROOT);
const filesToMove = {};
Object.keys(categories).forEach(cat => filesToMove[cat] = []);

let totalFound = 0;

files.forEach(file => {
    const filePath = path.join(PROJECT_ROOT, file);
    const stat = fs.statSync(filePath);
    if (!stat.isFile()) return;
    
    const ext = path.extname(file).toLowerCase();
    
    Object.entries(categories).forEach(([cat, config]) => {
        if (config.extensions.includes(ext) && !config.exclude.includes(file)) {
            filesToMove[cat].push(file);
            totalFound++;
        }
    });
});

console.log(`Found ${totalFound} files to organize\n`);

// Move files
let totalMoved = 0;
Object.entries(filesToMove).forEach(([cat, files]) => {
    if (files.length === 0) return;
    
    console.log(`Moving ${categories[cat].desc}...`);
    const destDir = path.join(ARCHIVE_DIR, cat);
    
    files.forEach(file => {
        const source = path.join(PROJECT_ROOT, file);
        const dest = path.join(destDir, file);
        
        try {
            fs.renameSync(source, dest);
            console.log(`  ✓ ${file}`);
            totalMoved++;
        } catch (err) {
            console.log(`  ✗ ${file} - ${err.message}`);
        }
    });
});

// Create README
const readmeContent = `# Project Documentation Archive

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

*Organized by Node.js script*
`;

fs.writeFileSync(path.join(ARCHIVE_DIR, 'README.md'), readmeContent);
console.log('\n  ✓ Created _archive/README.md');

// Summary
console.log('\n' + '='.repeat(50));
console.log('Organization Complete!');
console.log('='.repeat(50));

Object.entries(filesToMove).forEach(([cat, files]) => {
    if (files.length > 0) {
        console.log(`  ${cat.padEnd(12)}: ${String(files.length).padStart(3)} files`);
    }
});

console.log(`\n  Total moved: ${totalMoved} files`);
console.log('\nArchive structure:');
console.log('  _archive/');
console.log('    ├── reports/  - Documentation and audit reports');
console.log('    ├── sql/      - SQL migration and fix scripts');
console.log('    ├── assets/   - Screenshots and visual documentation');
console.log('    └── scripts/  - Shell scripts for maintenance');

console.log('\n' + '='.repeat(50));
console.log('Done! You can now delete organize.js');
console.log('='.repeat(50) + '\n');

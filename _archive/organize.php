<?php
/**
 * Vaaga Academy File Organization Script
 * Organizes documentation, SQL, and asset files into _archive folder
 */

$projectRoot = 'C:/Projects/vaagaacademy';
$archiveDir = $projectRoot . '/_archive';

// File categories
$categories = [
    'reports' => [
        'extensions' => ['.md'],
        'exclude' => ['README.md', 'ORGANIZATION_INSTRUCTIONS.md', 'organize.php'],
        'desc' => 'Documentation and audit reports'
    ],
    'sql' => [
        'extensions' => ['.sql'],
        'exclude' => [],
        'desc' => 'SQL migration and fix scripts'
    ],
    'assets' => [
        'extensions' => ['.png', '.jpg', '.jpeg', '.gif', '.svg'],
        'exclude' => [],
        'desc' => 'Screenshots and visual documentation'
    ],
    'scripts' => [
        'extensions' => ['.sh'],
        'exclude' => [],
        'desc' => 'Shell scripts for maintenance'
    ]
];

echo "\n";
echo str_repeat("=", 50) . "\n";
echo "Vaaga Academy File Organization\n";
echo str_repeat("=", 50) . "\n\n";

// Create directories
echo "Creating archive directories...\n";
foreach ($categories as $category => $config) {
    $dir = $archiveDir . '/' . $category;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    echo "  ✓ _archive/$category/\n";
}
echo "\n";

// Get all files in root
$files = scandir($projectRoot);
$filesToMove = [];
$totalFound = 0;

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $filePath = $projectRoot . '/' . $file;
    if (!is_file($filePath)) continue;
    
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $extWithDot = '.' . $ext;
    
    foreach ($categories as $category => $config) {
        if (in_array($extWithDot, $config['extensions']) && !in_array($file, $config['exclude'])) {
            $filesToMove[$category][] = $file;
            $totalFound++;
            break;
        }
    }
}

echo "Found $totalFound files to organize\n\n";

// Move files
$totalMoved = 0;
foreach ($filesToMove as $category => $files) {
    if (empty($files)) continue;
    
    echo "Moving {$categories[$category]['desc']}...\n";
    $destDir = $archiveDir . '/' . $category;
    
    foreach ($files as $file) {
        $source = $projectRoot . '/' . $file;
        $dest = $destDir . '/' . $file;
        
        if (rename($source, $dest)) {
            echo "  ✓ $file\n";
            $totalMoved++;
        } else {
            echo "  ✗ $file (failed)\n";
        }
    }
}

// Create README
$readmeContent = <<<EOD
# Project Documentation Archive

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

*Organized by PHP script*
EOD;

file_put_contents($archiveDir . '/README.md', $readmeContent);
echo "\n  ✓ Created _archive/README.md\n";

// Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "Organization Complete!\n";
echo str_repeat("=", 50) . "\n";

foreach ($filesToMove as $category => $files) {
    $count = count($files);
    if ($count > 0) {
        printf("  %-12s: %3d files\n", $category, $count);
    }
}

echo "\n  Total moved: $totalMoved files\n";
echo "\nArchive structure:\n";
echo "  _archive/\n";
echo "    ├── reports/  - Documentation and audit reports\n";
echo "    ├── sql/      - SQL migration and fix scripts\n";
echo "    ├── assets/   - Screenshots and visual documentation\n";
echo "    └── scripts/  - Shell scripts for maintenance\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "Done! You can now delete organize.php\n";
echo str_repeat("=", 50) . "\n\n";
?>

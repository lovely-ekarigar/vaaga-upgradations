<?php
/**
 * Resources Directory Comparison Script
 * Compares resources between current and old Laravel projects
 */

class ResourceComparator {
    private $currentProject = 'c:\\Projects\\vaagaacademy';
    private $oldProject = 'C:\\Users\\malik\\Downloads\\_public_html_vaaga_mock';
    
    public function run() {
        echo "=== RESOURCES DIRECTORY COMPARISON ===\n\n";
        
        // Check if directories exist
        echo "Checking project paths...\n";
        echo "Current project: " . $this->currentProject . " - " . (is_dir($this->currentProject) ? "EXISTS" : "NOT FOUND") . "\n";
        echo "Old project: " . $this->oldProject . " - " . (is_dir($this->oldProject) ? "EXISTS" : "NOT FOUND") . "\n\n";
        
        if (!is_dir($this->currentProject)) {
            echo "ERROR: Current project directory not found!\n";
            return;
        }
        
        if (!is_dir($this->oldProject)) {
            echo "ERROR: Old project directory not found!\n";
            return;
        }
        
        // 1. Compare Blade Views
        echo "========================================\n";
        echo "1. BLADE VIEW FILES COMPARISON\n";
        echo "========================================\n";
        $this->compareBladeViews();
        
        // 2. Compare JS Assets
        echo "\n========================================\n";
        echo "2. JAVASCRIPT ASSETS COMPARISON\n";
        echo "========================================\n";
        $this->compareJSAssets();
        
        // 3. Compare CSS/SASS Assets
        echo "\n========================================\n";
        echo "3. CSS/SASS ASSETS COMPARISON\n";
        echo "========================================\n";
        $this->compareCSSAssets();
        
        // 4. Compare Translation Files
        echo "\n========================================\n";
        echo "4. TRANSLATION FILES COMPARISON\n";
        echo "========================================\n";
        $this->compareTranslations();
        
        // 5. Overall Directory Structure
        echo "\n========================================\n";
        echo "5. RESOURCES DIRECTORY STRUCTURE\n";
        echo "========================================\n";
        $this->compareDirectoryStructure();
    }
    
    private function compareBladeViews() {
        $currentViews = $this->getBladeFiles($this->currentProject . '\\resources\\views');
        $oldViews = $this->getBladeFiles($this->oldProject . '\\resources\\views');
        
        echo "\n--- Current Project Blade Files ---\n";
        echo "Total: " . count($currentViews) . " files\n";
        
        echo "\n--- Old Project Blade Files ---\n";
        echo "Total: " . count($oldViews) . " files\n";
        
        // Find missing files
        $missingInCurrent = array_diff($oldViews, $currentViews);
        $missingInOld = array_diff($currentViews, $oldViews);
        
        echo "\n--- MISSING IN CURRENT PROJECT ---\n";
        if (empty($missingInCurrent)) {
            echo "No blade files missing in current project.\n";
        } else {
            echo "Count: " . count($missingInCurrent) . " files\n";
            foreach ($missingInCurrent as $file) {
                echo "  - " . $file . "\n";
            }
        }
        
        echo "\n--- NEW IN CURRENT PROJECT ---\n";
        if (empty($missingInOld)) {
            echo "No new blade files in current project.\n";
        } else {
            echo "Count: " . count($missingInOld) . " files\n";
            foreach ($missingInOld as $file) {
                echo "  - " . $file . "\n";
            }
        }
        
        return $missingInCurrent;
    }
    
    private function compareJSAssets() {
        $currentJS = $this->getFilesByExtension($this->currentProject . '\\resources', ['js']);
        $oldJS = $this->getFilesByExtension($this->oldProject . '\\resources', ['js']);
        
        echo "\n--- Current Project JS Files ---\n";
        echo "Total: " . count($currentJS) . " files\n";
        
        echo "\n--- Old Project JS Files ---\n";
        echo "Total: " . count($oldJS) . " files\n";
        
        // Find missing files
        $missingInCurrent = array_diff($oldJS, $currentJS);
        $missingInOld = array_diff($currentJS, $oldJS);
        
        echo "\n--- MISSING IN CURRENT PROJECT ---\n";
        if (empty($missingInCurrent)) {
            echo "No JS files missing in current project.\n";
        } else {
            echo "Count: " . count($missingInCurrent) . " files\n";
            foreach ($missingInCurrent as $file) {
                echo "  - " . $file . "\n";
            }
        }
        
        echo "\n--- NEW IN CURRENT PROJECT ---\n";
        if (empty($missingInOld)) {
            echo "No new JS files in current project.\n";
        } else {
            echo "Count: " . count($missingInOld) . " files\n";
            foreach ($missingInOld as $file) {
                echo "  - " . $file . "\n";
            }
        }
    }
    
    private function compareCSSAssets() {
        $currentCSS = $this->getFilesByExtension($this->currentProject . '\\resources', ['css', 'scss', 'sass']);
        $oldCSS = $this->getFilesByExtension($this->oldProject . '\\resources', ['css', 'scss', 'sass']);
        
        echo "\n--- Current Project CSS/SCSS/SASS Files ---\n";
        echo "Total: " . count($currentCSS) . " files\n";
        
        echo "\n--- Old Project CSS/SCSS/SASS Files ---\n";
        echo "Total: " . count($oldCSS) . " files\n";
        
        // Find missing files
        $missingInCurrent = array_diff($oldCSS, $currentCSS);
        $missingInOld = array_diff($currentCSS, $oldCSS);
        
        echo "\n--- MISSING IN CURRENT PROJECT ---\n";
        if (empty($missingInCurrent)) {
            echo "No CSS/SCSS/SASS files missing in current project.\n";
        } else {
            echo "Count: " . count($missingInCurrent) . " files\n";
            foreach ($missingInCurrent as $file) {
                echo "  - " . $file . "\n";
            }
        }
        
        echo "\n--- NEW IN CURRENT PROJECT ---\n";
        if (empty($missingInOld)) {
            echo "No new CSS/SCSS/SASS files in current project.\n";
        } else {
            echo "Count: " . count($missingInOld) . " files\n";
            foreach ($missingInOld as $file) {
                echo "  - " . $file . "\n";
            }
        }
    }
    
    private function compareTranslations() {
        $currentLang = $this->getAllFiles($this->currentProject . '\\resources\\lang');
        $oldLang = $this->getAllFiles($this->oldProject . '\\resources\\lang');
        
        echo "\n--- Current Project Translation Files ---\n";
        echo "Total: " . count($currentLang) . " files\n";
        foreach ($currentLang as $file) {
            echo "  - " . $file . "\n";
        }
        
        echo "\n--- Old Project Translation Files ---\n";
        echo "Total: " . count($oldLang) . " files\n";
        foreach ($oldLang as $file) {
            echo "  - " . $file . "\n";
        }
        
        // Find missing files
        $missingInCurrent = array_diff($oldLang, $currentLang);
        $missingInOld = array_diff($currentLang, $oldLang);
        
        echo "\n--- MISSING IN CURRENT PROJECT ---\n";
        if (empty($missingInCurrent)) {
            echo "No translation files missing in current project.\n";
        } else {
            echo "Count: " . count($missingInCurrent) . " files\n";
            foreach ($missingInCurrent as $file) {
                echo "  - " . $file . "\n";
            }
        }
        
        echo "\n--- NEW IN CURRENT PROJECT ---\n";
        if (empty($missingInOld)) {
            echo "No new translation files in current project.\n";
        } else {
            echo "Count: " . count($missingInOld) . " files\n";
            foreach ($missingInOld as $file) {
                echo "  - " . $file . "\n";
            }
        }
    }
    
    private function compareDirectoryStructure() {
        $currentStructure = $this->getDirectoryStructure($this->currentProject . '\\resources');
        $oldStructure = $this->getDirectoryStructure($this->oldProject . '\\resources');
        
        echo "\n--- Current Project Structure ---\n";
        foreach ($currentStructure as $dir => $count) {
            echo "  $dir: $count files\n";
        }
        
        echo "\n--- Old Project Structure ---\n";
        foreach ($oldStructure as $dir => $count) {
            echo "  $dir: $count files\n";
        }
        
        // Directories only in old
        $onlyInOld = array_diff_key($oldStructure, $currentStructure);
        echo "\n--- Directories only in Old Project ---\n";
        if (empty($onlyInOld)) {
            echo "None\n";
        } else {
            foreach ($onlyInOld as $dir => $count) {
                echo "  $dir: $count files\n";
            }
        }
        
        // Directories only in current
        $onlyInCurrent = array_diff_key($currentStructure, $oldStructure);
        echo "\n--- Directories only in Current Project ---\n";
        if (empty($onlyInCurrent)) {
            echo "None\n";
        } else {
            foreach ($onlyInCurrent as $dir => $count) {
                echo "  $dir: $count files\n";
            }
        }
    }
    
    private function getBladeFiles($directory) {
        return $this->getFilesByExtension($directory, ['blade.php']);
    }
    
    private function getFilesByExtension($directory, $extensions) {
        $files = [];
        
        if (!is_dir($directory)) {
            return $files;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = $file->getFilename();
                foreach ($extensions as $ext) {
                    if (substr($filename, -strlen('.'.$ext)) === '.'.$ext) {
                        // Store relative path from resources directory
                        $relativePath = str_replace($this->currentProject . '\\resources\\', '', $file->getPathname());
                        $relativePath = str_replace($this->oldProject . '\\resources\\', '', $relativePath);
                        $files[] = $relativePath;
                        break;
                    }
                }
            }
        }
        
        sort($files);
        return $files;
    }
    
    private function getAllFiles($directory) {
        $files = [];
        
        if (!is_dir($directory)) {
            return $files;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($directory, '', $file->getPathname());
                $relativePath = ltrim($relativePath, '\\/');
                $files[] = $relativePath;
            }
        }
        
        sort($files);
        return $files;
    }
    
    private function getDirectoryStructure($directory) {
        $structure = [];
        
        if (!is_dir($directory)) {
            return $structure;
        }
        
        $directories = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($directories as $item) {
            if ($item->isDir()) {
                $relativePath = str_replace($directory . '\\', '', $item->getPathname());
                $fileCount = count(glob($item->getPathname() . '\\*'));
                $structure[$relativePath] = $fileCount;
            }
        }
        
        return $structure;
    }
}

// Run the comparison
$comparator = new ResourceComparator();
$comparator->run();

<?php
$path = 'C:\Users\malik\Downloads\_public_html_vaaga_mock';
if (is_dir($path)) {
    echo "EXISTS: $path\n";
    $files = scandir($path);
    echo "Contents:\n";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
} else {
    echo "NOT FOUND: $path\n";
    // List Downloads folder
    $downloads = 'C:\Users\malik\Downloads';
    if (is_dir($downloads)) {
        echo "\nDownloads folder contents:\n";
        $files = scandir($downloads);
        foreach ($files as $file) {
            if (is_dir($downloads . DIRECTORY_SEPARATOR . $file) && $file !== '.' && $file !== '..') {
                echo "  [DIR] $file\n";
            }
        }
    }
}

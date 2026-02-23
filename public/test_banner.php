<?php
// Test if banner image exists
$bannerPath = __DIR__ . '/newassets/img/bg/bg-222.png';
if (file_exists($bannerPath)) {
    echo "✓ Banner image exists: " . $bannerPath . "<br>";
    echo "Size: " . filesize($bannerPath) . " bytes<br>";
    echo "URL: /newassets/img/bg/bg-222.png<br>";
    echo "<img src='/newassets/img/bg/bg-222.png' style='max-width:100%; height:200px;' />";
} else {
    echo "✗ Banner image NOT found at: " . $bannerPath . "<br>";
    echo "Available files in /newassets/img/bg/:<br>";
    $files = glob(__DIR__ . '/newassets/img/bg/*');
    foreach ($files as $file) {
        echo "- " . basename($file) . "<br>";
    }
}

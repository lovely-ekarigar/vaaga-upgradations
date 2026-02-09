<?php
$content = file_get_contents('route_references.txt');
echo "Raw file size: " . strlen($content) . PHP_EOL;

// Show first 100 chars as hex
for ($i = 0; $i < min(100, strlen($content)); $i++) {
    echo dechex(ord($content[$i])) . " ";
}
echo PHP_EOL . PHP_EOL;

// Check for 'route' string
echo "Looking for 'route': " . (strpos($content, 'route') !== false ? "found" : "not found") . PHP_EOL;
echo "Looking for 'route(': " . (strpos($content, 'route(') !== false ? "found" : "not found") . PHP_EOL;
echo "Looking for 'r': " . (strpos($content, 'r') !== false ? "found" : "not found") . PHP_EOL;

// Check first non-BOM char
$start = 0;
if (substr($content, 0, 3) == "\xEF\xBB\xBF") {
    $start = 3;
}
echo "First char after BOM: " . ord($content[$start]) . " ('" . $content[$start] . "')" . PHP_EOL;
echo "Next few chars: ";
for ($i = $start; $i < min($start + 20, strlen($content)); $i++) {
    echo ord($content[$i]) . " ";
}
echo PHP_EOL;

// Try case insensitive
if (stripos($content, 'route') !== false) {
    echo "Found 'route' (case insensitive)" . PHP_EOL;
    $pos = stripos($content, 'route');
    echo "At position $pos: " . substr($content, $pos, 30) . PHP_EOL;
}
?>

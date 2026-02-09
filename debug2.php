<?php
$content = file_get_contents('route_references.txt');
$content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

// Find first occurrence of route(
$pos = strpos($content, 'route(');
echo "Position of 'route(': $pos" . PHP_EOL;

if ($pos !== false) {
    $snippet = substr($content, $pos, 50);
    echo "Snippet: $snippet" . PHP_EOL;
    echo "Hex dump:" . PHP_EOL;
    for ($i = 0; $i < strlen($snippet); $i++) {
        echo dechex(ord($snippet[$i])) . " ";
    }
    echo PHP_EOL;
}

// Try to match manually
if (preg_match('/route\(/', $content, $m, PREG_OFFSET_CAPTURE)) {
    echo "Found at position: " . $m[0][1] . PHP_EOL;
}

// Just try matching any word after route(
preg_match_all('/route\(([\'"])([^\1]+)\1/', $content, $matches);
echo "Found " . count($matches[0]) . " matches" . PHP_EOL;
?>

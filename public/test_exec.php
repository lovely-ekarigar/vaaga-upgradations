<?php
$inputFile = __DIR__ . '/uploads/1772437751-upgrades-validation-live-feedback2.docx';
$outputDir = __DIR__ . '/uploads';
$libreExe  = '/bin/libreoffice';

$command = $libreExe
    . ' --headless --convert-to pdf '
    . escapeshellarg($inputFile)
    . ' --outdir '
    . escapeshellarg($outputDir)
    . ' 2>&1';

exec($command, $output, $returnCode);

echo "<pre>";
echo "Command: $command\n";
echo "Return Code: $returnCode\n";
echo "Output:\n";
print_r($output);
echo "</pre>";
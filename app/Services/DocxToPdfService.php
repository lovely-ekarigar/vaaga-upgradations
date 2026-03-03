<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service for converting DOCX files to PDF
 * 
 * Uses LibreOffice if available (best quality), otherwise falls back to PHPWord+TCPDF
 */
class DocxToPdfService
{
    /**
     * @var string Path to store converted PDFs
     */
    protected string $pdfStoragePath;

    /**
     * @var int Conversion timeout in seconds
     */
    protected int $timeout = 120;

    public function __construct()
    {
        $this->pdfStoragePath = storage_path('app/public/converted-pdfs');
        
        // Ensure the storage directory exists
        if (!file_exists($this->pdfStoragePath)) {
            mkdir($this->pdfStoragePath, 0755, true);
        }
    }

    /**
     * Convert DOCX file to PDF
     *
     * @param string $docxPath Path to the DOCX file
     * @param string|null $outputPath Optional custom output path
     * @return array Result with 'success', 'pdf_path', 'pdf_url', and 'message'
     */
    public function convert(string $docxPath, ?string $outputPath = null): array
    {
        // Validate input file
        if (!file_exists($docxPath)) {
            return [
                'success' => false,
                'pdf_path' => null,
                'pdf_url' => null,
                'message' => 'DOCX file not found: ' . $docxPath,
            ];
        }

        // Generate output path if not provided
        if (!$outputPath) {
            $outputPath = $this->generatePdfPath($docxPath);
        }

        // Check if PDF already exists and is newer than DOCX
        if ($this->isPdfCacheValid($docxPath, $outputPath)) {
            return [
                'success' => true,
                'pdf_path' => $outputPath,
                'pdf_url' => $this->getPdfUrl($outputPath),
                'message' => 'Using cached PDF',
                'cached' => true,
            ];
        }

        // Try LibreOffice first (best quality)
        if ($this->libreofficeAvailable()) {
            Log::info('Converting DOCX to PDF using LibreOffice', ['input' => $docxPath, 'output' => $outputPath]);
            $result = $this->convertWithLibreoffice($docxPath, $outputPath);
            
            if ($result['success']) {
                return $result;
            }
            
            Log::warning('LibreOffice conversion failed, trying fallback', ['error' => $result['message']]);
        }

        // Fallback to PHPWord + TCPDF/DomPDF
        Log::info('Converting DOCX to PDF using PHPWord fallback', ['input' => $docxPath, 'output' => $outputPath]);
        return $this->convertWithPhpWord($docxPath, $outputPath);
    }

    /**
     * Check if LibreOffice is available on the system
     *
     * @return bool
     */
    public function libreofficeAvailable(): bool
    {
        // Check for libreoffice command
        $checkCmd = $this->isWindows() 
            ? 'where libreoffice > NUL 2>&1 && echo YES || echo NO'
            : 'which libreoffice > /dev/null 2>&1 && echo YES || echo NO';
        
        $output = shell_exec($checkCmd);
        
        if (trim($output) === 'YES') {
            return true;
        }

        // Also check for soffice (alternative command)
        $checkCmd = $this->isWindows()
            ? 'where soffice > NUL 2>&1 && echo YES || echo NO'
            : 'which soffice > /dev/null 2>&1 && echo YES || echo NO';
        
        $output = shell_exec($checkCmd);
        
        return trim($output) === 'YES';
    }

    /**
     * Convert using LibreOffice command line
     *
     * @param string $input Path to DOCX file
     * @param string $output Path for PDF output
     * @return array Result array
     */
    protected function convertWithLibreoffice(string $input, string $output): array
    {
        try {
            $outputDir = dirname($output);
            $baseName = pathinfo($input, PATHINFO_FILENAME);
            $expectedOutput = $outputDir . '/' . $baseName . '.pdf';

            // Build the command
            $command = sprintf(
                '%s --headless --convert-to pdf --outdir %s %s 2>&1',
                $this->isWindows() ? 'soffice' : 'libreoffice',
                escapeshellarg($outputDir),
                escapeshellarg($input)
            );

            Log::debug('Executing LibreOffice command', ['command' => $command]);

            // Execute with timeout
            $outputCmd = [];
            $returnCode = 0;
            
            if ($this->isWindows()) {
                // Windows execution
                $result = shell_exec($command);
                $returnCode = $result === null ? 1 : 0;
            } else {
                // Linux/Mac execution with timeout
                $command = 'timeout ' . $this->timeout . ' ' . $command;
                exec($command, $outputCmd, $returnCode);
            }

            // LibreOffice creates file with same basename as input
            if ($returnCode === 0 && file_exists($expectedOutput)) {
                // If expected output path is different from what we want, rename it
                if ($expectedOutput !== $output) {
                    rename($expectedOutput, $output);
                }

                return [
                    'success' => true,
                    'pdf_path' => $output,
                    'pdf_url' => $this->getPdfUrl($output),
                    'message' => 'Converted successfully using LibreOffice',
                    'cached' => false,
                ];
            }

            return [
                'success' => false,
                'pdf_path' => null,
                'pdf_url' => null,
                'message' => 'LibreOffice conversion failed. Return code: ' . $returnCode . ' Output: ' . implode('\n', $outputCmd),
            ];

        } catch (\Exception $e) {
            Log::error('LibreOffice conversion exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'pdf_path' => null,
                'pdf_url' => null,
                'message' => 'LibreOffice conversion error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Convert using PHPWord and DomPDF (fallback method)
     *
     * @param string $input Path to DOCX file
     * @param string $output Path for PDF output
     * @return array Result array
     */
    protected function convertWithPhpWord(string $input, string $output): array
    {
        try {
            // Check if PHPWord is available
            if (!class_exists('\PhpOffice\PhpWord\IOFactory')) {
                return [
                    'success' => false,
                    'pdf_path' => null,
                    'pdf_url' => null,
                    'message' => 'PHPWord is not installed. Run: composer require phpoffice/phpword',
                ];
            }

            // Load the DOCX file
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($input);

            // Configure PDF renderer (using DomPDF which is already in the project)
            if (!class_exists('\Dompdf\Dompdf')) {
                return [
                    'success' => false,
                    'pdf_path' => null,
                    'pdf_url' => null,
                    'message' => 'DomPDF is not available for PDF conversion',
                ];
            }

            // Use PhpWord's PDF writer with DomPDF
            \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
            \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);

            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($output);

            if (file_exists($output)) {
                return [
                    'success' => true,
                    'pdf_path' => $output,
                    'pdf_url' => $this->getPdfUrl($output),
                    'message' => 'Converted successfully using PHPWord + DomPDF',
                    'cached' => false,
                ];
            }

            return [
                'success' => false,
                'pdf_path' => null,
                'pdf_url' => null,
                'message' => 'PDF file was not created',
            ];

        } catch (\Exception $e) {
            Log::error('PHPWord conversion exception', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return [
                'success' => false,
                'pdf_path' => null,
                'pdf_url' => null,
                'message' => 'PHPWord conversion error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get the cached PDF path for a DOCX file
     *
     * @param string $docxPath Original DOCX path
     * @return string|null Path to cached PDF or null if not cached
     */
    public function getCachedPdfPath(string $docxPath): ?string
    {
        $pdfPath = $this->generatePdfPath($docxPath);
        
        if ($this->isPdfCacheValid($docxPath, $pdfPath)) {
            return $pdfPath;
        }
        
        return null;
    }

    /**
     * Generate PDF storage path from DOCX path
     *
     * @param string $docxPath
     * @return string
     */
    protected function generatePdfPath(string $docxPath): string
    {
        $fileName = pathinfo($docxPath, PATHINFO_FILENAME);
        $fileHash = md5($docxPath . filemtime($docxPath));
        
        return $this->pdfStoragePath . '/' . $fileName . '_' . $fileHash . '.pdf';
    }

    /**
     * Check if cached PDF is valid (exists and is newer than source)
     *
     * @param string $docxPath
     * @param string $pdfPath
     * @return bool
     */
    protected function isPdfCacheValid(string $docxPath, string $pdfPath): bool
    {
        if (!file_exists($pdfPath)) {
            return false;
        }

        // Check if PDF is newer than DOCX
        $docxTime = filemtime($docxPath);
        $pdfTime = filemtime($pdfPath);

        return $pdfTime >= $docxTime;
    }

    /**
     * Get public URL for a PDF file
     *
     * @param string $pdfPath
     * @return string
     */
    protected function getPdfUrl(string $pdfPath): string
    {
        $relativePath = str_replace(storage_path('app/public/'), '', $pdfPath);
        return asset('storage/' . $relativePath);
    }

    /**
     * Clear cached PDFs older than specified days
     *
     * @param int $daysOld
     * @return int Number of files deleted
     */
    public function clearOldCache(int $daysOld = 30): int
    {
        $count = 0;
        $cutoffTime = time() - ($daysOld * 24 * 60 * 60);

        if (!is_dir($this->pdfStoragePath)) {
            return 0;
        }

        $files = glob($this->pdfStoragePath . '/*.pdf');
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
                $count++;
            }
        }

        Log::info('Cleared old PDF cache', ['deleted' => $count, 'older_than_days' => $daysOld]);
        
        return $count;
    }

    /**
     * Check if running on Windows
     *
     * @return bool
     */
    protected function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * Get conversion statistics
     *
     * @return array
     */
    public function getStats(): array
    {
        $stats = [
            'libreoffice_available' => $this->libreofficeAvailable(),
            'cache_directory' => $this->pdfStoragePath,
            'cached_files_count' => 0,
            'cached_files_size' => 0,
        ];

        if (is_dir($this->pdfStoragePath)) {
            $files = glob($this->pdfStoragePath . '/*.pdf');
            $stats['cached_files_count'] = count($files);
            
            foreach ($files as $file) {
                $stats['cached_files_size'] += filesize($file);
            }
        }

        $stats['cached_files_size_formatted'] = $this->formatBytes($stats['cached_files_size']);

        return $stats;
    }

    /**
     * Format bytes to human readable
     *
     * @param int $bytes
     * @return string
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $unitIndex), 2) . ' ' . $units[$unitIndex];
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocxToPdfConverter
{
    /**
     * Convert DOCX file to PDF
     * 
     * @param string $docxPath Path to DOCX file
     * @param string $outputPath Path for output PDF
     * @return bool Success status
     */
    public static function convert($docxPath, $outputPath)
    {
        Log::info('Converting DOCX to PDF', ['input' => $docxPath, 'output' => $outputPath]);
        
        // Method 1: Try LibreOffice (best quality)
        if (self::libreofficeAvailable()) {
            return self::convertWithLibreoffice($docxPath, $outputPath);
        }
        
        // Method 2: Try unoconv (alternative)
        if (self::unoconvAvailable()) {
            return self::convertWithUnoconv($docxPath, $outputPath);
        }
        
        // Method 3: Use PHPWord + DomPDF (fallback)
        return self::convertWithPhpWord($docxPath, $outputPath);
    }
    
    /**
     * Check if conversion is possible
     */
    public static function canConvert()
    {
        return self::libreofficeAvailable() || 
               self::unoconvAvailable() || 
               class_exists('\PhpOffice\PhpWord\IOFactory');
    }
    
    /**
     * Check LibreOffice availability
     */
    private static function libreofficeAvailable()
    {
        $output = shell_exec('which libreoffice 2>/dev/null');
        return !empty($output);
    }
    
    /**
     * Check unoconv availability
     */
    private static function unoconvAvailable()
    {
        $output = shell_exec('which unoconv 2>/dev/null');
        return !empty($output);
    }
    
    /**
     * Convert using LibreOffice
     */
    private static function convertWithLibreoffice($docxPath, $outputPath)
    {
        $outputDir = dirname($outputPath);
        $filename = basename($docxPath, '.docx');
        
        $cmd = sprintf(
            'libreoffice --headless --convert-to pdf --outdir %s %s 2>&1',
            escapeshellarg($outputDir),
            escapeshellarg($docxPath)
        );
        
        Log::info('Running LibreOffice command', ['cmd' => $cmd]);
        $output = shell_exec($cmd);
        Log::info('LibreOffice output', ['output' => $output]);
        
        // LibreOffice creates file with same name but .pdf extension
        $expectedOutput = $outputDir . '/' . $filename . '.pdf';
        
        if (file_exists($expectedOutput)) {
            // Rename if needed
            if ($expectedOutput !== $outputPath) {
                rename($expectedOutput, $outputPath);
            }
            Log::info('LibreOffice conversion successful', ['output' => $outputPath]);
            return true;
        }
        
        Log::error('LibreOffice conversion failed', ['expected' => $expectedOutput]);
        return false;
    }
    
    /**
     * Convert using unoconv
     */
    private static function convertWithUnoconv($docxPath, $outputPath)
    {
        $cmd = sprintf(
            'unoconv -f pdf -o %s %s 2>&1',
            escapeshellarg($outputPath),
            escapeshellarg($docxPath)
        );
        
        Log::info('Running unoconv command', ['cmd' => $cmd]);
        $output = shell_exec($cmd);
        Log::info('unoconv output', ['output' => $output]);
        
        return file_exists($outputPath);
    }
    
    /**
     * Convert using PHPWord + DomPDF (fallback)
     * Note: This has formatting limitations
     */
    private static function convertWithPhpWord($docxPath, $outputPath)
    {
        try {
            Log::info('Using PHPWord fallback conversion');
            
            if (!class_exists('\PhpOffice\PhpWord\IOFactory')) {
                Log::error('PHPWord not installed');
                return false;
            }
            
            // Load DOCX
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);
            
            // Configure PDF writer
            $domPdfPath = base_path('vendor/dompdf/dompdf');
            if (file_exists($domPdfPath)) {
                \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
                \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
            }
            
            // Save as PDF
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            $writer->save($outputPath);
            
            Log::info('PHPWord conversion successful');
            return file_exists($outputPath);
            
        } catch (\Exception $e) {
            Log::error('PHPWord conversion failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Get or create PDF version of a media file
     * 
     * @param \App\Models\Media $media
     * @return string|null Path to PDF file or null if conversion failed
     */
    public static function getPdfPath($media)
    {
        if (empty($media->file_name)) {
            return null;
        }
        
        // Find the original DOCX file
        $docxPaths = [
            storage_path('app/public/uploads/' . $media->file_name),
            public_path('storage/uploads/' . $media->file_name),
            base_path('storage/app/public/uploads/' . $media->file_name),
        ];
        
        $docxPath = null;
        foreach ($docxPaths as $path) {
            if (file_exists($path)) {
                $docxPath = $path;
                break;
            }
        }
        
        if (!$docxPath) {
            Log::error('DOCX file not found for conversion', ['media_id' => $media->id]);
            return null;
        }
        
        // Generate PDF path
        $pdfFilename = pathinfo($media->file_name, PATHINFO_FILENAME) . '.pdf';
        $pdfPath = storage_path('app/public/converted-pdfs/' . $pdfFilename);
        
        // Create directory if needed
        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }
        
        // Check if PDF already exists and is newer than DOCX
        if (file_exists($pdfPath)) {
            $docxTime = filemtime($docxPath);
            $pdfTime = filemtime($pdfPath);
            
            if ($pdfTime >= $docxTime) {
                Log::info('Using cached PDF', ['path' => $pdfPath]);
                return $pdfPath;
            }
        }
        
        // Convert to PDF
        if (self::convert($docxPath, $pdfPath)) {
            return $pdfPath;
        }
        
        return null;
    }
}

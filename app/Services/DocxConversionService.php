<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocxConversionService
{
    /**
     * Disk name for storage
     */
    protected string $disk = 'public';

    /**
     * Base path for uploads
     */
    protected string $basePath = 'uploads';

    /**
     * LibreOffice executable path
     */
    protected string $libreOfficePath = '/bin/libreoffice';

    /**
     * Alternative LibreOffice paths to try
     */
    protected array $alternativePaths = [
        '/bin/libreoffice',
        '/usr/bin/libreoffice',
        '/usr/local/bin/libreoffice',
        '/Applications/LibreOffice.app/Contents/MacOS/soffice',
        'soffice',
        'libreoffice',
    ];

    /**
     * Check if exec() function is available
     */
    public function isExecAvailable(): bool
    {
        return function_exists('exec')
            && !in_array('exec', array_map('trim', explode(',', ini_get('disable_functions'))));
    }

    /**
     * Find LibreOffice executable
     */
    public function findLibreOffice(): ?string
    {
        // Check if default path works
        if ($this->isExecutableAvailable($this->libreOfficePath)) {
            return $this->libreOfficePath;
        }

        // Try alternative paths
        foreach ($this->alternativePaths as $path) {
            if ($this->isExecutableAvailable($path)) {
                return $path;
            }
        }

        // Try to find using 'which' command
        if ($this->isExecAvailable()) {
            $output = [];
            $returnVar = 0;
            exec('which soffice 2>/dev/null', $output, $returnVar);
            if ($returnVar === 0 && !empty($output[0])) {
                return $output[0];
            }

            exec('which libreoffice 2>/dev/null', $output, $returnVar);
            if ($returnVar === 0 && !empty($output[0])) {
                return $output[0];
            }
        }

        return null;
    }

    /**
     * Check if an executable is available
     */
    protected function isExecutableAvailable(string $path): bool
    {
        // For Windows, just check if the command exists
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = [];
            $returnVar = 0;
            exec('where ' . escapeshellarg($path) . ' 2>nul', $output, $returnVar);
            return $returnVar === 0 && !empty($output[0]);
        }

        // For Unix-like systems
        return is_executable($path) || (!empty(shell_exec("which " . escapeshellarg($path) . " 2>/dev/null")));
    }

    /**
     * Check if conversion is possible
     */
    public function canConvert(): bool
    {
        return $this->isExecAvailable() && $this->findLibreOffice() !== null;
    }

    /**
     * Convert DOCX/DOC file to PDF
     *
     * @param string $inputPath Full path to the input file
     * @param string $outputDir Directory where PDF should be saved
     * @return string|null Path to the generated PDF file or null on failure
     */
    public function convertToPdf(string $inputPath, string $outputDir): ?string
    {
        if (!$this->canConvert()) {
            Log::warning('DOCX conversion not available: LibreOffice not found or exec() disabled');
            return null;
        }

        if (!file_exists($inputPath)) {
            Log::error('DOCX conversion failed: Input file does not exist', ['path' => $inputPath]);
            return null;
        }

        $libreOffice = $this->findLibreOffice();
        $pdfName = pathinfo($inputPath, PATHINFO_FILENAME) . '.pdf';
        $pdfPath = $outputDir . DIRECTORY_SEPARATOR . $pdfName;

        try {
            // Build the conversion command
            $command = sprintf(
                '%s --headless --convert-to pdf %s --outdir %s 2>&1',
                escapeshellarg($libreOffice),
                escapeshellarg($inputPath),
                escapeshellarg($outputDir)
            );

            Log::info('Starting DOCX to PDF conversion', [
                'input' => $inputPath,
                'output' => $pdfPath,
                'command' => $command,
            ]);

            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                Log::error('DOCX conversion failed with exit code', [
                    'exit_code' => $returnVar,
                    'output' => $output,
                ]);
                return null;
            }

            // Check if PDF was created
            if (!file_exists($pdfPath)) {
                Log::error('DOCX conversion failed: PDF file not created', [
                    'expected_path' => $pdfPath,
                ]);
                return null;
            }

            Log::info('DOCX conversion successful', [
                'pdf_path' => $pdfPath,
                'pdf_size' => filesize($pdfPath),
            ]);

            return $pdfPath;
        } catch (\Exception $e) {
            Log::error('DOCX conversion exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Convert a Media model's file to PDF and update the model
     *
     * @param Media $media
     * @return bool
     */
    public function convertMedia(Media $media): bool
    {
        // Check if this is a convertible file
        if (!$this->isConvertibleFile($media)) {
            return false;
        }

        // Skip if already converted
        if ($media->converted_file_name && $media->conversion_status === 'completed') {
            return true;
        }

        $media->conversion_status = 'pending';
        $media->save();

        $inputPath = storage_path('app/public/' . $this->basePath . '/' . $media->file_name);
        $outputDir = storage_path('app/public/' . $this->basePath);

        $pdfPath = $this->convertToPdf($inputPath, $outputDir);

        if ($pdfPath) {
            $pdfFileName = basename($pdfPath);
            
            $media->converted_file_name = $pdfFileName;
            $media->conversion_status = 'completed';
            $media->converted_at = now();
            $media->save();

            Log::info('Media conversion completed', [
                'media_id' => $media->id,
                'original' => $media->file_name,
                'converted' => $pdfFileName,
            ]);

            return true;
        } else {
            $media->conversion_status = 'failed';
            $media->save();

            Log::error('Media conversion failed', [
                'media_id' => $media->id,
                'file_name' => $media->file_name,
            ]);

            return false;
        }
    }

    /**
     * Check if a media file is convertible (DOCX, DOC, etc.)
     *
     * @param Media $media
     * @return bool
     */
    public function isConvertibleFile(Media $media): bool
    {
        $convertibleMimeTypes = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        $convertibleExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

        $extension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));

        return in_array($media->mime_type, $convertibleMimeTypes) ||
               in_array($extension, $convertibleExtensions);
    }

    /**
     * Get the URL for viewing the file (returns PDF URL if converted)
     *
     * @param Media $media
     * @return string|null
     */
    public function getViewUrl(Media $media): ?string
    {
        // If there's a converted PDF, return that URL
        if ($media->converted_file_name && $media->conversion_status === 'completed') {
            return asset('storage/' . $this->basePath . '/' . $media->converted_file_name);
        }

        // Otherwise return the original file URL
        return $media->url;
    }

    /**
     * Get the file path for viewing (returns PDF path if converted)
     *
     * @param Media $media
     * @return string|null
     */
    public function getViewFileName(Media $media): ?string
    {
        // If there's a converted PDF, return that filename
        if ($media->converted_file_name && $media->conversion_status === 'completed') {
            return $media->converted_file_name;
        }

        // Otherwise return the original filename
        return $media->file_name;
    }

    /**
     * Delete converted file if exists
     *
     * @param Media $media
     * @return bool
     */
    public function deleteConvertedFile(Media $media): bool
    {
        if ($media->converted_file_name) {
            $pdfPath = storage_path('app/public/' . $this->basePath . '/' . $media->converted_file_name);
            if (file_exists($pdfPath)) {
                @unlink($pdfPath);
            }
            return true;
        }
        return false;
    }

    /**
     * Retry failed conversion
     *
     * @param Media $media
     * @return bool
     */
    public function retryConversion(Media $media): bool
    {
        if ($media->conversion_status !== 'failed') {
            return false;
        }

        // Delete old converted file if exists
        $this->deleteConvertedFile($media);

        // Reset status
        $media->converted_file_name = null;
        $media->conversion_status = null;
        $media->converted_at = null;
        $media->save();

        // Retry conversion
        return $this->convertMedia($media);
    }
}

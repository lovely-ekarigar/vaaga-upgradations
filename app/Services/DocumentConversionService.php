<?php

namespace App\Services;

use CloudConvert\Laravel\Facades\CloudConvert;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Document Conversion Service
 * 
 * Handles DOCX to PDF conversion with multiple driver support:
 * - cloudconvert: CloudConvert API (recommended for shared hosting)
 * - libreoffice: Local LibreOffice (if available)
 * - phpword: PHPWord + DomPDF fallback (basic conversion only)
 */
class DocumentConversionService
{
    /**
     * @var string Default conversion driver
     */
    protected $defaultDriver;

    /**
     * @var array Driver availability cache
     */
    protected $driverAvailability = [];

    public function __construct()
    {
        $this->defaultDriver = config('document_conversion.default', 'cloudconvert');
    }

    /**
     * Convert DOCX to PDF
     *
     * @param string $inputPath Path to input DOCX file
     * @param string|null $outputPath Optional custom output path
     * @param string|null $driver Specific driver to use (null for default)
     * @return string Path to generated PDF
     * @throws \Exception
     */
    public function convertDocxToPdf(string $inputPath, ?string $outputPath = null, ?string $driver = null): string
    {
        if (!file_exists($inputPath)) {
            throw new \InvalidArgumentException("Input file not found: {$inputPath}");
        }

        $driver = $driver ?? $this->defaultDriver;
        
        // Generate output filename if not provided
        if (!$outputPath) {
            $outputPath = $this->generateOutputPath($inputPath, '.pdf');
        }

        // Ensure output directory exists
        $outputDir = dirname($outputPath);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        Log::info('Starting DOCX to PDF conversion', [
            'input' => $inputPath,
            'output' => $outputPath,
            'driver' => $driver
        ]);

        switch ($driver) {
            case 'cloudconvert':
                return $this->convertWithCloudConvert($inputPath, $outputPath);
                
            case 'libreoffice':
                return $this->convertWithLibreOffice($inputPath, $outputPath);
                
            case 'phpword':
                return $this->convertWithPhpWord($inputPath, $outputPath);
                
            default:
                throw new \InvalidArgumentException("Unknown driver: {$driver}");
        }
    }

    /**
     * Convert with automatic fallback
     *
     * Tries drivers in order: cloudconvert -> libreoffice -> phpword
     *
     * @param string $inputPath
     * @param string|null $outputPath
     * @return string
     * @throws \Exception
     */
    public function convertWithFallback(string $inputPath, ?string $outputPath = null): string
    {
        $drivers = ['cloudconvert', 'libreoffice', 'phpword'];
        $lastError = null;

        foreach ($drivers as $driver) {
            if (!$this->isDriverAvailable($driver)) {
                continue;
            }

            try {
                return $this->convertDocxToPdf($inputPath, $outputPath, $driver);
            } catch (\Exception $e) {
                Log::warning("Conversion with {$driver} failed, trying next driver", [
                    'error' => $e->getMessage()
                ]);
                $lastError = $e;
            }
        }

        throw new \RuntimeException(
            'All conversion drivers failed. Last error: ' . ($lastError ? $lastError->getMessage() : 'No drivers available'),
            0,
            $lastError
        );
    }

    /**
     * Convert using CloudConvert API
     *
     * @param string $inputPath
     * @param string $outputPath
     * @return string
     * @throws \Exception
     */
    protected function convertWithCloudConvert(string $inputPath, string $outputPath): string
    {
        try {
            // Create conversion job
            $job = (new Job())
                ->addTask(new Task('import/upload', 'upload-docx'))
                ->addTask(
                    (new Task('convert', 'convert-to-pdf'))
                        ->set('input', 'upload-docx')
                        ->set('output_format', 'pdf')
                        ->set('engine', 'libreoffice')
                )
                ->addTask(
                    (new Task('export/url', 'export-pdf'))
                        ->set('input', 'convert-to-pdf')
                );

            // Create the job
            CloudConvert::jobs()->create($job);

            // Upload the file
            $uploadTask = $job->getTasks()->whereName('upload-docx')[0];
            $inputStream = fopen($inputPath, 'r');
            
            if (!$inputStream) {
                throw new \RuntimeException("Cannot open input file: {$inputPath}");
            }
            
            CloudConvert::tasks()->upload($uploadTask, $inputStream);

            // Wait for completion with timeout
            CloudConvert::jobs()->wait($job);

            // Get export URL
            $exportTasks = $job->getTasks()->whereName('export-pdf');
            if (empty($exportTasks)) {
                throw new \RuntimeException('Export task not found in job response');
            }
            
            $exportTask = $exportTasks[0];
            $result = $exportTask->getResult();
            
            if (empty($result->files)) {
                throw new \RuntimeException('No files in export result');
            }
            
            $exportUrl = $result->files[0]->url;

            // Download and save
            $source = CloudConvert::getHttpTransport()->download($exportUrl)->detach();
            $dest = fopen($outputPath, 'w');
            
            if (!$dest) {
                throw new \RuntimeException("Cannot create output file: {$outputPath}");
            }
            
            stream_copy_to_stream($source, $dest);
            fclose($dest);

            Log::info('CloudConvert conversion successful', [
                'output' => $outputPath,
                'job_id' => $job->getId()
            ]);

            return $outputPath;
            
        } catch (\Exception $e) {
            Log::error('CloudConvert conversion failed', [
                'input' => $inputPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Convert using local LibreOffice
     *
     * @param string $inputPath
     * @param string $outputPath
     * @return string
     * @throws \Exception
     */
    protected function convertWithLibreOffice(string $inputPath, string $outputPath): string
    {
        if (!class_exists(\NcJoes\OfficeConverter\OfficeConverter::class)) {
            throw new \RuntimeException('ncjoes/office-converter package not installed');
        }

        try {
            $outputDir = dirname($outputPath);
            $outputFilename = basename($outputPath);

            $converter = new \NcJoes\OfficeConverter\OfficeConverter($inputPath, $outputDir);
            $result = $converter->convertTo($outputFilename);

            Log::info('LibreOffice conversion successful', [
                'output' => $result
            ]);

            return $result;
            
        } catch (\Exception $e) {
            Log::error('LibreOffice conversion failed', [
                'input' => $inputPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Convert using PHPWord + DomPDF
     * Only for simple documents!
     *
     * @param string $inputPath
     * @param string $outputPath
     * @return string
     * @throws \Exception
     */
    protected function convertWithPhpWord(string $inputPath, string $outputPath): string
    {
        try {
            // Configure DomPDF renderer
            $domPdfPath = base_path('vendor/dompdf/dompdf');
            
            if (!is_dir($domPdfPath)) {
                throw new \RuntimeException('dompdf/dompdf not installed. Run: composer require dompdf/dompdf');
            }

            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);

            // Load DOCX
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($inputPath);
            
            // Save as PDF
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            $writer->save($outputPath);

            Log::info('PHPWord conversion successful', [
                'output' => $outputPath,
                'note' => 'Limited formatting support'
            ]);

            return $outputPath;
            
        } catch (\Exception $e) {
            Log::error('PHPWord conversion failed', [
                'input' => $inputPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check if a driver is available
     *
     * @param string $driver
     * @return bool
     */
    public function isDriverAvailable(string $driver): bool
    {
        if (isset($this->driverAvailability[$driver])) {
            return $this->driverAvailability[$driver];
        }

        $available = match ($driver) {
            'cloudconvert' => $this->isCloudConvertAvailable(),
            'libreoffice' => $this->isLibreOfficeAvailable(),
            'phpword' => $this->isPhpWordAvailable(),
            default => false,
        };

        $this->driverAvailability[$driver] = $available;
        return $available;
    }

    /**
     * Get list of available drivers
     *
     * @return array
     */
    public function getAvailableDrivers(): array
    {
        $drivers = ['cloudconvert', 'libreoffice', 'phpword'];
        $available = [];

        foreach ($drivers as $driver) {
            if ($this->isDriverAvailable($driver)) {
                $available[] = $driver;
            }
        }

        return $available;
    }

    /**
     * Check CloudConvert API availability
     *
     * @return bool
     */
    protected function isCloudConvertAvailable(): bool
    {
        if (!class_exists(CloudConvert::class)) {
            return false;
        }

        $apiKey = config('cloudconvert.api_key');
        return !empty($apiKey);
    }

    /**
     * Check LibreOffice availability
     *
     * @return bool
     */
    protected function isLibreOfficeAvailable(): bool
    {
        if (!class_exists(\NcJoes\OfficeConverter\OfficeConverter::class)) {
            return false;
        }

        // Check if LibreOffice binary exists
        $paths = ['libreoffice', 'soffice', '/usr/bin/libreoffice', '/usr/bin/soffice'];
        
        foreach ($paths as $path) {
            exec("which {$path} 2>/dev/null", $output, $return);
            if ($return === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check PHPWord + DomPDF availability
     *
     * @return bool
     */
    protected function isPhpWordAvailable(): bool
    {
        return class_exists(\PhpOffice\PhpWord\PhpWord::class) && 
               is_dir(base_path('vendor/dompdf/dompdf'));
    }

    /**
     * Generate output path from input path
     *
     * @param string $inputPath
     * @param string $extension
     * @return string
     */
    protected function generateOutputPath(string $inputPath, string $extension): string
    {
        $directory = dirname($inputPath);
        $filename = pathinfo($inputPath, PATHINFO_FILENAME);
        $timestamp = time();
        
        return "{$directory}/{$filename}_{$timestamp}{$extension}";
    }

    /**
     * Process DOCX template with variables
     *
     * @param string $templatePath
     * @param array $variables
     * @param string|null $outputPath
     * @return string Path to processed DOCX
     * @throws \Exception
     */
    public function processTemplate(string $templatePath, array $variables, ?string $outputPath = null): string
    {
        if (!file_exists($templatePath)) {
            throw new \InvalidArgumentException("Template not found: {$templatePath}");
        }

        if (!$outputPath) {
            $outputPath = $this->generateOutputPath($templatePath, '.docx');
        }

        try {
            $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            // Set simple variables
            foreach ($variables as $key => $value) {
                if (is_array($value)) {
                    // Handle array data (clone rows)
                    if (isset($value['rows']) && is_array($value['rows'])) {
                        $template->cloneRow($key, count($value['rows']));
                        foreach ($value['rows'] as $index => $row) {
                            $rowNum = $index + 1;
                            foreach ($row as $field => $fieldValue) {
                                $template->setValue("{$key}#{$rowNum}#{$field}", $fieldValue);
                            }
                        }
                    }
                } else {
                    $template->setValue($key, htmlspecialchars($value, ENT_COMPAT, 'UTF-8'));
                }
            }

            $template->saveAs($outputPath);

            Log::info('Template processed successfully', [
                'template' => $templatePath,
                'output' => $outputPath
            ]);

            return $outputPath;
            
        } catch (\Exception $e) {
            Log::error('Template processing failed', [
                'template' => $templatePath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Complete workflow: Process template and convert to PDF
     *
     * @param string $templatePath
     * @param array $variables
     * @param string|null $outputPath
     * @param string|null $driver
     * @return string Path to PDF
     * @throws \Exception
     */
    public function processTemplateToPdf(string $templatePath, array $variables, ?string $outputPath = null, ?string $driver = null): string
    {
        // Process template to DOCX
        $docxPath = $this->processTemplate($templatePath, $variables);

        try {
            // Convert DOCX to PDF
            $pdfPath = $outputPath ?? str_replace('.docx', '.pdf', $docxPath);
            $result = $this->convertDocxToPdf($docxPath, $pdfPath, $driver);

            // Clean up temporary DOCX
            if (file_exists($docxPath) && $docxPath !== $templatePath) {
                @unlink($docxPath);
            }

            return $result;
            
        } catch (\Exception $e) {
            // Clean up on failure
            if (file_exists($docxPath) && $docxPath !== $templatePath) {
                @unlink($docxPath);
            }
            throw $e;
        }
    }
}

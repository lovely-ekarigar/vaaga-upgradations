<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadService
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
     * Allowed MIME types for study materials
     */
    protected array $allowedMimeTypes = [
        // Documents
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'text/plain' => 'txt',
        
        // Videos
        'video/mp4' => 'mp4',
        'video/avi' => 'avi',
        'video/quicktime' => 'mov',
        'video/webm' => 'webm',
        'video/x-matroska' => 'mkv',
        
        // Audio
        'audio/mpeg' => 'mp3',
        'audio/wav' => 'wav',
        'audio/ogg' => 'ogg',
        'audio/mp4' => 'm4a',
        
        // Images
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    /**
     * Type mappings for media types
     */
    protected array $typeMappings = [
        'pdf' => 'lesson_pdf',
        'doc' => 'document',
        'docx' => 'document',
        'xls' => 'document',
        'xlsx' => 'document',
        'ppt' => 'document',
        'pptx' => 'document',
        'txt' => 'document',
        'mp4' => 'upload',
        'avi' => 'upload',
        'mov' => 'upload',
        'webm' => 'upload',
        'mkv' => 'upload',
        'mp3' => 'lesson_audio',
        'wav' => 'lesson_audio',
        'ogg' => 'lesson_audio',
        'm4a' => 'lesson_audio',
        'jpg' => 'image',
        'png' => 'image',
        'gif' => 'image',
        'webp' => 'image',
    ];

    /**
     * Upload a file and create Media record
     *
     * @param UploadedFile $file
     * @param string $modelType
     * @param int $modelId
     * @param string|null $customName
     * @return Media|null
     */
    public function upload(UploadedFile $file, string $modelType, int $modelId, ?string $customName = null): ?Media
    {
        $mimeType = $file->getMimeType();
        $extension = $this->getExtensionFromMimeType($mimeType);
        
        if (!$extension) {
            $extension = $file->getClientOriginalExtension();
        }

        // Generate unique filename
        $filename = $this->generateFilename($file, $extension);
        
        // Store file using Storage facade
        $path = $this->storeFile($file, $filename);
        
        if (!$path) {
            return null;
        }

        // Create Media record
        $media = new Media();
        $media->model_type = $modelType;
        $media->model_id = $modelId;
        $media->name = $customName ?? $file->getClientOriginalName();
        $media->file_name = $filename;
        $media->mime_type = $mimeType;
        $media->size = $file->getSize();
        $media->type = $this->getMediaType($extension);
        $media->url = Storage::disk($this->disk)->url($this->basePath . '/' . $filename);
        $media->save();

        return $media;
    }

    /**
     * Upload a video file
     *
     * @param UploadedFile $file
     * @param string $modelType
     * @param int $modelId
     * @param string|null $customName
     * @return Media|null
     */
    public function uploadVideo(UploadedFile $file, string $modelType, int $modelId, ?string $customName = null): ?Media
    {
        return $this->upload($file, $modelType, $modelId, $customName);
    }

    /**
     * Upload a PDF file
     *
     * @param UploadedFile $file
     * @param string $modelType
     * @param int $modelId
     * @param string|null $customName
     * @return Media|null
     */
    public function uploadPDF(UploadedFile $file, string $modelType, int $modelId, ?string $customName = null): ?Media
    {
        $mimeType = $file->getMimeType();
        $extension = 'pdf';
        
        $filename = $this->generateFilename($file, $extension);
        $path = $this->storeFile($file, $filename);
        
        if (!$path) {
            return null;
        }

        $media = new Media();
        $media->model_type = $modelType;
        $media->model_id = $modelId;
        $media->name = $customName ?? $file->getClientOriginalName();
        $media->file_name = $filename;
        $media->mime_type = $mimeType;
        $media->size = $file->getSize();
        $media->type = 'lesson_pdf';
        $media->url = Storage::disk($this->disk)->url($this->basePath . '/' . $filename);
        $media->save();

        return $media;
    }

    /**
     * Upload an audio file
     *
     * @param UploadedFile $file
     * @param string $modelType
     * @param int $modelId
     * @param string|null $customName
     * @return Media|null
     */
    public function uploadAudio(UploadedFile $file, string $modelType, int $modelId, ?string $customName = null): ?Media
    {
        $mimeType = $file->getMimeType();
        $extension = $this->getExtensionFromMimeType($mimeType) ?? $file->getClientOriginalExtension();
        
        $filename = $this->generateFilename($file, $extension);
        $path = $this->storeFile($file, $filename);
        
        if (!$path) {
            return null;
        }

        $media = new Media();
        $media->model_type = $modelType;
        $media->model_id = $modelId;
        $media->name = $customName ?? $file->getClientOriginalName();
        $media->file_name = $filename;
        $media->mime_type = $mimeType;
        $media->size = $file->getSize();
        $media->type = 'lesson_audio';
        $media->url = Storage::disk($this->disk)->url($this->basePath . '/' . $filename);
        $media->save();

        return $media;
    }

    /**
     * Store file to disk
     *
     * @param UploadedFile $file
     * @param string $filename
     * @return string|null
     */
    protected function storeFile(UploadedFile $file, string $filename): ?string
    {
        try {
            $path = $file->storeAs($this->basePath, $filename, $this->disk);
            return $path;
        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @param string $extension
     * @return string
     */
    protected function generateFilename(UploadedFile $file, string $extension): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitized = Str::slug($originalName);
        $unique = time() . '_' . Str::random(8);
        
        return $sanitized . '_' . $unique . '.' . $extension;
    }

    /**
     * Get extension from MIME type
     *
     * @param string $mimeType
     * @return string|null
     */
    protected function getExtensionFromMimeType(string $mimeType): ?string
    {
        return $this->allowedMimeTypes[$mimeType] ?? null;
    }

    /**
     * Get media type from extension
     *
     * @param string $extension
     * @return string
     */
    protected function getMediaType(string $extension): string
    {
        return $this->typeMappings[$extension] ?? 'document';
    }

    /**
     * Delete media file and record
     *
     * @param Media $media
     * @return bool
     */
    public function delete(Media $media): bool
    {
        try {
            // Delete file from storage
            $filepath = $this->basePath . '/' . $media->file_name;
            if (Storage::disk($this->disk)->exists($filepath)) {
                Storage::disk($this->disk)->delete($filepath);
            }
            
            // Delete record
            $media->delete();
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Media deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file URL
     *
     * @param Media $media
     * @return string|null
     */
    public function getUrl(Media $media): ?string
    {
        $filepath = $this->basePath . '/' . $media->file_name;
        
        if (Storage::disk($this->disk)->exists($filepath)) {
            return Storage::disk($this->disk)->url($filepath);
        }
        
        return null;
    }

    /**
     * Get download URL with proper headers
     *
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|null
     */
    public function download(Media $media)
    {
        $filepath = $this->basePath . '/' . $media->file_name;
        
        if (Storage::disk($this->disk)->exists($filepath)) {
            return Storage::disk($this->disk)->download(
                $filepath,
                $media->name ?? $media->file_name
            );
        }
        
        return null;
    }

    /**
     * Check if file is video
     *
     * @param string $mimeType
     * @return bool
     */
    public function isVideo(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'video/');
    }

    /**
     * Check if file is audio
     *
     * @param string $mimeType
     * @return bool
     */
    public function isAudio(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'audio/');
    }

    /**
     * Check if file is PDF
     *
     * @param string $mimeType
     * @return bool
     */
    public function isPDF(string $mimeType): bool
    {
        return $mimeType === 'application/pdf';
    }

    /**
     * Check if file is image
     *
     * @param string $mimeType
     * @return bool
     */
    public function isImage(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'image/');
    }
}

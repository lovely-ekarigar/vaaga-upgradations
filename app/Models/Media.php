<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\VideoProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = "media";
    protected $guarded = [];

    protected $appends = ['file_url', 'file_type', 'icon_class', 'is_video', 'is_audio', 'is_pdf', 'is_image', 'is_document', 'is_external', 'embed_url', 'thumbnail_url', 'formatted_size'];

    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Get file URL attribute
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->is_external) {
            return $this->url;
        }
        
        if ($this->file_name) {
            return asset('storage/uploads/' . $this->file_name);
        }
        
        return $this->url;
    }

    /**
     * Get file type category
     */
    public function getFileTypeAttribute(): string
    {
        return match($this->type) {
            'lesson_pdf' => 'pdf',
            'lesson_audio' => 'audio',
            'upload', 'youtube', 'vimeo', 'embed' => 'video',
            default => 'document',
        };
    }

    /**
     * Get Bootstrap icon class
     */
    public function getIconClassAttribute(): string
    {
        return match($this->file_type) {
            'pdf' => 'bi-file-pdf text-danger',
            'audio' => 'bi-music-note-beamed text-success',
            'video' => 'bi-play-circle-fill text-primary',
            'image' => 'bi-image text-info',
            default => 'bi-file-earmark-text text-secondary',
        };
    }

    /**
     * Check if file is video
     */
    public function getIsVideoAttribute(): bool
    {
        return in_array($this->type, ['upload', 'youtube', 'vimeo', 'embed']) || 
               str_starts_with($this->mime_type ?? '', 'video/');
    }

    /**
     * Check if file is audio
     */
    public function getIsAudioAttribute(): bool
    {
        return $this->type === 'lesson_audio' || 
               str_starts_with($this->mime_type ?? '', 'audio/');
    }

    /**
     * Check if file is PDF
     */
    public function getIsPdfAttribute(): bool
    {
        return $this->type === 'lesson_pdf' || 
               ($this->mime_type ?? '') === 'application/pdf';
    }

    /**
     * Check if file is image
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Check if file is document
     */
    public function getIsDocumentAttribute(): bool
    {
        return !$this->is_video && !$this->is_audio && !$this->is_pdf && !$this->is_image;
    }

    /**
     * Check if media is external (YouTube, Vimeo, etc.)
     */
    public function getIsExternalAttribute(): bool
    {
        return in_array($this->type, ['youtube', 'vimeo', 'embed']);
    }

    /**
     * Get embed URL for external videos
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->type === 'youtube') {
            $videoId = $this->extractYoutubeId($this->url);
            return $videoId ? 'https://www.youtube.com/embed/' . $videoId : null;
        }
        
        if ($this->type === 'vimeo') {
            $videoId = $this->extractVimeoId($this->url);
            return $videoId ? 'https://player.vimeo.com/video/' . $videoId : null;
        }
        
        if ($this->type === 'embed') {
            return $this->url;
        }
        
        return null;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->is_video) {
            if ($this->type === 'youtube') {
                $videoId = $this->extractYoutubeId($this->url);
                return $videoId ? 'https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg' : null;
            }
            return asset('images/video-placeholder.png');
        }
        
        if ($this->is_pdf) {
            return asset('images/pdf-placeholder.png');
        }
        
        if ($this->is_audio) {
            return asset('images/audio-placeholder.png');
        }
        
        return null;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $size = $this->size ?? 0;
        
        if ($size === 0) {
            return 'Unknown';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    //Fetch Progress
    public function getProgress($user_id){
        $progress = null;
        $user = User::find($user_id);
        if($user){
            $progress = VideoProgress::where('user_id','=',$user_id)->where('media_id','=',$this->id)->first();
        }
        if($progress == null){
            $progress = new VideoProgress();
        }
        return $progress;
    }

    public function getProgressPercentage($user_id){
        $progress = $this->getProgress($user_id);
        if($progress->progress){
            $percentage = ($progress->progress / $progress->duration)* 100;
        }else{
            $percentage = 0;
        }
        return round($percentage,2);
    }

    /**
     * Extract YouTube video ID
     */
    protected function extractYoutubeId(string $url): ?string
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\s]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract Vimeo video ID
     */
    protected function extractVimeoId(string $url): ?string
    {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Scope for downloadable media (not streaming types)
     */
    public function scopeDownloadable($query)
    {
        $streamingTypes = ['youtube', 'vimeo', 'embed'];
        return $query->whereNotIn('type', $streamingTypes);
    }

    /**
     * Scope for videos
     */
    public function scopeVideos($query)
    {
        return $query->whereIn('type', ['upload', 'youtube', 'vimeo', 'embed']);
    }

    /**
     * Scope for PDFs
     */
    public function scopePdfs($query)
    {
        return $query->where('type', 'lesson_pdf');
    }

    /**
     * Scope for audio
     */
    public function scopeAudio($query)
    {
        return $query->where('type', 'lesson_audio');
    }
}

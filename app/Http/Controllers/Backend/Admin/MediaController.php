<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Media;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    protected MediaUploadService $mediaUploadService;

    public function __construct(MediaUploadService $mediaUploadService)
    {
        $this->mediaUploadService = $mediaUploadService;
    }

    /**
     * Delete a media file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $media_id = $request->media_id;
        $media = Media::find($media_id);

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Media not found'], 404);
        }

        // Use MediaUploadService to delete file and record
        $deleted = $this->mediaUploadService->delete($media);

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to delete media'], 500);
    }

    /**
     * Download a media file
     *
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function download(Media $media)
    {
        // Check if media is external (YouTube, Vimeo, Embed)
        if ($media->is_external) {
            return response()->json([
                'success' => false,
                'message' => 'External media cannot be downloaded',
                'url' => $media->url
            ], 400);
        }

        // Get file path
        $filePath = 'uploads/' . $media->file_name;
        
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server'
            ], 404);
        }

        // Return file download response
        return Storage::disk('public')->download(
            $filePath,
            $media->name ?? $media->file_name,
            ['Content-Type' => $media->mime_type ?? 'application/octet-stream']
        );
    }

    /**
     * Stream a media file (for video/audio streaming)
     *
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function stream(Media $media)
    {
        // Check if media is external
        if ($media->is_external) {
            return redirect($media->url);
        }

        $filePath = 'uploads/' . $media->file_name;
        
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        return Storage::disk('public')->response($filePath);
    }

    /**
     * Get media info as JSON
     *
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Media $media)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'type' => $media->type,
                'file_type' => $media->file_type,
                'size' => $media->formatted_size,
                'url' => $media->file_url,
                'is_video' => $media->is_video,
                'is_audio' => $media->is_audio,
                'is_pdf' => $media->is_pdf,
                'is_external' => $media->is_external,
                'embed_url' => $media->embed_url,
            ]
        ]);
    }
}

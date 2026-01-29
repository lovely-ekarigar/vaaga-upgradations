<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Image upload for rich text editors (CKEditor 4, Editor.js, etc.).
 * Saves to public/uploads/editor and returns JSON expected by the editor.
 */
class EditorUploadController extends Controller
{
    /** Max file size in KB */
    protected $maxSizeKb = 2048;

    /** Allowed MIME types */
    protected $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    public function handleImageUpload(Request $request)
    {
        $request->validate([
            'upload' => 'nullable|file|image|max:' . ($this->maxSizeKb),
            'image' => 'nullable|file|image|max:' . ($this->maxSizeKb),
            'file' => 'nullable|file|image|max:' . ($this->maxSizeKb),
        ]);

        $file = $request->file('upload') ?? $request->file('image') ?? $request->file('file');
        if (! $file) {
            return response()->json([
                'error' => ['message' => 'No image file provided.'],
            ], 422);
        }

        $mime = $file->getMimeType();
        if (! in_array($mime, $this->allowedMimes, true)) {
            return response()->json([
                'error' => ['message' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP.'],
            ], 422);
        }

        $dir = 'uploads/editor';
        $name = Str::random(16) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($dir, $name, 'public');

        $url = asset('storage/' . $path);

        // CKEditor 4 (upload response)
        $out = [
            'url' => $url,
            'uploaded' => 1,
        ];
        // Editor.js Image tool
        $out['success'] = 1;
        $out['file'] = ['url' => $url];

        return response()->json($out);
    }
}

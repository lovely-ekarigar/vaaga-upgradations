<?php

namespace App\Http\Controllers\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait FileUploadTrait
{

    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles(Request $request)
    {
        ini_set('memory_limit', '-1');
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/uploads/thumb'), 0777);
        }

        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                    // Check file width
                    $extension = array_last(explode('.', $request->file($key)->getClientOriginalName()));
                    $name = array_first(explode('.', $request->file($key)->getClientOriginalName()));
                    $filename = time() . '-' . str_slug($name) . '.' . $extension;
                    $file = $request->file($key);
                    $image = Image::make($file);
                    if (!file_exists(public_path('storage/uploads/thumb'))) {
                        mkdir(public_path('storage/uploads/thumb'), 0777, true);
                    }

                    Image::make($file)->resize(50, 50)->save(public_path('storage/uploads/thumb') . '/' . $filename);

                    $width = $image->width();
                    $height = $image->height();
                    if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                        $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                    } elseif ($width > $request->{$key . '_max_width'}) {
                        $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > $request->{$key . '_max_width'}) {
                        $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save(public_path('storage/uploads') . '/' . $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {

                    $extension = array_last(explode('.', $request->file($key)->getClientOriginalName()));
                    $name = array_first(explode('.', $request->file($key)->getClientOriginalName()));
                    $filename = time() . '-' . str_slug($name) . '.' . $extension;
                    $request->file($key)->move(public_path('storage/uploads'), $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }
        return $finalRequest;
    }

public function saveAllFiles(Request $request, $downloadable_file_input = null, $model_type = null, $model = null)
{
    $uploadPath = public_path('uploads');
    $uploadUrl  = 'uploads/';

    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
        mkdir($uploadPath . '/thumb', 0777, true);
    }

    $finalRequest = $request;

    foreach ($request->all() as $key => $value) {

        if ($request->hasFile($key)) {

            if ($key == $downloadable_file_input) {

                // ✅ Downloadable — multiple files loop
                foreach ($request->file($key) as $item) {
                    if (!$item || !$item->isValid()) continue;

                    $name      = pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $item->getClientOriginalExtension();
                    $filename  = $this->generateFilename($uploadPath, $name, $extension);
                    $size      = $item->getSize() / 1024;
                    $item->move($uploadPath, $filename);

                    Media::create([
                        'model_type' => $model_type,
                        'model_id'   => $model->id,
                        'name'       => $filename,
                        'url'        => asset($uploadUrl . $filename),
                        'type'       => $item->getClientMimeType(),
                        'file_name'  => $filename,
                        'size'       => $size,
                    ]);
                }

                $finalRequest = new Request($request->except($downloadable_file_input));

            } else {

                if ($key != 'video_file') {

                    if ($key == 'add_pdf') {

                        // ✅ Multiple PDF files — sab process karo
                        $files = is_array($request->file($key))
                                    ? $request->file($key)
                                    : [$request->file($key)];

                        foreach ($files as $file) {
                            if (!$file || !$file->isValid()) continue;

                            $name      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $extension = $file->getClientOriginalExtension();
                            $filename  = $this->generateFilename($uploadPath, $name, $extension);
                            $size      = $file->getSize() / 1024;
                            $file->move($uploadPath, $filename);

                            Media::create([
                                'model_type' => $model_type,
                                'model_id'   => $model->id,
                                'name'       => $filename,
                                'url'        => asset($uploadUrl . $filename),
                                'type'       => 'lesson_pdf',
                                'file_name'  => $filename,
                                'size'       => $size,
                            ]);
                        }

                        $finalRequest = new Request(array_merge($finalRequest->all(), [$key => 'processed']));

                    } elseif ($key == 'add_audio') {

                        // ✅ Multiple audio files
                        $files = is_array($request->file($key))
                                    ? $request->file($key)
                                    : [$request->file($key)];

                        foreach ($files as $file) {
                            if (!$file || !$file->isValid()) continue;

                            $name      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $extension = $file->getClientOriginalExtension();
                            $filename  = $this->generateFilename($uploadPath, $name, $extension);
                            $size      = $file->getSize() / 1024;
                            $file->move($uploadPath, $filename);

                            Media::create([
                                'model_type' => $model_type,
                                'model_id'   => $model->id,
                                'name'       => $filename,
                                'url'        => asset($uploadUrl . $filename),
                                'type'       => 'lesson_audio',
                                'file_name'  => $filename,
                                'size'       => $size,
                            ]);
                        }

                        $finalRequest = new Request(array_merge($finalRequest->all(), [$key => 'processed']));

                    } else {

                        // Single file — lesson image etc
                        $file      = $request->file($key);
                        $name      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $filename  = $this->generateFilename($uploadPath, $name, $extension);

                        $file->move($uploadPath, $filename);

                        $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));

                        $model->lesson_image = $filename;
                        $model->save();
                    }
                }
            }
        }
    }

    return $finalRequest;
}

/* ─────────────────────────────────────
   Helper — Clean unique filename
───────────────────────────────────── */
private function generateFilename(string $uploadPath, string $name, string $extension): string
{
    $slug     = Str::slug($name);
    $filename = $slug . '.' . $extension;

    if (file_exists($uploadPath . '/' . $filename)) {
        $counter = 1;
        while (file_exists($uploadPath . '/' . $slug . '_' . $counter . '.' . $extension)) {
            $counter++;
        }
        $filename = $slug . '_' . $counter . '.' . $extension;
    }

    return $filename;
}


    public function saveLogos(Request $request)
    {
        if (!file_exists(public_path('storage/logos'))) {
            mkdir(public_path('storage/logos'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                $extension = array_last(explode('.', $request->file($key)->getClientOriginalName()));
                $name = array_first(explode('.', $request->file($key)->getClientOriginalName()));
                $filename = time() . '-' . str_slug($name) . '.' . $extension;
                $request->file($key)->move(public_path('storage/logos'), $filename);
                $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));

            }
        }

        return $finalRequest;
    }
}
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
public function saveAllFiles(Request $request, $fileInput = null, $model_type = null, $model = null)
{
    $uploadPath = public_path('uploads/');
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    if (!$fileInput || !$request->hasFile($fileInput)) {
        return $request;
    }

    $files = is_array($request->file($fileInput))
        ? $request->file($fileInput)
        : [$request->file($fileInput)];

    foreach ($files as $file) {
        if (!$file || !$file->isValid()) continue;

        $extension = strtolower($file->getClientOriginalExtension());
        $name      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName  = preg_replace('/[^A-Za-z0-9_]+/', '_', $name);
        $filename  = time() . '-' . \Illuminate\Support\Str::slug($safeName) . '.' . $extension;

        // ✅ Move FIRST before any other operation
        $file->move($uploadPath, $filename);

        // ✅ Convert office files to PDF only if exec() is available
        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            $inputFile = $uploadPath . $filename;
            $pdfName   = pathinfo($filename, PATHINFO_FILENAME) . '.pdf';
            $libreExe  = '/bin/libreoffice';

            $execEnabled = function_exists('exec')
                && !in_array('exec', array_map('trim', explode(',', ini_get('disable_functions'))));

            if ($execEnabled) {
                exec(
                    $libreExe . ' --headless --convert-to pdf '
                    . escapeshellarg($inputFile)
                    . ' --outdir '
                    . escapeshellarg($uploadPath)
                );

                if (file_exists($uploadPath . $pdfName)) {
                    @unlink($inputFile);
                    $filename  = $pdfName;
                }
            }
            // If exec() disabled: file stays as .doc/.docx etc., still saved to DB
        }

        $size = filesize($uploadPath . $filename) / 1024;

        if ($fileInput === 'add_pdf') {
            Media::create([
                'model_type' => $model_type,
                'model_id'   => $model->id,
                'name'       => $filename,
                'url'        => asset('uploads/' . $filename),
                'type'       => 'lesson_pdf',
                'file_name'  => $filename,
                'size'       => $size,
            ]);
        } elseif ($fileInput === 'add_audio') {
            Media::create([
                'model_type' => $model_type,
                'model_id'   => $model->id,
                'type'       => 'lesson_audio',
                'file_name'  => $filename,
                'url'        => asset('uploads/' . $filename),
                'size'       => $size,
            ]);
        } elseif ($fileInput === 'downloadable_files') {
            Media::create([
                'model_type' => $model_type,
                'model_id'   => $model->id,
                'name'       => $filename,
                'url'        => asset('uploads/' . $filename),
                'type'       => 'downloadable',
                'file_name'  => $filename,
                'size'       => $size,
            ]);
        } else {
            $model->lesson_image = $filename;
            $model->save();
        }
    }

    return $request;
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
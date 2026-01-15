<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    public function index()
    {
        return view('backend.update.index');
    }

    /**
     * List files in a zip archive matching a pattern
     *
     * @param string $zipPath Path to the zip file
     * @param string|null $pattern Optional regex pattern to filter files
     * @return array List of file paths
     */
    private function listZipFiles($zipPath, $pattern = null)
    {
        $zip = new \ZipArchive();
        $files = [];
        
        if ($zip->open($zipPath) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if ($pattern) {
                    if (preg_match($pattern, $filename)) {
                        $files[] = $filename;
                    }
                } else {
                    $files[] = $filename;
                }
            }
            $zip->close();
        }
        
        return $files;
    }

    /**
     * Extract zip archive to destination
     *
     * @param string $zipPath Path to the zip file
     * @param string $destination Destination directory
     * @return bool Success status
     */
    private function extractZip($zipPath, $destination)
    {
        $zip = new \ZipArchive();
        
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($destination);
            $zip->close();
            return true;
        }
        
        return false;
    }

    public function listFiles(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:zip'
        ]);
        $file = $request->file('file');
        $file_name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path() . '/updates/', $file_name);
        $is_verified = false;
        
        $zipPath = public_path() . '/updates/' . $file_name;
        $checkFiles = $this->listZipFiles($zipPath, '/\.key/i');
        
        foreach ($checkFiles as $item) {
            $item = Arr::last(explode('/', $item));
            if ($item == md5('NeonLMSUpdate') . '.key') {
                $is_verified = true;
            }
        }
        
        if ($is_verified == true) {
            $files = $this->listZipFiles($zipPath);
            return view('backend.update.file-list', compact('files', 'file_name'));
        } else {
            unlink($zipPath);
            return redirect(route('admin.update-theme'))->withFlashDanger(__('alerts.backend.general.unverified'));
        }
    }

    public function updateTheme(Request $request)
    {
        ini_set('max_execution_time', 1000);
        ini_set('memory_limit', '-1');

        $file_name = $request->file_name;
        if ($request->submit == 'cancel') {
            unlink(public_path() . '/updates/' . $file_name);
            return redirect(route('admin.update-theme'))->withFlashDanger(__('alerts.backend.general.cancelled'));
        } else {
            try {
                $zipPath = public_path() . '/updates/' . $file_name;
                
                if (!$this->extractZip($zipPath, base_path())) {
                    throw new \Exception('Failed to extract zip file');
                }
                
                unlink($zipPath);

                exec('cd ' . base_path() . '/ && composer install');

                Artisan::call("migrate");
                Artisan::call("fix:lesson-test-course");

                exec('cd ' . base_path() . '/ && composer du');

                unlink(base_path() . '/bootstrap/cache/packages.php');
                unlink(base_path() . '/bootstrap/cache/services.php');

                return redirect(route('admin.update-theme'))->withFlashSuccess(__('alerts.backend.general.updated'));
            } catch (\Exception $e) {
                return redirect(route('admin.update-theme'))->withFlashSuccess('Error updating script. ' . $e->getMessage());
            }
        }
    }
}

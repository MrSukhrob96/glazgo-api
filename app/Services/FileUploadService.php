<?php

namespace App\Services;

use App\Services\Interfaces\FileUploadServiceInterface;
use Illuminate\Support\Facades\Storage;

class FileUploadService implements FileUploadServiceInterface
{
    public function upload($file, $path = '')
    {
        $filePath = $file->store($path);
        return Storage::path($filePath);
    }
}

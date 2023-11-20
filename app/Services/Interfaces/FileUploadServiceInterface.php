<?php

namespace App\Services\Interfaces;

interface FileUploadServiceInterface
{
    public function upload($file, $path = '');
}
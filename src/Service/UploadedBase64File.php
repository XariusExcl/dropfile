<?php


namespace App\Service;


class UploadedBase64File extends \Symfony\Component\HttpFoundation\File\UploadedFile
{
    public function __construct(string $base64String, string $originalName)
    {
        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
        $data = (base64_decode($base64String));
        file_put_contents($filePath, $data);
        $error = null;
        $mimeType = null;
        $test = true;

        parent::__construct($filePath, $originalName, $mimeType, $error, $test);
    }

}
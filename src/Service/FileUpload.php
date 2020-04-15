<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUpload
{
    public function __construct()
    {}

    public function upload(UploadedFile $file, $fileName, $tags)
    {
        try {
            $file->move($this->setTargerDir($tags), $fileName);
        } catch (FileException $e) {
            // handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function setTargerDir($tags)
    {
        return "music/{$tags['artist']}/{$tags['album']}";
    }
}

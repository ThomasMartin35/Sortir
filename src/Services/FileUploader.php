<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private readonly string $targetDirectory)
    {

    }

    public function upload(UploadedFile $file): string
    {
        //create an id for the fileName
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {

        }
        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function delete(?string $fileName, string $rep): void
    {
        $filePath = $rep . '/' . $fileName;
        if (null != $fileName && file_exists($filePath)) {
            unlink($filePath);
        }
    }

}
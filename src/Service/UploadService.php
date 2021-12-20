<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private SluggerInterface $slugger;
    private string $uploadDirectory;

    public function __construct(SluggerInterface $slugger, string $uploadDirectory)
    {
        $this->slugger = $slugger;
        $this->uploadDirectory = $uploadDirectory;
    }

    public function decodeFile(string $base64): UploadedFile
    {
        $data = explode( ',', $base64);
        preg_match("/([a-z]*\/([a-z]*))/", $data[0],$m);
        $mimeType = $m[1];
        $extension = $m[2];

        $tempPath = '/tmp/' . uniqid() . '.' . $extension;
        $ifp = fopen( $tempPath, 'wb' );

        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );

        return new UploadedFile($tempPath, $tempPath, $mimeType, null, true);
    }

    public function uploadFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $this->uploadDirectory,
            $newFilename
        );

        return $newFilename;
    }

}
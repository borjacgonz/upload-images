<?php

use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadServiceTest extends KernelTestCase
{
    public function test_base64_to_uploadimage()
    {
        self::bootKernel();
        $service = self::getContainer()->get(UploadService::class);
        $file = $service->decodeFile(file_get_contents(__DIR__ . '/../resources/image_ok'));

        $this->assertNotNull($file);
        $this->assertEquals('image/jpeg', $file->getMimeType());
        $this->assertEquals('jpeg', $file->getExtension());
        $this->assertEquals(43557, $file->getSize());
    }


    public function test_upload_file()
    {
        self::bootKernel();
        $service = self::getContainer()->get(UploadService::class);
        $path = __DIR__ . '/../resources/image.jpg';
        $newPath = __DIR__ . '/../resources/image_copy.jpg';
        copy($path, $newPath);
        $uploadedFile =  new UploadedFile($newPath, $newPath, 'image/jpeg', null, true);

        $newPath = $service->uploadFile($uploadedFile);

        $this->assertNotNull($newPath);
        $this->assertNotNull(file_get_contents(__DIR__ . '/../../uploads/' . $newPath));
    }
}
<?php
namespace App\Controller;
use App\Service\UploadService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadController extends AbstractController
{

    #[Route("/upload", methods: ['POST'])]
    public function uploadFile(Request $request, UploadService $service, ValidatorInterface $validator, LoggerInterface $logger)
    {
        if (empty($request->request->get('file'))) {
            return $this->json(['error' => 'File not found'], 400);
        }
        $uploadedFile = $service->decodeFile($request->request->get('file'));

        $validation = $validator->validate(
            $uploadedFile,
            [
                new File([
                    'maxSize' => $this->getParameter('upload_file_max_size'),
                    'mimeTypes' => [ 'image/*' ]
                ])
            ]
        );

        if ($validation->count() > 0) {
            return $this->json($validation, 400);
        }

        try {
            $newFilename = $service->uploadFile($uploadedFile);
        } catch (FileException) {
            return $this->json(['error' => 'failed to move uploaded file'], 500);
        }

        $logger->info('Uploaded file: ' . $newFilename . ' by user: ' . $this->getUser()->getUserIdentifier());

        return $this->json(
            ["url" => $request->getUriForPath('/file/') . $newFilename],
            201
        );
    }

    #[Route("/file/{fileName}", methods: ['GET'])]
    public function getFile(string $fileName)
    {
        $filePath =  $this->getParameter('upload_directory') . $fileName;

        try {
            return new BinaryFileResponse($filePath);
        } catch (FileNotFoundException) {
            return $this->json(
                'File: ' . $filePath . ' not found.',
                400
            );
        }
    }
}

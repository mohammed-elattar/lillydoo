<?php

declare(strict_types=1);

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Cocur\Slugify\SlugifyInterface;

class FileUploader
{
    private string $targetDirectory;
    private SlugifyInterface $slugger;
    private Filesystem $filesystem;

    public function __construct(string $targetDirectory, SlugifyInterface $slugger, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slugify($originalFilename);
        $fileName = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function delete(string $fileName): void
    {
        $filePath = sprintf('%s/%s', $this->targetDirectory, $fileName);
        if ($this->filesystem->exists($filePath)) {
            try {
                $this->filesystem->remove($this->targetDirectory . '/' . $fileName);
            } catch (FileException $e) {
                //TODO to handle the error
            }
        }
    }
}

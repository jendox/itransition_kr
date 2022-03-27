<?php
declare(strict_types=1);
namespace App\Service;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private Filesystem $filesystem,
        private string $imagesDirectory,
        private SluggerInterface $slugger) {
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function upload(UploadedFile $file, string $userDirectory): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);
        $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');
        $this->filesystem->writeStream($userDirectory.'/'.$newFileName, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }
        return $newFileName;
    }

    /**
     * @throws FilesystemException
     */
    public function remove(string $fileName): void
    {
        $this->filesystem->delete($fileName);
    }

    public function getTargetDirectory(): string {
        return $this->imagesDirectory;
    }
}
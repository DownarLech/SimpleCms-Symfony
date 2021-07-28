<?php declare(strict_types=1);

namespace App\Component\ProductFrontEnd\Business\Upload;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private SluggerInterface $slugger;
    private string $targetDirectory;

    /**
     * FileUploader constructor.
     * @param SluggerInterface $slugger
     * @param string $targetDirectory
     */
    public function __construct(SluggerInterface $slugger, string $targetDirectory)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
    }


    public function upLoad(UploadedFile $file): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);

        $newFileName = $safeFileName.'.'.$file->guessExtension(); //.csv

        try {
            $file->move($this->getTargetDirectory(), $newFileName);
        } catch (FileException $exception) {
            echo "There is something wrong with upload. " . $exception->getMessage();
        }
        return $newFileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

}
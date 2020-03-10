<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\Intervenant;

class FileUploader
{

    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, Intervenant $intervenant)
    {

        // Vérifie s'il existe déjà un fichier
        if ($intervenant->getNameCv()) {

            try {

                $oldFile = $this->getTargetDirectory() . '/' . $intervenant->getNameCv();

                $filesystem = new Filesystem();

                // Si le fichier existe encore dans le dossier
                if ($filesystem->exists($oldFile)) {

                    // On le supprime
                    $filesystem->remove($oldFile);

                }

            } catch (IOExceptionInterface $exception) {
                // return null;
            }

        }

        $name = $intervenant->getNom() . '_' . $intervenant->getPrenom();
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $name);
        $fileName = $safeFilename . '_' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return null;
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

}

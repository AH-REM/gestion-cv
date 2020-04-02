<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Filesystem\Filesystem;

use App\Service\FileUploader;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->redirectToRoute('list_intervenant');
    }

    /**
     * @Route("/cv/{file}", name="show_cv")
     */
    public function show_cv(String $file = null, FileUploader $fileUploader) {
        if (!$file) {
            throw $this->createNotFoundException();
        }

        $path = $fileUploader->getTargetDirectory() . '/' . $file;

        $filesystem = new Filesystem();
        if ($filesystem->exists($path)) {

            return new BinaryFileResponse($path);

        }

        else throw $this->createNotFoundException();
    }
}

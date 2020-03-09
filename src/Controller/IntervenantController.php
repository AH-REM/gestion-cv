<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\FileUploader;

use App\Entity\Intervenant;
use App\Entity\TypeEmploi;
use App\Repository\IntervenantRepository;
use App\Form\IntervenantType;

/**
 * @Route("/intervenant")
 */
class IntervenantController extends AbstractController
{
    /**
     * @Route("/", name="list_intervenant")
     */
    public function list(IntervenantRepository $repo)
    {
        $intervenants = $repo->findAll();

        return $this->render('intervenant/list.html.twig', [
            'intervenants' => $intervenants
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_intervenant")
     * @Route("/new", name="new_intervenant")
     */
    public function form(Intervenant $intervenant = null, Request $request, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $editMode = $intervenant ? true : false;

        // Si l'intervenant n'est pas donné en parametre
        if (!$editMode) {

            // On récupère la route
            $currentRoute = $request->attributes->get('_route');

            // Si on est en mode édition, on renvoie vers l'autre route
            if ($currentRoute == 'edit_intervenant') return $this->redirectToRoute('new_intervenant');
            else $intervenant = new Intervenant();

        }
        // On set le niveau
        else $intervenant->setNiveau($intervenant->getDiplome()->getNiveau());

        $form = $this->createForm(IntervenantType::class, $intervenant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $intervenant = $form->getData();

            /*$file = $form->get('file')->getData();

            if ($file) {

                $fileName = $fileUploader->upload($file, $intervenant);

                dump($fileName); die();

                // faudrait test si il y a un nom
                // et return une erreur si le fichier n'as pas été crée

                //$product->setBrochureFilename($brochureFileName);

            }*/

            $emploi = $intervenant->getEmploi();

            if (!$intervenant->getCreatedAt()) {
                $intervenant->setCreatedAt(new \DateTime());
            }

            $manager->persist($intervenant);
            $manager->flush();

            //return $this->redirectToRoute('list_intervenant');
        }

        return $this->render('intervenant/form.html.twig', [
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }

}

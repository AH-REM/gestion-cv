<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

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
     * @Route("/show/{id}", name="show_intervenant")
     */
    public function show(Intervenant $intervenant = null)
    {
        if (!$intervenant) return $this->redirectToRoute('list_intervenant');

        return $this->render('intervenant/show.html.twig', [
            'intervenant' => $intervenant
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit_intervenant")
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

        $form = $this->createForm(IntervenantType::class, $intervenant, [
            'file_name' => $intervenant->getNameCv(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $intervenant = $form->getData();

            $file = $form->get('file')->getData();
            $err = false;

            if ($file) {

                $fileName = $fileUploader->upload($file, $intervenant);

                // Si lors de l'upload il y a eu une erreur
                if (!$fileName) {

                    $err = true;
                    $form->get('file')->addError(new FormError('Une erreur s\'est produite lors de l\'envoie du fichier PDF. Veuillez recommencer ultérieurement.'));

                }
                else {

                    $intervenant->setNameCv($fileName)
                                ->setDateMajCv(new \DateTime());

                }

            }

            if (!$err) {

                $emploi = $intervenant->getEmploi();

                if (!$intervenant->getCreatedAt()) {
                    $intervenant->setCreatedAt(new \DateTime());
                }

                $manager->persist($intervenant);
                $manager->flush();

                return $this->redirectToRoute('list_intervenant');

            }

        }

        return $this->render('intervenant/form.html.twig', [
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }

}

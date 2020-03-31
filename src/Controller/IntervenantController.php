<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

use App\Service\FileUploader;
use App\Service\Paginator;

use App\Entity\Intervenant;
use App\Entity\IntervenantSearch;
use App\Entity\TypeEmploi;

use App\Repository\IntervenantRepository;
use App\Repository\DomaineRepository;

use App\Form\IntervenantType;
use App\Form\IntervenantSearchType;

/**
 * @Route("/intervenant")
 */
class IntervenantController extends AbstractController
{
    /**
     * @Route("/", name="list_intervenant")
     */
    public function list(Request $request, IntervenantRepository $repo, Paginator $paginator)
    {
        $intervenants = $paginator->paginate(
            $repo->findAllQuery(),
            $request
        );

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
     * @Route("/search", name="search_intervenant")
     */
    public function search(Request $request, IntervenantRepository $repo, Paginator $paginator) {

        $search = new IntervenantSearch();
        $form = $this->createForm(IntervenantSearchType::class, $search);
        $form->handleRequest($request);

        $intervenants = $paginator->paginate(
            $repo->searchIntervenantQuery($search),
            $request
        );

        return $this->render('intervenant/search.html.twig', [
            'form' => $form->createView(),
            'intervenants' => $intervenants,
        ]);

    }

    /**
     * @Route("/edit/{id}", name="edit_intervenant")
     * @Route("/new", name="new_intervenant")
     */
    public function form(Intervenant $intervenant = null, Request $request, EntityManagerInterface $manager, DomaineRepository $dr, FileUploader $fileUploader)
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
            'intervenant' => $intervenant,
            'domaines' => $dr->findAll()
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

                return $this->redirectToRoute('show_intervenant', [ 'id' => $intervenant->getId() ]);

            }

        }

        return $this->render('intervenant/form.html.twig', [
            'intervenant' => $intervenant,
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }

}

<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\DomaineRepository;

use App\Service\Paginator;

use App\Entity\Domaine;
use App\Form\DomaineType;

/**
 * @Route("/gestion/domaine")
 */
class DomaineController extends AbstractController
{
    private $name;
    private $manager;
    private $paginator;

    public function __construct(EntityManagerInterface $manager, Paginator $paginator)
    {
        $this->name = 'domaine';
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="list_domaine")
     */
    public function list(Request $request, DomaineRepository $repo)
    {
        $collection = $this->paginator->paginate(
            $repo->findAllQuery(),
            $request
        );

        return $this->render('base_list.html.twig', [
            'title' => 'Les domaines',
            'collection' => $collection,
            'name' => $this->name,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_domaine")
     */
    public function delete(Domaine $domaine = null, Request $request)
    {
        if ($domaine && $domaine->getIntervenants()->count() < 1) {

            // On supprime le domaine
            $this->manager->remove($domaine);
            $this->manager->flush();

        }
        return $this->redirectToRoute('list_domaine');
    }

    /**
     * @Route("/edit/{id}", name="edit_domaine")
     * @Route("/new", name="new_domaine")
     */
    public function form(Domaine $domaine = null, Request $request)
    {
        $editMode = $domaine ? true : false;

        // Si le domaine n'est pas donné en parametre
        if (!$editMode) {

            // On récupère la route
            $currentRoute = $request->attributes->get('_route');

            // Si on est en mode édition, on renvoie vers l'autre route
            if ($currentRoute == 'edit_domaine') return $this->redirectToRoute('new_domaine');
            else $domaine = new Domaine();

        }

        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $domaine = $form->getData();

            $this->manager->persist($domaine);
            $this->manager->flush();

            return $this->redirectToRoute('list_domaine');

        }

        return $this->render('base_form.html.twig', [
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }
}

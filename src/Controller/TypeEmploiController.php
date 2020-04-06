<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\TypeEmploiRepository;

use App\Service\Paginator;

use App\Entity\TypeEmploi;
use App\Form\TypeEmploiType;

/**
 * @Route("/gestion/emploi")
 */
class TypeEmploiController extends AbstractController
{
    private $name;
    private $manager;
    private $paginator;

    public function __construct(EntityManagerInterface $manager, Paginator $paginator)
    {
        $this->name = 'emploi';
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="list_emploi")
     */
    public function index(Request $request, TypeEmploiRepository $repo)
    {
        $collection = $this->paginator->paginate(
            $repo->findAllQuery(),
            $request
        );

        return $this->render('base_list.html.twig', [
            'title' => 'Les emplois',
            'collection' => $collection,
            'name' => $this->name,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_emploi")
     */
    public function delete(TypeEmploi $emploi = null, Request $request)
    {
        if ($emploi && $emploi->getIntervenants()->count() < 1) {

            // On supprime l'emploi
            $this->manager->remove($emploi);
            $this->manager->flush();

        }
        return $this->redirectToRoute('list_emploi');
    }

    /**
     * @Route("/edit/{id}", name="edit_emploi")
     * @Route("/new", name="new_emploi")
     */
    public function form(TypeEmploi $emploi = null, Request $request) {

        $editMode = $emploi ? true : false;

        // Si l'emploi n'est pas donné en parametre
        if (!$editMode) {

            // On récupère la route
            $currentRoute = $request->attributes->get('_route');

            // Si on est en mode édition, on renvoie vers l'autre route
            if ($currentRoute == 'edit_emploi') return $this->redirectToRoute('new_emploi');
            else $emploi = new TypeEmploi();

        }

        $form = $this->createForm(TypeEmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $emploi = $form->getData();

            $this->manager->persist($emploi);
            $this->manager->flush();

            return $this->redirectToRoute('list_emploi');

        }

        return $this->render('base_form.html.twig', [
            'title' => 'd\'un emploi',
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);


    }

}

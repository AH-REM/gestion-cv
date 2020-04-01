<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\NiveauRepository;

use App\Service\Paginator;

use App\Entity\Niveau;
use App\Form\NiveauType;

/**
 * @Route("/niveau")
 */
class NiveauController extends AbstractController
{
    private $name;
    private $manager;
    private $paginator;

    public function __construct(EntityManagerInterface $manager, Paginator $paginator)
    {
        $this->name = 'niveau';
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="list_niveau")
     */
    public function list(Request $request, NiveauRepository $repo)
    {
        $collection = $this->paginator->paginate(
            $repo->findAllQuery(),
            $request
        );

        return $this->render('base_list.html.twig', [
            'title' => 'Les niveaux',
            'collection' => $collection,
            'name' => $this->name,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_niveau")
     */
    public function delete(Niveau $niveau = null, Request $request)
    {
        if ($niveau && $niveau->getDiplomes()->count() < 1) {

            // On supprime le domaine
            $this->manager->remove($niveau);
            $this->manager->flush();

        }
        return $this->redirectToRoute('list_niveau');
    }

    /**
     * @Route("/edit/{id}", name="edit_niveau")
     * @Route("/new", name="new_niveau")
     */
    public function form(Niveau $niveau = null, Request $request)
    {
        $editMode = $niveau ? true : false;

        // Si le domaine n'est pas donné en parametre
        if (!$editMode) {

            // On récupère la route
            $currentRoute = $request->attributes->get('_route');

            // Si on est en mode édition, on renvoie vers l'autre route
            if ($currentRoute == 'edit_niveau') return $this->redirectToRoute('new_niveau');
            else $niveau = new Niveau();

        }

        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $niveau = $form->getData();

            $this->manager->persist($niveau);
            $this->manager->flush();

            return $this->redirectToRoute('list_niveau');

        }

        return $this->render('base_form.html.twig', [
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }
}

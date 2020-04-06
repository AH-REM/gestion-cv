<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\DiplomeRepository;

use App\Service\Paginator;

use App\Entity\Diplome;
use App\Form\DiplomeType;

/**
 * @Route("/gestion/diplome")
 */
class DiplomeController extends AbstractController
{
    private $name;
    private $manager;
    private $paginator;

    public function __construct(EntityManagerInterface $manager, Paginator $paginator)
    {
        $this->name = 'diplome';
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="list_diplome")
     */
    public function list(Request $request, DiplomeRepository $repo)
    {
        $collection = $this->paginator->paginate(
            $repo->findAllQuery(),
            $request
        );

        return $this->render('base_list.html.twig', [
            'title' => 'Les diplomes',
            'collection' => $collection,
            'name' => $this->name,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_diplome")
     */
    public function delete(Diplome $diplome = null, Request $request)
    {
        if ($diplome && $diplome->getIntervenants()->count() < 1) {

            // On supprime le diplome
            $this->manager->remove($diplome);
            $this->manager->flush();

        }
        return $this->redirectToRoute('list_diplome');
    }

    /**
     * @Route("/edit/{id}", name="edit_diplome")
     * @Route("/new", name="new_diplome")
     */
    public function form(Diplome $diplome = null, Request $request)
    {
        $editMode = $diplome ? true : false;

        // Si le domaine n'est pas donné en parametre
        if (!$editMode) {

            // On récupère la route
            $currentRoute = $request->attributes->get('_route');

            // Si on est en mode édition, on renvoie vers l'autre route
            if ($currentRoute == 'edit_diplome') return $this->redirectToRoute('new_diplome');
            else $diplome = new Diplome();

        }

        $form = $this->createForm(DiplomeType::class, $diplome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $diplome = $form->getData();

            $this->manager->persist($diplome);
            $this->manager->flush();

            return $this->redirectToRoute('list_diplome');

        }

        return $this->render('base_form.html.twig', [
            'title' => 'd\'un diplome',
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }
}

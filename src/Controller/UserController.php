<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Repository\UserRepository;

use App\Service\Paginator;

use App\Entity\User;
use App\Form\UserType;

/**
 * @Route("/gestion/admin/user")
 */
class UserController extends AbstractController
{
    private $name;
    private $manager;
    private $paginator;

    public function __construct(EntityManagerInterface $manager, Paginator $paginator)
    {
        $this->name = 'user';
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="list_user")
     */
    public function list(Request $request, UserRepository $repo)
    {
        $collection = $this->paginator->paginate(
            $repo->findAllQuery(),
            $request
        );

        return $this->render('base_list.html.twig', [
            'title' => 'Les utilisateurs',
            'collection' => $collection,
            'name' => $this->name,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_user")
     */
    public function delete(User $user = null, Request $request)
    {
        if ($user && $user->getUsername() != $this->getUser()->getUsername()) {

            // On supprime l'utilisateur
            $this->manager->remove($user);
            $this->manager->flush();

        }
        return $this->redirectToRoute('list_user');
    }

    /**
     * @Route("/edit/{id}", name="edit_user")
     * @Route("/new", name="new_user")
     */
    public function form(User $user = null, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $editMode = $user ? true : false;

        // Si le domaine n'est pas donné en parametre
        if (!$editMode) {

            // On récupère la route
            $currentRoute = $request->attributes->get('_route');

            // Si on est en mode édition, on renvoie vers l'autre route
            if ($currentRoute == 'edit_user') return $this->redirectToRoute('new_user');
            else $user = new User();

        }
        else {
            // Si c'est le même utilisateur qui est connecté
            if ($user->getUsername() == $this->getUser()->getUsername()) return $this->redirectToRoute('list_user');

            $user->setPassword("");
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute('list_user');

        }

        return $this->render('base_form.html.twig', [
            'title' => 'd\'un utilisateur',
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);

    }
}

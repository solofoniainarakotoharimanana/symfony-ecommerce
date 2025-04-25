<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user')]
    public function index(UserRepository $userRepo): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepo->findAll(),
        ]);
    }

    #[Route('/user/{id}/to/editor', name: 'app_user_to_editor')]
    public function changeRole(EntityManagerInterface $em, User $user): Response
    {
        $user->setRoles(['ROLE_EDITOR']);

        $em->flush();
        $this->addFlash('success', "Le role editeur à été attribué à votre utilisateur");

        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/{id}/remove/editor', name: 'app_user_remove_editor_role')]
    public function removeRoleEditor(User $user, EntityManagerInterface $em):Response
    {
        $user->setRoles([]);
        $em->flush();

        $this->addFlash('danger', 'Suppression de rôle editeur a été effectué avec succès.');

        return $this->redirectToRoute('app_user');
    }
}

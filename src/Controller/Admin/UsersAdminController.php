<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\Admin\AdminUsersFormType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users', name: 'admin_users_')]
class UsersAdminController extends AbstractController
{
        // Affichage de le liste des utilisateurs
    #[Route('/', name: 'index', methods: ['GET'])]
    public function users(UsersRepository $usersRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    #[Route('/private', name: 'private', methods: ['GET'])]
    public function private(UsersRepository $usersRepository): Response
    {
        return $this->render('admin/users/private.html.twig', [
            'users' => $usersRepository->findBy([], ['lastname' => 'asc']),
        ]);
    }

    // Affichage de la modification des utilisateurs par un administrateur
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function adminUserEdit(Request $request, Users $users, UsersRepository $usersRepository): Response
    {   
        $roles = $this->getParameter('security.role_hierarchy.roles');
        $form = $this->createForm(AdminUsersFormType::class, $users);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            $users->setUpdatedAt(new \DateTimeImmutable());
            $usersRepository->save($users, true);

            $this->addFlash('info', 'L\'utilisateur a bien été modifié');

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/edit.html.twig', [
            'users' => $users,
            'roles' => $roles,
            'form' => $form->createView(),
        ]);
    }

    // Suppression d'un utilisateur
    #[Route('/{id}/delete', name:'delete', methods: ['GET', 'POST'])]
    public function adminUserDelete(){
        //Mettre ici la suppression de l'utilisateur

        return $this->render('baseTest.html.twig');
    }
}
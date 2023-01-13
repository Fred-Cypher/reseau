<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUsersFormType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function update(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        $form = $this->createForm(EditUsersFormType::class, $user);
        $user->setEmail(($user->getEmail()));
        $user->getPassword($user->getPassword());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $usersRepository->save($user, true);

            $this->addFlash('info', 'Votre profil a bien été modifié');

            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/images', name: 'images')]
    public function showImages(): Response{
        return $this->render('profile/images.html.twig');
    }

    #[Route('/articles', name: 'articles')]
    public function showArticles(): Response
    {
        return $this->render('profile/articles.html.twig');
    }
}

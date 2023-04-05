<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Images;
use App\Entity\Users;
use App\Form\EditUsersPassFormType;
use App\Form\EditUserTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    // Affichage de la page de profil d'un utilisateur
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    // Affichage de la page d'édition de l'utilisateur
    #[Route('/edit', name: 'edit')]
    public function edit(): Response
    {

        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'Edition du profil de l\'utilisateur'
        ]);
    }

    // Affichage de la page d'édition du mot de passe
    #[Route('/edit/pass/{id}', name: 'edit_password', methods: ['GET', 'POST'])]
        public function editPass(
            Users $currentUser, 
            Request $request, 
            EntityManagerInterface $entityManagerInterface, 
            UserPasswordHasherInterface $hasher
            ): Response
        {
        $form = $this->createForm(EditUsersPassFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On efface le token
            $currentUser->setPassword(
                $hasher->hashPassword(
                    $currentUser,
                    $form->get('newPassword')->getData()
                )
            );
            $entityManagerInterface->persist($currentUser);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

            return $this->render('profile/editPass.html.twig', [
                //'controller_name' => 'Edition du profil de l\'utilisateur'
                'form' => $form->createView()
            ]);
        }

    // Affichage de la page d'édition de l'adresse mail
    #[Route('/edit/email/{id}', name: 'edit_email', methods: ['GET', 'POST'])]
    public function editEmail(Request $request, Users $user, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(EditUserTypeForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                $user->setUpdatedAt(new \DateTimeImmutable());
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();

                $this->addFlash('success', 'Votre profil a bien été édité.');

                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            } 

        return $this->render('profile/editEmail.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    // Affichage de la page avec la liste des images d'un utilisateur
    #[Route('/images', name: 'images')]
    public function showImages(): Response{
        return $this->render('profile/images.html.twig');
    }

    // Affichage de la page avec la liste des articles d'un utilisateur
    #[Route('/articles', name: 'articles')]
    public function showArticles(): Response
    {
        return $this->render('profile/articles.html.twig');
    }

    //Suppression du compte d'un utilisateur
    #[Route('/delete', name: 'user_delete', methods: ['GET', 'POST'])]
    public function deleteUser(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): RedirectResponse
    {
        $user = $this->getUser();

        foreach ($entityManager->getRepository(Images::class)->findBy(['user' => $user]) as $images) {
            $entityManager->remove($images);
        }

        foreach ($entityManager->getRepository(Articles::class)->findBy(['user' => $user]) as $articles) {
            $entityManager->remove($articles);
        }

        $tokenStorage->setToken(null);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
    } 
}

<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUsersPassFormType;
use App\Form\EditUserTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    /*private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }*/

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
        /*$user = $this->getUser();

        if($this->security->isGranted('ROLE_USER')){
            
            $form = $this->createForm(EditUsersFormType::class, $user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                modif mdp et modif email 
                $user = $this->getUser();
            }
        }*/

        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'Edition du profil de l\'utilisateur'
        ]);
    }

    // Affichage de la page d'édition du mot de passe
    //#[Security("is_granted('ROLE_USER' and user === user)")]
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
    //#[Security("is_granted('ROLE_USER' and user === user)")]
    #[Route('/edit/email/{id}', name: 'edit_email', methods: ['GET', 'POST'])]
    public function editEmail(Request $request, Users $user, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(EditUserTypeForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$user = $form->getData();
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
}

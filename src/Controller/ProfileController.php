<?php

namespace App\Controller;

use App\Entity\Users;
//use App\Form\EditUsersEmailFormType;
use App\Repository\UsersRepository;
//use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(Request $request): Response
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

    #[Route('/edit/pass', name: 'edit_password')]
        public function editPass()
    {

    }

    #[Route('/edit/email', name: 'edit_email', methods: ['GET', 'POST'])]
    public function editEmail(/*Request $request, Users $users, UsersRepository $usersRepository*/): Response
    {
        /*$form = $this->createForm(EditUsersEmailFormType::class, $users);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $users->getEmail();
            $usersRepository->save($users, true);

            $this->addFlash('info', 'Votre adresse email a bien été modifiée.');

            return $this->redirectToRoute('profile_edit');
        }

        return $this->renderform('profile/index.html.twig', [
            'users' => $users,
            'form' => $form,
        ]);*/
        return $this->render('profile/editEmail.html.twig', [
            'controller_name' => 'Edition du profil de l\'utilisateur'
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

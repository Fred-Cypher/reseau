<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JwtService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, 
    EntityManagerInterface $entityManager, SendMailService $mail, JwtService $jwt): Response
    {
        $user = new Users('');
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // Génération du Jwt de l'utilisateur
            // Création header
            $header = [
                'type' => 'JWT',
                'alg' => 'HS256'
            ];

            // Création payload
            $payload = [
                'user_id' => $user->getId(),
            ];
            
            // Génération token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Envoi d'un mail
            $mail->send(
                'no-reply@test.com',
                $user->getEmail(),
                'Activation de votre compte sur le forum de partage',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JwtService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        // Vérification token : valide, pas expiré et pas modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret')))
            {
                //Récupération payload
                $payload = $jwt->getPayload($token);

                //Récupération du user du token
                $user = $usersRepository->find($payload['user_id']);

                // Vérification que l'utilisateur existe et si son compte n'est pas encore actif
                if($user && !$user->getIsVerified()){
                    $user->setIsVerified(true);
                    $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('profile_index');
                }

            }
            // Problème dans token
            $this->addFlash('danger', 'Le token est invalide ou a expiré');
            return $this->redirectToRoute('app_login');
    }

        #[Route('/newverif', name: 'resend_verif')]
        public function resendVerif(JwtService $jwt, SendMailService $mail, UsersRepository $usersRepository): Response
        {
            $user = $this->getUser();

            if(!$user){
                $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
                return $this->redirectToRoute('app_login');
            }

            if($user->getIsVerified()){
                $this->addFlash('warning', 'Cet utilisateur est déjà activé');
                return $this->redirectToRoute('profile_index');
            }

            // Génération du Jwt de l'utilisateur
            // Création header
            $header = [
                'type' => 'JWT',
                'alg' => 'HS256'
            ];

            // Création payload
            $payload = [
                'user_id' => $user->getId() 
            ];

            // Génération token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Envoi d'un mail
            $mail->send(
                'no-reply@test.com',
                $user->getEmail(),
                'Activation de votre compte sur le forum de partage',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );
            $this->addFlash('success', 'Email de vérification envoyé');
            return $this->redirectToRoute('profile_index');
        }
}

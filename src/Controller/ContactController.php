<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManagerInterface,
        MailerInterface $mailer,
        ): Response
    {
        $contact = new Contact();
        
        if($this->getUser()){
            $contact->setFirstName($this->getUser()->getFirstName())
                ->setLastName($this->getUser()->getLastName())
                ->setEmail($this->getUser()->getEmail()); 
            }

        
        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            //Envoi d'email
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('admin@test.com')
                ->subject($contact->getSubject())
                ->htmlTemplate('emails/contact.html.twig')

                ->context([
                    'contact' => $contact
                ]);

            $mailer->send($email);
            dd($email);

            $entityManagerInterface->persist($contact);
            dd($contact);
            $entityManagerInterface->flush();

            $this->addFlash('info', 'Votre message est bien envoyé');

            return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

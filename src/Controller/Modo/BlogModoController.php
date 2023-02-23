<?php

namespace App\Controller\Modo;

use App\Entity\Images;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modo/blog', name: 'modo_blog_')]
class BlogModoController extends AbstractController
{
    // Affichage de la page index du blog image pour les modérateurs
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ImagesRepository $imagesRepository): Response
    {
        return $this->render('modo/blog/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    // Affichage du switch de visibilité des images
    #[Route('/visibility', name: 'visibility', methods: ['GET'])]
    public function blogVisibility(ImagesRepository $imagesRepository): Response
    {
        return $this->render('modo/blog/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    // Affichage de l'édition d'une page du blog images
    #[Route('/{slug}/edit', name: 'images_edit', methods: ['GET', 'POST'])]
    public function modoBlogEdit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $form = $this->createForm(ModoBlogFormType::class, $image);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            $image->setIsVisible($image->isIsVisible() ? true : false);
            $imagesRepository->save($image, true);

            $this->addFlash('info', 'L\'image a bien été modifiée');

            return $this->redirectToRoute('modo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modo/blog/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }
}
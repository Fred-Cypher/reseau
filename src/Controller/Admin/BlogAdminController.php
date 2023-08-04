<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Form\Admin\AdminBlogFormType;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/blog', name: 'admin_blog_') ]
class BlogAdminController extends AbstractController
{
    // Affichage de la page index du blog images pour les administrateurs
    #[Route('/', name: 'index', methods: ['GET'])]
    public function blogImage(ImagesRepository $imagesRepository, Request $request): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    // Affichage du switch de visibilité des images
    #[Route('/visibility', name: 'visibility', methods: ['GET'])]
    public function blogVisibility(ImagesRepository $imagesRepository): Response
    {
        return $this->render('admin/blog/index.html.twig',[
            'images' => $imagesRepository->findAll(),
        ]);
    }

    // Affichage de l'édition d'une page du blog images
    #[Route('/{slug}/edit', name: 'images_edit', methods: ['GET', 'POST'])]
    public function adminBlogEdit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $form = $this->createForm(AdminBlogFormType::class, $image);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){
            $image->setIsVisible(($image->isIsVisible() ? true : false));
            $image->setUpdatedAt(new \DateTimeImmutable());
            $imagesRepository->save($image, true);

            $this->addFlash('info', 'L\'image a bien été modifiée');

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/blog/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }
}




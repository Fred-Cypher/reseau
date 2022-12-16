<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\BlogImagesFormType;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog/images', name: 'images_')]
class BlogImagesController extends AbstractController
{
    #[Route('/', name: 'app_blog_images_index', methods: ['GET'])]
    public function index(ImagesRepository $imagesRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $images = $imagesRepository->imagesPaginated($page, 2);

        return $this->render('blog_images/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/new', name: 'app_blog_images_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImagesRepository $imagesRepository): Response
    {
        $image = new Images();
        $form = $this->createForm(BlogImagesFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image->setUser($this->getUser());

            $picture = $form->get('image_url')->getData();

            $file = md5(uniqid()).'.'.$picture->guessExtension();

            $picture->move(
                $this->getParameter('images_directory'),
                $file
            );

            $image->setImageUrl($file);

            $imagesRepository->save($image, true);

            $this->addFlash('info', 'Votre image a bien été enregistrée, vous pouvez dès à présent la retrouver dans la liste d\'images.');

            return $this->redirectToRoute('images_app_blog_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog_images/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_images_show', methods: ['GET'])]
    public function show(Images $image): Response
    {
        return $this->render('blog_images/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_images_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $form = $this->createForm(BlogImagesFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image->setUser($this->getUser());
            $imagesRepository->save($image, true);

            $this->addFlash('info', 'Votre image a bien été modifiée.');

            return $this->redirectToRoute('images_app_blog_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog_images/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_blog_images_delete', methods: ['POST'])]
    public function delete(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $imagesRepository->remove($image, true);
        }

        return $this->redirectToRoute('images_app_blog_images_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesFormType;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/images', name: 'images_')]
class ImagesController extends AbstractController
{
    #[Route('/', name: 'app_images', methods: ['GET'])]
    public function newImage(ImagesRepository $imagesRepository): Response
    {
        return $this->render('sharing/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_images_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImagesRepository $imagesRepository): Response
    {
        $image = new Images();
        $form = $this->createForm(ImagesFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$image->setImageUrl($this->getImageUrl());
            $imagesRepository->save($image, true);

            $this->addFlash('info', 'Votre image a bien été enregistrée, vous pouvez dès à présent la retrouver dans la liste.');

            return $this->redirectToRoute('images_app_images', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sharing/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_images_show', methods: ['GET'])]
    public function show(Images $image): Response
    {
        return $this->render('sharing/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_images_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $form = $this->createForm(ImagesFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesRepository->save($image, true);

            return $this->redirectToRoute('images_app_images', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sharing/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_images_delete', methods: ['POST'])]
    public function delete(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $imagesRepository->remove($image, true);
        }

        return $this->redirectToRoute('images_app_images', [], Response::HTTP_SEE_OTHER);
    }
}

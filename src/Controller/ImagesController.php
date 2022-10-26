<?php

namespace App\Controller;

use App\Entity\Images;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partage', name: 'images_')]
class ImagesController extends AbstractController
{
    #[Route('/', name: 'images')]
    public function images(): Response
    {
        return $this->render('partage/images.html.twig', [
            'controller_name' => 'Forum images']);
    }

    #[Route('/{title}', name: 'image')]
    public function image(Images $image): Response
    {
        return $this->render('partage/images.html.twig', compact('image'));
    }
}
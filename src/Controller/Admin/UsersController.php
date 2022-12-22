<?php

namespace App\Controller\Admin;

use App\Repository\ArticlesRepository;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class UsersController extends AbstractController
{
    #[Route('/users', name: 'users_index')]
    public function index(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

    #[Route('/blog', name: 'blog_index', methods: ['GET'])]
    public function blogImage(ImagesRepository $imagesRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $images = $imagesRepository->imagesPaginated($page, 6);

        return $this->render('admin/blog/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/articles', name: 'articles_index', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }
}
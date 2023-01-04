<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\Users;
use App\Repository\ArticlesRepository;
use App\Repository\ImagesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class UsersController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/users', name: 'users_index', methods: ['GET'])]
    public function users(UsersRepository $usersRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    #[Route('/blog', name: 'blog_index', methods: ['GET'])]
    public function blogImage(ImagesRepository $imagesRepository, Request $request): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    #[Route('/blog_visibility', name: 'blog_visibility', methods: ['GET'])]
    public function blogVisibility(ImagesRepository $imagesRepository): Response
    {
        return $this->render('admin/blog/index.html.twig',[
            'images' => $imagesRepository->findAll(),
        ]);
    }

    #[Route('/articles', name: 'articles_index', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    #[Route('/articles_visibility', name: 'article_visibility', methods: ['GET', 'POST'])]
    public function articlesVisibility(ArticlesRepository $articlesRepository, Articles $article): Response
    {
        $article->setIsVisible(($article->isIsVisible()? true : false));
        $articlesRepository->save($article, true);
        /*$em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();*/

        return $this->render('admin/articles/index.html.twig', [
            /*'articles' => $articlesRepository->findAll(),*/
            'articles' => $article,
        ]);
    }
}
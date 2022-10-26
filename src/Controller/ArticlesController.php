<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name: 'articles_')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'articles')]
    public function articles(ManagerRegistry $manager): Response
    {
        $repository = $manager->getRepository(Articles::class);
        $articles = $repository->findAll();

        return $this->render('articles/articles.html.twig', ['articles' => $articles])

        /*return $this->render('articles/articles.html.twig', [
            'controller_name' => 'Forum articles',
            'articles' => 'articles'
        ])*/;
    }

    #[Route('/{title}', name: 'article')]
    public function article(Articles $article): Response
    {
        return $this->render('articles/article.html.twig', compact('article'));
    }
}

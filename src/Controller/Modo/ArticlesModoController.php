<?php

namespace App\Controller\Modo;

use App\Entity\Articles;
use App\Form\Modo\ModoArticlesFormType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modo/articles', name: 'modo_articles_')]
class ArticlesModoController extends AbstractController
{
    // Affichage de la page index des articles pour un modérateur
    #[Route('/', name: 'index', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('modo/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    // Affichage du switch de visibilité des articles
    #[Route('/visibility', name: 'visibility', methods: ['GET', 'POST'])]
    public function articlesVisibility(ArticlesRepository $articlesRepository, Articles $articles): Response
    {
        $articles->setIsVisible($articlesRepository->isIsVisible() ? true : false);
        $articlesRepository->save($articles, true);

        return $this->render('modo/articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Affichage de la page d'édition d'un article par un modérateur
    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function modoArticleEdit(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        // Création du formulaire
        $form = $this->createForm(ModoArticlesFormType::class, $article);
        // Traitement de la requête du formulaire
        $form->handleRequest($request);

        //Vérification que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $article->setIsVisible($article->isIsVisible() ? true : false);
            $articlesRepository->save($article, true);

            $this->addFlash('info', 'L\'article a bien été modifié');

            return $this->redirectToRoute('modo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modo/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\Admin\AdminArticlesFormType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/articles', name: 'admin_articles_')]
class ArticlesAdminController extends AbstractController
{
    // Affichage de la page index des articles pour un administrateur
    #[Route('/', name: 'index', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    // Affichage du switch de visibilité des articles
    #[Route('/visibility', name: 'visibility', methods: ['GET', 'POST'])]
    public function articlesVisibility(ArticlesRepository $articlesRepository, Articles $article): Response
    {
        $article->setIsVisible(($article->isIsVisible() ? true : false));
        $articlesRepository->save($article, true);

        return $this->render('admin/articles/index.html.twig', [
            'articles' => $article,
        ]);
    }

    // Affichage de la page d'édition d'un article par un administrateur
    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function adminArticleEdit(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        $form = $this->createForm(AdminArticlesFormType::class, $article);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setIsVisible(($article->isIsVisible() ? true : false));
            $article->setUpdatedAt(new \DateTimeImmutable());
            $articlesRepository->save($article, true);

            $this->addFlash('info', 'L\'article a bien été modifié');

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

}
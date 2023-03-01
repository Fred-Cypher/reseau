<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesFormType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/articles', name:'articles_')]
class ArticlesController extends AbstractController
{
    // Affichage de la page d'index avec la liste des articles visibles par l'utilisateur
    #[Route('/', name: 'app_articles', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository, Request $request): Response
    {
        // Récupération du numéro de la page dans l'url
        $page = $request->query->getInt('page', 1);

        // Récupération des articles, définition du nombre d'articles par page
        $articles = $articlesRepository->articlesPaginated($page, 10);

        return $this->render('articles/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/newarticle', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function newArticle(Request $request, ArticlesRepository $articlesRepository, SluggerInterface $slug): Response
    {
        // Création nouvel article
        $article = new Articles();
        // Création formulaire
        $form = $this->createForm(ArticlesFormType::class, $article);
        // Traitement de la requête du formulaire
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur connecté et génération du slug
            $article->setUser($this->getUser());
            $article->getSlug($slug);
            $articlesRepository->save($article, true);

            $this->addFlash('info', 'Votre article a bien été enregistré, vous pouvez dès à présent le retrouver dans la liste d\'articles.');

            // Redirection
            return $this->redirectToRoute('articles_app_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'formNew' => $form->createView(),
        ]);
    }

    // Affichage d'un article grâce à son slug
    # requirements: ['title', 'title'], defaults: ['title', ''],
    #[Route('/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('articles/article.html.twig', [
            'article' => $article,
        ]);
    }

    // Affichage de la page d'édition d'un article
    #[Route('/{slug}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        // Création du formulaire
        $form = $this->createForm(ArticlesFormType::class, $article);
        // Traitement de la requête du formulaire
        $form->handleRequest($request);
        
        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($this->getUser()); 
            $articlesRepository->save($article, true);

            $this->addFlash('info', 'Votre article a bien été modifié.');

            return $this->redirectToRoute('articles_app_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    // Suppression d'un article
    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articlesRepository->remove($article, true);
        }

        return $this->redirectToRoute('articles_app_articles', [], Response::HTTP_SEE_OTHER);
    }
}

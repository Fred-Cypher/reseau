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
    #[Route('/', name: 'app_articles', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/articles.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    #[Route('/newarticle', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function newArticle(Request $request, ArticlesRepository $articlesRepository, SluggerInterface $slug): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($this->getUser());
            $article->getSlug($slug);
            $articlesRepository->save($article, true);

            $this->addFlash('info', 'Votre article a bien été enregistré, vous pouvez dès à présent le retrouver dans la liste d\'articles.');

            return $this->redirectToRoute('articles_app_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'formNew' => $form->createView(),
        ]);
    }

    # requirements: ['title', 'title'], defaults: ['title', ''],
    #[Route('/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('articles/article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        $form = $this->createForm(ArticlesFormType::class, $article);
        $form->handleRequest($request);

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

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articlesRepository->remove($article, true);
        }

        return $this->redirectToRoute('articles_app_articles', [], Response::HTTP_SEE_OTHER);
    }
}

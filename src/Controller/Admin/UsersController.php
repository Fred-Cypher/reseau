<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\Images;
use App\Entity\Users;
use App\Form\Admin\AdminArticlesFormType;
use App\Form\Admin\AdminBlogFormType;
use App\Form\Admin\AdminUsersFormType;
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
    // Affichage de la page d'administration
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    } 

    // Affichage de le liste des utilisateurs
    #[Route('/users', name: 'users_index', methods: ['GET'])]
    public function users(UsersRepository $usersRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    // Affichage de la modification des utilisateurs par un administrateur
    #[Route('/{id}/edit', name: 'users_edit', methods: ['GET', 'POST'])]
    public function adminUserEdit(Request $request, Users $users, UsersRepository $usersRepository): Response
    {   
        $roles = $this->getParameter('security.role_hierarchy.roles');
        $form = $this->createForm(AdminUsersFormType::class, $users);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            $usersRepository->save($users, true);

            $this->addFlash('info', 'L\'utilisateur a bien été modifié');

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/edit.html.twig', [
            'users' => $users,
            'roles' => $roles,
            'form' => $form->createView(),
        ]);
    }

    // Affichage de la page index du blog images pour les administrateurs
    #[Route('/blog', name: 'blog_index', methods: ['GET'])]
    public function blogImage(ImagesRepository $imagesRepository, Request $request): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    // Affichage du switch de visibilité des images
    #[Route('/blog_visibility', name: 'blog_visibility', methods: ['GET'])]
    public function blogVisibility(ImagesRepository $imagesRepository): Response
    {
        return $this->render('admin/blog/index.html.twig',[
            'images' => $imagesRepository->findAll(),
        ]);
    }

    // Affichage de l'édition d'une page du blog images
    #[Route('/{slug}/blog/edit', name: 'blog_images_edit', methods: ['GET', 'POST'])]
    public function adminBlogEdit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $form = $this->createForm(AdminBlogFormType::class, $image);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){
            $image->setIsVisible(($image->isIsVisible() ? true : false));
            $imagesRepository->save($image, true);

            $this->addFlash('info', 'L\'image a bien été modifiée');

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/blog/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    // Affichage de la page index des articles pour un administrateur
    #[Route('/articles', name: 'articles_index', methods: ['GET'])]
    public function articles(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    // Affichage du switch de visibilité des articles
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

    // Affichage de la page d'édition d'un article par un administrateur
    #[Route('/{slug}/article/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function adminArticleEdit(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        $form = $this->createForm(AdminArticlesFormType::class, $article);
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setIsVisible(($article->isIsVisible()? true : false));
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
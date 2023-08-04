<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Affichage de la page d'accueil du forum
#[Route('/forum', name: 'forum_')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ImagesRepository $imagesRepository, ArticlesRepository $articlesRepository): Response
    {      
        $images =  $imagesRepository->findAll();
        $image = array_rand($images, 1);
        
        $articles = $articlesRepository->findAll();
        $article = array_rand($articles, 1);

        return $this->render('forum/index.html.twig',[
            'images' => $images[$image],
            'articles' => $articles[$article]
        ]);
    }

    #[Route('/rules', name: 'rules', methods: ['GET'])]
    public function rules(): Response
    {
        return $this->render('forum/rules.html.twig', [
            'controller_name' => 'Réglement du site',
        ]);
    }
}
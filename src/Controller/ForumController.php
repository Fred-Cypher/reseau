<?php

namespace App\Controller;

use App\Entity\Images;
use App\Repository\ArticlesRepository;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Affichage de la page d'accueil du forum
#[Route('/forum', name: 'forum_')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ImagesRepository $images, ArticlesRepository $articles, EntityManagerInterface $manager): Response
    {        
        

        return $this->render('forum/index.html.twig',[
            'images' => $images,
            'articles' => $articles
        ]);
    }
}

/**
 * 
 * An exception has been thrown during the rendering of a template ("Undefined method "image_url". The method name must start with either findBy, findOneBy or countBy!").
 * 
 * Dans twig 
 * 
 */
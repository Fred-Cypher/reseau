<?php

namespace App\Controller\Modo;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modo', name: 'modo_')]
class MainModoController extends AbstractController
{
    // Affichage de la page de modération
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('modo/index.html.twig');
    }
};
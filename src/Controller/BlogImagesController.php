<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\BlogImagesFormType;
use App\Form\BlogModifFormType;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/blog/images', name: 'images_')]
class BlogImagesController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // Affichage de la page d'index avec la liste des images, visibilité différentes suivant l'utilisateur
    #[Route('/', name: 'app_blog_images_index', methods: ['GET'])]
    public function index(ImagesRepository $imagesRepository, Request $request): Response
    {
        //$role = $this->getUser()->getRoles();
        $user = $this->getUser();

        //## Page d'affichage administrateur
        if($this->security->isGranted('ROLE_MODO')){
            // Récupération du numéro de la page dans l'url
            $page = $request->query->getInt('page', 1);
            // Récupération des images, définition du nombre d'images par page
            $images = $imagesRepository->imagesPaginatedAll($page, 6);
        } elseif($user) {
        //## Page d'affichage de l'utilisateur connecté
            // Récupération du numéro de la page dans l'url
            $page = $request->query->getInt('page', 1);
            // Récupération des images, définition du nombre d'images par page
            $images = $imagesRepository->imagesPaginatedUser($page, 6);
        } else {
        //## Page d'affichage des autres utilisateurs
            // Récupération du numéro de la page dans l'url
            $page = $request->query->getInt('page', 1);
            // Récupération des images, définition du nombre d'images par page
            $images = $imagesRepository->imagesPaginated($page, 6);
        }

        return $this->render('blog_images/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/new', name: 'app_blog_images_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImagesRepository $imagesRepository, SluggerInterface $slug): Response
    {
        // Création d'une nouvelle image
        $image = new Images();
        // Création du formulaire
        $form = $this->createForm(BlogImagesFormType::class, $image);
        // Traitement de la requête du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur connecté et génération du slug
            $image->setUser($this->getUser());
            $image->getSlug($slug);

            // Récupération de l'image
            $picture = $form->get('image_url')->getData();
            // Création du nom de l'image
            $file = md5(uniqid()).'.'.$picture->guessExtension();
            // Enregistrement de l'image dans le dossier prédéfini
            $picture->move(
                $this->getParameter('images_directory'),
                $file
            );

            // Récupération du nom de l'image et enregistrement dans la BDD
            $image->setImageUrl($file);

            $imagesRepository->save($image, true);

            $this->addFlash('info', 'Votre image a bien été enregistrée, vous pouvez dès à présent la retrouver dans la liste d\'images.');

            return $this->redirectToRoute('images_app_blog_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog_images/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    // Affichage d'une page blog image
    #[Route('/{slug}', name: 'app_blog_images_show', methods: ['GET'])]
    public function show(Images $image): Response
    {
        return $this->render('blog_images/show.html.twig', [
            'image' => $image,
        ]);
    }

    // Affichage de la page de modification d'une page de blog image
    #[Route('/{slug}/edit', name: 'app_blog_images_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        // Création du formulaire
        $form = $this->createForm(BlogModifFormType::class, $image);
        // Traitement de la requête du formulaire
        $form->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $image->setUser($this->getUser());
            $imagesRepository->save($image, true);

            $this->addFlash('info', 'Votre image a bien été modifiée.');

            return $this->redirectToRoute('images_app_blog_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog_images/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    // Suppression d'une page du blog image
    #[Route('/delete/{id}', name: 'app_blog_images_delete', methods: ['POST'])]
    public function delete(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $imagesRepository->remove($image, true);
        }

        return $this->redirectToRoute('images_app_blog_images_index', [], Response::HTTP_SEE_OTHER);
    }
}


/**
*  $path = 'asset(assets/media/cache/miniature/assets/uploads)';
*  $miniature = $path . $image->getImageUrl();
*  if(file_exists($miniature)){
*     unlink($miniature);
*  }
 */
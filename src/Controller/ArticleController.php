<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Articlimages;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Flasher\Prime\FlasherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Flasher\Notyf\Prime\NotyfFactory;


/**
 * @Route("/")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/admin-dashboard/article/", name="article_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        $donnees = $this->getDoctrine()->getRepository(Article::class)->findBy([],['CreatedAt' => 'desc']);
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            3  //nbre page
        );



        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin-dashboard/article/new", name="article_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,NotyfFactory $flasher): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //recuperation des images

            $images = $form->get('images')->getData();

            foreach($images as $image){
                //un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Articlimages();
                $img->setName($fichier);
                $article->addImage($img);
            }

            //fin pour la recuperation
            //$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $flasher->addSuccess('Data has been saved successfully!');
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/article/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/admin-dashboard/article/{id}/edit", name="article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager,NotyfFactory $flasher): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Articlimages();
                $img->setName($fichier);
                $article->addImage($img);
            }

            //fin pour la recuperation
            $entityManager->flush();
            $flasher->addSuccess('Data has been updated successfully!');
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/article/{id}/delete", name="article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager,NotyfFactory $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        $flasher->AddWarning('Article is deleted !');
        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("admin-dashboard/image/{id}/delete", name="delete_image", methods={"DELETE"})
     */
    public function deleteImage(Articlimages $image, Request $request,NotyfFactory $flasher)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            $flasher->addWarning('Image is deleted !');
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalid'], 400);
        }
    }


    /**
     * @Route("/article/", name="article_index_front", methods={"GET"})
     */
    public function index_front(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/indexfront.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    /**
     * @Route("/article/{id}", name="article_show_client", methods={"GET"})
     */
    public function show_client(Article $article, EntityManagerInterface $entityManager): Response
    {
        $article->setViews($article->getViews()+1);
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->render('article/show_front.html.twig', [
            'article' => $article,
        ]);
    }
}

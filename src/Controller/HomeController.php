<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Excursioncategorie;
use App\Entity\Hotel;
use App\Entity\Maisonshotes;
use App\Repository\ExcursionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ExcursionRepository $excursionRepository): Response
    {
        $excursions = $excursionRepository->findAll();
        $hotels = $this->getDoctrine()->getRepository(Hotel::class)->findAll();
        $maisons = $this->getDoctrine()->getRepository(Maisonshotes::class)->findAll();
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('home/index.html.twig', [
            'excursions' => $excursions,
            'hotels'=>$hotels,
            'maisons'=>$maisons,
            'articles'=>$articles
        ]);

    }

    /**
     * @Route("/admin-dashboard", name="admin-dashboard")
     */
    public function admin_index(): Response
    {
        return $this->render('home/admin_index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }




}

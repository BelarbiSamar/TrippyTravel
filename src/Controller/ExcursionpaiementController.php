<?php

namespace App\Controller;

use App\Entity\Excursionreservation;
use App\Repository\ExcursionreservationRepository;
use Flasher\Prime\FlasherInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session;

class ExcursionpaiementController extends AbstractController
{
    /**
     * @Route("/excursionpaiement", name="app_excursionpaiement")
     */
    public function index(): Response
    {

        return $this->render('excursionpaiement/index.html.twig', [
            'controller_name' => 'ExcursionpaiementController',
        ]);
    }


}

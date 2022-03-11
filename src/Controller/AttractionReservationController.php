<?php

namespace App\Controller;

use App\Entity\AttractionReservation;
use App\Form\AttractionReservationType;
use App\Repository\AttractionReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/attraction/reservation")
 */
class AttractionReservationController extends AbstractController
{
    /**
     * @Route("/", name="app_attraction_reservation_index", methods={"GET"})
     */
    public function index(AttractionReservationRepository $attractionReservationRepository): Response
    {
        return $this->render('attraction_reservation/index.html.twig', [
            'attraction_reservations' => $attractionReservationRepository->findAll(),
        ]);
    }
    

    /**
     * @Route("/new", name="app_attraction_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AttractionReservationRepository $attractionReservationRepository): Response
    {
        $attractionReservation = new AttractionReservation();
        $form = $this->createForm(AttractionReservationType::class, $attractionReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attractionReservationRepository->add($attractionReservation);
            return $this->redirectToRoute('app_attraction_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attraction_reservation/new.html.twig', [
            'attraction_reservation' => $attractionReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_attraction_reservation_show", methods={"GET"})
     */
    public function show(AttractionReservation $attractionReservation): Response
    {
        return $this->render('attraction_reservation/show.html.twig', [
            'attraction_reservation' => $attractionReservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_attraction_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AttractionReservation $attractionReservation, AttractionReservationRepository $attractionReservationRepository): Response
    {
        $form = $this->createForm(AttractionReservationType::class, $attractionReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attractionReservationRepository->add($attractionReservation);
            return $this->redirectToRoute('app_attraction_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attraction_reservation/edit.html.twig', [
            'attraction_reservation' => $attractionReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_attraction_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, AttractionReservation $attractionReservation, AttractionReservationRepository $attractionReservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attractionReservation->getId(), $request->request->get('_token'))) {
            $attractionReservationRepository->remove($attractionReservation);
        }

        return $this->redirectToRoute('app_attraction_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}

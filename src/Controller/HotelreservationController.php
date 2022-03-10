<?php

namespace App\Controller;

use App\Entity\Hotelreservation;
use App\Form\HotelreservationType;
use App\Repository\HotelreservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hotelreservation")
 */
class HotelreservationController extends AbstractController
{
    /**
     * @Route("/", name="hotelreservation_index", methods={"GET"})
     */
    public function index(HotelreservationRepository $hotelreservationRepository): Response
    {
        return $this->render('hotelreservation/index.html.twig', [
            'hotelreservations' => $hotelreservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hotelreservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hotelreservation = new Hotelreservation();
        $form = $this->createForm(HotelreservationType::class, $hotelreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hotelreservation);
            $entityManager->flush();

            return $this->redirectToRoute('hotelreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotelreservation/new.html.twig', [
            'hotelreservation' => $hotelreservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hotelreservation_show", methods={"GET"})
     */
    public function show(Hotelreservation $hotelreservation): Response
    {
        return $this->render('hotelreservation/show.html.twig', [
            'hotelreservation' => $hotelreservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hotelreservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Hotelreservation $hotelreservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HotelreservationType::class, $hotelreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('hotelreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotelreservation/edit.html.twig', [
            'hotelreservation' => $hotelreservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hotelreservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Hotelreservation $hotelreservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotelreservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($hotelreservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hotelreservation_index', [], Response::HTTP_SEE_OTHER);
    }
}

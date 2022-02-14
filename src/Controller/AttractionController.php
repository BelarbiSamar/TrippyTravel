<?php

namespace App\Controller;

use App\Entity\Attraction;
use App\Form\AttractionType;
use App\Repository\AttractionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/attraction")
     */
class AttractionController extends AbstractController
{
    /**
     * @Route("/", name="attraction_index", methods={"GET"})
     */
    public function index(AttractionRepository $attractionRepository): Response
    {
        return $this->render('attraction/index.html.twig', [
            'attractions' => $attractionRepository->findAll(),
        ]);
        
    }
        /**
     * @Route("/admin-dashboard/attraction", name="admin-dashboard/attraction")
     */
    public function admin_index(AttractionRepository $attractionRepository): Response
    {
        return $this->render('attraction/admin_index.html.twig', [
            'attractions' => $attractionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="attraction_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attraction = new Attraction();
        $form = $this->createForm(AttractionType::class, $attraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attraction);
            $entityManager->flush();

            return $this->redirectToRoute('attraction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attraction/new.html.twig', [
            'attraction' => $attraction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="attraction_show", methods={"GET"})
     */
    public function show(Attraction $attraction): Response
    {
        return $this->render('attraction/show.html.twig', [
            'attraction' => $attraction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="attraction_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Attraction $attraction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttractionType::class, $attraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('attraction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attraction/edit.html.twig', [
            'attraction' => $attraction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="attraction_delete", methods={"POST"})
     */
    public function delete(Request $request, Attraction $attraction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attraction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($attraction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('attraction_index', [], Response::HTTP_SEE_OTHER);
    }
}

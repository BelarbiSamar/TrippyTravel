<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ReclamationController extends AbstractController
{
    /**
     * @Route("/admin-dashboard/reclamation", name="reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/reclamation/{status}", name="reclamation_index_status", methods={"GET"})
     */
    public function indexByStatus(ReclamationRepository $reclamationRepository, String $status): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findBy(['status' => $status]),
        ]);
    }

    /**
     * @Route("/trippy/reclamation", name="reclamation_index_client", methods={"GET"})
     */
    public function indexClient(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/indexClient.html.twig', [
            'reclamations' => $reclamationRepository->findBy(['client' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/trippy/reclamation/new", name="reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setClient($this->getUser());
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/reclamation/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {   
        if ($reclamation->getSeen() == false) {
            $reclamation->setStatus("seen");
        }
        $reclamation->setSeen("true");
        $entityManager->persist($reclamation);
        $entityManager->flush();
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/admin-dashboard/reclamation/take/{id}", name="reclamation_take", methods={"GET", "POST"})
     */
    public function take(Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $reclamation->setStatus("in progress");
        $entityManager->persist($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin-dashboard/reclamation/done/{id}", name="reclamation_done", methods={"GET", "POST"})
     */
    public function done(Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $reclamation->setStatus("done");
        $entityManager->persist($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/reclamation/{id}/edit", name="reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/reclamation/delete/{id}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Excursioncomment;
use App\Form\ExcursioncommentType;
use App\Repository\ExcursioncommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/excursioncomment")
 */
class ExcursioncommentController extends AbstractController
{
    /**
     * @Route("/", name="app_excursioncomment_index", methods={"GET"})
     */
    public function index(ExcursioncommentRepository $excursioncommentRepository): Response
    {
        return $this->render('excursioncomment/index.html.twig', [
            'excursioncomments' => $excursioncommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_excursioncomment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ExcursioncommentRepository $excursioncommentRepository): Response
    {
        $excursioncomment = new Excursioncomment();
        $form = $this->createForm(ExcursioncommentType::class, $excursioncomment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $excursioncommentRepository->add($excursioncomment);
            return $this->redirectToRoute('app_excursioncomment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursioncomment/new.html.twig', [
            'excursioncomment' => $excursioncomment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_excursioncomment_show", methods={"GET"})
     */
    public function show(Excursioncomment $excursioncomment): Response
    {
        return $this->render('excursioncomment/show.html.twig', [
            'excursioncomment' => $excursioncomment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_excursioncomment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Excursioncomment $excursioncomment, ExcursioncommentRepository $excursioncommentRepository): Response
    {
        $form = $this->createForm(ExcursioncommentType::class, $excursioncomment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $excursioncommentRepository->add($excursioncomment);
            return $this->redirectToRoute('app_excursioncomment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursioncomment/edit.html.twig', [
            'excursioncomment' => $excursioncomment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_excursioncomment_delete", methods={"POST"})
     */
    public function delete(Request $request, Excursioncomment $excursioncomment, ExcursioncommentRepository $excursioncommentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$excursioncomment->getId(), $request->request->get('_token'))) {
            $excursioncommentRepository->remove($excursioncomment);
        }

        return $this->redirectToRoute('app_excursioncomment_index', [], Response::HTTP_SEE_OTHER);
    }
}

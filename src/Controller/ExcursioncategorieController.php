<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\Excursioncategorie;
use App\Form\ExcursioncategorieType;
use App\Repository\ExcursioncategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Prime\FlasherInterface;
use Flasher\SweetAlert\Prime\SweetAlertFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcursioncategorieController extends AbstractController
{
    /**
     * @Route("admin-dashboard//excursioncategorie/", name="excursioncategorie_index", methods={"GET"})
     */
    public function index(ExcursioncategorieRepository $excursioncategorieRepository): Response
    {
        return $this->render('excursioncategorie/index.html.twig', [
            'excursioncategories' => $excursioncategorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("admin-dashboard//excursioncategorie/new", name="excursioncategorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlasherInterface $flasher): Response
    {
        $excursioncategorie = new Excursioncategorie();
        $form = $this->createForm(ExcursioncategorieType::class, $excursioncategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($excursioncategorie);
            $entityManager->flush();
            $flasher->addSuccess('Ajouté avec succés!');
            return $this->redirectToRoute('excursioncategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursioncategorie/new.html.twig', [
            'excursioncategorie' => $excursioncategorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin-dashboard//excursioncategorie/{id}", name="excursioncategorie_show", methods={"GET"})
     */
    public function show(Excursioncategorie $excursioncategorie): Response
    {
        return $this->render('excursioncategorie/show.html.twig', [
            'excursioncategorie' => $excursioncategorie,
        ]);
    }

    /**
     * @Route("admin-dashboard//excursioncategorie/{id}/edit", name="excursioncategorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Excursioncategorie $excursioncategorie, EntityManagerInterface $entityManager,FlasherInterface $flasher): Response
    {
        $form = $this->createForm(ExcursioncategorieType::class, $excursioncategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flasher->addSuccess('Modifié avec succés!');
            return $this->redirectToRoute('excursioncategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursioncategorie/edit.html.twig', [
            'excursioncategorie' => $excursioncategorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin-dashboard//excursioncategorie/{id}", name="excursioncategorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Excursioncategorie $excursioncategorie, EntityManagerInterface $entityManager,SweetAlertFactory $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$excursioncategorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($excursioncategorie);
            $entityManager->flush();
            $flasher->addSuccess('Supprimé avec succès');
        }

        return $this->redirectToRoute('excursioncategorie_index', [], Response::HTTP_SEE_OTHER);
    }
    public function menuCategories($max = 3)
    {
        $excursions = $this->getDoctrine()->getRepository(Excursion::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Excursioncategorie::class)->findAll();

        return $this->render(
            'excursioncategorie/menu_list.html.twig',
            [
                'excursions' => $excursions,
                'categories' => $categories
            ]
        );
    }
}

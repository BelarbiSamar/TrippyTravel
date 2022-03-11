<?php

namespace App\Controller;

use App\Entity\CategorieAttraction;
use App\Form\CategorieAttractionType;
use App\Repository\CategorieAttractionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class CategorieAttractionController extends AbstractController
{
    /**
     * @Route("/categorie_attraction", name="categorie_attraction_index", methods={"GET"})
     */
    public function index(CategorieAttractionRepository $categorieAttractionRepository): Response
    {
        return $this->render('categorie_attraction/index.html.twig', [
            'categorie_attractions' => $categorieAttractionRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/admin-dashboard/categorie_attraction", name="admin-dashboard/categorie_attraction")
     */
    public function admin_index(CategorieAttractionRepository $categorieAttractionRepository): Response
    {
        return $this->render('categorie_attraction/admin_index.html.twig', [
            'categorie_attractions' => $categorieAttractionRepository->findAll(),
        ]);
    }

        /**
   * @Route("/search", name="ajax_search")
   */
public function searchAction(Request $request)
{
    $em = $this->getDoctrine()->getManager();
    $libelle = $request->get('q');
    $categorieAttraction =$em->getRepository(CategorieAttraction::class)->findEntitiesByLibelle($libelle);
    if(!$categorieAttraction ) {
        $result['categorieAttraction ']['error'] = "Categorie introuvable !";
    } else {
        $result['categorieAttraction '] = $this->getRealEntities($categorieAttraction );
    }
    return new Response(json_encode($result));
}

public function getRealEntities($categorieAttraction ){
    foreach ($categorieAttraction  as $categorieAttraction ){
        $realEntities[$categorieAttraction ->getId()] = [$categorieAttraction->getLibelle(),$categorieAttraction->getContrainteAge()];
    }
    return $realEntities;
}

    /**
     * @Route("/admin-dashboard/categorie_attraction/new", name="categorie_attraction_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieAttraction = new CategorieAttraction();
        $form = $this->createForm(CategorieAttractionType::class, $categorieAttraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieAttraction);
            $entityManager->flush();

            return $this->redirectToRoute('admin-dashboard/categorie_attraction', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_attraction/new.html.twig', [
            'categorie_attraction' => $categorieAttraction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/categorie_attraction/{id}", name="categorie_attraction_show", methods={"GET"})
     */
    public function show(CategorieAttraction $categorieAttraction): Response
    {
        return $this->render('categorie_attraction/show.html.twig', [
            'categorie_attraction' => $categorieAttraction,
        ]);
    }

    /**
     * @Route("/admin-dashboard/categorie_attraction/{id}/edit", name="categorie_attraction_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategorieAttraction $categorieAttraction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieAttractionType::class, $categorieAttraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin-dashboard/categorie_attraction', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_attraction/edit.html.twig', [
            'categorie_attraction' => $categorieAttraction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/{id}", name="categorie_attraction_delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, CategorieAttraction $categorieAttraction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieAttraction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieAttraction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin-dashboard/categorie_attraction', [], Response::HTTP_SEE_OTHER);
    }
}

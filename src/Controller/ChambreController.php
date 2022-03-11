<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Hotelreservation;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


class ChambreController extends AbstractController
{
    /**
     * @Route("/hotel_single", name="hotel_single")
     */
    public function chambre_single(chambreRepository $chambreRepository): Response
    {
        return $this->render('hotel/hotel_single.html.twig', [
            
            'chambres' => $chambreRepository->findAll(),
            'controller_name' => 'chambreController',
        ]);
    }

    /**
     * @Route("/chambrefront/{id}", name="chambrefront_single" , methods={"GET","POST"})
     */
    public function chambre_one(EntityManagerInterface $entityManage,Request $request,Chambre $chambre): Response
    {
        $hoteleservation = new Hotelreservation();
        $form = $this->createFormBuilder(null)
            ->add('Reserver', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hoteleservation->setPrix($chambre->getPrix());
            $chambre->addHotelreservation($hoteleservation);
            $entityManage->flush();

        }
        return $this->render('chambre/chambre_desc.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    
    }

/**
     * @Route("/listp", name="chambre_listp", methods={"GET"})
     */
    public function listp(ChambreRepository $chambreRepository)
    {



        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
       $chambre= $chambreRepository->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('chambre/listp.html.twig', [
            'chambres' => $chambre,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    
       
    }



    /**
     * @Route("/admin-dashboard/chambre/", name="chambre_index", methods={"GET"})
     */
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/chambre/new", name="chambre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chambre);
            $entityManager->flush();

            return $this->redirectToRoute('chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/chambre/{id}", name="chambre_show", methods={"GET"})
     */
    public function show(Chambre $chambre): Response
    {
        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    /**
     * @Route("/admin-dashboard/chambre/{id}/edit", name="chambre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/chambre/{id}", name="chambre_delete", methods={"POST"})
     */
    public function delete(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chambre_index', [], Response::HTTP_SEE_OTHER);
    }
}

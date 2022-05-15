<?php

namespace App\Controller;

use App\Entity\MaisonReservation;
use App\Entity\Maisonshotes;

use App\Form\MaisonshotesType;
use App\Repository\MaisonshotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use App\Notifications\NouveauMaisonNotification;


/**
 * @Route("/ ")
 */
class MaisonshotesController extends AbstractController
{

    /**
     * MaisonshotesController constructor.
     * @param NouveauMaisonNotification $notify_creation
     */
    public function __construct(NouveauMaisonNotification $notify_creation)
    {
        $this->notify_creation = $notify_creation;

    }



    /**
     * @Route("/maisonshotes", name="maisonshotes_index", methods={"GET","POST"})
     */
    public function maisonshotes (Request $request, PaginatorInterface $paginator): Response
    {
        $rep=$this->getDoctrine()->getRepository(maisonshotes::class);
        $maisonshote =$rep-> findAll();
        $maisonshote = $paginator->paginate(
            $maisonshote, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

        return $this->render('maisonshotes/indexfront.html.twig', [
            'maisonshotes' => $maisonshote,


        ]);
    }


    /**
     * @Route("/admin-dashboard/maisonshote", name="admin-dashboard/maisonshote")
     */
    public function admin_index(MaisonshotesRepository $maisonshotesRepository): Response

    {
        return $this->render('maisonshotes/admin_index.html.twig', [
            'maisonshotes' => $maisonshotesRepository->findAll(),
        ]);
        //return $this->render('maisonshotes/admin_index.html.twig', [
          //  'controller_name' => 'MaisonshotesController',
      //  ]);
    }
    /**
     * @Route("/ajax_search", name="ajax_search")
     */
    public function ajax_search(Request $request,MaisonshotesRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $libelle = $request->get('q');
        if($request->get('q')){
            $maisonshote =$em->getRepository(maisonshotes::class)->findEntitiesByLibelle($libelle);
            if(!$maisonshote ) {
                $result['maisonshote ']['error'] = "maison introuvable !";
            } else {
                $result['maisonshote '] = $this->getRealEntities($maisonshote );
            }
        }else{
            $result['maisonshote '] = $this->getRealEntities($repository->findAll());
        }
        return new Response(json_encode($result));
    }
    /**
     * @Route("/ajax_search_searchmaisonhote", name="ajax_search_searchmaisonhote")
     */
    public function ajax_search_searchmaisonhote(Request $request,MaisonshotesRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $libelle = $request->get('q');
        if($request->get('q')){
            $maisonshote =$em->getRepository(maisonshotes::class)->findEntitiesByLibelle($libelle);
            if(!$maisonshote ) {
                $result['maisonshote ']['error'] = "maison introuvable !";
            } else {
                $result['maisonshote '] = $this->getRealEntities($maisonshote );
            }
        }else{
            $result['maisonshote'] = $this->getRealEntities($repository->findAll());
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($maisonshotes){
        foreach ($maisonshotes  as $maisonshote ){
            $img="";
            if ($maisonshote->getMaisonhotesimages()[0]){
                $img="/uploads/images/maisonhotes/".$maisonshote->getMaisonhotesimages()[0]->getImageName();
            }
            $realEntities[$maisonshote->getId()] = [$maisonshote->getLibelle(),$maisonshote->getCapacite(),$maisonshote->getLocalisation(), $maisonshote->getProprietaire(), $maisonshote->getPrix(), $img];
        }
        return $realEntities;
    }
    /**
     * @Route("/new", name="maisonshotes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $maisonshote = new Maisonshotes();
        $form = $this->createForm(MaisonshotesType::class, $maisonshote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($maisonshote);
            $entityManager->flush();
            $this->notify_creation->notify();

            $this->addFlash(
                'info' ,
                'Ajoutée avec succées'
            );

            return $this->redirectToRoute('admin-dashboard/maisonshote', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maisonshotes/new.html.twig', [
            'maisonshote' => $maisonshote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="maisonshotes_show", methods={"GET"})
     */
    public function show(Maisonshotes $maisonshote): Response
    {
        return $this->render('maisonshotes/show.html.twig', [
            'maisonshote' => $maisonshote,
        ]);
    }

    /**
     * @Route("/maisonshotes_show_front/{id}", name="maisonshotes_show_front", methods={"GET","POST"})
     */
    public function maisonshotes_show_front(Maisonshotes $maisonshote,EntityManagerInterface $entityManage,Request $request): Response
    {

        $hoteleservation = new MaisonReservation();
        $form = $this->createFormBuilder(null)
            ->add('Reserver', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hoteleservation->setPrix($maisonshote->getPrix());
            $hoteleservation->setStatut("valide");
            $maisonshote->addMaisonReservation($hoteleservation);
            $entityManage->flush();
            return $this->redirectToRoute('maisonshotes_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('maisonshotes/show_front.html.twig', [
            'maisonshote' => $maisonshote,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/edit/{id}", name="maisonshotes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Maisonshotes $maisonshote, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaisonshotesType::class, $maisonshote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin-dashboard/maisonshote', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maisonshotes/edit.html.twig', [
            'maisonshote' => $maisonshote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="maisonshotes_delete", methods={"POST"})
     */
    public function delete(Request $request, Maisonshotes $maisonshote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maisonshote->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maisonshote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin-dashboard/maisonshote', [], Response::HTTP_SEE_OTHER);
    }






}



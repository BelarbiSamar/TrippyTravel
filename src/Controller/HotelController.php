<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

use App\Notifications\NouveauhotelNotification;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};

class HotelController extends AbstractController
{


    
     
      /**
     * @Route("/hotel", name="hotel", methods={"GET","POST"})
     */
    public function hotel(Request $request, PaginatorInterface $paginator,HotelRepository $hotelRepository): Response
    { 
        $products = $hotelRepository->findAll();
        $form = $this->createFormBuilder(null)
            ->add('query', TextType::class)
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $re = $request->get('form');
            $products = $hotelRepository->findBy(
                ['libelle' => $re['query']]
            );
        }
        

      

        $rep=$this->getDoctrine()->getRepository(Hotel::class);
        
        $hotel =$rep-> findAll();
        $hotel = $paginator->paginate(
            $hotel, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
      
        return $this->render('hotel/hotel.html.twig', [
            'hotels' => $hotel,
            'form' => $form->createView()
           
           
        ]);
    }

/**
 * @var NouveauhotelNotification
 */
private $notify_creation;

/**
 * HotelController constructor.
 * @param NouveauhotelNotification $notify_creation
 */
public function __construct(NouveauhotelNotification $notify_creation)
{
    $this->notify_creation = $notify_creation;
    
}




    /**
     * @Route("/hotel_single", name="hotel_single")
     */
    public function hotel_single(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/hotel_single.html.twig', [
            
            'hotels' => $hotelRepository->findAll(),
            'controller_name' => 'HotelController',
        ]);
    }

    /**
     * @Route("/admin-dashboard/dashhotel", name="hotel_index", methods={"GET","POST"})
     */
    public function index(HotelRepository $hotelRepository,Request $request): Response
    {
        $products = $hotelRepository->findAll();
        $form = $this->createFormBuilder(null)
            ->add('query', TextType::class)
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $re = $request->get('form');
            $products = $hotelRepository->findBy(
                ['libelle' => $re['query']]
            );
        }
        return $this->render('hotel/index.html.twig', [
            'hotels' => $products,
            'form' => $form->createView()
            ]);

    }


/**
     * @Route("/listp", name="hotel_listp", methods={"GET"})
     */
    public function listp(HotelRepository $hotelRepository)
    {



        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
       $hotel= $hotelRepository->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('hotel/listp.html.twig', [
            'hotels' => $hotel,
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
     * @Route("/listpf", name="hotel_listpf", methods={"GET"})
     */
    public function listpf(HotelRepository $hotelRepository)
    {



        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
       $hotel= $hotelRepository->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('hotel/listpf.html.twig', [
            'hotels' => $hotel,
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
     * @Route("/admin-dashboard/hotel/new", name="hotel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager ): Response
    {
        
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hotel);
            $entityManager->flush();
            $this->notify_creation->notify();


            return $this->redirectToRoute('hotel_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/hotel/{id}", name="hotel_show_hotel" , methods={"GET"})
     */
    public function show_hotel(Request $request,Hotel $hotel): Response
    {
        $chambres = $hotel->getChambre();
        $arr = [];
        $arr_ch = [];
        foreach ($chambres as $key => $value) {
            $arr["id"] = $value->getId();
            $arr["typechambre"] = $value->getTypechambre();
            $arr["prix"] = $value->getPrix();
            $arr["description_chambre"] = $value->getDescriptionChambre();
            $arr_ch[] = $arr;
        }
        return $this->render('hotel/hotel_single.html.twig', [
            'hotel' => $hotel,
            'chambres'=>$arr_ch
        ]);
    }
/**
   * @Route("/search", name="ajax_search")
   */
  public function searchAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $libelle = $request->get('q');
      $hotel =$em->getRepository(hotel::class)->findEntitiesBylibelle($libelle);
      if(!$hotel ) {
          $result['hotel ']['error'] = "hotel introuvable 🙁 ";
      } else {
          $result['hotel '] = $this->getRealEntities($hotel );
      }

      return new Response(json_encode($result));
  }
  public function getRealEntities($hotel ){
      foreach ($hotel  as $hotel ){
          $img="";
          if ($hotel->getHotelimage()[0]){
              $img="/uploads/images/hotel/".$hotel->getHotelimage()[0]->getImage();
          }
          $realEntities[$hotel ->getId()] = [$hotel->getLibelle(),$hotel->getLocalisation(),$hotel->getNbetoile(), $hotel->getNbchdispo(), $hotel->getDescriptionHotel(),$img,$hotel ->getId()];
      }
      return $realEntities;
  }
    /**
     * @Route("/admin-dashboard/hotel/{id}", name="hotel_show", methods={"GET"})
     */
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    
   
    /**
     * @Route("/admin-dashboard/hotel/{id}/edit", name="hotel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-dashboard/hotel/{id}", name="hotel_delete", methods={"POST"})
     */
    public function delete(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($hotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hotel_index', [], Response::HTTP_SEE_OTHER);
    }




    /**
     * @Route("/ajax_searchhotel", name="ajax_searchhotel", methods={"Get"})
     */
    public function searchActionhotel(Request $request,HotelRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $libelle = $request->get('q');
        if($request->get('q')){
            $hotel =$em->getRepository(Hotel::class)->findEntitiesByLibelle($libelle);
            if(!$hotel ) {
                $result['hotel']['error'] = "Hotel introuvable !";
            } else {
                $result['hotel'] = $this->getRealEntities($hotel );
            }
        }else{
            $result['hotel'] = $this->getRealEntities($repository->findAll());
        }

        return new Response(json_encode($result));
    }





 


}

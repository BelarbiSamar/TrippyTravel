<?php

namespace App\Controller;


use App\Entity\Excursion;
use App\Entity\Excursioncategorie;
use App\Entity\Excursioncomment;
use App\Entity\Excursionimage;
use App\Entity\Excursionreservation;
use App\Form\ExcursioncommentType;
use App\Form\ExcursionimageType;
use App\Form\ExcursionType;
use App\Repository\ExcursioncategorieRepository;
use App\Repository\ExcursionRepository;
use App\Repository\ExcursionreservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Prime\FlasherInterface;
use Flasher\SweetAlert\Prime\SweetAlertFactory;
use Flasher\Toastr\Prime\ToastrFactory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Mailer\MailerInterface;



class ExcursionController extends AbstractController
{
    private $mailer;

    public function __construct( MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("admin-dashboard/excursion/", name="excursion_index", methods={"GET","POST"})
     */
    public function index(Request $request, ExcursionRepository $excursionRepository, ToastrFactory $flasher): Response
    {
        $excursions = $excursionRepository->findAll();
        return $this->render('excursion/index.html.twig', [
            'excursions' => $excursions,
        ]);
    }


    /**
     * @Route("admin-dashboard/excursion/new", name="excursion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $excursion = new Excursion();
        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productImages = $excursion->getExcursionimages();
            foreach ($productImages as $key => $productImage) {
                $productImage->setExcursion($excursion);
                $productImages->set($key, $productImage);
            }
            $entityManager->persist($excursion);
            $entityManager->flush();


            $flasher->addSuccess('Ajouté avec succés!');
            return $this->redirectToRoute('excursion_index', [], Response::HTTP_SEE_OTHER);
//            $flasher->addSuccess('Data has been saved successfully!');
        }

        return $this->render('excursion/new.html.twig', [
            'excursion' => $excursion,
            'form' => $form->createView(),

        ]);
    }

    /**

     * @Route("admin-dashboard/excursion/{id}", name="excursion_show", methods={"GET"})
     */
    public function show(Excursion $excursion): Response
    {
        $arr_img = [];
        $images = $excursion->getExcursionimages();
        foreach ($images as $item) {
            $arr_img[] = $item->getImageName();
        }
        return $this->render('excursion/show.html.twig', [
            'excursion' => $excursion,
            'categorie' => $excursion->getExcursioncategorie()->getLibelle(),
            'images' => $images,
        ]);
    }

    /**
     * @Route("admin-dashboard/excursion/{id}/edit", name="excursion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Excursion $excursion, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productImages = $excursion->getExcursionimages();
            foreach ($productImages as $key => $productImage) {
                $productImage->setExcursion($excursion);
                $productImages->set($key, $productImage);
            }
            $entityManager->flush();
            $flasher->addSuccess('Modifié avec succés!');
            return $this->redirectToRoute('excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursion/edit.html.twig', [
            'excursion' => $excursion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin-dashboard/excursion/{id}", name="excursion_delete", methods={"POST"})
     */
    public function delete(Request $request, Excursion $excursion, EntityManagerInterface $entityManager, SweetAlertFactory $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete' . $excursion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($excursion);
            $entityManager->flush();
            $flasher->addSuccess('Supprimé avec succès');
        }
        return $this->redirectToRoute('excursion_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("excursion/", name="excursion_index_front", methods={"GET"})
     */
    public function index_front(ExcursionRepository $excursionRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $all = $excursionRepository->findAll();
        $ville = [];
        foreach ($all as $one){
            $ville[] = $one->getVille();
        }
        $excursions = $paginator->paginate(
            $all,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );
        $categories = $this->getDoctrine()->getRepository(Excursioncategorie::class)->findAll();
        return $this->render('excursion/front_index.html.twig', [
            'excursions' => $excursions,
            'villes' => $ville,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("excursion/{id}", name="excursion_show_front", methods={"GET","POST"})
     */
    public function show_front(DompdfController $Dompdf, EntityManagerInterface $entityManager, Excursion $excursion, Request $request, FlasherInterface $flasher): Response
    {
        //comment
        $commentaires = $this->getDoctrine()->getRepository(Excursioncomment::class)->findBy([
            'excursion' => $excursion,
            'actif' => 1
        ],['created_at' => 'desc']);
        $commentaire = new Excursioncomment();
        $formcomment = $this->createForm(ExcursioncommentType::class, $commentaire);
        $formcomment->handleRequest($request);
        // Nous vérifions si le formulaire a été soumis et si les données sont valides
        if ($formcomment->isSubmitted() && $formcomment->isValid()) {
            // Hydrate notre commentaire avec l'article
            $commentaire->setExcursion($excursion);

            // Hydrate notre commentaire avec la date et l'heure courants
            $commentaire->setCreatedAt(new \DateTime('now'));

            $doctrine = $this->getDoctrine()->getManager();

            // On hydrate notre instance $commentaire
            $doctrine->persist($commentaire);

            // On écrit en base de données
            $doctrine->flush();
            $flasher->addSuccess('Commentaire ajouté avec succés!');
            // On redirige l'utilisateur
            return $this->redirectToRoute('excursion_show_front', ['id' => $excursion->getId()]);
        }
        //reservation
        $excursionreservation = new Excursionreservation();
        $form = $this->createFormBuilder(null)
            ->add('Reserver', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user_connected = $this->getUser();
            if ($user_connected){
                $excursionreservation->setPrix($excursion->getPrix());
                $excursionreservation->setUser($this->getUser());
                $excursion->addExcursionreservation($excursionreservation);
                $entityManager->flush();
                $session  = $this->get("session");
                $session->set("excursionreservation",$excursionreservation);
                $flasher->addSuccess('Réservé avec succés!');
                $lib_file = 'Reservation.pdf';
                $Dompdf->generate_store($excursionreservation,$lib_file);
                //send email
                try {
                    $email = new TemplatedEmail();
                    $email->subject("Notification réservation");
                    $email->from('amani.boussaa@esprit.tn');
                    $email->to($user_connected->getEmail());
                    $email->htmlTemplate('excursionreservation/confirmation_email.html.twig');
                    $email->context(['username' => $user_connected->getFirstname()." ".$user_connected->getLastname()]);
                    $email->attachFromPath($lib_file);
                    $this->mailer->send($email);
                    $flasher->addSuccess("Email envoyé, Verifier votre boite email svp");
                } catch (TransportExceptionInterface $e) {
                    $flasher->addError("Email non envoyé. ");
                }
                return $this->redirectToRoute('app_excursionpaiement', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
                //$flasher->addError("Vous devez être connecté");
            }
        }
        return $this->render('excursion/show_front.html.twig', [
            'excursion' => $excursion,
            'commentaires' => $commentaires,
            'form' => $form->createView(),
            'formcomment' => $formcomment->createView(),

        ]);
    }



    /**
     * @Route("/admin-dashboard/excursionsearch", name="ajax_search", methods={"POST"})
     */
    public function searchAction(Request $request, ExcursionRepository $excursionRepository)
//    public function searchAction()
    {
        $requestString = $request->get('q');
        if ($request){
            $excursions = $excursionRepository->findEntitiesByLibelle($requestString);
            if (!$excursions) {
                $result['excursions']['error'] = "Excursion Pas trouvé :( ";
            } else {
                $result['excursions'] = $this->getRealEntities($excursions,"");
            }
        }else{
            $result['excursions'] = $excursionRepository->findAll();
        }
        return new Response(json_encode($result));

        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(array('data' => 'this is a json response'));
        }

        return new Response('This is not ajax!', 400);
    }

    public function getRealEntities($excursions)
    {
        $token = "";
        foreach ($excursions as $excursion) {
            $img="";
            if ($excursion->getExcursionimages()[0]){
                $img="/uploads/images/excursion/".$excursion->getExcursionimages()[0]->getimageName();
            }
//            $token = $this->tokenManager->getToken('_csrf/delete'.$excursions->getId())->getValue();
            $realEntities[$excursion->getId()] = [$excursion->getId(), $excursion->getLibelle(),$excursion->getExcursioncategorie()->getLibelle(),$excursion->getPrix(),$token,$img,$excursion->getDuration(),$excursion->getVille()];
        }
        return $realEntities;
    }

    /**
     * @Route("/frontrechercheE", name="frontrechercheE", methods={"POST"})
     */
    public function frontrecherche(Request $request, ExcursionRepository $excursionRepository)
    {
        $requestString = $request->get('q');
        if ($request){
            $excursions = $excursionRepository->findEntitiesByLibelle($requestString);
            if (!$excursions) {
                $result['excursions']['error'] = "Excursion Pas trouvé :( ";
            } else {
                $result['excursions'] = $this->getRealEntities($excursions,"");
            }
        }else{
            $result['excursions'] = $excursionRepository->findAll();
        }
        return new Response(json_encode($result));

        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(array('data' => 'this is a json response'));
        }

        return new Response('This is not ajax!', 400);
    }

    /**
     * @Route("/dynamic_select_ville_excursion", name="dynamic_select_ville_excursion", methods={"POST"})
     */
    public function dynamic_select_ville_excursion(ExcursionRepository $excursionRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $result = [];
        $ville = $request->get('ville');
        if ($ville){
            $excursions = $excursionRepository->findBy(["ville" => $ville]);;
            if (!$excursions) {
                $result['excursions']['error'] = "Excursion Pas trouvé :( ";
            } else {
                $result['excursions'] = $this->getRealEntities($excursions,"");
            }
        }else{
            $result['excursions'] = $this->getRealEntities($excursionRepository->findAll());
        }
        return new Response(json_encode($result));
    }
    /**
     * @Route("/menushowexcursionbycategorie/{id}", name="menushowexcursionbycategorie", methods={"GET"})
     */
    public function menushowexcursionbycategorie(Excursioncategorie $excursioncategorie,Request $request, PaginatorInterface $paginator): Response
    {
        $excursion = $excursioncategorie->getExcursions();
        $ville = [];
        foreach ($excursion as $one){
            $ville[] = $one->getVille();
        }
        $excursions = $paginator->paginate(
            $excursion,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );
        $categories = $this->getDoctrine()->getRepository(Excursioncategorie::class)->findAll();
        return $this->render('excursion/front_index.html.twig', [
            'excursions' => $excursions,
            'villes' => $ville,
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/menushowexcursionbyville/{ville}", name="menushowexcursionbyville", methods={"GET"})
     */
    public function menushowexcursionbyville(Request $request, PaginatorInterface $paginator): Response
    {
//        $excursion = $excursioncategorie->getExcursions();
//        $ville = [];
//        foreach ($excursion as $one){
//            $ville[] = $one->getVille();
//        }
//        $excursions = $paginator->paginate(
//            $excursion,
//            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
//            1 // Nombre de résultats par page
//        );
//        $categories = $this->getDoctrine()->getRepository(Excursioncategorie::class)->findAll();
//        return $this->render('excursion/front_index.html.twig', [
//            'excursions' => $excursions,
//            'villes' => $ville,
//            'categories' => $categories
//        ]);

        $ville = $request->get('ville');
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $villes = [];
        $excursions = $excursionRepository->findAll();
        foreach ($excursions as $one){
            $villes[] = $one->getVille();
        }
        if ($ville){
            $excursions = $excursionRepository->findBy(["ville" => $ville]);;
        }
        $excursions = $paginator->paginate(
            $excursions,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );
        $categories = $this->getDoctrine()->getRepository(Excursioncategorie::class)->findAll();

        return $this->render('excursion/front_index.html.twig', [
            'excursions' => $excursions,
            'villes' => $villes,
            'categories' => $categories
        ]);
    }

}

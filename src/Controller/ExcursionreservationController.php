<?php

namespace App\Controller;

use App\Entity\Excursionreservation;
use App\Form\ExcursionreservationType;
use App\Repository\ExcursionreservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Flasher\Prime\FlasherInterface;
use Flasher\SweetAlert\Prime\SweetAlertFactory;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session;


class ExcursionreservationController extends AbstractController
{
    private $mailer;

    public function __construct( MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @Route("/excursionreservation/", name="excursionreservation_index", methods={"GET"})
     */
    public function index(ExcursionreservationRepository $excursionreservationRepository): Response
    {
        return $this->render('excursionreservation/index.html.twig', [
            'excursionreservations' => $excursionreservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/excursionreservation/new", name="excursionreservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $excursionreservation = new Excursionreservation();
        $form = $this->createForm(ExcursionreservationType::class, $excursionreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($excursionreservation);
            $entityManager->flush();
            $flasher->addSuccess('Ajouté avec succés!');
            return $this->redirectToRoute('excursionreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursionreservation/new.html.twig', [
            'excursionreservation' => $excursionreservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/excursionreservation/{id}", name="excursionreservation_show", methods={"GET"})
     */
    public function show(Excursionreservation $excursionreservation): Response
    {
        return $this->render('excursionreservation/show.html.twig', [
            'excursionreservation' => $excursionreservation,
        ]);
    }

    /**
     * @Route("/excursionreservation/{id}/edit", name="excursionreservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Excursionreservation $excursionreservation, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $form = $this->createForm(ExcursionreservationType::class, $excursionreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flasher->addSuccess('Modifié avec succés!');
            return $this->redirectToRoute('excursionreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('excursionreservation/edit.html.twig', [
            'excursionreservation' => $excursionreservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/excursionreservation/{id}", name="excursionreservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Excursionreservation $excursionreservation, EntityManagerInterface $entityManager, SweetAlertFactory $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete' . $excursionreservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($excursionreservation);
            $entityManager->flush();
            $flasher->addSuccess('Supprimé avec succès');
        }

        return $this->redirectToRoute('excursionreservation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin-dashboard/excursioncalendar", name="excursion_calendar", methods={"GET"})
     */
    public function calendar(Request $request, ExcursionreservationRepository $repository)
    {
        $events = $repository->findAll();

        $rdvs = [];

        foreach ($events as $event) {
            $color = "#249d1c";
            $status = "payé";
            if ($event->getStatus() == Excursionreservation::RESERVATION_EXCURSION_DEFAULT){
                $color = "#ff0000";
                $status = "non payé";
            }
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d'),
                'end' => $event->getEnd()->format('Y-m-d'),
                'title' => $status,
                'color' => $color
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('excursionreservation/calendar.html.twig', compact('data'));
    }

    /**
     * @Route("/checkoutexcursion", name="checkoutexcursion")
     */
    public function checkoutexcursion($stripeSK, ExcursionreservationRepository $repository): Response
    {
        $session_res = $this->get("session");
        $reservation_info = $session_res->get("excursionreservation");
        \Stripe\Stripe::setApiKey($stripeSK);

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $reservation_info->getExcursion()->getLibelle(),
                    ],
                    'unit_amount' => ($reservation_info->getPrix()) * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $entityManager = $this->getDoctrine()->getManager();
        $payment_intent = ($session->payment_intent);
        $reservation = $repository->find($reservation_info->getId());
        $reservation->setPi($payment_intent);
        $entityManager->flush();

        return $this->redirect($session->url, 303);
    }

    /**
     * @Route("/success_url", name="success_url")
     */
    public function success_url($stripeSK, FlasherInterface $flasher, ExcursionreservationRepository $repository,DompdfController $Dompdf): Response
    {
        $session_res = $this->get("session");
        $reservation_info = $session_res->get("excursionreservation");
        $reservation_detail = $repository->find($reservation_info->getId());
        $pi = $reservation_detail->getPi();
        $stripe = new \Stripe\StripeClient(
            $stripeSK
        );
        $info = $stripe->paymentIntents->retrieve(
            $pi,
            []
        );
        $recu_url = $info->charges->data[0]->receipt_url;

        $status = $info->status;
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $repository->find($reservation_info->getId());
        $reservation->setStatus($status);
        $entityManager->flush();
        if ($status == Excursionreservation::RESERVATION_EXCURSION_SUCCESS) {
            $user_connected = $this->getUser();
            if ($user_connected) {
                $lib_file = "Paiement.pdf";
                $Dompdf->generate_store($reservation_info,$lib_file);
                //send email
                try {
                    $email = new TemplatedEmail();
                    $email->subject("Notification paiement");
                    $email->from('amani.boussaa@esprit.tn');
                    $email->to($user_connected->getEmail());
                    $email->htmlTemplate('excursionpaiement/confirmation_email.html.twig');
                    $email->context(['recu_url'=>$recu_url,'username' => $user_connected->getFirstname() . " " . $user_connected->getLastname()]);
                    $email->attachFromPath($lib_file);
                    $this->mailer->send($email);
                    $flasher->addSuccess("Email envoyé, Verifier votre boite email svp");
                } catch (TransportExceptionInterface $e) {
                    $flasher->addError("Email non envoyé. ");
                }
            }
        }
        $flasher->addSuccess('Paiement effectué avec succés!');
        return $this->render('excursionpaiement/success.html.twig', [
            'controller_name' => 'ExcursionpaiementController',
        ]);
    }

    /**
     * @Route("/cancel_url", name="cancel_url")
     */
    public function cancel_url(): Response
    {
        return $this->render('excursionpaiement/cancel.html.twig', [
            'controller_name' => 'ExcursionpaiementController',
        ]);
    }
    /**
     * @Route("/reservation_index_client/", name="reservation_index_client", methods={"GET"})
     */
    public function reservation_index_client(ExcursionreservationRepository $excursionreservationRepository): Response
    {
        $reservation=[];
        $user = $this->getUser();
        if ($user){
            $reservation = $user->getExcursionreservations();
            return $this->render('excursionreservation/client_reservation.html.twig', [
                'client_reservations' => $reservation,
            ]);
        }
        return $this->render('excursionreservation/client_reservation.html.twig', [
            'client_reservations' => $reservation,
        ]);
    }

    /**
     * @Route("/admin-dashboard/remplir_modal_calendar", name="remplir_modal_calendar", methods={"GET"})
     */
    public function remplir_modal_calendar(Request $request)
    {
        $id_reservation = $request->query->get("id");
        $reservation = $this->getDoctrine()->getRepository(Excursionreservation::class)->find($id_reservation);
        $excursion = $reservation->getExcursion();
        $excursionlib = $excursion->getLibelle();
        $status = $reservation->getStatus();
        $user_exc = $reservation->getUser()->getFirstname();
        $start = $reservation->getStart()->format('Y-m-d');
        $color = "#249d1c";
        $status = "payé";
            if ($reservation->getStatus() == Excursionreservation::RESERVATION_EXCURSION_DEFAULT){
                $color = "#ff0000";
                $status = "non payé";
            }
        return new Response(
            '<dd  class="fw-bold fs-4 statusexcursion" style="background-color: '.$color.'">'.$status.'</dd>'.
            '<dt class="text-muted">Excursion</dt>'.
             '<dd >'.$excursionlib.'</dd>'.
            '<dt class="text-muted">Date</dt>'.
            '<dd >'.$start.'</dd>'.
            '<dt class="text-muted">Utilisateur</dt>'.
            '<dd >'.$user_exc.'</dd>'
        );
//        $events = $repository->findAll();
//
//        $rdvs = [];
//
//        foreach ($events as $event) {
//            $color = "#249d1c";
//            $status = "payé";
//            if ($event->getStatus() == Excursionreservation::RESERVATION_EXCURSION_DEFAULT){
//                $color = "#ff0000";
//                $status = "non payé";
//            }
//            $rdvs[] = [
//                'id' => $event->getId(),
//                'start' => $event->getStart()->format('Y-m-d'),
//                'end' => $event->getEnd()->format('Y-m-d'),
//                'title' => $status,
//                'color' => $color
//            ];
//        }
//
//        $data = json_encode($rdvs);
//
//        return $this->render('excursionreservation/calendar.html.twig', compact('data'));
    }
}

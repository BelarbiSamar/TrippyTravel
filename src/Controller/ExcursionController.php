<?php

namespace App\Controller;


use App\Entity\Excursion;
use App\Entity\Excursionimage;
use App\Entity\Excursionreservation;
use App\Form\ExcursionimageType;
use App\Form\ExcursionType;
use App\Repository\ExcursionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Prime\FlasherInterface;
use Flasher\SweetAlert\Prime\SweetAlertFactory;
use Flasher\Toastr\Prime\ToastrFactory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;



class ExcursionController extends AbstractController
{
    /**

     * @Route("admin-dashboard/excursion/", name="excursion_index", methods={"GET","POST"})
     */
    public function index(Request $request, ExcursionRepository $excursionRepository,ToastrFactory $flasher): Response
    {
        $excursions = $excursionRepository->findAll();
        $form = $this->createFormBuilder(null)
            ->add('query', TextType::class,[
                'required' => false,
                'label'=>false
            ])
            ->add('chercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $re = $request->get('form');
            $excursions = $excursionRepository->createQueryBuilder('e')
                ->where('e.libelle LIKE :lib')
                ->setParameter('lib','%'.$re['query'].'%')
                ->getQuery()
                ->getResult();
        }
        return $this->render('excursion/index.html.twig', [
            'excursions' => $excursions,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin-dashboard/excursion/new", name="excursion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlasherInterface $flasher): Response
    {
        $excursion = new Excursion();
        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productImages = $excursion->getExcursionimages();
            foreach($productImages as $key => $productImage){
                $productImage->setExcursion($excursion);
                $productImages->set($key,$productImage);
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
        foreach ($images as $item){
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
    public function edit(Request $request, Excursion $excursion, EntityManagerInterface $entityManager,FlasherInterface $flasher): Response
    {
        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productImages = $excursion->getExcursionimages();
            foreach($productImages as $key => $productImage){
                $productImage->setExcursion($excursion);
                $productImages->set($key,$productImage);
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
    public function delete(Request $request, Excursion $excursion, EntityManagerInterface $entityManager,SweetAlertFactory $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$excursion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($excursion);
            $entityManager->flush();
            $flasher->addSuccess('Supprimé avec succès');
        }
        return $this->redirectToRoute('excursion_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("excursion/", name="excursion_index_front", methods={"GET"})
     */
    public function index_front(ExcursionRepository $excursionRepository,Request $request, PaginatorInterface $paginator): Response
    {
            $excursions = $paginator->paginate(
                $excursionRepository->findAll(), // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('excursion/front_index.html.twig', [
            'excursions' => $excursions,
        ]);
    }

    /**
     * @Route("excursion/{id}", name="excursion_show_front", methods={"GET","POST"})
     */
    public function show_front(EntityManagerInterface $entityManager, Excursion $excursion,Request $request,FlasherInterface $flasher): Response
    {
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
            $excursionreservation->setPrix($excursion->getPrix());
            $excursion->addExcursionreservation($excursionreservation);
            $entityManager->flush();
            $flasher->addSuccess('Réservé avec succés!');
            return $this->redirectToRoute('excursion_index_front', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('excursion/show_front.html.twig', [
            'excursion' => $excursion,
            'form' => $form->createView(),
        ]);
    }

}

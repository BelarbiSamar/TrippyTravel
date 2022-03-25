<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExcursionRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Excursion;

class ApiExcursionController extends AbstractController
{
    /**
     * @Route("/api/excursion", name="api_excursion")
     */
    public function index(ExcursionRepository $excursionRepository,SerializerInterface $serializer): Response
    {
        $excursions = $excursionRepository->findAll();
        $json = $serializer->serialize($excursions,'json',['groups'=>'excursion']);
        return new Response($json);
    }
    /**
     * @Route("/api/excursion/new", name="api_excursion_new")
     */
    public function new(Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    {
        try {
            $content = $request->getContent();
            $data = $serializer->deserialize($content,Excursion::class,'json');
            $entityManager->persist($data);
            $entityManager->flush();
            return new Response("Excursion created successfully");
        }catch (\Exception $exception){
            return new Response($exception->getMessage());
        }
    }
}

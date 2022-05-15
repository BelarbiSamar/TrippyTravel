<?php

namespace App\Controller;

use App\Entity\Excursioncategorie;
//use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use App\Repository\ExcursionRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Excursion;
/**new ads**/
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;

class ApiExcursionController extends AbstractController
{
    /**
     * @Route("/allexcursionapi")
     * @Method("GET")
     */
    public function index()
    {
        try {
        $array1=[];
        $excursions = $this->getDoctrine()->getManager()->getRepository(Excursion::class)->findAll();
        foreach ($excursions as $key => $value) {
            $array["id"] =$value->getId();
            if ($value->getExcursionimages()[0]) {
                $array["image"] = "http://127.0.0.1:8000/uploads/images/excursion/".$value->getExcursionimages()[0]->getImageName();
            } else {
                $array["image"] = "http://localhost:8000/front-office/images/bg_4.jpg";
            }
            $array["libelle"] = $value->getLibelle();
            $array["description"] = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($value->getDescription()))) );
            $array["programme"] = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($value->getProgramme()))) );

            $array["ville"] = $value->getVille();
            $array["prix"] = $value->getPrix();
            $array["duration"] = $value->getDuration();
            $array["localisation"] = $value->getLocalisation();
            $array["comments"] = "";
            $array["excursioncategorie_id"] = $value->getExcursioncategorie()->getId();
            $array1[]=$array;
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($array1);
        return new JsonResponse($formatted);
        }catch (\Exception $exception){
           return new Response($exception->getMessage());
        }
    }
//    /**
//     * @Route("/api/excursion/new", name="api_excursion_new")
//     */
//    public function new(Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
//    {
//        try {
//            $content = $request->getContent();
//            $data = $serializer->deserialize($content,Excursion::class,'json');
//            $entityManager->persist($data);
//            $entityManager->flush();
//            return new Response("Excursion created successfully");
//        }catch (\Exception $exception){
//            return new Response($exception->getMessage());
//        }
//    }
    /******************Detail Excursion*****************************************/

    /**
     * @Route("/detailExcursionapi")
     * @Method("GET")
     */

    //Detail Excursion
    public function detailExcursionAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $excursion = $em->getRepository(Excursion::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($excursion);
        return new JsonResponse($formatted);
    }

    /******************Ajouter Excursion*****************************************/
    /**
     * @Route("/addExcursionapi")
     * @Method("POST")
     */

    public function ajouterExcursionAction(Request $request,SerializerInterface $serializer)
    {
        $excursion = new Excursion();
        $em = $this->getDoctrine()->getManager();
        $description = $request->query->get("description");
        $programme = $request->query->get("programme");
        $objet = $request->query->get("libelle");
        $ville = $request->query->get("ville");
        $prix = $request->query->get("prix");
        $duration = $request->query->get("duration");
        $excursioncategorie_id = $request->query->get("excursioncategorie_id");
        $cat = $em->getRepository(Excursioncategorie::class)->find($excursioncategorie_id);
        $excursion->setLibelle($objet);
        $excursion->setDescription($description);
        $excursion->setProgramme($programme);
        $excursion->setVille($ville);
        $excursion->setPrix($prix);
        $excursion->setDuration($duration);
        $excursion->setExcursioncategorie($cat);
        $em->persist($excursion);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($excursion);
        return new JsonResponse($formatted);
    }

    /******************Supprimer Excursion*****************************************/

    /**
     * @Route("/deleteExcursionapi")
     * @Method("DELETE")
     */

    public function deleteExcursionAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $excursion = $em->getRepository(Excursion::class)->find($id);
        if($excursion!=null ) {
            $em->remove($excursion);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Excursion a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id excursion invalide.");


    }

    /******************Modifier Excursion*****************************************/
    /**
     * @Route("/updateExcursionapi")
     * @Method("PUT")
     */

    public function modifierExcursionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $excursion = $this->getDoctrine()->getManager()
            ->getRepository(Excursion::class)
            ->find($request->get("id"));

        if ($request->get("description")){
            $excursion->setDescription($request->get("description"));
        }
        if ($request->get("libelle")){

            $excursion->setLibelle($request->get("libelle"));
        }
        if ($request->get("prix")){
            $excursion->setPrix($request->get("prix"));
        }
        if ($request->get("programme")){
            $excursion->setProgramme($request->get("programme"));
        }
        if ($request->get("duration")){
            $excursion->setDuration($request->get("duration"));
        }
        if ($request->get("categorie")){
            $excursion->setExcursioncategorie($request->get("categorie"));
        }
        if ($request->get("ville")){
            $excursion->setVille($request->get("ville"));
        }
        //$excursion->setLibelle($request->get("libelle"));
//        $excursion->setPrix($request->get("prix"));
//        $excursion->setProgramme($request->get("programme"));
//        $excursion->setDuration($request->get("duration"));
//        $excursion->setExcursioncategorie($request->get("categorie"));

        $em->persist($excursion);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($excursion);
        return new JsonResponse("Excursion a ete modifiee avec success.");

    }
}
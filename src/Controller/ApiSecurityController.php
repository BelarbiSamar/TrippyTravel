<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as NormalizerObjectNormalizer;
use Symfony\Component\Serializer\Serializer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiSecurityController extends AbstractController{
    
    
    /*public function apilogin() {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }*/

    /**
     * @Route("user/signup", name="api_register")
     */
    public function signupAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $firstname = $request->query->get("firstname");
        $lastname = $request->query->get("lastname");
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("email invalid");
        }

        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $user->setIsVerified(true);
        $user->setRoles(array("ROLE_CLIENT"));

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse("account is created",200);
        }catch(\Exception $ex) {
            return new  Response("exception".$ex->getMessage());
        }
    }

    /**
     * @Route("user/signin", name="api_login")
     */
    public function signinAction (Request $request) {
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);

        if($user) {
            if(password_verify($password, $user->getPassword())) {
                $serializer = new Serializer([new NormalizerObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {
                return new Response("password incorrect");
            } 
        }
        else {
            return new Response("user not found");
        }
    }
}
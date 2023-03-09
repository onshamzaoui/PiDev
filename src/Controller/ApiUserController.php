<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;





class ApiUserController extends AbstractController
{
    #[Route('/api/user/signup', name: 'app_api_register')]
    public function signupAction(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {

        $emailUser=$request->query->get('emailUser');
        $nomUser=$request->query->get('nomUser');
        $passwordUser=$request->query->get('passwordUser');
        $adresseUser=$request->query->get('adresseUser');
        $roleUser=$request->query->get('roleUser');
        if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
            return new Response("email invalid.");    
        }
        $user = new User();
        $user->setEmailUser($emailUser);
        $user->setNomUser($nomUser);
        $user->setPasswordUser(
            $userPasswordHasher->hashPassword(
                $user,
                $passwordUser,            )
        );
        // $user->setPasswordUser($passwordUser);
        $user->setAdresseUser($adresseUser);
        $user->setIsVerified(true);
        $user->setRoleUser(array($roleUser));

        try {
            // $em= $this->getDoctrine()->getManager();
            // $em->persist($user);
            // $em->flush();
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse("Acount is created",200);
        } catch (\Exception $ex) {
            return new Response("exception".$ex->getMessage());
        }
    //     return $this->render('api_user/index.html.twig', [
    //         'controller_name' => 'ApiUserController',
    //     ]);
    }
    #[Route('/api/user/signin', name: 'app_api_login')]
    public function signinAction(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $emailUser=$request->query->get('emailUser');
        $passwordUser=$request->query->get('passwordUser');
        $user = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $emailUser]);
        if($user){

            if(password_verify($passwordUser,$user->getPasswordUser())){
                $serializer=new Serializer([new ObjectNormalizer()]);
                $formatted= $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {
                return new Response("paswword not found");
            }
        }
        else {
            return new Response("user not found");
        }
    }
    #[Route('/api/user/edit', name: 'app_api_gestion_profile')]
    public function editUser(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
       $id=$request->get('id');       
       $emailUser=$request->query->get('emailUser');
       $nomUser=$request->query->get('nomUser');
       $passwordUser=$request->query->get('passwordUser');
       $adresseUser=$request->query->get('adresseUser');
       $user = $entityManager->getRepository(User::class)->find($id);

       $user->setEmailUser($emailUser);
       $user->setNomUser($nomUser);
       $user->setPasswordUser(
           $userPasswordHasher->hashPassword(
               $user,
               $passwordUser,            )
       );
       $user->setAdresseUser($adresseUser); 
       try {
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse("success",200);
    } catch (\Exception $ex) {
        return new Response("fail".$ex->getMessage());
    } 
    }
}

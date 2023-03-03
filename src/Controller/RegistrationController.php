<?php

namespace App\Controller;

use App\Entity\Speciality;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\SpecialityRepository;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;



class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, SpecialityRepository $specialityRepository ,  UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $user = new User();
        // $user->setRoleUser(['ROLE_PRO']); 
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        // die('User saved to database!');
        
        if ($form->isSubmitted() ) {

        //    if($request->isMethod('post')){
           
           
            //encode the plain password
                 $user->setPasswordUser(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // dd($request->get("registration_form"));
            $user->setEmailUser($request->get("registration_form")["emailUser"]);
            $user->setNomUser($request->get("registration_form")["nomUser"]);
            $user->setAdresseUser($request->get("registration_form")["adresseUser"]);
            $spec = $specialityRepository->findOneBy(["id" => $request->get("registration_form")["speciality"]]);
            // $request->get("registration_form")["speciality"]
            $user->setSpeciality($spec);

            $role[] ="ROLE_PRO";
            $user->setRoleUser($role);



            $entityManager->persist($user);
            $entityManager->flush();



            // // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('onshamzaoui720@gmail.com', 'GreenItBuildings'))
                    ->to($user->getEmailUser())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // // do anything else you need here, like send an email

          return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
              $request
         );
        //  return $this->redirectToRoute('app_login');
         // Debug message
    
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/registerClient', name: 'app_registerClient')]
    public function registerClient(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // $user->setRoleUser(['ROLE_CLIENT']); 
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) 
        if ($form->isSubmitted() ){
            // encode the plain password
             $user->setPasswordUser(
                 $userPasswordHasher->hashPassword(
                     $user,
                    $form->get('plainPassword')->getData()
                 )
             );

             $user->setEmailUser($request->get("registration_form")["emailUser"]);
             $user->setNomUser($request->get("registration_form")["nomUser"]);
             $user->setAdresseUser($request->get("registration_form")["adresseUser"]);
            // $user->setEmailUser($request->get("email_user"));
            // $user->setNomUser($request->get("nom_user"));
            // $user->setAdresseUser($request->get("adresse_user"));

            


            $role[] = "ROLE_CLIENT";
             $user->setRoleUser($role);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('onshamzaoui720@gmail.com', 'GreenItBuildings'))
                    ->to($user->getEmailUser())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            // return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/registerClient.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
